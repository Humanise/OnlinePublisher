<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Objects
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Streamitem'] = [
  'table' => 'streamitem',
  'properties' => [
    'streamId' => ['type' => 'int', 'column' => 'stream_id',
      'relation' => ['class' => 'Stream', 'property' => 'id']
    ],
    'data' => ['type' => 'string'],
    'hash' => ['type' => 'string'],
    'identity' => ['type' => 'string'],
    'originalDate' => ['type' => 'datetime', 'column' => 'originaldate'],
    'retrievalDate' => ['type' => 'datetime', 'column' => 'retrievaldate']
  ]
];

class Streamitem extends ModelObject {

  var $streamId;
  var $dataId;
  var $hash;
  var $identity;
  var $originalDate;
  var $retrievalDate;

  public static $STREAM_ID = 'stream_id';
  public static $HASH = 'hash';
  public static $IDENTITY = 'identity';

  function __construct() {
    parent::__construct('streamitem');
  }

  static function load($id) {
    return ModelObject::get($id,'streamitem');
  }

  function setStreamId($streamId) {
    $this->streamId = $streamId;
  }

  function getStreamId() {
    return $this->streamId;
  }

  function setData($data) {
    $this->data = $data;
  }

  function getData() {
    return $this->data;
  }

  function setHash($hash) {
    $this->hash = $hash;
  }

  function getHash() {
    return $this->hash;
  }

  function setIdentity($identity) {
      $this->identity = $identity;
  }

  function getIdentity() {
      return $this->identity;
  }

  function setOriginalDate($originalDate) {
    $this->originalDate = $originalDate;
  }

  function getOriginalDate() {
    return $this->originalDate;
  }

  function setRetrievalDate($retrievalDate) {
    $this->retrievalDate = $retrievalDate;
  }

  function getRetrievalDate() {
    return $this->retrievalDate;
  }

}