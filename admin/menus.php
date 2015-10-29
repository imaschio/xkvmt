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

define('IA_REALM', "menus");

esynUtil::checkAccess();

$esynAdmin->factory('Block', 'Page');

if (isset($_POST['do']))
{
	$esynAdmin->startHook('adminAddMenuValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error = false;

	$block = array();

	$block['name']              = $_POST['name'];
	$block['position']			= isset($_POST['position']) ? $_POST['position'] : 'left';
	$block['type']				= 'menu';
	$block['show_header']       = isset($_POST['show_header']) && $_POST['show_header'] ? '1' : '0';
	$block['collapsible']       = isset($_POST['collapsible']) && $_POST['collapsible'] ? '1' : '0';
	$block['collapsed']			= 0;
	$block['multi_language']	= 1;
	$block['sticky']			= isset($_POST['sticky']) && $_POST['sticky'] ? '1' : '0';
	$block['visible_on_pages']	= isset($_POST['visible_on_pages']) ? $_POST['visible_on_pages'] : '';
	$block['classname']			= $_POST['classname'] = !utf8_is_ascii($_POST['classname']) ? utf8_to_ascii($_POST['classname']) : $_POST['classname'];

	$post_title = $_POST['title'];

	if (empty($post_title))
	{
		$error = true;
		$msg[] = _t('menu_title_empty');
	}
	else
	{
		if (!utf8_is_valid($post_title))
		{
			$post_title = utf8_bad_replace($post_title);
		}
	}
	$block['title'] = $post_title;

	$cat_crossed = isset($_POST['cat_crossed']) ? $_POST['cat_crossed'] : '';
	$cat_crossed = explode(',', $cat_crossed);
	foreach ($cat_crossed as $key => $cat)
	{
		$block['visible_on_pages'][] = 'index_browse|' . $cat;
	}

	if (isset($_POST['menu']) && !empty($_POST['menu']))
	{
		$name = $block['name'];
		$post_menu = $esynBlock->object2array(esynUtil::jsonDecode($_POST['menu']));
		function recursive_read_menu($children, &$list, $name, $parent = '', $level = 0)
		{
			foreach ($children as $child)
			{
				$list[] = array(
					'block_name' => $name,
					'page_name' => $child['id'],
					'parent_name' => ($parent) ? $parent : 'node',
					'level' => $level
				);
				if (isset($child['children']) && !empty($child['children']))
				{
					recursive_read_menu($child['children'], $list, $name, $child['id'], $level + 1);
				}
			}
		}
		if ($post_menu['children'])
		{
			$insert = array();
			recursive_read_menu($post_menu['children'], $insert, $name);
			$block['menus'] = $insert;
		}
		else
		{
			$error = true;
			$msg[] = _t('menu_no_pages');
		}
	}

	if (!$error)
	{
		if ('edit' == $_POST['do'])
		{
			$result = $esynBlock->update($block, (int)$_GET['id']);

			if ($result)
			{
				$msg[] = $esynI18N['changes_saved'];
			}
			else
			{
				$error = true;
				$msg[] = $esynBlock->getMessage();
			}
		}
		else
		{
			$result = $esynBlock->insert($block);

			if ($result)
			{
				$msg[] = $esynI18N['menu_created'];
			}
			else
			{
				$error = true;
				$msg[] = $esynBlock->getMessage();
			}
		}

		$do = (isset($_POST['goto']) && 'new' == $_POST['goto']) ? 'add' : null;

		esynMessages::setMessage($msg, $error);

		esynUtil::reload(array("do" => $do));
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']))
{

	$start = (int)$_GET['start'];
	$limit = (int)$_GET['limit'];

	$out = array('data' => '', 'total' => 0);

	if ('get' == $_GET['action'])
	{
		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		if (!empty($sort) && !empty($dir))
		{
			$order = "ORDER BY `{$sort}` {$dir}";
		}

		$out['total'] = $esynBlock->one('COUNT(*)', "`type` = 'menu'");
		$out['data'] = $esynBlock->all("*, `id` `edit`, '1' `remove`", "`type` = 'menu' {$order}", array(), $start, $limit);

		if (!empty($out['data']))
		{
			foreach ($out['data'] as $key => $data)
			{
				if (in_array($data['name'], array('inventory', 'main', 'account', 'bottom')))
				{
					$out['data'][$key]['remove'] = 0;
				}
			}
		}

		if (empty($out['data']))
		{
			$out['data'] = '';
		}
	}
	elseif ('get_phrases' == $_GET['action'])
	{
		$out['langs'] = array();
		$langs = $esynAdmin->mLanguages;
		$node = isset($_GET['id']) ? esynSanitize::sql($_GET['id']) : false;
		$menu = isset($_GET['menu']) ? esynSanitize::sql($_GET['menu']) : false;

		if (isset($_GET['new']) && $_GET['new'] == 'true')
		{
			ksort($langs);
			foreach ($langs as $code => $lang)
			{
				$out['langs'][] = array(
					'fieldLabel' => $lang,
					'name' => $code,
					'value' => ''
				);
			}
		}
		elseif ($node && $menu)
		{
			$key = false;
			$title = _t('page_title_' . $node, 'none');
			if ($title != 'none')
			{
				$key = 'page_title_' . $node;
			}
			else
			{
				$pageId = (int)$node;
				if ($pageId > 0)
				{
					$esynAdmin->setTable('pages');
					$page = $esynAdmin->one('`name`', '`id` = ' . $pageId);
					$esynAdmin->resetTable();

					$key = 'page_title_' . $page;
				}
				else
				{
					$current = isset($_GET['current']) ? $_GET['current'] : '';
					ksort($langs);
					foreach ($langs as $code => $lang)
					{
						$out['langs'][] = array(
							'fieldLabel' => $lang,
							'name' => $code,
							'value' => $current
						);
					}
				}
			}

			if ($key)
			{
				$esynAdmin->setTable('language');
				$titles = $esynAdmin->all('*', "`key` = '$key' ORDER BY `code`");
				$esynAdmin->resetTable();

				foreach ($titles as $row)
				{
					if (isset($langs[$row['code']]))
					{
						$out['langs'][] = array(
							'fieldLabel' => $langs[$row['code']],
							'name' => $row['code'],
							'value' => $row['value']
						);
					}
				}
			}
			$out['key'] = $key;
		}
	}
	elseif ('save_phrases' == $_GET['action'])
	{
		$out['msg'] = _t('unknown_error');
		$menu = isset($_GET['menu']) ? esynSanitize::sql($_GET['menu']) : false;
		$node = isset($_GET['node']) ? esynSanitize::sql($_GET['node']) : false;

		if ($menu && $node)
		{
			$insert = array();
			foreach ($_POST as $code => $value)
			{
				$insert[] = array(
					'code' => $code,
					'value' => esynSanitize::sql($value),
					'plugin' => $menu,
					'key' => 'page_title_' . $node,
					'category' => 'common'
				);
			}

			$esynAdmin->setTable('language');
			$esynAdmin->delete("`key` = 'page_title_{$node}'");
			$esynAdmin->insert($insert);
			$esynAdmin->resetTable();

			$out['msg'] = _t('changes_saved');
			$out['success'] = true;

			$esynAdmin->mCacher->clearAll('language');
			$esynAdmin->mCacher->clearAll('lang');
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{

	$out = array('msg' => '', 'error' => true);

	if ('update' == $_POST['action'])
	{
		$where = $esynAdmin->convertIds('id', $_POST['ids']);

		$esynAdmin->setTable('blocks');
		$result = $esynAdmin->update(array($_POST['field'] => $_POST['value']), $where);
		$esynAdmin->resetTable();

		if ($result)
		{
			$out['error'] = false;
			$out['msg'] = $esynI18N['changes_saved'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynBlock->getMessage();
		}
	}

	if ('remove' == $_POST['action'])
	{
		$where = $esynAdmin->convertIds('id', $_POST['ids']);

		$esynAdmin->setTable('blocks');
		$blocks = $esynAdmin->all('`id`, `name`, `title`', $where);
		$esynAdmin->resetTable();

		$ids = array();

		if (!empty($blocks))
		{
			foreach ($blocks as $key => $block)
			{
				if (in_array($block['name'], array('inventory', 'main', 'account', 'bottom')))
				{
					$out['error'] = true;
					$out['msg'][] = $block['title'] . ' is unremovable.';

					unset($blocks[$key]);
				}
				else
				{
					$ids[] = $block['id'];
				}
			}
		}

		if (!empty($ids))
		{
			$result = $esynBlock->delete($ids);

			if ($result)
			{
				$out['error'] = false;
				$out['msg'] = $esynI18N['menu_removed'];
			}
			else
			{
				$out['error'] = true;
				$out['msg'] = $esynBlock->getMessage();
			}
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_menus'];

$gBc[0]['title'] = $esynI18N['manage_menus'];
$gBc[0]['url'] = 'controller.php?file=menus';

if (isset($_GET['do']))
{
	if (('edit' == $_GET['do']) || ('add' == $_GET['do']))
	{
		$gBc[0]['title'] = $esynI18N['manage_menus'];
		$gBc[0]['url'] = 'controller.php?file=menus';

		$gBc[1]['title'] = ('add' == $_GET['do']) ? $esynI18N['add_menu'] : $esynI18N['edit_menu'];
		$gTitle = $gBc[1]['title'];
	}
}

$actions = array(
	array(
		'url'	=> 'controller.php?file=menus&amp;do=add',
		'icon'	=> 'add_menu.png',
		'label'	=> $esynI18N['create']
	),
	array(
		'url'	=> 'controller.php?file=menus',
		'icon'	=> 'view_menu.png',
		'label'	=> $esynI18N['view']
	)
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']))
{
	$esynAdmin->setTable('pages');
	$pages = $esynAdmin->all('*', "`readonly` = '0' AND `status` = 'active'");
	$esynAdmin->resetTable();

	$tree_pages = array();
	$pages_group = array();
	$out = array();

	$esynAdmin->setTable('language');
	foreach ($pages as $key => $page)
	{
		if (!empty($page['unique_url']) && esynValidator::isUrl($page['unique_url']) && !stristr($page['unique_url'], IA_URL))
		{
			unset($pages[$key]);

			continue;
		}

		if (!isset($tree_pages[$page['group']]))
		{
			$tree_pages[$page['group']] = array(
				'text'		=> ucfirst($page['group']),
				'leaf'		=> (bool)false,
				'children'	=> array()
			);

			$pages_group[] = $page['group'];
		}

		$pages[$key]['title'] = $esynAdmin->one("`value`", "`key` = 'page_title_{$page['name']}' AND `code` = '" . IA_LANGUAGE . "'");

		$n = array(
			'id'	=> $page['name'],
			'text'	=> $pages[$key]['title'],
			'leaf'	=> (bool)TRUE,
			'cls'	=> 'leaf'
		);

		$tree_pages[$page['group']]['children'][] = $n;
	}
	$esynAdmin->resetTable();

	foreach ($tree_pages as $page)
	{
		$out[] = $page;
	}

	$tree_pages = esynUtil::jsonEncode($out);

	if ('edit' == $_GET['do'])
	{
		$esynAdmin->setTable('blocks');
		$menu = $esynAdmin->row('*', "`id` = :id AND `type` = 'menu'", array('id' => (int)$_GET['id']));
		$esynAdmin->resetTable();

		$sql = "SELECT `p`.`name`,  `c`.`title`, `bp`.* "
			 . "FROM `{$esynAdmin->mPrefix}block_pages` `bp` "
			 . "LEFT JOIN `{$esynAdmin->mPrefix}pages` `p` "
			 . "ON `bp`.`page_name` = `p`.`name` "
			 . "LEFT JOIN `{$esynAdmin->mPrefix}categories` `c` "
			 . "ON `c`.`id` = REPLACE(`bp`.`page_name`, 'index_browse|', '') "
			 . "WHERE `bp`.`block_name` = '{$menu['name']}' "
			 . "ORDER BY `bp`.`id`";

		$menu_childrens = $esynAdmin->getAll($sql);

		function buildTree(array &$elements, $parentName = 'node')
		{
			$branch = array();
			foreach ($elements as $k => $element)
			{
				if ($element['parent_name'] == $parentName)
				{
					$page['id'] = $element['page_name'];
					$page['text'] = !$element['name'] ? $element['title'] : _t('page_title_' . $element['page_name']);
					$page['leaf'] = true;

					$children = buildTree($elements, $element['page_name']);
					if ($children)
					{
						$page['leaf'] = false;
						$page['expanded'] = true;
						$page['children'] = $children;
					}
					$branch[] = $page;
					unset($page);
				}
			}
			return $branch;
		}

		$tree_menu_children = buildTree($menu_childrens);

		$tree_menus = array(
			'id'		=> $menu['id'],
			'text'		=> $menu['title'],
			'leaf'		=> false,
			'children'	=> $tree_menu_children,
		);

		$esynAdmin->setTable("block_show");
		$visibleOn = $esynAdmin->onefield("`page`", "`block_id` = :id", array('id' => (int)$_GET['id']));
		$esynAdmin->resetTable();

		if (empty($visibleOn))
		{
			$visibleOn = array();
		}
		else
		{
			foreach ($visibleOn as $key => $page)
			{
				if (strstr($page, "|"))
				{
					unset($visibleOn[$key]);
					$id = explode("|", $page);
					$menu['categories'][] = $id[1];
	 			}
			}
		}
	}
	else
	{
		$menu = array();
		$children = array();

		if (isset($post_menu['children']) && !empty($post_menu['children']))
		{
			$children = $post_menu['children'];
		}

		$tree_menus = array(
			'id'		=> '',
			'text'		=> 'New Menu',
			'leaf'		=> false,
			'children'	=> $children,
		);

		$visibleOn = isset($_POST['visible_on_pages']) ? $_POST['visible_on_pages'] : array();
	}

	$tree_menus = esynUtil::jsonEncode($tree_menus);

	$positions = explode(',', $esynAdmin->mConfig['esyndicat_block_positions']);

	$all_pages = $esynPage->getPages();
	$esynSmarty->assign('all_pages', $all_pages);

	$esynSmarty->assign('menu', $menu);
	$esynSmarty->assign('visibleOn', $visibleOn);
	$esynSmarty->assign('pages_group', $pages_group);
	$esynSmarty->assign('pages', $pages);
	$esynSmarty->assign('tree_pages', $tree_pages);
	$esynSmarty->assign('tree_menus', $tree_menus);
	$esynSmarty->assign('positions', $positions);
}

$esynSmarty->display('menus.tpl');
