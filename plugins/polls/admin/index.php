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

define('IA_REALM', "polls");

$esynAdmin->loadPluginClass("Polls", "polls", 'esyn', true);
$esynPolls = new esynPolls();

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$id = (int)$_GET['id'];

	$esynAdmin->setTable("polls");
	$one_poll = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();

	$esynAdmin->setTable("poll_options");
	$one_poll['options'] = $esynAdmin->all("*", "`poll_id` = '{$id}'");
	$esynAdmin->resetTable();

	if (!empty($one_poll['options']))
	{
		$one_poll['options'] = array_reverse($one_poll['options']);
	}

	$esynAdmin->setTable("polls_categories");
	$categories = $esynAdmin->all("*", "`poll_id` = '{$id}'");
	$esynAdmin->resetTable();

	if (is_array($categories))
	{
		foreach ($categories as $cat)
		{
			$categories_out[] = $cat['category_id'];
		}
		$categories_out = implode("|", $categories_out);
	}
}

if (isset($_POST['save']))
{
	$msg = array();
	$error = false;

	if (!defined('IA_NOUTF'))
	{
		require_once(IA_CLASSES.'esynUtf8.php');

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$lang = IA_LANGUAGE;
	$title = $_POST['title'];

	if (!empty($_POST['lang']) && array_key_exists($_POST['lang'], $esynAdmin->mLanguages))
	{
		$lang = $_POST['lang'];
	}

	if (!utf8_is_valid($title))
	{
		$title = utf8_bad_replace($title);
	}

	if (!$title)
	{
		$error = true;
		$msg[] = $esynI18N['error_title'];
	}

	$expires = $_POST['expires'];
	$date = date("Y-m-d H:i:s");

	$options = array();
	if (empty($_POST['options']) || !is_array($_POST['options']))
	{
		$error = true;
		$msg[] = $esynI18N['error_poll_options_required'];
	}
	else
	{
		$_POST['options'] = array_map("trim",$_POST['options']);
		$_POST['options'] = array_unique($_POST['options']);
		for($i = 0; $i < count($_POST['options']); $i++)
		{
			if (empty($_POST['options'][$i]))
			{
				unset($_POST['options'][$i]);
				continue;
			}

			if (!utf8_is_valid($_POST['options'][$i]))
			{
				$_POST['options'][$i] = utf8_bad_replace($_POST['options'][$i]);
			}
		}
		if ($_GET['do'] == 'add' && count($_POST['options']) < 2)
		{
			$error = true;
			$msg[] = $esynI18N['error_poll_options_required'];
		}
		else
		{
			$options = $_POST['options'];
		}
	}

	$recursive  = 0;
	if (isset($_POST['recursive']))
	{
		$recursive = 1;
	}

	$categories = isset($_POST['categories']) && !empty($_POST['categories']) ? explode("|", $_POST['categories']) : array();

	if (!empty($categories))
	{
		$categories = array_map("intval", $categories);
		$categories = array_unique($categories);
	}

	$status = isset($_POST['status']) && 'active' == $_POST['status'] ? 'active' : 'inactive';

	if (!$error)
	{
		if ($_GET['do'] == 'add')
		{
			$f = array(
				"lang"			=> $lang,
				"recursive"		=> $recursive,
				"title"			=> $title,
				"expires"		=> $expires,
				"date"			=> $date,
				"status" 		=> $status,
				"options" 		=> $options,
				"categories"	=> $categories
			);

			$esynPolls->insert($f);

			if ($esynPolls->exists("`expires` > NOW()"))
			{
				$esynConfig->setConfig("polls_exist", "1", true);
			}
			else
			{
				$esynConfig->setConfig("polls_exist", "", true);
			}

			$msg[] = $esynI18N['poll_added'];
		}
		elseif ($_GET['do'] == 'edit')
		{
			$newoptions = $options;
			$options = array();
			$_POST['existant'] = array_map("trim",$_POST['existant']);
			$_POST['existant'] = array_unique($_POST['existant']);
			foreach($_POST['existant'] as $id=>$t)
			{
				$id = (int)$id;
				$id = (string)$id;
				if (!utf8_is_valid($_POST['existant'][$id]))
				{
					$_POST['options'][$id] = utf8_bad_replace($_POST['existant'][$id]);
				}
			}

			// no options (not at least 2 options)
			if (count($_POST['existant']) < 1 && count($newoptions) < 1)
			{
				$error = true;
				$msg[] = $esynI18N['error_poll_options_required'];
			}
			else
			{
				$options = $_POST['existant'];
			}

			// preserve the state of the categories it is used in AJAX tree
			/*if (!empty($_POST['categories_state']))
			{
				$catstate = explode(",",$_POST['categories_state']);
				$categories = array_unique(array_merge($catstate,$categories));
			}*/

			$f = array(
				"id"			=> (int)$_GET['id'],
				"recursive"		=> $recursive,
				"title"			=> $title,
				"expires"		=> $expires,
				"lang"			=> $lang,
				"date" 			=> $date,
				"status"		=> $status,
				"newoptions"	=> $newoptions,
				"options"		=> $options,
				"categories"	=> $categories
			);

			$esynPolls->update($f);

			if ($esynPolls->exists("`expires` > NOW()"))
			{
				$esynConfig->setConfig("polls_exist", "1", true);
			}
			else
			{
				$esynConfig->setConfig("polls_exist", "", true);
			}

			$msg[] = $esynI18N['changes_saved'];
		}
		if (!$error)
		{
			esynMessages::setMessage($msg, $error);
			esynUtil::reload(array("do"=>null));
		}
	}
}

if (isset($_GET['action']))
{


	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];
		$order = isset($_GET['sort']) ? "ORDER BY `{$_GET['sort']}` {$_GET['dir']}" : '';

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("polls");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", "1=1 ".$order, $start, $limit);

		$esynAdmin->resetTable();
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}
	else
	{
		$out['data'] = esynSanitize::applyFn($out['data'], "striptags");
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{


	$out = array('msg' => 'Unknown error', 'error' => false);

	if ('remove' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("polls");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['polls'].' '.$esynI18N['deleted'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynI18N['params_wrong'];
		}
	}

	if ('reset' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('poll_id', $_POST['ids']);

			// update votes stats
			$esynAdmin->setTable("poll_options");
			$esynAdmin->update(array("votes" => 0), $where);
			$esynAdmin->resetTable();

			// empty clicks table
			$esynAdmin->setTable("poll_clicks");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynI18N['params_wrong'];
		}
	}

	if ('update' == $_POST['action'])
	{
		if (!empty($_POST['field']) && !empty($_POST['value']) && !empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("polls");
			$esynAdmin->update(array($_POST['field'] => $_POST['value']), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynI18N['params_wrong'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gNoBc = false;

$gTitle = $esynI18N['manage_polls'];

$gBc[1]['title'] = $esynI18N['manage_polls'];
$gBc[1]['url'] = 'controller.php?plugin=polls';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[2]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_poll'] : $esynI18N['add_poll'];
		$gTitle = $gBc[2]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?plugin=polls&amp;do=add", "icon" => "add.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?plugin=polls", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once(IA_ADMIN_HOME.'view.php');

$esynSmarty->assign('categories_out', isset($categories_out) ? $categories_out : null);
$esynSmarty->assign('category', isset($category) ? $category : null);
$esynSmarty->assign('one_poll', isset($one_poll) ? $one_poll : null);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');