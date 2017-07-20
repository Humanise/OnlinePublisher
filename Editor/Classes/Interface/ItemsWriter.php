<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Interface
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class ItemsWriter {
  function startItems() {
    header('Content-Type: text/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?><options>';
    return $this;
  }

  function startItem($options) {
    echo '<option';
    if (isset($options['value'])) {
      echo ' value="'.Strings::escapeEncodedXML($options['value']).'"';
    }
    if (isset($options['title'])) {
      echo ' title="'.Strings::escapeEncodedXML(GuiUtils::getTranslated($options['title'])).'"';
    }
    if (isset($options['text'])) {
      echo ' text="'.Strings::escapeEncodedXML(GuiUtils::getTranslated($options['text'])).'"';
    }
    if (isset($options['icon'])) {
      echo ' icon="'.$options['icon'].'"';
    }
    if (isset($options['kind'])) {
      echo ' kind="'.$options['kind'].'"';
    }
    if (isset($options['badge'])) {
      echo ' badge="'.$options['badge'].'"';
    }
    echo '>';
    return $this;
  }

  function item($options) {
    return $this->startItem($options)->endItem();
  }

  function endItem() {
    echo '</option>';
    return $this;
  }

  function endItems() {
    echo '</options>';
    return $this;
  }

  function title($title=null) {
    echo '<title title="'.Strings::escapeEncodedXML(GuiUtils::getTranslated($title)).'"/>';
    return $this;
  }

}
?>