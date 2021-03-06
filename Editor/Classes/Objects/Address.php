<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Address'] = [
  'table' => 'address',
  'properties' => [
    'street' => ['type' => 'string'],
    'zipcode' => ['type' => 'string'],
    'city' => ['type' => 'string'],
    'country' => ['type' => 'string']
  ]
];
class Address extends ModelObject {
  var $street;
  var $zipcode;
  var $city;
  var $country;

  function __construct() {
    parent::__construct('address');
  }

  function _updateTitle() {
    $this->title = $this->toString();
  }

  static function load($id) {
    return ModelObject::get($id,'address');
  }

  function getIcon() {
    return "common/map";
  }

  function setStreet($street) {
      $this->street = $street;
    $this->_updateTitle();
  }

  function getStreet() {
      return $this->street;
  }

  function setZipcode($zipcode) {
      $this->zipcode = $zipcode;
    $this->_updateTitle();
  }

  function getZipcode() {
      return $this->zipcode;
  }

  function setCity($city) {
      $this->city = $city;
    $this->_updateTitle();
  }

  function getCity() {
      return $this->city;
  }

  function setCountry($country) {
      $this->country = $country;
    $this->_updateTitle();
  }

  function getCountry() {
      return $this->country;
  }

  function toString() {
    $sb = new StringBuilder($this->street);
    $sb->separator(', ');
    return $sb->separator(', ')->append($this->zipcode)->separator(', ')->append($this->city)->separator(', ')->append($this->country)->toString();
  }

  function sub_index() {
    return Strings::buildIndex([$this->street, $this->zipcode, $this->city, $this->country]);
  }
}
?>