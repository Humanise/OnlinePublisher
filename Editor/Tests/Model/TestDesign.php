<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestDesign extends AbstractObjectTest {
    
	function TestDesign() {
		parent::AbstractObjectTest('design');
	}

	function testProperties() {
		$obj = new Design();
		$obj->setTitle('My design');
		$obj->save();
		
		$obj2 = Design::load($obj->getId());
		$this->assertEqual($obj2->getTitle(),'My design');
		
		$obj2->remove();
	}
}
?>