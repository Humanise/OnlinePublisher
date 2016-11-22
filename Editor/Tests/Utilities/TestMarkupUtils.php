<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class TestMarkupUtils extends UnitTestCase {

	function testFindScriptSegments() {
		$html = '<html><head><script type="text/javascript"></script></head><body><h1>Test</h1><script>if (true) {alert(0)}</script></body></html>';
		$result = MarkupUtils::findScriptSegments($html);
		//Log::debug(Strings::toJSON($result));
		$this->assertEqual(2,count($result));

		$first = substr($html,$result[0]['from'],$result[0]['to']-$result[0]['from']);
		$this->assertEqual('<script type="text/javascript"></script>',$first);

		$second = substr($html,$result[1]['from'],$result[1]['to']-$result[1]['from']);
		$this->assertEqual('<script>if (true) {alert(0)}</script>',$second);
	}

	function testMoveScriptsToBottom() {
		$html = '<html><head><script type="text/javascript" src="path.js"/><script type="text/javascript"></script></head><body><h1>Test</h1><script>if (true) {alert(0)}</script></body></html>';
    $result = MarkupUtils::moveScriptsToBottom($html);
    $expected = '<html><head></head><body><h1>Test</h1><script type="text/javascript" src="path.js"/><script type="text/javascript"></script><script>if (true) {alert(0)}</script></body></html>';
    $this->assertEqual($expected,$result);
  }

	function testStyleToHead() {
		$html = '<html><head></head>' .
      '<body>' .
        '<h1>Test</h1>' .
        '<style>.myclass{color:red;}</style>' .
        '<!--[if lt IE 7]><style>.hello {color: blue;}</style><![endif]-->' .
        '<!-- simple comment -->' .
        '<!--[if lt IE 7]>I should not be moved<![endif]-->' .
      '</body></html>';

    $result = MarkupUtils::moveStyleToHead($html);

    $expected = '<html>' .
      '<head>' .
        '<style>.myclass{color:red;}</style>' .
        '<!--[if lt IE 7]><style>.hello {color: blue;}</style><![endif]-->' .
      '</head>' .
      '<body><h1>Test</h1>' .
      '<!-- simple comment -->' .
      '<!--[if lt IE 7]>I should not be moved<![endif]-->' .
      '</body></html>';

    $this->assertEqual($expected,$result);
  }

	function testConvertToXHTML() {
		$tests = [
			'<p><br></p>' => '<p><br/></p>',
			'<p>' => '<p/>',
			'<p>&nbsp;</p>' => '<p>&#xA0;</p>',
      '<p>Egestas Vehicula Lorem </p><hr> Fermentum Vehicula Consectetur' => '<p>Egestas Vehicula Lorem </p><hr/> Fermentum Vehicula Consectetur',
      '' => '',
      'x' => 'x'
		];
		foreach ($tests as $html => $xhtml) {
      $this->assertEqual($xhtml,MarkupUtils::htmlToXhtml($html));
		}
  }

	function testMoveScriptsToBottomIgnoreIE() {
		$html = '<html><head><!-- ignore me --><!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="./style/version1386716400/in2isoft/css/msie6.css"> </link><![endif]--><!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js" data-movable="false"></script><![endif]--><!--[if lt IE 8]><script src="js/script.js"></script><![endif]--><script type="text/javascript" src="path.js"/><script type="text/javascript"></script></head><body><h1>Test</h1><script>if (true) {alert(0)}</script></body></html>';

    $result = MarkupUtils::moveScriptsToBottom($html);

    // BEWARE: if an IE-script contains html5shim - IT IS NOT MOVED!!!

    $expected = '<html><head><!-- ignore me --><!--[if lt IE 7]><link rel="stylesheet" type="text/css" href="./style/version1386716400/in2isoft/css/msie6.css"> </link><![endif]--><!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js" data-movable="false"></script><![endif]--></head><body><h1>Test</h1><!--[if lt IE 8]><script src="js/script.js"></script><![endif]--><script type="text/javascript" src="path.js"/><script type="text/javascript"></script><script>if (true) {alert(0)}</script></body></html>';

    $this->assertEqual($expected,$result);
  }
}