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

define('IA_REALM', "tagcloud");

require_once IA_INCLUDES . 'view.inc.php';

$tags = $tag = false;

$eSyndiCat->loadPluginClass("Tags", "tagcloud", "esyn");
$esynTags = new esynTags();

if (count($vals) <= 1)
{
	$tags = $esynTags->top_tags($esynConfig->getConfig('tags_count_all'));
	shuffle($tags);

	$esynSmarty->assign('all_tags', $tags);

	esynBreadcrumb::add($esynI18N['page_title_tags']);
	$esynSmarty->assign('title', $esynI18N['page_title_tags']);
}
else
{
	$tag = html_entity_decode(urldecode($vals[1]));
	$page = empty($_GET['page']) || (int)$_GET['page'] < 1 ? 1 : (int)$_GET['page'];
	$start = ( $page - 1 ) * $esynConfig->getConfig('num_index_listings');
	
	$url = 'mod/tagcloud/' . urlencode($tag) . '&page={page}';

	$eSyndiCat->factory("Listing");
	$cause = "LEFT JOIN `{$eSyndiCat->mPrefix}tags` as `tags` ON `tags`.`id_listing` = `listings`.`id` WHERE `listings`.`status` = 'active' AND `tags`.`tag` = '$tag' ";
	$listings = $esynTags->getByCriteria(0, 0, $cause);
	$listings_num = $eSyndiCat->foundRows();

	$eSyndiCat->startHook("phpFrontListingsAfterGetListings");
	
	$esynSmarty->assign('tag_listings', $listings);
	$esynSmarty->assign('tag', $tag);
	$esynSmarty->assign('tag_listings_num', $listings_num);
	$esynSmarty->assign('url', $url);

	esynBreadcrumb::add($esynI18N['page_title_tags'], 'mod/tagcloud/');
	esynBreadcrumb::add($tag);

	$esynSmarty->assign('title', $esynI18N['page_title_tags'] . ": " . $tag);
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'index.tpl');