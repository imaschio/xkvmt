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

define('IA_REALM', "spider");

$esynAdmin->loadClass("JSON");
$json = new Services_JSON();

$googleAPI = $esynConfig->getConfig('spider_google_api');
$bingAPI = $esynConfig->getConfig('spider_bing_api');

$start = 0;
// functions
/**
* Prints dropdown list with categories
*
* @param int $aCategory category id
* @param str $tree html source
* @param int $iter iteration number
*
*/
function print_categories_combo($aCategory, &$tree, &$iter)
{
	global $esynCategory;

	$div = '';
	$categories = $esynCategory->getAllByParent($aCategory, FALSE, 0, FALSE);

	foreach($categories as $key => $category)
	{
		$subcategories = $esynCategory->getAllByParent($category['id'], FALSE, 0, FALSE);

		if (isset($_POST['category_id']) && !empty($_POST['category_id']) && $category['id'] == $_POST['category_id']
			|| isset($_GET['id']) && !empty($_GET['id']) && $category['id'] == (int)$_GET['id'])
		{
			$selected = ' selected="selected"';
		}else{
			$selected = '';
		}
		$tree .= "<option value=\"{$category['id']}\"{$selected}>";
		if ($category['level'] >= 1)
		{

			$div = ($iter == $esynCategory->one("count(*)", "`status`='active'")) ? '' : $div;

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
			$tree .= $div.esynSanitize::html($category['title']);
		}
		else
		{
			$tree .= $div.esynSanitize::html($category['title']);
		}
		$tree .= "</option>";

		$iter++;
		$div = '';

		if ($subcategories)
		{
			print_categories_combo($category['id'], $tree, $iter);
		}

	}
}

function get_search_result($aParam)
{
	global $json,$esynConfig,$count,$googleAPI,$bingAPI;

	$count = intval($aParam['count']);
	$start = $aParam['start'];

	if ('google' == $aParam['search'])
	{
		$start = $start * 8;
		$aParam['keywords'] = urlencode($aParam['keywords']);

		$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&key=$googleAPI&userip=".$_SERVER['REMOTE_ADDR']."&q={$aParam['keywords']}&rsz=large&hl=en&start=$start";

		$content = esynUtil::getPageContent($url);
		$content = $json->decode($content);
	}
	/*
	elseif ('yahoo' == $aParam['search'])
	{
		$aParam['keywords'] = urlencode($aParam['keywords']);
		$appId = $esynConfig->getConfig('spider_yahoo_api');

		$url = "http://search.yahooapis.com/WebSearchService/V1/webSearch?appid=$bingAPI&query={$aParam['keywords']}&output=json&start=".intval($aParam['start']);

		$content = esynUtil::getPageContent($url);
		$content = $json->decode($content);
	}
	elseif ('bing' == $aParam['search'])
	{
		$start = $start * $count;
		$aParam['keywords'] = urlencode($aParam['keywords']);
		$appId = $esynConfig->getConfig('spider_bing_api');

		$url = "http://api.bing.net/json.aspx?AppId=$bingAPI&Version=2.2&Market=en-US&Query={$aParam['keywords']}&Sources=web&Web.Count=$count&Web.Offset=$start&JsonType=raw";

		$content = esynUtil::getPageContent($url);
		$content = $json->decode($content);
	}
	*/
	return $content;
}

function get_google_result($aContent)
{
	$out = array();

	$results = $aContent->responseData->results;
	if (is_array($results) && !empty($results))
	{
		foreach($results as $key => $result)
		{
			$out[$key]['url'] = isset($result->unescapedUrl) ? strip_tags($result->unescapedUrl) : '';
			$out[$key]['title'] = isset($result->title) ? strip_tags($result->title) : '';
			$out[$key]['description'] = isset($result->content) ? strip_tags($result->content) : '';
		}
		$out['start'] = $aContent->responseData->cursor->currentPageIndex;
		$out['num_results'] = $aContent->responseData->cursor->resultCount;
		$out['all_pages'] = count($aContent->responseData->cursor->pages);
	}

	return $out;
}

/*
function get_yahoo_result($aContent)
{
	$out = array();

	$results = $aContent->responseData->results;
	if (is_array($results) && !empty($results))
	{
		foreach($aContent->responseData->results as $key => $result)
		{
			$out[$key]['url'] = strip_tags($result->unescapedUrl);
			$out[$key]['title'] = strip_tags($result->title);
			$out[$key]['description'] = strip_tags($result->content);
		}
		$out['start'] = $aContent->responseData->cursor->currentPageIndex;
		$out['num_results'] = $aContent->responseData->cursor->resultCount;
		$out['all_pages'] = count($aContent->responseData->cursor->pages);
	}

	return $out;
}

function get_bing_result($aContent)
{
	global $count;
	$out = array();

	$results = $aContent->SearchResponse->Web->Results;
	if (is_array($results) && !empty($results))
	{
		foreach($results as $key => $result)
		{
			$out[$key]['url'] = isset($result->Url) ? strip_tags($result->Url) : '';
			$out[$key]['title'] = isset($result->Title) ? strip_tags($result->Title) : '';
			$out[$key]['description'] = isset($result->Description) ? strip_tags($result->Description) : '';
		}
		$num_results = count($results);

		$out['num_results'] = $aContent->SearchResponse->Web->Total;
		$out['all_pages'] = round($out['num_results'] / $count);
		$out['start'] = $aContent->SearchResponse->Web->Offset / $count;
		if ($aContent->SearchResponse->Web->Offset == 1000)
		{
			$out['all_pages'] = $out['start'] - 1;
		}
	}

	return $out;
}
*/

function run_parse($aParam)
{
	$content = get_search_result($aParam);
	//$content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));

	if ('google' == $aParam['search'])
	{
		return get_google_result($content);
	}
	/*
	elseif ('yahoo' == $aParam['search'])
	{
		return get_yahoo_result($content);
	}
	elseif ('bing' == $aParam['search'])
	{
		return get_bing_result($content);
	}
	*/
}
// end functions

$esynAdmin->factory("Category", "Listing");

$statuses = array('approval' => $esynI18N['approval'], 'banned' => $esynI18N['banned'], 'suspended' => $esynI18N['suspended'], 'active' => $esynI18N['active']);
$error	= false;
$msg	= '';

if (isset($_GET['action']) && 'add' == $_GET['action'])
{
	$links = array();
	$status = (isset($_POST['status']) && array_key_exists($_POST['status'], $statuses)) ? $_POST['status'] : 'approval';
	if (isset($_POST['index']))
	{
		foreach($_POST['index'] as $key => $value)
		{
			if ('on' == $value)
			{
				$links[$key]['url'] = $_POST['urls'][$key];
				$links[$key]['domain'] = esynUtil::getDomain($_POST['urls'][$key]);
				$links[$key]['title'] = esynSanitize::sql($_POST['titles'][$key]);
				$links[$key]['description'] = esynSanitize::sql($_POST['descriptions'][$key]);
				$links[$key]['pagerank'] = $esynConfig->getConfig('pagerank') ? PageRank::getPageRank($_POST['urls'][$key]) : -1;
				$links[$key]['category_id'] = isset($_POST['one_import']) ? $_POST['one_category'] : $_POST['categories'][$key];
				$links[$key]['status'] = $status;
				$links[$key]['email'] = false;
				$links[$key]['listing_header'] = esynUtil::getListingHeader($_POST['urls'][$key]);
			}
		}
	}

	if ($links)
	{
		foreach($links as $link)
		{
			$esynListing->insert($link);
		}
		esynMessages::setMessage($esynI18N['listings_added']);
		esynUtil::reload();
	}
}

$gBc[1]['title'] = $esynI18N['manage_spider'];
$gBc[1]['url'] = '';
$gTitle = $gBc[1]['title'];

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['action']) && 'get' == $_GET['action'] && !empty($_POST))
{
	$param['keywords']	= isset($_POST['keywords']) && !empty($_POST['keywords']) ? $_POST['keywords'] : '';
	$param['count']	    = isset($_POST['count']) && !empty($_POST['count']) ? $_POST['count'] : '';
	$param['start']		= isset($_POST['start']) ? intval($_POST['start']) : '';
	//$param['in']		= isset($_POST['keywordsin']) && !empty($_POST['keywordsin']) ? $_POST['keywordsin'] : '';
	$param['search']	= isset($_POST['engine']) && !empty($_POST['engine']) ? $_POST['engine'] : '';

	$result = run_parse($param);

	if (!empty($result))
	{
		print_categories_combo(-1, $categories, $iter);
	}
	if (!isset($categories))
	{
		$categories = '';
	}
	$esynSmarty->assign('categories', $categories);
	$esynSmarty->assign('result', $result);
}

if (ini_get('allow_url_fopen') || extension_loaded('curl'))
{
	if (empty($googleAPI))
	{
		esynMessages::setMessage($esynI18N['google_api_key_missed'], 'system');
	}

	/*
	if (empty($bingAPI))
	{
		$esyndicat_messages[] = array(
			'type'	=> 'alert',
			'msg'	=> $esynI18N['bing_api_key_missed']
		);
	}
	*/

	$esynSmarty->assign('error', false);
	$esynSmarty->assign('statuses', $statuses);
	$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');
}
else
{
	esynMessages::setMessage($esynI18N['not_available_spider'], 'system');

	$esynSmarty->assign('error', true);
	$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');
}