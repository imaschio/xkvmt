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

define('IA_REALM', "searches");

if (isset($_GET['action']))
{

	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$dir = isset($_GET['dir']) ? $_GET['dir'] : '';
		$sort = isset($_GET['sort']) ? 'ORDER BY `' . $_GET['sort'] . '` ' . $dir : '';

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("searches");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `search_id` as `id`", "1=1" . $sort, $start, $limit);

		foreach ($out['data'] as $key => $value)
		{
			$search_results = unserialize($value['search_result']);

			$out['data'][$key]['search_result'] = '';
			if (is_array($search_results))
			{
				foreach ($search_results as $name => $count)
				{
					$out['data'][$key]['search_result'] .= '<b>' . $esynI18N[$name] . '</b>: ' . $count . '<br />';
				}
			}
		}

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

	if ('remove' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => true);

		$searches = $_POST['ids'];

		if (!is_array($searches) || empty($searches))
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}
		else
		{
			$searches = array_map(array('esynSanitize', 'sql'), $searches);
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($searches))
			{
				foreach($searches as $comment)
				{
					$ids[] = (int)$comment;
				}

				$where = "`search_id` IN ('".join("','", $ids)."')";
			}
			else
			{
				$id = (int)$searches;

				$where = "`search_id` = '{$id}'";
			}

			$esynAdmin->setTable("searches");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['deleted'];

			$out['error'] = false;
		}
	}

	if ('update' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => true);

		$field = esynSanitize::sql($_POST['field']);
		$value = esynSanitize::sql($_POST['value']);

		if (is_array($_POST['ids']))
		{
			$searches = array_map(array('esynSanitize', 'sql'), $_POST['ids']);
		}
		elseif (!empty($accounts))
		{
			$searches[] = esynSanitize::sql($_POST['ids']);
		}
		else
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}

		if (!empty($field) && !empty($value) && !empty($searches))
		{
			foreach($searches as $comment)
			{
				$ids[] = (int)$comment;
			}

			$where = "`search_id` IN ('".join("','", $ids)."')";

			$esynAdmin->setTable("searches");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
			$out['error'] = false;
		}
		else
		{
			$out['msg'] = 'Wrong parametes';
			$out['error'] = true;
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_searches'];

$gBc[1]['title'] = $gTitle;
$gBc[1]['url'] = '';

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');
