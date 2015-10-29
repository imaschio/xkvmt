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

define('IA_REALM', "resend_activation_email");

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

if (!$esynConfig->getConfig('accounts'))
{
	$_GET['error'] = "670";
	include IA_HOME . 'error.php';
	exit;
}

$eSyndiCat->factory('Account', 'Layout');

require_once IA_INCLUDES . 'view.inc.php';

$esynSmarty->caching = false;

if (isset($_POST['resend']))
{
	$username = esynSanitize::sql($_POST['username']);

	/** check username **/
	if (!$username)
	{
		$error = true;
		$msg[] = $esynI18N['error_username_empty'];
	}

	if (!$error)
	{
		$field = esynValidator::isEmail($username) ? 'email' : 'username';

		$account = $esynAccount->row("*", "`{$field}` = '{$username}'");

		if ($account)
		{
			if ('unconfirmed' == $account['status'])
			{
				$password = $esynAccount->createPassword();
				$account['password'] = $password;
				$account['sec_key'] = md5(esynUtil::getNewToken());

				$esynAccount->update(array('password' => md5($account['password']), 'sec_key' => $account['sec_key'], 'id' => $account['id']));

				$esynAccount->resendEmail($account);

				$msg[] = $esynI18N['email_sent'];
			}
			else
			{
				$error = true;
				$msg[] = $esynI18N['account_already_confirmed'];
			}
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['error_no_account_username'];
		}
	}
}

$esynSmarty->assign('title', $esynI18N['resend_confirmation_email']);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['resend_confirmation_email']);

$esynSmarty->display('resend-confirm.tpl');
