<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Core
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Public.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Core/Request.php';
require_once '../../Classes/Services/AuthenticationService.php';

$superUsername = Request::getString('superUsername');
$superPassword = Request::getString('superPassword');

$adminUsername = Request::getString('adminUsername');
$adminPassword = Request::getString('adminPassword');

if (!AuthenticationService::isSuperUser($superUsername,$superPassword)) {
	Response::forbidden();
	exit;	
}
if (StringUtils::isBlank($adminUsername) || StringUtils::isBlank($adminPassword)) {
	Response::badRequest();
	exit;
}

$user = new User();
$user->setUsername($adminUsername);
$user->setTitle($adminUsername);
AuthenticationService::setPassword($user,$adminPassword);
$user->setInternal(true);
$user->setAdministrator(true);
$user->create();
$user->publish();
?>