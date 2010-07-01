<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Statistics
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Session.php';
require_once 'Functions.php';

$view = getRequestToolSessionVar('statistics','files.view','view','list');

$gui='<xmlwebgui xmlns="uri:XmlWebGui">'.
'<configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="100%" height="100%">'.
'<tabgroup size="Large">'.
'<tab title="Liste"'.($view=='list' ? ' style="Hilited"' : ' link="Files.php?view=list"').'/>'.
'<tab title="Graf"'.($view=='graph' ? ' style="Hilited"' : ' link="Files.php?view=graph"').'/>'.
'</tabgroup>'.
'<titlebar title="Filer" icon="Tool/Statistics"/>'.
'<content valign="top">'.
'<iframe xmlns="uri:Frame" source="'.($view=='list' ? 'FilesList.php' : 'Graph.php?data=FilesGraph.php').'"/>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Frame");

	
writeGui($xwg_skin,$elements,$gui);

?>