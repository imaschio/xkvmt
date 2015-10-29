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

define('IA_REALM', "rss");

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
	$new_rss	= array();

	$new_block['lang'] 	= IA_LANGUAGE;
	$new_block['title'] = $_POST['title'];
	$new_rss['url']		= $_POST['url'];
	$new_rss['num']		= $_POST['num'];
	$new_rss['refresh']		= $_POST['refresh'];
	$new_rss['recursive']	= isset($_POST['recursive']) ? $_POST['recursive'] : '0';
	$new_rss['status']	= isset($_POST['status']) && !empty($_POST['status']) && in_array($_POST['status'], array('active', 'inactive')) ? $_POST['status'] : 'inactive';

	if (!empty($_POST['lang']) && array_key_exists($_POST['lang'], $esynAdmin->mLanguages))
	{
		$new_block['lang'] = $_POST['lang'];
	}

	if (!utf8_is_valid($new_block['title']))
	{
		$new_block['title'] = utf8_bad_replace($new_block['title']);
	}

	if (!utf8_is_valid($new_rss['url']))
	{
		$new_rss['url'] = utf8_bad_replace($new_rss['url']);
	}

	if (!utf8_is_valid($new_rss['num']))
	{
		$new_rss['num'] = utf8_bad_replace($new_rss['num']);
	}

	if (empty($new_rss['url']))
	{
		$error = true;
		$msg[] = $esynI18N['error_url'];
	}

	if (empty($new_rss['num']))
	{
		$error = true;
		$msg[] = $esynI18N['error_url'];
	}

	if (!$error)
	{
		$block['lang'] = $new_block['lang'];
		$block['title'] = $new_block['title'];
		$block['show_header'] = '1';
		$block['plugin'] = 'rss';
		$block['collapsible'] = '1';
		$block['type'] = 'smarty';
		$block['sticky'] = '1';
		$block['multi_language'] = '1';

		$recursive = (int)isset($_POST['recursive']);

		if (isset($_POST['categories']))
		{
			$_POST['categories'] = explode('|', $_POST['categories']);
			$_POST['categories'] = array_unique(array_map("intval", $_POST['categories']));
		}
		else
		{
			$_POST['categories'] = array();
		}

		if (!empty($_POST['categories']))
		{
			$categories = array();
			$categories = $_POST['categories'];
		}

		$esynAdmin->factory("Block");

		if (isset($_GET['do']) && 'edit' == $_GET['do'])
		{
			$new_rss['id'] = (int)$_GET['id'];

			if (!empty($categories))
			{
				$esynAdmin->setTable("rss_categories");
				$esynAdmin->delete("`rss_id` = '{$new_rss['id']}'");

				foreach($categories as $cId)
				{
					$esynAdmin->insert(array("rss_id"=>$new_rss['id'], "category_id"=>$cId));
				}
				$esynAdmin->resetTable();
			}

			$esynAdmin->setTable("rss");
			$esynAdmin->update($new_rss, "`id` = '{$new_rss['id']}'");
			$esynAdmin->resetTable();

			// generate block content
			$block['id'] = (int)$_POST['block_id'];
			$contents  = '{if isset($rss_' .$new_rss['id']. ') and is_array($rss_' .$new_rss['id']. ')}';
			$contents .= '<div class="ia-wrap">';
			$contents .= '{foreach $rss_' .$new_rss['id']. ' as $one_rss}';
			$contents .= '<div class="ia-item list clearfix">';
			$contents .= '<i class="icon-rss-sign" style="margin-right:3px; color:#faa701;"></i>';
			$contents .= '<a href="{$one_rss.link}" target="_blank">{$one_rss.title}</a>';
			$contents .= '<p class="text-small muted">{$one_rss.description|strip_tags|truncate:150:"...":false}</p>';
			$contents .= '<p class="date text-small muted pull-right"><i class="icon-calendar icon-gray"></i> {$one_rss.pubdate|date_format:$config.date_format}</p></div>';
			$contents .= '{/foreach}';
			$contents .= '</div>';
			$contents .= '{/if}';

			$block['contents'] = $contents;
			$esynBlock->update($block, $block['id']);

			$msg[] = $esynI18N['changes_saved'];
		}
		else
		{
			// insert RSS feed
			$esynAdmin->setTable("rss");
			$idRss = $esynAdmin->insert($new_rss);
            $esynAdmin->resetTable();

			// generate block content and insert it
			$contents  = '{if isset($rss_' .$idRss. ') and is_array($rss_' .$idRss. ')}';
			$contents .= '<div class="ia-wrap">';
			$contents .= '{foreach $rss_' .$idRss. ' as $one_rss}';
			$contents .= '<div class="ia-item list clearfix">';
			$contents .= '<i class="icon-rss-sign" style="margin-right:3px; color:#faa701;"></i>';
			$contents .= '<a href="{$one_rss.link}" target="_blank"></i>{$one_rss.title}</a>';
			$contents .= '<p class="text-small muted">{$one_rss.description|strip_tags|truncate:150:"...":false}</p>';
			$contents .= '<p class="date text-small muted pull-right"><i class="icon-calendar icon-gray"></i> {$one_rss.pubdate|date_format:$config.date_format}</p></div>';
			$contents .= '{/foreach}';
			$contents .= '</div>';
			$contents .= '{/if}';

			$block['contents'] = $contents;

            $idBlock = $esynBlock->insert($block);

            // set correct block name
            $block['name'] = 'block_rss_' . $idBlock;
            $esynAdmin->setTable("blocks");
            $esynAdmin->update(array('name' => $block['name']), "`id` = '{$idBlock}'");
            $esynAdmin->resetTable();

			// update RSS block_id
            $esynAdmin->setTable("rss");
			$esynAdmin->update(array('id_block' => $idBlock), "`id` = '{$idRss}'");
			$esynAdmin->resetTable();

			if (!empty($categories))
			{

				$esynAdmin->setTable("rss_categories");
				$cats = array();

				foreach($categories as $cId)
				{
					$cats[] = array("rss_id"=>$idRss,"category_id"=>$cId);
				}

				$esynAdmin->insert($cats);
				$esynAdmin->resetTable();
			}

			$msg[] = $esynI18N['rss_added'];
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

		$esynAdmin->setTable("rss");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", "1=1 ".$order, $start, $limit);
		$esynAdmin->resetTable();
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

	if ('remove' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("rss_categories");
			foreach($_POST['ids'] as $rss_id)
			{
				$esynAdmin->delete("`rss_id` = '{$rss_id}'");
			}
			$esynAdmin->resetTable();

			// delete blocks
			$esynAdmin->factory("Block");

			$esynAdmin->setTable("rss");
			foreach($_POST['ids'] as $rss_id)
			{
				$rss_info[] = $esynAdmin->one('`id_block`', "`id` = '{$rss_id}'");
			}
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();
			$esynBlock->delete($rss_info);

			$out['msg'] = $esynI18N['rss_deleted'];
		}
		else
		{
			$out['msg'] = $esynI18N['params_wrong'];
			$out['error'] = true;
		}
	}

	if ('update' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => false);

		if (!empty($_POST['field']) && !empty($_POST['value']) && !empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("rss");
			$esynAdmin->update(array($_POST['field'] => $_POST['value']), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
		else
		{
			$out['msg'] = $esynI18N['params_wrong'];
			$out['error'] = true;
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}
/*
 * ACTIONS
 */

$gTitle = $esynI18N['manage_rss'];

$gBc[1]['title'] = $esynI18N['manage_rss'];
$gBc[1]['url'] = 'controller.php?plugin=rss';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[1]['title'] = $esynI18N['manage_rss'];
		$gBc[1]['url'] = 'controller.php?plugin=rss';

		$gBc[2]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_rss'] : $esynI18N['add_rss'];
		$gTitle = $gBc[2]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?plugin=rss&amp;do=add", "icon" => "add.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?plugin=rss", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$id = (int)$_GET['id'];

	$esynAdmin->setTable("rss");
	$sql = 'SELECT t1.*, t2.`title` `title` FROM `'.$esynAdmin->mPrefix.'rss` t1 ';
	$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}blocks` t2 ";
	$sql .= "ON t1.`id_block` = `t2`.`id` ";
	$sql .= "WHERE `t1`.id = {$id}";
	$rss = $esynAdmin->getRow($sql);
	$esynAdmin->resetTable();

	$esynAdmin->setTable("rss_categories");
	$cats = $esynAdmin->all("`category_id`", "`rss_id` = '{$id}'");
	$esynAdmin->resetTable();

	if (!empty($cats))
	{
		foreach($cats as $category)
		{
			$categories[] = $category['category_id'];
		}
		$selected_categories = implode("|", $categories);

		$esynSmarty->assign('selected_categories', $selected_categories);
	}

	$esynSmarty->assign('rss', $rss);
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');