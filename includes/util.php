<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.3.0
 *	 LISENSE: http://www.esyndicat.com/license.html
 *	 http://www.esyndicat.com/
 *
 *	 This program is a commercial software and any kind of using it must agree 
 *	 to eSyndiCat Directory Software license.
 *
 *	 Link to eSyndiCat.com may not be removed from the software pages without
 *	 permission of eSyndiCat respective owners. This copyright notice may not
 *	 be removed from source code in any case.
 *
 *	 Copyright 2007-2013 Intelliants LLC
 *	 http://www.intelliants.com/
 *
 ******************************************************************************/

class esynValidator
{
	/**
	 * Validates URL (simple yet)
	 *
	 * @param string $aUrl Url
	 *
	 * @return bool
	 */
	public static function isUrl($aUrl)
	{
		$pattern = '/^(https?):\/\/'.                              // protocol
			'(([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+'.         // username
			'(:([a-z0-9$_\.\+!\*\'\(\),;\?&=-]|%[0-9a-f]{2})+)?'.      // password
			'@)?(?#'.                                                  // auth requires @
			')((([a-z0-9][a-z0-9-]*[a-z0-9]\.)*'.                      // domain segments AND
			'[a-z][a-z0-9-]*[a-z0-9]'.                                 // top level domain  OR
			'|((\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])\.){3}'.
			'(\d|[1-9]\d|1\d{2}|2[0-4][0-9]|25[0-5])'.                 // IP address
			')(:\d+)?'.                                                // port
			')(((\/+([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)*'. // path
			'(\?([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)'.      // query string
			'?)?)?'.                                                   // path and query string optional
			'(#([a-z0-9$_\.\+!\*\'\(\),;:@&=-]|%[0-9a-f]{2})*)?'.      // fragment
			'$/i';

		return (bool)preg_match($pattern, $aUrl);
	}

	/**
	* Validates email
	*
	* @param string $email Email
	*
	* @return bool
	*/
	public static function isEmail($email)
	{
		return (bool)preg_match('/^(?:(?:\"[^\"\f\n\r\t\v\b]+\")|(?:[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(?:\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@(?:(?:\[(?:(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9]))\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9]))\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9]))\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9])))\])|(?:(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9]))\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9]))\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9]))\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:[0-1]?[0-9]?[0-9])))|(?:(?:(?:[A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/', $email);
	}

	/**
	* Checks if reciprocal link exists
	*
	* @param string $text (text where to search or URL where to get the text)
	* @param string $href
	*
	* @return int
	*/
	public static function hasUrl($text, $href='')
	{
		$href_check = preg_quote($href, "#");
		$href_check = str_replace('/', '\/', $href_check);
		$reciprocal = "#<a[^>]+href\s*=\s*(?:[\'\"]{0,1})(?:\s*)".$href_check."?(?:\s*)(?:[\'\"]{0,1})(?:[^>]*)>(?:.*)<\/a>#is";

		$res = 0;

		$content = esynValidator::isUrl($text) ? esynUtil::getPageContent($text) : $text;
		if ($content)
		{
			$res = preg_match($reciprocal, $content);
		}

		// if there were no WWW. part in the URL then check again with
		if (!$res && esynValidator::isUrl($text) && false===strpos($text, "://www."))
		{

			$href_check = preg_quote($href, "#");
			$href_check = str_replace('/', '\/', $href_check);
			$href_check = str_replace(":\/\/", ":\/\/www.", $href_check);
			$reciprocal = "#<a[^>]+href\s*=\s*(?:[\'\"]{0,1})(?:\s*)".$href_check."(?:\s*)(?:[\'\"]{0,1})(?:[^>]*)>(?:.*)<\/a>#is";
			$res = preg_match($reciprocal, $content);

		}

		return $res;
	}
}

class esynSanitize
{

	public static function paranoid($string)
	{
		return preg_replace( "/[^a-z_0-9-]/i", "", $string );
	}

	public static function sql($string, $level = 0)
	{
		// (this function requires database connection)
		// don't worry about slashes, script disables magic_quotes_runtime
		// and appends code to clear GPC from slashes in system.php file
		if (is_array($string) && $string)
		{
			foreach ($string as $k => $v)
			{
				$string[$k] = self::sql($v, $level + 1);
			}
		}
		else
		{
			if (defined('IA_IN_ADMIN'))
			{
				global $esynAdmin;
				$string = $esynAdmin->escape_sql($string);
			}
			else
			{
				global $eSyndiCat;
				$string = $eSyndiCat->escape_sql($string);
			}
		}

		return $string;
	}

	public static function html($string, $mode = ENT_QUOTES)
	{
		return htmlspecialchars(trim($string), $mode);
	}

	/**
	* This short function used to convert special characters to their appropriate code - this function used in input fields as values
	*
	* @param string $str any string
	*
	* @return str
	*/
	public static function quote($str)
	{
		return str_replace(array(">","<", "'", "\""), array("&gt;","&lt;", "&#039;", "&quot;"), $str);
	}

	public static function notags($string)
	{
		return str_replace(array(">","<"), array("&gt;", "&lt;"), $string);
	}

	public static function striptags($string)
	{
		return strip_tags($string);
	}

	/**
	* Converts string to url valid string
	*
	* @param arr $params params (passed by Smarty)
	*
	* @return str
	*/
	public static function urlAcceptable($params)
	{
		// array passed from the Smarty
		if (is_array($params))
		{
			$str = $params['string'];
		}
		else
		{
			$str = $params;
		}

		$str = preg_replace('/[^a-z0-9]+/i', '-', $str);
		$str = preg_replace('/\-+/', '-', $str);
		$str = trim($str, '-');

		return $str;
	}

	// Filter against email header injection
	public static function headerInjectionFilter($name)
	{
		return preg_replace("/(?:%0A|%0D|\n+|\r+)(?:content-type:|to:|cc:|bcc:)/i", "", $name);
	}

	/**
	 * applyFn
	 *
	 * Apply any function of esynSanitize class to multi-dimension array
	 * The array should be multi-dimension.
	 * The fn should be valid name of function or will be returned false otherwise.
	 *
	 * @param array $array multi-dimension array
	 * @param string $fn
	 * @access public
	 * @return void
	 */
	public static function applyFn($array, $fn, $keys = array())
	{
		$validFn = array('paranoid', 'sql', 'html', 'notags', 'striptags');

		if (!is_array($array))
		{
			return false;
		}

		if (empty($array))
		{
			return false;
		}

		if (!in_array($fn, $validFn))
		{
			return false;
		}

		foreach($array as $key => $value)
		{
			if (empty($keys))
			{
				$array[$key] = array_map(array("esynSanitize", $fn), $array[$key]);
			}
			else
			{
				foreach($keys as $k)
				{
					$array[$key][$k] = esynSanitize::$fn($array[$key][$k]);
				}
			}
		}

		return $array;
	}
}

class esynUtil
{
	const ERROR_UNAUTHORIZED = 401;
	const ERROR_FORBIDDEN = 403;
	const ERROR_NOT_FOUND = 404;
	const ERROR_INTERNAL = 500;

	static protected $_breadcrumb = array();

	public static function jsonEncode($data)
	{
		if (function_exists('json_encode'))
		{
			return json_encode($data);
		}
		else
		{
			require_once IA_CLASSES . 'esynJSON.php';
			$jsonServices = new Services_JSON();

			return $jsonServices->encode($data);
		}
	}

	public static function jsonDecode($data)
	{
		if (function_exists('json_decode'))
		{
			return json_decode($data, true);
		}
		else
		{
			require_once IA_CLASSES . 'esynJSON.php';
			$jsonServices = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

			return $jsonServices->decode($data);
		}
	}

	public static function getIpAddress($long = false)
	{
		// test if it is a shared client
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		// is it a proxy address
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $long ? ip2long($ip) : $ip;
	}

	// Generates title alias for categories.
	public static function getAlias($string, $separator = '-')
	{
		global $esynConfig;

		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'bad', 'validation', 'utf8_to_ascii');

		$string = html_entity_decode($string);
		$string = str_replace(array('&', ' '), array('and', '-'), $string);

		$urlEncoded = false;

		if (utf8_to_ascii($string))
		{
			if (true == $esynConfig->getConfig('alias_urlencode'))
			{
				//$string = utf8_bad_replace($string);
				$string = preg_replace('/[^0-9\\p{L}]+/ui', $separator, $string);
				$urlEncoded = true;
			}
			else
			{
				$string = utf8_to_ascii($string);
			}
		}

		$string = $urlEncoded ? $string : preg_replace('/[^a-z0-9_]+/i', $separator, $string);
		$string = trim($string, $separator);

		return $string;
	}

	// Generates hidden form element
	public static function preventCsrf()
	{
		$token = esynUtil::getToken();

		return '<input type="hidden" name="prevent_csrf" value="' . $token . '" />';
	}

	public static function getToken()
	{
		// support several post forms in the page
		static $calledTimes = 0;

		$_SESSION['prevent_csrf'][] = $token = esynUtil::getNewToken();
		$calledTimes++;

		return $token;
	}

	// Invoked when csrf attttt detected
	public static function csrfAttack()
	{
		esynUtil::logout();
		die("Suspicous referrer. Possible CSRF attack prevented.
			Wikipedia contains nice article:
				<a href=\"http://en.wikipedia.org/wiki/Cross-site_request_forgery\">Learn about CSRF attack</a>");
	}

	public static function logout()
	{
		unset($_SESSION['admin_name'], $_SESSION['admin_pwd']);
	}

	public static function accessDenied($admin = false)
	{
		if ($admin)
		{
			header("HTTP/1.1 403 Forbidden");
			die($GLOBALS['esynI18N']['access_denied']);
		}
		else
		{
			global $esynI18N;

			$content = esyn_display_error("Forbidden | 1 | {$esynI18N['access_denied']}");
			$content = preg_replace('/<p class="solution">.*<\/p>/i', ' ', $content);

			echo $content;

			exit;
		}
	}

	/**
	* Checks link and returns its header
	*
	* @param string $url page url
	*
	* @return int
	*/
	public static function getListingHeader($url)
	{
		if (empty($url) || 'http://' == $url)
		{
			return 0;
		}

		if (!esynValidator::isUrl($url))
		{
			return 0;
		}

		$redirect = false;

		do
		{
			$listing_header = 1;

			$headers = esynUtil::getPageHeaders($url);

			if (!empty($headers))
			{
				$listing_header = (int)$headers['Status'];
			}

			$redirect = in_array((int)$listing_header, array(301, 302), true);

			if ($redirect)
			{
				$location = isset($headers['Location']) ? $headers['Location'] : $headers['location'];
				if (substr($location, 0, 4) != 'http')
				{
					$parsed_url = parse_url($url);
					$url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $location;
				}
				else
				{
					$url = $location;
				}
			}
		}
		while ($redirect);

		return $listing_header;
	}

	/**
	* Returns array of page headers
	*
	* @param string $aUrl page url
	*
	* @return mixed array on success, null on failure
	*/
	public static function getPageHeaders($aUrl)
	{
		$user_agent = 'eSyndiCat Bot';

		if (empty($aUrl))
		{
			return null;
		}

		$content = '';

		// Connect to the remote web server
		// and get headers content

		if (extension_loaded('curl'))
		{
			// set time limit
			set_time_limit(60);

			// Get content via cURL module
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $aUrl);
			curl_setopt($ch, CURLOPT_HEADER, true);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);

			$content = @curl_exec($ch);

			/*if (!$content)
			{
				echo '<b>CURL error&nbsp;:&nbsp;</b>';
				echo curl_error($ch);
				echo '<br />';
			}*/
			curl_close($ch);
		}
		elseif (ini_get('allow_url_fopen'))
		{
			// Get content via fsockopen
			$parsed_url = parse_url($aUrl);

			// Get host to connect to
			$host = $parsed_url['host'];

			$port = 80;

			if ('https' == $parsed_url['scheme'])
			{
				$port = 443;
				$host = 'ssl://' . $host;
			}

			// Get path to insert into HTTP HEAD request
			$path = empty($parsed_url['path']) ? '/' : $parsed_url['path'];
			$path .= empty($parsed_url['query']) ? '' : '?'.$parsed_url['query'];
			$path .= empty($parsed_url['fragment']) ? '' : '#'.$parsed_url['fragment'];

			if (function_exists("fsockopen"))
			{
				// Build request
				$request = 'GET '.$path.' HTTP/1.0'."\r\n";
				$request .= 'Host: '.$host."\r\n";
				$request .= 'User-Agent: '.$user_agent."\r\n";
				$request .= 'Connection: Close'."\r\n\r\n";

				// Get headers via system calls
				$f = @fsockopen($host, $port, $errno, $errstr, 5);
				if ($f)
				{
					$retval = array ();

					// Send request
					fwrite($f, $request);
					// Read response
					while (!feof($f))
					{
						$content .= fgets($f, 2048);
					}
					fclose($f);
				}
			}
			else
			{
				$stream = @fopen($host, "r");
				$data = stream_get_meta_data($stream);
				$content = $data['wrapper_data'];
				unset($data);
				fclose($stream);
			}
		}

		// Parse content into headers and return
		if (!empty($content))
		{
			$retval = array();
			// stream_get_meta_data returns array
			if (is_string($content))
			{
				$content = str_replace("\r\n", "\n", $content);
			}

			$temp = explode("\n", $content);
			foreach ($temp as $entry)
			{
				if (preg_match('/^HTTP\/[\d\.]+\s(\d+).*$/i', $entry, $matches))
				{
					$retval['Status'] = $matches[1];
				}
				else if (preg_match('/^(.*):\s(.*)$/i', $entry, $matches))
				{
					$retval[$matches[1]] = $matches[2];
				}
			}
			return $retval;
		}
		else
		{
			return null;
		}
	}

	public static function getRemoteZipContent ($url, $path)
	{
		if (extension_loaded('curl'))
		{
			$fp = fopen($path, 'w');

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_FILE, $fp);

			$data = curl_exec($ch);

			curl_close($ch);
			fclose($fp);
		}

		return true;
	}

	/**
	* Returns page web content
	*
	* @param string $url url of the page
	*
	*
	* @return mixed string on success, false on failure
	*/
	public static function getPageContent($url)
	{
		global $esynConfig;

		$retval = null;
		$user_agent = 'eSyndiCat Bot';

		if (!esynValidator::isUrl($url))
		{
			return false;
		}

		do
		{
			$listing_header = 1;
			$headers = esynUtil::getPageHeaders($url);

			if (!empty($headers))
			{
				$listing_header = (int)$headers['Status'];
			}

			$redirect = in_array((int)$listing_header, array(301, 302), true);

			if ($redirect)
			{
				$location = isset($headers['Location']) ? $headers['Location'] : $headers['location'];

				if (substr($location, 0, 4) != 'http')
				{
					$parsed_url = parse_url($url);
					$url = $parsed_url['scheme'].'://'.$parsed_url['host'].$location;
				}
				else
				{
					$url = $location;
				}
			}
		}
		while ($redirect);

		$headers = $esynConfig->gC('http_headers');

		$correct_headers = explode(',', $headers);

		if (in_array($listing_header, $correct_headers))
		{
			if (extension_loaded('curl'))
			{
				// Get page contents via cURL module
				set_time_limit(60);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
				$retval = curl_exec($ch);
				curl_close($ch);
			}
			elseif (ini_get('allow_url_fopen'))
			{
				ini_set('user_agent', $user_agent);

				// Context support was added with PHP 5.0.0.
				//$ctx = stream_context_create(array('http' => array('timeout' => 3)));

				$retval = file_get_contents($url, false/*, $ctx*/);

				ini_restore('user_agent');
			}
			else
			{
				return false;
			}

			return $retval;
		}

		return false;
	}

	public static function dateFormat($date)
	{
		global $esynConfig;

		if (trim($date) == '' || substr($date,0,10) == '0000-00-00')
		{
			return '';
		}

		$ts = strtotime($date);

		if ($ts === false)
		{
			return '';
		}

		return strftime($esynConfig->getConfig('date_format'), $ts);
	}

	/**
	* Uploads file to server
	*
	* @param string $aName index into $_FILES array
	* @param string $aDest destination file name
	*
	* @return bool true if file uploaded, false otherwise
	*/
	public static function upload($aName, $aDest)
	{
		$ret = false;
		// Check upload error
		if (0 == $_FILES[$aName]['error'] && is_uploaded_file($_FILES[$aName]['tmp_name']))
		{
			if (move_uploaded_file($_FILES[$aName]['tmp_name'], $aDest))
			{
				$ret = true;
			}
		}

		return $ret;
	}

	/**
	 * Returns unique alphanum string
	 *
	 * @param int $size string length
	 *
	 * @return string
	 */
	public static function getNewToken($size = 10)
	{
		$ret = md5(uniqid(rand(), true));
		$ret = substr($ret, 0, $size);

		return $ret;
	}

	public static function errorPage($errorCode, $message = null)
	{
		if (!in_array($errorCode, array(self::ERROR_UNAUTHORIZED, self::ERROR_FORBIDDEN, self::ERROR_NOT_FOUND, self::ERROR_INTERNAL)) && is_null($message))
		{
			$message = $errorCode;
			$errorCode = self::ERROR_FORBIDDEN;
		}
		elseif (is_null($message))
		{
			$message = _t((string)$errorCode, $errorCode);
		}
		$msg = $message ? $message : '';

		header('HTTP/1.0 ' . $errorCode);

		$_GET['error'] = $errorCode;
		include IA_HOME . 'error.php';
	}

	/**
	 * Redirects to a given URL
	 *
	 * @param string $url URL
	 * @param bool $permanent true - sets permanent redirect
	 */
	public static function go2($url, $permanent = false)
	{
		if (empty($url))
		{
			die("Fatal error: empty url param for function 'goto'");
		}

		if (!headers_sent($file, $line))
		{
			$permanent ? header("Location: " . $url, true, 301) : header("Location: " . $url);
			exit;
		}
		else
		{
			die(basename($file) . ":" . $line);
		}
	}

	// reload current page
	public static function reload($params=false)
	{
		$url = IA_BASE_URL . ltrim($_SERVER['SCRIPT_NAME'], "/");

		if (is_array($params))
		{
			foreach ($params as $k => $v)
			{
				// remove key
				if ($v === null)
				{
					unset($params[$k]);
					if (array_key_exists($k, $_GET))
					{
						unset($_GET[$k]);
					}
				}
				elseif (array_key_exists($k, $_GET)) // set new value
				{
					$_GET[$k] = $v;
					unset($params[$k]);
				}
			}
		}
		if (!empty($_GET) || !empty($params))
		{
			$url .= "?";
		}
		foreach($_GET as $k=>$v)
		{
			// Unfort. At this time we delete an individual items using GET requests instead of POST
			// so when reloading we should skip delete action
			if ($k == 'action' && $v == 'delete')
			{
				continue;
			}
			$url .= $k."=".urlencode($v)."&";
		}
		if ($params)
		{
			if (is_array($params))
			{
				foreach($params as $k=>$v)
				{
					$url .= $k."=".urlencode($v)."&";
				}
			}
			else
			{
				$url .= $params;
			}
		}
		$url = rtrim($url, "&");
		esynUtil::go2($url);
	}

	/**
	 * Returns domain name by a given URL
	 *
	 * @param string $aUrl url
	 *
	 * @return str
	 */
	public static function getDomain($aUrl = '')
	{
		if (preg_match('/^(?:http|https|ftp):\/\/((?:[A-Z0-9][A-Z0-9_-]*)(?:\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?/i', $aUrl))
		{
			$domain = parse_url($aUrl);

			return $domain['host'];
		}
	}

	/**
	 * @param $url
	 * @return bool|string
	 */
	public static function getDomain2($url)
	{
		// a list of decimal-separated TLDs
		$doubleTlds = array(
			'co.uk', 'me.uk', 'net.uk', 'org.uk', 'sch.uk',
			'ac.uk', 'gov.uk', 'nhs.uk', 'police.uk', 'mod.uk',
			'asn.au', 'com.au', 'net.au', 'id.au', 'org.au',
			'edu.au', 'gov.au', 'csiro.au', 'br.com', 'com.cn',
			'com.tw', 'cn.com', 'de.com', 'eu.com', 'hu.com',
			'idv.tw', 'net.cn', 'no.com', 'org.cn', 'org.tw',
			'qc.com', 'ru.com', 'sa.com', 'se.com', 'se.net',
			'uk.com', 'uk.net', 'us.com', 'uy.com', 'za.com',
			'com.br',
		);

		// sanitize the URL
		$url = trim($url);

		// if no hostname, use the current by default
		if (empty( $url ) || '/' == $url[0])
		{
			$url = $_SERVER['HTTP_HOST'] . $url;
		}

		// if no scheme, use `http://` by default
		if (false === strpos($url, '://' ))
		{
			$url = 'http://' . $url;
		}

		// can we successfully parse the URL?
		if ($host = parse_url($url, PHP_URL_HOST))
		{
			// is this an IP?
			if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $host))
			{
				return $host;
			}

			// sanitize the hostname
			$host = strtolower($host);

			// explode on the decimals
			$parts = explode('.', $host);

			// is there just one part? (`localhost`, etc)
			if (!isset($parts[1]))
			{
				return $parts[0];
			}

			// grab the TLD
			$tld = array_pop($parts);

			// grab the hostname
			$host = array_pop($parts) . '.' . $tld;

			// have we collected a double TLD?
			if (!empty($parts) && in_array($host, $doubleTlds))
			{
				$host = array_pop($parts) . '.' . $host;
			}

			return $host;
		}

		return false;
	}

	public static function geCategoriesChain($aCategory, $ext = '')
	{
		global $eSyndiCat;

		static $times = 0;

		// set correct extension
		if (empty($ext))
		{
			$ext = $eSyndiCat->mConfig['use_html_path'] ? '.html' : IA_URL_DELIMITER;
		}

		if ($times > 10)
		{
			trigger_error("Recursion in esynUtil::getBreadcrumb() in file: ".__FILE__." on line: ".__LINE__, E_USER_ERROR);
		}

		$eSyndiCat->setTable("categories");
		$category = $eSyndiCat->row("`parent_id`,`title`,`page_title`,`path`, `no_follow`", "`id` = '{$aCategory}'");

		if ('-1' != $category['parent_id'])
		{
			self::$_breadcrumb[] = array ('id' => $aCategory, 'title' => $category['title'], 'caption' => $category['title'],
				'page_title' => $category['page_title'], 'path' => $category['path'], 'url' => $category['path'] . $ext,
				'no_follow' => $category['no_follow']);

			$times++;
			$eSyndiCat->resetTable();

			esynUtil::getBreadcrumb($category['parent_id'], $ext);
		}
		else
		{
			$times = 0;
		}
	}

	public static function getBreadcrumb($aCategory)
	{
		self::geCategoriesChain($aCategory, $ext = '');

		return array_reverse(self::$_breadcrumb);
	}

	public static function convertStr($str)
	{
		$str = is_array($str) ? $str['string'] : $str;

		require_once IA_CLASSES.'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'utf8_to_ascii');

		if (!utf8_is_ascii($str))
		{
			$str = utf8_to_ascii($str);
		}

		$str = preg_replace('/[^a-z0-9]+/i', '-', $str);
		$str = preg_replace('/\-+/', '-', $str);
		$str = trim($str, '-');

		return empty($str) ? "listing" : $str;
	}

	public static function inArray($needle, $haystack)
	{
		if (is_array($haystack))
		{
			foreach($haystack as $item)
			{
				if (is_array($item))
				{
					$ret = esynUtil::inArray($needle, $item);
				}
				else
				{
					$ret = ($item == $needle);
				}
			}

			return $ret;
		}

		return false;
	}

	/*
	 This function can(should) be used by the array_walk and array_walk_recursive functions
	*/
	public static function filenameEscape(&$item,$key)
	{
		$item = str_replace(array("`","~","/","\\"), "", $item);
	}

	public static function ArraySearchRecursive($needle, $haystack, $nodes=array())
	{
		foreach ($haystack as $key1=>$value1)
		{
			if (is_array($value1))
			{
				$nodes = esynUtil::ArraySearchRecursive($needle, $value1, $nodes);
			}
			elseif (($key1 == $needle) or ($value1 == $needle))
			{
				$nodes[] = array($key1=>$value1);
			}
		}
		if (empty($nodes))
		{
			return false;
		}else{
			return $nodes;
		}
	}

	// Return keys for search in two dim arrays..
	public static function arraySearch($needle, $haystack)
	{
		$value = false;

		if (!is_scalar($needle))
		{
			return false;
		}
		if (is_array($haystack))
		{
			foreach($haystack as $k=>$temp)
			{
				$search = array_search($needle, $temp);
				if (strlen($search) > 0 && $search >= 0)
				{
					$value[0] = $k;
					$value[1] = $search;
				}
			}
		}

		return $value;
	}

	public static function checkAccess()
	{
		global $esynAdmin;
		global $currentAdmin;

		$esynAdmin->setTable("admin_pages");
		$esynAcos = $esynAdmin->keyvalue("`id`, `aco`", "1=1 GROUP BY `aco`");
		$esynAdmin->resetTable();

		if (defined("IA_REALM") && !$currentAdmin['super'])
		{
			if (!in_array(IA_REALM, $currentAdmin['permissions']+$esynAcos, true))
			{
				esynUtil::accessDenied();
			}
		}
	}

	// Is PHP running from other account?
	public static function checkUid()
	{
		$ret = false;

		if (0 === strpos($_SERVER['DOCUMENT_ROOT'], '/'))
		{
			$ret = function_exists("posix_getuid") && getmyuid() != posix_getuid();
		}

		return $ret;
	}

	/*
	 * Create directory recursively
	 *
	 * @param string $aDirName path
	 */
	public static function mkdir($aDirName)
	{
		$cwd = getcwd();
		$path = false === strpos($aDirName, IA_HOME) ? $aDirName : substr($aDirName, strlen(IA_HOME) - 1);
		$path = explode(IA_DS, $path);
		chdir(IA_HOME);

		foreach ($path as $p)
		{
			if (empty($p)) continue;
			if (file_exists($p))
			{
				chdir( $p );
				continue;
			}

			if ( is_writeable( getcwd() ) )
			{
				mkdir( $p );
				esynUtil::checkUid() && chmod( $p, 0777 );
				chdir( $p );
			}
			else
			{
				$e  = 'Directory Creation Error | tmp_dir_permissions | ';
				$e .= 'Please set writable permission for ' . getcwd() . ' directory.';
				trigger_error($e, E_USER_ERROR);
			}
		}
		chdir( $cwd );
	}
}

class esynMessages
{
	const ALERT = 'alert';
	const ERROR = 'error';
	const NOTIFICATION = 'notification';
	const SYSTEM = 'system';

	private static $_validTypes = array(self::ALERT, self::ERROR, self::NOTIFICATION, self::SYSTEM);

	public static function getMessages()
	{
		if (!empty($_SESSION['messages']))
		{
			$ret = $_SESSION['messages'];

			unset($_SESSION['messages']);

			return $ret;
		}
		else
		{
			return array();
		}
	}

	public static function setMessage($msg, $error = false)
	{
		if (!$msg)
		{
			return false;
		}

		if (is_bool($error))
		{
			$messageType = $error ? 'error' : 'notification';
		}
		else
		{
			$messageType = in_array($error, self::$_validTypes) ? $error : 'notification';
		}

		unset($_SESSION['messages']);

		if (is_array($msg))
		{
			$_SESSION['messages'][$messageType] = $msg;
		}
		else
		{
			$_SESSION['messages'][$messageType][] = $msg;
		}
	}
}

function stripslashes_deep($value)
{
	return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
}

function check_syntax($code)
{
	return @eval('return true;' . $code);
}
