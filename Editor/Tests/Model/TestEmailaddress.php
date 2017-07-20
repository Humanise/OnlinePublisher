<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestEmailaddress extends AbstractObjectTest {

  function TestEmailaddress() {
    parent::AbstractObjectTest('emailaddress');
  }

  function testProperties() {
    $obj = new Emailaddress();
    $obj->setTitle('My group');
    $obj->setAddress('hephey@domain.com');
    $obj->setContainingObjectId(100);
    $obj->save();

    $loaded = Emailaddress::load($obj->getId());
    $this->assertEqual($obj->getTitle(),$loaded->getTitle());
    $this->assertEqual($obj->getAddress(),$loaded->getAddress());
    $this->assertEqual($obj->getContainingObjectId(),$loaded->getContainingObjectId());

    $loaded->remove();
  }
}
?>