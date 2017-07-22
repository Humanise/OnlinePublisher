<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../Include/Private.php';

$type = Request::getString('type');
$queryString = Request::getString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort == '') $sort = 'title';
if ($direction == '') $direction = 'ascending';

$query = ['windowSize' => $windowSize, 'windowPage' => $windowPage, 'sort' => $sort, 'direction' => $direction];

if ($type != '') $query['type'] = $type;
if ($queryString != '') $query['query'] = $queryString;

$list = ObjectService::findAny($query);
$objects = $list['result'];

$writer = new ListWriter();

$writer->startList()->
  sort($sort,$direction)->
  window(['total' => $list['total'], 'size' => $list['windowSize'], 'page' => $list['windowPage']])->
  startHeaders()->
    header(['title' => ['Title', 'da' => 'Titel'], 'key' => 'title', 'sortable' => true])->
    header(['title' => ['Note', 'da' => 'Notat'], 'width' => 30]);
  if ($type == '') {
    $writer->header(['title' => 'Type', 'key' => 'type', 'sortable' => true, 'width' => 20]);
  }
  $writer->header(['title' => ['Modified', 'da' => 'Ændret'], 'key' => 'updated', 'sortable' => true, 'width' => 1]);
  $writer->endHeaders();

foreach ($objects as $object) {
  $writer->startRow(['id' => $object->getId(), 'kind' => $object->getType(), 'icon' => $object->getIcon(), 'title' => $object->getTitle()])->
    startCell(['icon' => $object->getIcon()])->
      text($object->getTitle())->
    endCell()->
        startCell()->startWrap()->text($object->getNote())->endWrap()->endCell();
    if ($type == '') {
      $writer->cell($object->getType());
    }
    $writer->startCell(['wrap' => false])->text(Dates::formatDateTime($object->getCreated()))->endCell();
  $writer->endRow();
}

$writer->endList();
?>