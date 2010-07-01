<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Productoffer.php';

$data = Request::getObject('data');

if ($data->id>0) {
	$offer = ProductOffer::load($data->id);
} else {
	$offer = new ProductOffer();
}
$offer->setOffer(Request::fromUnicode($data->offer));
$offer->setNote(Request::fromUnicode($data->note));
$offer->setExpiry($data->expiry);
$offer->save();
$offer->publish();
?>