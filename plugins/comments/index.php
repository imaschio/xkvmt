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

define('IA_REALM', "comments");

if (isset($_POST['action']))
{
	global $item;

	if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
	{
		$eSyndiCat->factory("Captcha");
	}

	if ('add' == $_POST['action'])
	{
		$error = false;
		$msg = array();
		$comment = array();

		if (!defined('IA_NOUTF'))
		{
			require_once IA_CLASSES . 'esynUtf8.php';

			esynUtf8::loadUTF8Core();
			esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
		}

		// checking author
		if (isset($_POST['author']) && !empty($_POST['author']))
		{
			$comment['author'] = strip_tags($_POST['author']);

			/** check for author name **/
			if (!$comment['author'])
			{
				$error = true;
				$msg[] = $esynI18N['error_comment_author'];
			}
			elseif (!utf8_is_valid($comment['author']))
			{
				$comment['author'] = utf8_bad_replace($comment['author']);
			}
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['error_comment_author'];
		}

		// checking email
		if (isset($_POST['email']) && !empty($_POST['email']))
		{
			$comment['email'] = $_POST['email'];

			/** check for author email **/
			if (!esynValidator::isEmail($comment['email']))
			{
				$error = true;
				$msg[] = $esynI18N['error_comment_email'];
			}
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['error_comment_email'];
		}

		// checking body
		$comment['body'] = $_POST['body'];

		if (!utf8_is_valid($comment['body']))
		{
			$comment['body'] = utf8_bad_replace($comment['body']);
		}

		if (utf8_is_ascii($comment['body']))
		{
			$len = strlen($comment['body']);
		}
		else
		{
			$len = utf8_strlen($comment['body']);
		}

		/** check for captcha **/
		if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
		{
			if (!$esynCaptcha->validate())
			{
				$error = true;
				$msg[] = $esynI18N['error_captcha'];
			}
		}

		if (!empty($_POST['item_id']) && ctype_digit($_POST['item_id']) && !empty($_POST['item_name']))
		{
			$comment['item_id'] = (int)$_POST['item_id'];
			$comment['item'] = esynSanitize::sql($_POST['item_name']);
		}

		if (empty($comment['body']))
		{
			$error = true;
			$msg[] = $esynI18N['error_comment'];
		}
		else
		{
			if ($esynConfig->getConfig('comments_'.$comment['item'].'_html'))
			{
				require_once(IA_INCLUDES.'safehtml/safehtml.php');
				$safehtml = new safehtml();
				$comment['body'] = $safehtml->parse($comment['body']);
			}
			else
			{
				$comment['body'] = strip_tags($comment['body']);
			}
		}

		if (!$error)
		{
			if (!empty($esynAccountInfo['id']) && ctype_digit($esynAccountInfo['id']))
			{
				$comment['account_id'] = (int)$esynAccountInfo['id'];
			}

			if (!empty($_POST['comment_rating']) && ctype_digit($_POST['comment_rating']))
			{
				$comment['rating'] = (int)$_POST['comment_rating'];
			}

			$comment['ip_address'] = esynUtil::getIpAddress();
			$comment['status'] = $esynConfig->getConfig('comments_'.$comment['item'].'_approval') ? 'active' : 'inactive';

			$eSyndiCat->setTable("comments");
			$id = $eSyndiCat->insert($comment, array("date" => "NOW()"));

			$out['comment'] = $eSyndiCat->row("*", "`id` = '{$id}'");
			$out['comment']['date'] = strftime($esynConfig->getConfig('date_format'), strtotime($out['comment']['date']));

			$eSyndiCat->resetTable();

			if (!$esynConfig->getConfig('comments_'.$comment['item'].'_approval'))
			{
				$eSyndiCat->mMailer->notifyAdmins('comment_added');
			}

			$esynI18N['comment_added'] .= !$esynConfig->getConfig('comments_'.$comment['item'].'_approval') ? ' '.$esynI18N['comment_waits_approve'] : '';

			$msg[] = $esynI18N['comment_added'];
		}
	}

	if ('vote' == $_POST['action'])
	{
		require_once(IA_HOME . 'plugins' . IA_DS . 'comments' . IA_DS . 'includes' . IA_DS . 'classes' . IA_DS . 'esynRating.php');

		$esynRating = new esynRating();
		$item['id'] = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
		$item['name'] = isset($_POST['item_name']) ? esynSanitize::sql($_POST['item_name']) : '';

		if (!$esynRating->isVoted(esynUtil::getIpAddress(), $item['id'], $item['name']))
		{
			$esynRating->ratingInsert($item['id'], esynSanitize::sql($_POST['comment_rating']), esynUtil::getIpAddress(), $item['name']);

			$comment_rating = $esynRating->getRating($item['id'], $item['name']);

			$comment_rating['html'] = number_format($comment_rating['rating'], 2);
			$comment_rating['html'] .= '&nbsp;/&nbsp;';
			$comment_rating['html'] .= $esynConfig->getConfig('comments_'.$item['name'].'_rating_block_max');
			$comment_rating['html'] .= '&nbsp;(';
			$comment_rating['html'] .= $comment_rating['num_votes'];
			$comment_rating['html'] .= '&nbsp;';
			$comment_rating['html'] .= $comment_rating['num_votes'] > 1 ? $esynI18N['votes_cast'] : $esynI18N['vote_cast'];
			$comment_rating['html'] .= ')&nbsp;';
			$comment_rating['html'] .= '<span style="color: green;">';
			$comment_rating['html'] .= $esynI18N['thanks_for_voting'];
			$comment_rating['html'] .= '</span>';

			echo esynUtil::jsonEncode($comment_rating);
			exit;
		}
		else
		{
			die(" ");
		}
	}

	$out['error'] = $error;
	$out['msg'] = $msg;

	echo esynUtil::jsonEncode($out);
	exit;
}