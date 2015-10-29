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

define('IA_REALM', "faq_cat");

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
	$new_faq_cat	= array();

	$new_faq_cat['lang']		= IA_LANGUAGE;
	$new_faq_cat['title']	= $_POST['title'];
	$new_faq_cat['description']		= strip_tags(trim($_POST['description']));
	$new_faq_cat['status']		= isset($_POST['status']) && !empty($_POST['status']) && in_array($_POST['status'], array('active', 'inactive')) ? $_POST['status'] : 'inactive';
	
	if (!empty($_POST['lang']) && array_key_exists($_POST['lang'], $esynAdmin->mLanguages))
	{
		$new_faq_cat['lang'] = $_POST['lang'];
	}

	if (!utf8_is_valid($new_faq_cat['title']))
	{
		$new_faq_cat['title'] = utf8_bad_replace($new_faq_cat['title']);
	}

	if (!utf8_is_valid($new_faq_cat['description']))
	{
		$new_faq_cat['description'] = utf8_bad_replace($new_faq_cat['description']);
	}
	
	if (empty($new_faq_cat['title']))
	{
		$error = true;
		$msg[] = $esynI18N['error_title'];
	}
	
	if (isset($_GET['do']) && 'edit' != $_GET['do'])
	{
		$esynAdmin->setTable("faq_categories");
		if ($esynAdmin->exists("`title` LIKE '{$new_faq_cat['title']}'"))
		{
			$error = true;
			$msg[] = $esynI18N['category_exists'];
		}
		$esynAdmin->resetTable();
	}
	
	if (!$error)
	{
		if (isset($_GET['do']) && 'edit' == $_GET['do'])
		{
			$new_faq_cat['id'] = (int)$_GET['id'];

			$esynAdmin->setTable("faq_categories");
			$esynAdmin->update($new_faq_cat);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['changes_saved'];
		}
		else
		{
			$esynAdmin->setTable("faq_categories");
			$esynAdmin->insert($new_faq_cat);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['faq_cat_added'];
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
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$out = array('data' => '', 'total' => 0);
		$order = isset($_GET['sort']) ? "ORDER BY `{$_GET['sort']}` {$_GET['dir']}" : '';
		
		$esynAdmin->setTable("faq_categories");
		$stripfield = 'description';

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`, `id` `remove`", "1=1 ".$order, $start, $limit);
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
			$esynAdmin->delete($esynAdmin->convertIds('category', $_POST['ids']));
			$esynAdmin->resetTable();
			
			$esynAdmin->setTable("faq_categories");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['category'].' '.$esynI18N['deleted'];
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

			$esynAdmin->setTable("faq_categories");
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

$gBc[1]['title'] = $gTitle;
$gBc[1]['url'] = 'controller.php?plugin=faq&file=categories';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[1]['title'] = $esynI18N['manage_faq'];
		$gBc[1]['url'] = 'controller.php?plugin=faq&file=categories';
	
		$gBc[2]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_faq_cat'] : $esynI18N['add_faq_cat'];
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

	$esynAdmin->setTable("faq_categories");
	$faq_category = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();

	$esynSmarty->assign('faq_category', $faq_category);
}

if (isset($_GET['do']))
{
	$esynSmarty->display(IA_PLUGIN_TEMPLATE.'category.tpl');
}else{
	$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');
}
?>
