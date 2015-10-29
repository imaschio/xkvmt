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

$id = !empty($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : false;
if ($id)
{
	define('IA_REALM', "view_news");
}
else
{
	define('IA_REALM', "news");
}

require_once IA_INCLUDES . 'view.inc.php';

$id = !empty($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : false;

if ($id)
{
	// get one news array by news id
	$eSyndiCat->setTable("news");
	$single_news = $eSyndiCat->row("*", "id = '{$id}'");
	$eSyndiCat->resetTable();

	$esynSmarty->assign('single_news', $single_news);

	esynBreadcrumb::add($esynI18N['news'], 'news.html');
	esynBreadcrumb::add($esynI18N['view_news']);

	$title = $single_news['title'];
}
else
{
	$num_news = (int)$esynConfig->getConfig('news_number');

	/** gets current page and defines start position **/
	$page = empty($_GET['page']) ? 0 : (int)$_GET['page'];
	$page = ($page < 1) ? 1 : $page;
	$start = ($page - 1) * $esynConfig->getConfig('news_number');

	$eSyndiCat->setTable("news");
	$total_news = $eSyndiCat->one("count(`id`)", "`status`='active' AND `lang` = '".IA_LANGUAGE."'");

	/** get news by status **/
	$all_news = $eSyndiCat->all('`id`,`title`,`date`,`body`,`alias`,`image`',"`status`='active' AND `lang` = '".IA_LANGUAGE."' ORDER BY `date` DESC", $values = array(), $start, $num_news);
	$eSyndiCat->resetTable();

	$esynSmarty->assignByRef('total_news', $total_news);
	$esynSmarty->assignByRef('all_news', $all_news);

	esynBreadcrumb::add($esynI18N['news']);

	$title = $esynI18N['news'];
}

$url = IA_URL . 'news{page}.html';
$esynSmarty->assign('url', $url);
$esynSmarty->assign('title', strip_tags($title));

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');