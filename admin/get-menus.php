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

if (!isset($_GET['node']))
{
	header("HTTP/1.1 404 Not found");
	die('Powered By eSyndiCat');
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

require_once '.' . DIRECTORY_SEPARATOR . 'header.php';

$esynAdmin->factory('Category');
$node = $_GET['node'];

function generate_categories($aCategs)
{
	global $esynCategory;

	foreach($aCategs as $key => $category)
	{
		$leaf = ($esynCategory->getNumSubcategories($category['id']) > 0) ? false : true;

		$nodes[$key]['text'] = esynSanitize::html($category['title']);
		$nodes[$key]['id'] = 'index_browse|' . $category['id'];
		$nodes[$key]['leaf'] = $leaf;
		$nodes[$key]['cls'] = 'folder';
		if (!$leaf)
		{
			$nodes[$key]['checked'] = false;
		}
	}
	return $nodes;
}

if ($node == 'items_root')
{
	$esynAdmin->setTable('pages');
	$pages = $esynAdmin->all('*', "`readonly` = '0' AND `status` = 'active'");
	$esynAdmin->resetTable();

	$tree_pages = array();
	$pages_group = array();
	$out = array();

	$esynAdmin->setTable('language');
	foreach ($pages as $key => $page)
	{
		if (!empty($page['unique_url']) && esynValidator::isUrl($page['unique_url']) && !stristr($page['unique_url'], IA_URL))
		{
			unset($pages[$key]);

			continue;
		}

		if (!isset($tree_pages[$page['group']]))
		{
			$tree_pages[$page['group']] = array(
				'text'		=> ucfirst($page['group']),
				'leaf'		=> (bool)false,
				'children'	=> array()
			);

			$pages_group[] = $page['group'];
		}

		$pages[$key]['title'] = $esynAdmin->one("`value`", "`key` = 'page_title_{$page['name']}' AND `code` = '" . IA_LANGUAGE . "'");

		$n = array(
			'id'	=> $page['name'],
			'text'	=> $pages[$key]['title'],
			'leaf'	=> (bool)true,
			'cls'	=> 'leaf'
		);

		$tree_pages[$page['group']]['children'][] = $n;
	}
	$esynAdmin->resetTable();

	foreach ($tree_pages as $page)
	{
		$pages_nodes[] = $page;
	}

	$nodes[] = array(
		'id'	=> 'pages',
		'text'	=> 'Pages',
		'children'	=> $pages_nodes
	);

	$categories = $esynCategory->getAllByParent($category_id);
	$cat_nodes = generate_categories($categories);

	$nodes[] = array(
		'id'	=> 'index_browse|0',
		'text'	=> 'Categories',
		'children'	=> $cat_nodes
	);
}

if (strstr($node, "|"))
{
	$params = explode('|', $node);
	$category_id = $params[1];

	$single = (isset($_GET['single']) && '1' == $_GET['single']) ? true : false;

	$nodes = array();

	$categories = $esynCategory->getAllByParent($category_id);

	if ($categories)
	{
		$nodes = generate_categories($categories);
	}
}

echo esynUtil::jsonEncode($nodes);
exit;
