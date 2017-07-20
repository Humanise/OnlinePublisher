<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($info = LinkService::getLinkInfo($id)) {
  Response::sendObject([
    'id' => $info->getId(),
    'text' => $info->getSourceText(),
    'type' => $info->getTargetType(),
    'targetId' => $info->getTargetId(),
    'targetValue' => $info->getTargetValue(),
    'scope' => $info->getPartId()>0 ? 'part' : 'page',
    'rendering' =>
      '<p><strong>' . Strings::escapeSimpleXML($info->getTargetTitle()) . '</strong></p>
      <p class="hui_rendering_dimmed">' . LinkService::translateLinkType($info->getTargetType()) . '</p>' .
      '<p style="margin-top: 5px;">' .
      ( $info->getPartId()>0 ?
          GuiUtils::getTranslated(['Only inserted in this section','da'=>'Kun indsat i dette afsnit']) :
          GuiUtils::getTranslated(['Inserted everywhere on page','da'=>'Indsat overalt på siden'])
      ) .
      '</p>'
  ]);
} else {
  Response::badRequest();
}
?>