<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Newssource'] = [
    'table' => 'newssource',
    'properties' => [
      'url' => ['type' => 'string'],
      'synchronized' => ['type' => 'datetime'],
      'syncInterval' => ['type' => 'int', 'column' => 'sync_interval']
    ]
];

class Newssource extends ModelObject {
  var $url;
  var $synchronized;
  var $syncInterval;

  function __construct() {
    parent::__construct('newssource');
  }

  static function load($id) {
    return ModelObject::get($id,'newssource');
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

  function setSyncInterval($syncInterval) {
      $this->syncInterval = $syncInterval;
  }

  function getSyncInterval() {
      return $this->syncInterval;
  }

  function isInSync() {
    return (time() - $this->synchronized < $this->syncInterval);
  }

  function synchronize($force = false) {
    NewsService::synchronizeSource($this->id,$force);
  }


  function getIcon() {
    return 'common/internet';
  }

  function removeMore() {
    $items = Query::after('newssourceitem')->withProperty('newssource_id',$this->id)->get();
    foreach ($items as $item) {
      $item->remove();
    }
  }
}