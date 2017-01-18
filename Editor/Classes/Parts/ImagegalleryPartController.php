<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class ImagegalleryPartController extends PartController
{

  function ImagegalleryPartController() {
    parent::PartController('imagegallery');
  }

  function getFromRequest($id) {
    $part = ImagegalleryPart::load($id);
    $part->setHeight(Request::getInt('height',64));
    $part->setWidth(Request::getInt('imageWidth'));
    $part->setImageGroupId(Request::getInt('group'));
    $part->setFramed(Request::getBoolean('framed'));
    $part->setShowTitle(Request::getBoolean('showTitle'));
    $part->setVariant(Request::getString('variant'));
    $part->setFrame(Request::getString('frame'));
    return $part;
  }

  static function createPart() {
    $part = new ImagegalleryPart();
    $part->setHeight(100);
    $part->setWidth(100);
    $part->setVariant('floating');
    $part->save();
    return $part;
  }

  function display($part,$context) {
    return $this->render($part,$context);
  }

  function editor($part,$context) {
    return
      '<input type="hidden" name="group" value="'.$part->getImageGroupId().'"/>'.
      '<input type="hidden" name="height" value="'.$part->getHeight().'"/>'.
      '<input type="hidden" name="imageWidth" value="'.$part->getWidth().'"/>'.
      '<input type="hidden" name="framed" value="'.Strings::toBoolean($part->getFramed()).'"/>'.
      '<input type="hidden" name="showTitle" value="'.Strings::toBoolean($part->getShowTitle()).'"/>'.
      '<input type="hidden" name="variant" value="'.$part->getVariant().'"/>'.
      '<input type="hidden" name="frame" value="'.$part->getFrame().'"/>'.
      '<script src="'.ConfigurationService::getBaseUrl().'Editor/Parts/imagegallery/editor.js" type="text/javascript" charset="utf-8"></script>'.
      '<div id="part_imagegallery_container">'.$this->render($part,$context).'</div>';
  }

  function buildSub($part,$context) {
    $data = '<imagegallery xmlns="'.$this->getNamespace().'" image-group="'.$part->getImageGroupId().'">';
    $data.= '<display';
    $data.= ' height="'.$part->getHeight().'"';
    if ($part->getWidth()) {
      $data.= ' width="'.$part->getWidth().'"';
    }
    $data.= ' variant="'.Strings::escapeXML($part->getVariant()).'"';
    $data.= ' framed="'.Strings::toBoolean($part->getFramed()).'"';
    if ($part->getFrame()) {
      $data.= ' frame="'.Strings::escapeXML($part->getFrame()).'"';
    }
    $data.= ' show-title="'.Strings::toBoolean($part->getShowTitle()).'"/>';
    if ($part->getImageGroupId()) {
      $sql="SELECT object.data from object,imagegroup_image where imagegroup_image.image_id = object.id and imagegroup_image.imagegroup_id=@int(group) order by imagegroup_image.position, object.title";
      $result = Database::select($sql,['group'=>$part->getImageGroupId()]);
      while ($row = Database::next($result)) {
        $data.=$row['data'];
      }
      Database::free($result);
    }
    $data.='</imagegallery>';
    return $data;
  }

  function importSub($node,$part) {
    $imagegallery = DOMUtils::getFirstDescendant($node,'imagegallery');
    if ($imagegallery) {
      $part->setImageGroupId(intval($imagegallery->getAttribute('image-group')));
      $display = DOMUtils::getFirstDescendant($imagegallery,'display');
      if ($display) {
        $part->setHeight(intval($display->getAttribute('height')));
        $part->setWidth(intval($display->getAttribute('width')));
        $part->setVariant($display->getAttribute('variant'));
        $part->setFrame($display->getAttribute('frame'));
        $part->setFramed($display->getAttribute('framed')=='true' ? true : false);
      }
    }
  }

  function getToolbars() {
    return array(
      'Billedgalleri' => '
        <item label="{Image group; da:Billedgruppe}">
          <dropdown width="200" name="group">
          '.UI::buildOptions('imagegroup').'
          </dropdown>
        </item>
        <item label="{Height; da:Højde}">
          <number-input name="height" width="70"/>
        </item>
        <item label="{Width; da:Bredde}">
          <number-input name="width" width="70"/>
        </item>
        <divider/>
        <item label="Variant">
          <dropdown name="variant">
            <option value="floating" text="{Floating; da:Flydende}"/>
            <option value="changing" text="{Shifting; da:Skiftende}"/>
            <option value="masonry" text="{Masonry; da:Murværk}"/>
          </dropdown>
        </item>
        <item label="{Frame; da:Ramme}">
          <dropdown name="imageFrame">
            '.DesignService::getFrameOptions().'
          </dropdown>
        </item>
        <grid>
          <row>
            <cell right="5"><checkbox name="showTitle"/><label>{Show title; da:Vis titel}</label></cell>
          </row>
          <row>
            <cell right="5"><checkbox name="framed"/><label>{Framed; da:Indrammet}</label></cell>
          </row>
        </grid>'
    );
  }

}
?>