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

define('IA_REALM', "search");

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

require_once IA_INCLUDES . 'view.inc.php';

$error = false;
$msg = '';

$showForm = true;
$searchType = isset($_GET['type']) && in_array($_GET['type'], array(1, 2, 3)) ? $_GET['type'] : 1;

$eSyndiCat->factory("Listing", "Category");

// gets current page and defines start position
$page = 0;
if (isset($_GET['page']))
{
	$page = (int)$_GET['page'];
}
elseif (isset($_POST['_settings']['page']))
{
	$page = (int)$_POST['_settings']['page'];
}

$page = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $esynConfig->getConfig('num_index_listings');

$esynSmarty->assign("page", $page);

$esynSmarty->assign("POST_json", "[]");

$esynSmarty->assign('title', $esynI18N['search']);

// get link fields for display
$fields = $esynListing->getFieldsForSearch();

// advanced search functionality
$field_groups = array();

$eSyndiCat->setTable('field_groups');
$groups = $eSyndiCat->onefield('`name`', "FIND_IN_SET('search', `pages`) ORDER by `order`");
$eSyndiCat->resetTable();

$searchFilters = array();
if (isset($_SESSION['searchFilters']) && $_SESSION['searchFilters'])
{
	$searchFilters = unserialize($_SESSION['searchFilters']);
}

$temp = $fileFields = array();
foreach ($fields as $key => $value)
{
	if (!in_array($value['type'], array('text', 'textarea', 'number'), true))
	{
		// set checked values
		$value['checked'] = array();
		if (isset($_POST[$value['name']]))
		{
			$searchFilters[$value['name']] = $value['checked'] = $_POST[$value['name']];
		}
		elseif (isset($searchFilters[$value['name']]))
		{
			$value['checked'] = $searchFilters[$value['name']];
		}

		if (in_array($value['type'], array('image', 'storage')))
		{
			$temp = $value;
			$temp['file_types'] = explode(',', $value['file_types']);

			// fill in search query
			if (isset($searchFilters[$value['name']]))
			{
				$fileFields[$value['name']] = $searchFilters[$value['name']]['has'];

				$plain .= ' AND `listings`.`' . $value['name'] . '` ' . ($searchFilters[$value['name']]['has'] == 'n' ? '=' : '!=') . " ''";
			}
		}
		else
		{
			if (in_array($value['type'], array('checkbox', 'combo', 'radio')))
			{
				// fill in search query
				if (isset($searchFilters[$value['name']]))
				{
					if ('checkbox' == $value['type'])
					{
						$values = implode(",", $searchFilters[$value['name']]);
						$plain .= ' AND FIND_IN_SET(`listings`.`' . $value['name'] . "`, '{$values}') ";
					}
					else
					{
						$values = implode("','", $searchFilters[$value['name']]);
						$plain .= ' AND `listings`.`' . $value['name'] . "` IN ('{$values}')";
					}
				}

				$fields[$key]['default'] = explode(',', $value['default']);
				$temp = $value;
			}
			$values = explode(',', $value['values']);

			$temp['values'] = array();
			foreach($values as $v)
			{
				$k = 'field_' . $value['name'] . '_' . $v;
				$temp['values'][$v] = $esynI18N[$k];
			}
		}
	}
	else
	{
		if ($value['type'] == 'number')
		{
			$eSyndiCat->setTable("language");
			$ranges = $eSyndiCat->keyvalue("`key`,`value`", "`key` LIKE 'field\_".$value['name']."\_range\_%'");
			$eSyndiCat->resetTable();

			$value['ranges'] = array();
			if (!empty($ranges))
			{
				foreach($ranges as $k2=>$v2)
				{
					$k2 = array_pop(explode("_", $k2));
					$value['ranges'][$k2] = $v2;
				}
			}

			ksort($value['ranges']);
		}

		$temp = $value;

		if ($value['type'] != 'number')
		{
			$textFields[$key] = $temp;
		}
	}

	if ($value['type'] != 'text' && $value['type'] != 'textarea')
	{
		if (!empty($temp['group']) && in_array($temp['group'], $groups))
		{
			$field_groups[$temp['group']][] = $temp;
		}
	}
}
if ($searchFilters)
{
	$_SESSION['searchFilters'] = serialize($searchFilters);
}

$esynSmarty->assign('fileFields', $fileFields);
$esynSmarty->assign('textFields', $textFields);
$esynSmarty->assign('field_groups', $field_groups);

$listings = array();
if (isset($_GET['what']) && !isset($_GET['adv']))
{
	$eSyndiCat->startHook('searchBeforeValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';
		$esynUtf8 = new esynUtf8();

		$esynUtf8->loadUTF8Core();
		$esynUtf8->loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$total_listings	= 0;
	$total_categories = 0;

	$what = trim($_GET['what']);

	if (!utf8_is_valid($what))
	{
		trigger_error("Bad characters in 'query' variable in searching", E_USER_NOTICE);
		$what = utf8_bad_replace($what);
	}
	$len = utf8_strlen($what);

	if (!$error)
	{
		// escape wild characters
		$what = str_replace(array("%","_"), array("\%","\_"), $what);
		$what = esynSanitize::sql($what);

		if (isset($plain))
		{
			$search_params['plain'] = $plain;
		}
		$search_params['what'] = $what;
		$search_params['category'] = (int)$_GET['search_category'];
		$search_params['location'] = (int)$_GET['search_location'];

		$cats_only = isset($_GET['cats']) ? $_GET['cats'] : false;

		// search listings box formation
		$nm = $esynConfig->getConfig('num_index_listings');

		$cause = $esynListing->getSearchCriterias($search_params, $searchType);
		$url = "search.php?what=" . urlencode($what) . "&type=" . $searchType . "&page={page}";

		$nmCat = $cats_only ? 0 : $esynConfig->getConfig('num_cats_for_search');
		$causeCat = $esynCategory->getCatSearchCriterias($what, $searchType);

		$replaceQuery = false;
		$replaceQueryCat = false;

		$eSyndiCat->startHook('injectSearchClause');

		// alphabetic search
		if ($listing_alpha)
		{
			if (ctype_digit($listing_alpha{0}))
			{
				$cause = "(`listings`.`title` REGEXP '^[0-9]') AND (`listings`.`status` = 'active') ";
			}
			else
			{
				$cause = "(`listings`.`title` LIKE '{$listing_alpha}%') AND (`listings`.`status` = 'active') ";
			}

			$_GET['what'] = '';
			$searchType .= "&alpha=" . $listing_alpha;
			$url = IA_URL . "alpha/{$listing_alpha}/index{page}.html";

			$showForm = false;
			$esynSmarty->assign('header', $esynI18N['listings'] . ' : ' . $listing_alpha);
			$esynSmarty->assign('title', $esynI18N['listings'] . ' : ' . $listing_alpha);
		}

		if (!$replaceQueryCat && (!isset($_GET['search_category']) || empty($_GET['search_category'])))
		{
			$categories = $esynCategory->getCatByCriteria(0, $nmCat, $causeCat, true);
			$total_categories = $esynCategory->foundRows();
		}

		if (!$replaceQuery)
		{
			if (!$cats_only)
			{
				$listings = $esynListing->getByCriteria($start, $nm, $cause, true, $esynAccountInfo['id']);
				$total_listings	= $esynListing->foundRows();
			}
		}

		// get pages search results
		if ($what)
		{
			$eSyndiCat->factory('Page');

			$pages = $esynPage->getPagesForSearch($what);
			$esynSmarty->assign('pages', $pages);
		}

		$eSyndiCat->startHook('afterGetSearchResult');

		$esynSmarty->assign('categories', $categories);
		$esynSmarty->assign('listings', $listings);

		$esynSmarty->assign('url', $url);
	}

	$esynSmarty->assign('total_categories', $total_categories);
	$esynSmarty->assign('total_listings', $total_listings);
}

if (isset($_POST['searchquery']) || (isset($_GET['adv']) && isset($_GET['paging'])) || (isset($_GET['adv']) && isset($_POST['field_groups'])))
{
	$eSyndiCat->startHook('advSearchBeforeValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';
		$esynUtf8 = new esynUtf8();

		$esynUtf8->loadUTF8Core();
		$esynUtf8->loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	// user paging
	if (!isset($_POST['searchquery']) && isset($_GET['adv']) && !empty($_SESSION['lastSearchState']))
	{
		$_POST = $_SESSION['lastSearchState'];
	}
	// from 0 to 1
	$criteriasRate = 0;

	$what = isset($_POST['searchquery']) ? trim($_POST['searchquery']) : '';

	$textFields = array(); // $_POST['queryFilter'];

	if (!utf8_is_valid($what))
	{
		trigger_error("Bad characters in 'query' variable in searching", E_USER_NOTICE);
		$what = utf8_bad_replace($what);
	}

	$textQueryProvided = false;

	$list = array();
	$fulltext = array();
	foreach($fields as $f)
	{
		if ($f['searchable'] == 2)
		{
			$fulltext[] = $f['name'];
		}
		$list[] = $f['name'];
	}

	$like = array();

	$sqlQuery = array();

	$len = utf8_strlen($what);
	$match = '';

	if ($len > 2 && $len < 50)
	{
		$criteriasRate += 0.2;

		// escape wild characters
		$what	= esynSanitize::sql($what);

		$match = ' MATCH(';

		$temp = array();
		$tempCat = array();

		// textfields where to search
		if (isset($_POST['queryFilter']) && is_array($_POST['queryFilter']) && !empty($_POST['queryFilter']))
		{
			foreach($_POST['queryFilter'] as $qf)
			{
				/*if (in_array($qf, $fulltext))
				{
					$temp[] = $qf;
				}*/

				if (in_array($qf, $list))
				{
					$temp[] = $qf;
				}
			}
		}

		if (isset($_POST['queryFilterCat']) && is_array($_POST['queryFilterCat']) && !empty($_POST['queryFilterCat']))
		{
			foreach($_POST['queryFilterCat'] as $qf)
			{
				$list_cat = array('title', 'description', 'meta_description', 'meta_keywords');
				if (in_array($qf, $list_cat))
				{
					$tempCat[] = $qf;
				}
			}
		}

		if (empty($temp))
		{
			$_POST['cats_only'] = '1';
			$match .= "`t1`.`id`";
		}
		else
		{
			foreach($temp as $t)
			{
				$match .= "`t1`.`" . $t . "`,";
			}

			$match = rtrim($match, ",");
		}

		unset($temp);

		if (!isset($_POST['match']))
		{
			$_POST['match'] = 'any';
		}

		$against = '';

		switch($_POST['match'])
		{
			case "any":
				$words = explode(" ", $what);
				// remove duplicated words
				$words = array_flip(array_flip($words));
				$against = implode("* ", $words) . '*';
				$mode = "IN BOOLEAN MODE";
				$whereCat = '';
				if (!empty($tempCat))
				{
					foreach ($tempCat as $t)
					{
						foreach ($words as $word)
						{
							$temp_where[] = " `t44`.`$t` LIKE '%$word%'";
						}
					}
					$whereCat .= implode(" OR ", $temp_where);
				}

				break;
			case "all":
				$words = explode(" ", $what);
				$whereCat = '';
				if (!empty($tempCat))
				{
					foreach ($tempCat as $t)
					{
						foreach ($words as $word)
						{
							$temp_where[] = " `t44`.`$t` LIKE '%$word%'";
						}
						$temp[] = implode(" AND ", $temp_where);
						unset($temp_where);
					}
					$whereCat .= "(".implode(") OR (", $temp).")";
				}

				// remove duplicated words
				$words = array_flip(array_flip($words));
				foreach($words as $k => $i)
				{
					$i = trim($i);
					$words[$k] = "+".$i;
					if (strlen($i) < 3)
					{
						unset($words[$k]);
					}
					$against = implode(" ", $words);
					$mode = "IN BOOLEAN MODE";
				}
				break;
			case "exact":
			default:
				$whereCat = '';
				if (!empty($tempCat))
				{
					foreach ($tempCat as $t)
					{
						$temp_where[] = " `t44`.`$t` LIKE '%$what%'";
					}
					$whereCat .= implode(" OR ", $temp_where);
				}
				$against = "\"".$what."\"";
				$mode = "IN BOOLEAN MODE";
				break;
		}

		unset($tempCat);

		$match .= ") AGAINST('".$against."' ".$mode.") ";

		$sqlQuery[] = $match;

		$textQueryProvided = true;
	}
	else
	{
		// escape wild characters
		$what = esynSanitize::sql($what);
	}

	$nm = $esynConfig->getConfig('num_index_listings');
	$nmCat = isset($_POST['cats_only']) ? 0 : $esynConfig->getConfig('num_cats_for_search');
	$s = '';

	/*
	 *	t1 - is a 'listings' table (there are JOINs)
	 */
	foreach($_POST as $k => $f)
	{
		if (in_array($k, $list, true))
		{
			if (($fields[$k]['type'] == 'checkbox' && is_array($f) && !empty($f))
						||
				(($fields[$k]['type'] == 'combo' || $fields[$k]['type'] == 'radio') && $fields[$k]['show_as'] == 'checkbox'))
			{
				$s = "(";
				foreach($f as $x)
				{
					$s .= " FIND_IN_SET('".(int)$x."', t1.`".$k."`) OR ";
				}

				// remove last "OR "
				$s = substr($s, 0, -3);
				$s .= ")";
				$sqlQuery[] = $s;

				// lower rate as of 'OR' operator may return too match result set
				$criteriasRate += 0.075;
			}
			elseif ($fields[$k]['type'] == 'checkbox' && $fields[$k]['show_as'] == 'combo' && $f != '_doesnt_selected_')
			{
				$sqlQuery[] = " FIND_IN_SET('" . (int)$f . "', t1.`" . $k . "`) ";
			}
			elseif (($fields[$k]['show_as'] == 'combo') && is_scalar($f) && ($f !== '_doesnt_selected_'))
			{
				$criteriasRate += 0.1;
				// system stores numeric value and the title stored to the `language`
				$sqlQuery[] = "t1.`" . $k . "`='" . (int)$f . "'";
			}
			elseif ($fields[$k]['show_as'] == 'radio' && is_scalar($f))
			{
				$criteriasRate += 0.1;
				// system stores numeric value and the title stored to the `language`
				$sqlQuery[] = "t1.`" . $k . "`='" . (int)$f . "'";
			}
			elseif (($fields[$k]['type'] == 'storage' || $fields[$k]['type'] == 'image') && is_array($f))
			{
				if ($f['has'] == 'y')
				{
					$sqlQuery[] = "t1.`" . $k . "` <> ''";
				}
				else
				{
					$sqlQuery[] = "t1.`" . $k . "` = ''";
				}
				$criteriasRate += 0.075;
			}
		}
	}

	if (isset($_POST['_from']) && isset($_POST['_to']))
	{
		foreach($_POST['_from'] as $k => $v)
		{
			if ($fields[$k]['type'] == 'number' && in_array($k, $list, true))
			{
				if ((float)$v > 0)
				{
					// if both are set
					if (isset($_POST['_to'][$k]) && strlen($_POST['_to'][$k]) > 0)
					{
						$from = min((float)$v, (float)$_POST['_to'][$k]);
						$to 	= max((float)$v, (float)$_POST['_to'][$k]);
						if ($to == $from)
						{
							$s = "`" . $k . "` = " . $to;
						}
						else
						{
							$s = "`".$k."` BETWEEN '".$from."' AND '".$to."' ";
						}

						$criteriasRate += 0.1;
					}
					else // if only from is set
					{
						$s = "`".$k."` > '".(float)$v."'";

						$criteriasRate += 0.1;
					}
				}
				else
				{
					// if only "to" is set
					if (isset($_POST['_to'][$k]) && (float)$_POST['_to'][$k] > 0)
					{
						$to 	= (float)$_POST['_to'][$k];
						$s = "`".$k."` < '".(float)$to."'";

						$criteriasRate += 0.1;
					}
					else // none of them is set
					{
						$s = false;
					}
				}
			}

			if ($s)
			{
				$sqlQuery[] = "(".$s.")";
			}
		}
	}

	$cause = implode(" AND ", $sqlQuery);

	if (empty($cause))
	{
		$cause = ' 1 ';
	}

	if (!isset($_POST['_settings']['sort']) || $_POST['_settings']['sort'] == 'relevance')
	{
		$_POST['_settings']['sort'] = 'relevance';

		if ($textQueryProvided)
		{
			$sortBy = 'search_score';
			$sortCatBy = '`t44`.`path` ASC';
		}
		else
		{
			$sortBy = 't1.`date`';
			$sortCatBy = 't44.`id` ASC';
		}
	}else{
		$sortBy = 't1.`date`';
		$sortCatBy = 't44.`id` ASC';
	}

	if (false)//$criteriasRate < 0.2)
	{
		$error = true;
		$msg = $esynI18N['not_found_listings'];
	}

	$total_categories = 0;
	$total_listings = 0;
	// not enough search criterias
	if (!$error)
	{

		$replaceQuery = false;
		$replaceQueryCat = false;
		$eSyndiCat->startHook('injectAdvancedSearchClause');

		if (!$replaceQuery)
		{
			$listings = $esynListing->getAdvSearchListings((!empty($match) ? $match." as search_score, " : ''), $cause, $sortBy, $start, $nm);
		}

		if (!empty($listings))
		{
			$total_listings	= $esynListing->foundRows();
		}

		$categories = array();
		if (!$replaceQueryCat and !empty($whereCat))
		{
			$categories = $esynCategory->getAdvSearchCategories($whereCat, $sortCatBy, 0, $nmCat);
		}

		if (!empty($categories))
		{
			$total_categories = $esynCategory->foundRows();
		}

		$eSyndiCat->startHook('afterGetAdvSearchResult');
	}

	$esynSmarty->assignByRef('total_listings', $total_listings);
	$esynSmarty->assignByRef('listings', $listings);

	$esynSmarty->assignByRef('total_categories', $total_categories);
	$esynSmarty->assignByRef('categories', $categories);

	$what = str_replace(' ', '+', $what);

	$url = "search.php?paging&adv&page={page}";

	$esynSmarty->assignByRef('url', $url);

	$_SESSION['lastSearchState'] = $_POST;

	$esynSmarty->assign("POST_json", esynUtil::jsonEncode($_SESSION['lastSearchState']));
	$showForm = true;
}
elseif (isset($_SESSION['lastSearchState']))
{
	$esynSmarty->assign("POST_json", esynUtil::jsonEncode($_SESSION['lastSearchState']));
}
else
{
	$esynSmarty->assign("POST_json", '[]');
}

$eSyndiCat->factory('Layout');

// breadcrumb formation
esynBreadcrumb::add(isset($_GET['adv']) ? $esynI18N['advanced_search'] : $esynI18N['search']);
if ((isset($listings) || isset($categories)) && (!empty($listings) || !empty($categories)) && isset($_GET['adv']))
{
	esynBreadcrumb::add($esynI18N['search_criterias']);
}

$esynSmarty->assign('adv', isset($_GET['adv']));
$esynSmarty->assign('showForm', $showForm);
$esynSmarty->assign('showFilters', true);

$esynSmarty->display('search.tpl');
