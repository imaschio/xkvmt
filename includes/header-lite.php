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

// configs, includes, authentication, authorization section
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.inc.php';
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

// including common file classes
require_once IA_CLASSES . 'esynDatabase.' . IA_CONNECT_ADAPTER . '.php';
require_once IA_CLASSES . 'esynCacher.php';
require_once IA_CLASSES . 'esynMailer.php';
require_once IA_CLASSES . 'eSyndiCat.php';
require_once IA_CLASSES . 'esynConfig.php';

require_once IA_INCLUDES . 'util.php';

$eSyndiCat = new eSyndiCat();
$eSyndiCat->parseUrl();
$esynConfig = esynConfig::instance();

$eSyndiCat->startHook('theVeryStart');

$esynConfig->setConfig('esyn_url', IA_URL);

if (isset($_GET['switchToNormalMode']) && !empty($_SESSION['frontendManageMode']))
{
	$_SESSION['frontendManageMode'] = false;
}

// define used language
$language = !empty($_GET['language']) ? $_GET['language'] : (!empty($_COOKIE['language']) ? $_COOKIE['language'] : false);
if (!$language || !array_key_exists($language, $eSyndiCat->mLanguages))
{
	$language = $esynConfig->getConfig('lang');
}
$esynConfig->setConfig('lang', $language);
$esynI18N = $eSyndiCat->getI18N($language);

define("IA_LANGUAGE", $language);
define("IA_CACHING", false);
define("IA_VERSION", $esynConfig->getConfig('version'));
define("IA_JSCSS_CACHEDIR", IA_CACHEDIR);

header("X-Directory-Script: eSyndiCat Pro v" . IA_VERSION);
