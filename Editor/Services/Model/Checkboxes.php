<?php
/**
 * @package OnlinePublisher
 * @subpackage Customers
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Object.php';
require_once '../../Classes/Request.php';

$type = Request::getString('type');

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>';
echo '<checkboxes>';

$query = array('type'=>$type);

$list = Object::find($query);
$objects = $list['result'];
foreach ($objects as $object) {
	echo '<checkbox value="'.$object->getId().'" label="'.In2iGui::escape($object->getTitle()).'"/>';
}

echo '</checkboxes>';
?>