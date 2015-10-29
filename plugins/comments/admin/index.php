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

define('IA_REALM', "comments");

if (isset($_POST['edit_comments']) && isset($_POST['id']))
{
	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$comment = array();

	$comment['id'] = (int)$_POST['id'];
	$comment['author'] = ($_POST['author']);
	$comment['url'] = ($_POST['url']);
	$comment['body'] = $_POST['body'];
	$comment['item_id'] = (int)$_POST['item_id'];
	$comment['item'] = $_POST['item'];

	if (utf8_is_valid($comment['author']))
	{
		$comment['author'] = utf8_bad_replace($comment['author']);
	}

	require_once(IA_INCLUDES.'safehtml/safehtml.php');
	$safehtml = new safehtml();
	$comment['body'] = $safehtml->parse($comment['body']);

	if (isset($_POST['status']))
	{
		$comment['status'] = in_array($_POST['status'], array('active', 'inactive')) ? $_POST['status'] : 'inactive';
	}

	if (isset($_POST['email']) && esynValidator::isEmail($_POST['email']))
	{
		$comment['email'] = $_POST['email'];
	}

	$esynAdmin->setTable("comments");
	$esynAdmin->update($comment);
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

		$dir = isset($_GET['dir']) ? $_GET['dir'] : '';
		$sort = isset($_GET['sort']) ? 'ORDER BY `' . $_GET['sort'] . '` ' . $dir : 'ORDER BY `date` DESC ';

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("comments");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", "1=1 " . $sort, array(), $start, $limit);

		$esynAdmin->resetTable();

		if ($out['data'])
		{
			foreach($out['data'] as $key => $comment)
			{
				$esynAdmin->setTable($comment['item']);
				$out['data'][$key]['item_title'] = $esynAdmin->one("title", "`id` = '{$comment['item_id']}'");
				$esynAdmin->resetTable();
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
	$esynAdmin->loadClass("JSON");

	$json = new Services_JSON();

	if ('remove' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => true);

		if (!empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("comments");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = (count($comments) > 1) ? $esynI18N['comments'] : $esynI18N['comment'];
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
		$out = array('msg' => 'Unknown error', 'error' => true);

		$field = ($_POST['field']);
		$value = ($_POST['value']);

		if (!empty($field) && !empty($value) && !empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("comments");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
			$out['error'] = false;
		}
		else
		{
			$out['msg'] = 'Wrong parametes';
			$out['error'] = true;
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}


$gTitle = $esynI18N['manage_comments'];

$gBc[0]['title'] = $esynI18N['manage_plugins'];
$gBc[0]['url'] = 'controller.php?file=plugins';

$gBc[1]['title'] = $esynI18N['manage_comments'];
$gBc[1]['url'] = 'controller.php?plugin=comments';

if (isset($_GET['do']))
{
	if (('edit' == $_GET['do']))
	{
		$gBc[2]['title'] = $esynI18N['edit_comment'];
		$gTitle = $gBc[2]['title'];
	}
}

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$id = (int)$_GET['id'];

	$esynAdmin->setTable("comments");
	$comment = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();

	$esynSmarty->assign('comment', $comment);
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');