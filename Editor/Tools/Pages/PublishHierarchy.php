<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Model/Hierarchy.php';
require_once '../../Classes/Core/Request.php';

$id = Request::getInt('id');

$hier = Hierarchy::load($id);
$hier->publish();

Response::redirect('EditHierarchy.php?id='.$id);
?>