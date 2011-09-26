<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../../Config/Setup.php';
require_once '../../../../Include/Security.php';
require_once '../../../../Classes/Core/Request.php';
require_once '../../../../Classes/Interface/In2iGui.php';
require_once '../../../../Classes/Model/Page.php';

$id = Request::getInt('id');

$page = Page::load($id);
$page->toUnicode();

$arr = array(
	'title'=>$page->getTitle(), 
	'path'=>$page->getPath(), 
	'keywords'=>$page->getKeywords(), 
	'language'=>$page->getLanguage(), 
	'description'=>$page->getDescription()
);

In2iGui::sendObject($arr);
?>