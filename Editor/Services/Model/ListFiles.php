<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Interface/ListWriter.php';
require_once '../../Classes/Model/Object.php';
require_once '../../Classes/Core/Request.php';

$queryString = Request::getUnicodeString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort','title');
$direction = Request::getString('direction','ascending');

$result = Query::after('file')->withWindowSize($windowSize)->withWindowPage($windowPage)->withDirection($direction)->orderBy($sort)->withText($queryString)->search();
$objects = $result->getList();

$writer = new ListWriter();

$writer->startList()->
	sort($sort,$direction)->
	window(array('total'=>$result->getTotal(),'size'=>$result->getWindowSize(),'page'=>$result->getWindowPage()))->
	startHeaders()->
		header(array('title'=>'Titel','width'=>30,'key'=>'title','sortable'=>true))->
	endHeaders();
	foreach ($objects as $object) {
		$writer->startRow(array('id'=>$object->getId(),'kind'=>$object->getType(),'icon'=>$object->getIn2iGuiIcon(),'title'=>$object->getTitle()))->
			startCell(array('icon'=>$object->getIn2iGuiIcon()))->startWrap()->text($object->getTitle())->endWrap()->endCell()->
		endRow();
	}
$writer->endList();
?>