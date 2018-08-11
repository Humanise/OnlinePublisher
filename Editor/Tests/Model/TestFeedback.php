<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestFeedback extends AbstractObjectTest {

  function __construct() {
    parent::__construct('feedback');
  }

  function testProperties() {
    $obj = new Feedback();
    $obj->setName('Jonas Munk');
    $obj->setEmail('jonas@munk.dk');
    $obj->setMessage('This is the text');
    $obj->save();

    $obj2 = Feedback::load($obj->getId());
    $this->assertEqual($obj2->getName(),'Jonas Munk');
    $this->assertEqual($obj2->getEmail(),'jonas@munk.dk');
    $this->assertEqual($obj2->getMessage(),'This is the text');

    $obj2->remove();
  }
}
?>