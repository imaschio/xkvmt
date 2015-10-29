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

session_start();

header("Content-Type: text/html; charset=utf-8");

// include configuration file
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.inc.php';
require_once IA_INCLUDES . 'functions.php';
require_once IA_INCLUDES . 'utils' . IA_DS . 'pagerank.inc.php';

start_time_render();

// checking for tmp directory
if (!file_exists(IA_TMP))
{
	trigger_error("Temporary Directory Absent | tmp_dir_absent | The '" . IA_TMP_NAME . "' directory does not exist. Please create it and set writable permissions.", E_USER_ERROR);
}
elseif (!is_writeable(IA_TMP))
{
	trigger_error("Temporary Directory Permissions | tmp_dir_permissions | The '" . IA_TMP_NAME . "' directory is not writable. Please set writable permissions.", E_USER_ERROR);
}

// including common file classes
require_once IA_CLASSES . 'esynDatabase.' . IA_CONNECT_ADAPTER . '.php';
require_once IA_CLASSES . 'esynCacher.php';
require_once IA_CLASSES . 'esynMailer.php';
require_once IA_CLASSES . 'eSyndiCat.php';
require_once IA_CLASSES . 'esynConfig.php';

require_once IA_INCLUDES . 'util.php';

$eSyndiCat = new eSyndiCat();
$eSyndiCat->parseUrl();

time_render('aftereSyndiCatObjectInit');

$esynConfig = esynConfig::instance();

$eSyndiCat->startHook('theVeryStart');

$esynAccountInfo = null;

$esynConfig->setConfig('esyn_url', IA_URL);
$eSyndiCat->mConfig['esyn_url'] = IA_URL;

if (isset($_GET['switchToNormalMode']) && !empty($_SESSION['frontendManageMode']))
{
	$_SESSION['frontendManageMode'] = false;
}
elseif(isset($_GET['switchToNormalMode']) && !empty($_SESSION['preview']))
{
	unset($_SESSION['preview']);
}

if (!empty($_COOKIE['account_id']) && !empty($_COOKIE['account_pwd']) && ctype_digit($_COOKIE['account_id']) && $_COOKIE['account_id'] > 0 && !preg_match("/\s/", $_COOKIE['account_pwd']))
{
	$eSyndiCat->factory('Account');
	$esynAccountInfo = $esynAccount->getInfo($_COOKIE['account_id']);

	if (empty($esynAccountInfo))
	{
		// delete cookies
		setcookie('account_id',	'', time() - 3600, '/');
		setcookie('account_pwd', '', time() - 3600, '/');
	}
}

$esyn_dir = IA_DIR;
$esyn_dir = !empty($esyn_dir) ? '/' . IA_DIR : '';
$exclude_pages = array(
	$esyn_dir . '/login.php',
	$esyn_dir . '/confirm.php',
	$esyn_dir . '/register.php',
	$esyn_dir . '/logout.php',
	$esyn_dir . '/forgot.php',
	$esyn_dir . '/controller.php',
	$esyn_dir . '/get-address.php',
	$esyn_dir . '/get-fields.php',
	$esyn_dir . '/get-categories.php',
	$esyn_dir . '/post_pay.php'
);
if (!in_array($_SERVER['PHP_SELF'], $exclude_pages))
{
	$_SESSION['esyn_last_page'] = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
elseif (!isset($_SESSION['esyn_last_page']))
{
	$_SESSION['esyn_last_page'] = IA_URL;
}

// check if account is logged in
if (!empty($esynAccountInfo) && is_array($esynAccountInfo))
{
	$pwd = crypt($esynAccountInfo['password'], IA_SALT_STRING);
	if (($_COOKIE['account_id'] != $esynAccountInfo['id']) || ($_COOKIE['account_pwd'] != $pwd))
	{
		esynUtil::go2('login.php');
	}
}
elseif (defined("IA_THIS_PAGE_PROTECTED"))
{
	esynUtil::go2('login.php');
}

if (isset($esynAccountInfo) && !empty($esynAccountInfo) && '1' == $esynConfig->getConfig('captcha'))
{
	$esynConfig->setConfig('captcha', '0');
}

// select default language from browser preferences
$language = !empty($_GET['language']) ? $_GET['language'] : (!empty($_COOKIE['language']) ? $_COOKIE['language'] : false);
if (!$language && isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && count($eSyndiCat->mLanguages) > 1)
{
	$tmp = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	foreach ($tmp as $l)
	{
		$l = trim($l);
		if (array_key_exists($l, $eSyndiCat->mLanguages))
		{
			$language = $l;
			setcookie('language', $l, time() + 3600*24*30); // we are not going to check this every time
			break;
		}
	}
}
if (!$language || !array_key_exists($language, $eSyndiCat->mLanguages))
{
	$language = $esynConfig->getConfig('lang');
}
$esynConfig->setConfig('lang', $language);
$esynI18N = $eSyndiCat->getI18N($language);

define("IA_LANGUAGE", $language);
define("IA_CACHING", false);
define("IA_VERSION", $esynConfig->getConfig('version'));
define('IA_TEMPLATE', IA_TEMPLATES . $esynConfig->getConfig('tmpl') . '/');
define("IA_JSCSS_CACHEDIR", IA_CACHEDIR);

header("X-Directory-Script: eSyndiCat Pro v" . IA_VERSION);

if (!$esynConfig->getConfig('display_frontend'))
{
	$accessDenied = true;
	if (isset($_SESSION['admin_name']) && $_SESSION['admin_name'])
	{
		$eSyndiCat->setTable('admins');
		$accessDenied = !(bool)$eSyndiCat->row("*", "username = :name AND `status` = 'active'", array('name' => $_SESSION['admin_name']));
		$eSyndiCat->resetTable();

		esynMessages::setMessage('disabled_frontend_admin_notification', esynMessages::SYSTEM);
	}

	if ($accessDenied)
	{
		$error = $esynConfig->getConfig('underconstruction');

		$content = file_get_contents(IA_INCLUDES . 'common' . IA_DS . 'error_handler.html');

		$error_solutions = '';
		$error_description = $error;
		$error_title = '&nbsp;';

		$search = array('{title}', '{base_url}', '{error_title}', '{error_description}', '{error_solutions}', '{additional}');
		$replace = array($esynConfig->getConfig('site').' '.$esynConfig->getConfig('suffix'), IA_URL, $error_title, $error_description, $error_solutions, '');

		$content = str_replace($search, $replace, $content);
		$content = preg_replace('/<p class="solution">.*<\/p>/i', ' ', $content);

		echo $content;
		exit;
	}
}
