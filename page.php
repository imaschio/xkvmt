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

// if empty or contains disallowed characters (NOTE: comma, and dot characters are allowed)
if (empty($_GET['name']) || strlen($_GET['name']) != strcspn($_GET['name'], " \"\\?*:/@|'<>%!#$%^&*()+[]{}"))
{
	$_GET['error'] = "404";
	require './error.php';
	exit;
}

define('IA_REALM', $_GET['name']);

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory('Page', 'Layout');

$page_status = 'active';
$msg = '';

// the page preview mode
// get only draft pages
if (isset($_GET['page_preview']))
{
	$page_status = 'draft';
	$msg[] = _t('preview_page_notification');
}

/** defines page information **/
$where = "(`name` = :name OR `custom_url` = :custom_url) AND `status` = '{$page_status}'";
$page = $esynPage->row("*", $where, array('name' => $_GET['name'], 'custom_url' => $_GET['name']));

if (empty($page))
{
	$_GET['error'] = "404";
	require './error.php';
	exit;
}

if (isset($page['unique_tpl']))
{
	esynUtil::go2($page['name']);
}

require_once IA_INCLUDES . 'view.inc.php';

$esynSmarty->caching = false;

$esynSmarty->assignByRef('page', $page);
$esynSmarty->assignByRef('msg', $msg);

$esynSmarty->assign('alert', true);

$eSyndiCat->setTable('config');
$default_language = $eSyndiCat->one('`value`',"`name` = 'lang'");
$eSyndiCat->resetTable();

$eSyndiCat->setTable('language');
$jt_where = "`category`='page' AND `key`='page_{DATA_REPLACE}_{$page['name']}' AND `code`='";

$page_title_check = $eSyndiCat->one('value', str_replace('{DATA_REPLACE}', 'title', $jt_where).IA_LANGUAGE."'");
$page_title = $page_title_check ? $page_title_check : $eSyndiCat->one('`value`',str_replace('{DATA_REPLACE}','title',$jt_where).$default_language."'");
$esynSmarty->assign('title', esynSanitize::html(strip_tags($page_title)));

$page_content_check = $eSyndiCat->one('`value`',str_replace('{DATA_REPLACE}','content',$jt_where).IA_LANGUAGE."'");
$page_content = $page_content_check ? $page_content_check : $eSyndiCat->one('`value`',str_replace('{DATA_REPLACE}','content',$jt_where).$default_language."'");
$esynSmarty->assign('content', $page_content);

$eSyndiCat->resetTable();

$esynSmarty->assign('description', $page['meta_description']);
$esynSmarty->assign('keywords', $page['meta_keywords']);

// breadcrumb formation
esynBreadcrumb::add(esynSanitize::html(strip_tags($page_title)));

$esynSmarty->display('page.tpl');
