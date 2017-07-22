<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Network
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class WebRequest {

  private $url;
  private $parameters = [];
  private $headers = [];
  private $unicode;

  function WebRequest($url = null) {
    $this->url = $url;
  }

  function setUrl($url) {
    $this->url = $url;
  }

  function getUrl() {
    return $this->url;
  }

  function addParameter($name,$value) {
    $this->parameters[$name] = $value;
  }

  function addHeader($name,$value) {
    $this->headers[] = ['name' => $name, 'value' => $value];
  }

  function setParameters($parameters) {
    $this->parameters = $parameters;
  }

  function getParameters() {
    return $this->parameters;
  }

  function getHeaders() {
    return $this->headers;
  }

  function setUnicode($unicode) {
    $this->unicode = $unicode;
  }

  function getUnicode() {
      return $this->unicode;
  }

}