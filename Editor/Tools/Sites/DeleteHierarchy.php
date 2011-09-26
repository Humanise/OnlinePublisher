<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Model/Hierarchy.php';

$id = Request::getInt('id');

if ($hierarchy = Hierarchy::load($id)) {
	if ($hierarchy->delete()) {
		exit; // Success
	}
}
Response::badRequest();
?>