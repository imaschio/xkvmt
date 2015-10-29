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

define('IA_REALM', "view_account");

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

if (!$esynConfig->getConfig('accounts'))
{
	$_GET['error'] = "404";
	include './error.php';
	die();
}

$eSyndiCat->factory('Account', 'Listing', 'Layout');

require_once IA_INCLUDES . 'view.inc.php';

$account = $esynAccount->row("*", "`username` = :account AND `status` = 'active'", array('account' => urldecode($_GET['account'])));

if (empty($account))
{
	$_GET['error'] = "404";
	include './error.php';
	die();
}

/** gets current page and defines start position **/
$page = empty($_GET['page']) ? 0 : (int)$_GET['page'];
$page = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $esynConfig->getConfig('num_index_listings');

$listings = $esynListing->getListingsByAccountId($account['id'], 'active', $start, $esynConfig->getConfig('num_index_listings'));
$total_listings = $esynListing->getNumListingsByAccountId($account['id'], 'active');

/** set the property lettter **/
$alphas = array('0-9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

$account_first_letter = strtoupper(substr($account['username'], 0, 1));

$alpha = in_array($account_first_letter, $alphas) ? $account_first_letter : 'A';

/** set the title of page **/
$title = strip_tags($account['username']) . ' ' . strtolower($esynI18N['account']);

// set url for pagination menu
$url = $esynLayout->printAccountUrl(array('account' => $account));
$url .= '?page={page}';

// set breadcrumb
esynBreadcrumb::add($esynI18N['accounts'], 'accounts/');
esynBreadcrumb::add($alpha, 'accounts/' . $alpha . '/');
esynBreadcrumb::add(esynSanitize::html(strip_tags($account['username'])));

$esynSmarty->assign('title', $title);
$esynSmarty->assign('account', $account);
$esynSmarty->assign('listings', $listings);
$esynSmarty->assign('total_listings', $total_listings);
$esynSmarty->assign('url', $url);

$eSyndiCat->startHook('phpFrontViewAccountBeforeDisplay');

$esynSmarty->display('view-account.tpl');
