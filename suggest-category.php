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

define('IA_REALM', "suggest_category");

$id = $cid = false;
if (
	isset($_POST['category_id']) && preg_match("/\D/", $cid = $_POST['category_id'])
	||
	isset($_GET['category_id']) && preg_match("/\D/", $cid = $_GET['category_id'])
	||
	isset($_GET['id']) && preg_match("/\D/", $id=$_GET['id'])
	||
	isset($_POST['plan']) && preg_match("/\D/", $planId=$_POST['plan'])
)
{
	$_GET['error'] = "404";
	include './error.php';
	die();
}

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$error = false;
$msg = array();

if (!$esynConfig->getConfig('suggest_category'))
{
	// Internal error message that tells that category suggestion is not allowed
	$_GET['error'] = "672";
	include IA_HOME . 'error.php';
	exit;
}

// category submission disabled for not authenticated
if (empty($esynAccountInfo) && !$esynConfig->getConfig('allow_categories_submit_guest'))
{
	$_SESSION['esyn_last_page'] = IA_URL . 'suggest-category.php';
	$_SESSION['esyn_msg'] = _t('login_create_account_submit_category');

	esynUtil::go2(IA_URL . 'login.php');
	exit;
}

/** defines tab name for this page **/
$currentTab = 'suggest-category';

require_once IA_INCLUDES . 'view.inc.php';

$eSyndiCat->factory("Category", "Layout");

if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
{
	$eSyndiCat->factory("Captcha");
}

$esynSmarty->caching = false;

if (isset($_GET['id']))
{
	$id = (int)$_GET['id'];
}

if (isset($_POST['category_id']))
{
	$cid = (int)$_POST['category_id'];
}

/** gets information about current category **/
if ($cid || (isset($_POST['category_title']) && 'ROOT' == $_POST['category_title']))
{
	$category_id = (int)$cid;
}
else
{
	$category_id = (int)$id;
}

/** gets information about current category **/
$category = $esynCategory->row("*", "id=".$category_id);

if (empty($category))
{
	$category = $esynCategory->row("*", "`parent_id` = '-1'");
}

$cid = $category['id'];
unset($id, $category_id);

$esynSmarty->assign('category', $category);

if ($category['locked'])
{
	$error = true;
	$msg[] = $esynI18N['error_category_locked'];
}

if (isset($_POST['add_category']))
{
	$eSyndiCat->startHook('suggestCategoryBeforeValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';
		$esynUtf8 = new esynUtf8();

		$esynUtf8->loadUTF8Core();
		$esynUtf8->loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$title = $_POST['title'];
	if ('' == trim($title))
	{
		$error = true;
		$msg[] = $esynI18N['title_empty'];
	}
	else
	{
		if (utf8_is_valid($title))
		{
			$temp['title'] = $title;
		}
		else
		{
			$temp['title'] = utf8_bad_replace($title);
		}

		$temp['path'] = esynUtil::getAlias($temp['title']);

		// check for duplicate categories
		if (!empty($temp['path']))
		{
			$temp['path'] = $esynCategory->getPath($category['path'], $temp['path']);
			if ($esynCategory->exists("`path` = '".esynSanitize::sql($temp['path'])."'"))
			{
				$error = true;
				$msg[] = $esynI18N['error_category_exists'];
			}
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['title_incorrect'];
		}
	}

	if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
	{
		if (!$esynCaptcha->validate())
		{
			$error = true;
			$msg[] = $esynI18N['error_captcha'];
		}
	}
	$_SESSION['pass'] = '';

	if (!$error)
	{
		$temp['parent_id'] = $cid;
		$temp['account_id'] = $esynAccountInfo ? $esynAccountInfo['id'] : 0;
		$temp['num_neighbours'] = '-1';

		$new_id = $esynCategory->insert($temp);
		$temp['id'] = $new_id;

		$msg[] = $esynI18N['category_submitted'];

		// recursively add records to non-tree structure table of categories
		$_s = $esynCategory->buildRelation($temp['id']);

		// something wrong (may be infinite recursive detected)
		if (!$_s)
		{
			trigger_error("Error in Category::buildRelation method possibly infinite recursive", E_USER_ERROR);
		}
	}
}

if (isset($title))
{
	$esynSmarty->assign('cat_title', $title);
}

$esynSmarty->assign('title', $esynI18N['suggest_category']);

// breadcrumb formation
esynBreadcrumb::replaceEnd($esynI18N['suggest_category']);
if ($category)
{
	esynBreadcrumb::addArray(esynUtil::getBreadcrumb($cid));
}

$esynSmarty->display('suggest-category.tpl');
