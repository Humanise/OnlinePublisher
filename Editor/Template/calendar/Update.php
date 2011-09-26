<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Database.php';
require_once '../../Classes/Core/InternalSession.php';
require_once '../../Classes/Core/Request.php';

$objects = Request::getArray('object');
$title = Request::getString('title');
$newsViewStartHour = Request::getInt('weekview_starthour');

$id = InternalSession::getPageId();

$sql="update calendarviewer set title=".Database::text($title).",weekview_starthour=".Database::int($newsViewStartHour)." where page_id=".$id;
Database::update($sql);

$sql="delete from calendarviewer_object where page_id=".$id;
Database::delete($sql);

foreach ($objects as $object) {
	$sql="insert into calendarviewer_object (page_id,object_id) values (".$id.",".$object.")";
	Database::insert($sql);
}

$sql="update page set changed=now() where id=".$id;
Database::update($sql);

Response::redirect('Editor.php');
?>