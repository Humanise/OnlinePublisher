<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.News
 */
require_once '../../../Include/Private.php';

$sourceId = Request::getInt('source');

if ($sourceId) {
  NewsService::synchronizeSource($sourceId);

  $writer = new ListWriter();

  $writer->startList();
  $writer->startHeaders();
  $writer->header(['title'=>['Title','da'=>'Titel'],'width'=>70]);
  $writer->header(['title'=>['Date','da'=>'Dato']]);
  $writer->endHeaders();

  $items = Query::after('newssourceitem')->withProperty('newssource_id',$sourceId)->orderBy('date')->descending()->get();

  foreach ($items as $item) {
    $writer->startRow()->
    startCell(['icon'=>'common/page'])->
      startLine()->text($item->getTitle())->endLine()->
      startLine(['dimmed'=>true])->text(Strings::shortenString(trim(Strings::removeTags($item->getText())),400))->endLine()->
    endCell()->
    startCell()->
      text(Dates::formatFuzzy($item->getDate()))->
    endCell()->
    endRow();
  }
  $writer->endList();
  exit;
}

$main = Request::getString('main');
$group = Request::getInt('group');
$type = Request::getString('type');
$queryString = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');

if (!$sort) {
  $sort='title';
}

$query = Query::after('news')->orderBy($sort)->withDirection($direction)->withWindowSize($windowSize)->withWindowPage($windowPage);
$query->withText($queryString);
$query->withCustom('group',$group);

if ($main=='latest') {
  $query->withCreatedMin(Dates::addDays(mktime(),-1));
} else if ($main=='active') {
  $query->withCustom('active',true);
} else if ($main=='inactive') {
  $query->withCustom('active',false);
} else if ($main=='url' || $main=='page' || $main=='email' || $main=='file') {
  $query->withCustom('linkType',$main);
}



$result = $query->search();
$objects = $result->getList();

$linkCounts = ObjectLinkService::getLinkCounts($objects);

$writer = new ListWriter();

$writer->startList()->
  sort($sort,$direction)->
  window([ 'total' => $result->getTotal(), 'size' => $windowSize, 'page' => $windowPage ])->
  startHeaders()->
    header(['title'=>['Title','da'=>'Titel'],'width'=>40,'key'=>'title','sortable'=>true])->
    header(['title'=>['Start date','da'=>'Startdato'],'key'=>'startdate','sortable'=>true])->
    header(['title'=>['End date','da'=>'Slutdato'],'key'=>'enddate','sortable'=>true])->
    header(['width'=>1])->
  endHeaders();

foreach ($objects as $object) {
  $active = false;
  if ($object->getStartDate()==null && $object->getEndDate()==null) {
    $active = true;
  } else if ($object->getEndDate()>time()) {
    $active = true;
  } else if ($object->getEndDate()==null && $object->getStartDate()<time()) {
    $active = true;
  }
  $writer->startRow(['kind'=>'news','id'=>$object->getId(),'icon'=>$object->getIcon(),'title'=>$object->getTitle()]);
  $writer->startCell(['icon'=>$object->getIcon()])->text($object->getTitle())->endCell();
  $writer->startCell();
  $writer->text(Dates::formatDateTime($object->getStartdate()))->endCell();
  $writer->startCell()->text(Dates::formatDateTime($object->getEnddate()))->endCell();
  $writer->startCell()->startIcons();
  if (!$active) {
    $writer->icon(['icon'=>'monochrome/invisible']);
  }
  //$writer->icon(array('icon'=>($active ? 'monochrome/play' : 'monochrome/invisible')));
  if (isset($linkCounts[$object->getId()]) && $linkCounts[$object->getId()]>0) {
    $writer->icon(['icon'=>"monochrome/link"]);
  }
  $writer->endIcons()->endCell();
  $writer->endRow();
}
$writer->endList();
?>