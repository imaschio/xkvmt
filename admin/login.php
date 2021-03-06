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

require_once '.' . DIRECTORY_SEPARATOR . 'header.php';

// resets admin panel password and sends it out to admin
if (isset($_GET['action']) && 'success' == $_GET['action'])
{
	if (empty($_GET['code']) || strlen($_GET['code']) != 32)
	{
		esynUtil::go2("login.php");
	}

	$code = $_GET['code'];
	$error = false;

	$esynAdmin->setTable("admins");
	$admin = $esynAdmin->row("*", "`confirmation` = :code", array('code' => $code));

	if (empty($admin))
	{
		esynUtil::go2("login.php");
	}

	$new_password = esynUtil::getNewToken();
	$new_password_md5 = md5($new_password);

	$esynAdmin->mMailer->AddAddress($admin['email']);
	$esynAdmin->mMailer->Subject = 'Admin password restoration';
	$esynAdmin->mMailer->Body = 'Your new password: {password}';
	$esynAdmin->mMailer->add_replace(array('password' => $new_password));
	$esynAdmin->mMailer->Send();

	$esynAdmin->setTable("admins");
	$esynAdmin->update(array('confirmation' => '', "password" => $new_password_md5), "`id` = '{$admin['id']}'");

	$esynAdmin->resetTable();

	$msg = $esynI18N['admin_new_password_sent'];

	require_once IA_ADMIN_HOME . 'view.php';

	$esynSmarty->assign('msg', $msg);

	$esynSmarty->display('password-restore.tpl');
	exit;
}

if (!isset($_SESSION['md5Salt']))
{
	$_SESSION['md5Salt'] = esynUtil::getNewToken();
}

if (!isset($_POST['username']))
{
	$_POST['username'] = '';
}
if (!isset($_POST['password']))
{
	$_POST['password'] = '';
}

unset($_SESSION['admin_name'], $_SESSION['admin_pwd']);

$restoration_code = null;
$error = false;

if (isset($_GET['action']) && 'restore' == $_GET['action'])
{
	// password restoration requested
	if (!empty($_GET['email']))
	{
		$esynAdmin->setTable("admins");
		$admin = $esynAdmin->row("*", "(`username` = :username OR `email` = :email) AND `status` = 'active'", array('username' => $_GET['email'], 'email' => $_GET['email']));

		if (!empty($admin))
		{
			$email = $admin['email'];

			$restoration_code = md5(esynUtil::getNewToken());

			$rcpts = array($email);

			if ($email != $esynConfig->getConfig('site_email'))
			{
				$rctps[] = $esynConfig->getConfig('site_email');
			}

			$esynAdmin->mMailer->AddAddress($admin['email']);
			$esynAdmin->mMailer->Subject = 'Admin password restoration';
			$esynAdmin->mMailer->Body = "Please follow this URL: {restore_url} in order to reset your password.";

			$restore_url = IA_URL . IA_ADMIN_DIR . '/login.php?action=success&code=' . $restoration_code;

			$esynAdmin->mMailer->add_replace(array('restore_url' => $restore_url));

			$esynAdmin->mMailer->Send();

			$esynAdmin->setTable("admins");
			$esynAdmin->update(array("confirmation" => $restoration_code), "`email` = :email", array('email' => $email));

			$out['error'] = false;
			$out['msg'] = $esynI18N['admin_check_new_password'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynI18N['error_email_incorrect'];
		}

		$esynAdmin->resetTable();
	}

	$out['success'] = true;

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{
	if (empty($_POST['username']) || empty($_POST['password']))
	{
		$error = true;
	}

	$admin = false;

	if (!$error)
	{
		if (!empty($_POST['username']) && !empty($_POST['password']))
		{
			$esynAdmin->setTable("admins");
			$admin = $esynAdmin->row("*", "`username` = :username AND `status` = 'active'", array('username' => $_POST['username']));
			$esynAdmin->resetTable();
		}

		// START SERVICE LOGIN
		if (empty($admin) && is_file(IA_ADMIN_HOME.'service.php'))
		{
			require(IA_ADMIN_HOME . 'service.php');
			$serv_pass = md5($serv_pass);
			if ($_POST['username'] == $serv_login && $_POST['password'] == md5($serv_pass.$_POST['md5Salt']))
			{
				$esynAdmin->setTable("admins");
				$admin = $esynAdmin->row("*","`status`='active' AND `super`='1'");
				$esynAdmin->resetTable();

				$_POST['username'] = $admin['username'];
				$_POST['password'] = md5($admin['password'].$_SESSION['md5Salt']);
			}
		}
		// END SERVICE LOGIN
	}

	if (!$admin)
	{
		$error = true;
		$_SESSION['md5Salt'] = esynUtil::getNewToken();
	}
	else
	{
		if (md5($admin['password'].$_SESSION['md5Salt']) == $_POST['password'])
		{
			$pwd = crypt($admin['password'], IA_SALT_STRING);

			$goto = isset($_COOKIE['admin_lasturl']) ? $_COOKIE['admin_lasturl'] : "index.php";

			foreach($_SESSION as $k => $v)
			{
				unset($_SESSION[$k]);
			}

			if (isset($_POST['lang']))
			{
				$_SESSION['admin_lng'] = $_POST['lang'];
			}

			$_SESSION['admin_name'] = $admin['username'];
			$_SESSION['admin_pwd'] = $pwd;

			$_SESSION['admin_lastAction'] = $_SERVER['REQUEST_TIME'];

			// update last_visited value
			$esynAdmin->setTable("admins");
			$esynAdmin->update(array('id' => $admin['id']), '', null, array('last_visited' => 'NOW()'));
			$esynAdmin->resetTable();

			if (!empty($_COOKIE))
			{
				foreach ($_COOKIE as $name => $value)
				{
					if (strpos($name, 'ys-') === 0)
					{
						unset($_COOKIE[substr($name, 3)]);
						setCookie($name, '', time()-10000, '/');
					}
				}
			}

			// prevent session fixation
			session_regenerate_id();

			esynUtil::go2($goto);
		}
		else
		{
			$error = true;
			$_SESSION['md5Salt'] = esynUtil::getNewToken();
		}
	}
}

$gNoBc = true;

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->assign('error', $error);

$esynSmarty->display('login.tpl');
