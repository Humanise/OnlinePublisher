<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Base
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$text = Request::getString('text');
$kind = Request::getString('kind');

if ($issue = Issue::load($id)) {
  $issue->setTitle(Strings::shortenString($text,30));
  $issue->setNote($text);
  $issue->setKind($kind);
  $issue->save();
  $issue->publish();
}
?>