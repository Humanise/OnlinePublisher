<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Imagegroup'] = [
    'table' => 'imagegroup',
    'properties' => []
];

class Imagegroup extends ModelObject {

  function __construct() {
    parent::__construct('imagegroup');
  }

  static function load($id) {
    return ModelObject::get($id,'imagegroup');
  }

  function getIcon() {
        return "common/folder";
  }

  function removeMore() {
    $sql = "delete from imagegroup_image where imagegroup_id = @int(id)";
    Database::delete($sql, ['id' => $this->id]);
  }

}
?>