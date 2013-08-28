<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class In2iGui {

	static function render(&$gui) {
		global $basePath;
		$xhtml = strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')!==false;
		if (@$_GET['xhtml']=='false') {
			$xhtml = false;
		}
		$dev = @$_GET['dev']=='true' ? 'true' : 'false';
		$profile = @$_GET['profile']=='true' ? 'true' : 'false';
		$context = substr(ConfigurationService::getBaseUrl(),0,-1);
		$pathVersion = ConfigurationService::isUrlRewrite() ? 'version'.SystemInfo::getDate().'/' : '';

		$xmlData='<?xml version="1.0" encoding="UTF-8"?>'.In2iGui::localize($gui,InternalSession::getLanguage());

		$xslData='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="'.($xhtml ? 'xml' : 'html').'"/>'.

		'<xsl:variable name="dev">'.$dev.'</xsl:variable>'.
		'<xsl:variable name="profile">'.$profile.'</xsl:variable>'.
		'<xsl:variable name="version">'.SystemInfo::getDate().'</xsl:variable>'.
		'<xsl:variable name="pathVersion">'.$pathVersion.'</xsl:variable>'.
		'<xsl:variable name="context">'.$context.'</xsl:variable>'.
		'<xsl:variable name="language">'.InternalSession::getLanguage().'</xsl:variable>'.

		'<xsl:include href="'.$basePath.'hui/xslt/gui.xsl"/>'.

		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.

		'</xsl:stylesheet>';
	
		if (function_exists('xslt_create')) {
			$arguments = array('/_xml' => &$xmlData,'/_xsl' => &$xslData);
			$xp = xslt_create();
			header('Content-Type: '.($xhtml ? 'application/xhtml+xml' : 'text/html'));
			echo xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
	    	xslt_free($xp);
		}
		else {
			function xslErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
				header('Content-Type: text/xml');
				echo $vars['gui'];
				exit;
			}
			header('Content-Type: '.($xhtml ? 'application/xhtml+xml' : 'text/html'));
			$xslt = new xsltProcessor;
			$xslt->importStyleSheet(DomDocument::loadXML($xslData));
			echo $xslt->transformToXML(DomDocument::loadXML($xmlData));
		}
	}
	
	static function localize($xml,$language='en') {
		
		$pattern = "/({[^}]+})/mi";
		preg_match_all($pattern, $xml, $matches,PREG_OFFSET_CAPTURE);
		$diff = 0;
		for ($i=0;$i<count($matches[0]);$i++) {
			$pos = $matches[0][$i][1];
			if ($xml[$pos+$diff-1]!='"' && $xml[$pos+$diff-1]!='>') {
				continue;
			}
			$old = $matches[0][$i][0];
			$parts = In2iGui::extract($old);
			$new = array_key_exists($language,$parts) ? $parts[$language] : @$parts['any'];
			$xml = substr_replace ( $xml , $new , $pos+$diff ,strlen($old));
			
			$diff = $diff + strlen($new)-strlen($old);
		}
		return $xml;
	}
	
	static function extract($str) {
		$parsed = array();
		$str = substr($str,1,-1);
		$parts = explode(';',$str);
		foreach ($parts as $part) {
			$pos = strpos($part,':');
			if ($pos===false) {
				$parsed['any'] = trim($part);
			} else {
				$lang = trim(substr($part,0,$pos));
				$text = substr($part,$pos+1);
				$parsed[$lang] = trim($text);
			}
		}
		return $parsed;
	}
	
	static function renderFragment($gui) {
		global $basePath;
		$gui='<?xml version="1.0" encoding="UTF-8"?><subgui xmlns="uri:hui">'.In2iGui::localize($gui,InternalSession::getLanguage()).'</subgui>';
		$xsl='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="xml"/>'.
		'<xsl:variable name="dev">false</xsl:variable>'.
		'<xsl:variable name="version">'.SystemInfo::getDate().'</xsl:variable>'.
		'<xsl:variable name="context">'.substr(ConfigurationService::getBaseUrl(),0,-1).'</xsl:variable>'.
		'<xsl:variable name="language">'.InternalSession::getLanguage().'</xsl:variable>'.
		'<xsl:include href="'.$basePath.'hui/xslt/gui.xsl"/>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
		$result = XslService::transform($gui,$xsl);
		$result = preg_replace("/<!DOCTYPE[^>]+>/u", "", $result);
		$result = str_replace(
			array(' xmlns="http://www.w3.org/1999/xhtml"',' xmlns:html="http://www.w3.org/1999/xhtml"')
			,'',$result);
		return $result;
	}
	
	static function respondUploadSuccess() {
		header('Content-Type: text/plain');
		echo 'SUCCESS';
	}

	static function respondUploadFailure() {
		Response::badRequest();
		header('Content-Type: text/plain');
		echo 'FAILURE';
	}
	
	static function toLinks($links) {
		$out = array();
		foreach ($links as $link) {
			$out[] = array(
				'id' => $link->getId(), 
				'text' => $link->getText(), 
				'kind' => $link->getType(), 
				'value' => $link->getValue(), 
				'info' => $link->getInfo(), 
				'icon' => $link->getIcon()
			);
		}
		return $out;
	}
	
	static function fromLinks($links) {
		if (!is_array($links)) return;
		$out = array();
		foreach ($links as $link) {
			$objectLink = new ObjectLink();
			$objectLink->setText($link->text);
			$objectLink->setType($link->kind);
			$objectLink->setValue($link->value);
			$out[] = $objectLink;
		}
		return $out;
	}
}
?>