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

global $eSyndiCat, $esynSmarty, $esynConfig;

$eSyndiCat->setTable("comments");
$num_total_comments = $eSyndiCat->one("COUNT(*)", "`status` = 'active'");
$eSyndiCat->resetTable();
$esynSmarty->assign('num_total_comments', $num_total_comments);

$sql = "SELECT * FROM `{$eSyndiCat->mPrefix}comments` ";
$sql .= "WHERE `status`='active' ";
$sql .= "ORDER BY `date` DESC ";
$sql .= "LIMIT ".$esynConfig->getConfig('comments_num_latest_comments');
$latest_comments = $eSyndiCat->getAll($sql);

if (!empty($latest_comments))
{
	foreach ($latest_comments as $key => $comment)
	{
		$comment['item'] = empty($comment['item']) ? 'listings' : $comment['item'];

		if ('listings' == $comment['item'])
		{
			$eSyndiCat->factory("Listing");
			global $esynListing;
			$item = $esynListing->getListingById($comment['item_id']);
		}
		else
		{
			$eSyndiCat->setTable($comment['item']);
			$item = $eSyndiCat->row("*", "`id` = '{$comment['item_id']}' AND `status` = 'active'");
			$eSyndiCat->resetTable();
		}

		if ($item)
		{
			$latest_comments[$key]['_item'] = $item;
		}
		else
		{
			unset($latest_comments[$key]);
		}
	}
}
$esynSmarty->assign('latest_comments', $latest_comments);