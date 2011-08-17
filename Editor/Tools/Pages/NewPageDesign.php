<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../Include/Private.php';
require_once '../../Include/XmlWebGui.php';

require_once 'PagesController.php';

$info = PagesController::getNewPageInfo();
if (Request::exists('template')) {
	$info['template'] = Request::getInt('template',0);
}
PagesController::setNewPageInfo($info);

$close = InternalSession::getToolSessionVar('pages','rightFrame');
$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="500" height="300" align="center">'.
'<titlebar title="Ny side" icon="Tool/Assistant">'.
'<close link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<layout xmlns="uri:Layout" width="100%" height="100%">'.
'<row><cell valign="top">'.
'<group xmlns="uri:Icon" size="1" titles="right" spacing="6" wrapping="false">'.
'<row><icon icon="Element/Template" title="V�lg skabelon" style="Disabled"/></row>'.
'<row><icon icon="Basic/Color" title="V�lg design" style="Hilited"/></row>'.
'<row><icon icon="Web/Frame" title="V�lg ops�tning"/></row>';
if ($info['fixedHierarchy']==0) {
	$gui.='<row><icon icon="Element/Structure" title="Menupunkt"/></row>';
}
$gui.=
'<row><icon icon="Basic/Info" title="Angiv egenskaber"/></row>'.
'</group>'.
'</cell><cell width="99%">'.
'<area xmlns="uri:Area" width="100%" height="100%"><content padding="10">'.
'<text xmlns="uri:Text" align="center" bottom="5">'.
'<strong>V�lg design</strong>'.
'<break/><small>Klik p� det design du vil bruge til den nye side</small>'.
'</text>'.
'<overflow xmlns="uri:Layout" height="300">'.
'<group xmlns="uri:Icon" size="3" titles="right" spacing="5" wrapping="true">';

$sql="select * from design,object where object.id=design.object_id order by `unique`";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$props = DesignService::getInfo($row['unique']);
	$gui.='<row><icon'.
	' link="NewPageFrame.php?design='.$row['object_id'].'"'.
	' image="../../../style/'.$row['unique'].'/info/Preview128.png"'.
	' title="'.StringUtils::escapeXML($row['title']).'"'.
	' description="'.StringUtils::escapeXML($props->description).'"'.
	($row['id']==$info['design'] ? ' style="Hilited"' : '').
	'/></row>';
}
Database::free($result);

$gui.=
'</group>'.
'</overflow>'.
'</content></area>'.
'</cell></row>'.
'<row><cell colspan="2">'.
'<group size="Large" xmlns="uri:Button" align="right" top="5">'.
'<button title="Annuller" link="'.$close.'" help="Afbryd oprettelse af den nye side"/>'.
'<button title="Forrige" link="NewPageTemplate.php" help="G� tilbage til forrige punkt"/>'.
($info['design']>0
? '<button title="N�ste" link="NewPageFrame.php" help="G� videre til n�ste punkt"/>'
: '<button title="N�ste" style="Disabled"/>'
).
'</group>'.
'</cell></row>'.
'</layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Area","Layout","Icon","Text","Button");
writeGui($xwg_skin,$elements,$gui);
?>