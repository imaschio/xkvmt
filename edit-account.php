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

define('IA_REALM', "edit_account");

define("IA_THIS_PAGE_PROTECTED", true);

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

include IA_INCLUDES . 'view.inc.php';

$esynSmarty->caching = false;

$eSyndiCat->factory('Account', 'Layout', 'Plan');

$error = false;
$msg = array();

if (isset($_GET['delete']) && 'photo' == $_GET['delete'])
{
	$account['id'] = $esynAccountInfo['id'];
	$account['avatar'] = '';

	// delete avatar
	@unlink(IA_UPLOADS . $esynAccountInfo['avatar']);

	// update account information
	$esynAccount->updateAccount($account);
	$esynAccountInfo['avatar'] = '';

	$error = false;
	$msg[] = $esynI18N['changes_saved'];
}

if (isset($_POST['change_email']))
{
	// check email
	if (!esynValidator::isEmail($_POST['email']))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}

	if ($esynAccount->exists("`email` = :email AND `id` != :account_id", array('email' => $_POST['email'], 'account_id' => $esynAccountInfo['id'])))
	{
		$error = true;
		$msg[] = $esynI18N['account_email_exists'];
	}

	// avatar upload
	if (!$_FILES['avatar']['error'])
	{
		$ext = substr($_FILES['avatar']['name'], -3);
		$token = esynUtil::getNewToken();

		$field_info = array(
			'image_width' => '100',
			'image_height' => '100',
			'thumb_width' => '100',
			'thumb_height' => '100',
			'resize_mode' => 'crop'
		);

		// process image
		$eSyndiCat->loadClass("Image");
		$image = new esynImage();

		$file_name = 'avatar_' . $esynAccountInfo['username'] . '_' . $token . '.' . $ext;
		$image->processImage($_FILES['avatar'], IA_HOME . 'uploads' . IA_DS, $file_name, $field_info);

		$esynAccountInfo['avatar'] = $account['avatar'] = 'small_' . $file_name;
	}

	if (!$error)
	{
		// process sponsored plan actions
		$plan = array();
		$planId = isset($_POST['plan']) ? (int)$_POST['plan'] : false;
		if ($planId)
		{
			$plan = $esynPlan->row("*", "`id` = :plan", array('plan' => $planId));

			$account['plan_id'] = $planId;
			$account['plan_period'] = $plan['period'];
			$account['plan_cost'] = $plan['cost'];
		}

		$account['id'] = $esynAccountInfo['id'];

		// update account information
		$esynAccount->updateAccount($account);
		$msg[] = $esynI18N['changes_saved'];

		if ($_POST['old_email'] != $_POST['email'])
		{
			$esynAccountInfo['nemail'] = $_POST['email'];
			$esynAccount->confirmEmail($esynAccountInfo, 'account_confirm_change_email');

			$msg[] = $esynI18N['instructions_change_email_sent'];
		}

		$item['id'] = $item['account_id'] = $account['id'];
		$item['item'] = 'account';

		if (isset($_POST['payment_gateway']) && !empty($_POST['payment_gateway']) && $plan)
		{
			if ($plan['cost'] > 0)
			{
				$sec_key = $esynPlan->preparePayment($item, $_POST['payment_gateway'], $plan['cost'], $plan);

				$redirect_url = IA_URL . 'pre_pay.php?sec_key=' . $sec_key;

				esynUtil::go2($redirect_url);
			}
		}
	}
}

if (isset($_POST['delete_account']))
{
	if (!isset($_POST['delete_accept']))
	{
		$error = true;
		$msg[] = _t('error_delete_account_accept');
	}

	if (!$error)
	{
		$esynAccount->deleteAccount($esynAccountInfo['id']);

		esynUtil::go2(IA_URL);
	}
}

if (isset($_POST['delete_listing']))
{
	$id = (int)$_POST['listing'];

	$eSyndiCat->setTable('listings');

	$listing = $eSyndiCat->row('*', "`id` = '{$id}'");

	if (!empty($listing) && $listing['account_id'] == $esynAccountInfo['id'])
	{
		$eSyndiCat->update(array('status' => 'deleted'), "`id` = '{$listing['id']}'", array(), array('date_del' => 'NOW()'));

		$msg = _t('listing_deleted');
	}

	$eSyndiCat->resetTable();

	echo $msg;
	exit;
}

if (isset($_POST['change_pass']))
{
	$eSyndiCat->startHook('editAccountBeforeValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';
		$esynUtf8 = new esynUtf8();

		$esynUtf8->loadUTF8Core();
		$esynUtf8->loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	/** checks for current password **/
	if ($esynAccountInfo['password'] != md5($_POST['current']))
	{
		$error = true;
		$msg[] = $esynI18N['password_incorrect'];
	}

	if ($esynAccountInfo['password'] == md5($_POST['new']))
	{
		$error = true;
		$msg[] = $esynI18N['password_the_same'];
	}

	if (!$_POST['new'])
	{
		$error = true;
		$msg[] = $esynI18N['password_empty'];
	}

	if ($_POST['new'] != $_POST['confirm'])
	{
		$error = true;
		$msg[] = $esynI18N['passwords_not_match'];
	}

	// clear compiled templates
	if (!$error)
	{
		if (utf8_is_ascii($_POST['new']))
		{
			// delete cookies
			setcookie('account_id', '', $_SERVER['REQUEST_TIME'] - 3600);
			setcookie('account_pwd', '', $_SERVER['REQUEST_TIME'] - 3600);

			$esynAccount->changePassword($esynAccountInfo['id'], $_POST['new']);

			$pwd = crypt(md5($_POST['new']), IA_SALT_STRING);

			setcookie("account_id", $esynAccountInfo['id']);
			setcookie("account_pwd", $pwd);

			$msg[] = $esynI18N['password_changed'];
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['ascii_required'];
		}
	}
}

$eSyndiCat->setTable('plans');
$plans = $eSyndiCat->all('*', "`status` = 'active' AND `item` = 'account' && FIND_IN_SET('edit', `pages`) ORDER BY `order`");
$eSyndiCat->resetTable();
$esynSmarty->assign('plans', $plans);

$esynSmarty->assignByRef('title', $esynI18N['edit_account']);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['edit_account']);

$esynSmarty->display('edit-account.tpl');
