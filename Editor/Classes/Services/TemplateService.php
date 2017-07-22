<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TemplateService {

  static function getTemplateByUnique($unique) {
    $sql = "select id,`unique` from `template` where `unique`=" . Database::text($unique);
    if ($row = Database::selectFirst($sql)) {
      $template = new Template();
      $template->setId(intval($row['id']));
      $template->setUnique($row['unique']);
      return $template;
    }
    return null;
  }

  static function getTemplateById($id) {
    $sql = "select id,`unique` from `template` where id=" . Database::int($id);
    if ($row = Database::selectFirst($sql)) {
      $template = new Template();
      $template->setId(intval($row['id']));
      $template->setUnique($row['unique']);
      return $template;
    }
    return null;
  }

  static function getController($type) {
    global $basePath;
    $class = ucfirst($type) . 'TemplateController';
    $path = $basePath . 'Editor/Classes/Templates/' . $class . '.php';
    if (!file_exists($path)) {
      return null;
    }
    require_once $path;
    return new $class;
  }

  static function getAvailableTemplates() {
    global $basePath;
    $arr = FileSystemService::listDirs($basePath . "Editor/Template/");
    for ($i = 0; $i < count($arr); $i++) {
      if (substr($arr[$i],0,3) == 'CVS') {
        unset($arr[$i]);
      }
    }
    return $arr;
  }

  static function install($key) {
    $sql = "select id from `template` where `unique`=" . Database::text($key);
    if (Database::isEmpty($sql)) {
      $sql = "insert into template (`unique`) values (" . Database::text($key) . ")";
      Database::insert($sql);
    } else {
      Log::debug('Unable to install template (' . $key . ') since it already exists');
    }
  }

  static function uninstall($key) {
    $sql = "select `template`.`id` from `template`,`page` where page.template_id=template.id and `template`.`unique`=" . Database::text($key);
    if (Database::isEmpty($sql)) {
      $sql = "delete from `template` where `unique`=" . Database::text($key);
      Database::delete($sql);
    } else {
      Log::debug('Unable to delete template (' . $key . ') since it is in use');
    }
  }

  static function getInstalledTemplates() {
    $arr = [];
    $sql = "select id,`unique` from `template`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $arr[] = ["id" => $row['id'], "unique" => $row['unique']];
    }
    Database::free($result);
    return $arr;
  }

  static function getInstalledTemplateKeys() {
    $arr = [];
    $sql = "select `unique` from `template`";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $arr[] = $row['unique'];
    }
    Database::free($result);
    return $arr;
  }

  static function getUsedTemplates() {
    $arr = [];
    $sql = "select distinct `template`.`unique` from `template`,`page` where page.template_id=template.id";
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      $arr[] = $row['unique'];
    }
    Database::free($result);
    return $arr;
  }

  /**
   * @static
   */
  static function getTemplatesKeyed() {
    $output = [];
    $templates = TemplateService::getInstalledTemplates();
    for ($i = 0; $i < count($templates); $i++) {
      $unique = $templates[$i]['unique'];
      $info = TemplateService::getTemplateInfo($unique);
      $info['id'] = $templates[$i]['id'];
      $output[$unique] = $info;
    }
    return $output;
  }

  // returns all installed templates sorted by name
  static function getTemplatesSorted() {
    $output = [];
    $templates = TemplateService::getInstalledTemplates();
    for ($i = 0; $i < count($templates); $i++) {
      $unique = $templates[$i]['unique'];
      $info = TemplateService::getTemplateInfo($unique);
      $info['id'] = $templates[$i]['id'];
      $output[] = $info;
    }
    usort($output,['TemplateService', 'compareTemplates']);
    return $output;
  }

  // Used to sort arrays of tools
  static function compareTemplates($templateA, $templateB) {
    $a = $templateA['name'];
    $b = $templateB['name'];
    if ($a == $b) {
      return 0;
    }
    return ($a < $b) ? -1 : 1;
  }

  static function getTemplateInfo($unique) {
    global $basePath;
    if ($out = InternalSession::getSessionCacheVar('template.info.' . $unique)) {
      return $out;
    }
    else {
      $out = ['unique' => $unique, 'icon' => null, 'name' => null, 'description' => null];
      $filename = $basePath . "Editor/Template/" . $unique . "/info.xml";

      $data = implode("", file($filename));

      $parser = xml_parser_create('UTF-8');
      xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
      xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
      xml_parse_into_struct($parser, $data, $values, $tags);
      xml_parser_free($parser);
      foreach ($values as $key) {
        switch($key['tag']) {
          case 'icon' : $out['icon'] = $key['value']; break;
          case 'name' : $out['name'] = $key['value']; break;
          case 'status' : $out['status'] = $key['value']; break;
          case 'description' : $out['description'] = $key['value']; break;
        }
      }
      InternalSession::setSessionCacheVar('template.info.' . $unique,$out);
      return $out;
    }
  }
}