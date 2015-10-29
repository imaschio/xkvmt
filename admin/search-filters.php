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

define('IA_REALM', "search_filters");

if (isset($_POST['save']))
{
	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error		= false;
	$msg		= '';
	$new_filter	= array();

	$new_filter['title']	= $_POST['title'];
	$new_filter['fields']	= $_POST['filter_content'];
	$new_filter['status']	= isset($_POST['status']) && !empty($_POST['status']) && in_array($_POST['status'], array('active', 'inactive')) ? $_POST['status'] : 'inactive';
	$new_filter['category_id']	= (int)$_POST['category_id'];

	if ((int)$_POST['recursive'] && $new_filter['category_id'] != '0')
	{
		$esynAdmin->setTable('flat_structure');
		$childs[] = $esynAdmin->onefield('`category_id`', "`parent_id` = {$new_filter['category_id']}");
		$esynAdmin->resetTable();

		$new_filter['childs'] = implode(',', $childs);
	}

	if (!utf8_is_valid($new_filter['title']))
	{
		$new_filter['title'] = utf8_bad_replace($new_filter['title']);
	}
	if (empty($new_filter['title']))
	{
		$error = true;
		$msg[] = $esynI18N['error_title'];
	}

	if (!$error)
	{
		if (isset($_GET['do']) && 'edit' == $_GET['do'])
		{
			$new_filter['id'] = (int)$_GET['id'];

			$esynAdmin->setTable("search_filters");
			$esynAdmin->update($new_filter);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['changes_saved'];
		}
		else
		{
			$esynAdmin->setTable("search_filters");
			$esynAdmin->insert($new_filter);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['filter_added'];
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
		$order = isset($_GET['sort']) ? "ORDER BY `{$_GET['sort']}` {$_GET['dir']}" : '';

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("search_filters");

		$out['data'] = $esynAdmin->all("*, `id` `edit`", "1=1 ".$order, array(), $start, $limit);
		$out['total'] = $esynAdmin->one("COUNT(*)");

		$esynAdmin->resetTable();
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}
	else
	{
		$out['data'] = esynSanitize::applyFn($out['data'], "striptags", array('body'));
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

			$esynAdmin->setTable("search_filters");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['filter'].' '.$esynI18N['deleted'];
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

			$esynAdmin->setTable("search_filters");
			$esynAdmin->update(array($_POST['field'] => $_POST['value']), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_filters'];

$gBc[0]['title'] = $esynI18N['manage_filters'];
$gBc[0]['url'] = 'controller.php?file=search-filters';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[0]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_filter'] : $esynI18N['add_filter'];
		$gTitle = $gBc[0]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?file=search-filters&amp;do=add", "icon" => "add.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?file=search-filters", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']))
{
	$esynAdmin->factory("ListingField");

	$fields = $esynListingField->all("`id`, `name`, `group`", "`type` IN ('checkbox', 'combo', 'radio', 'image', 'storage') ORDER BY `order`");

	$esynAdmin->setTable('field_groups');
	$field_groups = $esynAdmin->onefield('`name`', "1 ORDER BY `order`");
	$esynAdmin->resetTable();

	$field_groups[] = 'non_group';

	$groups = array();
	$root = array(
		'id' => 'node',
		'leaf' => false,
		'text' => 'Fields'
	);

	foreach ($field_groups as $k => $value)
	{
		$groups[$k]['id'] = $value;
		//$groups[$k]['text'] = $value;
		$groups[$k]['text'] = $esynI18N['field_group_title_' . $value];
		$groups[$k]['leaf'] = false;

		foreach ($fields as $key => $field)
		{
			$field['id'] = $field['name'];
			$field['leaf'] = true;
			$field['text'] = $esynI18N['field_' . $field['name']];
			if ($field['group'] == $value)
			{
				$groups[$k]['children'][] = $field;
			}
			elseif (!$field['group'] && $value == 'non_group')
			{
				$groups[$k]['children'][] = $field;
			}
		}
	}
	$root['children'] = $groups;

	$fields = esynUtil::jsonEncode($root);
	unset($groups);

	$filter_menu = array(
		'id'		=> '',
		'text'		=> 'New Filter',
		'leaf'		=> false,
		'children'	=> array(),
	);

	$esynSmarty->assign('fields', $fields);
	$esynSmarty->assign('filter_menu', esynUtil::jsonEncode($filter_menu));

	if ('edit' == $_GET['do'])
	{
		$id = (int)$_GET['id'];

		$esynAdmin->setTable("search_filters");
		$filter = $esynAdmin->row("*", "`id` = '{$id}'");
		$esynAdmin->resetTable();

		$category_id = isset($_POST['category_id']) && ctype_digit($_POST['category_id']) ? $_POST['category_id'] : $filter['category_id'];

		$esynAdmin->setTable("categories");
		$category = $esynAdmin->row("*", "`id` = '{$category_id}'");
		$esynAdmin->resetTable();

		$esynSmarty->assign('filter_menu', $filter['fields']);
		$esynSmarty->assign('filter', $filter);
		$esynSmarty->assign('category', $category);
	}
}

$esynSmarty->display('search-filters.tpl');
