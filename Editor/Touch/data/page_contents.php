<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../Include/Private.php';

$id = Request::getInt('id',0);
$html = RenderingService::previewPage([
  'pageId' => $id,
  'mini' => true,
  'relativePath' => '../../../',
  'relativeUrl' => '../../'
]);

if ($html) {
  header("Content-Type: text/html; charset=UTF-8");
  echo $html;
} else {
  Response::notFound('Siden findes ikke længere');
}
?>