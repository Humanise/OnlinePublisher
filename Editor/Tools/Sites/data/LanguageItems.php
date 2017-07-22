<?php
/**
 * @package OnlinePublisher
 * @subpackage Sites
 */
require_once '../../../Include/Private.php';

$counts = PageService::getLanguageCounts();

$writer = new ItemsWriter();

$writer->startItems();

foreach ($counts as $row) {
  $options = ['kind' => 'language'];
  if ($row['language'] == null || count($row['language']) == 0) {
    $options['icon'] = 'monochrome/round_question';
    $options['title'] = ['No language', 'da' => 'Intet sprog'];
  } else {
    $options['icon'] = GuiUtils::getLanguageIcon($row['language']);
    $options['title'] = GuiUtils::getLanguageName($row['language']);
  }
  $options['badge'] = $row['count'];
  $options['value'] = $row['language'];
  $writer->item($options);
}
$writer->endItems();
?>