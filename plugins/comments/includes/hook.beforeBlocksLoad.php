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

global $eSyndiCat, $esynSmarty, $esynConfig, $esynI18N, $esynAccountInfo;

$item['id'] = isset($_GET['id']) && !empty($_GET['id']) ? intval($_GET['id']) : false;

if (isset($_GET['plugin']))
{
	if ('premium_articles' == $_GET['plugin'])
	{
		$item['item'] = 'articles';
	}
	else
	{
		$item['item'] = $_GET['plugin'];
	}
}
elseif (IA_REALM == 'view_listing')
{
	$item['item'] = 'listings';
}

if ($item['id'] && isset($item['item']))
{
	$eSyndiCat->setTable("comments");
	$comments = $eSyndiCat->all("*", "`item_id` = :id AND `status` = 'active' AND `item` = :item", array('id' => $item['id'], 'item' => $item['item']));
	$eSyndiCat->resetTable();

	if ($esynConfig->getConfig('comments_'.$item['item'].'_rating'))
	{
		require_once(IA_HOME . 'plugins' . IA_DS . 'comments' . IA_DS . 'includes' . IA_DS . 'classes' . IA_DS . 'esynRating.php');
		$esynRating = new esynRating();

		$comment_rating = $esynRating->getRating($item['id'], $item['item']);
		$comment_rating['voted'] = false;

		if ($esynRating->isVoted(esynUtil::getIpAddress(), $item['id'], $item['item']))
		{
			$comment_rating['voted'] = true;

			$comment_rating['html'] = number_format($comment_rating['rating'], 2);
			$comment_rating['html'] .= '&nbsp;/&nbsp;';
			$comment_rating['html'] .= $esynConfig->getConfig('comments_'.$item['item'].'_rating_block_max');
			$comment_rating['html'] .= '&nbsp;(';
			$comment_rating['html'] .= $comment_rating['num_votes'];
			$comment_rating['html'] .= '&nbsp;';
			$comment_rating['html'] .= $comment_rating['num_votes'] > 1 ? $esynI18N['votes_cast'] : $esynI18N['vote_cast'];
			$comment_rating['html'] .= ')&nbsp;';
			$comment_rating['html'] .= '<span style="color: green;">';
			$comment_rating['html'] .= $esynI18N['thanks_for_voting'];
			$comment_rating['html'] .= '</span>';
		}
		$esynSmarty->assign('comment_rating', $comment_rating);
	}
	$esynSmarty->assign('comments', $comments);
	$esynSmarty->assign('item', $item);
	$esynSmarty->assign('total_comments', count($comments));
}