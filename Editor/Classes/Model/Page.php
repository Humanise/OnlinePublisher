<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

Entity::$schema['Page'] = [
  'table' => 'page',
  'properties' => [
    'title' => ['type'=>'string'],
    'description' => ['type'=>'string'],
    'keywords' => ['type'=>'string'],
    'templateId' => ['type'=>'int', 'column'=>'template_id', 'relation'=>['class'=>'Template', 'property'=>'id']],
//    'templateUnique' => array('type'=>'string'),
    'frameId' => ['type'=>'int', 'column'=>'frame_id', 'relation'=>['class'=>'Frame', 'property'=>'id']],
    'designId' => ['type'=>'int', 'column'=>'design_id', 'relation'=>['class'=>'Design', 'property'=>'id']],
    'language' => ['type'=>'string'],
    'searchable' => ['type'=>'boolean'],
    'disabled' => ['type'=>'boolean'],
    'changed' => ['type'=>'datetime'],
    'published' => ['type'=>'datetime']
  ]
];
class Page extends Entity {

  var $title;
  var $description;
  var $keywords;
  var $templateId;
  var $templateUnique;
  var $frameId;
  var $designId;
  var $language;
  var $searchable;
  var $disabled;
  var $changed;
  var $published;
  var $data;
  var $name;
  var $path;
  var $nextPage;
  var $previousPage;

  function Page() {
  }

  function setName($name) {
    $this->name = $name;
  }

  function getName() {
    return $this->name;
  }

  function setPath($path) {
    $this->path = $path;
  }

  function getPath() {
    return $this->path;
  }


  function setTitle($title) {
    $this->title = $title;
  }

  function getTitle() {
    return $this->title;
  }

  function setDescription($description) {
    $this->description = $description;
  }

  function getDescription() {
    return $this->description;
  }

  function setKeywords($keywords) {
    $this->keywords = $keywords;
  }

  function getKeywords() {
    return $this->keywords;
  }

  function setData($data) {
    $this->data = $data;
  }

  function getData() {
    return $this->data;
  }

  function setTemplateId($templateId) {
    $this->templateId = $templateId;
  }

  function getTemplateId() {
    return $this->templateId;
  }

  function setFrameId($frameId) {
    $this->frameId = $frameId;
  }

  function getFrameId() {
    return $this->frameId;
  }

  function setDesignId($designId) {
    $this->designId = $designId;
  }

  function getDesignId() {
    return $this->designId;
  }

  function setLanguage($language) {
    $this->language = $language;
  }

  function getLanguage() {
    return $this->language;
  }

  function setSearchable($searchable) {
    $this->searchable = $searchable;
  }

  function getSearchable() {
    return $this->searchable;
  }

  function setDisabled($disabled) {
    $this->disabled = $disabled;
  }

  function getDisabled() {
    return $this->disabled;
  }

  function getChanged() {
    return $this->changed;
  }

  function getPublished() {
    return $this->published;
  }

  function setNextPage($nextPage) {
    $this->nextPage = $nextPage;
  }

  function getNextPage() {
    return $this->nextPage;
  }

  function setPreviousPage($previousPage) {
    $this->previousPage = $previousPage;
  }

  function getPreviousPage() {
    return $this->previousPage;
  }


/////////////////////////// Special ///////////////////////////

  /**
   * WARNING: Only for persistent pages
   */
  function getTemplateUnique() {
    return $this->templateUnique;
  }

  function getIcon() {
    return 'common/page';
  }

///////////////////////// Persistence /////////////////////////

  static function load($id) {
    return PageService::load($id);
  }

  function create() {
    return PageService::create($this);
  }

  function save() {
    return PageService::save($this);
  }

  function publish() {
    PublishingService::publishPage($this->id);
  }

  function remove() {
    $this->delete();
  }

  function delete() {
    return PageService::delete($this);
  }
}
?>