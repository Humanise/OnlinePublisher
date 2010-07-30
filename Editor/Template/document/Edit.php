<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once 'Functions.php';

$id=getPageId();

$sql="select design.unique from page,design where page.design_id=design.id and page.id=".$id;
$row = Database::selectFirst($sql);
setDocumentDesign($row['unique']);

setDocumentRow(0);
setDocumentColumn(0);
setDocumentSection(0);
setDocumentToolbarTab('document');


$gui='
<frames xmlns="uri:In2iGui">
	<frame source="Toolbar.php" scrolling="false" name="Toolbar"/>
	<frame source="Frame.php" name="Frame"/>
</frames>';

In2iGui::render($gui);
?>
