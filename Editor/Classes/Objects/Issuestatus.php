<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Issuestatus'] = [
    'table' => 'issuestatus',
    'properties' => []
];

class Issuestatus extends ModelObject {

  function __construct() {
    parent::__construct('issuestatus');
  }

  static function load($id) {
    return ModelObject::get($id,'issuestatus');
  }
}
?>