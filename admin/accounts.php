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

if (isset($_GET['do']) && 'add' == $_GET['do'])
{
	define('IA_REALM', "accounts_add");
}
else
{
	define('IA_REALM', "accounts");
}

esynUtil::checkAccess();

$esynAdmin->factory("Account");

// delete photo
if (isset($_GET['delete']) && 'photo' == $_GET['delete'])
{
	$esynAdmin->setTable('accounts');
	$imageName = $esynAdmin->one('avatar', "`id` = :id", array('id' => $_GET['id']));

	if (file_exists(IA_UPLOADS . $imageName))
	{
		unlink(IA_UPLOADS . $imageName);
	}

	if (file_exists(IA_UPLOADS . 'small_' . $imageName))
	{
		unlink(IA_UPLOADS . 'small_' . $imageName);
	}
	$esynAdmin->update(array('avatar' => ''), "`id` = :id", array('id' => $_GET['id']));
	$esynAdmin->resetTable();
}

if (isset($_GET['login_as']))
{
	$acc_id = (int)$_GET['login_as'];

	$esynAdmin->setTable('accounts');
	$acc = $esynAdmin->row('*', "`id` = '{$acc_id}'");

	if ($acc)
	{
		$esynAdmin->startHook('afterLogged');

		$pwd = crypt($acc['password'], IA_SALT_STRING);

		setcookie("account_id", $acc['id'], 0, '/');
		setcookie("account_pwd", $pwd, 0, '/');

		esynUtil::go2( IA_URL );
	}
	else
	{
		die('Account not found.');
	}
}

if (isset($_POST['save']))
{
	$esynAdmin->startHook('adminAddAccountValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error = false;

	$account = array();

	$account['username'] = trim($_POST['username']);
	$account['status'] = isset($_POST['status']) && in_array($_POST['status'], array('active', 'approval', 'banned')) ? $_POST['status'] : 'approval';

	if (empty($account['username']))
	{
		$error = true;
		$msg[] = $esynI18N['error_username_empty'];
	}
	else
	{
		if (!utf8_is_ascii($account['username']))
		{
			$error = true;
			$msg[] = 'Username: ' . $esynI18N['ascii_required'];
		}
	}

	if (isset($_GET['do']) && 'edit' == $_GET['do'])
	{
		if ($account['username'] != $_POST['old_name'])
		{
			if ($esynAccount->exists("`username` = :username", array('username' => $account['username'])))
			{
				$error = true;
				$msg[] = $esynI18N['error_username_exists'];
			}
		}
	}
	else
	{
		if ($esynAccount->exists("`username` = :username", array('username' => $account['username'])))
		{
			$error = true;
			$msg[] = $esynI18N['error_username_exists'];
		}
	}

	// checking plan
	if (isset($_POST['plan']) && !empty($_POST['plan']))
	{
		$account['plan_id'] = ('-1' == $_POST['plan']) ? 0 : (int)$_POST['plan'];
		$account['sponsored_expire_date'] = $_POST['sponsored_expire_date'];
	}

	/**
	 * checking password
	 * don't need to check password if user edits account and don't enter password
	 *
	 */
	if (!empty($_POST['password']) || !empty($_POST['password2']))
	{
		$account['password'] = $_POST['password'];

		if (empty($account['password']))
		{
			$error = true;
			$msg[] = $esynI18N['error_password_empty'];
		}
		elseif (!utf8_is_ascii($account['password']))
		{
			$error = true;
			$msg[] = 'Password: ' . $esynI18N['ascii_required'];
		}
		elseif (md5($account['password']) != md5($_POST['password2']))
		{
			$error = true;
			$msg[] = $esynI18N['error_password_match'];
		}
	}

	if (empty($_POST['password']) && isset($_GET['do']) && 'add' == $_GET['do'])
	{
		$error = true;
		$msg[] = $esynI18N['error_password_empty'];
	}

	// checking email
	$account['email'] = $_POST['email'];

	if (!esynValidator::isEmail($account['email']))
	{
		$error = true;
		$msg[] = $esynI18N['incorrect_email'];
	}
	else
	{
		if (('edit' == $_GET['do'] && $account['email'] != $_POST['old_email'])
			|| 'add' == $_GET['do']
		)
		{
			if ($esynAccount->exists("`email` = :email", array('email' => $account['email'])))
			{
				$error = true;
				$msg[] = $esynI18N['account_email_exists'];
			}
		}
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
		$esynAdmin->loadClass('Image');
		$image = new esynImage();

		$file_name = 'avatar_' . $account['username'] . '_' . $token . '.' . $ext;
		$image->processImage($_FILES['avatar'], IA_UPLOADS, $file_name, $field_info);
		$account['avatar'] = 'small_' . $file_name;
	}

	if (!$error)
	{
		if ('edit' == $_POST['do'])
		{
			$send_email = isset($_POST['send_email']) ? true : false;

			$result = $esynAccount->update($account, (int)$_POST['id'], $send_email);

			if ($result)
			{
				$msg[] = $esynI18N['changes_saved'];
			}
			else
			{
				$error = true;

				$msg[] = $esynAccount->getMessage();
			}
		}
		else
		{
			$result = $esynAccount->insert($account);

			if ($result)
			{
				$msg[] = $esynI18N['account_added'];
			}
			else
			{
				$error = true;

				$msg[] = $esynAccount->getMessage();
			}
		}

		esynMessages::setMessage($msg, $error);

		if (!$error)
		{
			$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

			esynUtil::reload(array("do" => $do));
		}
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']))
{
	$start = (int)$_GET['start'];
	$limit = (int)$_GET['limit'];

	$out = array('data' => '', 'total' => 0);

	if ('get' == $_GET['action'])
	{
		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		$where = array();
		$values = array();

		if (!empty($sort) && !empty($dir))
		{
			$sort = ('date' == $sort) ? 'date_reg' : $sort;

			$order = " ORDER BY `{$sort}` {$dir}";
		}

		if (isset($_GET['status']) && in_array($_GET['status'], array('active', 'approval', 'unconfirmed')))
		{
			$where[] = "`a`.`status` = :status";
			$values['status'] = $_GET['status'];
		}

		if (isset($_GET['username']) && !empty($_GET['username']))
		{
			$where[] = "`a`.`username` LIKE :username";
			$values['username'] = '%'.$_GET['username'].'%';
		}

		if (isset($_GET['email']) && !empty($_GET['email']))
		{
			$where[] = "`a`.`email` LIKE :email";
			$values['email'] = '%'.$_GET['email'].'%';
		}

		if (isset($_GET['id']) && !empty($_GET['id']))
		{
			$where[] = "`a`.`id` = :id";
			$values['id'] = (int)$_GET['id'];
		}

		if (empty($where))
		{
			$where[] = '1=1';
			$values = array();
		}

		$where = implode(" AND ", $where);

		$count_where = str_replace('`a`.', '', $where);
		$count_where = str_replace('`l`.', '', $count_where);

		$out['total'] = $esynAccount->one("COUNT(*)", $count_where, $values);

		$sql = "SELECT `a`.*, (SELECT COUNT(*) from `{$esynAdmin->mPrefix}listings` `l` WHERE `l`.`account_id` = `a`.`id`) `listings`, `a`.`id` `edit`, "
			. "if (`a`.`status` = 'unconfirmed', `a`.`id`, 0) `sendemail` "
			. "FROM `{$esynAdmin->mPrefix}accounts` `a` "
			. "WHERE "
			. $where
			. " GROUP BY `a`.`id` "
			. $order
			. " LIMIT {$start}, {$limit}";

		$out['data'] = $esynAccount->getAll($sql, $values);

		if (is_array($out['data']))
		{
			foreach ($out['data'] as $k => $v )
			{
				$out['data'][$k]['login'] = sprintf('<a href="controller.php?file=accounts&amp;login_as=%d" title="%s" target="_blank">%s</a>', $v['id'], $esynI18N['login_on_frontend'], $esynI18N['login']);
			}
		}
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{
	$out = array('msg' => 'Unknown error', 'error' => true, 'notes' => '');

	if ('remove' == $_POST['action'])
	{
		$result = $esynAccount->delete($_POST['ids']);

		if ($result)
		{
			$out['error'] = false;
			$out['msg'] = (count($_POST['ids']) > 1) ? $esynI18N['accounts_deleted'] : $esynI18N['account_deleted'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynAccount->getMessage();
		}
	}

	if ('update' == $_POST['action'])
	{
		if ('username' == $_POST['field'])
		{
			if ($esynAccount->exists("`username` = :username", array('username' => $_POST['value'])))
			{
				$out['error'] = true;
				$out['msg'] = $esynI18N['error_username_exists'];
			}
			else
			{
				$out['error'] = false;
			}
		}

		if ('email' == $_POST['field'])
		{
			if ($esynAccount->exists("`email` = :email", array('email' => $_POST['value'])))
			{
				$out['error'] = true;
				$out['msg'] = $esynI18N['account_email_exists'];
			}
			else
			{
				$out['error'] = false;
			}
		}

		if (!in_array($_POST['field'], array('username', 'email')))
		{
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			$result = $esynAccount->update(array($_POST['field'] => $_POST['value']), $_POST['ids'], true);

			if ($result)
			{
				$out['error'] = false;
				$out['msg'] = $esynI18N['changes_saved'];
			}
			else
			{
				$out['error'] = true;
				$out['msg'] = $esynAccount->getMessage();
			}
		}
	}

	if ('sendemail' == $_POST['action'])
	{
		$ids = $esynAccount->convertIds('id', $_POST['ids']);
		$accounts = $esynAccount->all("*", $ids);

		if ($accounts)
		{
			foreach($accounts as $account)
			{
				// set a new password for account and update it
				$password = $esynAccount->createPassword();
				$account['password'] = $password;
				$account['sec_key'] = md5(esynUtil::getNewToken());

				$esynAccount->update(array('password' => $account['password'], 'sec_key' => $account['sec_key']), $account['id']);

				$esynAccount->resendEmail($account);
			}

			$out['error'] = false;
			$out['msg'] = $esynI18N['confirmation_resent'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynAccount->getMessage();
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gBc[0]['title'] = $esynI18N['manage_accounts'];
$gBc[0]['url'] = 'controller.php?file=accounts';

$gTitle = $esynI18N['manage_accounts'];

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[0]['title'] = $esynI18N['manage_accounts'];
		$gBc[0]['url'] = 'controller.php?file=accounts';

		$gBc[1]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_account'] : $esynI18N['create_account'];
		$gTitle = $gBc[1]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?file=accounts&amp;do=add", "icon" => "add_account.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?file=accounts", "icon" => "view_account.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']))
{
	$esynAdmin->setTable('plans');
	$plans = $esynAdmin->all('*', "`item` = 'account' AND `status` = 'active'");
	$esynAdmin->resetTable();

	$esynSmarty->assign('plans', $plans);

	if ('edit' == $_GET['do'] && isset($_GET['id']) && ctype_digit($_GET['id']))
	{
		$account = $esynAccount->row("*", "`id` = :id", array('id' => (int)$_GET['id']));
		$esynSmarty->assign('account', $account);
	}
}

$esynSmarty->display('accounts.tpl');
