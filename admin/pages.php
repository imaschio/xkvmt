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

define('IA_REALM', "pages");

esynUtil::checkAccess();

$esynAdmin->factory("Page");

if (isset($_POST['preview']))
{
	$_POST['save'] = true;
}

if (isset($_POST['save']))
{
	$esynAdmin->startHook('adminAddPageValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error = false;

	$new_page = array();

	$new_page['name'] = $_POST['name'] = !utf8_is_ascii($_POST['name']) ? utf8_to_ascii($_POST['name']) : $_POST['name'];
	$new_page['custom_url'] = !utf8_is_ascii($_POST['custom_url']) ? utf8_to_ascii($_POST['custom_url']) : $_POST['custom_url'];
	$new_page['group'] = 'pages';

	function utf8_validation(&$item, $key)
	{
		$item = !utf8_is_valid($item) ? utf8_bad_replace($item) : $item;
	}

	if (isset($_POST['titles']) && is_array($_POST['titles']))
	{
		foreach($_POST['titles'] as $key => $title)
		{
			if (isset($_POST['titles'][$key]) && !empty($_POST['titles'][$key]))
			{
				utf8_validation($_POST['titles'][$key], $key);
			}
			else
			{
				$error = true;
				$msg[] = $esynI18N['error_title'];
			}

		}
	}
	else
	{
		$error = true;
		$msg[] = $esynI18N['error_title'];
	}

	$new_page['menus'] = (isset($_POST['menus']) && is_array($_POST['menus'])) ? $_POST['menus'] : array();

	if (isset($_POST['preview']))
	{
		$new_page['status'] = 'draft';
	}
	else
	{
		$new_page['status'] = in_array($_POST['status'], array('active', 'inactive')) ? $_POST['status'] : 'inactive';
	}

	$new_page['name'] = preg_replace("/[^a-z0-9-_]/iu", "", $new_page['name']);
	$new_page['custom_url'] = !empty($new_page['custom_url']) ? esynUtil::convertStr($new_page['custom_url']) : '';

	if (!empty($new_page['custom_url']))
	{
		if (isset($_GET['do']) && 'edit' == $_GET['do'])
		{
			$old_custom_url = esynUtil::convertStr($_POST['old_custom_url']);

			if ($old_custom_url != $new_page['custom_url']
				&& $esynPage->exists("`custom_url` = :custom_url AND `status` != 'draft'", array('custom_url' => $new_page['custom_url']))
			)
			{
				$error = true;
				$msg[] = $esynI18N['custom_url_exist'];
			}
		}
		else
		{
			if ($esynPage->exists("`custom_url` = :custom_url AND `status` != 'draft'", array('custom_url' => $new_page['custom_url'])))
			{
				$error = true;
				$msg[] = $esynI18N['custom_url_exist'];
			}
		}
	}

	if (isset($_POST['meta_description']) && !empty($_POST['meta_description']))
	{
		if (!utf8_is_valid($_POST['meta_description']))
		{
			$_POST['meta_description'] = utf8_bad_replace($_POST['meta_description']);
		}

		$new_page['meta_description'] = $_POST['meta_description'];
	}

	if (isset($_POST['meta_keywords']) && !empty($_POST['meta_keywords']))
	{
		if (!utf8_is_valid($_POST['meta_keywords']))
		{
			$_POST['meta_keywords'] = utf8_bad_replace($_POST['meta_keywords']);
		}

		$new_page['meta_keywords'] = $_POST['meta_keywords'];
	}

	if (isset($_POST['nofollow']))
	{
		$new_page['nofollow'] = (int)$_POST['nofollow'];
	}

	if (isset($_POST['new_window']))
	{
		$new_page['new_window'] = (int)$_POST['new_window'];
	}

	if (isset($_POST['contents']) && is_array($_POST['contents']))
	{
		foreach($_POST['contents'] as $key => $content)
		{
			utf8_validation($_POST['contents'][$key], $key);
		}
		$new_page['contents'] = $_POST['contents'];
	}

	if (isset($_POST['external_url']) && $_POST['external_url'])
	{
		if (!empty($_POST['unique_url']))
		{
			$new_page['unique_url'] = trim(strip_tags($_POST['unique_url']));
			unset($new_page['contents']);
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['enter_external_url'];
		}
	}
	else
	{
		$new_page['unique_url'] = '';
	}

	if (empty($new_page['name']))
	{
		$error = true;
		$msg[] = $esynI18N['page_name_incorrect'];
	}

	$new_page['titles'] = $_POST['titles'];

	if (!$error)
	{
		if ('edit' == $_POST['do'])
		{
			$result = $esynPage->update($new_page, $_POST['id']);

			if ($result)
			{
				$msg[] = $esynI18N['changes_saved'];
			}
			else
			{
				$error = true;
				$msg[] = $esynPage->getMessage();
			}
		}
		else
		{
			$result = $esynPage->insert($new_page);

			if ($result)
			{
				$msg[] = $esynI18N['page_added'];
			}
			else
			{
				$error = true;
				$msg[] = $esynPage->getMessage();
			}
		}

		if (!$error)
		{
			if (isset($_POST['preview']))
			{
				$redirect_url = IA_URL;

				if (isset($_POST['unique']) && 1 == $_POST['unique'])
				{
					$redirect_url .= $new_page['unique_url'];
				}
				else
				{
					$redirect_url .= 'page.php?page_preview=true&name=' . $new_page['name'];
				}

				esynUtil::go2($redirect_url);
			}
			else
			{
				$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

				esynMessages::setMessage($msg, $error);

				esynUtil::reload(array("do" => $do));
			}
		}
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']))
{

	$out = array('data' => '', 'total' => 0);

	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		$where = array();
		$order = '';
		$LIMIT = '';

		if (!empty($sort) && !empty($dir))
		{
			if ('title' == $sort)
			{
				$order = "ORDER BY `l`.`value` {$dir}";
			}
			else
			{
				$order = "ORDER BY `{$sort}` {$dir}";
			}
		}

		if ($limit)
		{
			$LIMIT = "LIMIT {$start},{$limit}";
		}

		if (isset($_GET['key']) && !empty($_GET['key']))
		{
			$key = esynSanitize::sql($_GET['key']);

			$where[] = "`name` LIKE '%{$key}%'";
		}

		if (isset($_GET['plg']) && !empty($_GET['plg']) && 'all' != $_GET['plg'])
		{
			$plugin = esynSanitize::sql($_GET['plg']);
			$where[] = "`p`.`plugin` = '{$plugin}'";
		}

		$where[] = "`readonly` = '0'";

		$where = implode(' AND ', $where);

		$sql = "SELECT SQL_CALC_FOUND_ROWS `p`.*, `l`.`value` AS `title`
				FROM `{$esynAdmin->mPrefix}pages` `p`
				LEFT JOIN `{$esynAdmin->mPrefix}language` `l`
					ON `p`.`name` = REPLACE(`l`.`key`, 'page_title_', '')
				WHERE {$where}
					AND `l`.`key` LIKE('page_title_%')
					AND `l`.`category` = 'page'
					AND `l`.`code` = 'en'
					{$order}
					{$LIMIT}
				";

		$out['data'] = $esynAdmin->getAll($sql);
		$out['total'] = $esynAdmin->foundRows();
	}

	if ('getplugins' == $_GET['action'])
	{
		$esynAdmin->setTable('plugins');
		$out['data'] = $esynAdmin->all('`name`,`title`', '1=1');
		$esynAdmin->resetTable();
	}

	if ('getpageurl' == $_GET['action'])
	{
		$url = '';

		if ('true' == $_GET['external_url'])
		{
			$_GET['unique_url'] = trim(strip_tags($_GET['unique_url']));
			$url = !empty($_GET['unique_url']) ? $_GET['unique_url'] : '';
		}
		else
		{
			require_once IA_CLASSES . 'esynUtf8.php';

			esynUtf8::loadUTF8Core();
			esynUtf8::loadUTF8Util('ascii', 'bad', 'utf8_to_ascii');

			$_GET['page_unique_key'] = !utf8_is_ascii($_GET['page_unique_key']) ? utf8_to_ascii($_GET['page_unique_key']) : $_GET['page_unique_key'];
			$_GET['page_unique_key'] = preg_replace("/[^a-z0-9-_]/iu", "", $_GET['page_unique_key']);

			$_GET['custom_seo_url'] = !utf8_is_ascii($_GET['custom_seo_url']) ? utf8_to_ascii($_GET['custom_seo_url']) : $_GET['custom_seo_url'];
			$_GET['custom_seo_url'] = preg_replace("/[^a-z0-9-_]/iu", "", $_GET['custom_seo_url']);

			$seo_url = (isset($_GET['custom_seo_url']) && !empty($_GET['custom_seo_url'])) ? $_GET['custom_seo_url'] : $_GET['page_unique_key'];
			$url = $esynConfig->getConfig("old_pages_urls") ? 'p' . $seo_url . '.html' : $seo_url . '.html';
		}

		if (!empty($url))
		{
			$out['data'] = IA_URL . $url;
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

	$out = array('msg' => 'Unknown error', 'error' => true);

	if ('update' == $_POST['action'])
	{
		$result = $esynPage->update(array($_POST['field'] => $_POST['value']), $_POST['ids']);

		if ($result)
		{
			$out['error'] = false;
			$out['msg'] = $esynI18N['changes_saved'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynPage->getMessage();
		}
	}

	if ('remove' == $_POST['action'])
	{
		$result = $esynPage->delete($_POST['ids']);

		if ($result)
		{
			$out['error'] = false;
			$out['msg'] = $esynI18N['page_deleted'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $this->getMessage();
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gBc[0]['title'] = $esynI18N['manage_pages'];
$gBc[0]['url'] = 'controller.php?file=pages';

$gTitle = $esynI18N['manage_pages'];

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[0]['title'] = $esynI18N['manage_pages'];
		$gBc[0]['url'] = 'controller.php?file=pages';

		$gBc[1]['title'] = ('add' == $_GET['do']) ? $esynI18N['add_page'] : $esynI18N['edit_page'];
		$gTitle = $gBc[1]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?file=pages&amp;do=add", "icon" => "add_page.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?file=pages", "icon" => "view_page.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']) && 'edit' == $_GET['do'] && isset($_GET['id']) && ctype_digit($_GET['id']))
{
	$page = $esynPage->row("*", "`id` = :id", array('id' => (int)$_GET['id']));

	$esynAdmin->setTable("language");
	$page['titles'] = $esynAdmin->keyvalue("`code`, `value`", "`key` = 'page_title_{$page['name']}' AND `category` = 'page'");
	$page['contents'] = $esynAdmin->keyvalue("`code`, `value`", "`key` = 'page_content_{$page['name']}' AND `category` = 'page'");
	$esynAdmin->resetTable();

	$esynAdmin->setTable('blocks');
	$menus = $esynAdmin->keyvalue('`name`, `title`', "`type` = 'menu'");
	$esynAdmin->resetTable();

	$esynAdmin->setTable('block_pages');
	$page_menus = $esynAdmin->keyvalue("`id`, `block_name`", "`page_name` = '{$page['name']}'");
	$esynAdmin->resetTable();

	$esynSmarty->assign('menus', $menus);
	$esynSmarty->assign('page_menus', $page_menus);
	$esynSmarty->assign('page', $page);
}

if (isset($_GET['do']) && 'add' == $_GET['do'])
{
	$esynAdmin->setTable('blocks');
	$menus = $esynAdmin->keyvalue('`name`, `title`', "`type` = 'menu'");
	$esynAdmin->resetTable();

	$esynSmarty->assign('menus', $menus);
}

$esynSmarty->display('pages.tpl');
