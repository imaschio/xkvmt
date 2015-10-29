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

define('IA_REALM', "faq");

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

	$error		= false;
	$msg		= '';
	$new_faq	= array();

	$new_faq['question']	= isset($_POST['question']) ? $_POST['question'] 		: '';
	$new_faq['answer']		= isset($_POST['answer']) 	? $_POST['answer'] 			: '';
	$new_faq['category']	= isset($_POST['category']) ? (int)$_POST['category'] 	: 0;
	$new_faq['status']		= isset($_POST['status']) && !empty($_POST['status']) && in_array($_POST['status'], array('active', 'inactive')) ? $_POST['status'] : 'inactive';
	$_POST['lang']			= isset($_POST['lang'])		? $_POST['lang']			: $_POST['lang2'];
	
	if (!empty($_POST['lang']) && array_key_exists($_POST['lang'], $esynAdmin->mLanguages))
	{
		$new_faq['lang'] = $_POST['lang'];
	}

	if (!utf8_is_valid($new_faq['question']))
	{
		$new_faq['question'] = utf8_bad_replace($new_faq['question']);
	}

	if (!utf8_is_valid($new_faq['answer']))
	{
		$new_faq['answer'] = utf8_bad_replace($new_faq['answer']);
	}
	
	if (empty($new_faq['question']))
	{
		$error = true;
		$msg[] = $esynI18N['error_question'];
	}

	if (empty($new_faq['answer']))
	{
		$error = true;
		$msg[] = $esynI18N['error_answer'];
	}
	
	if (!$error)
	{
		if (isset($_GET['do']) && 'edit' == $_GET['do'])
		{
			$new_faq['id'] = (int)$_GET['id'];

			$esynAdmin->setTable("faq");
			$esynAdmin->update($new_faq);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['changes_saved'];
		}
		else
		{
			$esynAdmin->setTable("faq");
			$esynAdmin->insert($new_faq);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['faq_added'];
		}
		
		$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

		esynMessages::setMessage($msg, $error);

		esynUtil::reload(array("do" => $do));
	}

	esynMessages::setMessage($msg, $error);
}


if (isset($_GET['action']))
{

	
	if ('get' == $_GET['action'])
	{
		$start 	= (int)$_GET['start'];
		$limit 	= (int)$_GET['limit'];
		$id		= $_GET['id'] ? (int)$_GET['id'] : '-1';

		$out = array('data' => '', 'total' => 0);
		$order = isset($_GET['sort']) ? "ORDER BY `{$_GET['sort']}` {$_GET['dir']}" : '';
		
		$esynAdmin->setTable("faq");
		$stripfield = 'answer';
		
		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", "`category` = '{$id}' ".$order, $start, $limit);
		$esynAdmin->resetTable();
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}
	else
	{
		$out['data'] = esynSanitize::applyFn($out['data'], "striptags", array($stripfield));
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{


	if ('remove' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => false);

		if (empty($_POST['ids']) || !is_array($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}

		if (!$out['error'])
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("faq");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['faq'].' '.$esynI18N['deleted'];
		}
	}

	if ('update' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => false);

		if (empty($_POST['field']) || empty($_POST['value']) || empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}

		if (!$out['error'])
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("faq");
			$esynAdmin->update(array($_POST['field'] => $_POST['value']), $where);
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

$gNoBc = false;

$gTitle = $esynI18N['manage_faq'];

$gBc[1]['title'] = $esynI18N['manage_faq'];
$gBc[1]['url'] = 'controller.php?plugin=faq';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[1]['title'] = $esynI18N['manage_faq'];
		$gBc[1]['url'] = 'controller.php?plugin=faq';
	
		$gBc[2]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_faq'] : $esynI18N['add_faq'];
		$gTitle = $gBc[2]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?plugin=faq&amp;do=add", "icon" => "add.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?plugin=faq&amp;file=categories&amp;do=add", "icon" => "create_category.png", "label" => $esynI18N['create_category']),
	array("url" => "controller.php?plugin=faq", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once(IA_ADMIN_HOME.'view.php');

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$id = (int)$_GET['id'];

	$esynAdmin->setTable("faq");
	$faq = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();

	$esynSmarty->assign('faq', $faq);
}
if (isset($_GET['do']) && ('add' == $_GET['do'] || 'edit' == $_GET['do']))
{
	$esynAdmin->setTable("faq_categories");
	$faq_categories = $esynAdmin->all("`title`, `id`, `lang`");
	$esynAdmin->resetTable();

	$esynSmarty->assign('faq_categories', $faq_categories);
}

if (isset($_GET['do']))
{
	$esynSmarty->display(IA_PLUGIN_TEMPLATE.'faq.tpl');
}else{
	$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');
}
?>
