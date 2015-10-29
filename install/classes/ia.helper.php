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

class iaHelper
{
	const PLUGINS_LIST_SOURCE = 'http://tools.esyndicat.com/plugins-list/?version=%s';
	const PLUGINS_DOWNLOAD_SOURCE = 'http://tools.esyndicat.com/download-plugin/?plugin=%s&version=%s';

	const USER_AGENT = 'eSyndiCat CMS Bot';

	const HTTP_STATUS_OK = 200;

	const INSTALLATION_FILE_NAME = 'install.xml';

	public static function isAjaxRequest()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	public static function getIniSetting($name)
	{
		return ini_get($name) == '1' ? 'ON' : 'OFF';
	}

	public static function cleanUpDirectoryContents($directory, $removeFolder = false)
	{
		$directory = substr($directory, -1) == IA_DS
			? substr($directory, 0, -1)
			: $directory;
		if (!file_exists($directory) || !is_dir($directory))
		{
			return false;
		}
		elseif (is_readable($directory))
		{
			$handle = opendir($directory);
			while ($item = readdir($handle))
			{
				if (!in_array($item, array('.', '..', '.htaccess')))
				{
					$path = $directory . IA_DS . $item;
					if (is_dir($path))
					{
						self::cleanUpDirectoryContents($path, true);
					}
					else
					{
						unlink($path);
					}
				}
			}
			closedir($handle);
			if ($removeFolder)
			{
				if (!rmdir($directory))
				{
					return false;
				}
			}
		}
		return true;
	}

	public static function loadCoreClass($name)
	{
		global $esynAdmin, $eSyndiCat, $esynConfig;

		if (!class_exists('esynAdmin'))
		{
			define('IA_DBHOST', self::getPost('dbhost', 'localhost'));
			define('IA_DBPORT', self::getPost('dbport', 3306));
			define('IA_DBUSER', self::getPost('dbuser'));
			define('IA_DBPASS', self::getPost('dbpwd'));
			define('IA_DBNAME', self::getPost('dbname'));
			define('IA_DBPREFIX', self::getPost('prefix', '', false));
			define('IA_DEBUG', false);

			define('IA_MYSQLVER', version_compare('4.1', mysql_get_server_info(), '<=') ? '41' : '40');
			define('IA_CONNECT_ADAPTER', function_exists('mysqli_connect') ? 'mysqli' : 'mysql');

			define('IA_PLUGINS', IA_HOME . 'plugins' . IA_DS);
			define('IA_INCLUDES', IA_HOME . 'includes' . IA_DS);
			define('IA_CLASSES', IA_INCLUDES . 'classes' . IA_DS);
			define('IA_TMP_NAME', 'tmp');
			define('IA_TMP', IA_HOME . IA_TMP_NAME . IA_DS);
			define('IA_CACHEDIR', IA_TMP . 'cache' . IA_DS);
			define('IA_TEMPLATES', IA_HOME . 'templates' . IA_DS);

			define('IA_ADMIN_DIR', 'admin');
			define('IA_ADMIN_HOME', IA_HOME . IA_ADMIN_DIR . IA_DS);
			define('IA_ADMIN_CLASSES', IA_CLASSES . 'admin' . IA_DS);

			set_include_path(IA_CLASSES);

			require_once 'esynDatabase.' . IA_CONNECT_ADAPTER . '.php';
			require_once 'esynCacher.php';
			require_once 'esynMailer.php';
			require_once 'eSyndiCat.php';
			require_once 'esynConfig.php';

			require_once IA_INCLUDES . 'util.php';

			require_once IA_ADMIN_CLASSES . 'esynAdmin.php';
			require_once IA_ADMIN_CLASSES . 'esynTemplate.php';
			require_once IA_ADMIN_CLASSES . 'esynPlugin.php';

			$eSyndiCat = new eSyndiCat();
			$esynConfig = new esynConfig();
			$esynAdmin = new esynAdmin();

			define('IA_IN_ADMIN', true);
		}

		$esynAdmin->factory($name);

		return true;
	}

	public static function hasAccessToRemote()
	{
		if (extension_loaded('curl'))
		{
			return true;
		}

		if (ini_get('allow_url_fopen'))
		{
			if (function_exists('fsockopen'))
			{
				return true;
			}
			if (function_exists('stream_get_meta_data') && in_array('http', stream_get_wrappers()))
			{
				return true;
			}
		}

		return false;
	}

	public static function getPost($name, $default = '', $notEmpty = true)
	{
		if (isset($_POST[$name]))
		{
			if (empty($_POST[$name]) && $notEmpty) return $default;
			return $_POST[$name];
		}
		return $default;
	}

	public static function email($email)
	{
		return (bool)preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email);
	}

	public static function getRemoteContent($sourceUrl, $savePath = null)
	{
		$result = false;

		if (extension_loaded('curl'))
		{
			set_time_limit(60);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $sourceUrl);
			curl_setopt($ch, CURLOPT_HEADER, 0);

			if ($savePath)
			{
				$fh = fopen($savePath, 'w');
				curl_setopt($ch, CURLOPT_FILE, $fh);
			}
			else
			{
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			}

			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_USERAGENT, self::USER_AGENT);
			$response = curl_exec($ch);
			if (self::HTTP_STATUS_OK == curl_getinfo($ch, CURLINFO_HTTP_CODE))
			{
				$result = $response;
			}
			curl_close($ch);

			if (isset($fh))
			{
				fclose($fh);
			}
		}
		elseif (ini_get('allow_url_fopen'))
		{
			ini_set('user_agent', self::USER_AGENT);
			$result = @file_get_contents($sourceUrl);
			ini_restore('user_agent');

			if ($result !== false)
			{
				if ($savePath)
				{
					$fh = fopen($savePath, 'w');
					$result = fwrite($fh, $result);
					fclose($fh);
				}
			}
		}

		return $result;
	}

	public static function _html ($string, $mode = ENT_QUOTES)
	{
		return htmlspecialchars($string, $mode);
	}

	public static function _sql ($string)
	{
		if (is_array($string))
		{
			foreach ($string as $k => $v)
			{
				$string[$k] = self::_sql($v);
			}
		}
		else
		{
			$string = mysql_real_escape_string($string);
		}
		return $string;
	}

	protected static function _getInstalledPluginsList()
	{
		self::loadCoreClass('db', 'core');
		$iaDb = iaCore::instance()->iaDb;

		$list = $iaDb->onefield('name', "type = 'plugin'", 0, null, 'extras');

		return empty($list)
			? array()
			: $list;
	}

	public static function getRemotePluginsList($coreVersion, $checkIfInstalled = true)
	{
		$result = false;

		$response = self::getRemoteContent(sprintf(self::PLUGINS_LIST_SOURCE, $coreVersion));
		if ($response !== false)
		{
			$response = json_decode($response);
			if (isset($response->plugins) && count($response->plugins))
			{
				$result = $response->plugins;
			}
		}

		if ($checkIfInstalled)
		{
			$installedPlugins = self::_getInstalledPluginsList();
			foreach ($installedPlugins as $pluginName) {
				if (isset($result->$pluginName))
				{
					$result->$pluginName->installed = 1;
				}
			}
		}

		return $result;
	}

	// performs complete plugin installation
	public static function installRemotePlugin($pluginName)
	{
		$result = false;

		if ($pluginName)
		{
			$downloadPath = self::_composePath(array(IA_HOME, 'tmp', 'plugins'));
			if (!is_dir($downloadPath))
			{
				mkdir($downloadPath);
			}

			$savePath = $downloadPath . $pluginName . '.plugin';
			if (!self::getRemoteContent(sprintf(self::PLUGINS_DOWNLOAD_SOURCE, $pluginName, IA_VERSION), $savePath))
			{
				return false;
			}

			if (is_file($savePath))
			{
				$extrasFolder = self::_composePath(array(IA_HOME, 'plugins'));
				if (is_writable($extrasFolder))
				{
					$pluginFolder = self::_composePath(array($extrasFolder, $pluginName));
					if (is_dir($pluginFolder))
					{
						self::cleanUpDirectoryContents($pluginFolder);
					}
					else
					{
						mkdir($pluginFolder);
					}

					require_once self::_composePath(array(IA_HOME, 'includes', 'utils')) . 'pclzip.lib.php';
					$zipSource = new PclZip($savePath);

					if ($zipSource->extract(PCLZIP_OPT_PATH, $extrasFolder))
					{
						$installationFile = file_get_contents($pluginFolder . self::INSTALLATION_FILE_NAME);
						if ($installationFile !== false)
						{
							$iaExtras = self::loadCoreClass('extras');

							$iaExtras->setXml($installationFile);
							$iaExtras->parse();

							if (!$iaExtras->getNotes())
							{
								$result = $iaExtras->install();

							}
						}
					}
				}

				iaHelper::cleanUpDirectoryContents(IA_HOME . 'tmp' . IA_DS);
			}
		}

		return $result;
	}

	// handy function to create a path
	protected static function _composePath (array $path)
	{
		foreach ($path as $key => $value)
		{
			$path[$key] = trim($value, IA_DS);
		}
		return (stripos(PHP_OS, 'win') !== false ? '' : IA_DS) . implode(IA_DS, $path) . IA_DS;
	}
}
