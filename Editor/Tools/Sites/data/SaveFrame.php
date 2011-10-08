<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';
$id = Request::getInt('id');
$data = Request::getUnicodeObject('frame');
$topLinks = Request::getUnicodeObject('topLinks');
$bottomLinks = Request::getUnicodeObject('bottomLinks');

if ($id>0) {
	$object = Frame::load($id);
} else {
	$object = new Frame();
}
if ($object) {
	$object->setTitle($data->title);
	$object->setName($data->name);
	$object->setBottomText($data->bottomText);
	$object->setHierarchyId($data->hierarchyId);
	$object->save();

	FrameService::replaceLinks($object,$topLinks,$bottomLinks);
}
?>