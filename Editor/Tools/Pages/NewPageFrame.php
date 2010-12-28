<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once 'PagesController.php';

$info = PagesController::getNewPageInfo();
if (requestGetExists('design')) {
	$info['design']=requestGetNumber('design');
}
PagesController::setNewPageInfo($info);

$close = getToolSessionVar('pages','rightFrame');
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
'<row><icon icon="Basic/Color" title="V�lg design" style="Disabled"/></row>'.
'<row><icon icon="Web/Frame" title="V�lg ops�tning" style="Hilited"/></row>';
if ($info['fixedHierarchy']==0) {
	$gui.='<row><icon icon="Element/Structure" title="Menupunkt"/></row>';
	$next = 'NewPageHierarchyItem.php';
}
else {
	$next = 'NewPageProperties.php';
}
$gui.=
'<row><icon icon="Basic/Info" title="Angiv egenskaber"/></row>'.
'</group>'.
'</cell><cell width="99%">'.
'<area xmlns="uri:Area" width="100%" height="100%"><content padding="10">'.
'<text xmlns="uri:Text" align="center" bottom="5">'.
'<strong>V�lg grundops�tning</strong>'.
'<break/><small>Klik p� den ops�tning den nye side skal anvende. Ops�tningen bestemmer bl.a. navigation, s�gefelt og nyheder i sidens omsluttende ramme.</small>'.
'</text>'.
'<overflow xmlns="uri:Layout" height="300">'.
'<group xmlns="uri:Icon" size="3" titles="right" spacing="3" wrapping="true">';

$sql="select frame.id,frame.name,frame.searchenabled,frame.userstatusenabled,hierarchy.name as hierarchy from frame left join hierarchy on frame.hierarchy_id=hierarchy.id order by frame.name";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<row><icon'.
	' link="'.$next.'?frame='.$row['id'].'"'.
	' icon="Web/Frame"'.
	' title="'.encodeXML($row['name']).'" description="'.buildFrameDescription($row).'"'.
	($row['id']==$info['frame'] ? ' style="Hilited"' : '').
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
'<button title="Forrige" link="NewPageDesign.php" help="G� tilbage til forrige punkt"/>'.
($info['frame']>0
? '<button title="N�ste" link="'.$next.'" help="G� videre til n�ste punkt"/>'
: '<button title="N�ste" style="Disabled"/>'
).
'</group>'.
'</cell></row>'.
'</layout>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

function buildFrameDescription(&$row) {
	$out = array();
	if ($row['hierarchy']) {
		$out[]='Hierarki: '.$row['hierarchy'];
	}
	if ($row['searchenabled']) {
		$out[]='S�gning: ja';
	}
	if ($row['userstatusenabled']) {
		$out[]='Brugerstatus: ja';
	}
	return implode(', ',$out);
}

$elements = array("Window","Area","Layout","Icon","Text","Button");
writeGui($xwg_skin,$elements,$gui);
?>