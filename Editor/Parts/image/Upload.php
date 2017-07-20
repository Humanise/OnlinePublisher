<?php
/**
 * @package OnlinePublisher
 * @subpackage Parts.Image
 */
require_once '../../Include/Private.php';

$response = ImageService::createUploadedImage();

if ($response->getSuccess()) {
  ImagePartController::setLatestUploadId($response->getObject()->getId());
  Response::uploadSuccess();
} else {
  ImagePartController::setLatestUploadId(null);
  Log::debug($response);
  Response::uploadFailure();
}
?>