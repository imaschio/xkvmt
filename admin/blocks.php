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

define('IA_REALM', "blocks");

esynUtil::checkAccess();

$esynAdmin->factory('Block', 'Page');

$block_types = array('plain', 'html', 'smarty', 'php');

if (isset($_POST['save']))
{
	$esynAdmin->startHook('adminAddBlockValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error = false;

	$block = array();

	$block['name'] = $_POST['name'] = !utf8_is_ascii($_POST['name']) ? utf8_to_ascii($_POST['name']) : $_POST['name'];
	$block['name'] = preg_replace("/[^a-z0-9-_]/iu", "", $block['name']);
	$block['position'] = isset($_POST['position']) && in_array($_POST['position'], $esynBlock->positions) ? $_POST['position'] : 'left';
	$block['type'] = isset($_POST['type']) && in_array($_POST['type'], $esynBlock->types) ? $_POST['type'] : 'plain';
	$block['status'] = isset($_POST['status']) && in_array($_POST['status'], $esynBlock->status) ? $_POST['status'] : 'inactive';
	$block['show_header'] = isset($_POST['show_header']) && $_POST['show_header'] ? 1 : 0;
	$block['external'] = isset($_POST['external']) && $_POST['external'] ? 1 : 0;
	$block['collapsible'] = isset($_POST['collapsible']) && $_POST['collapsible'] ? 1 : 0;
	$block['collapsed'] = isset($_POST['collapsed']) && $_POST['collapsed'] ? 1 : 0;
	$block['multi_language'] = isset($_POST['multi_language']) && $_POST['multi_language'] ? 1 : 0;
	$block['sticky'] = isset($_POST['sticky']) && $_POST['sticky'] ? 1 : 0;
	$block['visible_on_pages'] = isset($_POST['visible_on_pages']) ? $_POST['visible_on_pages'] : '';
	$block['classname'] = $_POST['classname'] = !utf8_is_ascii($_POST['classname']) ? utf8_to_ascii($_POST['classname']) : $_POST['classname'];

	if (0 == $block['show_header'])
	{
		$block['collapsible'] = 0;
		$block['collapsed'] = 0;
	}

	$cat_crossed = isset($_POST['cat_crossed']) ? $_POST['cat_crossed'] : '';
	$cat_crossed = explode(',', $cat_crossed);
	foreach ($cat_crossed as $key => $cat)
	{
		$block['visible_on_pages'][] = 'index_browse|' . $cat;
	}

	if (1 == $block['multi_language'])
	{
		$block['title'] = $_POST['multi_title'];

		if (empty($block['title']))
		{
			$error = true;
			$msg[] = $esynI18N['error_title'];
		}
		elseif (!utf8_is_valid($block['title']))
		{
			$block['title'] = utf8_bad_replace($block['title']);
		}

		$block['contents'] = $_POST['multi_contents'];

		if (empty($block['contents']) && empty($_POST['external']))
		{
			$error = true;
			$msg[] = $esynI18N['error_contents'];
		}

		if ('html' != $block['type'])
		{
			if (!utf8_is_valid($block['contents']))
			{
				$block['contents'] = utf8_bad_replace($block['contents']);
			}
		}
	}
	else
	{
		if (isset($_POST['block_languages']) && !empty($_POST['block_languages']))
		{
			$block['block_languages'] = $_POST['block_languages'];
			$block['title'] = $_POST['title'];
			$block['contents'] = $_POST['contents'];

			foreach($block['block_languages'] as $block_language)
			{
				if (isset($block['title'][$block_language]))
				{
					if (empty($block['title'][$block_language]))
					{
						$error = true;
						$msg[] = str_replace('{lang}', $esynAdmin->mLanguages[$block_language], $esynI18N['error_lang_title']);
					}
					elseif (!utf8_is_valid($block['title'][$block_language]))
					{
						$block['title'][$block_language] = utf8_bad_replace($block['title'][$block_language]);
					}
				}

				if (isset($block['contents'][$block_language]))
				{
					if (empty($block['contents'][$block_language]))
					{
						$error = true;
						$msg[] = str_replace('{lang}', $esynAdmin->mLanguages[$block_language], $esynI18N['error_lang_contents']);
					}

					if ('html' != $block['type'])
					{
						if (!utf8_is_valid($block['contents'][$block_language]))
						{
							$block['contents'][$block_language] = utf8_bad_replace($block['contents'][$block_language]);
						}
					}
				}
			}
		}
		else
		{
			$error = true;
			$msg[] = $esynI18N['block_languages_empty'];
		}
	}

	$block['name'] = preg_replace("/[^a-z0-9-_]/iu", "", $block['name']);

	if (empty($block['name']))
	{
		$error = true;
		$msg[] = $esynI18N['block_name_incorrect'];
	}

	if (isset($_GET['do'])
		&& 'edit' != $_GET['do']
		&& $esynBlock->exists("`name` = '{$block['name']}'"))
	{
		$error = true;
		$msg[] = _t('block_name_exists');
	}

	if (!$error)
	{
		if ('edit' == $_POST['do'])
		{
			$result = $esynBlock->update($block, (int)$_POST['id']);

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
				$msg[] = $esynI18N['block_created'];
			}
			else
			{
				$error = true;
				$msg[] = $esynBlock->getMessage();
			}
		}

		$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

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
		$where = array();
		$values = array();

		if (!empty($sort) && !empty($dir))
		{
			$order = " ORDER BY `{$sort}` {$dir}";
		}

		if (isset($_GET['title']) && !empty($_GET['title']))
		{
			$title = esynSanitize::sql($_GET['title']);

			$where[] = "`title` LIKE '%{$title}%'";
		}

		if (isset($_GET['plg']) && !empty($_GET['plg']) && 'all' != $_GET['plg'])
		{
			$where[] = "`plugin` = :plugin";
			$values['plugin'] = $_GET['plg'];
		}

		if (isset($_GET['type']) && !empty($_GET['type']) && 'all' != $_GET['type'])
		{
			$where[] = '`type` = :type';
			$values['type'] = $_GET['type'];
		}

		$where[] = "`type` != 'menu'";

		if (!empty($where))
		{
			$where = implode(' AND ', $where);
		}
		else
		{
			$where = '1=1';
		}

		$out['total'] = $esynBlock->one('COUNT(*)', $where, $values);
		$out['data'] = $esynBlock->all("*, `id` `edit`, '1' `remove`", "{$where} {$order}", $values, $start, $limit);

		if ($out['data'])
		{
			foreach($out['data'] as $key => $block)
			{
				$esynAdmin->setTable("block_show");
				$pages = $esynAdmin->all("*", "`block_id` = :id", array('id' => $block['id']));
				$esynAdmin->resetTable();

				if ($pages)
				{
					foreach($pages as $page)
					{
						$out['data'][$key]["visible_on_pages[{$page['page']}]"] = 1;
					}
				}

				if ('0' == $block['multi_language'])
				{
					$esynAdmin->setTable("language");
					$title_languages = $esynAdmin->keyvalue("`code`, `value`", "`key` = 'block_title_blc{$block['id']}'");
					$esynAdmin->resetTable();

					if (!empty($title_languages))
					{
						if (!empty($title_languages[IA_LANGUAGE]))
						{
							$out['data'][$key]['title'] = $title_languages[IA_LANGUAGE];
						}
						else
						{
							unset($title_languages[IA_LANGUAGE]);

							foreach($title_languages as $title_language)
							{
								if (!empty($title_language))
								{
									$out['data'][$key]['title'] = $title_language;

									break;
								}
							}
						}
					}
				}
			}
		}

		if (empty($out['data']))
		{
			$out['data'] = '';
		}
	}

	if ('getplugins' == $_GET['action'])
	{
		$esynAdmin->setTable('plugins');
		$out['data'] = $esynAdmin->all('`name`,`title`', '1=1');
		$esynAdmin->resetTable();
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
		$esynAdmin->update(array($_POST['field'] => $_POST['value']), $where);
		$esynAdmin->resetTable();

		$out['error'] = false;
		$out['msg'] = $esynI18N['changes_saved'];
	}

	if ('remove' == $_POST['action'])
	{
		$result = $esynBlock->delete($_POST['ids']);

		if ($result)
		{
			$out['error'] = false;
			$out['msg'] = $esynI18N['block_deleted'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynBlock->getMessage();
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_blocks'];

$gBc[0]['title'] = $esynI18N['manage_blocks'];
$gBc[0]['url'] = 'controller.php?file=blocks';

if (isset($_GET['do']))
{
	if (('edit' == $_GET['do']) || ('add' == $_GET['do']))
	{
		$gBc[0]['title'] = $esynI18N['manage_blocks'];
		$gBc[0]['url'] = 'controller.php?file=blocks';

		$gBc[1]['title'] = ('add' == $_GET['do']) ? $esynI18N['add_block'] : $esynI18N['edit_block'];
		$gTitle = $gBc[1]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?file=blocks&amp;do=add", "icon" => "add_block.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?file=blocks", "icon" => "view_block.png", "label" => $esynI18N['view']),
	array("url" => "controller.php?file=visual", "icon" => "visual_mode.png", "label" => $esynI18N['visual_mode'], "attributes" => 'target="_blank"'),
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']))
{
	if ('edit' == $_GET['do'] && isset($_GET['id']) && ctype_digit($_GET['id']))
	{
		$block = $esynBlock->row("*", "`id` = :id", array('id' => (int)$_GET['id']));

		if ('0' == $block['multi_language'])
		{
			$esynAdmin->setTable("language");

			$block['block_languages'] = $esynAdmin->onefield("code", "`key` = 'block_content_blc{$block['id']}'");
			$block['title'] = $esynAdmin->keyvalue("`code`, `value`", "`key` = 'block_title_blc{$block['id']}'");
			$block['contents'] = $esynAdmin->keyvalue("`code`, `value`", "`key` = 'block_content_blc{$block['id']}'");

			$esynAdmin->resetTable();
		}

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
					$block['categories'][] = $id[1];
				}
			}
		}

		$esynSmarty->assign('block', $block);
		$esynSmarty->assign('visibleOn', $visibleOn);
	}

	if ('add' == $_GET['do'])
	{
		$visibleOn = isset($_POST['visible_on_pages']) ? $_POST['visible_on_pages'] : array();

		$esynSmarty->assign('visibleOn', $visibleOn);
	}

	// get pages
	$pages = $esynPage->getPages();
	$esynSmarty->assign('pages', $pages);

	// get page groups
	$pages_group = $esynPage->getPageGroups();
	$esynSmarty->assign('pages_group', $pages_group);

	$esynSmarty->assign('block_status', $esynBlock->status);
	$esynSmarty->assign('types', $block_types);
	$esynSmarty->assign('positions', $esynBlock->positions);
}

$esynSmarty->display('blocks.tpl');
