<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

DocumentTemplateEditor::updateRow(Request::getInt('id'), [
  'top' => Request::getString('top'),
  'bottom' => Request::getString('bottom'),
  'spacing' => Request::getString('spacing'),
  'class' => Request::getString('class')
]);
?>