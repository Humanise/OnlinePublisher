<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

// THE ENCODING OF THIS FILE SHOULD BE UTF-8

class TestStrings extends UnitTestCase {

  function testTest() {
    $result = preg_replace_callback("/&#(0|30);/", function() {return ''; }, 'hep &#30; &#0;hey');
    $this->assertEqual($result,'hep  hey');
  }

    function testStripInvalidXml() {
        $this->assertEqual('-1',Strings::stripInvalidXml('-1'));
        $this->assertEqual('-1',Strings::stripInvalidXml(-1));
        $this->assertEqual('0',Strings::stripInvalidXml(0));
        $this->assertEqual(' ',Strings::stripInvalidXml("\0"));
  }

    function testToString() {
        $this->assertEqual("0",Strings::toString(0));
  }

    function testSplitIntegers() {
        $this->assertIdentical([0],Strings::splitIntegers("0"));
        $this->assertIdentical([],Strings::splitIntegers(""));
        $this->assertIdentical([],Strings::splitIntegers(null));
        $this->assertIdentical([0],Strings::splitIntegers(0));
        $this->assertIdentical([0,-23,56],Strings::splitIntegers("0,-23,56"));
        $this->assertIdentical([0,-23,56],Strings::splitIntegers("0 ,  -23 , 56   ,x"));
        $this->assertIdentical([-23,56],Strings::splitIntegers("0xx ,  -23 , 56   ,x"));
  }

    function testEscaping() {
        $this->assertEqual("0",Strings::escapeXML(0));
        $this->assertEqual("0",Strings::escapeXML("0"));
        $this->assertEqual("&#60;&#62;&#38;",Strings::escapeXML('<>&'));
        $this->assertEqual("&quot;",Strings::escapeXML('"'));
        $this->assertEqual("&#38;",Strings::escapeXML('&'));
        $this->assertEqual("-",Strings::escapeXML('-'));
        $this->assertEqual("+",Strings::escapeXML('+'));
        $this->assertEqual("&#226;&#243;&#8220;",Strings::escapeXML('–'));

    $this->assertEqual("-",Strings::fromUnicode('–'),"Long dash is replaced with short dash");
    $this->assertEqual("\"",Strings::fromUnicode('”'),"Curcly quotes are replaced with normal quotes");
    $this->assertEqual("\"",Strings::fromUnicode('“'),"Curcly quotes are replaced with normal quotes");
    // TODO: is this correct?
    $this->assertEqual("&#230;",Strings::escapeXML(Strings::fromUnicode('æ')));
    }

    function testUnicode() {
    // The encoding of this file should be UTF-8

    $this->assertEqual("æ",Strings::fromUnicode('Ã¦'));
    $this->assertEqual("Ã¦",Strings::toUnicode('æ'));
    $this->assertEqual("æ",Strings::toUnicode(Strings::fromUnicode('æ')));

    $obj = ['first'=>'æ','sub'=>['one'=>'æ']];
    $obj = Strings::toUnicode($obj);
    $this->assertEqual("Ã¦",$obj['first']);
    $this->assertEqual("Ã¦",$obj['sub']['one']);

    $group = new Imagegroup();
    $group->setTitle('æ');
    Strings::toUnicode($group);
    $this->assertEqual("Ã¦",$group->getTitle());
  }

    function testBlank() {
        $this->assertTrue(Strings::isBlank(null));
        $this->assertFalse(Strings::isNotBlank(null));

    $this->assertTrue(Strings::isBlank(""));
    $this->assertFalse(Strings::isNotBlank(""));

    $this->assertTrue(Strings::isBlank(" "));
    $this->assertFalse(Strings::isNotBlank(" "));

    $this->assertTrue(Strings::isBlank("   "));
    $this->assertFalse(Strings::isNotBlank("   "));

    $this->assertTrue(Strings::isBlank("\n"));
    $this->assertFalse(Strings::isNotBlank("\n"));

    $this->assertFalse(Strings::isBlank("abc"));
    $this->assertTrue(Strings::isNotBlank("abc"));
    }

    function testSummarize() {
    $this->assertEqual("onc<highlight>e</highlight> upon a tim<highlight>e</highlight>",Strings::summarizeAndHighlight(["e"],"once upon a time"));
  }

    function testNumericEntities() {
        $str = Strings::fromUnicode("Æbleø");
    $this->assertEqual("&#198;ble&#248;",Strings::htmlNumericEntities($str));
  }

  function testInsertEmail() {
    $this->assertEqual('lorem ipsum <a href="mailto:me@my.com">me@my.com</a> hep hep',Strings::insertEmailLinks('lorem ipsum me@my.com hep hep'));
    $this->assertEqual('lorem ipsum me@my @my.com hep hep',Strings::insertEmailLinks('lorem ipsum me@my @my.com hep hep'));
  }

  function testAppendWordToString() {
    $this->assertEqual('Jonas Munk',Strings::appendWordToString('Jonas','Munk',' '));
    $this->assertEqual('Jonas  Munk',Strings::appendWordToString('Jonas ','Munk',' '));
    $this->assertEqual('Munk',Strings::appendWordToString('','Munk',' '));
    $this->assertEqual('Munk',Strings::appendWordToString(null,'Munk',' '));
    $this->assertEqual('0 Munk',Strings::appendWordToString(0,'Munk',' '));
    $this->assertEqual('',Strings::appendWordToString(null,'',' '));
    $this->assertEqual('',Strings::appendWordToString(null,null,' '));
  }

  function testStartsWith() {
    $this->assertTrue(Strings::startsWith('Merge branch','Merge'));
  }

  function testEndsWith() {
    $this->assertTrue(Strings::endsWith('Merge branch','branch'));
    $this->assertTrue(Strings::endsWith('http://www.humanize.dk/','/'));
  }

  function testRemoveTags() {
    $this->assertEqual('Jonas Munk',Strings::removeTags('Jonas <b>Munk</b>'));
    $this->assertEqual('Jonas Munk',Strings::removeTags('Jonas <b >Munk</b>'));
    $this->assertEqual('Jonas Munk',Strings::removeTags('Jonas <b style="._kfdjkjs">Munk</b>'));
    $this->assertEqual('Jonas < > Munk',Strings::removeTags('Jonas < > Munk'));
    $this->assertEqual('Jonas <> Munk',Strings::removeTags('Jonas <> Munk'));
  }

  function testBuildIndex() {
    $this->assertEqual('Jonas Munk',Strings::buildIndex(['Jonas','Munk']));
    $this->assertEqual('Jonas Munk',Strings::buildIndex([' ','Jonas ',null,'  Munk',null,'']));
    $this->assertEqual('',Strings::buildIndex(null));
  }

  function testExtract() {
    $str = 'hep hey <table><tr><td>hdsjfhafkhk</td></tr></table> hey <table></table><table border="1">--</table>';
    $extracted = Strings::extract($str,'<table','table>');
    $this->assertEqual($extracted[0],'<table><tr><td>hdsjfhafkhk</td></tr></table>');
    $this->assertEqual($extracted[1],'<table></table>');
    $this->assertEqual($extracted[2],'<table border="1">--</table>');
  }

  function testConcatUrl() {
    $this->assertEqual(Strings::concatUrl(null,null),'');
    $this->assertEqual(Strings::concatUrl('http://www.humanize.dk/',null),'http://www.humanize.dk/');
    $this->assertEqual(Strings::concatUrl('http://www.humanize.dk','/path/to/somewhere.html'),'http://www.humanize.dk/path/to/somewhere.html');
    $this->assertEqual(Strings::concatUrl('http://www.humanize.dk/','path/to/somewhere.html'),'http://www.humanize.dk/path/to/somewhere.html');
    $this->assertEqual(Strings::concatUrl('http://www.humanize.dk/','/path/to/somewhere.html'),'http://www.humanize.dk/path/to/somewhere.html');
  }

  function testAnalyzeMovieUrl() {
    $this->assertNull(Strings::analyzeMovieURL(''));
    $this->assertNull(Strings::analyzeMovieURL(null));

    $result = Strings::analyzeMovieURL('http://vimeo.com/95415863');
    $this->assertEqual('vimeo',$result['type']);
    $this->assertEqual('95415863',$result['id']);

    $result = Strings::analyzeMovieURL('https://www.youtube.com/watch?v=cfOa1a8hYP8');
    $this->assertEqual('youtube',$result['type']);
    $this->assertEqual('cfOa1a8hYP8',$result['id']);

    $result = Strings::analyzeMovieURL('http://www.youtube.com/watch?v=cfOa1a8hYP8&jdskadjal');
    $this->assertEqual('youtube',$result['type']);
    $this->assertEqual('cfOa1a8hYP8',$result['id']);

    $result = Strings::analyzeMovieURL('https://www.youtube.com/watch?v=9q-HBMSSbp4');
    $this->assertEqual('youtube',$result['type']);
    $this->assertEqual('9q-HBMSSbp4',$result['id']);

    $result = Strings::analyzeMovieURL('https://www.youtube.com/watch?v=XqJU6PQM0_Q');
    $this->assertEqual('youtube',$result['type']);
    $this->assertEqual('XqJU6PQM0_Q',$result['id']);
  }
}
?>