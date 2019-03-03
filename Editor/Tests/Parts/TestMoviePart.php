<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Parts
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestMoviePart extends UnitTestCase {

  function testLoad() {
    $this->assertNull(MoviePart::load(0));
  }

  function testCreate() {
    $obj = new MoviePart();
    $this->assertFalse($obj->isPersistent());
    $obj->save();
    $this->assertTrue($obj->isPersistent());
    $id = $obj->getId();
    $this->assertNotNull(MoviePart::load($id));
    $obj->remove();
    $this->assertNull(MoviePart::load($id));
  }

  function testProperties() {
    $obj = new MoviePart();
    $obj->setFileId(10);
    $obj->setImageId(4);
    $obj->save();

    $loaded = MoviePart::load($obj->getId());
    $this->assertEqual($loaded->getFileId(),10);
    $this->assertEqual($loaded->getImageId(),4);

    $loaded->remove();
  }

  function testVimeo() {
    $parsed = Strings::analyzeMovieURL('http://vimeo.com/94839196');
    $this->assertEqual('vimeo',$parsed['type']);
    $this->assertEqual('94839196',$parsed['id']);

    // HTTPS version
    $parsed = Strings::analyzeMovieURL('https://vimeo.com/94839196');
    $this->assertEqual('vimeo',$parsed['type']);
    $this->assertEqual('94839196',$parsed['id']);
  }

  function testBuildingFile() {
    $file = new File();
    $file->save();
    $file->publish();

    $obj = new MoviePart();
    $obj->setFileId($file->getId());
    $obj->save();

    $ctrl = new MoviePartController();
    $xml = $ctrl->build($obj,new PartContext());
    echo $xml;

    $file->remove();
    $obj->remove();
  }

  function testImport() {
    $obj = new MoviePart();
    $obj->setWidth('400px');
    $obj->setHeight('40%');
    $obj->setFileId(20);
    $obj->setImageId(120);
    $obj->setText('Get me back!');
    $obj->setCode('<iframe src="http://get.me/back"></iframe>');
    $obj->setUrl('http://get.me/back');

    $ctrl = new MoviePartController();

    $xml = $ctrl->build($obj,new PartContext());

    $this->assertNull($ctrl->importFromString(null));

    $imported = $ctrl->importFromString($xml);

    $this->assertNotNull($imported);
    $this->assertIdentical($imported->getWidth(),$obj->getWidth());
    $this->assertIdentical($imported->getHeight(),$obj->getHeight());
    $this->assertIdentical($imported->getFileId(),$obj->getFileId());
    $this->assertIdentical($imported->getImageId(),$obj->getImageId());
    $this->assertIdentical($imported->getText(),$obj->getText());
    $this->assertIdentical($imported->getCode(),$obj->getCode());
    $this->assertIdentical($imported->getUrl(),$obj->getUrl());
  }
}
?>