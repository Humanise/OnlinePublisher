<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Newsgroup'] = [
    'table' => 'newsgroup',
    'properties' => []
];

class Newsgroup extends Object {

  function Newsgroup() {
    parent::Object('newsgroup');
  }

  static function load($id) {
    return Object::get($id,'newsgroup');
  }

  function removeMore() {
    $sql = "delete from newsgroup_news where newsgroup_id=" . Database::int($this->id);
    Database::delete($sql);
    $sql = "delete from part_news_newsgroup where newsgroup_id=" . Database::int($this->id);
    Database::delete($sql);
  }

  function canDelete() {
    $sql = "select id from frame_newsblock_newsgroup where newsgroup_id=" . Database::int($this->id);
    if (Database::selectFirst($sql)) {
      return FALSE;
    } else {
      return TRUE;
    }
  }

  function getIcon() {
        return "common/folder";
  }
}
?>