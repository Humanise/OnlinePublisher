<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Tests
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

require_once($basePath . 'Editor/Libraries/simpletest/unit_tester.php');

class AbstractObjectTest extends UnitTestCase {

  private $type;

  function __construct($type) {
    //parent::UnitTestCase();
    $this->type = $type;
  }

  function testLoad() {
    Log::debug('Testing load!');
    if (!$this->type) {
      Log::debug('No type...');
      Log::debug($this);
    }
    $class = ucfirst($this->type);
    $obj = new $class();
    $this->assertNull($obj->load(0));
  }

  function testCreate() {

    $class = ucfirst($this->type);
    $obj = new $class();
    if (method_exists($this, 'makeValid')) {
      $this->makeValid($obj);
    }
    $this->assertFalse($obj->isPersistent());
    $obj->save();
    $this->assertTrue($obj->isPersistent());
    $id = $obj->getId();

    $obj->setNote('This is the note');
    $obj->save();
    $loaded = $obj->load($id);
    $this->assertEqual('This is the note', $loaded->getNote());

    $this->assertNotNull($obj->load($id));
    $this->assertNotNull(ObjectService::loadAny($id));
    $obj->remove();
    $loaded = $obj->load($id);
    $this->assertNull($loaded);
    if ($loaded) {
      Log::debug($loaded);
    }
  }

}