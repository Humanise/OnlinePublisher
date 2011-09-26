<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Core/Request.php';
require_once '../../../Classes/Objects/Event.php';

$id = Request::getInt('id');

$obj = Event::load($id);
if ($obj) {
	$obj->remove();
}
?>