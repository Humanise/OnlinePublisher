<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['MailinglistPart'] = [
  'table' => 'part_mailinglist',
  'identity' => 'part_id',
  'properties' => [
  ],
  'relations' => [
    'mailinglistIds' => [ 'table' => 'part_mailinglist_mailinglist', 'fromColumn' => 'part_id', 'toColumn' => 'mailinglist_id' ]
  ]
];

class MailinglistPart extends Part
{
  var $mailinglistIds;

  function __construct() {
    parent::__construct('mailinglist');
  }

  static function load($id) {
    return Part::get('mailinglist',$id);
  }

  function setMailinglistIds($mailinglistIds) {
    $this->mailinglistIds = $mailinglistIds;
  }

  function getMailinglistIds() {
    return $this->mailinglistIds;
  }

}
?>