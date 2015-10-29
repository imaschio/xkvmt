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

define('IA_REALM', "guestbook");
define('IA_CURRENT_PLUGIN_URL', 'controller.php?plugin='.IA_CURRENT_PLUGIN);

$id = false;
if (isset($_GET['id']) && !preg_match('/\D/', $_GET['id']))
{
	$id = (int)$_GET['id'];
}

function get_post($name = '')
{
	return isset($_POST[$name]) ? $_POST[$name] : '';
}

$message = array(
	"author_name" => '',
	"author_url" => '',
	"body" => '',
	"status" => 'active',
	"email" => '',
);

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$id = (int)$_GET['id'];

	$esynAdmin->setTable("guestbook");
	$message = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();
}

/*
 * ACTIONS
 */
if (isset($_POST['save']))
{
	if (!defined('IA_NOUTF'))
	{
		require_once(IA_CLASSES.'esynUtf8.php');

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$message = array(
		"author_name" => get_post("author_name"),
		"author_url" => get_post("author_url"),
		"body" => get_post("body"),
		"status" => get_post("status"),
		"email" => get_post("email"),
		"date" => get_post("date"),
	);

	$message['id'] = $id;

	if (utf8_is_valid($message['author_name']))
	{
		$message['author_name'] = utf8_bad_replace($message['author_name']);
	}

	if (!empty($message['status']))
	{
		$message['status'] = in_array($message['status'], array('active', 'approval')) ? $message['status'] : 'approval';
	}

	$message['email'] = (!empty($message['email']) && esynValidator::isEmail($message['email'])) ? $message['email'] : '';

	$esynAdmin->setTable("guestbook");
	$esynAdmin->update($message);
	$esynAdmin->resetTable();

	$msg = $esynI18N['changes_saved'];

	esynMessages::setMessage($msg, false);

	esynUtil::reload(array("do" => null, "id" => null));
}


if (isset($_GET['action']))
{
	$esynAdmin->loadClass("JSON");
	
	$json = new Services_JSON();
	
	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("guestbook");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", "1=1", $start, $limit);
		
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
		if (!empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("guestbook");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = (count($_POST['ids']) > 1) ? $esynI18N['messages'] : $esynI18N['message'];
			$out['msg'] .= ' '.$esynI18N['deleted'];

			$out['error'] = false;
		}
		else
		{
			$out['msg'] = $esynI18N['params_wrong'];
			$out['error'] = true;
		}

	}

	if ('update' == $_POST['action'])
	{
		$field = ($_POST['field']);
		$value = ($_POST['value']);

		if (!empty($field) && !empty($value) && !empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("guestbook");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();
			
			$out['msg'] = $esynI18N['changes_saved'];
			$out['error'] = false;
		}
		else
		{
			$out['msg'] = $esynI18N['params_wrong'];
			$out['error'] = true;
		}
	}
	
	echo esynUtil::jsonEncode($out);
	exit;	
}
/*
 * ACTIONS
 */

$gNoBc = false;

$gTitle = $esynI18N['manage_guestbook'];

$gBc[0]['title'] = $esynI18N['manage_plugins'];
$gBc[0]['url'] = 'controller.php?file=plugins';

$gBc[1]['title'] = $esynI18N['manage_guestbook'];
$gBc[1]['url'] = IA_CURRENT_PLUGIN_URL;

if (isset($_GET['do']))
{
	if (('edit' == $_GET['do']))
	{
		$gTitle = $esynI18N['edit_message'];
		$gBc[2]['title'] = $gTitle;

		$actions = array(
			array("url" => IA_CURRENT_PLUGIN_URL, "icon" => "view.png", "label" => $esynI18N['view']),
		);
	}
}

require_once(IA_ADMIN_HOME.'view.php');

$esynSmarty->assign('message', $message);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');