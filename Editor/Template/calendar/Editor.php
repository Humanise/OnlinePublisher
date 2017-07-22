<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.HTML
 */
require_once '../../Include/Private.php';

$gui = '
<gui xmlns="uri:hui" padding="10">
  <controller url="controller.js"/>
  <script>
    controller.id = ' . Request::getId() . ';
  </script>
  <box width="500" top="10" padding="10" title="Kalender">
    <formula name="formula">
      <fields labels="above">
        <field label="Titel:">
          <text-input key="title"/>
        </field>
        <field label="Start-time:">
          <number-input key="weekview_starthour" min="0" max="23"/>
        </field>
        <field label="Calendars">
          <checkboxes key="calendar">' . UI::buildOptions('calendar') . '</checkboxes>
        </field>
        <field label="Sources">
          <checkboxes key="calendarsource">' . UI::buildOptions('calendarsource') . '</checkboxes>
        </field>
      </fields>
      <buttons>
        <button text="Opdater" name="save" highlighted="true" disabled="true" submit="true"/>
      </buttons>
    </formula>
  </box>
</gui>
';
UI::render($gui);
?>