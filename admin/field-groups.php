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

define('IA_REALM', "field_groups");

esynUtil::checkAccess();

if (isset($_POST['save']))
{
	$error = false;
	$msg = array();

	$field_group = array();

	$esynAdmin->startHook('adminAddFieldGroupValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	// name field group
	if ('edit' != $_GET['do'])
	{
		$token = esynUtil::getNewToken(6);
		$field_group['name'] = 'fgroup_' . $token;
	}

	// add field group titles
	$field_group['title'] = $_POST['title'];

	foreach ($esynAdmin->mLanguages as $code => $lang)
	{
		if (!empty($field_group['title'][$code]))
		{
			if (!utf8_is_valid($field_group['title'][$code]))
			{
				$field_group['title'][$code] = utf8_bad_replace($field_group['title'][$code]);
			}
		}
		else
		{
			$error = true;
			$msg[] = str_replace('{lang}', $lang, _t('field_group_title_empty'));
		}
	}

	// show on pages
	$field_group['pages'] = isset($_POST['pages']) && !empty($_POST['pages']) ? $_POST['pages'] : '';

	if (!empty($field_group['pages']))
	{
		$field_group['pages'] = implode(',', $field_group['pages']);
	}

	if (!$error)
	{
		$field_group['collapsible'] = (int)$_POST['collapsible'];
		$field_group['collapsed'] = (int)$_POST['collapsed'];

		$titles = $field_group['title'];

		unset($field_group['title']);

		if ('edit' == $_POST['do'])
		{
			$groupId = (int)$_GET['id'];
			$groupName = $_POST['name'];

			$esynAdmin->setTable('field_groups');
			$esynAdmin->update($field_group, "`id` = :id", array('id' => $groupId));
			$esynAdmin->resetTable();

			// update titles to language
			$esynAdmin->setTable('language');
			foreach ($esynAdmin->mLanguages as $code => $lang)
			{
				$insert = array();

				$insert['key'] = 'field_group_title_' . $_POST['name'];
				$insert['value'] = $titles[$code];
				$insert['lang'] = $lang;
				$insert['category'] = 'common';
				$insert['code'] = $code;

				if ($esynAdmin->exists("`key` = '{$insert['key']}' AND `code` = '{$code}'"))
				{
					$esynAdmin->update(array('value' => $insert['value']), "`key` = '{$insert['key']}' AND `code` = '{$insert['code']}'");
				}
				else
				{
					$esynAdmin->insert($insert);
				}
			}
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['changes_saved'];
		}
		else
		{
			$groupName = $field_group['name'];

			// add group
			$esynAdmin->setTable('field_groups');

			$max_order = $esynAdmin->one('MAX(`order`) + 1');
			$field_group['order'] = $max_order;

			$groupId = $esynAdmin->insert($field_group);
			$esynAdmin->resetTable();

			// add titles to language
			$esynAdmin->setTable('language');
			foreach ($esynAdmin->mLanguages as $code => $lang)
			{
				$insert = array();

				$insert['key'] = 'field_group_title_' . $field_group['name'];
				$insert['value'] = $titles[$code];
				$insert['lang'] = $lang;
				$insert['category'] = 'common';
				$insert['code'] = $code;

				$esynAdmin->insert($insert);
			}
			$esynAdmin->resetTable();

			$msg[] = _t('field_groups_added');
		}

		// update field group
		if ($_POST['fields'])
		{
			$esynAdmin->setTable('listing_fields');
			$fields_gr = $esynAdmin->all('`name`, `group`', "`adminonly` = '0' ORDER BY `order`");

			$fields = array();
			foreach ($fields_gr as $key => $value)
			{
				if (!in_array($value['name'], $_POST['fields']))
				{
					$fields[$key]['name'] = $value['name'];
					$fields[$key]['group'] = $value['group'];
				}
			}

			$where = "`name` IN ('" . implode("','", $_POST['fields']) . "')";
			$esynAdmin->update(array('group' => $groupName), $where);

			if ($fields)
			{
				foreach ($fields as $key => $value)
				{
					$group = ($groupName == $value['group']) ? '': $value['group'];

					$esynAdmin->update(array('group' => $group), "`name` = '{$value['name']}'");
				}
			}

			$esynAdmin->resetTable();
		}

		$esynAdmin->mCacher->clearAll('lang', true);

		esynMessages::setMessage($msg, $error);
		esynUtil::reload(array('do' => null, 'id' => null));
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

		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		if (!empty($sort) && !empty($dir))
		{
			$order = " ORDER BY `{$sort}` {$dir}";
		}

		$esynAdmin->setTable('field_groups');
		$out['total'] = $esynAdmin->one('COUNT(*)');
		$out['data'] = $esynAdmin->all("*, `name` `edit`, '1' `remove`", "1=1 {$order}", array(), $start, $limit);
		$esynAdmin->resetTable();

		if (!empty($out['data']))
		{
			foreach ($out['data'] as $key => $item)
			{
				$out['data'][$key]['title'] = _t('field_group_title_' . $item['name']);
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
	$out = array('msg' => array(), 'error' => false);

	if ('remove' == $_POST['action'])
	{
		if (!isset($_POST['ids']) || empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'][] = 'IDS are wrong.';
		}

		if (!$out['error'])
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable('field_groups');
			$field_groups = $esynAdmin->onefield('`name`', $where);
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			if (!empty($field_groups))
			{
				foreach ($field_groups as $group)
				{
					// remove language
					$esynAdmin->setTable('language');
					$esynAdmin->delete("`key` = 'field_group_title_{$group}'");
					$esynAdmin->resetTable();

					// remove relations
					$esynAdmin->setTable('field_relations');
					$esynAdmin->delete("`group` = '{$group}'");
					$esynAdmin->resetTable();

					// reset listing field group
					$esynAdmin->setTable('listing_fields');
					$esynAdmin->update(array('group' => ''), "`group` = '{$group}'");
					$esynAdmin->resetTable();
				}
			}

			$out['msg'][] = count($_POST['ids']) > 1 ? _t('field_groups_removed') : _t('field_group_removed');
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
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable('field_groups');
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$actions[] = array(
	'url' => 'controller.php?file=field-groups&amp;do=add',
	'icon' => 'add.png',
	'label' => $esynI18N['create'],
);

$actions[] = array(
	'url' => 'controller.php?file=field-groups',
	'icon' => 'view.png',
	'label' => $esynI18N['view'],
);

$gTitle = _t('manage_field_groups');

$gBc[0]['title'] = _t('manage_field_groups');
$gBc[0]['url'] = 'controller.php?file=field-groups';

if (isset($_GET['do']))
{
	if ('add' == $_GET['do'])
	{
		$gTitle = _t('add_field_group');
	}

	if ('edit' == $_GET['do'])
	{
		$gTitle = _t('edit_field_group');
	}

	$gBc[2]['title'] = $gTitle;
}

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']))
{
	$esynAdmin->setTable('listing_fields');
	$fields = $esynAdmin->all('`id`, `name`, `group`', "`adminonly` = '0' ORDER BY `order`");
	$esynAdmin->resetTable();

	if ('edit' == $_GET['do'])
	{
		$id = (int)$_GET['id'];

		// get field group
		$esynAdmin->setTable('field_groups');
		$group = $esynAdmin->row('*', "`id` = '{$id}'");
		$esynAdmin->resetTable();

		// get pages
		$group['pages'] = explode(',', $group['pages']);

		// get titles
		$esynAdmin->setTable('language');
		foreach ($esynAdmin->mLanguages as $code => $lang)
		{
			$group['title'][$code] = $esynAdmin->one('value', "`key` = 'field_group_title_{$group['name']}' AND `code` = '{$code}'");
		}
		$esynAdmin->resetTable();

		$esynSmarty->assign('group', $group);
	}

	$esynSmarty->assign('fields', $fields);
}

$esynSmarty->display('field-groups.tpl');
