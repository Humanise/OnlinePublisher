<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class RemoteDataService {

  static function _getUrlAsFile($url) {
    global $basePath;
    $path = RemoteDataService::getPathOfUrl($url);
    if (true || !file_exists($path)) {
      $success = RemoteDataService::writeUrlToFile($url,$path);
      if ($file = fopen($url, "rb")) {
          if ($temp = fopen($path, "wb")) {
          while (!feof($file)) {
            fwrite($temp,fread($file, 8192));
          }
            fclose($temp);
        }
        fclose($file);
      }
    }
    return $path;
  }

  static function writeUrlToFile($url,$path) {
    $existing = file_exists($path);
    $success = false;
    if (!function_exists('curl_init')) {
      if ($file = @fopen($url, "rb")) {
          if ($temp = @fopen($path, "wb")) {
          while (!feof($file)) {
            fwrite($temp,fread($file, 8192));
          }
            fclose($temp);
          $success = true;
        }
        fclose($file);
      }
    } else {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      //curl_setopt($ch, CURLOPT_VERBOSE, 1);
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      //curl_setopt($ch, CURLOPT_POST, 1);
      //curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
      //curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
      $headers = ["Cache-Control: no-cache", "Pragma: no-cache"];
      if (strpos($url, 'https://api.github.com') === 0) {
        $headers[] = 'User-Agent: Humanise Editor';
      }
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      if ($file = fopen($path, "wb")) {
        curl_setopt($ch, CURLOPT_FILE, $file);
        $success = curl_exec($ch);
        fclose($file);
        if (!$success) {
          $info = curl_getinfo($ch);
          if (!$existing) {
            unlink($path);
          }
          Log::debug('Unable to load url: ' . $url);
          Log::debug($info);
        }
      } else {
        Log::debug('Unable to open file path: ' . $path);
      }
      curl_close($ch);
    }
    return $success;
  }

  static function getPathOfUrl($url) {
    global $basePath;
    return $basePath . 'local/cache/urls/' . sha1($url);
  }

  /**
   * @param $maxAge The number of seconds
   */
  static function getRemoteData($url,$maxAge = 0) {
    $now = time();
    $cached = Query::after('cachedurl')->withProperty('url',$url)->first();
    $path = RemoteDataService::getPathOfUrl($url);
    $success = false;
    if (!$cached) {
      $cached = new Cachedurl();
      $cached->setTitle($url);
      $cached->setUrl($url);
      $cached->setSynchronized(0);
    }
    $age = $now - $cached->getSynchronized();
    if ($age > $maxAge) {
      $success = RemoteDataService::writeUrlToFile($url,$path);
      if ($success) {
        $cached->setTitle($url);
        $cached->setSynchronized(time());
        $cached->save();
        $cached->publish();
      }
    }
    $data = new RemoteData();
    $data->setAge($age);
    $data->setFile($path);
    $data->setSuccess($success);
    $data->setHasData(file_exists($path) && filesize($path) > 0);
    return $data;
  }
}