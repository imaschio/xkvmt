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

define('IA_REALM', "account_login");

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory('Account', 'Layout');

$error = false;
$msg = array();

if (isset($_SESSION['esyn_msg']) && !empty($_SESSION['esyn_msg']))
{
	$msg = $_SESSION['esyn_msg'];

	unset($_SESSION['esyn_msg']);
}

require_once IA_INCLUDES . 'view.inc.php';

$esynSmarty->cache_lifetime	= 0;
$esynSmarty->caching = false;

if ($esynConfig->getConfig('accounts'))
{
	if (!empty($_POST['login']))
	{
		if (empty($_POST['username']))
		{
			$error = true;
			$msg[] = $esynI18N['error_account_incorrect'];
		}

		if (empty($_POST['password']))
		{
			$error = true;
			$msg[] = $esynI18N['error_accountpsw_incorrect'];
		}

		if (!$error)
		{
			$successfullyAuthenticated	= false;
			$ownAuthenTicationMechanism = false;

			$eSyndiCat->startHook('beforeAuthenticate');

			// default authentication mechanism
			if (!$ownAuthenTicationMechanism)
			{
				$login = esynSanitize::sql($_POST['username']);
				$condition = sprintf("(`username` = '%s' OR `email` = '%s') AND `status` IN ('%s', '%s', '%s')", $login, $login, 'active', 'banned', 'unconfirmed');
				$account = $esynAccount->row("`id`, `password`, `status`, `username`", $condition);

				if (!$account)
				{
					$error = true;
					$msg[] = $esynI18N['username_empty'];
				}
				elseif ('banned' == $account['status'])
				{
					$error = true;
					$msg[] = $esynI18N['username_banned'];
				}
				elseif ('unconfirmed' == $account['status'])
				{
					$error = true;
					$msg[] = str_replace('{username}', $account['username'], $esynI18N['username_unconfirmed']);
				}
				elseif ($account['password'] != md5($_POST['password']))
				{
					$error = true;
					$msg[] = $esynI18N['password_incorrect'];
				}
				elseif ('active' == $account['status']) // success
				{
					$successfullyAuthenticated = true;
				}
			}

			if ($successfullyAuthenticated)
			{
				$account['ip_address'] = esynUtil::getIpAddress(true);

				$esynAccount->updateAccount($account);

				$eSyndiCat->startHook('afterLogged');

				$pwd = crypt($account['password'], IA_SALT_STRING);

				$expireTime = (isset($_POST['rememberme']) && 1 == $_POST['rememberme']) ? time() + 60 * 60 * 24 * 14 : 0;

				setcookie("account_id", $account['id'], $expireTime, '/');
				setcookie("account_pwd", $pwd, $expireTime, '/');

				$go2_url = isset($_SESSION['esyn_last_page']) ? $_SESSION['esyn_last_page'] : IA_URL;

				esynUtil::go2($go2_url);
			}
		}
		else
		{
			$eSyndiCat->startHook('afterFailLogin');
		}
	}
}
else
{
	$_GET['error'] = '670';
	require 'error.php';
	exit;
}

$esynSmarty->assign('title', $esynI18N['account_login']);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['account_login']);

$esynSmarty->display('login.tpl');
