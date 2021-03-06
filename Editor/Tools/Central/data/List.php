<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Central
 */
require_once '../../../Include/Private.php';

$showTools = Request::getBoolean('tools');
$showTemplates = Request::getBoolean('templates');
$showEmail = Request::getBoolean('email');
$order = Request::getString('order');
$direction = Request::getString('direction');

if (!$order) {
  $order = 'title';
}

$query = Query::after('remotepublisher');
if ($order !== 'version') {
  $query->orderBy($order)->withDirection($direction);
}
$objects = $query->get();

$sites = [];

$time = 60 * 60;
foreach ($objects as $site) {
  $data = RemoteDataService::getRemoteData($site->getUrl() . 'services/info/json/',$time);
  $obj = null;
  $date = 0;
  if ($data->isHasData()) {
    $str = file_get_contents($data->getFile());
    $obj = Strings::fromJSON($str);
    if ($obj) {
      $version = Dates::formatLongDate($obj->date);
      $date = $obj->date;
    } else {
      $version = 'Not set';
    }
  } else {
    $version = 'Unknown';
  }
  $sites[] = ['site' => $site, 'object' => $obj, 'version' => $version, 'date' => $date];
}

if ($order == 'version') {
    function _comparator($a,$b) {
        $a = $a['date'];
        $b = $b['date'];
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
  }
  usort($sites,'_comparator');
  if ($direction == 'descending') {
    $sites = array_reverse($sites);
  }
}

$writer = new ListWriter();

$writer->startList(['unicode' => true])->
  sort($order,$direction)->
  startHeaders()->
  header(['title' => ['Title', 'da' => 'Titel'], 'key' => 'title', 'sortable' => true])->
  header(['title' => ['Address', 'da' => 'Adresse'], 'key' => 'url', 'sortable' => true]);
$writer->header(['title' => 'Version', 'key' => 'version', 'sortable' => true]);
$writer->header(['title' => 'Heartbeat', 'key' => 'heartbeat', 'sortable' => true]);
$writer->header(['title' => 'Inspection', 'key' => 'inspection']);
if ($showTools) {
  $writer->header(['title' => ['Tools', 'da' => 'Værktøjer']]);
}
if ($showTemplates) {
  $writer->header(['title' => ['Templates', 'da' => 'Skabeloner']]);
}
if ($showEmail) {
  $writer->header(['title' => 'E-mail']);
}
$writer->endHeaders();

foreach ($sites as $row) {
  $site = $row['site'];
  $obj = $row['object'];
  $version = $row['version'];
  $writer->startRow(['kind' => 'remotepublisher', 'id' => $site->getId()])->
    startCell(['wrap' => false])->text($site->getTitle())->endCell()->
    startCell()->text($site->getUrl())->endCell()->
    startCell(['wrap' => false])->text($version)->endCell()->
    startCell(['wrap' => false])->text(isset($obj->heartbeat) ? Dates::formatLongDate($obj->heartbeat) : '-')->endCell()->
    startCell();
  if (isset($obj->inspection)) writeInspection($writer, $obj->inspection);
  $writer->endCell();
  if ($showTools) {
    $writer->startCell(['wrap' => false]);
    writeTools($writer,$obj);
    $writer->endCell();
  }
  if ($showTemplates) {
    $writer->startCell(['wrap' => false]);
    writeTemplates($writer,$obj);
    $writer->endCell();
  }
  if ($showEmail) {
    $writer->startCell(['wrap' => false]);
    writeEmail($writer, $obj);
    $writer->endCell();
  }
  $writer->endRow();
}

$writer->endList();

function writeInspection(ListWriter $writer, stdClass $ins = null) {
  if ($ins) {
    if (isset($ins->ok)) {
      $writer->object(['icon' => 'common/ok', 'text' => $ins->ok]);
    }
    if (isset($ins->warning)) {
      $writer->object(['icon' => 'common/warning', 'text' => $ins->warning]);
    }
    if (isset($ins->error)) {
      $writer->object(['icon' => 'common/stop', 'text' => $ins->error]);
    }
  }
}

function writeTemplates(ListWriter $writer, stdClass $obj = null) {
  if ($obj && property_exists($obj,'templates')) {
    $installed = $obj->templates->installed;
    $used = $obj->templates->used;
    foreach ($installed as $template) {
      $writer->startLine()->object(['icon' => in_array($template,$used) ? 'common/ok' : 'monochrome/round_question', 'text' => $template])->endLine();
    }
  } else {
    $writer->object(['icon' => 'monochrome/warning', 'text' => ['Not available', 'da' => 'Ikke tilgængelig']]);
  }
}

function writeTools(ListWriter $writer, stdClass $obj = null) {
  if ($obj && property_exists($obj,'tools')) {
    $installed = $obj->tools->installed;
    if (!$installed || !is_array($installed)) {
      $writer->text('not an array');
      return;
    }
    foreach ($installed as $tool) {
      $writer->startLine()->object(['icon' => 'common/ok', 'text' => $tool])->endLine();
    }
  } else {
    $writer->object(['icon' => 'monochrome/warning', 'text' => ['Not available', 'da' => 'Ikke tilgængelig']]);
  }
}

function writeEmail(ListWriter $writer, stdClass $obj = null) {
  if ($obj && property_exists($obj,'email')) {
    $email = $obj->email;
    if ($email->enabled) {
      $writer->startLine()->object(['icon' => 'common/ok', 'text' => ['Enabled', 'da' => 'Slået til']])->endLine();
    } else {
      $writer->startLine()->object(['icon' => 'common/stop', 'text' => ['Disabled', 'da' => 'Slået fra']])->endLine();
    }
    $writer->startLine()->object(['icon' => $email->server ? 'common/ok' : 'common/stop', 'text' => 'Server'])->endLine();
    $writer->startLine()->object(['icon' => $email->username ? 'common/ok' : 'common/stop', 'text' => ['Username', 'da' => 'Brugernavn']])->endLine();
    $writer->startLine()->object(['icon' => $email->password ? 'common/ok' : 'common/stop', 'text' => ['Password', 'da' => 'Kodeord']])->endLine();
    $writer->startLine()->object(['icon' => $email->standardEmail ? 'common/ok' : 'common/stop', 'text' => ['Standard e-email', 'da' => 'Standard e-post']])->endLine();
    $writer->startLine()->object(['icon' => $email->standardName ? 'common/ok' : 'common/stop', 'text' => ['Standard name', 'da' => 'Standard navn']])->endLine();
    $writer->startLine()->object(['icon' => $email->feedbackEmail ? 'common/ok' : 'common/stop', 'text' => ['Feedback e-email', 'da' => 'Feedback e-post']])->endLine();
    $writer->startLine()->object(['icon' => $email->feedbackName ? 'common/ok' : 'common/stop', 'text' => ['Feedback name', 'da' => 'Feedback navn']])->endLine();
  } else {
    $writer->object(['icon' => 'monochrome/warning', 'text' => ['Not available', 'da' => 'Ikke tilgængelig']]);
  }
}