<?php
/**
 * @package OnlinePublisher
 * @subpackage Services
 */
require_once '../../Include/Public.php';

if (Request::isPost()) {
  $key = Request::getString('key');
  $password = Request::getString('password');
  if (AuthenticationService::updatePasswordForEmailValidationSession($key,$password)) {
    Response::sendObject(['success' => true]);
    exit;

  }
}
Response::sendObject(['success' => false,'message' => 'Det lykkedes desv�rre ikke at �ndre kodeordet']);
?>