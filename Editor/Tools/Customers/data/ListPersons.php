<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Include/Private.php';

$kind = Request::getString('kind');
$value = Request::getInt('value');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$text = Request::getString('query');
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') {
  $sort='title';
}

$query = Query::after('person')->orderBy($sort)->withWindowSize($windowSize)->withWindowPage($windowPage)->withDirection($direction)->withText($text);

if ($kind=='mailinglist') {
  $query->withCustom('mailinglist',$value);
}
if ($kind=='persongroup') {
  $query->withCustom('group',$value);
}
$result = $query->search();

$writer = new ListWriter();

$writer->startList()->
    sort($sort,$direction)->
    window(['total'=>$result->getTotal(),'size'=>$windowSize,'page'=>$windowPage])->
    startHeaders()->
      header(['title'=>['Name','da'=>'Navn'],'key'=>'title','sortable'=>true,'width'=>30])->
      header(['title'=>['E-mail','da'=>'E-post'],'width'=>20])->
      header(['title'=>['Phone','da'=>'Telefon'],'width'=>20])->
      header(['title'=>['Address','da'=>'Adresse'],'width'=>20])->
      header(['width'=>1])->
    endHeaders();
foreach ($result->getList() as $object) {
  $writer->startRow(['id'=>$object->getId(),'kind'=>$object->getType(),'icon'=>'common/person','title'=>$object->getTitle()])->
    startCell(['icon'=>'common/person'])->text($object->getTitle())->endCell();
  $writer->startCell();
  buildEmails($object,$writer);
  $writer->endCell();
  $writer->startCell();
  buildPhones($object,$writer);
  $writer->endCell();
  $writer->startCell();
  buildAddress($object,$writer);
  $writer->endCell();
  $writer->startCell();
  if ($object->getImageId()!=null) {
    $writer->startIcons()->icon(['icon'=>'monochrome/image'])->endIcons();
  }
  $writer->endCell();
  $writer->endRow();
}

$writer->endList();

function buildAddress($person,$writer) {
  $addr = $person->getStreetname();
  $zipcode = $person->getZipcode();
  $city = $person->getCity();
  $country = $person->getCountry();
  if ($zipcode!='' || $city!='') {
    if ($addr!='') $addr.="\n";
    $addr.=$zipcode.' '.$city;
  }
  if ($country!='') {
    if ($addr!='') $addr.="\n";
    $addr.=$country;
  }
  $writer->text($addr);
}

function buildEmails($person,$writer) {
  $mails = Query::after('emailaddress')->withProperty('containingObjectId',$person->getId())->get();
  foreach ($mails as $mail) {
    $writer->object(['icon'=>'common/email','text'=>$mail->getAddress()]);
  }
}

function buildPhones($person,$writer) {
  $phones = Query::after('phonenumber')->withProperty('containingObjectId',$person->getId())->get();
  foreach ($phones as $phone) {
    $writer->object(['icon'=>'common/phone','text'=>$phone->getNumber()]);
  }
}
?>