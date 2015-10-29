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

require_once '.' . DIRECTORY_SEPARATOR . 'header.php';

$block_names = isset($_GET['menu']) && !empty($_GET['menu']) ? $_GET['menu'] : array();

$adminMenuItems = array();

if (isset($currentAdmin) && !empty($currentAdmin))
{
	/* create where clause if admin is not super */
	$where = !$currentAdmin['super'] ? "AND `aco` IN ('".join("','", $currentAdmin['permissions'])."')" : "";

	$i = 0;

	if (!empty($block_names))
	{
		/* get menu items */
		$esynAdmin->setTable("admin_pages");

		foreach($block_names as $block_name)
		{
			$items = $esynAdmin->all("*", "`block_name` = '{$block_name}' AND FIND_IN_SET('main', `menus`) > 0 {$where}");

			if (!empty($items))
			{
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

					$icon = IA_ADMIN_DIR . '/templates/'.$esynConfig->getConfig('admin_tmpl').'/img/icons/menu/'.$item['aco'].'.png';

					if (!is_file(IA_HOME.$icon) || !file_exists(IA_HOME.$icon))
					{
						$icon = IA_ADMIN_DIR.'/templates/'.$esynConfig->getConfig('admin_tmpl').'/img/icons/menu/default.png';
					}

					$style = 'style="background-image: url(\''.IA_URL.$icon.'\');"';

					$url .= implode("&amp;", $params);

					if (isset($esynI18N['admin_page_title_' . $item['aco']]))
					{
						$text = _t('admin_page_title_' . $item['aco']);
					}
					else
					{
						if (isset($item['title']) && !empty($item['title']))
						{
							$text = $item['title'];
						}
						else
						{
							$text = $item['aco'];
						}
					}

					$adminMenuItems[$block_name][$jey]['text'] = $text;
					$adminMenuItems[$block_name][$jey]['href'] = $url;
					$adminMenuItems[$block_name][$jey]['aco'] = $item['aco'];
					$adminMenuItems[$block_name][$jey]['style'] = $style;

					if (isset($item['attr']) && !empty($item['attr']))
					{
						$adminMenuItems[$block_name][$jey]['attr'] = $item['attr'];
					}
				}

				$i++;
			}
		}

		$esynAdmin->resetTable();
	}
}

echo esynUtil::jsonEncode($adminMenuItems);
exit;
