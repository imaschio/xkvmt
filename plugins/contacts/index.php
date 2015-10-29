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

if (isset($_GET['contact']) && $_GET['contact'] && 'us' != $_GET['contact'])
{
	('member' == $_GET['contact']) ? define('IA_REALM', "contact_member") : define('IA_REALM', "contact_listing");
}
else
{
	define('IA_REALM', "contact_us");
}

$eSyndiCat->factory("Listing", "Account", "Layout");

if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
{
	$eSyndiCat->factory("Captcha");
}

// requires common header file
require_once IA_INCLUDES . 'view.inc.php';

$error = false;
$msg = array();

if (isset($_GET['id']) && !empty($_GET['id']))
{
	$_GET['id'] = (int)$_GET['id'];

	if (isset($_GET['contact']) && 'member' == $_GET['contact'])
	{
		$account = $esynAccount->row("*", "`id` = :id", array('id' => $_GET['id']));
		$listing = $esynListing->getListingById((int)$_GET['lid']);
	}

	if (isset($_GET['contact']) && 'listing' == $_GET['contact'])
	{
		/** get current listing info **/
		$listing = $esynListing->getListingById((int)$_GET['id']);
	}
}

if (!empty($_POST['contact']))
{
	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$fullname = $_POST['fullname'];
	if (!utf8_is_valid($fullname))
	{
		$fullname = utf8_bad_replace($fullname);
		trigger_error("Bad UTF-8 detected (replacing with '?') in contacts page", E_USER_NOTICE);
	}

	if (utf8_is_ascii($fullname))
	{
		$fullname = preg_replace("#[^a-z0-9 \.]#i", "", substr($fullname, 0, 30));
	}
	else
	{
		// well..any characters allowed only whitespaces converted to `space`
		$fullname = preg_replace("#\s+#", " ", utf8_substr($fullname, 0, 30));
	}

	if (!$fullname)
	{
		$error = true;
		$msg[] = $esynI18N['error_contact_fullname'];
	}

	$email = $_POST['email'];

	if (!esynValidator::isEmail($email))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}

	$subject = $_POST['subject'];
	if (!utf8_is_valid($subject))
	{
		$subject = utf8_bad_replace($subject);
		trigger_error("Bad UTF-8 detected (replacing with '?') in contacts page", E_USER_NOTICE);
	}
	if (!$subject)
	{
		$error = true;
		$msg[] = $esynI18N['error_contact_subject'];
	}

	$body = $_POST['body'];
	if (!utf8_is_valid($body))
	{
		$body = utf8_bad_replace($body);
		trigger_error("Bad UTF-8 detected (replacing with '?') in contacts page", E_USER_NOTICE);
	}
	if (!$body)
	{
		$error = true;
		$msg[] = $esynI18N['error_contact_body'];
	}

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

		$mail->From = $email;
		$mail->FromName = $fullname;

		$replace = array(
			"sender_fullname" => $fullname,
			"sender_email" => $email,
			"subject" => $subject,
			"body" => $body,
		);

		if ("member" == $_GET['contact'] || "listing" == $_GET['contact'])
		{
			if ("listing" == $_GET['contact'])
			{
				$mail->AddAddress($listing['email']);
				$notif = 'contact_listing_owner';
				$email_tpl = 'contact_listing_owner';
			}
			else
			{
				$mail->AddAddress($account['email']);
				$notif = 'contact_member';
				$email_tpl = 'contact_member';
				$replace["account_url"] = $esynLayout->printAccountUrl(array("account" => $account));
			}

			if ($esynConfig->getConfig('contacts_send_admin'))
			{
                $mail->add_notif ($notif);
			}

			$mail->add_replace($replace);
			$mail->Send($email_tpl, $account, $listing);
		}
		elseif ('us' == $_GET['contact'])
		{
			$data = array(
				"fullname"	=> $fullname,
				"email"		=> $email,
				"subject"	=> $subject,
				"reason"	=> $body,
				"ip"		=> esynUtil::getIpAddress()
			);

			$eSyndiCat->setTable("contacts");
			$eSyndiCat->insert($data, array("date" => "NOW()"));
			$eSyndiCat->resetTable();

			$msg[] = $esynI18N['contact_added'];

			$mail->add_replace($replace);

            $mail->notifyAdmins('contact_us');
		}

		$msg[] = $esynI18N['email_sent'];
	}
}

$tpl = $_SERVER['REQUEST_METHOD'] == 'POST' && !$error ? 'success.tpl' : 'send.tpl';

switch (IA_REALM)
{
	case 'contact_us':

		esynBreadcrumb::add($esynI18N['contact_us']);

		$esynSmarty->assign('title', $esynI18N['contact_us']);
		$esynSmarty->assign('contact_header', $esynI18N['contact_us']);

		break;
	case 'contact_listing':

		esynBreadcrumb::add($listing['title'], $esynLayout->printListingUrl(array('listing' => $listing)));
		esynBreadcrumb::add($esynI18N['contact_listing_owner']);

		$esynSmarty->assign('listing', $listing);

		$esynSmarty->assign('title', $esynI18N['contact_listing_owner']);
		$esynSmarty->assign('contact_header', $esynI18N['contact_listing_owner']);

		break;
	case 'contact_member':

		$account_url = $esynLayout->printAccountUrl(array('account' => $account));
		$account_url = str_replace(IA_URL, '', $account_url);

		esynBreadcrumb::add($account['username'], $account_url);
		esynBreadcrumb::add($esynI18N['contact_account']);

		$esynSmarty->assign('account', $account);
		$esynSmarty->assign('listing', $listing);
		$esynSmarty->assign('title', $esynI18N['contact_account']);
		$esynSmarty->assign('contact_header', $esynI18N['contact_account']);

		break;
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE . $tpl);