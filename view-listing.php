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

define('IA_REALM', "view_listing");

if (isset($_GET['id']) && preg_match("/\D/", $_GET['id']) || isset($_POST['id']) && preg_match("/\D/", $_POST['id']))
{
	$_GET['error'] = "404";
	include './error.php';
	die();
}

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory("Listing", "Category", "ListingField", "Layout");

$id = (int)$_GET['id'];
$error = false;
$msg = '';

require_once IA_INCLUDES . 'view.inc.php';

$eSyndiCat->startHook('topViewListing');

$listing = $esynListing->getListingById($id, $esynAccountInfo['id']);

$eSyndiCat->startHook('viewListingAfterGetListing');

if ($listing)
{
	$eSyndiCat->setTable("deep_links");
	$listing['_deep_links'] = $eSyndiCat->keyvalue("`url`, `title`", "`listing_id` = '{$listing['id']}'");
	$eSyndiCat->resetTable();

	if (!$listing['account_id'] || $esynAccountInfo['id'] != $listing['account_id'])
	{
		unset($esyndicat_actions['delete']);
	}

	if (!$listing['url'] || 'http://' == $listing['url'])
	{
		unset($esyndicat_actions['visit']);
	}
}

// get parent category info
if (!isset($_GET['cat']) || !$esynCategory->validPath($_GET['cat']))
{
	$_GET['cat'] = "";
}

$category_id = $esynCategory->one("`id`", "`path` = '{$_GET['cat']}'");

$eSyndiCat->setTable("listing_categories");
$crossed = $eSyndiCat->exists("`listing_id` = '{$_GET['id']}' AND `category_id` = '{$category_id}'");
$eSyndiCat->resetTable();

if ($esynConfig->getConfig('lowercase_urls'))
{
	$listing['title_alias'] = strtolower($listing['title_alias']);
	$listing['path'] = strtolower($listing['path']);
}

if (!$listing)
{
	$_GET['error'] = "404";
	include IA_HOME . 'error.php';
	exit;
}
elseif ($listing && ($listing['path'] != $_GET['cat'] || $listing['title_alias'] != $_GET['title']))
{
	esynutil::go2(esynLayout::printForwardUrl($listing), true);
}

$eSyndiCat->startHook('viewListing');

// display crossed categories modification & get list of crossed categories
$sql = "SELECT `categories`.* FROM `{$eSyndiCat->mPrefix}listing_categories` `listing_categories` ";
$sql .= "LEFT JOIN `{$eSyndiCat->mPrefix}categories` `categories` ";
$sql .= "ON `listing_categories`.`category_id` = `categories`.`id` ";
$sql .= "WHERE `listing_categories`.`listing_id` = '{$listing['id']}'";
$crossed_categories = $eSyndiCat->getAll($sql);
$esynSmarty->assignByRef('crossed_categories', $crossed_categories);

$esynSmarty->assignByRef('listing', $listing);

// get meta description and meta keywords for display
$description = !empty($listing['meta_description']) ? strip_tags($listing['meta_description']) : substr(strip_tags($listing['description']), 0, 155);
$esynSmarty->assignByRef('description', $description);

$keywords = !empty($listing['meta_keywords']) ? strip_tags($listing['meta_keywords']) : '';
$esynSmarty->assignByRef('keywords', $keywords);

$category_id = $listing['category_id'];
$plan_id = $listing['plan_id'] > 0 ? $listing['plan_id'] : null;
$plan = null;

if ($plan_id)
{
	$eSyndiCat->setTable("plans");
	$plan = $eSyndiCat->row("*", "`id` = '{$plan_id}'");
	$eSyndiCat->resetTable();
}

// get category
$category = $esynCategory->row("*", "`id` = '{$listing['category_id']}'");
$esynSmarty->assign('category', $category);

// get link fields for display
$fields = $esynListing->getFieldsByPage('view', $category, $plan);

$eSyndiCat->setTable('field_groups');
$groups = $eSyndiCat->onefield('`name`', "FIND_IN_SET('view', `pages`) ORDER BY `order`");
$eSyndiCat->resetTable();

$field_groups = array();

if ($fields)
{
	//$field_groups['non_group'] = array();

	foreach ($fields as $key => $field)
	{
		if ('textarea' == $field['type'])
		{
			if (!$field['editor'])
			{
				$listing[$field['name']] = nl2br($listing[$field['name']]);
			}

			if ('description' == $field['name'])
			{
				continue;
			}
		}

		if (!empty($listing[$field['name']]) || ($field['type'] == 'radio' && $listing[$field['name']] != '') || ('combo' == $field['type']) || ('checkbox' == $field['type'] && $listing[$field['name']] != ''))
		{
			if (!empty($field['group']) && in_array($field['group'], $groups))
			{
				$field_groups[$field['group']][] = $field;
			}
			else
			{
				$field_groups['non_group'][] = $field;
			}
		}
	}

	$temp = array();

	if (isset($field_groups['non_group']))
	{
		$temp['non_group'] = $field_groups['non_group'];

		unset($field_groups['non_group']);
	}

	if (!empty($groups))
	{
		foreach ($groups as $group)
		{
			if (isset($field_groups[$group]) && !empty($field_groups[$group]))
			{
				$temp[$group] = $field_groups[$group];
			}
		}
	}

	$field_groups = $temp;
}
$esynSmarty->assign('field_groups', $field_groups);

$esynSmarty->assign('title', esynSanitize::html(strip_tags($listing['title'])) . ' : ' . $category['title']);
$esynSmarty->assign('header', $listing['title']);

// breadcrumb formation
esynBreadcrumb::replaceEnd($esynI18N['view_listing']);
if ($category)
{
	// print categories breadcrumb
	$breadcrumb = esynUtil::getBreadcrumb($category['id']);
	esynBreadcrumb::addArray($breadcrumb);

	$esynSmarty->assign('cats_chain', array_reverse($breadcrumb));
}

$eSyndiCat->startHook('beforeListingDisplay');

$esynSmarty->display('view-listing.tpl');
