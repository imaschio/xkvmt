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

// init esynLayout class
$eSyndiCat->factory('Layout');

$eSyndiCat->loadClass('Smarty');

$eSyndiCat->startHook('phpFrontViewAfterSmartyLoad');

// preview
if (isset($_GET['preview']) || isset($_SESSION['preview']))
{
	$tmpl = isset($_GET['preview']) ? $_GET['preview'] : $_SESSION['preview'];

	if (!file_exists(IA_TEMPLATES . $tmpl))
	{
		$tmpl = $eSyndiCat->mConfig['tmpl'];
	}
	$template = $_SESSION['preview'] = $tmpl;
	$esynConfig->setConfig('tmpl', $template);
}
else
{
	$template = $eSyndiCat->mConfig['tmpl'];
}
// init esynSmarty class
$esynSmarty = new esynSmarty($template, $eSyndiCat->mConfig);
$esynSmarty->mHooks = $eSyndiCat->mHooks;

// breadcrumb
$eSyndiCat->loadClass('Breadcrumb');

$eSyndiCat->createJsCache();

// global arrays used in the script
$esynSmarty->assignByRef('config', $eSyndiCat->mConfig);
$esynSmarty->assignByRef('esynAccountInfo', $esynAccountInfo);
$esynSmarty->assign('lang', $esynI18N);

$esynSmarty->registerPlugin('function', 'print_category_url', array($esynLayout, "printCategoryUrl"));
$esynSmarty->registerPlugin('function', 'print_account_url', array($esynLayout, "printAccountUrl"));

$templs = IA_URL . 'templates/' . $esynConfig->getConfig('tmpl') .'/';
$esynSmarty->assign('templates', $templs);
$esynSmarty->assign('img', $templs . 'img/');

// check if language switching is enabled
if ($esynConfig->getConfig('language_switch'))
{
	// 604800 is one week in seconds
	$languages = $eSyndiCat->mCacher->get("languages", 604800, true);

	if (!$languages)
	{
		$eSyndiCat->setTable("language");
		$languages = $eSyndiCat->keyvalue("`code`,`lang`", "1 GROUP BY `code`");
		$eSyndiCat->resetTable();
		$eSyndiCat->mCacher->write("languages", $languages);
	}

	if (count($languages) == 1)
	{
		$esynConfig->setConfig("language_switch", 0);
	}
	else
	{
		$esynSmarty->assign('languages', $languages);
	}

	// define language
	if (!empty($_GET['language']))
	{
		if (!empty($_COOKIE['language']))
		{
			setcookie("language", $_COOKIE['language'], $_SERVER['REQUEST_TIME'] - 3600);
			$get_language = $_COOKIE['language'];
		}
		if (!empty($_GET['language']))
		{
			setcookie("language", $_GET['language'], 0);
			$get_language = $_GET['language'];
		}

		if ($get_language)
		{
			setlocale(LC_ALL, $get_language . '_' . strtoupper($get_language));
		}
	}
}

// category id
$category_id = isset($category) && !empty($category) ? $category['id'] : 0;

// get pages
$eSyndiCat->factory('Page', 'Listing', 'Category');

$block_where = '';

$eSyndiCat->startHook('beforeBlocksLoad');

// get all blocks array
$sql = "SELECT DISTINCT `blocks`.* ";
$sql .= "FROM `{$eSyndiCat->mPrefix}blocks` `blocks` ";
$sql .= "LEFT JOIN `{$eSyndiCat->mPrefix}block_show` `block_show` ";
$sql .= "ON `blocks`.`id` = `block_show`.`block_id` ";
$sql .= "WHERE `blocks`.`status` = 'active' ";
$sql .= !$esynAccountInfo ? "AND `blocks`.`name` != 'account' " : '';
$sql .= "AND (`blocks`.`sticky` = '1' OR `block_show`.`page` = '" . IA_REALM . "' ";
$sql .= "OR REPLACE(`block_show`.`page`, 'index_browse|', '') = '{$category_id}' ";
$sql .= isset($page['name']) ? "OR `block_show`.`page` = '{$page['name']}') AND " : ') AND ';
$sql .= $eSyndiCat->mPlugins ? "`blocks`.`plugin` IN('', '" . join("','", array_keys($eSyndiCat->mPlugins)) . "') " : "`blocks`.`plugin` = '' ";

// uses from hook, e.g. to make exceptions
$sql .= $block_where;

$sql .= "ORDER BY `blocks`.`position`, `blocks`.`order`";
$all_blocks = $eSyndiCat->getAll($sql);

// build menu tree
function buildTree(array &$elements, $parentName = 'node')
{
	$branch = array();
	foreach ($elements as $k => $element)
	{
		if ($element['parent_name'] == $parentName)
		{
			$page = $element;

			$children = buildTree($elements, $element['page_name']);
			if ($children)
			{
				$page['children'] = $children;
			}
			$branch[] = $page;
			unset($page);
		}
	}
	return $branch;
}

// process blocks to display
$blocks = array();
if ($all_blocks)
{
	foreach($all_blocks as $key => $block)
	{
		if ('0' == $block['multi_language'])
		{
			$eSyndiCat->setTable("language");

			$block['title'] = $eSyndiCat->one("`value`", "`key` = 'block_title_blc{$block['id']}' AND `code` = '" . IA_LANGUAGE . "'");
			$block['contents'] = $eSyndiCat->one("`value`", "`key` = 'block_content_blc{$block['id']}' AND `code` = '" . IA_LANGUAGE . "'");

			$eSyndiCat->resetTable();
		}

		if ('menu' == $block['type'])
		{
			$sql = "SELECT `bp`.*, `p`.*, `c`.`title`, `c`.`path` "
				 . "FROM `{$eSyndiCat->mPrefix}block_pages` `bp` "
				 . "LEFT JOIN `{$eSyndiCat->mPrefix}pages` `p` "
				 . "ON `bp`.`page_name` = `p`.`name` "
				 . "LEFT JOIN `{$eSyndiCat->mPrefix}categories` `c` "
				 . "ON `c`.`id` = REPLACE(`bp`.`page_name`, 'index_browse|', '') "
				 . "WHERE `bp`.`block_name` = '{$block['name']}' "
				 . "ORDER BY `bp`.`id`";

			$pages = $eSyndiCat->getAll($sql);
			$pages = $esynPage->preparePages($pages, $category_id);
			$block['contents'] = buildTree($pages);
		}

		$blocks[$block['position']][] = $block;
	}
}
$esynSmarty->assignByRef('blocks', $blocks);

// get actions
$sql = "SELECT DISTINCT `actions`.`name`, `actions`.* FROM `{$eSyndiCat->mPrefix}actions` `actions` ";
$sql .= "LEFT JOIN `{$eSyndiCat->mPrefix}action_show` `action_show` ";
$sql .= "ON `actions`.`name` = `action_show`.`action_name` ";
$sql .= "WHERE `action_show`.`page` = '" . IA_REALM . "' ";
if (!$esynConfig->getConfig('broken_listings_report'))
{
	$sql .= "AND `actions`.`name` != 'report' ";
}
$sql .= "ORDER BY `actions`.`order`";
$esyndicat_actions = $eSyndiCat->getAssoc($sql);
$esynSmarty->assignByRef('esyndicat_actions', $esyndicat_actions);

// get sponsored listings
$esynSmarty->assign('featured_listings', $esynListing->getFeatured($category_id, 0, $esynConfig->getConfig('num_featured_display')));
$esynSmarty->assign('partner_listings', $esynListing->getPartner($category_id, 0, $esynConfig->getConfig('num_partner_display')));
if ($esynConfig->getConfig('sponsored_listings'))
{
	$esynSmarty->assign('sponsored_listings', $esynListing->getSponsored($category_id, 0, $esynConfig->getConfig('num_sponsored_display')));
}

// define visual mode
if ($_SESSION['frontendManageMode'] && $_SESSION['admin_name'])
{
	esynMessages::setMessage('youre_in_manage_mode', esynMessages::SYSTEM);
	$esynSmarty->assign('manageMode', true);
}

// define preview page
if (isset($_GET['preview']) || $_SESSION['preview'])
{
	esynMessages::setMessage('youre_in_preview_mode', esynMessages::SYSTEM);
}

// process system messages
$messages = esynMessages::getMessages();
if ($messages)
{
	$esynSmarty->assignByRef('messages', $messages);
	$esynSmarty->assignByRef('msg', $messages['msg']);
	$esynSmarty->assignByRef('error', $messages['error']);
	$esynSmarty->assignByRef('alert', $messages['alert']);
}
else
{
	$esynSmarty->assignByRef('msg', $msg);
	$esynSmarty->assignByRef('error', $error);
	$esynSmarty->assignByRef('alert', $alert);
}

$eSyndiCat->startHook('bootstrap');

// get field name to use instead of thumbnail
$instead_thumbnail = $esynListing->getThumbnail();
$esynSmarty->assign('instead_thumbnail', $instead_thumbnail['name']);
$esynSmarty->assign('instead_thumbnail_info', $instead_thumbnail);

// get top level categories
$top_level_categories = $eSyndiCat->mCacher->get("categoriesByParent_0", 86400, true);
if (!$top_level_categories)
{
	$top_level_categories = $esynCategory->getAllByParent(0, $esynConfig->getConfig('subcats_display'));
	$eSyndiCat->mCacher->write("categoriesByParent_0", $top_level_categories);
}
$esynSmarty->assign('top_level_categories', $top_level_categories);

// alphabetic search
$search_alphas = array_merge(array('0-9'), range('A', 'Z'));
if (isset($_GET['alpha']) && in_array($_GET['alpha'], $search_alphas))
{
	$listing_alpha = esynSanitize::sql($_GET['alpha']);
	$_GET['what'] = 'alphaSearch';
}
$esynSmarty->assign('search_alphas', $search_alphas);

// get visual options
$eSyndiCat->setTable('listing_visual_options');
$visual_options = $eSyndiCat->keyvalue("`name`, `value`", "`show` = 1");
$eSyndiCat->resetTable();
$esynSmarty->assign('visual_options', $visual_options);
