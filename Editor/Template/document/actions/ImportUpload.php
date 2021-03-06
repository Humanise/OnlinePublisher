<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$columnId = Request::getInt('columnId');
$sectionIndex = Request::getInt('sectionIndex');
$text = Request::getString('text');
$url = Request::getString('url');
$type = Request::getString('type');

$part = null;

if (Strings::isNotBlank($text)) {
  if ($type == 'header') {
    $ctrl = new HeaderPartController();
  } else {
    $ctrl = new TextPartController();
  }
  $part = $ctrl->createPart();
  $part->setText($text);
  $part->save();

} else if ($type == 'image' && Strings::isNotBlank($url)) {
  $response = ImageService::createImageFromUrl($url);
  if ($response->getSuccess()) {
    $image = $response->getObject();
    $ctrl = new ImagePartController();
    $part = $ctrl->createPart();
  }

} else if ($type == 'movie' && Strings::isNotBlank($url)) {
  $ctrl = new MoviePartController();
  $part = $ctrl->createPart();
  $part->setUrl($url);
  $part->save();

} else if ($type == 'image' && ImageService::isUploadedFileValid()) {
  $response = ImageService::createUploadedImage();
  if ($response->getSuccess()) {
    $image = $response->getObject();
    $ctrl = new ImagePartController();
    $part = $ctrl->createPart();
  }
} else if ($type == 'file') {

  $response = FileService::createUploadedFile();
  if ($response->getSuccess()) {
    $file = $response->getObject();

    $ctrl = new FilePartController();
    $part = $ctrl->createPart();
  }
}

if ($part != null) {
  $sectionId = DocumentTemplateEditor::addSectionFromPart($columnId,$sectionIndex,$part);
}

?>