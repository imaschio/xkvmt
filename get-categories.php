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

if (!isset($_GET['id']) || preg_match("/\D/", $_GET['id']))
{
	header("HTTP/1.1 404 Not found");
	echo 'Powered By eSyndicat';
	die();
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory('Category');

$catId = (int)$_GET['id'];

if (isset($_GET['type']) && 'simple dropdown' == $_GET['type'])
{
	$out = '';
	$iter = '';

	getSimpleDropDown(-1, $out, $iter);

	echo $out;

	exit;
}
else
{
	$out = getTree($catId);

	echo esynUtil::jsonEncode($out);
}

if (empty($out))
{
	$out[] = 'null';
}

function getSimpleDropDown($aCategory, &$tree, &$iter)
{
	global $esynCategory, $esynConfig;

	$categories = $esynCategory->getAllByParent($aCategory);

	foreach($categories as $key => $category)
	{
		$subcategories = $esynCategory->getAllByParent($category['id'], $esynConfig->getConfig('subcats_display'), true, false);

		$tree .= "<option value=\"{$category['id']}\" class=\"{$category['title']}\">";

		if ($category['level'] >= 1)
		{
			$div = '&#x251C;';

			$div = ($iter == $esynCategory->one("count(*)", "`status`='active'")) ? '&#x2514;' : $div;

			for($j = 0; $j < $category['level']; $j++)
			{
				$div .= '&ndash;';
			}
		}
		else
		{
			$div = $iter ? '&#x251C;' : '&#x250C;';
			$div = ($iter == $esynCategory->one("count(*)") - 1) ? '&#x2514;' : $div;
		}

		if ($subcategories)
		{
			$tree .= $div.$category['title'];
		}
		else
		{
			$tree .= $div.$category['title'];
		}
		$tree .= "</option>";

		$iter++;
		$div = '';

		if ($subcategories)
		{
			getSimpleDropDown($category['id'], $tree, $iter);
		}

	}
}

function getTree($catId)
{
	global $esynCategory, $esynConfig;

	$out = array();

	$categories = $esynCategory->getAllByParent($catId, $esynConfig->getConfig('subcats_display'), true, false);

	if ($categories)
	{
		foreach($categories as $key => $category)
		{
			$out[$key]['id'] = $category['id'];
			$out[$key]['title'] = esynSanitize::html($category['title']);
			$out[$key]['crossed'] = (1 == $category['crossed']) ? true : false;
			$out[$key]['locked'] = (1 == $category['locked']) ? true : false;

			$category_exist = $esynCategory->exists("`parent_id` = '{$category['id']}' AND `status` = 'active'");

			$esynCategory->setTable('crossed');
			$crossed_exist = $esynCategory->exists("`category_id` = '{$category['id']}'");
			$esynCategory->resetTable();

			$out[$key]['sub'] = ($category_exist || $crossed_exist) ? true : false;
		}
	}

	return $out;
}
