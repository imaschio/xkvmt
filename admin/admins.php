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

define('IA_REALM', "admins");

esynUtil::checkAccess();

$esynAdmin->factory("Admins");

if (isset($_POST['save']))
{
	$esynAdmin->startHook('adminAddAdminValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	if (isset($_GET['id']))
	{
		$id = (int)$_GET['id'];

		$old_admin = $esynAdmins->row("*", "`id` = :id", array('id' => $id));
	}

	$error = false;

	$admin = array();

	/* Checking admin username */
	$admin['username'] = $_POST['username'];

	if (!utf8_is_valid($admin['username']))
	{
		$admin['username'] = utf8_bad_replace($admin['username']);
	}

	/* checking admin fullname */
	$admin['fullname'] = $_POST['fullname'];

	if (!utf8_is_valid($admin['fullname']))
	{
		$admin['fullname'] = utf8_bad_replace($admin['fullname']);
	}

	$admin['email'] = $_POST['email'];
	$admin['submit_notif'] = (int)$_POST['submit_notif'];
	$admin['account_registr_notif'] = (int)$_POST['account_registr_notif'];
	$admin['payment_notif'] = (int)$_POST['payment_notif'];

	if ('add' == $_GET['do'] || $currentAdmin['id'] != $id)
	{
		$admin['status'] = in_array($_POST['status'], array('active', 'inactive')) ? $_POST['status'] : 'inactive';
		$admin['super'] = in_array($_POST['super'], array('0', '1')) ? $_POST['super'] : '0';
	}

	if (isset($_GET['do']) && 'edit' == $_GET['do'])
	{
		if ($old_admin['username'] != $admin['username'] && $esynAdmins->exists("`username` = :username", array('username' => $admin['username'])))
		{
			$error = true;
			$msg[] = $esynI18N['username_exists'];
		}
	}
	else
	{
		if ($esynAdmins->exists("`username` = :username", array('username' => $admin['username'])))
		{
			$error = true;
			$msg[] = $esynI18N['username_exists'];
		}
	}

	if ($_POST['new_pass'] || $_POST['new_pass2'])
	{
		if (!utf8_is_ascii($_POST['new_pass']))
		{
			$error = true;
			$msg[] = $esynI18N['ascii_required'];
		}
		elseif ($_POST['new_pass'] != $_POST['new_pass2'])
		{
			$error = true;
			$msg[] = $esynI18N['incorrect_password_confirm'];
		}
		else
		{
			$admin['password'] = md5($_POST['new_pass']);
		}
	}

	if (!$admin['username'])
	{
		$error = true;
		$msg[] = $esynI18N['incorrect_username'];
	}

	if (!$admin['fullname'])
	{
		$error = true;
		$msg[] = $esynI18N['incorrect_fullname'];
	}

	if (!esynValidator::isEmail($admin['email']))
	{
		$error = true;
		$msg[] = $esynI18N['incorrect_email'];
	}
	else
	{
		if (('edit' == $_GET['do'] && $admin['email'] != $_POST['old_email']) || 'add' == $_GET['do'])
		{
			if ($esynAdmins->exists("`email` = :email", array('email' => $admin['email'])))
			{
				$error = true;
				$msg[] = _t('admin_email_exists');
			}
		}
	}

	if (!$_POST['new_pass'] && 'edit' != $_POST['do'])
	{
		$error = true;
		$msg[] = $esynI18N['incorrect_password'];
	}

	if (isset($admin['super']) && '0' == $admin['super'] && empty($_POST['permissions']))
	{
		$error = true;
		$msg[] = $esynI18N['incorrect_permissions'];
	}

	if (isset($_POST['permissions']) && is_array($_POST['permissions']) && '0' == $admin['super'])
	{
		$admin['permissions'] = $_POST['permissions'];
	}

	if (!$error)
	{
		if ('edit' == $_POST['do'])
		{
			$result = $esynAdmins->update($admin, $_POST['id']);

			if ($result)
			{
				$msg[] = $esynI18N['changes_saved'];
			}
			else
			{
				$error = true;
				$msg[] = $esynAdmins->getMessage();
			}
		}
		else
		{
			$result = $esynAdmins->insert($admin);

			if ($result)
			{
				$msg[] = $esynI18N['admin_added'];
			}
			else
			{
				$error = true;
				$msg[] = $esynAdmins->getMessage();
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

		if (!empty($sort) && !empty($dir))
		{
			$order = "ORDER BY `{$sort}` {$dir}";
		}

		$out['total'] = $esynAdmins->one("COUNT(*)");
		$out['data'] = $esynAdmins->all("*, `id` `edit`, '1' `remove`", "1=1 {$order}", array(), $start, $limit);
		$out['data'] = esynSanitize::applyFn($out['data'], "html");

		if (!empty($out['data']))
		{
			foreach($out['data'] as $key => $value)
			{
				if ($value['id'] == $currentAdmin['id'])
				{
					$out['data'][$key]['remove'] = 0;
				}
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
	$out = array('msg' => 'Unknown error', 'error' => true);

	if ('update' == $_POST['action'])
	{
		if ('status' == $_POST['field'] && in_array($currentAdmin['id'], $_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = _t('current_admin_status_change');
		}
		else
		{
			$result = $esynAdmins->update(array($_POST['field'] => $_POST['value']), $_POST['ids']);

			if ($result)
			{
				$out['error'] = false;
				$out['msg'] = $esynI18N['changes_saved'];
			}
			else
			{
				$out['error'] = true;
				$out['msg'] = $esynAdmins->getMessage();
			}
		}
	}

	if ('remove' == $_POST['action'])
	{
		// check if user tries to remove current admin
		if (is_array($_POST['ids']))
		{
			$key = array_search($currentAdmin['id'], $_POST['ids']);

			if ($key)
			{
				unset($_POST['ids'][$key]);
			}
		}

		$result = $esynAdmins->delete($_POST['ids']);

		if ($result)
		{
			$out['error'] = false;
			$out['msg'] = $esynI18N['changes_saved'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynAdmins->getMessage();
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_admins'];

$gBc[0]['title'] = $esynI18N['manage_admins'];
$gBc[0]['url'] = 'controller.php?file=admins';

if (isset($_GET['do']))
{
	if ('add' == $_GET['do'])
	{
		$gBc[0]['title'] = $esynI18N['manage_admins'];
		$gBc[0]['url'] = 'controller.php?file=admins';

		$gBc[1]['title'] = $esynI18N['create_admin'];
		$gTitle = $gBc[1]['title'];
	}
	elseif ('edit' == $_GET['do'])
	{
		$gBc[0]['title'] = $esynI18N['manage_admins'];
		$gBc[0]['url'] = 'controller.php?file=admins';

		$gBc[1]['title'] = $esynI18N['edit_admin'];
		$gTitle = $gBc[1]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?file=admins&amp;do=add", "icon" => "add_admin.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?file=admins", "icon" => "view_admin.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']))
{
	if ('edit' == $_GET['do'] && isset($_GET['id']) && ctype_digit($_GET['id']))
	{
		$id = (int)$_GET['id'];

		$admin = $esynAdmins->row("*", "`id` = :id", array('id' => $id));

		if ($admin)
		{
			$esynAdmin->setTable("admin_permissions");
			$admin['permissions'] = $esynAdmin->onefield("`aco`", "`allow` = '1' AND `admin_id` = :id", array('id' => $id));
			$esynAdmin->resetTable();

			if (empty($admin['permissions']))
			{
				$admin['permissions'] = array();
			}
		}

		$esynSmarty->assign('admin', $admin);
	}

	$esynAdmin->setTable('admin_blocks');
	$admin_blocks = $esynAdmin->onefield('`name`', '1=1 ORDER BY `order`');
	$esynAdmin->resetTable();

	$sql = "SELECT `ap`.`block_name`, `ap`.`aco`, `l`.`value` `title` "
		 . "FROM `{$esynAdmin->mPrefix}admin_pages` `ap` "
		 . "LEFT JOIN `{$esynAdmin->mPrefix}language` `l` "
		 . "ON `l`.`key` = CONCAT('admin_page_title_', `ap`.`aco`) "
		 . "WHERE `block_name` != '' "
		 . "GROUP BY `aco` "
		 . "ORDER BY `order`";

	$admin_pages = $esynAdmin->getAll($sql);

	$esynSmarty->assign('admin_blocks', $admin_blocks);
	$esynSmarty->assign('admin_pages', $admin_pages);
}

$esynSmarty->display('admins.tpl');

