<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$message = Request::getString('message');

$user = User::load(InternalSession::getUserId());

$request = new WebRequest('http://www.humanise.dk/services/issues/create/');
$request->setParameters(['description'=>$message, 'site'=>ConfigurationService::getBaseUrl(), 'user'=>$user->getUsername()]);

$client = new HttpClient();

$response = $client->send($request);

if (!$response->isSuccess()) {
  Response::badGateway();
}
?>