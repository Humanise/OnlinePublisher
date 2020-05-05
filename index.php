<?php
/**
 * @package OnlinePublisher
 * @subpackage Public
 */
if (ini_get('display_errors')) {
  ini_set('display_errors', 0);
}
// If no setup, go to setup UI
if (!file_exists('Config/Setup.php')) {
  header('Location: ' . dirname($_SERVER['PHP_SELF']) . '/setup/initial/');
  exit();
}
require_once 'Editor/Include/Public.php';

require_once 'Editor/Classes/Core/SystemInfo.php';
require_once 'Editor/Classes/Core/Database.php';
require_once 'Editor/Classes/Core/Request.php';
require_once 'Editor/Classes/Utilities/Strings.php';
require_once 'Editor/Classes/Services/ConfigurationService.php';
require_once 'Editor/Classes/Services/CacheService.php';

// TODO Is it necessary to start the session each time?
if (ConfigurationService::isPublicSession()) {
  session_set_cookie_params(0);
  session_start();
}

if (!Database::testConnection()) {
  RenderingService::displayMessage([
    'status' => Response::$UNAVAILABLE,
    'title' => 'The page is not available at the moment',
    'note' => 'Please try again later'
  ]);
  exit();
}

$file = Request::getInt('file', -1);
$id = Request::getInt('id', -1);
$path = Request::getString('path');
if (Strings::isBlank($path)) {
  $path = '/';
}
if (strpos($path, 'api/') === 0) {
  if (preg_match('/api\/style\/([a-zA-Z0-9_]+)\.css/', $path, $matches)) {
    DesignService::writeCSS($matches[1]);
  }
  else if (preg_match('/api\/style\/([a-zA-Z0-9_]+)\.js/', $path, $matches)) {
    DesignService::writeJS($matches[1]);
  }
  else {
    ApiService::handle();
  }
  exit();
}
if ($file > 0) {
  RenderingService::showFile($file);
  exit();
}
// TODO move this to service
if (Request::getBoolean('logout')) {
  ExternalSession::logOut();
}

if (!CacheService::sendCachedPage($id, $path)) {

  require_once 'Editor/Classes/Services/ClassService.php';
  require_once 'Editor/Classes/Services/RenderingService.php';
  require_once 'Editor/Classes/Services/XslService.php';
  require_once 'Editor/Classes/Services/TemplateService.php';
  require_once 'Editor/Classes/Templates/TemplateController.php';

  if (strlen($path) > 1) {
    $relative = str_repeat('../', substr_count($path, '/'));
    $samePageBaseUrl = $relative . $path . '?';
  }
  else {
    $relative = '';
    $samePageBaseUrl = '?id=' . $id . '&amp;';
  }
  if (strlen($relative) == 0) {
    $relative = './';
  }

  if ($id == -1 && Strings::isBlank($path)) {
    $id = RenderingService::findPage('home');
  }
  // echo $id;
  $page = RenderingService::buildPage($id, $path, Request::getParameters());
  if (!$page && !(Strings::isNotBlank($path) || $id > 0)) {
    $id = RenderingService::findPage('home');
    if ($id == null) {
      RenderingService::displayMessage([
        'title' => 'No front page',
        'note' => 'This website has no front page. If you are the editor you probably should log in and fix the issue'
      ]);
      exit();
    }
    $page = RenderingService::buildPage($id);
  }
  if (!$page) {
    if (Strings::isNotBlank($path)) {
      $sql = "select path from page where path = @text(pathA) or path = @text(pathB)";
      if ($row = Database::selectFirst($sql,['pathA' => $path . '/','pathB' => '/' . $path . '/'])) {
        $location = $row['path'];
        if (strpos($location,'/') !== 0) {
          $location = '/' . $location;
        }
        Response::redirect($location);
      }
    }
    RenderingService::handleMissingPage($path);
  } // If the page has redirect
  else if ($page['redirect'] !== false) {
    // echo $page['redirect'];
    // exit;
    Response::redirect($page['redirect']);
  } // If the page is secure
  else if ($page['secure']) {
    if ($user = ExternalSession::getUser()) {
      if (RenderingService::userHasAccessToPage($user['id'], $page['id'])) {
        RenderingService::writePage($id, $path, $page, $relative, $samePageBaseUrl);
      }
      else {
        RenderingService::goToAuthenticationPage($page['id'], $path);
      }
    }
    else {
      RenderingService::goToAuthenticationPage($page['id'], $path);
    }
  } // If nothing special about page
  else {
    RenderingService::writePage($id, $path, $page, $relative, $samePageBaseUrl);
  }
}

?>