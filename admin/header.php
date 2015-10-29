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

header("Content-Type: text/html; charset=utf-8");

session_start();

/** reload browser cache if the trigger is true **/
if (isset($_SESSION['reloadCache']) && $_SESSION['reloadCache'])
{
	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

	$_SESSION['reloadCache'] = false;
}

// including common file classes
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'config.inc.php';

define('IA_ADMIN_EXPIRY', 3600);

require_once IA_INCLUDES . 'functions.php';
require_once IA_INCLUDES . 'utils' . IA_DS . 'pagerank.inc.php';

start_time_render();

// checking for tmp directory
if (!file_exists(IA_TMP))
{
	trigger_error('Temporary Directory Absent | tmp_dir_absent | The ' . IA_TMP_NAME . ' directory does not exist. Please create it and set writeable permissions.', E_USER_ERROR);
}
elseif (!is_writeable(IA_TMP))
{
	trigger_error('Temporary Directory Permissions | tmp_dir_permissions | The ' . IA_TMP_NAME . ' directory is not writeable. Please set writeable permissions.', E_USER_ERROR);
}

define('IA_IN_ADMIN', true);
define('IA_CACHING', false);

require_once IA_CLASSES . 'esynDatabase.' . IA_CONNECT_ADAPTER . '.php';
require_once IA_CLASSES . 'esynCacher.php';
require_once IA_CLASSES . 'esynMailer.php';
require_once IA_CLASSES . 'eSyndiCat.php';
require_once IA_ADMIN_CLASSES . 'esynAdmin.php';
require_once IA_CLASSES . 'esynConfig.php';

require_once IA_INCLUDES . 'util.php';

$esynAdmin = new esynAdmin();
$esynAdmin->parseUrl();

$esynConfig = new esynConfig();

$esynAdmin->startHook('adminTheVeryStart');

$currentAdmin = array();
$login = true;

$esynConfig->setConfig('esyn_url', IA_URL);
$esynAdmin->mConfig['esyn_url'] = IA_URL;

// user is _not_ at login page and already logged in. just authenticate him
if (false === strpos($_SERVER['SCRIPT_NAME'], "login.php") && !empty($_SESSION['admin_name']) && !empty($_SESSION['admin_pwd']))
{
	$name = $_SESSION['admin_name'];

	$esynAdmin->setTable("admins");
	$currentAdmin = $esynAdmin->row("*", "username = :name AND `status` = 'active'", array('name' => $name));
	$esynAdmin->resetTable();

	$esynAdmin->setTable("admin_permissions");
	$currentAdmin['permissions'] = $esynAdmin->onefield("`aco`", "`allow`='1' AND `admin_id` = :id", array('id' => $currentAdmin['id']));
	$esynAdmin->resetTable();

	if (!is_array($currentAdmin['permissions']))
	{
		$currentAdmin['permissions'] = array();
	}

	$pwd = crypt($currentAdmin['password'], IA_SALT_STRING);

	// save the last URL of page admin visited
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 'XMLHttpRequest' != $_SERVER['HTTP_X_REQUESTED_WITH'])
	{
		$exclude_pages = array(
			'logout.php',
			'?action=get-state'
		);

		$save_last_url = true;

		foreach ($exclude_pages as $page)
		{
			if (false !== strpos($_SERVER['REQUEST_URI'], $page))
			{
				$save_last_url = false;

				break;
			}
		}

		if ($save_last_url)
		{
			$domain = esynUtil::getDomain(IA_URL);

			$domain = $domain ? $domain : '';

			setcookie('admin_lasturl', $_SERVER['REQUEST_URI'], time() + 3600, '/' . IA_ADMIN_DIR . '/', $domain);
		}
	}

	if (0 === strcmp($pwd, $_SESSION['admin_pwd']))
	{
		$login = false;

		if (IA_ADMIN_EXPIRY > 0)
		{
			// admin activity expiration
			if ($_SERVER['REQUEST_TIME'] - $_SESSION['admin_lastAction'] > IA_ADMIN_EXPIRY)
			{
				$_SESSION['admin_name'] = $_SESSION['admin_pwd'] = false;

				session_destroy();

				$login = true;
			}
			else
			{
				$_SESSION['admin_lastAction'] = $_SERVER['REQUEST_TIME'];
			}
		}
	}
	else
	{
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH'])
		{
			$last_url = $_SERVER['HTTP_REFERER'];
		}
		else
		{
			$last_url = $_SERVER['REQUEST_URI'];
		}

		setcookie('admin_lasturl', $last_url, time() + 3600, '/');

		$login = true;
	}
}
elseif (false !== strpos($_SERVER['SCRIPT_NAME'], "login.php") || false !== strpos($_SERVER['SCRIPT_NAME'], "password-restore.php"))
{
	$login = false;
}

// Calling all registered hooks to admin authentication
$esynAdmin->startHook('adminAuthentication');

if ($login && !defined('IA_INTEGRATED'))
{
	$f = IA_URL . IA_ADMIN_DIR . '/';

	$esynAdmin->startHook('adminAuthenticationFailedAction');

	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH'] && in_array($_SERVER['HTTP_X_FLAGTOPREVENTCSRF'], array('using ExtJS', 'using jQuery')))
	{
		header("X-eSyndiCat-Redirect: login");
		exit;
	}

	esynUtil::go2($f . 'login.php');
}

if (!empty($_SESSION['admin_lng']) && array_key_exists($_SESSION['admin_lng'], $esynAdmin->mLanguages))
{
	$language = $_SESSION['admin_lng'];
}
elseif (isset($_GET['lang']) && 'default' == $_GET['lang'] || !empty($_GET['lang']) && array_key_exists($_GET['lang'], $esynAdmin->mLanguages))
{
	$language = $_GET['lang'];
}
else
{
	$language = $esynConfig->getConfig('lang', 'en');
}

define("IA_LANGUAGE", $language);
define("IA_VERSION", $esynConfig->getConfig('version'));
define("IA_ADMIN_URL", IA_URL . IA_ADMIN_DIR . '/');
define("IA_JSCSS_CACHEDIR", IA_CACHEDIR . IA_ADMIN_DIR);

if (!file_exists(IA_JSCSS_CACHEDIR))
{
	esynUtil::mkdir(IA_JSCSS_CACHEDIR);
}

if (IA_VERSION != IA_FILES_VERSION)
{
	if (!isset($esynI18N['esyn_version_mismatch']))
	{
		$esynI18N['esyn_version_mismatch'] = 'Your files and MySQL structure versions mismatch. You need to run MySQL structure upgrade script. <a href="controller.php?file=database&page=import">Click here</a> to start upgrade process.';
	}

	esynMessages::setMessage($esynI18N['esyn_version_mismatch'], esynMessages::SYSTEM);
}
$esynI18N = $esynAdmin->getI18N($language, 'admin');

// protect against CSRF attack - bad referrer
if (!empty($_SERVER['HTTP_REFERER']) && false === strpos($_SERVER['HTTP_REFERER'], strtolower(IA_URL)))
{
	if (false === strpos($_SERVER['SCRIPT_NAME'], "login.php"))
	{
		esynUtil::csrfAttack();
	}
}

// protect against CSRF attack - look for hidden key, if using XMLHTTPREQUEST then it should be considered as safe
$emptyCsrf = (!empty($_SESSION['prevent_csrf']) && empty($_POST['prevent_csrf']));
$emptyFlag = (!empty($_POST) && empty($_POST['prevent_csrf']) && !isset($_SERVER['HTTP_X_FLAGTOPREVENTCSRF']));

if (($emptyCsrf || $emptyFlag) && (!defined('IA_REALM') || 'adminer' != IA_REALM))
{
	if (!empty($_POST))
	{
		unset($_SESSION['prevent_csrf']);
		esynUtil::csrfAttack();
	}
}
unset($_SESSION['prevent_csrf']);

if (isset($_GET['clearcache']))
{
	$esynAdmin->mCacher->clearAll('', true);
	$esynAdmin->mCacher->clearTpl(array('admin', $esynConfig->gC('tmpl')));

	esynMessages::setMessage(_t('cache_cleared'));

	esynUtil::reload(array("clearcache" => null));
}
