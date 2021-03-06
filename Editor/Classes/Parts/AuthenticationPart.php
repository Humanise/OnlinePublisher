<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['AuthenticationPart'] = [
  'table' => 'part_authentication',
  'identity' => 'part_id',
  'properties' => []
];

class AuthenticationPart extends Part
{
  function __construct() {
    parent::__construct('authentication');
  }

  static function load($id) {
    return Part::get('authentication',$id);
  }
}
?>