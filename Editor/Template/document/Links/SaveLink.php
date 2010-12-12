<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/InternalSession.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Link.php';
require_once '../../../Classes/Page.php';

$id = Request::getInt('id');
$pageId = InternalSession::getPageId();
$text = Request::getEncodedString('text');
$value = Request::getEncodedString('value');
$type = Request::getString('type');
$target = Request::getString('target');
$alternative = Request::getEncodedString('alternative');

if ($id) {
	$link=Link::load($id);
} else {
	$link=new Link();
}
$link->setText($text);
$link->setAlternative($alternative);
$link->setPageId($pageId);
$link->setTypeAndValue($type,$value);

$link->save();

Page::markChanged($pageId);
?>