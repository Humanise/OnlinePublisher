<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/International.php';
require_once '../../Classes/Hierarchy.php';

$id = requestGetNumber('id',0);
if (requestGetExists('return')) {
    $return = requestGetText('return');
} else {
    $return = "HierarchyFrame.php?id=".$id;
}


$hier = Hierarchy::load($id);

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface background="Desktop">'.
'<window xmlns="uri:Window" width="300" align="center" top="30">'.
'<titlebar title="Egenskaber for hierarki" icon="Basic/Info">'.
'<close link="'.$return.'"/>'.
'</titlebar>'.
'<content padding="5" background="true">'.
'<form xmlns="uri:Form" action="UpdateHierarchy.php" method="post" name="Formula" focus="name">'.
'<hidden name="id">'.$id.'</hidden>'.
'<hidden name="return">'.$return.'</hidden>'.
'<group size="Large">'.
'<textfield badge="Navn:" name="name">'.encodeXML($hier->getName()).'</textfield>'.
'<select badge="Sprog" name="language" selected="'.$hier->getLanguage().'">'.
'<option title="" value=""/>';
while ($language = current($languages)) {
    $gui.='<option value="'.key($languages).'" title="'.$language.'"/>';
    next($languages);
}
$gui.=
'</select>'.
'<buttongroup size="Large">'.
($hier->canDelete() ? 
'<button title="Slet" link="DeleteHierarchy.php?id='.$id.'"/>'
:
'<button title="Slet" style="Disabled"/>'
).
'<button title="Annuller" link="'.$return.'"/>'.
'<button title="Opdater" submit="true" style="Hilited"/>'.
'</buttongroup>'.
'</group>'.
'</form>'.
'</content>'.
'</window>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Window","Toolbar","Form");
writeGui($xwg_skin,$elements,$gui);
?>