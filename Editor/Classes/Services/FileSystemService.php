<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Log.php');

class FileSystemService {
	
	/**
	 * Converts a filename into a safe filename, removing chars that could give problems
	 * TODO: Known not to work in extreme cases, use positive chars instead of negative!!
	 * @param string $str The filename to analyze
	 * @return string A safe filename
	 */
	function safeFilename($str){
		$str = str_replace("\xe6","ae",$str);
		$str = str_replace("\xf8","oe",$str);
		$str = str_replace("\xe5","aa",$str);
		$str = str_replace("%","x",$str);
		$str = str_replace('"','x',$str);
		$str = str_replace('#','x',$str);
		$str = str_replace('?','x',$str);
		return preg_replace('/[^!-%\x27-;?-~ ]/e', '"x"', $str);
	}
	
	/**
	 * Returns an array of directories inside a given directory
	 * @param string $dir Path of the dir to analyze
	 * @return array An array of the names of the directories inside the directory
	 */
	function listDirs($dir) {
		$out=array();
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if (is_dir($dir.$file) && !($file=='.' || $file=='..' || $file=='CVS') ) {
						array_push($out,$file);
					}
				}
				closedir($dh);
			}
		}
		return $out;
	}
	
	/**
	 * Finds an array of files inside a given directory
	 * @param string $dir The path to the directory
	 * @return array Array of files inside the dir
	 * @todo Filenames or paths?
	 */
	function listFiles($dir) {
		$out=array();
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if (is_file($dir.$file) && !($file=='.' || $file=='..') ) {
						array_push($out,$file);
					}
				}
				closedir($dh);
			}
		}
		return $out;
	}
	
	/**
	 * Wites a string to a file, overwriting any existing files
	 * @param string $string The string to write
	 * @param string $file The path of the file to write to
	 * @return boolean True on success, False otherwise
	 */
	function writeStringToFile($string,$file) {
		if (!$handle = fopen($file, 'w')) {
			return false;
		}
		if (fwrite($handle, $string) === FALSE) {
			return false;
		}
		fclose($handle);
		return true;
	}
	
	/**
	 * Finds the extension of a filename
	 * @param string $filename The filename to analyze
	 * @return string The extension of the filename
	 */
	function getFileExtension($filename) {
		$pos = strrpos($filename,'.');
		if ($pos === false) {
			return '';
		}
		else {
			return substr($filename,$pos+1,strlen($filename)-$pos);
		}
	}
	
	function getMaxUploadSize() {
	    $maxPost = FileSystemService::parseBytes(ini_get('post_max_size'));
	    $maxUpload = FileSystemService::parseBytes(ini_get('upload_max_filesize'));
	    if ($maxPost<$maxUpload) {
	        return $maxPost;
	    } else {
	        return $maxUpload;
	    }
	}
	
	/**
	 * Finds a free path for a file, using a provided file as input
	 * @param string $path The path to be used
	 * @return string A free path
	 */
	function findFreeFilePath($path) {
		$path_parts = pathinfo($path);
		$dir = $path_parts['dirname'];
		$file = $path_parts['basename'];
		if (array_key_exists('extension',$path_parts)) {
			$ext = $path_parts['extension'];
		} else {
			$ext = '';
		}
	
		$output = $path;
		$head = substr($file,0,strlen($file)-strlen($ext)-1);
		$count = 1;
		while (file_exists($path)) {
			$path = $dir.'/'.$head.$count.'.'.$ext;
			$count++;
		}
		return $path;
	}
	
	/**
	 * Finds a paths base filename
	 * @param string $path The path to analyze
	 * @return string The base filename
	 */
	function getFileBaseName($path) {
		$path_parts = pathinfo($path);
		return $path_parts['basename'];
	}
	
	/**
	 * Makes a filename into a nice looking title (stripping extensions etc.)
	 * @param string $filename The filename to convert
	 * @return string A nice looking title
	 */
	function filenameToTitle($filename) {
		$pos = strpos($filename,'.');
		if ($pos === false) {
			$title = $filename;
		}
		else {
			$title = substr($filename,0,$pos);
		}
		return ucfirst($title);
	}
	

	function parseBytes($val) {
	    $val = trim($val);
	    $last = strtolower($val{strlen($val)-1});
	    switch($last) {
	        // The 'G' modifier is available since PHP 5.1.0
	        case 'g':
	            $val *= 1024;
	        case 'm':
	            $val *= 1024;
	        case 'k':
	            $val *= 1024;
	    }

	    return $val;
	}

}