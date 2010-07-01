<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Calendars
 */
require_once '../../../../Config/Setup.php';
require_once '../../../Include/Security.php';
require_once '../../../Classes/Request.php';
require_once '../../../Classes/Event.php';

$id = Request::getInt('id');

$obj = Event::load($id);
if ($obj) {
	$obj->remove();
}
?>