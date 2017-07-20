<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../Include/Private.php';

$pageId = InternalSession::getPageId();

$gui='
<gui xmlns="uri:hui" title="Links" padding="30">
  <controller url="js/controller.js"/>
  <source name="listSource" url="data/HistoryList.php">
    <parameter key="pageId" value="'.$pageId.'"/>
  </source>
  <box width="700" title="Historik">
    <toolbar>
      <icon icon="common/close" text="Luk" name="close"/>
    </toolbar>
  </box>
</gui>';

UI::render($gui);
?>