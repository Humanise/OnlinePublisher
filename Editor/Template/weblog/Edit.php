<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Weblog
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface xmlns="uri:Frame">'.
'<dock align="top" id="Root" tabs="true">'.
'<frame name="Toolbar" source="Toolbar.php" scrolling="false"/>'.
'<frame name="Editor" source="Editor.php"/>'.
'</dock>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Frame");
writeGui($xwg_skin,$elements,$gui);
?>