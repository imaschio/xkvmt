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

define("IA_REALM", "tell_friend");

$eSyndiCat->factory("Layout", "Captcha");

require_once(IA_INCLUDES.'view.inc.php');

$error = false;
$msg = array();
$temp = array();

if (isset($_POST['tell']))
{
	require_once(IA_CLASSES.'esynUtf8.php');

	esynUtf8::loadUTF8Core();
	esynUtf8::loadUTF8Util('ascii', 'validation');

	if (!utf8_is_valid($_POST['fullname']))
	{
		$_POST['fullname'] = utf8_bad_replace($_POST['fullname']);
		trigger_error("Bad UTF-8 detected (replacing with '?') in tell-friend.php - fullname field", E_USER_NOTICE);
	}

	if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
	{
		if(!$esynCaptcha->validate())
		{
			$error = true;
			$msg[] = $esynI18N['error_captcha'];
		}
	}

	$_POST['fullname'] = preg_replace("#\s+#"," ", $_POST['fullname']);

	if (!utf8_is_valid($_POST['fullname2']))
	{
		$_POST['fullname2'] = utf8_bad_replace($_POST['fullname2']);
		trigger_error("Bad UTF-8 detected (replacing with '?') in tell-friend.php - fullname2 field", E_USER_NOTICE);
	}

	$_POST['fullname2'] = preg_replace("#\s+#"," ", $_POST['fullname2']);

	if (empty($_POST['fullname']))
	{
		$error = true;
		$msg[] = $esynI18N['error_contact_fullname'];
	}

	$temp['fullname'] = $_POST['fullname'];

	/** check emails **/
	if (empty($_POST['email']) || empty($_POST['email2']) || !esynValidator::isEmail($_POST['email']) || !esynValidator::isEmail($_POST['email2']))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}

	$temp['email']	= $_POST['email'];
	$temp['email2'] = $_POST['email2'];

	if (empty($_POST['fullname2']))
	{
		$error = true;
		$msg[] = $esynI18N['error_contact_fullname'];
	}

	$temp['fullname2'] = $_POST['fullname2'];

	if (!utf8_is_valid($_POST['body']))
	{
		$_POST['body'] = utf8_bad_replace($_POST['body']);
		trigger_error("Bad UTF-8 detected (replacing with '?') in tell-friend.php - body field", E_USER_NOTICE);
	}

	if (empty($_POST['body']))
	{
		$error = true;
		$msg[] = $esynI18N['error_contact_body'];
	}

	$message = htmlentities($_POST['body'], ENT_QUOTES);

	if (!$error)
	{
		$mail = $eSyndiCat->mMailer;

		$mail->From = $esynConfig->getConfig('site_email');
		$mail->FromName = $temp['fullname'];

		$replace = array(
			'name' => $temp['fullname'],
			'sender_email' => $temp['email'],
			'friends_name' => $temp['fullname2'],
			'message' => $message,
		);

		if ($esynConfig->getConfig('tell_friend_include_admin') == 1)
		{
			$replace['tf_from'] = $temp['email'];
			$replace['tf_to'] = $temp['email2'];

			$mail->add_notif('tell_friend');
		}

		$mail->add_replace($replace);
		$mail->AddAddress($temp['email2']);
		$mail->Send('tell_friend');

		$msg[] = $esynI18N['friend_told'];
		unset($_POST);
	}
}

/** defines page title **/
$esynSmarty->assign('title', $esynI18N['page_title_tell_friend']);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['page_title_tell_friend']);

$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'tell-friend.tpl');