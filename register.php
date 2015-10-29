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

define('IA_REALM', "account_register");

if (isset($_GET['id']) && preg_match("/\D/", $_GET['id']))
{
	$_GET['error'] = "404";
	require './error.php';
	exit;
}

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory('Account', 'Layout', 'Plan');

if (!$esynConfig->getConfig('accounts'))
{
	$_GET['error'] = "670";
	require IA_HOME . "error.php";
	exit;
}

// Smarty and other View related things
require_once IA_INCLUDES . 'view.inc.php';

// confirm account registration email address in any case, no matter auto approval enabled/disabled
if (isset($_GET['action']) && ('confirm' == $_GET['action']))
{
	$success = false;

	if (isset($_GET['user']) && isset($_GET['key']) && !empty($_GET['user']) && !empty($_GET['key']))
	{
		$result = $esynAccount->row("*", "`sec_key` = '" . esynSanitize::sql($_GET['key']) . "' AND `username` = '" . esynSanitize::sql($_GET['user']) . "'");

		// if account exists based on input values
		if ($result)
		{
			if ($esynConfig->getConfig('accounts_autoapproval'))
			{
				$status = 'active';
				$msg[] = $esynI18N['reg_confirmed'];
			}
			else
			{
				$status = 'approval';
				$msg[] = $esynI18N['reg_confirmed_pending'];
			}

			$esynAccount->update(array('status' => $status, 'sec_key' => ''), "`sec_key` = '" . esynSanitize::sql($_GET['key']) . "'");
			$success = true;

			$eSyndiCat->mMailer->notifyAdmins('account_confirmed', $result['id']);
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['reg_confirm_err'];
		}
	}
	else
	{
		$error = true;
		$msg[] = $esynI18N['confirm_not_valid'];
	}
	$esynSmarty->assignByRef('success', $success);

	// defines page title
	$esynSmarty->assign('title', $esynI18N['reg_confirmation']);
	$esynSmarty->assign('header', $esynI18N['confirm_email']);

	// breadcrumb formation
	esynBreadcrumb::add($esynI18N['reg_confirmation']);

	$esynSmarty->display('confirm.tpl');
	exit;
}

if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
{
	$eSyndiCat->factory('Captcha');
}

$error = false;
$msg = array();

$accountCreated = false;

// set cache time for this page
$esynSmarty->caching = false;

if (isset($_POST['register']))
{
	$account['username'] = isset($_POST['username']) ? $_POST['username'] : '';
	$account['email'] = isset($_POST['email']) ? $_POST['email'] : '';
	$account['password'] = isset($_POST['password']) ? $_POST['password'] : '';
	$account['auto_generate'] = isset($_POST['auto_generate']) ? (int)$_POST['auto_generate'] : 0;

	// check username
	if (!preg_match("/^[\w\s]{3,30}$/", $account['username']))
	{
		$error = true;
		$msg[] = $esynI18N['error_username_incorrect'];
	}
	elseif (!$account['username'])
	{
		$error = true;
		$msg[] = $esynI18N['error_username_empty'];
	}
	elseif ($esynAccount->exists("`username` = '{$account['username']}'"))
	{
		$error = true;
		$msg[] = $esynI18N['error_username_exists'];
	}

	// check email
	if (!esynValidator::isEmail($account['email']))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}
	elseif ($esynAccount->exists("`email` = '" . esynSanitize::sql($account['email']) . "'"))
	{
		$error = true;
		$msg[] = $esynI18N['account_email_exists'];
	}

	// check password
	if (!$account['auto_generate'])
	{
		if (!$account['password'])
		{
			$error = true;
			$msg[] = $esynI18N['error_password_empty'];
		}
		else
		{
			if ($_POST['password'] != $_POST['password2'])
			{
				$error = true;
				$msg[] = $esynI18N['error_password_match'];
			}
		}
	}

	if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
	{
		if (!$esynCaptcha->validate())
		{
			$error = true;
			$msg[] = $esynI18N['error_captcha'];
		}
	}

	$_SESSION['pass'] = '';

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

		// create account
		$account_id = $esynAccount->registerAccount($account);

		$accountCreated = true;

		$msg[] = $esynI18N['account_created'];

		$eSyndiCat->startHook('afterAccountCreated');

		$item['id'] = $item['account_id'] = $account_id;
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

	$esynSmarty->assign('account', $account);
}

// get plans
$plans = $esynPlan->getAllPlansByCategory(0, "`item` = 'account' AND FIND_IN_SET('suggest', `pages`) AND `lang` = '" . IA_LANGUAGE . "'");
$esynSmarty->assign('plans', $plans);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['register']);

if ($accountCreated)
{
	$title = $esynI18N['account_created'];
	$esynSmarty->assign('email', $account['email']);
	$tpl = 'thank.tpl';
}
else
{
	$title = $esynI18N['register_account'];
	$tpl = 'register.tpl';
}

$esynSmarty->assignByRef('title', $title);
$esynSmarty->assign('header', $esynI18N['register']);

$esynSmarty->display($tpl);
