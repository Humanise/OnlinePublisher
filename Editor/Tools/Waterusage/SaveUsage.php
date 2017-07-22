<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';

$data = Request::getObject('data');

$meter = Query::after('watermeter')->withProperty('number',$data->number)->first();
if (!$meter) {
  $meter = new Watermeter();
  $meter->setNumber($data->number);
  $meter->save();
  $meter->publish();
}
if ($data->id > 0) {
  $obj = Waterusage::load($data->id);
} else {
  $obj = new Waterusage();
  $obj->setSource(Waterusage::$ADMIN);
  $obj->setStatus(Waterusage::$VALIDATED);
}
$obj->setWatermeterId($meter->getId());
$obj->setValue($data->value);
$obj->setDate($data->date);
$obj->save();
$obj->publish();
?>