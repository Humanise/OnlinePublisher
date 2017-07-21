<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';

$data = Request::getObject('data');

$usage = Waterusage::load($data->id);

$meter = Watermeter::load($usage->getWatermeterId());


Response::sendObject([
  'id' => $usage->getId(),
  'number' => $meter->getNumber(),
  'value' => $usage->getValue(),
  'date' => $usage->getDate()
]);
?>