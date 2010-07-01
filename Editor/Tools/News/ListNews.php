<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/News.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/UserInterface.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/DateUtil.php';
require_once '../../Classes/Services/ObjectLinkService.php';
require_once '../../Classes/Log.php';

$main = Request::getString('main');
$group = Request::getInt('group');
$type = Request::getString('type');
$queryString = Request::getUnicodeString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='title';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowPage' => $windowPage,'ordering' => $sort,'direction' => $direction);

if ($type!='') $query['type'] = $type;
if ($queryString!='') $query['query'] = $queryString;

if ($group>0) {
	$query['group'] = $group;
}
if ($type) {
	$query['type'] = $type;
}
if ($main=='latest') {
	$query['createdMin']=DateUtil::addDays(mktime(),-1);
} else if ($main=='active') {
	$query['active']=true;
} else if ($main=='inactive') {
	$query['active']=false;
} else if ($main=='url' || $main=='page' || $main=='email' || $main=='file') {
	$query['linkType']=$main;
}
$list = News::search2($query);
$objects = $list['result'];

$linkCounts = ObjectLinkService::getLinkCounts($objects);

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>'Titel','width'=>40,'key'=>'title','sortable'=>true));
$writer->header(array('title'=>'Startdato','key'=>'startdate','sortable'=>true));
$writer->header(array('title'=>'Slutdato','key'=>'enddate','sortable'=>true));
$writer->header(array('width'=>1));
$writer->endHeaders();

foreach ($objects as $object) {
	$active = false;
	if ($object->getStartDate()==null && $object->getEndDate()==null) {
		$active = true;
	} else if ($object->getEndDate()>time()) {
		$active = true;
	} else if ($object->getEndDate()==null && $object->getStartDate()<time()) {
		$active = true;
	}
	$writer->startRow(array('kind'=>'news','id'=>$object->getId(),'icon'=>$object->getIn2iGuiIcon(),'title'=>$object->getTitle()));
	$writer->startCell(array('icon'=>$object->getIn2iGuiIcon()))->text($object->getTitle())->endCell();
	$writer->startCell();
	$writer->text(UserInterface::presentDateTime($object->getStartdate()))->endCell();
	$writer->startCell()->text(UserInterface::presentDateTime($object->getEnddate()))->endCell();
	$writer->startCell()->startIcons();
	$writer->icon(array('icon'=>($active ? 'monochrome/play' : 'monochrome/stop')));
	if ($linkCounts[$object->getId()]>0) {
		$writer->icon(array('icon'=>"monochrome/attachment"));
	}
	$writer->endIcons()->endCell();
	$writer->endRow();
}
$writer->endList();
?>