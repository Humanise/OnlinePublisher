<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

class TestFiles extends UnitTestCase {
    
    function testGetFileExtension() {
        $this->assertTrue(FileSystemService::getFileExtension('filnavn.php')=='php');
        $this->assertTrue(FileSystemService::getFileExtension('filnavn.php.xml')=='xml');
        $this->assertTrue(FileSystemService::getFileExtension('.xml')=='xml');
        $this->assertTrue(FileSystemService::getFileExtension('xml')=='');
        $this->assertTrue(FileSystemService::getFileExtension('')=='');
    }
    
    function testGetFileTitle() {
        $this->assertTrue(FileSystemService::filenameToTitle('filnavn.php')=='Filnavn');
        $this->assertTrue(FileSystemService::filenameToTitle('filnavn.php.xml')=='Filnavn');
        $this->assertTrue(FileSystemService::filenameToTitle('filnavn')=='Filnavn');
        $this->assertTrue(FileSystemService::filenameToTitle('')=='');
    }
    
    function testGetExtension() {
        $this->assertTrue(FileSystemUtil::mimeTypeToExtension('text/html')=='html');
        $this->assertTrue(FileSystemUtil::extensionToMimeType('html')=='text/html');
    }
}
?>