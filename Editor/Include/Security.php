<?php
/**
 * @package OnlinePublisher
 * @subpackage Include
 */
session_set_cookie_params(0);
session_start();
date_default_timezone_set('Europe/Copenhagen');
$basePath = substr(__FILE__, 0,strpos(__FILE__,'Editor'));

// Load stuff we know we need
require_once($basePath."Editor/Classes/Core/Request.php");
require_once($basePath."Editor/Classes/Core/Response.php");
require_once($basePath."Editor/Classes/Core/InternalSession.php");
require_once($basePath."Editor/Classes/Services/ConfigurationService.php");
require_once($basePath."Editor/Include/Classloader.php");

if (@$_SESSION['core.debug.simulateLatency']) {
  usleep(rand(1000000,2000000));
}

// If not logged in
if (!InternalSession::isLoggedIn()) {
  if (Request::getHeader('Ajax')) {
    Response::forbidden();
  }
  else if (isset($_GET['page'])) {
    Response::forbidden();
    header("Location: ".ConfigurationService::getBaseUrl()."Editor/Authentication.php?notloggedin=true&page=".$_GET['page']);
  }
  else {
    Log::debug('Sending forbidden');
    Response::forbidden();
    header("Location: ".ConfigurationService::getBaseUrl()."Editor/Authentication.php?notloggedin=true");
  }
  exit;
}
// If timed out
else if (InternalSession::isTimedOut()) {
  InternalSession::logOut();
  if (isset($_GET['page'])) {
    Response::forbidden();
    header("Location: ".ConfigurationService::getBaseUrl()."Editor/Authentication.php?timeout=true&page=".$_GET['page']);
  } else {
    Response::forbidden();
    header("Location: ".ConfigurationService::getBaseUrl()."Editor/Authentication.php?timeout=true");
  }
  exit;
}
// update timestamp if nothing is wrong
else {
  InternalSession::registerActivity();
}


?>