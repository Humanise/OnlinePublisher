<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Include/Session.php';
require_once '../../Include/Images.php';
require_once '../../Classes/Image.php';
require_once 'ImagesController.php';

// hide warnings
error_reporting(E_ERROR);

$id = requestPostNumber('id');
$close = 'Image.php?id='.$id;

$response = replaceUploadedImage($id);

if ($response['success']==true) {
	redirect($close);
}
else {
	$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
	'<interface background="Desktop">'.
	'<window xmlns="uri:Window" width="300" align="center" top="30">'.
	'<titlebar title="Advarsel"/>'.
	'<content background="true">'.
	'<message xmlns="uri:Message" icon="Stop">'.
	'<title>Billedet kunne ikke erstattes</title>'.
	'<description>'.encodeXML($response['errorMessage']).'</description>'.
	($response['errorDetails']!=null ?
	'<error badge="Vis fejl">'.encodeXML($response['errorDetails']).'</error>'
	: '').
	'<buttongroup size="Large">'.
	'<button title="Annuller" link="'.$close.'"/>'.
	'<button title="Pr�v igen" link="ReplaceImage.php?id='.$id.'" style="Hilited"/>'.
	'</buttongroup>'.
	'</message>'.
	'</content>'.
	'</window>'.
	'</interface>'.
	'</xmlwebgui>';
	$elements = array("Window","Message");
	writeGui($xwg_skin,$elements,$gui);
}
?>