<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Sites
 */
require_once '../../../Include/Private.php';

$move = Request::getInt('move',0);
$targetItem = Request::getInt('targetItem',0);
$targetHierarchy = Request::getInt('targetHierarchy',0);


Log::debug($targetItem);

$response = Hierarchy::relocateItem($move,$targetItem,$targetHierarchy);

Log::debug($response);

Response::sendObject($response);
?>