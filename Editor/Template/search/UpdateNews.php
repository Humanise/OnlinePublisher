<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Search
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = requestPostNumber('id',0);
$label = requestPostText('label');

$mode = requestPostText('mode');

$enabled = false;
$default = false;
$hidden = false;
if ($mode=='inactive') {
	// keep defaults
}
elseif ($mode=='possible') {
	$enabled = true;
	$default = false;
	$hidden = false;
}
elseif ($mode=='choosen') {
	$enabled = true;
	$default = true;
	$hidden = false;
}
elseif ($mode=='automatic') {
	$enabled = true;
	$default = true;
	$hidden = true;
}


$sql="update search set".
" newsenabled=".sqlBoolean($enabled).
",newslabel=".sqlText($label).
",newsdefault=".sqlBoolean($default).
",newshidden=".sqlBoolean($hidden).
" where page_id=".$id;
Database::update($sql);

$sql="update page set changed=now() where id=".$id;
Database::update($sql);


redirect('News.php');
?>