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

define('IA_REALM', "accounts");

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

if (!$esynConfig->getConfig('accounts'))
{
	$_GET['error'] = "404";
	include './error.php';
	die();
}

$eSyndiCat->factory('Account', 'Layout');

require_once IA_INCLUDES . 'view.inc.php';

if (!$esynPage->exists("`name` = '" . IA_REALM . "' AND `status` = 'active'"))
{
	$_GET['error'] = "404";
	include './error.php';
	die();
}

$search_alphas = array('0-9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$esynSmarty->assign('search_alphas', $search_alphas);

$alpha = (isset($_GET['alpha']) && in_array($_GET['alpha'], $search_alphas)) ? $_GET['alpha'] : false;
$esynSmarty->assign('alpha', $alpha);

$cause = $alpha ? ('0-9' == $alpha ? "(`username` REGEXP '^[0-9]') AND " : "(`username` LIKE '{$alpha}%') AND ") : '';

$num_accounts = $esynConfig->getConfig('num_get_accounts');
$page = 1;

// gets current page and defines start position
$page	= !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$page	= ($page < 1) ? 1 : $page;
$start	= ($page - 1) * $num_accounts;

$url = !empty($alpha) ? "accounts/{$alpha}/index{page}.html" : "accounts/index{page}.html";
$esynSmarty->assign('url', $url);

$total_accounts = $esynAccount->one("count(*)", "{$cause} `status` = 'active'");
$esynSmarty->assign('total_accounts', $total_accounts);

$accounts = $esynAccount->all("*", "{$cause} `status` = 'active' LIMIT {$start}, {$num_accounts}");
$esynSmarty->assign('accounts', $accounts);

$eSyndiCat->startHook('phpFrontAccountsAfterGetAccounts');

esynBreadcrumb::add($esynI18N['accounts'], 'accounts/');
if ($alpha)
{
	esynBreadcrumb::add($alpha);
}
$esynSmarty->assign('title', $esynI18N['accounts']);

$esynSmarty->display('accounts.tpl');
