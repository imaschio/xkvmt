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

define('IA_REALM', "calendar");

include_once(IA_INCLUDES.'view.inc.php');

$eSyndiCat->factory("Layout");

$eSyndiCat->loadPluginClass("Calendar", "calendar", "esyn");
$esynCalendar = new esynCalendar();

$params = array();

$params['year'] = esynSanitize::sql($_GET['year']);
$params['month'] = esynSanitize::sql($_GET['month']);
$params['day'] = esynSanitize::sql($_GET['day']);

$num_listings = $esynConfig->getConfig('num_get_listings');

$page = 1;

$page	= !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$page	= ($page < 1) ? 1 : $page;
$start	= ($page - 1) * $num_listings;

if (!empty($params['day']))
{
	$listings_by_date = $esynCalendar->getListingByDate($params['year'], $params['month'], $params['day'], $start, $num_listings);
}
else
{
	$listings_by_date = $esynCalendar->getActiveDaysOnMonth($params['year'], $params['month'], $start, $num_listings);
}

$total_listings = $esynCalendar->foundRows();
$title = $esynI18N['calendar'];

if (!empty($params['year']) && !empty($params['month']) && !empty($params['day']))
{
	$title = $esynI18N['listings_posted_on'].' '.$params['day'].' '.$params['month'].' '.$params['year'];
}

$counts = count($listings_by_date);

// defines page title
$esynSmarty->assign('title', strip_tags($title));

$url = 'date/' . $params['year'] . "/" . $params['month'] ."/". (!empty($params['day']) ? $params['day'] . "/" : '') . "page{page}.html";

$esynSmarty->assignByRef('listingsbydate', $listings_by_date);
$esynSmarty->assignByRef('counts', $counts);
$esynSmarty->assignByRef('total_listings', $total_listings);
$esynSmarty->assignByRef('url', $url);

esynBreadcrumb::add($esynI18N['calendar']);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');