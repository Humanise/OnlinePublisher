<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Files
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/In2iGui.php';

$response = FileService::replaceUploadedFile(Request::getInt('id'));

if ($response['success']==true) {
	In2iGui::respondUploadSuccess();
} else {
	In2iGui::respondUploadFailure();
}
?>