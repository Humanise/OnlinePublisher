<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class Dates {

  static function parseRFC822($str) {
    preg_match("/(.+)\, (\d+) (\w+) (\d+) (\d+):(\d+):(\d+) (.+)/i",$str, $matches);
    if (!$matches) {
      return null;
    }
    $months = array("Jan" => 1,"Feb" => 2,"Mar" => 3,"Apr" => 4,"May" => 5,"Jun" => 6,"Jul" => 7,"Aug" => 8,"Sep" => 9,"Oct" => 10,"Nov" => 11,"Dec" => 12);

    return gmmktime ( $matches[5],$matches[6],$matches[7], $months[$matches[3]],$matches[2], $matches[4]);
  }

  static function parseRFC3339($date) {
    if (Strings::isBlank($date)) {
      return null;
    }
    if (preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})([-+][0-9]{2}):([0-9]{2})/mi",$date, $matches)) {
      $diff = intval($matches[7]);
      return gmmktime( $matches[4]-$diff,$matches[5], $matches[6], $matches[2],$matches[3], $matches[1]);
    } else if (preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})Z/mi",$date, $matches)) {
      return gmmktime( $matches[4],$matches[5], $matches[6], $matches[2],$matches[3], $matches[1]);
    }
    Log::debug('Could not parse date: '.$date.' as RFC3339');
    return null;
  }

  /** The goal of this method is to parse anything */
  static function parse($str) {
    if ($time = Dates::parseRFC3339($str)) {
      return $time;
    }
    else if ($time = Dates::parseRFC822($str)) {
      return $time;
    }
    // DD-MM-YYYY
    else if (preg_match("/([0-9]{2})[-\/\.]([0-9]{2})[-\/\.]([0-9]{4})/mi",$str, $matches)) {
      return mktime( 0,0, 0, $matches[2],$matches[1], $matches[3]);
    }
    // YYYY-MM-DD
    else if (preg_match("/([0-9]{4})[-\/\.]([0-9]{1,2})[-\/\.]([0-9]{1,2})/mi",$str, $matches)) {
      return mktime( 0,0, 0, $matches[2],$matches[3], $matches[1]);
    }
    // DD-MM-YY
    else if (preg_match("/([0-9]{2})[-\/\.]?([0-9]{2})[-\/\.]?([0-9]{2})/mi",$str, $matches)) {
      return mktime( 0,0, 0, $matches[2],$matches[1], intval($matches[3])+2000);
    }
    return null;
  }

  static function buildTag($tagName,$stamp) {
    return '<'.$tagName.' unix="'.$stamp.'" day="'.date('d',$stamp).'" weekday="'.date('w',$stamp).'" yearday="'.date('z',$stamp).'" month="'.date('m',$stamp).'" year="'.date('Y',$stamp).'" hour="'.date('H',$stamp).'" minute="'.date('i',$stamp).'" second="'.date('s',$stamp).'" offset="'.date('Z',$stamp).'" timezone="'.date('T',$stamp).'"/>';
  }

  static function formatCSV($stamp) {
    if ($stamp) {
      return date("Y-m-d H:i:s",$stamp);
    }
    return '';
  }

  static function formatLongDateTime($timestamp,$locale="da_DK") {
    if ($timestamp) {
      setlocale(LC_TIME, $locale);
      return strftime("%e. %b %Y kl. %H:%M",$timestamp);
    } else {
      return '';
    }
  }

  static function formatShortDate($timestamp) {
    if ($timestamp) {
      setlocale(LC_TIME, "da_DK");
      return strftime("%e. %b",$timestamp);
    }
  }

  static function formatLongDate($timestamp,$locale="da_DK") {
    if ($timestamp) {
      setlocale(LC_TIME, $locale);
      return strftime("%e. %b %Y",$timestamp);
    } else {
      return '';
    }
  }

  static function formatDate($timestamp,$options=array()) {
    if ($timestamp==null) return '';
    $format = "%e. %B";
    if (isset($options['shortWeekday'])) {
      $format = "%a ".$format;
    }
    if (!isset($options['year']) || $options['year']) {
      $format.=' %Y';
    }
    setlocale(LC_TIME, "da_DK");
    return strftime($format,$timestamp);
  }

  static function formatDateTime($timestamp,$locale="da_DK") {
    if ($timestamp) {
      setlocale(LC_TIME, $locale);
      return strftime("%e. %b kl. %H:%M",$timestamp);
    } else {
      return '';
    }
  }

  static function formatShortTime($timestamp,$locale="da_DK") {
    if ($timestamp) {
      setlocale(LC_TIME, $locale);
      return strftime("%H:%M",$timestamp);
    }
  }

  static function formatLongDateTimeGM($timestamp,$locale="da_DK") {
    if ($timestamp) {
      setlocale(LC_TIME, $locale);
      return gmstrftime("%e. %b %Y kl. %H:%M",$timestamp);
    } else {
      return '';
    }
  }

  static function formatDuration($seconds,$locale="da_DK") {
    if ($seconds < 60) {
      return $seconds.' sekunder';
    }
    if ($seconds < 60 * 60) {
      return round($seconds / 60, 2).' minutter';
    }
    return round($seconds / 60 / 60, 2).' timer';
  }

  static function formatFuzzy($timestamp,$locale="da_DK") {
    if ($timestamp) {
      $diff = time() - $timestamp;
      if ($diff > -10) {
        if ($diff < 60) {
          if (InternalSession::getLanguage()=='da') {
            return 'for '.$diff.' sekunder siden';
          } else {
            return 'about '.$diff.' seconds ago';
          }
        } else if ($diff < 3600) {
          $minutes = floor($diff / 60);
          if (InternalSession::getLanguage()=='da') {
            return 'for '.$minutes.($minutes==1 ? ' minut siden' : ' minutter siden');
          } else {
            return 'about '.$minutes.($minutes==1 ? ' minute ago' : ' minutes ago');
          }
        } else if ($diff < 3600 * 24) {
          $minutes = floor($diff / 60 / 60);
          if (InternalSession::getLanguage()=='da') {
            return 'for '.$minutes.($minutes==1 ? ' time siden' : ' timer siden');
          } else {
            return 'about '.$minutes.($minutes==1 ? ' hour ago' : ' hours ago');
          }
        } else if ($diff < 3600 * 24 * 4) {
          $days = floor($diff / 3600 / 24);
          if (InternalSession::getLanguage()=='da') {
            return 'for '.$days.($days==1 ? ' dag' : ' dage').' siden kl. '.Dates::formatShortTime($timestamp,$locale);
          } else {
            return 'about '.$days.($days==1 ? ' day' : ' days').' ago at '.Dates::formatShortTime($timestamp,$locale);
          }
        }
      }
      if (strftime('%Y',time()) !== strftime('%Y',$timestamp)) {
        return Dates::formatLongDateTime($timestamp,$locale);
      } else {
        return Dates::formatDateTime($timestamp,$locale);
      }
    } else {
      return '';
    }
  }

  static function getCurrentYear() {
    return date('Y',time());
  }

  /**
   * Gets the start time of the week of the provided timestamp
   */
  static function getWeekStart($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $year = date('Y',$timestamp);
    $weekday = date('w',$timestamp);
    if ($weekday==0) {
      $weekday=6;
    } else {
      $weekday--;
    }
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime(0,0,0,$month,$date-$weekday,$year);
  }

  static function getWeekDay($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $weekday = date('w',$timestamp);
    if ($weekday==0) {
      $weekday=6;
    } else {
      $weekday--;
    }
    return $weekday;
  }

  static function getWeekEnd($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $year = date('Y',$timestamp);
    $weekday = date('w',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime(23,59,59,$month,$date-$weekday+7,$year);
  }

  static function getDayEnd($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime(23,59,59,$month,$date,$year);
  }

  static function getDayStart($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime(0,0,0,$month,$date,$year);
  }

  static function getMonthStart($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    return mktime(0,0,0,$month,1,$year);
  }

  static function getMonthEnd($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    return mktime(0,0,-1,$month+1,1,$year);
  }

  static function getFirstInstanceOfYear($year) {
    return mktime(0,0,0,1,1,$year);
  }

  static function getLastInstanceOfYear($year) {
    return mktime(0,0,-1,1,1,$year+1);
  }

  static function getYearStart($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $year = date('Y',$timestamp);
    return mktime(0,0,0,1,1,$year);
  }

  static function getYearEnd($timestamp=null) {
    if ($timestamp==null) {
      $timestamp = time();
    }
    $year = date('Y',$timestamp);
    return mktime(0,0,-1,1,1,$year+1);
  }

  static function getSecondsSinceMidnight($timestamp) {
    $hours = date('H',$timestamp);
    $minutes = date('i',$timestamp);
    $seconds = date('s',$timestamp);
    return $hours*60*60+$minutes*60+$seconds;
  }

  static function stripTime($timestamp) {
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime(0,0,0,$month,$date,$year);
  }

  static function addSeconds($timestamp,$secs) {
    $hours = date('H',$timestamp);
    $minutes = date('i',$timestamp);
    $seconds = date('s',$timestamp);
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime($hours,$minutes,$seconds+$secs,$month,$date,$year);
  }

  static function addMinutes($timestamp,$mins) {
    $hours = date('H',$timestamp);
    $minutes = date('i',$timestamp);
    $seconds = date('s',$timestamp);
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime($hours,$minutes+$mins,$seconds,$month,$date,$year);
  }

  static function addHours($timestamp,$hrs) {
    $hours = date('H',$timestamp);
    $minutes = date('i',$timestamp);
    $seconds = date('s',$timestamp);
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime($hours+$hrs,$minutes,$seconds,$month,$date,$year);
  }

  static function addDays($timestamp,$days) {
    $hours = date('H',$timestamp);
    $minutes = date('i',$timestamp);
    $seconds = date('s',$timestamp);
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime($hours,$minutes,$seconds,$month,$date+$days,$year);
  }

  static function addYears($timestamp,$years) {
    $hours = date('H',$timestamp);
    $minutes = date('i',$timestamp);
    $seconds = date('s',$timestamp);
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime($hours,$minutes,$seconds,$month,$date,$year+$years);
  }

  static function addMonths($timestamp,$months) {
    $hours = date('H',$timestamp);
    $minutes = date('i',$timestamp);
    $seconds = date('s',$timestamp);
    $year = date('Y',$timestamp);
    $month = date('n',$timestamp);
    $date = date('j',$timestamp);
    return mktime($hours,$minutes,$seconds,$month+$months,$date,$year);
  }

  static function getTimeZones() {
    return DateTimeZone::listIdentifiers();
  }
}