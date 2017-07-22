<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Issues
 */
require_once '../../../Include/Private.php';

$list = Query::after('issuestatus')->orderByTitle()->get();

$writer = new ListWriter();

$writer->startList();

$writer->startHeaders()->
  header('Status')->
endHeaders();

foreach($list as $item) {
  $writer->startRow(['id' => $item->getId()])->
    startCell(['icon' => $item->getIcon()])->text($item->getTitle())->endCell()->
  endRow();
}
$writer->endList();
?>