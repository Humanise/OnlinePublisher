<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Services
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestToolService extends UnitTestCase {

  function testIt() {
    $tools = ToolService::getAvailable();
    $this->assertTrue(count($tools)>0);
    $this->assertTrue(is_array($tools));
    $this->assertTrue(in_array('System',$tools),"The system tool is not present");
  }

  function testValid() {
    $tools = ToolService::getAvailable();
    foreach ($tools as $key) {
      $info = ToolService::getInfo($key);
      $this->assertTrue($info!=null,"The tool $key has no info");
      $this->assertEqual($info->key,$key,"The tool $key does not have the correct key");
    }
  }
}
?>