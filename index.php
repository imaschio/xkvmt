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

if (!file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'config.inc.php'))
{
	header('Location: install/');
	die();
}

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory('Category');

if (isset($_SERVER['HTTP_X_REWRITE_URL']))
{
	$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
}

$path = '';
if (isset($_GET['category']))
{
	$path = ('0' == $_GET['category'] && isset($_GET['page'])) ? '' : $_GET['category'];
}

if (!empty($path) && $esynConfig->getConfig('use_html_path') && (!stristr($_SERVER['REQUEST_URI'], '.html')))
{
	$_GET['error'] = "404";
	include IA_HOME . 'error.php';
	exit;
}

// get current category
$category = array();
if ($esynCategory->validPath($path))
{
	$category = $esynCategory->row("*", "`path` = :path AND `status` = 'active'", array('path' => $path));
}
unset($_GET['category']);

// by this time category might be active BUT one of it's parent categories might be approval
if ($category)
{
	// define tab name for this page
	$GLOBALS['currentTab'] = 0 == $category['id'] ? 'home' : 'home' . $category['id'];

	$eSyndiCat->setTable("flat_structure");
	$parents = $eSyndiCat->onefield("`parent_id`", "`category_id` = :id", array('id' => $category['id']));
	$eSyndiCat->resetTable();

	if ($parents)
	{
		// $parents array contains itself also (as a parent to itself) so it must be more than 1 elements
		$check_parents = $parents;
		array_shift($check_parents);

		$check_parents = implode("','", $check_parents);

		if ($esynCategory->exists("`id` IN('" . $check_parents . "') AND status <> 'active'"))
		{
			// see below
			$category = false;
		}
	}
	else
	{
		$parents = array();
	}
}

// no such category OR category is approval
if (empty($category))
{
	$eSyndiCat->factory("Page");

	$where = "(`name` = :name OR `custom_url` = :custom_url) AND `status` = 'active'";

	if ($esynPage->exists($where, array('name' => $path, 'custom_url' => $path)))
	{
		$_GET['name'] = $path;
		include IA_HOME . 'page.php';
		exit;
	}

	$_GET['error'] = "404";
	include IA_HOME . 'error.php';
	exit;
}

$confirm_parents = implode("','", $parents);

// category confirmation
if (isset($_POST['confirm_answer_yes']))
{
	$confirm_id = (int)$_POST['category_id'];
	$parent_confirm = $esynCategory->row('*', "`id` IN ('" . implode("','", $parents) . "') AND `confirmation` = '1'");

	$confirm_ids = array();
	if (isset($_COOKIE['confirm_ids']) && !empty($_COOKIE['confirm_ids']))
	{
		$confirm_ids = explode(',', $_COOKIE['confirm_ids']);
	}
	$confirm_ids[] = $parent_confirm['id'];

	setcookie('confirm_ids', implode(',', $confirm_ids), time() + 3600, '/');
	esynUtil::go2(IA_URL . $category['path'] . '/');
}

$confirm_category = $esynCategory->row('*', "`id` IN ('" . implode("','", $parents) . "') AND `confirmation` = '1'");
if ($confirm_category || $category['confirmation'])
{
	$confirm_ids = array();
	$confirm_id = $confirm_category ? $confirm_category['id'] : $category['id'];
	if (isset($_COOKIE['confirm_ids']) && !empty($_COOKIE['confirm_ids']))
	{
		$confirm_ids = explode(',', $_COOKIE['confirm_ids']);
	}

	if (in_array($confirm_id, $confirm_ids))
	{
		$category['confirmation'] = 0;
	}
	else
	{
		$category['confirmation'] = 1;
		$category['confirmation_text'] = $confirm_category['confirmation_text'];
	}
}

if (-1 != $category['parent_id'])
{
	define('IA_REALM', "index_browse");
}
else
{
	define('IA_REALM', "index");
}

// gets current page and defines start position
$page = empty($_GET['page']) ? 0 : (int)$_GET['page'];
$page = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $esynConfig->getConfig('num_index_listings');

$id = $category['id'];

$cat_tpl = $category['unique_tpl'] ? $id : '';
$render = "index" . $cat_tpl . ".tpl";

$rootNoFollow = $esynCategory->one('`no_follow`', "`id` = '0'");
$fields = $join = array();
$where = '';

// Smarty and other View related things
include IA_INCLUDES . 'view.inc.php';

$esynSmarty->assign('start', $start);

if (isset($esynAccountInfo['id']))
{
	$esynSmarty->caching = false;
}
else
{
	// set cache time for this page
	$esynSmarty->cache_lifetime	= 3600;
}

// default sorting
if (isset($_GET['order']) && !empty($_GET['order']))
{
	$eSyndiCat->setTable('config');
	$sortings = explode("','", trim($eSyndiCat->one("`multiple_values`", "`name` = 'listings_sorting'"), "'"));
	$eSyndiCat->resetTable();

	$order = in_array($_GET['order'], $sortings) ? $_GET['order'] : 'alphabetic';
	setcookie("listings_sorting", $order, 0, '/');
}
else
{
	$order = !empty($_COOKIE['listings_sorting']) ? $_COOKIE['listings_sorting'] : $eSyndiCat->getConfig('listings_sorting');
}
$eSyndiCat->setConfig('listings_sorting', $order);

if (!empty($_GET['order_type']))
{
	$order_type = $_GET['order_type'];
	setcookie("listings_sorting_type", $_GET['order_type'], 0, '/');
}
else
{
	$order_type = $_COOKIE['listings_sorting_type'] ? $_COOKIE['listings_sorting_type'] : $eSyndiCat->getConfig('listings_sorting_type');
}
$eSyndiCat->setConfig('listings_sorting_type', $order_type);

$cache_id = IA_LANGUAGE . '|' . $id . '|' . $page . '|' . $eSyndiCat->getConfig('listings_sorting_type') . '|' . $eSyndiCat->getConfig('listings_sorting');

// if page cache time elapsed
if (!$esynSmarty->isCached($render, $cache_id))
{
	$esynSmarty->assignByRef('category', $category);

	if ('-1' != $category['parent_id'])
	{
		// print categories breadcrumb
		$categories_chain = esynUtil::getBreadcrumb($category['id']);
		esynBreadcrumb::addArray($categories_chain);

		$title = $esynLayout->getTitle($categories_chain, empty($category['page_title']) ? $category['title'] : $category['page_title'], $page);
		$header = $category['title'];

		$esynSmarty->assign('description', $category['meta_description']);
		$esynSmarty->assign('keywords',	$category['meta_keywords']);

		$categories = $eSyndiCat->mCacher->get("categoriesByParent_" . $id, 86400, true);
		if (!$categories)
		{
			$categories = $esynCategory->getAllByParent($id, $esynConfig->getConfig('subcats_display'));
			$eSyndiCat->mCacher->write("categoriesByParent_" . $id, $categories);
		}

		// clicks counter
		$ip = esynUtil::getIpAddress();
		if (!$esynCategory->checkClick($id, $ip))
		{
			$esynCategory->click($id, $ip);
		}
	}
	else
	{
		$esynSmarty->assign('description', $esynConfig->getConfig('site_description'));
		$esynSmarty->assign('keywords',	$esynConfig->getConfig('site_keywords'));
		$category['description'] = $esynConfig->getConfig('site_main_content');

		$header = $title = $esynConfig->getConfig('site');

		$categories = $top_level_categories;
	}
	$esynSmarty->assign('categories', $categories);

	$esynSmarty->assign('title', $title);
	$esynSmarty->assign('header', $header);

	$eSyndiCat->startHook('beforeGetListingList');

	/*
	$eSyndiCat->setTable('search_filters');
	$search_filter = $eSyndiCat->row('*', "`category_id` = {$category['id']} AND `status` = 'active'");
	if (!$search_filter)
	{
		$search_filter = $eSyndiCat->row('*', "FIND_IN_SET({$category['id']}, `childs`) AND `status` = 'active'");
	}
	$eSyndiCat->resetTable();

	if ($search_filter)
	{
		$search_filter['fields'] = esynUtil::jsonDecode($search_filter['fields']);
		$esynSmarty->assign('search_filter', $search_filter);
	}

	if ($search_filter)
	{
		$cookie = 'filterState_' . $category['id'];
		$filterState = esynUtil::jsonDecode($_COOKIE[$cookie]);

		if ($filterState)
		{
			foreach ($filterState as $field => $key)
			{
				if ($key == 'checked')
				{
					$where[] = "`t1`.`{$field}` != '' ";
				}
			}

			if ($where)
			{
				$where = implode("AND ", $where);
			}
		}

		$esynSmarty->assign('where', $where);
	}
	*/

	// get category listings
	$listings = $esynListing->getListingsByCategory($id, $start, $esynConfig->getConfig('num_index_listings'), $esynAccountInfo['id'], $sqlFoundRows = true, $sqlCountRows = false, $fields, $where, $join);

	// get total listings num
	$total_listings = $esynListing->foundRows();

	if (!$esynConfig->getConfig('show_children_listings'))
	{
		$p = '';

		if ($category['id'] != '0')
		{
			$p = $category['path'];
		}

		$c = count($listings);

		for ($i = 0; $i < $c; $i++)
		{
			$p2 = $p;

			if ($listings[$i]['crossed'])
			{
				$p2 = ($listings[$i]['category_id'] == 0) ? '' : $esynCategory->one("path", "`id`='" . $listings[$i]['category_id'] . "'") . "/";
			}

			$listings[$i]['path'] = $p2;
			$listings[$i]['category_title'] = $esynCategory->one("title", "`id`='{$listings[$i]['category_id']}'");
			$listings[$i]['category_id'] = $category['id'];
		}
	}

	$eSyndiCat->startHook('afterGetListingList');

	$esynSmarty->assign('total_listings', $total_listings);
	$esynSmarty->assign('listings', $listings);

	if ($category['id'] > 0 && $esynConfig->getConfig('use_html_path'))
	{
		$url = IA_URL . $category['path'] . '_{page}.html';
	}
	elseif ($category['parent_id'] == '-1')
	{
		$url = IA_URL . 'index{page}.html';
	}
	else
	{
		$url = IA_URL . $category['path'] . '/index{page}.html';
	}
	$esynSmarty->assign('url', $url);

	// related categories
	if ($esynConfig->getConfig('related'))
	{
		$related_categories = $esynCategory->getRelated($id);
		$esynSmarty->assign('related_categories', $related_categories);
	}

	// get neighbour categories
	// if num_neighbours == 0 that means that user don't want to show neighbour categories
	// if -1 that means that user wants to show all neighbour categories
	if ($category['id'] > 0 && $esynConfig->getConfig('neighbour') && $category['num_neighbours'] != 0)
	{
		if ((int)$category['num_neighbours'] == -1)
		{
			// show all
			$category['num_neighbours'] = 0;
		}
		$neighbour_categories = $esynCategory->getNeighbours($id, $category['num_neighbours']);
		$esynSmarty->assign('neighbour_categories', $neighbour_categories);
	}
}

// number of listings for current category and subcategories
$esynSmarty->assign('num_listings', $category['num_all_listings']);

// number of subcategories for current category
$num_categories = $esynCategory->getNumSubcategories($id);
$esynSmarty->assign('num_categories', $num_categories);

// if unique template does not exist, then reset to default
if (!$esynSmarty->templateExists($render))
{
	$render = "index.tpl";
}

$esynSmarty->display($render, $cache_id);
