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

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header-lite.php';
require_once IA_INCLUDES . 'util.php';
require_once IA_CLASSES . 'esynUtf8.php';

$from	= isset($_GET['from']) && !empty($_GET['from']) ? $_GET['from'] : null;
$start  = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$limit	= isset($_GET['limit']) && !empty($_GET['limit']) ? (int)$_GET['limit'] : 10;
$out	= '';

if (null == $from)
{
	header("HTTP/1.1 404 Not found");
	die("404 Not found. Powered by eSyndicat");
}

$eSyndiCat->factory('Category', 'Listing', 'Layout');

$self = $_SERVER['SERVER_NAME'];
$self .= str_replace('&', '&amp;', $_SERVER['REQUEST_URI']);

$out .= '<?xml version="1.0" encoding="utf-8"?>';
$out .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
$out .= '<channel>';

$out .= '<atom:link href="http://'. $self .'" rel="self" type="application/rss+xml" />';

if ((is_array($from) && in_array('category', $from)) || ('category' == $from))
{
	$category_id = isset($_GET['id']) && !empty($_GET['id']) ? (int)$_GET['id'] : 0;

	$category = $esynCategory->row("*", "`id` = '{$category_id}'");

	$out .= '<title>' . esynSanitize::html($category['title']) . '</title>';
	$out .= '<description>' . esynSanitize::html(strip_tags($category['description'])) . '</description>';
	$out .= '<link>';
	$out .= $esynLayout->printCategoryUrl(array('cat' => $category));
	$out .= '</link>';

	// Get link for the selected category
	$listings = $esynListing->getListingsByCategory($category_id, $start, $limit, false, false, 0);

	if (!empty($listings))
	{
		foreach ($listings as $key => $item)
		{
			$item['path'] = $category['path'];
			$item['link'] = $esynLayout->printListingUrl(array('listing' => $item));

			$out .= create_rss_item($item);
		}
	}
}

if ((is_array($from) && in_array('new', $from)) || ('new' == $from))
{
	$out .= '<title>' . $esynI18N['new_listings'] . '</title>';
	$out .= '<description>' . $esynI18N['newly_added_listings'] . '</description>';
	$out .= '<link>' . IA_URL . 'new-listings.html';
	$out .= '</link>';

	$listings = $esynListing->getLatest($start = 0, $limit);

	if (!empty($listings))
	{
		foreach ($listings as $key => $item)
		{
			$item['link'] = $esynLayout->printListingUrl(array('listing' => $item));
			$out .= create_rss_item($item);
		}
	}
}

if ((is_array($from) && in_array('popular', $from)) || ('popular' == $from))
{
	$out .= '<title>' . $esynI18N['popular_listings'] . '</title>';
	$out .= '<description>' . $esynI18N['most_popular_listings'] . '</description>';
	$out .= '<link>' . IA_URL . 'popular-listings.html';
	$out .= '</link>';

	$listings = $esynListing->getPopular($start = 0, $limit);

	if (!empty($listings))
	{
		foreach ($listings as $key => $item)
		{
			$item['link'] = $esynLayout->printListingUrl(array('listing' => $item));
			$out .= create_rss_item($item);
		}
	}
}

if ((is_array($from) && in_array('top', $from)) || ('top' == $from))
{
	$out .= '<title>' . $esynI18N['top_listings'] . '</title>';
	$out .= '<description>' . $esynI18N['top_listings'] . '</description>';
	$out .= '<link>' . IA_URL . 'top-listings.html';
	$out .= '</link>';

	$listings = $esynListing->getTop($start = 0, $limit);

	if (!empty($listings))
	{
		foreach ($listings as $key => $item)
		{
			$item['link'] = $esynLayout->printListingUrl(array('listing' => $item));
			$out .= create_rss_item($item);
		}
	}
}

$eSyndiCat->startHook('feed');

function create_rss_item($item)
{
	global $esynLayout;

	$out = '<item>';
	$out .= '<title>' . esynSanitize::html($item['title']) . '</title>';
	$out .= '<link>' . $item['link'] . '</link>';
	$out .= '<guid>' . $item['link'] . '</guid>';
	$out .= '<description>' . esynSanitize::html($item['description']) . '</description>';
	$out .= '<pubDate> '. date("r", strtotime($item['date'])) . '</pubDate>';
	$out .= '</item>';

	return $out;
}

$out .= '</channel>';
$out .=  '</rss>';

header('Content-Type: text/xml');

echo $out;
die();
