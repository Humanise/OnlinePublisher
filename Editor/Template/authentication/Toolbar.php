<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Page.php';
require_once '../../Include/XmlWebGui.php';

$tab = requestGetText('tab') | 'authentication';

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<dock xmlns="uri:Dock" orientation="Top">'.
'<tabgroup align="left">'.
($tab=='authentication' ?
	'<tab title="Autentifikation" style="Hilited"/>'.
	'<tab title="Avanceret" link="Toolbar.php?tab=advanced"/>'
:
	'<tab title="Autentifikation" link="Toolbar.php?tab=authentication"/>'.
	'<tab title="Avanceret" style="Hilited"/>'
).
'</tabgroup>'.
'<content>'.
'<tool title="Luk" icon="Basic/Close" link="../../Tools/Pages/index.php" target="Desktop"/>'.
'<divider/>';
if ($tab=='authentication') {
	$gui.=
	(Page::isChanged(getPageId())
	? '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" link="Publish.php" badge="!" badgestyle="Hilited"/>'
	: '<tool title="Udgiv" icon="Basic/Internet" overlay="Upload" style="Disabled"/>'
	).
	'<tool title="Vis �ndringer" icon="Basic/View" link="../../Services/Preview/" target="Desktop"/>'.
	'<tool title="Egenskaber" icon="Basic/Info" link="../../Tools/Pages/?action=pageproperties&amp;id='.getPageId().'" target="Desktop" help="Vis sidens egenskaber i side-v�rkt�jet"/>';
} else {
	$gui.=
	'<tool title="Historik" icon="Basic/Time" link="../../Services/PageHistory/" target="Editor" help="Oversigt over tidligere versioner af dokumentet"/>';
}
$gui.=
'</content>'.
'</dock>'.
'</xmlwebgui>';

$elements = array("Dock","DockForm","Script");
writeGui($xwg_skin,$elements,$gui);
?>