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

define('IA_REALM', "manage_newsletters");

define("IA_ITEMS_PER_PAGE", 20);

$error 	= false;

if (isset($_POST['save']))
{
	$esynAdmin->setTable('newsletter');

	$realname 	= $_POST['realname'] 	? $_POST['realname'] 	: '';
	$email 		= $_POST['email'] 		? $_POST['email'] 		: '';
	$status 	= $_POST['status'] 		? $_POST['status'] 		: '';

	if (empty($realname))
	{
		$error = true;
		$msg[] = $esynI18N['error_name'];
	}

	if (!esynValidator::isEmail($email))
	{
		$error = true;
		$msg[] = $esynI18N['error_email'];
	}

	if ($esynAdmin->exists("`email` = '{$email}'"))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_exist'];
	}

	if (!$error)
	{
		$newsletters['realname'] = $realname;
		$newsletters['email'] 	 = $email;
		$newsletters['status'] 	 = $status;

		$esynAdmin->insert($newsletters, array('date_reg' => 'NOW()')); 

		$msg[] = $esynI18N['newsletters_added'];

		esynMessages::setMessage($msg);
		esynUtil::reload(array("do" => 'add'));
	}
	$esynAdmin->resetTable();
	esynMessages::setMessage($msg, $error);
}

if (isset($_POST['action']))
{


	$out = array('msg' => $esynI18N['error_saving_changes'], 'error' => false, 'notes' => '');
	
	$esynAdmin->setTable('newsletter');
	
	if ('remove' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);
			$esynAdmin->delete($where);

			$out['msg'] = (count($_POST['ids']) > 1) ? $esynI18N['newsletters_deleted'] : $esynI18N['newsletter_deleted'];
		}
		else
		{
			$out['msg'] = $esynI18N['params_wrong'];
			$out['error'] = true;
		}
	}

	if ('update' == $_POST['action'])
	{
		$field = $_POST['field'];
		$value = $_POST['value'];

		if (!empty($field) || !empty($value) || !empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);
			$esynAdmin->update(array($field => $value), $where);
			$out['msg'] = $esynI18N['changes_saved'];
		}
		else
		{
			$out['msg'] = $esynI18N['params_wrong'];
			$out['error'] = true;
		}
	}

	if ('sendemail' == $_POST['action'])
	{
		$where = $esynAdmin->convertIds('id', $_POST['ids']);
		$newsletters = $esynAdmin->all("*", $where);

		if ($newsletters)
		{
			$mail = $esynAdmin->mMailer;

			foreach($newsletters as $newsletter)
			{
				$replace = array(
		            "realname" => $newsletter['realname'],
		            "key" => $newsletter['sec_key'],
		        );
		        $mail->add_replace($replace);

		        $mail->AddAddress($newsletter['email']);
				$mail->Send('newsletter_confirm');
			}

			$out['error'] = false;
			$out['msg'] = $esynI18N['confirmation_resent'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynI18N['params_wrong'];
		}
	}
	
	$esynAdmin->resetTable();
	
	echo esynUtil::jsonEncode($out);
	exit;	
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
			$order = " ORDER BY `{$sort}` {$dir}";
		}

		if (isset($_GET['status']) && in_array($_GET['status'], array('active', 'approval', 'unconfirmed')))
		{
			$where[] = "`status` = :status";
			$values['status'] = $_GET['status'];
		}

		if (isset($_GET['realname']) && !empty($_GET['realname']))
		{
			$where[] = "`realname` LIKE :realname";
			$values['realname'] = '%'.$_GET['realname'].'%';
		}

		if (isset($_GET['email']) && !empty($_GET['email']))
		{
			$where[] = "`email` = :email";
			$values['email'] = $_GET['email'];
		}

		if (isset($_GET['id']) && !empty($_GET['id']))
		{
			$where[] = "`id` = :id";
			$values['id'] = (int)$_GET['id'];
		}

		if (empty($where))
		{
			$where[] = '1=1';
			$values = array();
		}

		$where = implode(" AND ", $where);

		$esynAdmin->setTable('newsletter');
		$out['total'] = $esynAdmin->one("COUNT(*)", $where, $values);
		$out['data'] = $esynAdmin->all("*, if (`status` = 'unconfirmed', `id`, 0) `sendemail` ", $where.$order, $values, $start, $limit);
		$esynAdmin->resetTable();
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gNoBc	= false;

if (isset($_GET['do']) && 'add' == $_GET['do'])
{
	$gBc[1]['title'] = $esynI18N['manage_subscribers'];
	$gBc[1]['url'] = 'controller.php?plugin=mailer';

	$gBc[2]['title'] = $esynI18N['add_subscriber'];
	$gTitle = $gBc[2]['title'];
}
else
{
	$gBc[1]['title'] = $esynI18N['manage_subscribers'];
	$gBc[1]['url'] = 'index.php.php';
	$gTitle	= $gBc[1]['title'];
}

$actions[] = array("url" => "controller.php?plugin=mailer&amp;do=add", "icon" => "add.png", "label" => $esynI18N['add']);
$actions[] = array("url" => "controller.php?plugin=mailer&amp;file=manage-mailer", "icon" => "edit.png", "label" => $esynI18N['manage_mailer']);
$actions[] = array("url" => "controller.php?plugin=mailer", "icon" => "view.png", "label" => $esynI18N['view_all']);

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'manage_subscribe.tpl');