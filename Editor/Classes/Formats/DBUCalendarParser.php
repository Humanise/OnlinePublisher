<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Formats
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class DBUCalendarParser {

  static function parseURL($url) {
    $string = @file_get_contents($url);
    if (!$string) {
      return false;
    }
    $cal = new DBUCalendar();
    $string = Strings::toUnicode($string);
    $table = HtmlTableParser::parseUsingHeader($string);

    $first = $table[0];
    if (is_array($first)) {     
      foreach ($first as $row) {
        $date = @$row['Dato'];
        $time = @$row['Kl.'];
        $home = @$row['Hjemmehold'];
        $away = @$row['Udehold'];
        $location = @$row['Spillested'];
        $score = @$row['Res'];
      
        if (Strings::isBlank($date) || Strings::isBlank($time)) {
          continue;
        }
        $parts = preg_split('/:/',$time);
        if (count($parts) != 2) {
          continue;
        }
        $parsed = Dates::parse($date);
        $parsed = Dates::addHours($parsed,intval($parts[0]));
        $startDate = Dates::addMinutes($parsed,intval($parts[1]));
        $endDate = Dates::addMinutes($parsed,60*1.75);
            
        $event = new DBUCalendarEvent();
        $event->setStartDate($startDate);
        $event->setEndDate($endDate);
        $event->setHomeTeam($home);
        $event->setGuestTeam($away);
        $event->setLocation($location);
        $event->setScore($score);
        $cal->addEvent($event);

      }
    }
    return $cal;
    
    
    //$reg = '/<tr><td[^>]*>[0-9]+<\/td><td[^>]*>[^<]{3}<\/td><td>([0-9]{4})-([0-9]{2})-([0-9]{2})<\/td><td>([0-9]{2}):([0-9]{2})<\/td><td>([^<]+)<\/td><td>([^<]+)<\/td><td>([^<]+)<\/td>/i';
    //$reg = '/<tr><td[^>]*>[0-9]+<\/td><td[^>]*>[^<]*<\/td><td[^>]*>[^<]*<\/td><td[^>]*>([0-9]{4})-([0-9]{2})-([0-9]{2})<\/td><td[^>]*>([0-9]{2}):([0-9]{2})<\/td><td[^>]*>([^<]+)<\/td><td[^>]*>([^<]*)<\/td><td[^>]*>([^<]*)<\/td><td[^>]*>([^<]*)<\/td>/i';
    //$reg = '/<tr><td[^>]*>[0-9]+<\/td><td[^>]*>[^<]*<\/td><td[^>]*>[^<]*<\/td><td[^>]*>([0-9]{4})-([0-9]{2})-([0-9]{2})<\/td><td>([0-9]{2}):([0-9]{2})<\/td><td>([^<]+)<\/td><td>([^<]+)<\/td><td>([^<]+)<\/td>/i';
    $reg = '/<tr><td[^>]*>[0-9]+<\/td><td[^>]*>[^<]*<\/td><td[^>]*>([0-9]{4})-([0-9]{2})-([0-9]{2})<\/td><td[^>]*>([0-9]{2}):([0-9]{2})<\/td><td[^>]*>([^<]+)<\/td><td[^>]*>([^<]*)<\/td><td[^>]*>([^<]*)<\/td><td[^>]*>([^<]*)<\/td>/i';
    preg_match_all($reg,$string, $matches);
    
    $pos = -1;
    
    while ($pos!==false) {
      $pos = strpos($string,'<tr',$pos+1);
      //Log::debug($pos);
      $end = strpos($string,'</tr>',$pos);
      
      //Log::debug(substr($string,$pos,$end-$pos));
    }
    for ($i=0; $i < count($matches[0]); $i++) {
      $event = new DBUCalendarEvent();

      $year = intval($matches[1][$i]);
      $month = intval($matches[2][$i]);
      $day = intval($matches[3][$i]);
      $hour = intval($matches[4][$i]);
      $minute = intval($matches[5][$i]);
      
      $homeTeam = $matches[6][$i];
      $guestTeam = $matches[7][$i];
      $location = $matches[8][$i];
      $score = $matches[9][$i];

      $startDate = mktime ( $hour, $minute, $second, $month, $day, $year);
      $event->setStartDate($startDate);

      $endDate = mktime ( $hour+1, $minute+45, $second, $month, $day, $year);
      $event->setEndDate($endDate);
      
      $event->setHomeTeam($homeTeam);
      $event->setGuestTeam($guestTeam);
      $event->setLocation($location);
      $event->setScore($score);
      $cal->addEvent($event);
    }
    return $cal;
  }
}
?>