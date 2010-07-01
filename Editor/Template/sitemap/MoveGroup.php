<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Sitemap
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Page.php';

$id = requestGetNumber('id',0);
$dir = requestGetNumber('dir',0);


$sql="select * from sitemap_group where id=".$id;
$row = Database::selectFirst($sql);
$page=$row['page_id'];
$position=$row['position'];

$sql="select id from sitemap_group where page_id=".$page." and position=".($position+$dir);
$result = Database::select($sql);
if ($row = Database::next($result)) {
	$otherid=$row['id'];

	$sql="update sitemap_group set position=".($position+$dir)." where id=".$id;
	Database::update($sql);

	$sql="update sitemap_group set position=".$position." where id=".$otherid;
	Database::update($sql);
}
Database::free($result);


Page::markChanged($page);

redirect('Groups.php');
?>