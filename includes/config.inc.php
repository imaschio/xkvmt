<?php
// eSyndiCat Pro 3.3.0 installation proccess generated configuration file: 27 February 2015 08:41:04

if (version_compare('5.2', PHP_VERSION, '>'))
{
	exit('eSyndiCat requires PHP 5.2 or higher to run properly.');
}

if (false === strpos(PHP_SAPI, "ap") && isset($_SERVER['REQUEST_URI']))
{
	$t = $_SERVER['REQUEST_URI'];
	if (false!==strpos($t, "?"))
	{
		$t = substr($t, 0, strpos($t, "?"));
	}
	if (false!==strpos($t, ".php/"))
	{
		$t = substr($t, 0, strpos($t, ".php")+4);
	}
	elseif (false === strpos($t, ".php"))
	{
		$t .= "index.php";
	}
	$_SERVER['SCRIPT_NAME'] = $t;
	unset($t);
}

// disable magic quotes
ini_set("magic_quotes_runtime", 'off');

// process stripslashes if magic_quotes is enabled on the server
if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
{
	$in = array(&$_GET, &$_POST, &$_COOKIE, &$_SERVER);
	while (list($k, $v) = each($in))
	{
		foreach ($v as $key => $val)
		{
			if (!is_array($val))
			{
				$in[$k][$key] = stripslashes($val);
				continue;
			}
			$in[] = & $in[$k][$key];
		}
	}
	unset($in);
}

define('IA_DS', DIRECTORY_SEPARATOR);

// directory where esyndicat is installed
define('IA_DIR', '');

define('IA_URL', 'http://seoportale.com/');

define('IA_HOME', '/home/wwwsyaqd/public_html' . IA_DS);
define('IA_INCLUDES', IA_HOME . 'includes' . IA_DS);
define('IA_CLASSES', IA_INCLUDES . 'classes' . IA_DS);
define('IA_PLUGINS', IA_HOME . 'plugins' . IA_DS);
define('IA_TEMPLATES', IA_HOME . 'templates' . IA_DS);
define('IA_TMP_NAME', 'tmp');
define('IA_TMP', IA_HOME . IA_TMP_NAME . IA_DS);
define('IA_CACHEDIR', IA_TMP . 'cache' . IA_DS);
define('IA_UPLOADS', IA_HOME . 'uploads' . IA_DS);

define('IA_ADMIN_DIR', 'admin');
define('IA_ADMIN_HOME', IA_HOME . IA_ADMIN_DIR . IA_DS);
define('IA_ADMIN_CLASSES', IA_CLASSES . 'admin' . IA_DS);

define('IA_SALT_STRING', 'd512792f5c');
define('IA_MYSQLVER', 41);
// define('IA_CONNECT_ADAPTER', 'mysqli');
define('IA_CONNECT_ADAPTER', 'mysql');

define('IA_DBHOST', 'localhost');
define('IA_DBNAME', 'wwwsyaqd_esyn935');
define('IA_DBUSER', 'wwwsyaqd_esyn935');
define('IA_DBPASS', '2d!5]3SPR3');
define('IA_DBPREFIX', 'esy_');
define('IA_DBPORT', '3306');

define('IA_COMPRESS', 0);

error_reporting(E_ALL ^ E_NOTICE);

/*
Level:
	0: production (no debug) the fastest
	1: turn on errors and show errors on the browser
	2: as 1 and show execution time of hooks, queries, page generation
*/
define('IA_DEBUG', 0);

$old_error_handler = set_error_handler('esynErrorHandler', E_ALL ^ E_NOTICE);
$timezone = ini_get('date.timezone');
if (empty($timezone))
{
	date_default_timezone_set('UTC');
}



if (IA_DEBUG == 0)
{
	ini_set("display_errors", "0");
}

function esynErrorHandler($errno, $errstr, $errfile, $errline)
{
	if (IA_DEBUG == 0)
	{
		return true;
	}

	if (error_reporting() == 0)
	{
		return;
	}

	$exit = false;

	switch ($errno)
	{
		case E_NOTICE:
			$log_file = "notice.log";
			$log_msg ="NOTICE: {$errstr} ";
			break;

		case E_USER_NOTICE:
			$log_file = "user_notice.log";
			$log_msg ="USER_NOTICE: {$errstr} ";
			break;

		case E_WARNING:
			$log_file = "error.log";
			$log_msg ="WARNING: {$errstr} ";
			break;

		case E_USER_ERROR:
			$log_file = "error.log";
			$log_msg = "FATAL_USER_ERROR: {$errstr} ";
			$exit = true;
			break;

		case E_USER_WARNING:
			$log_file = "error.log";
			$log_msg ="eSynDicat WARNING: {$errstr} ";
			break;

		default:
			$log_file = "error.log";
			$log_msg = $errstr.' ';
			break;
	}

	$errfile = str_replace(IA_HOME, '', $errfile);
	$log_file = IA_TMP."log".IA_DS.$log_file;
	$log_msg_date = "[ ".date("d M Y H:i:s")." ]\t";

	$log_msg .= "{$log_msg_date} in $errfile:$errline\n\n";

	if (IA_DEBUG > 0)
	{
		if (!file_exists(dirname($log_file)))
		{
			$result = @mkdir(dirname($log_file), 0777);

			if(!$result)
			{
				echo esyn_display_error("Directory Creation Error | tmp_dir_permissions | Can not create the '" . IA_TMP_NAME . "log' directory.");
				exit;
			}
		}

		if (is_writeable(dirname($log_file)))
		{
			if(file_exists($log_file))
			{
				if(!is_writeable($log_file))
				{
					chmod($log_file, 0777);
				}
			}

			file_put_contents($log_file, $log_msg, FILE_APPEND);
		}
		else
		{
			echo esyn_display_error("Directory Permissions Error | dir_permissions_error | The '" . IA_TMP_NAME . "log' directory is not writable. Please set writable permissions.");
			exit;
		}
	}

	if ($exit)
	{
		echo esyn_display_error($errstr);
		exit;
	}

	if (IA_DEBUG > 0)
	{
		echo nl2br($log_msg);
	}
}

function esyn_display_error($errstr)
{
	$content = file_get_contents(IA_INCLUDES . 'common' . IA_DS . 'error_handler.html');

	$error_solutions = '';

	list($error_title, $error_key, $error_description) = explode(' | ', $errstr);

	switch($error_key)
	{
		case 'db_connect_error':
			$error_solutions .= 'Below is a list of possible solutions:';
			$error_solutions .= '<ul><li>Make sure you have the correct username and password.</li>';
			$error_solutions .= '<li>Make sure you have the correct database hostname.</li>';
			$error_solutions .= '<li>Make sure that the database server is running.</li></ul>';
			break;
		case 'db_select_error':
			$error_solutions .= 'Below is a list of possible solutions:';
			$error_solutions .= '<ul><li>Make sure you have created the database.</li>';
			$error_solutions .= '<li>Make sure you have granted privileges for your user on the database.</li>';
			$error_solutions .= '<li>Make sure you have the correct database name.</li></ul>';
			break;
		case 'tmp_dir_absent':
			$error_solutions .= 'Below is a list of possible solutions:';
			$error_solutions .= "<ul><li>Make sure " . IA_TMP_NAME . " directory exists.</li></ul>";
			break;
		case 'tmp_dir_permissions':
			$error_solutions .= 'Below is a list of possible solutions:';
			$error_solutions .= "<ul><li>Make sure " . IA_TMP_NAME . " directory has writable permissions.</li>";
			$error_solutions .= "<li>Run the following command: <i>chmod 777 " . IA_TMP_NAME . "</i></li></ul>";
			break;
		case 'dir_permissions_error':
			$error_solutions .= 'Below is a list of possible solutions:';
			$error_solutions .= "<ul><li>Make sure the directory exists.</li>";
			$error_solutions .= "<li>Make sure the directory has writable permissions.</li>";
			$error_solutions .= "<li>Run the following command: <i>chmod 777 " . IA_TMP_NAME . "</i></li></ul>";
			break;
	}

	if (empty($error_key))
	{
		$error_key = 'docs';
	}

	$search = array('{title}', '{base_url}', '{error_title}', '{error_description}', '{error_solutions}', '{error_key}', '{additional}');
	$replace = array($error_title . ' :: eSyndiCat Directory Software ' . IA_VERSION, IA_URL, $error_title, $error_description, $error_solutions, $error_key, '');

	$content = str_replace($search, $replace, $content);

	return $content;
}