<?
class Request {

	/**
	 * Gets a text string variable passed thru the get protocol
	 * @param string $key The name of the variable
	 * @return string The value of the variable, '' if variable not set
	 */
	function getString($key) {
		if (isset($_GET[$key])) {
			$output=$_GET[$key];
			$output=str_replace('\\"', '"', $output);
			$output=str_replace('\\\'', '\'', $output);
			$output=str_replace('\\\\', '\\', $output);
			return $output;
		}
		else {
			return '';
		}
	}

	/**
	 * Gets a integer variable passed thru the GET protocol
	 * @param string $key The name of the variable
	 * @param string $default Optional: The value to return if the variable is not set
	 * or not a number. Defaults to 0.
	 * @return int The value of the variable, $default if variable not set or not numeric
	 */
	function getInt($key,$default=0) {
		if (isset($_GET[$key]) && is_numeric($_GET[$key])) {
			return intval($_GET[$key]);
		}
		else {
			return $default;
		}
	}

	/**
	 * Gets the boolean value of a variable passed thru the get protocol
	 * @param string $key The name of the variable
	 * @return boolean True if the value equals "true", false otherwise
	 */
	function getBoolean($key) {
		$output=false;
		if (isset($_GET[$key])) {
			if ($_GET[$key]=='true') {
				$output=true;
			}
		}
		return $output;
	}

	function getPostDateInFormat($key,$format) {
		$value = Request::getPostString($key);
		$parsed = strptime($value, $format);
		$stamp = mktime ( $parsed['tm_hour'] , $parsed['tm_min'] , $parsed['tm_sec'], $parsed['tm_mon']+1, $parsed['tm_mday'], $parsed['tm_year'] );
		error_log($value);
		error_log(print_r($parsed,true));
		error_log(strftime($format, $stamp));
		return $stamp;
	}


	/**
	 * Gets a integer variable passed thru the POST protocol
	 * @param string $key The name of the variable
	 * @param string $default Optional: The value to return if the variable is not set
	 * or not a number. Defaults to 0.
	 * @return int The value of the variable, $default if variable not set or not numeric
	 */
	function getPostInt($key,$default=0) {
		if (isset($_POST[$key]) && is_numeric($_POST[$key])) {
			return intval($_POST[$key]);
		}
		else {
			return $default;
		}
	}
	
	function getStringUTF8($key) {
		$value = Request::getString($key);
		return mb_convert_encoding($value, "ISO-8859-1","UTF-8");
	}

	/**
	 * Gets a text string variable passed thru the get protocol
	 * @param string $key The name of the variable
	 * @return string The value of the variable, '' if variable not set
	 */
	function getPostString($key) {
		if (isset($_POST[$key])) {
			$output=$_POST[$key];
			$output=str_replace('\\"', '"', $output);
			$output=str_replace('\\\'', '\'', $output);
			$output=str_replace('\\\\', '\\', $output);
			return $output;
		}
		else {
			return '';
		}
	}
	
	function getPostStringUTF8($key) {
		$value = Request::getPostString($key);
		return mb_convert_encoding($value, "ISO-8859-1","UTF-8");
	}
	
	function isPost() {
		return $_SERVER['REQUEST_METHOD']=='POST';
	}

	/**
	 * Gets the array value of a variable passed thru the post protocol
	 * @param string $key The name of the variable
	 * @return array the array value of the variable,
	 *         an empty array if variable is not an array
	 */
	function getPostArray($key) {
		if (isset($_POST[$key]) && is_array($_POST[$key])) {
			return $_POST[$key];
		}
		else {
			return array();
		}
	}
}
?>