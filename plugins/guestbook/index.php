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

define('IA_REALM', "guestbook");
define('IA_CURRENT_PLUGIN_URL', 'controller.php?plugin='.IA_CURRENT_PLUGIN);

$limit = $esynConfig->getConfig('gb_messages_per_page');

if (isset($_POST['action']))
{
	if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
	{
		$eSyndiCat->factory("Captcha");
	}

	$error		= false;
	$msg		= array();
	$message	= array();
	
	if ('add' == $_POST['action'])
	{
		if (!defined('IA_NOUTF'))
		{
			require_once(IA_CLASSES.'esynUtf8.php');

			esynUtf8::loadUTF8Core();
			esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
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

		// checking author
		if (isset($_POST['author']) && !empty($_POST['author']))
		{
			$message['author_name'] = $_POST['author'];
			
			/** check for author name **/
			if (!$message['author_name'])
			{
				$error = true;
				$msg[] = $esynI18N['error_gb_author'];
			}		
			elseif (!utf8_is_valid($message['author_name']))
			{
				$message['author_name'] = utf8_bad_replace($message['author_name']);
			}
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['error_gb_author'];
		}

		// checking email
		if (isset($_POST['email']) && !empty($_POST['email']))
		{
			$message['email'] = $_POST['email'];
			
			/** check for author email **/
			if (!esynValidator::isEmail($message['email']))
			{
				$error = true;
				$msg[] = $esynI18N['error_gb_email'];
			}
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['error_gb_email'];
		}
		
		// checking url
		if (isset($_POST['url']) && !empty($_POST['url']))
		{
			$message['author_url'] = $_POST['url'];
			if (!esynValidator::isUrl($message['author_url']))
			{
				$error = true;
				$msg[] = $esynI18N['error_url'];
			}
		}

		// checking body
		$message['body'] = $_POST['body'];
		
		if (!utf8_is_valid($message['body']))
		{
			$message['body'] = utf8_bad_replace($message['body']);
		}
		
		if (utf8_is_ascii($message['body']))
		{
			$len = strlen($message['body']);
		}
		else
		{
			$len = utf8_strlen($message['body']);
		}
		
		if (empty($message['body']))
		{
			$error = true;
			$msg[] = $esynI18N['error_gb'];
		}
		else
		{
			require_once(IA_INCLUDES.'safehtml/safehtml.php');
			$safehtml = new safehtml();
			$message['body'] = $safehtml->parse($message['body']);
		}

		if (!$error)
		{
			if (!empty($esynAccountInfo['id']) && ctype_digit($esynAccountInfo['id']))
			{
				$message['account_id'] = (int)$esynAccountInfo['id'];
			}

			$message['sess_id'] = session_id();
			$message['ip_address'] = esynUtil::getIpAddress();
			$message['status'] = $esynConfig->getConfig('gb_auto_approval') ? 'active' : 'approval';

			$eSyndiCat->setTable("guestbook");
			$id = $eSyndiCat->insert($message, array("date" => "NOW()"));
			
			if ($esynConfig->getConfig('gb_auto_approval'))
			{
				$sql = "SELECT t1.*, IF (t1.`account_id` > 0, t2.`username`, t1.`author_name`) author 
					FROM `".$eSyndiCat->mPrefix."guestbook` t1 
						LEFT JOIN `".$eSyndiCat->mPrefix."accounts` t2 ON (t1.`account_id` = t2.`id`) 
					WHERE t1.`status` = 'active' 
					ORDER BY  t1.`date` DESC"
					. ($limit ? " LIMIT 0, $limit" : '');
				$out['gb'] = $eSyndiCat->getAll($sql);
			}
			$eSyndiCat->resetTable();

			$esynI18N['message_added'] .= (!$esynConfig->getConfig('gb_auto_approval')) ? ' '.$esynI18N['message_approval'] : '';

			$msg[] = $esynI18N['message_added'];
		}
	}
	
	$out['error'] = $error;
	$out['msg'] = $msg;

	echo esynUtil::jsonEncode($out);
	exit;
}

$eSyndiCat->factory("Layout");

$sess_id = ($_COOKIE['PHPSESSID'] && !$esynAccountInfo) ? $_COOKIE['PHPSESSID'] : '';

require_once IA_INCLUDES . 'view.inc.php';

$eSyndiCat->setTable("guestbook");
$total_messages = $eSyndiCat->one("count(id) n","`status`='active'");	

$esynSmarty->assign('total_messages', $total_messages);
$eSyndiCat->resetTable();

$page = isset($_GET['page']) ? $_GET['page'] : 1;
if ($page > $total_messages/$limit && $page < 0 || !is_numeric($page))
{
	$page = 1;
}
$start = ($page-1) * $limit;

$sql = "SELECT t1.*, IF (t1.`account_id` > 0, t2.`username`, t1.`author_name`) author 
	FROM `".$eSyndiCat->mPrefix."guestbook` t1 
		LEFT JOIN `".$eSyndiCat->mPrefix."accounts` t2 ON (t1.`account_id` = t2.`id`) 
	WHERE t1.`status` = 'active' 
	ORDER BY  t1.`date` DESC"
	. ($limit ? " LIMIT $start, $limit" : '');
$messages = $eSyndiCat->getAll($sql);

$esynSmarty->assign('messages', $messages);

$url = IA_URL.'mod/guestbook/?page={page}';

$esynSmarty->assign('url', $url);

$esynSmarty->assign('title', $esynI18N['guestbook']);

esynBreadcrumb::add($esynI18N['guestbook']);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');