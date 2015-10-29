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

define('IA_REALM', "dmozex");

$esynAdmin->factory("Category", "Listing");
$statuses = array('approval' => $esynI18N['approval'], 'banned' => $esynI18N['banned'], 'suspended' => $esynI18N['suspended'], 'active' => $esynI18N['active']);
$catStatuses = array('active' => $esynI18N['active'], 'approval' => $esynI18N['approval']);

function printMain ($data)
{
	$out = '';
	$out .= '<ul>';
	foreach ($data as $key => $value)
	{
		$out .= "<li><a href=\"javascript:void(0);\" onclick=\"goBrowser('{$value['path']}');\">{$value['title']}</a></li>";
	}
	$out .= '</ul>';
	return $out;
}

function printCategoriesBrowse ($data)
{
	$out = '';
	$out .= '<ul>';
	foreach ($data as $key => $value)
	{
		$out .= "<li><a href=\"javascript:void(0);\" onclick=\"goBrowser('{$value['path']}');\">{$value['title']}</a></li>";
	}
	$out .= '</ul>';
	return $out;
}

function printListings ($data)
{
	global $statuses, $esynI18N, $esynUtil;

	$out = '';
	$out .= "<form method=\"post\" action=\"controller.php?plugin=dmozex&amp;action=add\" id=\"listing-result\">";
	$out .= esynUtil::preventCsrf();
	$out .= "<table class=\"striped\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
	$out .= "<tr>";
	$out .= "<th colspan='4'><div style='background:none repeat scroll 0 0 #E9CFCF;border:1px solid #CCCCCC;float:left;height:15px;margin:10px;width:15px;'>&nbsp;</div><div style='margin:10px;'> - {$esynI18N['listing_exists']}</div></th>";
	$out .= "</tr>";
	$out .= "<tr>";
	$out .= "<th>&nbsp;</th>";
	$out .= "<th>URL</th>";
	$out .= "<th>Title</th>";
	$out .= "<th>Description</th>";
	$out .= "</tr>";

	foreach ($data as $key => $value)
	{
		$checked = 'checked="checked"';
		$style = '';

		if ($value['exists'])
		{
			$style = 'style="background-color:#E9CFCF;"';
			$checked = '';
		}

		$out .= "<tr $style>";
		$out .= "<td width=\"1%\" style=\"vertical-align: top;\"><input class=\"common\" type=\"checkbox\" name=\"index[$key]\" $checked /></td>";
		$out .= "<td width=\"19%\" style=\"vertical-align: top;\"><input type=\"text\" class=\"common\" name=\"urls[]\" value=\"" . esynSanitize::notags($value['url']) . "\" size=\"35\" /></td>";
		$out .= "<td width=\"20%\"><textarea class=\"common\" rows=\"5\" cols=\"5\" name=\"titles[]\">" . esynSanitize::notags($value['title']) . "</textarea></td>";
		$out .= "<td width=\"40%\"><textarea class=\"common\" rows=\"5\" cols=\"5\" name=\"descriptions[]\">" . esynSanitize::notags($value['description']) . "</textarea></td>";
		$out .= "</tr>";
	}

	$out .= "<tr>";
	$out .= "<td>";
	$out .= "<img src=\"" . IA_PLUGIN_TEMPLATE_URL . "img/arrow_ltr.png\" alt=\"" . $esynI18N['arrow'] . "\"/>";
	$out .= "</td>";
	$out .= "<td colspan=\"4\" style=\"vertical-align: center;\">";
	$out .= "<a href=\"javascript:checkAll('listing-result');\">" . $esynI18N['check_all'] . "</a>&nbsp;/&nbsp;";
	$out .= "<a href=\"javascript:uncheckAll('listing-result');\">" . $esynI18N['uncheck_all'] . "</a>";
	$out .= "</td>";
	$out .= "</tr>";
	$out .= "<tr>";
	$out .= "<td colspan=\"5\" style=\"vertical-align: center;\">";
	$out .= "<input type=\"hidden\" id=\"category_id\" name=\"category_id\" value=\"0\" />";
	$out .= "<div style=\"float:left;padding-right: 5px;\">" . $esynI18N['import_links_to'] . "</div>";
	$out .= "<div id=\"category_title\" style=\"float:left;padding-right: 5px;\">";
	$out .= "<a id=\"show_tree_listings\" href=\"#\" onclick=\"showTree(); return false;\"><b>" . $esynI18N['root'] . "</b></a></div>";
	$out .= "<div class=\"tree\" style=\"float:left;display:none;padding-right: 5px;padding-left: 5px;\"></div>";
	$out .= "<div style=\"float:left;\">" . $esynI18N['with_status'] . "&nbsp;</div>";
	$out .= "<select name=\"status\" class=\"common\">";

	foreach ($statuses as $key => $status)
	{
		$out .= "<option value=\"{$key}\">{$status}</option>";
	}

	$out .= "</select>";
	$out .= "</td>";
	$out .= "</tr>";
	$out .= "<tr>";
	$out .= "<td colspan=\"4\"><input class=\"common\" type=\"submit\" name=\"import\" value=\"" . $esynI18N['import_links'] . "\" /></td>";
	$out .= "</tr>";
	$out .= "</table>";
	$out .= "</form>";

	return $out;
}

function printCategories ($data)
{
	global $catStatuses, $esynI18N, $esynUtil;

	$out = '';
	$out .= "<form method=\"post\" action=\"controller.php?plugin=dmozex&amp;action=addcategories\" id=\"categories-result\">";
	$out .= esynUtil::preventCsrf();
	$out .= "<table class=\"striped\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";

	foreach ($data as $key => $value)
	{
		$out .= "<tr>";
		$out .= "<td width=\"1%\" style=\"vertical-align: top;\"><input class=\"common\" type=\"checkbox\" name=\"index[$key]\" checked /></td>";
		$out .= "<td width=\"30%\"><input class=\"common\" type=\"text\" name=\"titles[]\" value=\"" . esynSanitize::notags($value['title']) . "\"/></td>";
		$out .= "</tr>";
	}

	$out .= "<tr>";
	$out .= "<td>";
	$out .= "<img src=\"" . IA_PLUGIN_TEMPLATE_URL . "img/arrow_ltr.png\" alt=" . $esynI18N['arrow'] . "/>";
	$out .= "</td>";
	$out .= "<td colspan=\"2\" style=\"vertical-align: center;\">";
	$out .= "<a href=\"javascript:checkAll('categories-result');\">" . $esynI18N['check_all'] . "</a>&nbsp;/&nbsp;";
	$out .= "<a href=\"javascript:uncheckAll('categories-result');\">" . $esynI18N['uncheck_all'] . "</a>";
	$out .= "</td>";
	$out .= "</tr>";
	$out .= "<tr>";
	$out .= "<td colspan=\"5\" style=\"vertical-align: center;\">";
	$out .= "<input type=\"hidden\" id=\"parent_id\" name=\"parent_id\" value=\"0\" />";
	$out .= "<div style=\"float:left;padding-right: 5px;\">" . $esynI18N['import_cats_to'] . "</div>";
	$out .= "<div id=\"category_title1\" style=\"float:left;padding-right: 5px;\">";
	$out .= "<a href=\"#\" id=\"show_tree_cats\" onclick=\"showTree1(); return false;\"><b>" . $esynI18N['root'] . "</b></a></div>";
	$out .= "<div class=\"tree1\" style=\"float:left;display:none;padding-right: 5px;padding-left: 5px;\"></div>";
	$out .= "<div style=\"float:left;\">with status&nbsp;</div>";
	$out .= "<select name=\"status\" class=\"common\">";

	foreach ($catStatuses as $key => $status)
	{
		$out .= "<option value=\"{$key}\">{$status}</option>";
	}

	$out .= "</select>";
	$out .= "</td>";
	$out .= "</tr>";
	$out .= "<tr>";
	$out .= "<td colspan=\"4\"><input type=\"submit\" class=\"common\" name=\"import\" value=\"" . $esynI18N['import_cats'] . "\" /></td>";
	$out .= "</tr>";
	$out .= "</table>";
	$out .= "</form>";

	return $out;
}

function printBreadcrumb ($data)
{
	global $esynI18N;

	$out = '';
	$out = '<a href="javascript:void(0);" onclick="goBrowser(\'/\');">' . $esynI18N['dmoz'] . '</a>&nbsp;&#187;&nbsp;';

	foreach ($data as $key => $value)
	{
		if ('' != $value['path'])
		{
			$out .= "<a href=\"javascript:void(0);\" onclick=\"goBrowser('{$value['path']}');\">{$value['title']}</a>&nbsp;&#187;&nbsp;";
		}
		else
		{
			$out .= $value['title'];
		}
	}

	$out .= "<br /><br />";

	return $out;
}

// patterns
define("PTR_GET_MAIN", "/<span( class=\"catalog\")?><a href=\"(.*?)\">(.*?)<\/a><\/span>/smi");
//define("PTR_GET_CATEGORIES", "/<table border=0>(.*?)<\/table>/smi");
define("PTR_GET_CATEGORIES", "/<div class=\"dir-1 borN\">(.*?)<\/div>/smi");
define("PTR_GET_CATEGORIES_LIST", "/<a href=(\'|\")(.*?)(\'|\")>(.*?)<\/a>/smi");
define("PTR_GET_LISTINGS_ALL", "/<ul class=\"directory\-url\" style=\"margin\-left\:0\;\">(.*?)<\/ul>/smi");
define("PTR_GET_LISTINGS", "/<li(.*?)>(.*?)<a href=\"(http:\/\/.*?)\">(.*?)<\/a>(.*?)<\/li>/smi");
define("PTR_GET_BREADCRUMB", "/<ul class=\"navigate\">(.*?)<\/ul>/smi");

// ajax
$get_actions = array('getMain', 'getBrowser', 'getCategories', 'getListings');

if (isset($_GET['action']) && in_array($_GET['action'], $get_actions))
{
	$url = 'http://www.dmoz.org';
	$out = '';

	if ('getMain' == $_GET['action'])
	{
		$main = array();
		$_SESSION['dmoz'] = '/';
		$content = esynUtil::getPageContent($url);

		preg_match_all(PTR_GET_MAIN, $content, $mainCategories);

		foreach ($mainCategories[2] as $key => $mainCategory)
		{
			$main[$key]['title'] = strip_tags($mainCategories[3][$key]);
			$main[$key]['path'] = $mainCategory;
		}

		$out = printMain($main);
		$out .= '||';
		$out.= printCategories($main);

		echo $out;
		exit();
	}

	if ('getBrowser' == $_GET['action'])
	{
		$categoriesBrowse = array();
		$categories = array();
		$listings = array();
		$breadcrumb = array();

		if (! empty($_GET['category']))
		{
			$url .= $_GET['category'];
		}

		$_SESSION['dmoz'] = $_GET['category'];
		$content = esynUtil::getPageContent($url);

		if (strstr($content, "full-index.html"))
		{
			$content = esynUtil::getPageContent($url . "full-index.html");
		}

		preg_match_all(PTR_GET_CATEGORIES, $content, $matches);
		$matches0 = isset($matches[0][0]) && ! empty($matches[0][0]) ? $matches[0][0] : null;
		$matches1 = isset($matches[0][1]) && ! empty($matches[0][1]) ? $matches[0][1] : null;
		$matches2 = isset($matches[0][2]) && ! empty($matches[0][2]) ? $matches[0][2] : null;
		$matches3 = isset($matches[0][3]) && ! empty($matches[0][3]) ? $matches[0][3] : null;
		$categories = $matches0 . $matches1 . $matches2 . $matches3;
		preg_match_all(PTR_GET_CATEGORIES_LIST, $categories, $categories_list);

		foreach ($categories_list[2] as $key => $category)
		{
			$categoriesBrowse[$key]['title'] = strip_tags($categories_list[4][$key]);
			$categoriesBrowse[$key]['path'] = $category;
		}

		preg_match(PTR_GET_LISTINGS_ALL, $content, $matches);

		if (! empty($matches[1]))
		{
			preg_match_all(PTR_GET_LISTINGS, $matches[1], $listings_all);

			foreach ($listings_all[3] as $key => $listing)
			{
				$listings[$key]['exists'] = $esynListing->exists("`url` = '".esynSanitize::sql($listing)."'") ? true : false;
				$listings[$key]['url'] = $listing;
				$listings[$key]['title'] = trim($listings_all[4][$key]," -[]");
				$listings[$key]['description'] = trim(str_replace(array("\r\n", "\r", "\n"), "",$listings_all[5][$key]), " -\t[]");
			}
		}

		preg_match(PTR_GET_BREADCRUMB, $content, $breadcrumbHtml);

		if (! empty($breadcrumbHtml))
		{
			$elements = explode(':', $breadcrumbHtml[1]);

			if ($elements)
			{
				foreach ($elements as $key => $value)
				{
					preg_match(PTR_GET_CATEGORIES_LIST, $value, $breadEl);

					if (! empty($breadEl))
					{
						$breadcrumb[$key]['title'] = $breadEl[4];
						$breadcrumb[$key]['path'] = $breadEl[2];
					}
					else
					{
						$breadcrumb[$key]['title'] = trim(strip_tags($value));
						$breadcrumb[$key]['path'] = '';
					}
				}
			}
		}

		$out .= printBreadcrumb($breadcrumb);
		$out .= '||';
		$out .= (empty($categoriesBrowse)) ? 'null' : printCategoriesBrowse($categoriesBrowse);
		$out .= '||';
		$out .= (empty($categoriesBrowse)) ? 'null' : printCategories($categoriesBrowse);
		$out .= '||';
		$out .= (empty($listings)) ? 'null' : printListings($listings);

		echo $out;
		exit();
	}
	exit();
}

if (isset($_GET['action']) && 'add' == $_GET['action'])
{
	$links = array();
	$status = (isset($_POST['status']) && array_key_exists($_POST['status'],$statuses)) ? $_POST['status'] : 'approval';

	if (isset($_POST['index']))
	{
		foreach ($_POST['index'] as $key => $value)
		{
			if ('on' == $value)
			{
				$links[$key]['url'] = $_POST['urls'][$key];
				$links[$key]['domain'] = esynUtil::getDomain($_POST['urls'][$key]);
				$links[$key]['title'] = $_POST['titles'][$key];
				$links[$key]['description'] = $_POST['descriptions'][$key];
				$links[$key]['pagerank'] = $esynConfig->getConfig('pagerank') ? PageRank::getPageRank($_POST['urls'][$key]) : - 1;
				$links[$key]['category_id'] = (int)$_POST['category_id'];
				$links[$key]['status'] = $status;
				$links[$key]['email'] = false;
				$links[$key]['listing_header'] = esynUtil::getListingHeader($_POST['urls'][$key]);
			}
		}
	}

	if ($links)
	{
		foreach ($links as $link)
		{
			$esynListing->insert($link);
		}

		$esynCategory->adjustNumListings((int)$_POST['category_id']);
		esynMessages::setMessage($esynI18N['listings_added']);
		esynUtil::reload();
	}
}

if (isset($_GET['action']) && 'addcategories' == $_GET['action'])
{
	if (! defined('IA_NOUTF'))
	{
		require_once (IA_CLASSES . 'esynUtf8.php');
		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$_POST['parent_id'] = isset($_POST['parent_id']) && ! empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : false;

	if (isset($_POST['index']))
	{
		foreach ($_POST['index'] as $key => $value)
		{
			if ('on' == $value)
			{
				$parentPath = $esynCategory->one("`path`", "`id` = '{$_POST['parent_id']}'");
				$titlepath = $_POST['titles'][$key];

				if (!utf8_is_ascii($titlepath))
				{
					$titlepath = utf8_to_ascii($titlepath);
				}

				$titlepath = preg_replace("/[^a-z0-9_-]+/i", "-", $titlepath);
				$titlepath = trim($titlepath, "-");
				$path = $esynCategory->getPath($parentPath, $titlepath);

				if (! utf8_is_valid($_POST['titles'][$key]))
				{
					$_POST['titles'][$key] = utf8_bad_replace($_POST['titles'][$key]);
				}

				$_POST['titles'][$key] = $_POST['titles'][$key];
				$dmozCategories[$key]['title'] = $_POST['titles'][$key];
				$dmozCategories[$key]['path'] = $path;
				$dmozCategories[$key]['parent_id'] = (int) $_POST['parent_id'];
				$dmozCategories[$key]['num_neighbours'] = $esynConfig->getConfig('neighbour');
			}
		}
	}

	if (isset($dmozCategories) && is_array($dmozCategories))
	{
		foreach ($dmozCategories as $category)
		{
			$new_id = $esynCategory->insert($category);
			$esynCategory->buildRelation($new_id);
		}

		esynMessages::setMessage($esynI18N['category_added']);
		esynUtil::reload();
	}
}

$gBc[1]['title'] = $esynI18N['dmozex'];
$gBc[1]['url'] = '';
$gTitle = $gBc[1]['title'];

require_once (IA_ADMIN_HOME . 'view.php');

$esynSmarty->assign("dmoz_session", isset($_SESSION['dmoz']) ? $_SESSION['dmoz'] : "");
$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'index.tpl');