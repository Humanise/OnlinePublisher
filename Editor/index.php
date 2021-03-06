<?php
/**
 * Displays the base frameset of the internal system
 *
 * @package OnlinePublisher
 * @subpackage Base
 * @category Interface
 */
if (!file_exists('../Config/Setup.php')) {
  header('Location: ../setup/initial/');
  exit;
}
require_once 'Include/Private.php';

$start = 'Services/Start/';
if (Request::exists("page")) {
  $page = Request::getInt('page');
  InternalSession::setPageId($page);
  $start = 'Services/Preview/';
}

$unpublished = PublishingService::getTotalUnpublishedCount();
if ($unpublished == 0) {
  $unpublished = '';
}

$categorized = ToolService::getCategorized();

$lang = InternalSession::getLanguage();

$gui = '
<gui xmlns="uri:hui" title="Humanise Editor">

  <source name="searchSource" url="Services/Base/data/Search.php">
    <parameter key="text" value="@search.value"/>
  </source>
  <source name="hierarchySource" url="Services/Base/data/Hierarchy.php"/>
  <source name="issueSource" url="Services/Base/data/ListIssues.php"/>
  <source name="reviewSource" url="Services/Base/data/ListReview.php">
    <parameter key="subset" value="@reviewSubset.value"/>
  </source>

  <controller url="Services/Base/controller.js"/>

  <dock url="' . $start . '" name="dock" position="bottom" frame-name="Desktop">
    <sidebar collapsed="true">
      <bar variant="layout_mini">
        <button icon="monochrome/hierarchy" name="navPages" selected="true"/>
        <button icon="monochrome/note" name="navNotes"/>
        <button icon="monochrome/stamp" name="navReview"/>
        <!--<button icon="monochrome/warning" name="navWarnings"/>-->
      </bar>
      <bar variant="layout" name="searchBar">
        <searchfield adaptive="true" name="search"/>
      </bar>
      <bar variant="layout" name="reviewBar" visible="false">
        <dropdown value="unreviewed" name="reviewSubset">
          <option text="Ikke revideret" value="unreviewed"/>
          <option text="Godkendte" value="accepted"/>
          <option text="Afviste" value="rejected"/>
        </dropdown>
        <!--
        <segmented value="day" name="reviewSpan">
          <option text="Vis alle" value="all"/>
          <option text="Et døgn" value="day"/>
          <option text="7 dage" value="week"/>
        </segmented>
        -->
      </bar>
      <overflow>
        <list name="list" source="searchSource" visible="false"/>
        <selection value="all" name="selector">
          <options source="hierarchySource"/>
        </selection>
      </overflow>
    </sidebar>

    <tabs small="true">';
      $tabs = ['edit' => '{ Editing ; da: Redigering }', 'analyse' => '{Analysis ; da:Analyse}', 'setup' => '{ Setup ; da:Opsætning }'];
      foreach ($tabs as $tab => $tabTitle) {
        $tools = @$categorized[$tab];
        if ($tools) {
          $gui .= '<tab title="' . $tabTitle . '" background="light"><toolbar name="' . $tab . 'Toolbar" left="5">';
          foreach ($tools as $key => $tool) {
            $deprecated = $tool->key == 'Pages';
            $gui .= '<icon text="' . $tool->name->$lang . '" icon="' . $tool->icon . '" click="dock.setUrl(\'Tools/' . $tool->key . '/\')" key="tool:' . $tool->key . '"' . ($deprecated ? ' overlay="warning"' : '') . '/>';
          }
          $gui .= '
          <right>
          <icon text="{ View ; da:Vis }" icon="common/view" click="dock.setUrl(\'Services/Preview/\')" key="service:preview"/>
          <icon text="{ Edit ; da:Rediger }" icon="common/edit" click="dock.setUrl(\'Template/Edit.php/\')" key="service:edit"/>
          <icon text="{ Publish ; da:Udgiv }" icon="common/internet" overlay="upload" click="baseController.goPublish()" badge="' . $unpublished . '" key="service:publish"/>
          <divider/>
          <icon text="Start" icon="common/play" click="dock.setUrl(\'Services/Start/\')" key="service:start"/>
          <icon text="{ Exit ; da: Log ud }" icon="common/stop" click="document.location=\'Authentication.php?logout=true\'"/>
          </right>
          </toolbar></tab>';
        }
      }
      $gui .= '
    </tabs>
  </dock>

  <boundpanel name="issuePanel" width="250">
    <form name="issueFormula">
      <fields labels="above">
        <field label="Note:">
          <text-input key="text" breaks="true"/>
        </field>
        <field label="Type">
          <radiobuttons value="improvement" key="kind">
            <option value="improvement" text="{Improvement; da:Forbedring}"/>
            <option value="error" text="{Error; da:Fejl}"/>
            <option value="unknown" text="{Unknown; da:Ukendt}"/>
          </radiobuttons>
        </field>
      </fields>
      <buttons>
        <button text="{Delete; da:Slet}" name="deleteIssue" small="true">
          <confirm text="{Are you sure?; da:Er du sikker?}" ok="{Yes, delete; da:Ja, slet}" cancel="{No; da:Nej}"/>
        </button>
        <button text="{Cancel; da:Annuller}" name="cancelIssue" small="true"/>
        <button text="{Save; da:Gem}" highlighted="true" submit="true" small="true" name="saveIssue"/>
      </buttons>
    </form>
  </boundpanel>
</gui>';

UI::render($gui);
?>
