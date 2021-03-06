<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
Entity::$schema['Hierarchy'] = [
  'table' => 'hierarchy',
  'properties' => [
    'id' => ['type' => 'int'],
    'name' => ['type' => 'string'],
    'language' => ['type' => 'string'],
    'changed' => ['type' => 'datetime'],
    'published' => ['type' => 'datetime']
  ]
];
class Hierarchy extends Entity implements Loadable {

  var $name;
  var $language;
  var $changed;
  var $published;

  function setName($name) {
    $this->name = $name;
  }

  function getName() {
    return $this->name;
  }

  function setLanguage($language) {
    $this->language = $language;
  }

  function getLanguage() {
    return $this->language;
  }

  function setChanged($changed) {
    $this->changed = $changed;
  }

  function getChanged() {
    return $this->changed;
  }

  function setPublished($published) {
    $this->published = $published;
  }

  function getPublished() {
    return $this->published;
  }



    //////////////////// Special ////////////////////

  function canDelete() {
    return HierarchyService::canDeleteHierarchy($this->id);
  }

    ////////////////// Persistence //////////////////

  static function load($id) {
    return ModelService::load('Hierarchy', $id);
  }

  static function loadFromItemId($id) {
    $sql = "select hierarchy_id from hierarchy_item where id=@int(id)";
    if ($row = Database::selectFirst($sql, ['id' => $id])) {
      return Hierarchy::load($row['hierarchy_id']);
    }
    return null;
  }

  static function _populate(&$row) {
    $hier = new Hierarchy();
    $hier->setId($row['id']);
    $hier->setName($row['name']);
    $hier->setLanguage($row['language']);
    $hier->setPublished($row['published']);
    $hier->setChanged($row['changed']);
    return $hier;
  }

  static function search() {
    $out = [];
    $sql = "select id,name,language,UNIX_TIMESTAMP(changed) as changed,UNIX_TIMESTAMP(published) as published from hierarchy order by name";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $out[] = Hierarchy::_populate($row);
    }
    Database::free($result);
    return $out;
  }

  function create() {
    HierarchyService::createHierarchy($this);
  }

  function remove() {
    return $this->delete();
  }

  function delete() {
    if ($this->canDelete()) {
      $sql = 'delete from hierarchy where id=@int(id)';
      return Database::delete($sql, ['id' => $this->id]);
    } else {
      Log::debug('The hierarchy cannot be deleted');
      return false;
    }
  }

  function save() {
    if ($this->id > 0) {
      $this->update();
    } else {
      $this->create();
    }
  }

  function update() {
    HierarchyService::updateHierarchy($this);
  }

  function createItemForPage($pageId,$title,$parentId) {
    return $this->createItem(['targetType' => 'page', 'targetValue' => $pageId, 'title' => $title, 'parent' => $parentId, 'hidden' => false]);
  }

  function createItem($options) {
    $options['hierarchyId'] = $this->id;
    return HierarchyService::createItem($options);
  }

  function markChanged() {
    HierarchyService::markHierarchyChanged($this->id);
  }

    /////////////////// Publishing //////////////////

  function publish($allowDisabled = false) {
    $data = $this->build($this->id,$allowDisabled);
    $sql = "update hierarchy set published=now(),data=@text(data) where id=@int(id)";
    if (Database::update($sql, ['id' => $this->id, 'data' => $data])) {
      EventService::fireEvent('publish','hierarchy',null,$this->id);
      return true;
    }
    return false;
  }

  static function build($id,$allowDisabled = true) {
    return '<hierarchy xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/">' .
    HierarchyService::hierarchyTraveller($id,0,$allowDisabled) .
    '</hierarchy>';
  }


  ///////////////////// Static ////////////////////

  static function getItemIcon($type) {
    $icons = ['page' => 'common/page', 'pageref' => 'common/pagereference', 'email' => 'common/email', 'url' => 'monochrome/globe', 'file' => 'monochrome/file'];
    return $icons[$type];
  }

  static function moveItem($id,$dir) {
    if ($dir < 0) {
      $dir = -1;
    } else {
      $dir = 1;
    }
    $output = false;
    $sql = "select * from hierarchy_item where id=@int(id)";
    if ($row = Database::selectFirst($sql, ['id' => $id])) {
      $index = $row['index'];
      $hierarchyId = $row['hierarchy_id'];
      $parent = $row['parent'];

      $sql = "select id from hierarchy_item where parent=@int(parent) and hierarchy_id=@int(hierarchy) and `index`=@int(index)";
      if ($row = Database::selectFirst($sql, [
        'parent' => $parent,
        'hierarchy' => $hierarchyId,
        'index' => $index + $dir
      ])) {
        $otherid = $row['id'];

        $sql = "update hierarchy_item set `index`=@int(index) where id=@int(id)";
        Database::update($sql, ['index' => $index + $dir, 'id' => $id]);
        Database::update($sql, ['index' => $index, 'id' => $otherid]);

        HierarchyService::markHierarchyChanged($hierarchyId);
        $output = true;
        EventService::fireEvent('update', 'hierarchy', null, $hierarchyId);
      }
      Hierarchy::fixLevel($parent, $hierarchyId);
    } else {
      error_log('could not load: ' . $sql);
    }
    return $output;
  }

  static function deleteItem($id) {
    return HierarchyService::deleteItem($id);
  }

  static function getAncestorPath($parent) {
    $output = [];
    while ($parent > 0) {
      $sql = "select id, parent from hierarchy_item where id=@int(id)";
      if ($row = Database::selectFirst($sql, ['id' => $parent])) {
        $output[] = intval($row['id']);
        $parent = $row['parent'];
      } else {
        $parent = 0;
      }
    }
    return array_reverse($output);
  }

  static function getItemPath($parent) {
    $output = [];
    while ($parent > 0) {
      $sql = "select id,parent,title from hierarchy_item where id=@int(id)";
      if ($row = Database::selectFirst($sql, ['id' => $parent])) {
        $output[] = ['type' => 'item', 'id' => $row['id'], 'title' => $row['title']];
        $parent = $row['parent'];
      } else {
        $parent = 0;
      }
    }
    $sql = "select hierarchy.id,hierarchy.name
      from hierarchy, hierarchy_item
      where hierarchy.id = hierarchy_item.hierarchy_id
      and hierarchy_item.id = @int(id)";
    if ($row = Database::selectFirst($sql, ['id' => $parent])) {
      $output[] = ['type' => 'hierarchy', 'id' => $row['id'], 'title' => $row['name']];
    }
    return array_reverse($output);
  }

  static function markHierarchyOfItemChanged($id) {
    $sql = "update hierarchy,hierarchy_item
      set hierarchy.changed = now()
      where hierarchy.id = hierarchy_item.hierarchy_id
      and hierarchy_item.id = @int(id)";
    Database::update($sql, ['id' => $id]);
  }

  static function markHierarchyOfPageChanged($id) {
    $sql = "update hierarchy,hierarchy_item
      set hierarchy.changed=now()
      where hierarchy_item.hierarchy_id=hierarchy.id
      and hierarchy_item.target_id = @int(id)
      and (target_type in ('page','pageref'))";
    Database::update($sql, ['id' => $id]);
  }

  static function relocateItem($move,$targetItem = 0,$targetHierarchy = 0) {

    // Get info about hierarchy item
    $sql = "select * from hierarchy_item where id=@int(id)";
    if ($row = Database::selectFirst($sql, ['id' => $move])) {
      $moveHierarchy = $row['hierarchy_id'];
      $moveParent = $row['parent'];
    } else {
      return ['success' => false, 'message' => ['en' => 'The item could not be found', 'da' => 'Punktet findes ikke']];
    }
    if ($targetItem > 0) {
      $sql = "select * from hierarchy_item where id=@int(id)";
      if ($row = Database::selectFirst($sql, ['id' => $targetItem])) {
        $targetHierarchy = $row['hierarchy_id'];
      } else {
        return ['success' => false, 'message' => ['en' => 'The target could not be found', 'da' => 'Punktet findes ikke']];
      }
    }

    if ($targetHierarchy > 0 && $targetHierarchy != $moveHierarchy) {
      return ['success' => false, 'message' => ['en' => 'The item cannot be moved to another hierarchy', 'da' => 'Punktet kan ikke flyttes til et andet hierarki']];
    } else if ($moveParent == $targetItem) {
      return ['success' => false, 'message' => ['en' => 'The item is already located here', 'da' => 'Punktet har allerede denne position']];
    } else if ($move == $targetItem) {
      return ['success' => false, 'message' => ['en' => 'The item cannot be moved to itself', 'da' => 'Punktet kan ikke flyttes til sig selv']];
    } else {
      $path = Hierarchy::getAncestorPath($targetItem);
      if (in_array($move,$path)) {
        return ['success' => false, 'message' => ['en' => 'The item cannot be moved to a sub item', 'da' => 'Punktet kan ikke flyttes til et underpunkt']];
      }
    }

    $row = null;
    // Find largest position of items under new parent
    if ($targetHierarchy > 0 && $targetItem == 0) {
      $sql = "select max(`index`) as `index` from hierarchy_item where parent=0 and hierarchy_id=@int(id)";
      $row = Database::selectFirst($sql, ['id' => $targetHierarchy]);
      $newParent = 0;
    } else {
      $sql = "select max(`index`) as `index` from hierarchy_item where parent=@int(id)";
      $row = Database::selectFirst($sql, ['id' => $targetItem]);
      $newParent = $targetItem;
    }
    if ($row) {
        $newIndex = $row['index'] + 1;
    } else {
        $newIndex = 1;
    }

    // Change position to new position
    $sql = "update hierarchy_item set parent=@int(parent),`index`=@int(index) where id=@int(id)";
    Database::update($sql, ['parent' => $newParent, 'index' => $newIndex, 'id' => $move]);

    Hierarchy::fixLevel($moveParent, $moveHierarchy);

    Hierarchy::markHierarchyOfItemChanged($move);

    EventService::fireEvent('update', 'hierarchy', null, $moveHierarchy);

    return ['success' => true];
  }

  static function fixLevel($parent, $hierarchy) {
    $sql = "select id from hierarchy_item
      where parent=@int(parent)
      and hierarchy_id=@int(hierarchy)
      order by `index`";
    $result = Database::select($sql, ['parent' => $parent, 'hierarchy' => $hierarchy]);
    $index = 1;
    while ($row = Database::next($result)) {
      $sql = "update hierarchy_item set `index`=@int(index) where id=@int(id)";
      Database::update($sql, ['index' => $index, 'id' => $row['id']]);
      $index++;
    }
    Database::free($result);
  }
}
?>