<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['FilePart'] = [
  'table' => 'part_file',
  'identity' => 'part_id',
  'properties' => [
    'fileId' => [ 'type' => 'int', 'column' => 'file_id', 'relation' => ['class' => 'File', 'property' => 'id'] ],
    'text' => [ 'type' => 'string' ]
  ]
];

class FilePart extends Part
{
  var $fileId;
  var $text;

  function __construct() {
    parent::__construct('file');
  }

  static function load($id) {
    return Part::get('file',$id);
  }

  function setFileId($fileId) {
    $this->fileId = $fileId;
  }

  function getFileId() {
      return $this->fileId;
  }

  function setText($text) {
    $this->text = $text;
  }

  function getText() {
    return $this->text;
  }
}
?>