<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Waterusage.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/UserInterface.php';
require_once '../../Classes/GuiUtils.php';

$year = Request::getInt('year');
$text = Request::getUnicodeString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);

$query = array(
	'windowSize' => $windowSize,
	'windowPage' => $windowPage,
	'sort' => 'title',
	'query' => $text
);

if ($year) {
	$query['year'] = $year;
}

$list = WaterUsage::search($query);

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>'Nummer','width'=>40));
$writer->header(array('title'=>'�r'));
$writer->header(array('title'=>'V�rdi'));
$writer->header(array('title'=>'Afl�sningsdato'));
$writer->header(array('title'=>'Opdateret'));
$writer->endHeaders();

foreach ($list['result'] as $object) {
	$writer->startRow(array( 'kind'=>'file', 'id'=>$object->getId(), 'icon'=>$object->getIn2iGuiIcon(), 'title'=>$object->getTitle() ));
	$writer->startCell(array( 'icon'=>$object->getIn2iGuiIcon() ))->text( $object->getNumber() )->endCell();
	$writer->startCell()->text($object->getYear())->endCell();
	$writer->startCell()->text($object->getValue())->endCell();
	$writer->startCell()->text(UserInterface::presentDateTime($object->getDate()))->endCell();
	$writer->startCell()->text(UserInterface::presentDateTime($object->getUpdated()))->endCell();
	$writer->endRow();
}
$writer->endList();
?>