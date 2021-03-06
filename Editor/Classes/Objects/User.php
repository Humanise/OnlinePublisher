<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['User'] = [
  'table' => 'user',
  'properties' => [
    'username' => ['type' => 'string'],
    'password' => ['type' => 'string'],
    'email' => ['type' => 'string'],
    'language' => ['type' => 'string'],
    'internal' => ['type' => 'boolean'],
    'external' => ['type' => 'boolean'],
    'administrator' => ['type' => 'boolean'],
    'secure' => ['type' => 'boolean']
  ]
];

class User extends ModelObject {

  var $username;
  var $password;
  var $email;
  var $language;
  var $internal = false;
  var $external = false;
  var $administrator = false;
  var $secure = false;

  function __construct() {
    parent::__construct('user');
  }

  static function load($id) {
    return ModelObject::get($id,'user');
  }

  function getIcon() {
    return 'common/user';
  }

  function setUsername($username) {
    $this->username = $username;
  }

  function getUsername() {
    return $this->username;
  }

  function setPassword($pass) {
    $this->password = $pass;
  }

  function getPassword() {
    return $this->password;
  }

  function setEmail($email) {
    $this->email = $email;
  }

  function getEmail() {
    return $this->email;
  }

  function setInternal($internal) {
    $this->internal = $internal;
  }

  function getInternal() {
    return $this->internal;
  }

  function setExternal($external) {
    $this->external = $external;
  }

  function getExternal() {
    return $this->external;
  }

  function setAdministrator($admin) {
    $this->administrator = $admin;
  }

  function getAdministrator() {
    return $this->administrator;
  }

  function setLanguage($language) {
    $this->language = $language;
  }

  function getLanguage() {
    return $this->language;
  }

  function setSecure($secure) {
      $this->secure = $secure;
  }

  function getSecure() {
      return $this->secure;
  }

  function removeMore() {
    $sql = "delete from securityzone_user where user_id = @id";
    Database::delete($sql, $this->id);
    $sql = "delete from user_permission where user_id = @id";
    Database::delete($sql, $this->id);
  }

  function sub_publish() {
    return '<user xmlns="' . parent::_buildnamespace('1.0') . '">' .
      '<username>' . Strings::escapeEncodedXML($this->username) . '</username>' .
      '</user>';
  }

  function isValid() {
    if (!AuthenticationService::isValidUsername($this->username)) {
      Log::debug('Invalid username: ' . $this->username);
      return false;
    }
    return true;
  }
}
?>