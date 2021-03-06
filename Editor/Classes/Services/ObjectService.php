<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ObjectService {

  static function getLatestId($type) {
    $sql = "select max(id) as id from object where type = @text(type)";
    if ($row = Database::selectFirst($sql, ['type' => $type])) {
      return intval($row['id']);
    }
    return null;
  }

  static function getValidIds($ids) {
    if (count($ids) == 0) {
      return [];
    }
    $sql = "select id from object where id in @ints(ids)";
    return Database::getIds($sql, ['ids' => $ids]);
  }

  static function getInstance($type) {
    if (!$type) {
      return null;
    }
    ObjectService::importType($type);
    $class = ucfirst($type);
    if (class_exists($class,true)) {
      return new $class;
    }
    return null;
  }

  static function getObjectData($id) {
    $data = null;
    if ($id) {
      $sql = "select data from object where id = @id";
      if ($row = Database::selectFirst($sql, $id)) {
        $data = $row['data'];
      }
    }
    return $data;
  }

  static function importType($type) {
    global $basePath;
    $class = ucfirst($type);
    if (class_exists($class,false)) {
      return true;
    }
    $path = $basePath . 'Editor/Classes/Objects/' . $class . '.php';
    if (!file_exists($path)) {
      $path = $basePath . 'Editor/Classes/' . $class . '.php';
      if (!file_exists($path)) {
        return false;
      }
    }
    require_once $path;
    return true;
  }

  static function addRelation($fromObject,$toObject,$kind = '') {
    $sql = "insert into relation (from_object_id,to_object_id,kind) values (@int(from), @int(to), @text(kind))";
    Database::insert($sql, ['from' => $fromObject->getId(), 'to' => $toObject->getId(), 'kind' => $kind]);
  }

  static function removeRelations($objectId) {
    $sql = "delete from `relation` where (from_type = 'object' and from_object_id = @int(objectId)) or (to_type = 'object' and to_object_id = @int(objectId))";
    Database::delete($sql, ['objectId' => $objectId]);
  }

  static function remove($object) {
    if ($object->isPersistent() && $object->canRemove()) {
      $sql = "delete from `object` where id = @id";
      $row = Database::delete($sql, $object->getId());

      $sql = "delete from `object_link` where object_id = @id";
      Database::delete($sql, $object->getId());
      ObjectService::removeRelations($object->getId());

      $schema = ObjectService::_getSchemaProperties($object->getType());
      if (is_array($schema)) {
        $sql = "delete from @name(table) where object_id = @id";
        Database::delete($sql, ['table' => $object->getType(), 'id' => $object->getId()]);
        if (method_exists($object,'removeMore')) {
          $object->removeMore();
        }
      }
      EventService::fireEvent('delete','object',$object->getType(),$object->getId());
      return true;
    }
    return false;
  }

  static function isChanged($id) {
    $sql = "select updated-published as delta from object where id = @id";
    $row = Database::selectFirst($sql, $id);
    if ($row['delta'] > 0) {
      return true;
    }
    return false;
  }

  static function publish($object) {
    if (!$object->isPersistent()) {
      Log::warn('Try to publish something not persistent');
      return;
    }
    $index = $object->getIndex();
    $xml = $object->getCurrentXml();
    $sql = "update `object` set data = @text(data),`index` = @text(index), published=now() where id = @id";
    Database::update($sql, ['data' => $xml, 'index' => $index, 'id' => $object->getId()]);
    EventService::fireEvent('publish','object',$object->getType(),$object->getId());
  }

  static function loadAny($id) {
    $sql = "select type from object where id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      $unique = ucfirst($row['type']);
      if (!$unique) {
        Log::debug('Unable to load object by id: ' . $id);
        return null;
      }
      ObjectService::importType($unique);
      $class = new $unique;
      $object = $class->load($id);
      return $object;
    }
    return null;
  }


  static function load($id,$type) {
    return ModelService::load(ucfirst($type),$id);
  }

  static function _getSchema($type) {
    if (array_key_exists(ucfirst($type),Entity::$schema)) {
      return Entity::$schema[ucfirst($type)];
    }
  }

  static function _getSchemaProperties($type) {
    if (array_key_exists(ucfirst($type),Entity::$schema)) {
      return Entity::$schema[ucfirst($type)]['properties'];
    }
  }

  static function create($object) {
    if ($object->isPersistent()) {
      Log::debug('Tried creating object already persisted...');
      Log::debug($object);
      return false;
    }
    if (!$object->isValid()) {
      Log::debug('Tried creating invalid object...');
      Log::debug($object);
      return false;
    }
    $sql = [
      'table' => 'object',
      'values' => [
        'title' => ['text' => $object->title],
        'type' => ['text' => $object->type],
        'note' => ['text' => $object->note],
        'searchable' => ['boolean' => $object->searchable],
        'owner_id' => ['int' => $object->ownerId],
        'updated' => ['datetime' => time()],
        'created' => ['datetime' => time()]
      ]
    ];
    $object->id = Database::insert($sql);
    $schema = ObjectService::_getSchemaProperties($object->getType());
    if (is_array($schema)) {
      $sql = [
        'table' => $object->type,
        'values' => [
          'object_id' => ['int' => $object->id]
        ]
      ];
      foreach ($schema as $property => $info) {
        $column = SchemaService::getColumn($property,$info);
        if (@$info['type'] == 'int') {
          $sql['values'][$column] = ['int' => $object->$property];
        } else if (@$info['type'] == 'float') {
          $sql['values'][$column] = ['float' => $object->$property];
        } else if (@$info['type'] == 'datetime') {
          $sql['values'][$column] = ['datetime' => $object->$property];
        } else if (@$info['type'] == 'boolean') {
          $sql['values'][$column] = ['boolean' => $object->$property];
        } else {
          $sql['values'][$column] = ['text' => $object->$property];
        }
      }
      Database::insert($sql);
    }
    else if (method_exists($object,'sub_create')) {
      $object->sub_create();
    }
    EventService::fireEvent('create','object',$object->type,$object->id);
    return true;
  }


  static function update($object) {
    if (!$object->isPersistent()) {
      Log::debug('Tried updating object not persisted...');
      Log::debug($object);
      return false;
    }
    if (!$object->isValid()) {
      Log::debug('Tried saving invalid object...');
      Log::debug($object);
      return false;
    }
    $sql = [
      'table' => 'object',
      'values' => [
        'title' => ['text' => $object->getTitle()],
        'note' => ['text' => $object->getNote()],
        'searchable' => ['boolean' => $object->searchable],
        'owner_id' => ['int' => $object->ownerId],
        'updated' => ['datetime' => time()]
      ],
      'where' => [
        'id' => ['int' => $object->id]
      ]
    ];
    Database::update($sql);

    $schema = ObjectService::_getSchemaProperties($object->getType());
    if (is_array($schema)) {
      $values = SchemaService::buildSqlValueStructure($object,$schema);
      if ($values) {
        $sql = [
          'table' => $object->getType(),
          'values' => $values,
          'where' => ['object_id' => ['int' => $object->id]]
        ];
        Database::update($sql);
      }
    }
    EventService::fireEvent('update','object',$object->type,$object->id);
    return true;
  }

  static function toXml($object) {
    $ns = 'http://uri.in2isoft.com/onlinepublisher/class/object/1.0/';
    $xml = '<object xmlns="' . $ns . '" id="' . $object->id . '" type="' . $object->type . '">' .
      '<title>' . Strings::escapeEncodedXML($object->title) . '</title>' .
      '<note>' . Strings::escapeXMLBreak($object->note,'<break/>') . '</note>' .
      Dates::buildTag('created',$object->created) .
      Dates::buildTag('updated',$object->updated) .
      Dates::buildTag('published',$object->published);

    $links = '';

    $sql = "select object_link.*,page.path from object_link left join page on page.id=object_link.target_value and object_link.target_type='page' where object_id = @id order by position";
    $result = Database::select($sql, $object->id);
    while ($row = Database::next($result)) {
      $links .= '<link title="' . Strings::escapeEncodedXML($row['title']) . '"';
      if ($row['alternative'] != '') {
        $links .= ' alternative="' . Strings::escapeEncodedXML($row['alternative']) . '"';
      }
      if ($row['target'] != '') {
        $links .= ' target="' . Strings::escapeEncodedXML($row['target']) . '"';
      }
      if ($row['path'] != '') {
        $links .= ' path="' . Strings::escapeEncodedXML($row['path']) . '"';
      }
      if ($row['target_type'] == 'page') {
        $links .= ' page="' . Strings::escapeEncodedXML($row['target_value']) . '"';
      }
      elseif ($row['target_type'] == 'file') {
        $links .= ' file="' . Strings::escapeEncodedXML($row['target_value']) . '" filename="' . Strings::escapeEncodedXML(ObjectService::_getFilename($row['target_value'])) . '"';
      }
      elseif ($row['target_type'] == 'url') {
        $links .= ' url="' . Strings::escapeEncodedXML($row['target_value']) . '"';
      }
      elseif ($row['target_type'] == 'email') {
        $links .= ' email="' . Strings::escapeEncodedXML($row['target_value']) . '"';
      }
      $links .= '/>';
    }
    Database::free($result);

    if ($links != '') {
      $xml .= '<links>' . $links . '</links>';
    }
    $xml .= '<sub>';
    if (method_exists($object,'sub_publish')) {
      $xml .= $object->sub_publish();
    }
    $xml .= '</sub>' .
      '</object>';
    return $xml;
  }

  static function _getFilename($id) {
    $sql = "select filename from file where object_id = @id";
    if ($row = Database::selectFirst($sql, $id)) {
      return $row['filename'];
    }
    return null;
  }


  /**
   * @param query Query
   */
  static function search($query) {
    $parts = [
      'type' => $query->getType(),
      'query' => $query->getText(),
      'fields' => $query->getFields(),
      'ordering' => $query->getOrdering(),
      'direction' => $query->getDirection(),
      'tables' => [],
      'parts' => []
    ];
    if ($query->getWindowPage() !== null) {
      $parts['windowPage'] = $query->getWindowPage();
    }
    if ($query->getWindowSize() !== null) {
      $parts['windowSize'] = $query->getWindowSize();
    }
    if ($query->getCreatedMin() !== null) {
      $parts['createdMin'] = $query->getCreatedMin();
    }
    $ids = $query->getIds();
    if (is_array($ids) && count($ids) > 0) {
      $limit = 'object.id in (';
      for ($i = 0; $i < count($ids); $i++) {
        if ($i > 0) {
          $limit .= ',';
        }
        $limit .= Database::int($ids[$i]);
      }
      $limit .= ')';
      $parts['limits'][] = $limit;
    }
    if (count($query->getRelationsFrom()) > 0) {
      $relations = $query->getRelationsFrom();
      for ($i = 0; $i < count($relations); $i++) {
        $relation = $relations[$i];
        $parts['tables'][] = 'relation as relation_from_' . $i;
        $parts['limits'][] = 'relation_from_' . $i . '.to_object_id=object.id';
        $parts['limits'][] = "relation_from_" . $i . ".to_type='object'";
        $parts['limits'][] = "relation_from_" . $i . ".from_type=" . Database::text($relation['fromType']);
        $parts['limits'][] = 'relation_from_' . $i . '.from_object_id=' . Database::int($relation['id']);
        if ($relation['kind']) {
          $parts['limits'][] = "relation_from_" . $i . ".kind=" . Database::text($relation['kind']);
        }
      }
    }
    if (count($query->getRelationsTo()) > 0) {
      $relations = $query->getRelationsTo();
      for ($i = 0; $i < count($relations); $i++) {
        $relation = $relations[$i];
        $parts['tables'][] = 'relation as relation_to_' . $i;
        $parts['limits'][] = 'relation_to_' . $i . '.from_object_id=object.id';
        $parts['limits'][] = "relation_to_" . $i . ".from_type='object'";
        $parts['limits'][] = "relation_to_" . $i . ".to_type=" . Database::text($relation['toType']);
        $parts['limits'][] = 'relation_to_' . $i . '.to_object_id=' . Database::int($relation['id']);
        if ($relation['kind']) {
          $parts['limits'][] = "relation_to_" . $i . ".kind=" . Database::text($relation['kind']);
        }
      }
    }

    if ($class = ObjectService::getInstance($query->getType())) {
      if (method_exists($class,'addCustomSearch')) {
        $class->addCustomSearch($query,$parts);
      }
    } else {
      Log::debug('Unable to get class for type=' . $query->getType());
      return new SearchResult();
    }
    $x = ObjectService::_search($parts);
    $result = new SearchResult();
    $result->setList($x['result']);
    $result->setTotal($x['total']);
    $result->setWindowPage($x['windowPage']);
    $result->setWindowSize($x['windowSize']);

    return $result;
  }

  static function _search($query = []) {
    $type = $query['type'];
    if (!$type) {
      return null;
    }
    $schema = ObjectService::_getSchema($type);
    $properties = $schema['properties'];
    if (!is_array($properties)) {
      Log::debug('Unable to find schema for: ' . $type);
    }
    $table = $schema['table'];
    $parts = [
      // It is important to name type "object_type" since the image class also has a column named type
      'columns' => 'object.id,object.title,object.note,object.type as object_type,object.owner_id,UNIX_TIMESTAMP(object.created) as created,UNIX_TIMESTAMP(object.updated) as updated,UNIX_TIMESTAMP(object.published) as published,object.searchable',
      'tables' => 'object,`' . $table . '`',
      'ordering' => ['object.title'],
      'limits' => [
        '`' . $table . '`.object_id=object.id'
      ],
      'joins' => []
    ];
    if (isset($query['ordering'])) {
      $parts['ordering'] = $query['ordering'];
    }
    if (isset($query['direction'])) {
      $parts['direction'] = $query['direction'];
    }
    if (isset($query['limits']) && is_array($query['limits'])) {
      $parts['limits'] = array_merge($parts['limits'],$query['limits']);
    }
    if (isset($query['joins']) && is_array($query['joins'])) {
      $parts['joins'] = array_merge($parts['joins'],$query['joins']);
    }
    if (isset($query['fields']) && is_array($query['fields'])) {
      foreach ($query['fields'] as $field => $value) {
        if (isset($properties[$field])) {
          $column = $field;
          if (isset($properties[$field]['column'])) {
            $column = $properties[$field]['column'];
          }
          if ($properties[$field]['type'] == 'datetime') {
            if (is_array($value)) {
              $parts['limits'][] = '`' . $table . '`.`' . $column . '`>=' . Database::datetime($value['from']);
              $parts['limits'][] = '`' . $table . '`.`' . $column . '`<=' . Database::datetime($value['to']);
            } else {
              $parts['limits'][] = '`' . $table . '`.`' . $column . '`=' . Database::datetime($value);
            }
          } else {
            if (is_array($value)) {
              if (@$value['comparison'] == 'not') {
                $parts['limits'][] = '`' . $table . '`.`' . $column . '`!=' . Database::text($value['value']);
              } else {
                $parts['limits'][] = '`' . $table . '`.`' . $column . '`=' . Database::text($value['value']);
              }
            } else {
              $parts['limits'][] = '`' . $table . '`.`' . $column . '`=' . Database::text($value);
            }
          }
        } else {
          $parts['limits'][] = '`' . $table . '`.`' . $field . '`=' . Database::text($value);
        }
      }
    }
    if (isset($query['tables']) && is_array($query['tables']) && count($query['tables']) > 0) {
      $parts['tables'] .= ',' . implode(',',$query['tables']);
    }
    if (isset($query['query'])) {
      $words = preg_split("/[\s,]+/", $query['query']);
      foreach ($words as $word) {
        if ($word != '') {
          $parts['limits'][] = '`index` like ' . Database::search($word);
        }
      }
    }
    if (isset($query['createdMin'])) {
      $parts['limits'][] = '`object`.`created` > ' . Database::datetime($query['createdMin']);
    }
    foreach ($properties as $property => $info) {
      $column = $property;
      if (isset($info['column'])) {
        $column = $info['column'];
      }
      if (@$info['type'] == 'datetime') {
        $parts['columns'] .= ",UNIX_TIMESTAMP(`$table`.`$column`) as `$column`";
      } else {
        $parts['columns'] .= ",`$table`.`$column`";
      }
    }
    $list = ObjectService::_find($parts,$query);

    ObjectService::importType($type);
    $class = ucfirst($type);
    foreach ($list['rows'] as $row) {
      $obj = new $class;
      $obj->id = intval($row['id']);
      $obj->title = $row['title'];
      $obj->created = intval($row['created']);
      $obj->updated = intval($row['updated']);
      $obj->published = intval($row['published']);
      $obj->type = $row['object_type'];
      $obj->note = $row['note'];
      $obj->ownerId = intval($row['owner_id']);
      $obj->searchable = ($row['searchable'] == 1);
      foreach ($properties as $property => $info) {
        $column = SchemaService::getColumn($property,$info);
        $obj->$property = SchemaService::getRowValue(@$info['type'],@$row[$column]);
      }
      $list['result'][] = $obj;
    }
    return $list;
  }

  static function findAny($query = []) {
    $parts = [];
    $parts['columns'] = 'object.id,object.type';
    $parts['tables'] = 'object';
    $parts['limits'] = [];
    $parts['ordering'] = 'object.title';
    $parts['direction'] = $query['direction'];

    if ($query['sort'] == 'title') {
      $parts['ordering'] = "object.title";
    } else if ($query['sort'] == 'type') {
      $parts['ordering'] = "object.type";
    } else if ($query['sort'] == 'updated') {
      $parts['ordering'] = "object.updated";
    }
    if (isset($query['type'])) {
      $parts['limits'][] = 'object.type=' . Database::text($query['type']);
    }
    if (isset($query['query'])) {
      $parts['limits'][] = '`index` like ' . Database::search($query['query']);
    }
    $list = ObjectService::_find($parts,$query);
    $list['result'] = [];
    foreach ($list['rows'] as $row) {
      if ($row['type'] == '') {
        error_log('Could not load ' . $row['id'] . ' it has no type');
        continue;
      }
      $className = ucfirst($row['type']);
      ObjectService::importType($row['type']);
      $class = new $className;
      $object = $class->load($row['id']);
      if ($object) {
        $list['result'][] = $object;
      } else {
        error_log('Could not load ' . $row['id']);
      }
    }
    return $list;
  }

  static function _find($parts, $query, $parameters = []) {
    $list = ['result' => [], 'rows' => [], 'windowPage' => 0, 'windowSize' => 0, 'total' => 0];

    $sql = "select " . $parts['columns'] . " from " . $parts['tables'];
    if (isset($parts['joins']) && is_array($parts['joins']) && count($parts['joins']) > 0) {
      $sql .= " " . implode(' ',$parts['joins']);
    }
    if (isset($parts['limits']) && is_array($parts['limits']) && count($parts['limits']) > 0) {
      $sql .= " where " . implode(' and ',$parts['limits']);
    }
    if (is_string($parts['limits']) && strlen($parts['limits']) > 0) {
      $sql .= " where " . $parts['limits'];
    }
    if (isset($parts['ordering']) && !empty($parts['ordering'])) {
      $ordering = is_array($parts['ordering']) ? $parts['ordering'] : [$parts['ordering']];
      $dir = '';
      if (isset($parts['direction'])) {
        if ($parts['direction'] == 'descending') {
          $dir = ' desc';
        } else if ($parts['direction'] == 'ascending') {
          $dir = ' asc';
        }
      }
      for ($i = 0; $i < count($ordering); $i++) {
        $ord = $ordering[$i];
        if ($i == 0) {
          $sql .= " order by ";
        } else {
          $sql .= ",";
        }
        $sql .= $ord . $dir;
      }
    }
    $start = 0;
    $end = 1000;
    if (isset($query['windowSize']) && isset($query['windowPage'])) {
      $start = ($query['windowPage']) * $query['windowSize'];
      $end = ($query['windowPage'] + 1) * $query['windowSize'] + 1;
      $list['windowPage'] = $query['windowPage'];
      $list['windowSize'] = $query['windowSize'];
    }
    $num = 1;
    $size = 0;
    $result = Database::select($sql, $parameters);
    $list['total'] = Database::size($result);
    while ($row = Database::next($result)) {
      if ($num >= $start && $num < $end) {
        $list['rows'][] = $row;
        $size++;
      }
      $num++;
    }
    Database::free($result);
    return $list;
  }
}