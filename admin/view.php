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

defined("IA_ADMIN_CLASSES") || die("Forbidden");

$esynAdmin->loadClass("Smarty");
$esynSmarty = new esynSmarty($esynAdmin->mConfig['tmpl']);
$esynSmarty->mHooks = $esynAdmin->mHooks;

if (!file_exists(IA_TMP . 'admin'))
{
	esynUtil::mkdir(IA_TMP . 'admin');
}
$esynUtil = new esynUtil();

$esynSmarty->setCompileDir(IA_TMP . 'admin' . IA_DS);
$esynSmarty->setConfigDir(IA_TMP.'configs'.IA_DS);
$esynSmarty->setCacheDir(IA_TMP.'smartycache'.IA_DS);

$esynSmarty->setTemplateDir(IA_ADMIN_HOME . 'templates' . IA_DS . 'default' . IA_DS);

$esynSmarty->caching = false;
$esynSmarty->cache_modified_check = true;
$esynSmarty->debugging = false;

$esynSmarty->register_function("preventCsrf", array($esynUtil, "preventCsrf"));

$esynAdmin->createJsCache();

$esynAdmin->factory('View');

if (isset($currentAdmin) && !empty($currentAdmin))
{
	$adminMenu = array();

	$esynAdmin->setTable("admin_blocks");
	$adminBlocks = $esynAdmin->all("*", "1=1 ORDER BY `order`");
	$esynAdmin->resetTable();

	$state = array();

	if (!empty($currentAdmin['state']))
	{
		$state = unserialize($currentAdmin['state']);
	}

	if (isset($state['admin_blocks_order']) && !empty($state['admin_blocks_order']))
	{
		$tmp = array();

		foreach ($state['admin_blocks_order'] as $key => $name)
		{
			foreach ($adminBlocks as $j => $block)
			{
				if ($name == $block['name'])
				{
					$tmp[] = $adminBlocks[$j];
				}
			}
		}

		$adminBlocks = $tmp;
	}

	/* create where clause if admin is not super */
	$where = !$currentAdmin['super'] ? "AND `aco` IN ('" . join("','", $currentAdmin['permissions']) . "')" : "";

	if (!empty($adminBlocks))
	{
		$i = 0;

		/* get menu items */
		$esynAdmin->setTable("admin_pages");

		foreach($adminBlocks as $key => $adminBlock)
		{
			$items = $esynAdmin->all("*", "`block_name` = '{$adminBlock['name']}' AND FIND_IN_SET('main', `menus`) > 0 {$where} ORDER BY `order`");

			if (!empty($items))
			{
				if (empty($state))
				{
					$open = 'open';
				}
				else
				{
					if (isset($state['admin_blocks_close'][$adminBlock['name']]))
					{
						$open = $state['admin_blocks_close'][$adminBlock['name']] == 1 ? 'open' : 'close';
					}
					else
					{
						$open = 'open';
					}
				}

				if (isset($esynI18N['admin_block_title_' . $adminBlock['name']]))
				{
					$block_text = _t('admin_block_title_' . $adminBlock['name']);
				}
				else
				{
					if (isset($adminBlock['title']))
					{
						$block_text = $adminBlock['title'];
					}
					else
					{
						$block_text = $adminBlock['name'];
					}
				}

				$adminMenu[$i]['text'] = $block_text;
				$adminMenu[$i]['name'] = $adminBlock['name'];
				$adminMenu[$i]['open'] = $open;

				foreach($items as $jey => $item)
				{
					$params = array();
					$url = 'controller.php?';

					if (!empty($item['file']))
					{
						$params[] = "file={$item['file']}";
					}
					if (!empty($item['plugin']))
					{
						$params[] = "plugin={$item['plugin']}";
					}
					if (!empty($item['params']))
					{
						$params[] = $item['params'];
					}

					$url .= implode("&amp;", $params);

					if (isset($esynI18N['admin_page_title_' . $item['aco']]))
					{
						$text = _t('admin_page_title_' . $item['aco']);
					}
					else
					{
						if (isset($item['title']))
						{
							$text = $item['title'];
						}
						else
						{
							$text = $item['aco'];
						}
					}

					$adminMenu[$i]['items'][$jey]['text'] = $text;
					$adminMenu[$i]['items'][$jey]['href'] = $url;
					$adminMenu[$i]['items'][$jey]['aco'] = $item['aco'];

					if (isset($item['attr']) && !empty($item['attr']))
					{
						$adminMenu[$i]['items'][$jey]['attr'] = $item['attr'];
					}
				}
			}

			$i++;
		}

		$esynAdmin->resetTable();
	}

	$esynSmarty->assign('adminMenu', $adminMenu);

	$esynAdmin->setTable("admin_pages");
	$adminHeaderMenu = $esynAdmin->all("*", "FIND_IN_SET('header', `menus`) > 0 {$where} ORDER BY `header_order` ASC");
	$esynAdmin->resetTable();
	if (!empty($adminHeaderMenu))
	{
		foreach($adminHeaderMenu as $key => $item)
		{
			$params = array();
			$url = 'controller.php?';

			if (!empty($item['file']))
			{
				$params[] = "file={$item['file']}";
			}
			if (!empty($item['plugin']))
			{
				$params[] = "plugin={$item['plugin']}";
			}
			if (!empty($item['params']))
			{
				if ('&' == substr($item['params'], 0, 1))
				{
					$item['params'] = str_replace('&', '', $item['params']);

					if (!empty($_SERVER['QUERY_STRING']))
					{
						$params[] = $_SERVER['QUERY_STRING'];
					}

					$params[] = $item['params'];
				}
				else
				{
					$params[] = $item['params'];
				}
			}

			$url .= implode("&amp;", $params);

			if (isset($esynI18N['admin_page_title_' . $item['aco']]))
			{
				$text = _t('admin_page_title_' . $item['aco']);
			}
			else
			{
				if (isset($item['title']))
				{
					$text = $item['title'];
				}
				else
				{
					$text = $item['aco'];
				}
			}

			$adminHeaderMenu[$key]['text'] = $text;
			$adminHeaderMenu[$key]['href'] = $url;

			if (isset($item['attr']) && !empty($item['attr']))
			{
				$adminHeaderMenu[$key]['attr'] = $item['attr'];
			}
		}

		$esynSmarty->assign('adminHeaderMenu', $adminHeaderMenu);
	}
}

$sql = "SELECT `tabs`.* FROM `{$esynAdmin->mPrefix}tabs` `tabs` ";
$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}tab_pages` `tab_pages` ";
$sql .= "ON `tab_pages`.`tab_name` = `tabs`.`name` ";
$sql .= "WHERE `tabs`.`sticky` = 1 ";
$sql .= defined("IA_REALM") ? "OR `tab_pages`.`page_name` = '".IA_REALM."' " : '';
$sql .= "ORDER BY `tabs`.`order` ASC";

$esynAdmin->setTable("tabs");
$esyn_tabs = $esynAdmin->getAll($sql);
$esynAdmin->resetTable();
if (!empty($esyn_tabs))
{
	$esynSmarty->assign('esyn_tabs', $esyn_tabs);
}

if ($esynAdmin->mExpireDate)
{
	esynMessages::setMessage('Your trial license will expire on ' . $esynAdmin->mExpireDate . '.', esynMessages::SYSTEM);
}

if (file_exists(IA_HOME . 'install' . IA_DS . 'modules' . IA_DS . 'module.install.php'))
{
	esynMessages::setMessage($esynI18N['installer_not_removed'], esynMessages::SYSTEM);
}

if (esynUtil::checkUid() && is_writeable(IA_INCLUDES . 'config.inc.php'))
{
	esynMessages::setMessage($esynI18N['config_writable'], esynMessages::SYSTEM);
}

$messages = esynMessages::getMessages();
$esynSmarty->assignByRef('messages', $messages);

if (empty($category))
{
	// hack to display the correct breadcrumb on suggest category page
	// the $category variable is used for creating breadcrumb
	// and it is used on suggest category page and can't be changed
	if (defined("IA_REALM") && 'create_category' == IA_REALM && isset($parent))
	{
		$breadcrumb_category = $parent;
	}
	else
	{
		$breadcrumb_category = array("id" => 0);
	}
}
else
{
	$breadcrumb_category = $category;
}

$gBc[0]['title'] = $gBc[0]['title'] ? $gBc[0]['title'] : $gTitle;
$esynSmarty->assign('breadcrumb', esynView::printBreadcrumb($breadcrumb_category['id'], $gBc));

if (empty($url))
{
	$url = '';
}

if (!empty($actions))
{
	$esynSmarty->assign('actions', $actions);
}

$esynSmarty->assignByRef('gTitle', $gTitle);

$esynSmarty->assign('esynI18N', $esynI18N);
$esynSmarty->assign('currentAdmin', $currentAdmin);
$esynSmarty->assign('langs', $esynAdmin->mLanguages);

$esynSmarty->assignByRef('config', $esynAdmin->mConfig);
