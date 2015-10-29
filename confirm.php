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

define('IA_REALM', "confirm");

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

include IA_INCLUDES . 'view.inc.php';

$esynSmarty->caching = false;

$eSyndiCat->factory('Account', 'Layout');

$error = false;
$msg = array();

if (isset($_GET['action']))
{
	if ('change_email' == $_GET['action'])
	{
		$account = $esynAccount->row("*", "`id` = :id AND `sec_key` = :sec_key AND `nemail` != ''", array('id' => (int)$_GET['account'], 'sec_key' => $_GET['r']));

		if (!$account)
		{
			$error = true;
			$msg[] = $esynI18N['error_no_account_email'];
		}

		if (!$error)
		{
			$esynAccount->setNewAccountEmail($account);
			$msg[] = $esynI18N['account_successful_change_email'];
		}
	}

	if ('restore_password' == $_GET['action'])
	{
		$account = $esynAccount->row("*", "`id` = :id AND `sec_key` = :sec_key", array('id' => (int)$_GET['account'], 'sec_key' => $_GET['r']));

		if (!$account)
		{
			$form = false;

			$error = true;
			$msg[] = $esynI18N['error_no_account_email'];
		}

		if (!$error)
		{
			$form = false;

			$esynAccount->setNewPassword($account);
			$msg[] = $esynI18N['new_password_sent'];
		}
	}
}

$esynSmarty->assignByRef('title', $esynI18N['confirm_email']);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['confirm_email']);

$esynSmarty->display('confirm.tpl');
