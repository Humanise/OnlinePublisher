<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ZipFile {

  var $delegate = null;

  function ZipFile($delegate) {
    $this->delegate = $delegate;
  }

  function getFiles() {
    $list = $this->delegate->listContent();
    $files = [];

    foreach ($list as $file) {
      $base = basename($file['filename']);
      if ($base{0} != '.' && !$file['folder']) {
        $files[] = new ZipFileItem($file,$this->delegate);
      }
    }
    return $files;
  }

  function extractToTemporaryFolder() {
    $folder = new TemporaryFolder();
    $path = $folder->make();
    $this->delegate->extract($path);
    return $folder;
  }

  function extract($file) {
    global $basePath;
    $extracted = $this->delegate->extractByIndex($file['index'],$basePath . 'local/cache/temp');
    Log::debug($extracted);
  }
}