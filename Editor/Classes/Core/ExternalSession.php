<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ExternalSession {

  static function logIn($username,$password) {

    if ($user = AuthenticationService::getExternalUser($username,$password)) {
      $_SESSION['external.user']=['id'=>$user->getId(), 'username'=>$user->getUsername(), 'title'=>$user->getTitle()];
      return $user;
    }
    else {
      return false;
    }
  }

  static function logOut() {
    unset($_SESSION['external.user']);
  }

  static function getUser() {
    if (isset($_SESSION['external.user'])) {
      return $_SESSION['external.user'];
    }
    else {
      return false;
    }
  }
}