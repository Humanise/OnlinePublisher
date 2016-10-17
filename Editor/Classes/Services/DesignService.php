<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class DesignService {

  static $useYUI = false;

  /**
   * Finds all available designs
   * @return array An array of the unique names of all available designs
   * @static
   */
  static function getAvailableDesigns() {
    global $basePath;
    $names = FileSystemService::listDirs($basePath."style/");
    $out = array();
    foreach ($names as $name) {
      $out[$name] = DesignService::getInfo($name);
    }
    return $out;
  }

  static function getInfo($name) {
    global $basePath;
    $path = $basePath."style/".$name."/info/info.json";
    $info = JsonService::readFile($path);
    return $info;
  }

  static function _getPartStyleFiles() {
    global $basePath;
    $files = array();
    $names = FileSystemService::listFiles($basePath."style/basic/css/");
    foreach ($names as $name) {
      if (Strings::startsWith($name,'part_')) {
        $files[] = "style/basic/css/".$name;
      }
    }
    return $files;
  }

  static function _inlineJS() {
    $files = FileSystemService::find([
      'dir' => FileService::getPath('style'),
      'extension' => 'xsl'
    ]);
    foreach ($files as $xslFile) {
      $xsl = file_get_contents($xslFile);
      $callTemplate = Strings::extract($xsl,'<xsl:call-template name="util:script-inline">','</xsl:call-template>');
      foreach ($callTemplate as $template) {
        $minified = null;
        if (preg_match("/<xsl:with-param name=\"file\" select=\"'([^']+)'\"/", $template, $found)) {
          $jsFile = FileService::getPath($found[1]);
          $minified = DesignService::_compressToString($jsFile);
        } else {
          continue;
        }

        $compiledParam = Strings::extract($template,'<xsl:with-param name="compiled">','</xsl:with-param>');
        if (count($compiledParam)==1) {
          $compiledParam = $compiledParam[0];
          $new = '<xsl:with-param name="compiled"><![CDATA[' . $minified . ']]></xsl:with-param>';
          $replacement = str_replace($compiledParam,$new,$template);
          $xsl = str_replace($template,$replacement,$xsl);
        }
      }
      FileSystemService::writeStringToFile($xsl,$xslFile);
    }
  }

  static function _getFileParameter($xsl,$default='inline.css') {
    if (preg_match("/<xsl:with-param[\\W]+name=\"file\"[\\W]+select=\"'([^']+)'\"/uim", $xsl,$matches)) {
      return $matches[1];
    }
    return $default;
  }

  static function _embedInlineCSS($design) {
    global $basePath;

    Log::info('Embedding inline css for: ' . $design);
    $xslFile = $basePath.'style/' .$design . '/xslt/main.xsl';
    $xsl = file_get_contents($xslFile);
    $callTemplate = Strings::extract($xsl,'<xsl:call-template name="util:style-inline">','</xsl:call-template>');
    foreach ($callTemplate as $template) {
      $compiledParam = Strings::extract($template,'<xsl:with-param name="compiled">','</xsl:with-param>');
      if (count($compiledParam)==1) {
        $compiledParam = $compiledParam[0];

        $cssFileName = DesignService::_getFileParameter($template);
        $cssFile = $basePath."style/".$design."/css/".$cssFileName;
        if (file_exists($cssFile)) {
          $cssMin = DesignService::_compressToString($cssFile);
          $cssMin = DesignService::adjustURLs($cssMin,$design);
          $new = '<xsl:with-param name="compiled">' . $cssMin . '</xsl:with-param>';
          $replacement = str_replace($compiledParam,$new,$template);
          $xsl = str_replace($template,$replacement,$xsl);
        } else {
          Log::warn('Inline file not found: ' . $cssFile);
        }
      }
    }
    FileSystemService::writeStringToFile($xsl,$xslFile);
  }

  static function adjustURLs($css,$design) {
    return preg_replace("/url\((['\"]{0,1})..\//um", 'url($1<xsl:value-of select="\$path"/><xsl:value-of select="\$timestamp-url"/>style/'.$design.'/', $css);
  }

  static function writeJS($design) {
    $dev = Request::getBoolean('development');
    $preview = Request::getBoolean('preview');

    $files = DesignService::getJS($design, 'async');

    if ($preview) {
      $files[] = 'hui/bin/minimized.js';
      $files[] = 'hui/js/Editor.js';
      $files[] = 'hui/js/Pages.js';
    } else {
      // TODO: Use list of files
      $files[] = 'hui/bin/joined.site.js';
    }
    $files[] = 'style/basic/js/OnlinePublisher.js';

    $key = sha1(join($files,'|').'|'.ConfigurationService::getDeploymentTime());
    $cachedFile = FileSystemService::getFullPath('local/cache/temp/' . $key . '.js');
    header('Content-type: text/javascript');
    if (!$dev && file_exists($cachedFile)) {
      DesignService::sendFile($cachedFile);
      exit;
    }
    $out = '';
    foreach ($files as $file) {
      $path = FileSystemService::getFullPath($file);
      $out.= file_get_contents($path);
      $out.= "\n";
    }
    echo $out;
    if (!$dev) {
      $tempFile = $cachedFile.'.tmp.js';
      if (FileSystemService::writeStringToFile($out, $tempFile)) {
        DesignService::_compress($tempFile,$cachedFile);
        unlink($tempFile);
      }
    }
  }

  private static function getJS($design,$target='async') {
    $info = DesignService::getInfo($design);
    $files = [];
    if (isset($info) && isset($info->js)) {
      foreach ($info->js as $file) {
        $type = 'async';
        if (is_object($file)) {
          $type = isset($file->inline) && $file->inline === true ? 'inline' : 'async';
          $file = $file->file;
        }
        if ($type == $target) {
          $files[] = $file;
        }
      }
    }
    return $files;
  }

  private static function getCSS($design,$target='async') {
    $info = DesignService::getInfo($design);
    $files = [];
    if (isset($info) && isset($info->css)) {
      foreach ($info->css as $file) {
        $type = 'async';
        if (is_object($file)) {
          $type = isset($file->inline) && $file->inline === true ? 'inline' : 'async';
          $file = $file->file;
        }
        if ($type == $target) {
          if ($file == '@parts') {
            $files[] = 'style/basic/css/document.css';
            $files = array_merge($files, DesignService::_getPartStyleFiles());
          } else {
            $files[] = $file;
          }
        }
      }
    }
    return $files;
  }

  static function writeCSS($design) {
    $dev = Request::getBoolean('development');
    $preview = Request::getBoolean('preview');
    $files = DesignService::getCSSFiles($design, $preview);
    $key = sha1(join($files,'|').'|'.ConfigurationService::getDeploymentTime());
    $cachedFile = FileSystemService::getFullPath('local/cache/temp/' . $key . '.css');
    header('Content-type: text/css');
    if (file_exists($cachedFile) && !$dev) {
      DesignService::sendFile($cachedFile);
      exit;
    }
    $out = DesignService::compileCSSfiles($files, $dev);
    if ($dev) {
      echo $out;
    } else {
      $tempFile = $cachedFile.'.tmp.css';
      if (FileSystemService::writeStringToFile($out, $tempFile)) {
        DesignService::_compress($tempFile, $cachedFile);
        if (file_exists($cachedFile)) {
          DesignService::sendFile($cachedFile);
        } else {
          echo $out;
        }
        unlink($tempFile);
      } else {
        echo $out;
      }
    }
  }
  private static function sendFile($path) {
    if (ConfigurationService::isUrlRewrite()) {
      Response::setExpiresInDays(365);
    } else {
      Response::setExpiresInDays(7);
    }
    readfile($path);
  }

  private static function compileCSSfiles($files, $developmentMode) {
    $out = '';
    foreach ($files as $file) {
      if ($developmentMode) {
        $out .= '@import url(../../../' . $file . ');' . PHP_EOL;
      } else {
        $folder = FileSystemService::folderOfPath($file);
        $css = DesignService::_read($file);
        $out .= DesignService::fixUrls($css, $folder, '../../../');
      }
    }
    return $out;
  }

  private static function fixUrls($css, $context, $prefix) {
    return preg_replace_callback("/(url\\(['\"]?)([^\\)]+)/u", function($matches) use ($context, $prefix) {
      if (strpos($matches[2],'data:') === 0) {
        return $matches[0];
      }
      $local = rtrim($matches[2],"'\"");
      $joined = FileSystemService::join($context,$local);

      return 'url(\'' . $prefix . 'version' . ConfigurationService::getDeploymentTime() . '/' .  DesignService::normalizePath($joined) . '\'';
    }, $css);
  }

  private static function normalizePath($path) {
    $parts = array_filter(explode('/', $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
      if ('.' == $part) continue;
      if ('..' == $part) {
        array_pop($absolutes);
      } else {
        $absolutes[] = $part;
      }
    }
    return implode('/', $absolutes);
  }

  private static function loadInlineCSS($design) {
    $files = DesignService::getCSS($design, 'inline');
    $css = '';
    foreach ($files as $file) {
      $folder = FileSystemService::folderOfPath($file);
      $str = DesignService::_read($file);
      $css .= DesignService::fixUrls($str, $folder, '/');
    }
    return $css;
  }

  private static function loadInlineJS($design) {
    $files = ['style/basic/js/boot.js'];
    $files = array_merge($files, DesignService::getJS($design, 'inline'));
    $js = '';
    foreach ($files as $file) {
      $path = FileSystemService::getFullPath($file);
      $js .= file_get_contents($path) . PHP_EOL;
    }
    return $js;
  }

  public static function getCustomInlineCSS($design, $file, $development='false') {
    if ($development == 'true') {
      $folder = FileSystemService::folderOfPath($file);
      $str = DesignService::_read($file);
      return DesignService::fixUrls($str, $folder, '/');
    }
    $out = '';
    $key = sha1($file . '|' . ConfigurationService::getDeploymentTime());
    $cacheFile = FileSystemService::getFullPath('local/cache/temp/' . $key . '.css');
    if (!file_exists($cacheFile)) {
      $folder = FileSystemService::folderOfPath($file);
      $str = DesignService::_read($file);
      $css = DesignService::fixUrls($str, $folder, '/');
      FileSystemService::writeStringToFile($css,$cacheFile);
      DesignService::_compress($cacheFile,$cacheFile);
    }
    return file_get_contents($cacheFile);
  }

  public static function getInlineCSS($design,$development='false') {
    if ($development == 'true') {
      return DesignService::loadInlineCSS($design);
    }
    $out = '';
    $key = $design . '_inline_' . ConfigurationService::getDeploymentTime();
    $cacheFile = FileSystemService::getFullPath('local/cache/temp/' . $key . '.css');
    if (!file_exists($cacheFile)) {
      $css = DesignService::loadInlineCSS($design);
      FileSystemService::writeStringToFile($css,$cacheFile);
      DesignService::_compress($cacheFile,$cacheFile);
    }
    return file_get_contents($cacheFile);
  }

  public static function getInlineJS($design,$development='false') {
    if ($development == 'true') {
      return DesignService::loadInlineJS($design);
    }
    $out = '';
    $key = $design . '_inline_' . ConfigurationService::getDeploymentTime();
    $cacheFile = FileSystemService::getFullPath('local/cache/temp/' . $key . '.js');
    if (!file_exists($cacheFile)) {
      $css = DesignService::loadInlineJS($design);
      FileSystemService::writeStringToFile($css,$cacheFile);
      DesignService::_compress($cacheFile,$cacheFile);
    }
    return file_get_contents($cacheFile);
  }

  private static function getCSSFiles($design, $preview) {
    $files = [];
    if ($preview) {
      $files[] = 'hui/bin/minimized.css';
    } else {
      $components = ['icon', 'curtain', 'imageviewer', 'overlay', 'box', 'button', 'formula', 'message', 'textinput'];
      foreach ($components as $name) {
        $files[] = 'hui/css/' . $name . '.css';
      }
    }
    $files = array_merge($files, DesignService::getCSS($design, 'async'));
    return $files;
  }

  static function rebuild($design) {
    global $basePath;

    $designs = DesignService::getAvailableDesigns();
    foreach ($designs as $key => $info) {
      if ($design!==null && $design!==$key) {
          continue;
      }
      if (isset($info->build)) {
        if (isset($info->build->css)) {

          $imports = array();

          $data = '/* '.Strings::toJSON($info->build->css).' */';
          // TODO: Use getCSSFiles
          foreach ($info->build->css as $file) {
            if ($file[0]=='@') {
              if ($file=='@parts') {
                $data .= DesignService::_read('style/basic/css/document.css');
                $imports[] = 'style/basic/css/document.css';

                $partFiles = DesignService::_getPartStyleFiles();
                foreach ($partFiles as $partFile) {
                  $data .= DesignService::_read($partFile);
                  $imports[] = $partFile;
                }
              }
            } else {
              $data .= DesignService::_read($file);
              $imports[] = $file;
            }
          }
          {
            $cssFile = $basePath."style/".$key."/css/style.private.tmp.css";
            FileSystemService::writeStringToFile($data,$cssFile);
            DesignService::_compress($cssFile,$basePath."style/".$key."/css/style.private.css");
            unlink($cssFile);
          }
          {
            $huiCss = DesignService::_read('hui/bin/joined.site.css');
            $huiCss = preg_replace("/(url\\(['\"]?)(..\/gfx\/)([^\\)]+\\))/u", "$1../../../hui/gfx/$3", $huiCss);
            $data = $huiCss . $data;
            $cssFile = $basePath."style/".$key."/css/style.tmp.css";
            FileSystemService::writeStringToFile($data,$cssFile);
            DesignService::_compress($cssFile,$basePath."style/".$key."/css/style.css");
            unlink($cssFile);
          }
          {
            $data = '';
            foreach ($imports as $path) {
              $data .= '@import url(../../../' . $path . ');' . PHP_EOL;
            }
            $cssFile = $basePath."style/".$key."/css/style.dev.css";
            FileSystemService::writeStringToFile($data,$cssFile);
          }
        }
        if (isset($info->build->js)) {

          $imports = array();
          $data = '/* '.Strings::toJSON($info->build->js).' */' . PHP_EOL . PHP_EOL;

          $data .= DesignService::_read('style/basic/js/OnlinePublisher.js');

          foreach ($info->build->js as $file) {
            $data .= DesignService::_read($file);
            $imports[] = $file;
          }
          {
            $jsFile = $basePath."style/".$key."/js/script.private.tmp.js";
            FileSystemService::writeStringToFile($data,$jsFile);
            DesignService::_compress($jsFile,$basePath."style/".$key."/js/script.private.js");
          }
          {
            $jsFile = $basePath."style/".$key."/js/script.tmp.js";
            $data = DesignService::_read('hui/bin/joined.site.js') . $data;
            FileSystemService::writeStringToFile($data,$jsFile);
            DesignService::_compress($jsFile,$basePath."style/".$key."/js/script.js");
          }
          {
            $data = '';
            $jsFile = $basePath."style/".$key."/js/script.dev.js";
            foreach ($imports as $path) {
              $data.= 'document.write(\'<script type="text/javascript" src="\' + _editor.context + \'' . $path . '"></script>\');';
            }
            FileSystemService::writeStringToFile($data,$jsFile);
          }
        }
      }
      DesignService::_embedInlineCSS($key);
    }
    DesignService::_inlineJS();
  }

  static function _read($path) {
    $data = PHP_EOL . '/* '.$path.' */' . PHP_EOL;
    $data .= file_get_contents(FileSystemService::getFullPath($path));
    return $data;
  }

  static function _compress($in,$out) {
    global $basePath;
    if (DesignService::$useYUI) {
      $cmd = "java -jar ".$basePath."hui/tools/yuicompressor-2.4.8.jar ".$in." --charset UTF-8 -o ".$out;
    } else {
      $cmd = "minify --no-comments ".$in." -o ".$out;
    }
    ShellService::execute($cmd);
    if (!file_exists($out)) {
      Log::debug('Compression failed: ' . $cmd);
    }
  }

  static function _compressToString($in) {
    global $basePath;
    $out = $in . '.tmp';
    DesignService::_compress($in,$out);
    $str = file_get_contents($out);
    unlink($out);
    return $str;
  }

  static function loadParameters($id) {
    $out = array();
    $design = Design::load($id);
    $info = DesignService::getInfo($design->getUnique());
    if (isset($info->parameters)) {
      $sql = "select * from design_parameter where design_id=".Database::int($id);
      $rows = Database::selectAll($sql);
      foreach ($info->parameters as $parameter) {
        $arr = get_object_vars($parameter);
        foreach ($rows as $row) {
          if ($row['key']==$arr['key']) {
            $arr['value'] = $row['value'];
            break;
          }
        }
        $out[] = $arr;
      }
    }
    return $out;
  }

  static function _getType($key,$info) {
    if ($info->parameters) {
      foreach ($info->parameters as $parameter) {
        if ($parameter->key == $key) {
          return $parameter->type;
        }
      }
    }
    return null;
  }

  static function saveParameters($id,$parameters) {
    $design = Design::load($id);
    $info = DesignService::getInfo($design->getUnique());
    $sql = "delete from design_parameter where design_id=".Database::int($id);
    Database::delete($sql);
    $xml = '';
    foreach ($parameters as $key => $value) {
      $type = DesignService::_getType($key,$info);
      $sql = "insert into design_parameter (design_id,`key`,`value`) values (".Database::int($id).",".Database::text($key).",".Database::text($value).")";
      Database::insert($sql);
      if (Strings::isNotBlank($value)) {
        $xml.='<parameter key="'.$key.'">';
        if ($type=='image') {
          $image = Image::load($value);
          if ($image) {
            $xml.='<image id="'.$image->getId().'" width="'.$image->getWidth().'" height="'.$image->getHeight().'"/>';
          }
        } else {
          $xml.=Strings::escapeXML($value);
        }
        $xml.='</parameter>';
      }
    }

    $design->setParameters($xml);
    $design->save();
    $design->publish();
  }

  static function getFrameOptions() {
    return '
      <item title="{None; da:Ingen}" value=""/>
      <item title="{Light; da:Let}" value="light"/>
      <item title="Elegant" value="elegant"/>
      <item title="{Shaddow; da:Skygge}" value="shadow_slant"/>
      <item title="Polaroid" value="polaroid"/>';
  }

  static function validate($name) {
    global $basePath;
    $valid = true;
    $info = DesignService::getInfo($name);
    if ($info!==null) {
      $valid = $valid && Strings::isNotBlank($info->name);
      $valid = $valid && Strings::isNotBlank($info->description);
      $valid = $valid && Strings::isNotBlank($info->owner);
    } else {
      $valid = false;
    }
    $valid = $valid && !file_exists($basePath."style/".$name."/info/info.xml");
    $valid = $valid && file_exists($basePath."style/".$name."/info/Preview128.png");
    $valid = $valid && file_exists($basePath."style/".$name."/info/Preview64.png");
    $valid = $valid && file_exists($basePath."style/".$name."/xslt/main.xsl");
    if ($info!==null && !(isset($info->build) || isset($info->js) || isset($info->css))) {
      $valid = $valid && file_exists($basePath."style/".$name."/css/style.php");
    } else {
      $valid = $valid && !file_exists($basePath."style/".$name."/css/style.php");
    }
    // TODO (jm)
    $valid = $valid && file_exists($basePath."style/".$name."/css/editor.css");
    return $valid;
  }
}