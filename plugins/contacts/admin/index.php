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

define('IA_REALM', "contacts");

if (isset($_POST['send']))
{
	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error = false;
	$msg = '';

	if (!utf8_is_valid($_POST['subject']))
	{
		$_POST['subject'] = utf8_bad_replace($_POST['subject']);
	}
	$subject = trim($_POST['subject']);

	if (!utf8_is_valid($_POST['body']))
	{
		$_POST['body'] = utf8_bad_replace($_POST['body']);
	}
	$body = trim($_POST['body']);

	$to = $_POST['to'];
	$from = $_POST['from'];

	if (empty($subject))
	{
		$error = true;
		$msg[] = $esynI18N['subject_incorrect'];
	}

	if (!esynValidator::isEmail($to))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}

	if (!esynValidator::isEmail($from))
	{
		$error = true;
		$msg[] = $esynI18N['from_incorrect'];
	}

	if (!$error)
	{
		$mail = $esynAdmin->mMailer;
		$mail->From 	= $from;
		$mail->FromName = $from;
		$mail->Subject	= $subject;
		$mail->Body = $body;
		$mail->AddAddress($to);

		$r = $mail->Send();

		$msg[] = $esynI18N['email_sent'];
	}
	$do = null;
	esynMessages::setMessage($msg, $error);

	if (isset($_POST['go2accounts']) && 'true' == $_POST['go2accounts'])
		esynUtil::go2(IA_ADMIN_URL . 'controller.php?file=accounts');
	else
		esynUtil::reload(array("do" => $do));
}

if (isset($_GET['action']))
{

	$start = (int)$_GET['start'];
	$limit = (int)$_GET['limit'];

	if ('get' == $_GET['action'])
	{
		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		if (!empty($sort) && !empty($dir))
		{
			$order = " ORDER BY `{$sort}` {$dir} ";
		}

		$where = "1=1";

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("contacts");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", $where.$order, array(), $start, $limit);

		$out['data'] = esynSanitize::applyFn($out['data'], "html");

		$esynAdmin->resetTable();
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

	if ('remove' == $_POST['action'])
	{
		if (empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}
		else
		{
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($_POST['ids']))
			{
				foreach($_POST['ids'] as $id)
				{
					$ids[] = (int)$id;
				}
			}
			else
			{
				$ids = (int)$_POST['ids'];
			}

			$esynAdmin->setTable('contacts');
			$esynAdmin->delete("`id` IN('".join("','", $ids)."')");
			$esynAdmin->resetTable();

			$out['msg'] = (count($ids) > 1) ? $esynI18N['contacts_deleted'] : $esynI18N['contact_deleted'];
		}
	}

	/*
	 * Update grid field
	 */
	if ('update' == $_POST['action'])
	{
		$field = $_POST['field'];
		$value = $_POST['value'];

		if (empty($field) || empty($value) || empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}
		else
		{
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($_POST['ids']))
			{
				foreach($_POST['ids'] as $id)
				{
					$ids[] = (int)$id;
				}

				$where = "`id` IN ('".join("','", $ids)."')";
			}
			else
			{
				$id = (int)$_POST['ids'];

				$where = "`id` = '{$id}'";
			}

			$esynAdmin->setTable("contacts");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}
/*
 * ACTIONS
 */

$gTitle = $esynI18N['manage_contacts'];

$gBc[1]['title'] = $esynI18N['manage_contacts'];
$gBc[1]['url'] = 'controller.php?plugin=contacts';

if (isset($_GET['do']))
{
	switch ($_GET['do'])
	{
		case 'compose':
			$gBc[2]['title'] = $esynI18N['compose_email'];
			break;
		case 'reply':
			$gBc[2]['title'] = $esynI18N['reply_member'];
			break;
		case 'contact':
			$gBc[2]['title'] = $esynI18N['contact_account'];
			break;
		default:
			$gBc[2]['title'] = $esynI18N['compose_email'];
			break;
	}
	$gTitle = $gBc[2]['title'];
}

$actions = array(
	array("url" => "controller.php?plugin=contacts&amp;do=compose", "icon" => "add.png", "label" => $esynI18N['compose_email']),
	array("url" => "controller.php?plugin=contacts", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']) && 'reply' == $_GET['do'])
{
	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$id = (int)$_GET['id'];

	$esynAdmin->setTable("contacts");
	$contact = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();

	$body = preg_split("#(\r|\n){1}#", esynSanitize::html($contact['reason']));
	$body = implode("\r\n&gt;&gt; ", $body);

	$esynSmarty->assign('contact', $contact);
	$esynSmarty->assign('body', $body);
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');

