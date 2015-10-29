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

define("IA_REALM", "pagerank_listings");

$pr = $_GET['pr'];

if(empty($pr) && $pr != 0 )
{
	$_GET['error'] = "404";
	include("./error.php");
	exit;
}

$eSyndiCat->factory("Listing", "Layout");

require_once(IA_INCLUDES.'view.inc.php');

$id = !empty($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : false;

$num_index = $esynConfig->getConfig('num_index_listings');

// gets current page and defines start position
$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page < 1) ? 1 : $page;
$start = ($page - 1) * $num_index;

// gets number of all listings
$total_listings = $esynListing->one("count(*)", "`pagerank`='{$pr}' AND `status` = 'active'");
$esynSmarty->assign('total_listings', $total_listings);

// get listings by pagerank value
$sql = "SELECT t1.*, t9.`path`, t9.`title` `category_title`, t9.`path` `category_path`, ";
$sql .= $esynAccountInfo ? "IF (`fav_accounts_set` LIKE '%{$esynAccountInfo['id']},%', '1', '0') `favorite` " : "'0' `favorite` ";
$sql .= $esynAccountInfo ? ', IF((`t1`.`account_id` = 0) OR (`t1`.`account_id` = \'0\'), \'0\', \'1\') `account_id_edit` ' : ', \'0\' `account_id_edit` ';
$sql .= "FROM `{$eSyndiCat->mPrefix}listings` t1 ";
$sql .= "LEFT JOIN `{$eSyndiCat->mPrefix}categories` t9 ";
$sql .= "ON t1.`category_id` = t9.`id` ";
$sql .= "WHERE `pagerank` = '{$pr}' ";
$sql .= " AND t1.`status` = 'active' ";
$sql .= "ORDER BY `clicks` DESC, ";
$sql .= "`t1`.`sponsored` DESC, ";
$sql .= "`t1`.`featured` DESC, ";
$sql .= "`t1`.`partner` DESC ";
$sql .= $num_index ? "LIMIT {$start}, {$num_index}" : '';
$listings = $eSyndiCat->getAll($sql);

$eSyndiCat->startHook("phpFrontListingsAfterGetListings");

$esynSmarty->assign('listings', $listings);

// defines page title
$esynSmarty->assign('title', $esynI18N['pagerank_listings'].' '.$pr);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['pagerank_listings'] . ' ' . $pr);

$esynSmarty->assign('pr', $pr);

$url = "pagerank{$pr}-listings{page}.html";
$esynSmarty->assign('url', $url);

$view ='pagerank'.$pr;
$esynSmarty->assign('view', $view);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');