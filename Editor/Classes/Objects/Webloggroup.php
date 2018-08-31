<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Webloggroup'] = [
  'table' => 'webloggroup',
  'properties' => []
];

class Webloggroup extends ModelObject {

  function __construct() {
    parent::__construct('webloggroup');
  }

  static function load($id) {
    return ModelObject::get($id,'webloggroup');
  }

  function getIcon() {
    return 'common/folder';
  }

  function removeMore() {
    $sql = "delete from webloggroup_weblogentry where webloggroup_id=" . Database::int($this->id);
    Database::delete($sql);
    $sql = "delete from weblog_webloggroup where webloggroup_id=" . Database::int($this->id);
    Database::delete($sql);
  }

  static function search($query = []) {
    $out = [];
    if (isset($out['page'])) {
      $sql = "select webloggroup_id as id from weblog_webloggroup where page_id=" . Database::int($out['page']);
    } else {
      $sql = "select id from object,webloggroup where object.id=webloggroup.object_id order by object.title";
    }
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $out[] = WeblogGroup::load($row['id']);
    }
    Database::free($result);
    return $out;
  }
}
?>