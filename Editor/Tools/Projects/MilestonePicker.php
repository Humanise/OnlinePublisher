<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Milestone.php';

$milestones = Milestone::search();

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<objects>';
foreach ($milestones as $milestone) {
	echo '<entity icon="'.$milestone->getIcon().'" title="'.encodeXML($milestone->getTitle()).'" value="'.$milestone->getId().'"/>';
}
echo '</objects>'
?>