<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class VCalendar {

  var $events = [];
  var $version;
  var $title;
  var $timeZone;

  function addEvent($event) {
    $this->events[] = $event;
  }

  function getEvents($comparator = null) {
    if ($comparator == 'startDate') {
      usort($this->events,['VCalendar', 'startDateComparator']);
    }
    return $this->events;
  }

  function setVersion($version) {
      $this->version = $version;
  }

  function getVersion() {
      return $this->version;
  }

  function setTitle($title) {
      $this->title = $title;
  }

  function getTitle() {
      return $this->title;
  }

  function setTimeZone($timeZone) {
      $this->timeZone = $timeZone;
  }

  function getTimeZone() {
      return $this->timeZone;
  }



  function startDateComparator($a, $b) {
    $a = $a->getStartDate();
    $b = $b->getStartDate();
    if (!$a) $a = 0;
    if (!$b) $b = 0;
      if ($a == $b) {
          return 0;
      }
      return ($a < $b) ? -1 : 1;
  }

}?>