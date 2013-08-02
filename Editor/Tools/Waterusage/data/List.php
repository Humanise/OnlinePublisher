<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Include/Private.php';

$filterKind = Request::getString('filterKind');
$filter = Request::getString('filter');
$text = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');

if ($filter=='meters') {
	listMeters($windowSize,$windowPage,$text,$sort,$direction);
} else if ($filter=='log') {
	listLog($windowSize,$windowPage,$text);
} else {
	listUsage($windowSize,$windowPage,$text,$filterKind,intval($filter));
}

function listMeters($windowSize, $windowPage, $text, $sort, $direction) {
	if (!$sort) {
		$sort = 'number';
	}
	$query = Query::after('watermeter')->orderBy($sort)->withDirection($direction)->withWindowPage($windowPage)->withWindowSize($windowSize)->withText($text);
	$result = $query->search();

	$writer = new ListWriter();

	$writer->startList();
	$writer->sort($sort,$direction);
	$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Nummer','width'=>20,'key'=>'number','sortable'=>'true'));
	$writer->header(array('title'=>'Adresse'));
	$writer->header(array('title'=>'Kontakt'));
	$writer->header(array('title'=>'Seneste v�rdi'));
	$writer->header(array('title'=>'Afl�sningsdato'));
	$writer->endHeaders();

	foreach ($result->getList() as $object) {
		$address = Query::after('address')->withRelationFrom($object)->first();
		$phone = Query::after('phonenumber')->withRelationFrom($object)->first();
		$email = Query::after('emailaddress')->withRelationFrom($object)->first();
		$usage = Query::after('waterusage')->withProperty('watermeterId',$object->getId())->orderBy('date')->descending()->first();
		$writer->startRow(array( 'kind'=>'watermeter', 'id'=>$object->getId(), 'icon'=>$object->getIcon(), 'title'=>$object->getTitle() ));
		$writer->startCell(array( 'icon'=>$object->getIcon() ))->
			text( $object->getNumber() )->
			startIcons()->
				icon(array('icon'=>'monochrome/info','revealing'=>true,'action'=>true,'data'=>array('action'=>'meterInfo','id'=>$object->getId())))->
			endIcons()->
		endCell()->
		startCell();
		if ($address) {
			$writer->startLine()->icon(array( 'icon'=>$address->getIcon() ))->text($address->toString())->endLine();
		}
		$writer->endCell()->startCell();
		if ($email) {
			$writer->startLine()->icon(array( 'icon'=>$email->getIcon() ))->text($email->getAddress())->endLine();
		}
		if ($phone) {
			$writer->startLine()->icon(array( 'icon'=>$phone->getIcon() ))->text($phone->getNumber())->endLine();
		}
		$writer->endCell();
		if ($usage) {
			$writer->startCell()->text($usage->getValue())->endCell();
			$writer->startCell()->text(Dates::formatDate($usage->getDate()))->endCell();
		} else {
			$writer->startCell()->text('?')->endCell();
			$writer->startCell()->text('?')->endCell();
		}
		$writer->endRow();
	}
	
	$writer->endList();
}

function listUsage($windowSize, $windowPage, $text, $filterKind, $year=null) {
	$status = array(
		Waterusage::$UNKNOWN => 'monochrome/round_question',
		Waterusage::$VALIDATED => 'common/success',
		Waterusage::$REJECTED => 'common/stop'
	);
	
	
	$query = Query::after('waterusage')->orderBy('date')->withWindowPage($windowPage)->withWindowSize($windowSize)->withText($text);
	if ($filterKind=='year') {
		$from = Dates::getFirstInstanceOfYear($year);
		$to = Dates::getLastInstanceOfYear($year);
		$query->withPropertyBetween('date',$from,$to);
	}
	if ($filterKind=='status') {
		$query->withProperty('status',$year);
	}
	$result = $query->search();

	$writer = new ListWriter();

	$writer->startList();
	$writer->sort($sort,$direction);
	$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'M�lernummer','width'=>30));
	$writer->header(array('title'=>'V�rdi','width'=>30));
	$writer->header(array('title'=>'Afl�sningsdato'));
	$writer->header(array('title'=>'Opdateret'));
	$writer->header(array('title'=>'Status'));
	$writer->header(array('title'=>'Kilde'));
	$writer->endHeaders();

	foreach ($result->getList() as $object) {
		$meter = Watermeter::load($object->getWatermeterId());
		$writer->startRow(array( 'kind'=>'waterusage', 'id'=>$object->getId(), 'icon'=>$object->getIcon(), 'title'=>$object->getTitle() ));
		if ($meter) {
			$writer->startCell(array( 'icon'=>$meter->getIcon() ))->text( $meter->getNumber() )->			
				startIcons()->
					icon(array('icon'=>'monochrome/info','revealing'=>true,'action'=>true,'data'=>array('action'=>'meterInfo','id'=>$meter->getId())))->
				endIcons()->
			endCell();
		} else {
			$writer->startCell(array( 'icon'=>'common/warning' ))->text( 'Ikke fundet' )->endCell();
		}
		$writer->startCell(array( 'icon'=>$object->getIcon() ))->
			text($object->getValue())->
			startIcons()->
				icon(array('icon'=>'monochrome/info','revealing'=>true,'action'=>true,'data'=>array('action'=>'usageInfo','id'=>$object->getId())))->
			endIcons()->
		endCell()->
		startCell()->text(Dates::formatLongDate($object->getDate()))->endCell()->
		startCell()->text(Dates::formatFuzzy($object->getUpdated()))->endCell()->
		startCell(array('align'=>'center'))->
			startIcons()->
				icon(array('icon'=>WaterusageService::getStatusIcon($object->getStatus()),'action'=>true,'data'=>array('action'=>'usageStatus','id'=>$object->getId())))->
			endIcons()->
		
		endCell()->
		startCell(array('dimmed'=>true))->text(WaterusageService::getSourceText($object->getSource()))->endCell()->
		endRow();
	}
	$writer->endList();
}

function listLog($windowSize, $windowPage, $text) {
	$entries = LogService::getEntries(array('category'=>'waterusage'));

	$writer = new ListWriter();

	$writer->startList();
	//$writer->sort($sort,$direction);
	//$writer->window(array( 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ));
	$writer->startHeaders();
	$writer->header(array('title'=>'Tid','width'=>30));
	$writer->header(array('title'=>'Besked'));
	$writer->endHeaders();

	foreach ($entries as $entry) {
		$writer->startRow(array( 'kind'=>'logentry', 'icon'=>'common/file' ));
		$writer->startCell()->text(Dates::formatDateTime($entry['time']))->endCell();
		$writer->startCell()->text($entry['message'])->endCell();
		$writer->endRow();
	}
	$writer->endList();
}
?>