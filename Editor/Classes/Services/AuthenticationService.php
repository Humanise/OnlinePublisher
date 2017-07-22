<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class AuthenticationService {

  static function ensureSecurity() {
    $sql = "select object_id as id from user where secure=0";
    $ids = Database::getIds($sql);
    foreach ($ids as $id) {
      if ($user = User::load($id)) {
        $user->setPassword(AuthenticationService::encryptPassword($user->getPassword()));
        $user->setSecure(true);
        $user->save();
      }
    }
  }

  static function isSharedSecret($key) {
    $secret = SettingService::getSharedSecret();
    if (Strings::isBlank($secret)) {
      Log::debug('No shared secret is configured');
      return false;
    } else if (Strings::isBlank($key)) {
      Log::debug('The secret is blank');
      return false;
    } else if ($key !== $secret) {
      Log::debug('The secret is incorrect');
      return false;
    }
    return true;
  }

  static function isSuperUser($username,$password) {
    $superUser = ConfigurationService::getSuperUsername();
    $superPassword = ConfigurationService::getSuperPassword();
    if (Strings::isBlank($username) || Strings::isBlank($password) || Strings::isBlank($superUser) || Strings::isBlank($superPassword)) {
      return false;
    }
    if ($superUser == $username && $superPassword == $password) {
      return true;
    }
    return false;
  }

  static function setPassword($user,$password) {
    $user->setPassword(AuthenticationService::encryptPassword($password));
    $user->setSecure(true);
  }

  static function encryptPassword($str) {
    return sha1($str);
  }

  static function getInternalUser($username,$password) {
    return AuthenticationService::getUser($username,$password,true);
  }

  static function getExternalUser($username,$password) {
    return AuthenticationService::getUser($username,$password,false);
  }

  static function getUser($username,$password,$internal = null) {
      $sql = "select object_id as id from user where " .
      " username=" . Database::text($username) . " and ((secure=0 and password=" . Database::text($password) . ") or (secure=1 and password=" . Database::text(AuthenticationService::encryptPassword($password)) . "))";
    if ($internal === true) {
      $sql .= " and internal=1";
    } else if ($internal === false) {
      $sql .= " and external=1";
    }
    //Log::debug($sql);
    if ($row = Database::selectFirst($sql)) {
      return User::load($row['id']);
    }
    return null;
  }

  static function isInternalUser($username,$password) {
    $user = AuthenticationService::getUser($username,$password,true);
    return $user !== null;
  }

  static function getUserByEmailOrUsername($emailOrUsername) {
    if (Strings::isBlank($emailOrUsername)) {
      return null;
    }
    $sql = "select object_id from user where username=" . Database::text($emailOrUsername) . " or email=" . Database::text($emailOrUsername);
    if ($row = Database::selectFirst($sql)) {
      $id = intval($row['object_id']);
      $user = User::load($id);
      if (!$user) {
        Log::debug('AuthenticationService: User with ID=' . $id . ' could not be loaded');
      }
      return $user;
    }
    return null;
  }

  static function createValidationSession($user) {
      $unique = md5(uniqid(rand(), true));
      $limit = time() + 60 * 60; // 1 hour into future

      $body = "Klik på følgende link for at ændre dit kodeord til brugeren \"" . $user->getUsername() . "\": \n\n" .
      ConfigurationService::getBaseUrl() . "Editor/Recover.php?key=" . $unique;
      if (MailService::send(Strings::fromUnicode($user->getEmail()),Strings::fromUnicode($user->getTitle()),"Humanise Editor - ændring af kodeord",$body)) {
        $sql = "insert into email_validation_session (`unique`,`user_id`,`email`,`timelimit`)" .
        " values (" .
        Database::text($unique) . "," . $user->getId() . "," . Database::text($user->getEmail()) . "," . Database::datetime($limit) .
        ")";
        Database::insert($sql);
      return true;
    }
    return false;
  }

  static function isValidUsername($username) {
    return preg_match("/^[\\S]{2,}$/u", $username);
  }

  static function isValidEmailValidationSession($key) {
    $sql = "select id from email_validation_session where `unique`=" . Database::text($key) . " and timelimit>now()";
    return !Database::isEmpty($sql);
  }

  static function updatePasswordForEmailValidationSession($key,$password) {
    if (Strings::isBlank($key) || Strings::isBlank($password)) {
      Log::debug('key or password is blank');
      return false;
    }
    $sql = "select user_id from email_validation_session where `unique`=" . Database::text($key) . " and timelimit>now()";
    if ($row = Database::selectFirst($sql)) {
      if ($user = User::load($row['user_id'])) {
        AuthenticationService::setPassword($user,$password);
        $user->save();
        $user->publish();
        // remove the session
        $sql = "delete from email_validation_session where `unique`=" . Database::text($key);
        Database::delete($sql);
        return true;
      }
    }
    Log::debug('Key not found');
    return false;
  }
}