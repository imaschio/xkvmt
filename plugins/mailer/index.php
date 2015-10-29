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

define('IA_REALM', 'subscribe');

require_once IA_INCLUDES . 'view.inc.php';

// set cache time for this page
$esynSmarty->caching = false;

$created = false;
$post_action = '';
define("EMAIL_NOTIFY", true);

if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
{
	$eSyndiCat->factory("Captcha");
}

if (!empty($_POST['subscribe']))
{
	$temp['realname'] = $_POST['realname'];
	$temp['email'] 	= $_POST['email'];

	if (!$temp['realname'])
	{
		$error = true;
		$msg[] = $esynI18N['error_realname_empty'];
	}

	$eSyndiCat->setTable('newsletter');
	if (!esynValidator::isEmail($temp['email']))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}
	elseif ($eSyndiCat->exists("`email`='".$temp['email']."'"))
	{
		$error = true;
		$msg[] = $esynI18N['newsletter_email_exists'];
	}
	else
	{
		$temp['email'] = $temp['email'];
	}
	$eSyndiCat->resetTable();

	if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
	{
		if (!$esynCaptcha->validate())
		{
			$error = true;
			$msg[] = $esynI18N['error_captcha'];
		}
	}

	if (!$error)
	{
		$mail = $eSyndiCat->mMailer;

		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		$pass = '';
		srand((double)microtime()*1000000);
		for ($i = 0; $i < 7; $i++)
		{
			$num = rand() % 33;
			$pass .= $chars[$num];
		}
		$sec_key = md5($pass);

		$sql = "INSERT INTO	`{$eSyndiCat->mPrefix}newsletter` ";
		$sql .= "(`realname`, `email`, `date_reg`, `sec_key`,`status`) ";
		$sql .= "VALUES('{$temp['realname']}','{$temp['email']}', NOW(), '{$sec_key}', 'unconfirmed')";
		$eSyndiCat->query($sql);

		$replace = array(
			"realname" => $temp['realname'],
			"key" => $sec_key,
		);
		$mail->add_replace($replace);

		$mail->AddAddress($temp['email']);
		$mail->Send('newsletter_confirm');
		$post_action = "subscribed";
	}
}
elseif (!empty($_POST['unsubscribe']))
{
	$temp['email'] = $_POST['email'];

	/** check email **/
	if (!esynValidator::isEmail($temp['email']))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}
	else
	{
		$temp['email'] = $temp['email'];
	}

	if (!$error)
	{
		$mail = $eSyndiCat->mMailer;

		$eSyndiCat->setTable("newsletter");
		$record_info = $eSyndiCat->row("*", "`email` = '".$temp['email']."'");
		$eSyndiCat->resetTable();

		$replace = array(
			"realname" => $record_info['realname'],
			"key" => $record_info['sec_key'],
		);

		$mail->add_replace($replace);
		$mail->AddAddress($temp['email']);
		$mail->Send('newsletter_unsubscribe');
		
		$post_action = "unsubscribed";
	}
}
elseif (isset($_GET['action']) && $_GET['action'] == "subscribe" && $_GET['key'])
{
	$eSyndiCat->setTable('newsletter');
	$result = $eSyndiCat->row("*", "`sec_key` = '".esynSanitize::sql($_GET['key'])."' AND `status` = 'unconfirmed'");
	$eSyndiCat->resetTable();

	if ($result)
	{
		$sql = "UPDATE `{$eSyndiCat->mPrefix}newsletter` ";
		$sql .= "SET ";
		$sql .= $esynConfig->getConfig('newsletter_autoapproval') ? " `status`='active' " : " `status`='approval' ";
		$sql .= "WHERE `sec_key` = '".esynSanitize::sql($_GET['key'])."' ";
	
		$final = $eSyndiCat->query($sql);
		if ($final)
			$post_action = "confirmed";
		else
			$post_action = "confirm_err";
	}
	else	
	{
		$post_action = "confirm_err";
	}
}
elseif (isset($_GET['action']) && $_GET['action'] == "unsubscribe" && $_GET['key'])
{
	$eSyndiCat->setTable('newsletter');
	$result = $eSyndiCat->row("*", "`sec_key` = '".esynSanitize::sql($_GET['key'])."'");
	$eSyndiCat->resetTable();
	
	if ($result)
	{
		$sql = "DELETE FROM `{$eSyndiCat->mPrefix}newsletter` WHERE `sec_key` = '".esynSanitize::sql($_GET['key'])."' LIMIT 1";
		$final = $eSyndiCat->query($sql);
		if ($final)
			$post_action = "unsubscribed_confirm";
		else
			$post_action = "unsubscribed_confirm_err";
	}
	else
	{
		$post_action = "unsubscribed_confirm_err";
	}
}

$esynSmarty->assignByRef('title', $esynI18N['newsletter']);

esynBreadcrumb::add($esynI18N['newsletter']);

$esynSmarty->assign('post_action', $post_action);

$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'newsletter.tpl');