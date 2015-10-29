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

$view  = $_GET['view'];
$types = array('new', 'top', 'popular', 'account', 'random', 'favorites');

if (!in_array($view, $types, true))
{
	$_GET['error'] = "404";
	include './error.php';
	exit;
}

define('IA_REALM', "{$view}_listings");

if ('account' == $view || 'favorites' == $view)
{
	define("IA_THIS_PAGE_PROTECTED", true);
}

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory('Listing', 'Layout');

require_once IA_INCLUDES . 'view.inc.php';

$cached = false;

$num_index = $esynConfig->getConfig('num_index_listings');
$num_listings = $esynConfig->getConfig('num_get_listings');

// gets current page and defines start position
$page	= isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page	= ($page < 1) ? 1 : $page;
$start	= ($page - 1) * $num_listings;

$view_title = $view;
switch ($view)
{
	case 'account':
		$start	= ($page - 1) * $num_index;
		$view_title = 'my';
		$listings = $esynListing->getListingsByAccountId($esynAccountInfo['id'], '', $start, $num_index);
		$num_display_listings = $num_index;
		break;

	case 'favorites':
		$start	= ($page - 1) * $num_index;
		$view_title = 'my_favorite';
		$listings = $esynListing->getFavoriteListingByAccountId($esynAccountInfo['id'], $start, $num_index);
		$num_display_listings = $num_index;
		break;

	case 'popular':
		$listings = $esynListing->getPopular($start, $num_listings, $esynAccountInfo['id']);
		$num_display_listings = $num_listings;
		break;

	case 'top':
		$listings = $esynListing->getTop($start, $num_listings, $esynAccountInfo['id']);
		$num_display_listings = $num_listings;
		break;

	case 'new':
		$listings = $esynListing->getLatest($start, $num_listings, $esynAccountInfo['id']);
		$num_display_listings = $num_listings;
		break;

	case 'random':
		$start	= ($page - 1) * $num_index;
		$listings = $esynListing->getRandom($num_index, $esynAccountInfo['id']);
		$num_display_listings = $num_index;
		break;
}
$esynSmarty->assignByRef('listings', $listings);

$total_listings = $esynListing->foundRows();
$esynSmarty->assignByRef('total_listings', $total_listings);
$esynSmarty->assignByRef('num_display_listings', $num_display_listings);

// get link fields for display
$fields = $esynListing->getFieldsByPage(3);
$esynSmarty->assignByRef('fields', $fields);

$eSyndiCat->startHook('phpFrontListingsAfterGetListings');

$title = $esynI18N[$view_title . '_listings'];

// breadcrumb formation
esynBreadcrumb::add($title);

if ($page > 1)
{
	$title .= ' ' . $esynI18N['page'] . ' ' . $page;
}
$esynSmarty->assign('title', $title);

$url = $view . '-listings{page}.html';
$esynSmarty->assign('url', $url);

$c = count($listings);
$esynSmarty->assign('num_listings', $num_listings > $c ? $c : $num_listings);

$esynSmarty->assign('view', $view);

$esynSmarty->display('listings.tpl');
