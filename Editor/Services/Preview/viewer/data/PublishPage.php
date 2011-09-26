<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../../Config/Setup.php';
require_once '../../../../Include/Security.php';
require_once '../../../../Classes/Core/Request.php';
require_once '../../../../Classes/Interface/In2iGui.php';
require_once '../../../../Classes/Services/PublishingService.php';

$id = Request::getInt('id');

PublishingService::publishPage($id);

In2iGui::respondSuccess();
?>