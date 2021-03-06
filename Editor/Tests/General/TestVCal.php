<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.General
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestVCal extends UnitTestCase {

  function testIt() {
    $path = TestService::getResourceUrl('ical.ics');
    $parser = new VCalParser();
    $cal = $parser->parseUrl($path);
    $this->assertTrue($cal !== false);

    $this->assertEqual('2.0',$cal->getVersion());
    $this->assertEqual('My calendar',$cal->getTitle());
    $this->assertEqual('Europe/Copenhagen',$cal->getTimeZone());

    $events = $cal->getEvents();
    $this->assertEqual(count($events),3);

    $first = $events[0];
    $this->assertEqual($first->getSummary(),'My event');
    $this->assertEqual($first->getStartDate(),1290088800);
    $this->assertEqual($first->getEndDate(),1290096000);
    $this->assertEqual(gmdate("M d Y H:i:s", $first->getStartDate()),"Nov 18 2010 14:00:00");
    $this->assertEqual(date("M d Y H:i:s", $first->getStartDate()),"Nov 18 2010 15:00:00");
    $this->assertEqual(gmdate("M d Y H:i:s", $first->getEndDate()),"Nov 18 2010 16:00:00");
    $this->assertEqual($first->getDuration(),null);

    $next = $events[1];
    $this->assertEqual($next->getUrl(),"http://www.jonasmunk.dk");

    $third = $events[2];
    $this->assertEqual($third->getDescription(),"This is the note\næøå\nHep hey");
  }
}