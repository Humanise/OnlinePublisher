<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['imagegallery'] = array(
	'fields' => array(
		'variant'   	=> array('type'=>'text'),
		'height'		=> array('type'=>'int'),
		'imageGroupId'	=> array('type'=>'int','column'=>'imagegroup_id'),
		'framed'		=> array('type'=>'boolean'),
		'showTitle'		=> array('type'=>'boolean','column'=>'show_title')
	)
);
class Imagegallery extends Part
{
	var $variant;
	var $height;
	var $imageGroupId;
	var $framed;
	var $showTitle;
	
	function Imagegallery() {
		parent::Part('imagegallery');
	}
	
	function setVariant($variant) {
	    $this->variant = $variant;
	}

	function getVariant() {
	    return $this->variant;
	}
	
	function setHeight($height) {
	    $this->height = $height;
	}

	function getHeight() {
	    return $this->height;
	}
	
	function setImageGroupId($imageGroupId) {
	    $this->imageGroupId = $imageGroupId;
	}

	function getImageGroupId() {
	    return $this->imageGroupId;
	}
	
	function setFramed($framed) {
	    $this->framed = $framed;
	}
	
	function setShowTitle($showTitle) {
	    $this->showTitle = $showTitle;
	}

	function getShowTitle() {
	    return $this->showTitle;
	}

	function getFramed() {
	    return $this->framed;
	}
	
	function load($id) {
		return Part::load('imagegallery',$id);
	}
}
?>