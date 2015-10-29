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

define('IA_REALM', "account_password_forgot");

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

if (!$esynConfig->getConfig('accounts'))
{
	$_GET['error'] = "670";
	include IA_HOME . 'error.php';
	exit;
}

$eSyndiCat->factory('Account', 'Layout');

$error = false;
$msg = array();

$form = true;

require_once IA_INCLUDES . 'view.inc.php';

$esynSmarty->caching = false;

if (isset($_POST['restore']))
{
	// check emails
	if (!esynValidator::isEmail($_POST['email']))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}

	if (!$error)
	{
		$account = $esynAccount->row("*", "`email` = :email", array('email' => $_POST['email']));

		if ($account)
		{
			$form = false;

			$esynAccount->confirmEmail($account, 'account_confirm_restore_password');

			$msg[] = $esynI18N['instructions_restore_password_sent'];
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['error_no_account_email'];
		}
	}
}

$esynSmarty->assign('form', $form);

$esynSmarty->assignByRef('title', $esynI18N['restore_password']);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['restore_password']);

$esynSmarty->display('forgot.tpl');
