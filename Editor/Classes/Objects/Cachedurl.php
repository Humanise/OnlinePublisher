<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Cachedurl'] = [
    'table' => 'cachedurl',
    'properties' => [
        'url' => ['type' => 'string'],
        'synchronized' => ['type' => 'datetime'],
        'mimeType' => ['type' => 'string']
    ]
];

class Cachedurl extends ModelObject {
  var $url;
  var $synchronized;
  var $mimeType;

  function __construct() {
    parent::__construct('cachedurl');
  }

  static function load($id) {
    return ModelObject::get($id,'cachedurl');
  }

  function setUrl($url) {
      $this->url = $url;
  }

  function getUrl() {
      return $this->url;
  }

  function setSynchronized($synchronized) {
      $this->synchronized = $synchronized;
  }

  function getSynchronized() {
      return $this->synchronized;
  }

  function setMimeType($mimeType) {
      $this->mimeType = $mimeType;
  }

  function getMimeType() {
      return $this->mimeType;
  }

}