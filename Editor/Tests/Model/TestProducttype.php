<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class TestProducttype extends AbstractObjectTest {

  function __construct() {
    parent::__construct('producttype');
  }

}
?>