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

define('IA_REALM', "categories");

esynUtil::checkAccess();

$esynAdmin->factory("Category");

$category_id = isset($_GET['category']) && ctype_digit($_GET['category']) ? $_GET['category'] : 0;
$category['id'] = $category_id;

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

		if (isset($_GET['title']) && !empty($_GET['title']))
		{
			$where[] = "`title` LIKE :title";
			$values['title'] = '%'.$_GET['title'].'%';
		}

		if (isset($_GET['status']) && in_array($_GET['status'], array('active', 'approval')))
		{
			$where[] = " `id` > '0' AND `status` = '{$_GET['status']}'";
		}

		if (empty($where))
		{
			$where[] = "`id` > '0'";
		}

		$where = implode(" AND ", $where);

		$out['total'] = $esynCategory->one("COUNT(*)", $where, $values);
		$out['data'] = $esynCategory->all("*, `id` `edit`", $where.$order, $values, $start, $limit);

		$out['data'] = esynSanitize::applyFn($out['data'], "html");
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
	$out = array('msg' => 'Unknown error', 'error' => false);

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

			$esynAdmin->setTable("categories");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];

			$esynAdmin->mCacher->clearAll('categories');
		}
	}

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
					$esynCategory->delete((int)$id);
				}
			}
			else
			{
				$esynCategory->delete((int)$_POST['ids']);
			}

			$out['msg'] = $esynI18N['changes_saved'];

			$esynAdmin->mCacher->clearAll('categories');
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gBc[0]['title'] = $esynI18N['manage_categories'];
$gBc[0]['url'] = $_SERVER['SCRIPT_NAME'];

$gTitle = $esynI18N['manage_categories'];

$actions = array(
	array("url"	=> "controller.php?file=suggest-listing&amp;id=0", "icon" => "create_listing.png", "label" => $esynI18N['create_listing']),
	array("url"	=> "controller.php?file=suggest-category&amp;id=0", "icon" => "create_category.png", "label" => $esynI18N['create_category']),
);

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->display('categories.tpl');
