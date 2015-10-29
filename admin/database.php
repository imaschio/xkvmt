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

define('IA_REALM', "database");

esynUtil::checkAccess();

if (empty($_GET['page']))
{
	$_GET['page'] = "sql";
}

$reset_options = array(
	'categories'		=> $esynI18N['reset_categories'],
	'listings'			=> $esynI18N['reset_listings'],
	'accounts'			=> $esynI18N['reset_accounts'],
	'category_clicks'	=> $esynI18N['reset_category_clicks'],
	'listing_clicks'	=> $esynI18N['reset_listing_clicks']
);

$esynAdmin->factory('DbControl');

$tables = $esynDbControl->getTables();

$error = false;
$msg = '';

if (isset($_GET['type']) && $_GET['page'] == 'consistency')
{
	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	if($_GET['call'] == 'ajax')
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];
		$level = (int)$_GET['level'];

		// count active listings
		if ($_GET['type'] == 'num_all_listings')
		{
			$esynAdmin->setTable("categories");
			$ids = $esynAdmin->onefield("`id`", "1=1 ORDER BY `id`", array(), $start, $limit);
			$esynAdmin->resetTable();

			if($ids)
			{
				$esynAdmin->factory("Category");
				$esynCategory->adjustNumListings($ids);

				$out['msg'] = 'ok';
			}
			else
			{
				$out['error'] = true;
			}
		}

		// repair listing aliases
		if ($_GET['type'] == 'listing_alias')
		{
			$esynAdmin->setTable("listings");

			$sql = "SELECT l.`id`, l.`title`, l.`title_alias` ";
			$sql .= "FROM {$esynAdmin->mPrefix}listings l ";

			if (isset($_GET['all_listings']) && '1' == $_GET['all_listings'])
			{
				$sql .= "WHERE 1=1 ";
			}
			else
			{
				$sql .= "WHERE l.`title_alias` = '' ";
			}

			$sql .= "ORDER BY l.`id` LIMIT {$start},{$limit}";

			$listings = $esynAdmin->getAll($sql);

			$cnt = 0;

			if ($listings)
			{
				foreach ($listings as $key => $listing)
				{
					$cnt++;
					$title_alias = esynUtil::getAlias($listing['title']);

					$listing['title_alias'] = $title_alias;
					$esynAdmin->update($listing);
				}

				$out['msg'] = 'ok';
				$out['num_updated'] = $cnt;
			}
			else
			{
				$out['error'] = true;
				$out['level'] = $level;
				$out['num_updated'] = -1;
			}

			$esynAdmin->resetTable();
		}

		// rebuild categories pathes
		if ($_GET['type'] == 'categories_paths')
		{
			$esynAdmin->setTable("categories");

			if ($_GET['prelaunch'])
			{
				$out = $esynAdmin->row('MAX(`level`) as `level`');
				$out['msg'] = 'ok';
			}
			else
			{
				$sql = "SELECT c.`id`, c.`parent_id`, c.`title`, p.`path` "
					 . "FROM {$esynAdmin->mPrefix}categories c "
					 . "LEFT JOIN {$esynAdmin->mPrefix}categories p "
					 . "ON p.`id` = c.`parent_id` "
					 . "WHERE c.`parent_id` != '-1' "
					 . "AND c.`level` = {$level} "
					 . "ORDER BY c.`id` LIMIT {$start},{$limit}";

				$categories = $esynAdmin->getAll($sql);

				$cnt = 0;
				if($categories)
				{
					foreach ($categories as $key => $category)
					{
						$cnt++;
						$title_path = esynUtil::getAlias($category['title']);

						$category['path'] = $category['path'] ? $category['path'] . '/' . $title_path : $title_path;
						$esynAdmin->update($category);
					}

					$out['msg'] = 'ok';
					$out['num_updated'] = $cnt;
				}
				else
				{
					$out['error'] = true;
					$out['level'] = $level;
					$out['num_updated'] = -1;
				}

				$esynAdmin->resetTable();
			}
		}

		// rebuild categories relation
		if ($_GET['type'] == 'categories_relation')
		{
			if ($_GET['prelaunch'])
			{
				$esynAdmin->query("TRUNCATE TABLE {$esynAdmin->mPrefix}flat_structure");
				$out['msg'] = 'ok';
			}
			else
			{
				$esynDbControl->rebuildFlat($start, $limit);
				$out['msg'] = 'ok';
			}
		}

		// repair categories level
		if ($_GET['type'] == 'categories_level')
		{
			function getParent($id, $level)
			{
				global $esynAdmin;

				$parent = $esynAdmin->one("`parent_id`", "`id` = '{$id}'");

				if ($parent)
				{
					$level++;
					$level = getParent($parent, $level);

				}
				return $level;
			}

			$esynAdmin->setTable('categories');
			$categories = $esynAdmin->all("`id`, `parent_id`", '1=1 ORDER BY `id`', '', $start, $limit);
			if (!empty($categories))
			{
				foreach ($categories as $category)
				{
					$level = $category['parent_id'] == '-1' ? 0 : 1;

					if($category['parent_id'] > 0)
					{
						$level = getParent($category['parent_id'], 2);
					}
					$esynAdmin->update(array('level' => $level), "`id` = {$category['id']}");
				}
				$out['msg'] = 'ok';
			}
			else
			{
				$out['error'] = true;
			}
			$esynAdmin->resetTable();
		}

		// delete crossed listings
		if ($_GET['type'] == 'listing_categories')
		{
			// get listings
			$esynAdmin->setTable("listings");
			$listings = $esynAdmin->onefield("`id`", null, null, $start, $limit);
			$esynAdmin->resetTable();

			$esynAdmin->setTable("listing_categories");
			$lc = $esynAdmin->keyvalue("`listing_id`,`category_id`", "`listing_id` IN (" . implode(',', $listings) . ") GROUP BY `listing_id`");
			$esynAdmin->resetTable();

			$to_delete = '';
			$cnt =0;
			if ($lc)
			{
				foreach ($lc as $id)
				{
					if (empty($listings[$id]))
					{
						$to_delete[] = $id;
						$cnt++;
					}
				}
			}
			if ($to_delete)
			{
				$to_delete = implode(',', $to_delete);
				$esynAdmin->setTable("listing_categories");
				$delete_it = $esynAdmin->delete("`listing_id` NOT IN (".$to_delete.")");
				$esynAdmin->resetTable();
			}

			// get categories
			$esynAdmin->setTable("categories");
			$cats = $esynAdmin->onefield("`id`", null, null, $start, $limit);
			$esynAdmin->resetTable();

			$esynAdmin->setTable("listing_categories");
			$lc = $esynAdmin->keyvalue("`category_id`,`listing_id`", "`category_id` IN (" . implode(',', $cats) . ") GROUP BY `category_id`");
			$esynAdmin->resetTable();

			$to_delete = '';
			$cnt =0;

			if($lc)
			{
				foreach ($lc as $id=>$value)
				{
					if (empty($cats[$id]))
					{
						$to_delete[] = $id;
						$cnt++;
					}
				}
			}

			if ($to_delete)
			{
				$to_delete = implode(',', $to_delete);
				$esynAdmin->setTable("listing_categories");
				$delete_it = $esynAdmin->delete('`category_id` NOT IN ('.$to_delete.')');
				$esynAdmin->resetTable();
			}

			$out['msg'] = 'ok';
		}

		$esynAdmin->startHook('phpAdminDatabaseConsistencyTypeAjax');

		echo esynUtil::jsonEncode($out);
		exit;
	}

	if ($_GET['type'] == 'optimize_tables')
	{
		$query = "OPTIMIZE TABLE ";
		foreach($tables as $t)
		{
			$query .= "`".$t."`,";
		}
		$query = rtrim($query, ",");
		$esynAdmin->query($query);

		esynMessages::setMessage($esynI18N['done']);
		esynUtil::reload(array("type"=>null));
	}
	elseif ($_GET['type'] == 'repair_tables')
	{
		$query = "REPAIR TABLE ";
		foreach($tables as $t)
		{
			$query .= "`".$t."`,";
		}
		$query = rtrim($query, ",");
		$esynAdmin->query($query);

		esynMessages::setMessage($esynI18N['done']);
		esynUtil::reload(array("page"=>"consistency", "type"=>null));
	}

	$esynAdmin->startHook('phpAdminDatabaseConsistencyType');
}

if (isset($_POST['run_update']))
{
	if ($_FILES)
	{
		$filename = $_FILES['sql_file']['tmp_name'];
	}
	else
	{
		if (is_scalar($_POST['sqlfile']))
		{
			$_POST['sqlfile'] = str_replace(array("`","~","/","\\"), "", $_POST['sqlfile']);
		}
		else
		{
			$_POST['sqlfile'][0] = str_replace(array("`","~","/","\\"), "", $_POST['sqlfile'][0]);
		}

		$filename = IA_HOME . 'updates' . IA_DS . $_POST['sqlfile'] . '.sql';
	}

	if (!($f = fopen ($filename, "r" )))
	{
		$error = true;
		$msg = str_replace("{filename}", $filename, $esynI18N['cant_open_sql']);
	}
	else
	{
		$sql = '';
		while ($s = fgets ($f, 10240))
		{
			$s = trim($s);

			if (!empty($s))
			{
				if ($s[0] == '#' || $s[0] == '')
				{
					continue;
				}
			}
			else
			{
				continue;
			}

			if ($s[strlen($s) - 1] == ';')
			{
				$sql .= $s;
			}
			else
			{
				$sql .= $s;
				continue;
			}
			$sql = str_replace("{prefix}", $esynAdmin->mPrefix, $sql);
			$sql = str_replace("{mysql_version}", (IA_MYSQLVER == '41' ? 'ENGINE=MyISAM DEFAULT CHARSET=utf8' : 'TYPE=MyISAM'), $sql);

			$esynAdmin->query($sql);
			$sql = "";
		}
		fclose($f);

		$msg = $esynI18N['upgrade_completed'];

		$esynAdmin->mCacher->clearAll();
		$esynAdmin->mCacher->clearTpl(array('admin', $esynConfig->gC('tmpl')));
	}

	esynMessages::setMessage($msg, $error);
	esynUtil::reload();
}

/** run query **/
if (isset($_POST['exec_query']))
{
	$esynAdmin->startHook('adminRunSqlQuery');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$queryOut = '';
	$error = false;

	$sql_query = $_POST['query'];

	if (!utf8_is_valid($sql_query))
	{
		$sql_query = utf8_bad_replace($sql_query);
	}

	$select = false;

	$tmp = $sql_query;
	$sql_queries = explode(";\r\n", $sql_query);

	$queries = 0;
	$error_msg = '';

	if (!empty($sql_queries) && is_array($sql_queries))
	{
		foreach ($sql_queries as $sql_query)
		{
			if (!empty($sql_query))
			{
				$numrows = 0;

				$sql_query = str_replace("{prefix}", $esynAdmin->mPrefix, $sql_query);
				$sql_query = str_replace("{mysql_version}", (IA_MYSQLVER == '41' ? 'ENGINE=MyISAM DEFAULT CHARSET=utf8' : 'TYPE=MyISAM'), $sql_query);

				$result = $esynAdmin->query($sql_query);
				if ($result)
				{
					$numrows = $rows = $esynAdmin->getNumRows($result);

					if ($rows)
					{
						$rows .= ($rows > 1) ? ' rows' : ' row';
						$msg = "<b>Query OK:</b> {$rows} selected.";
					}
					else
					{
						$msg = '<b>Query OK:</b> ' . $esynAdmin->getAffected() . ' rows affected.';
					}
					$queries++;
				}
				else
				{
					$error = true;
					$error_msg = '<b>Query Failed:</b><br />' . $esynAdmin->getError();
					break 1;
				}
			}
		}
	}

	$sql_query = $tmp;
	unset($tmp);

	if ($queries > 1)
	{
		$msg = "<b>{$queries}</b> sql queries executed <b>successfully</b>.</br>";
	}

	$msg .= $error_msg;

	if ($numrows && !$error)
	{
		// get field names
		$fieldNames = $esynAdmin->getFieldNames($result);

		$queryOut .= '<table cellspacing="0" cellpadding="0" class="common" id="sql_table" style="visibility: hidden;"><thead>';
		$i = 0;
		foreach ($fieldNames as $field)
		{
			$queryOut .= '<th ' . (!$i ? 'class="first"' : '') . '>' . $field->name . '</th>';
			$i++;
		}
		$queryOut .= '</tr></thead><tbody>';

		$numFields = $esynAdmin->getNumFields($result);
		while ($row = $esynAdmin->fetchRow($result))
		{
			$queryOut .= '<tr>';
			for ($i = 0; $i < $numFields; $i++)
			{
				$queryOut .= '<td ' . (!$i ? 'class="first"' : '') . '>' . esynSanitize::html($row[$i]) . '</td>';
			}
			$queryOut .= '</tr>';
		}
		$queryOut .= '</tbody></table>';
	}

	esynMessages::setMessage($msg, $error);
}

// export
if (isset($_POST['export']))
{
	$esynAdmin->factory("DbControl");

	if (!isset($_POST['tbl']) || empty($_POST['tbl']))
	{
		$error = true;
		$msg[] = $esynI18N['export_tables_incorrect'];
	}

	if (!$error)
	{
		$out = PHP_EOL . '#  MySQL COMMON INFORMATION:' . PHP_EOL;
		$out .= '#  MySQL CLIENT INFO: ' . $esynAdmin->getInfo('client_info') . PHP_EOL;
		$out .= '#  MySQL HOST INFO: ' . $esynAdmin->getInfo('host_info') . PHP_EOL;
		$out .= '#  MySQL PROTOCOL VERSION: ' . $esynAdmin->getInfo('proto_info') . PHP_EOL;
		$out .= '#  MySQL SERVER VERSION: ' . $esynAdmin->getInfo('server_info') . PHP_EOL . PHP_EOL;
		$out .= '#  __MySQL DUMP GENERATED BY ESYNDICAT__ #' . PHP_EOL . PHP_EOL . PHP_EOL;
		$out .= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . PHP_EOL . PHP_EOL;

		$drop = isset($_POST['drop']) ? $_POST['drop'] : 0;
		$showcolumns = isset($_POST['showcolumns']) ? $_POST['showcolumns'] : 0;
		$real_prefix = isset($_POST['real_prefix']) ? $_POST['real_prefix'] : 0;

		if (isset($_POST['sql_structure']) && empty($_POST['sql_data']))
		{
			if (!empty($_POST['tbl']) && is_array($_POST['tbl']))
			{
				foreach($_POST['tbl'] as $value)
				{
					$sql .= $esynDbControl->makeStructureBackup($value, $drop, $real_prefix);
				}
			}
			else
			{
				$sql = $esynDbControl->makeDbStructureBackup($drop, $real_prefix);
			}
		}
		elseif (isset($_POST['sql_data']) && empty($_POST['sql_structure']))
		{
			if (!empty($_POST['tbl']) && is_array($_POST['tbl']))
			{
				foreach($_POST['tbl'] as $value)
				{
					$sql .= $esynDbControl->makeDataBackup($value, $showcolumns, $real_prefix);
				}
			}
			else
			{
				$sql = $esynDbControl->makeDbDataBackup($showcolumns, $real_prefix);
			}
		}
		elseif (isset($_POST['sql_structure']) && isset($_POST['sql_data']))
		{
			if (!empty($_POST['tbl']) && is_array($_POST['tbl']))
			{
				foreach($_POST['tbl'] as $value)
				{
					$sql .= $esynDbControl->makeFullBackup($value, $drop, $showcolumns, $real_prefix);
				}
			}
			else
			{
				$sql = $esynDbControl->makeDbBackup($drop, $showcolumns, $real_prefix);
			}
		}
		$sql = $out.$sql;

		if (isset($_POST['save_file']))
		{
			$sqlfile = IA_HOME.$esynConfig->getConfig('backup');

			/** saves to server **/
			if ('server' == $_POST['savetype'])
			{
				array_walk_recursive($_POST['tbl'], array("esynUtil", 'filenameEscape'));
				$sqlfile .= !empty($_POST['tbl']) ? date("Y-m-d").'-'.$_POST['tbl'][0].'.sql' : 'db-'.date("Y-m-d").'.sql' ;
				if (!$fd = @fopen($sqlfile, 'w'))
				{
					@chmod($sqlfile, 0775);
					$error = true;
					$msg = str_replace("{filename}", $sqlfile, $esynI18N['cant_open_sql']);
				}
				elseif (fwrite($fd,$sql) === false)
				{
					$error = true;
					$msg = str_replace("{filename}", $sqlfile, $esynI18N['cant_write_sql']);

					fclose($fd);
				}
				else
				{
					$tbls = '';
					if (!empty($_POST['tbl']))
					{
						$tbls = implode(", ",$_POST['tbl']);
					}
					$msg = str_replace("{table}", $tbls, $esynI18N['table_dumped']);
					$msg = str_replace("{filename}", $sqlfile, $msg);

					fclose($fd);
				}
			}
			/** saves to computer **/
			elseif ('client' == $_POST['savetype'])
			{
				$sqlfile = ($_POST['tbl']) ? date('Y-m-d').'-'.$_POST['tbl'][0].'.sql' : 'db_'.date('Y-m-d').'.sql';
				if (function_exists('gzencode') && isset($_POST['gzip_compress']) && $_POST['gzip_compress'])
				{
					$sql = gzencode($sql);
					$sqlfile .= '.gz';

					header('Content-Type: application/x-gzip');
					header('Content-Encoding: gzip');
				}
				else
				{
					header('Content-Type: text/plain');
				}

				header('Content-Disposition: attachment; filename="'.$sqlfile.'"');

				echo $sql;
				exit;
			}
		}
		else
		{
			/** show on the screen **/
			$out_sql = $sql;
		}
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_POST['reset']))
{
	if (empty($_POST['options']))
	{
		$error = true;
		$msg[] = $esynI18N['reset_choose_table'];
	}

	if (!$error)
	{
		foreach($_POST['options'] as $option)
		{
			$esynDbControl->reset($option);
		}

		$msg[] = $esynI18N['reset_success'];

		$esynAdmin->mCacher->clearAll();
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']) && 'fields' == $_GET['action'] && !empty($_GET['table']))
{
	$array = array();
	if ($fields = $esynAdmin->describe($_GET['table']))
	{
		foreach ($fields as $key => $value)
		{
			$array[] = $value['Field'];
		}
	}
	echo esynUtil::jsonEncode($array);
	exit;
}

if (isset($_POST['action']))
{
	if ('getCode' == $_POST['action'])
	{
		$esynAdmin->setTable("hooks");
		$code = $esynAdmin->one("`code`", "`id` = :id", array('id' => (int)$_POST['hook']));
		$esynAdmin->resetTable();

		echo $code;
	}

	if ('setCode' == $_POST['action'])
	{
		$code = esynSanitize::sql($_POST['code']);

		$esynAdmin->setTable("hooks");
		$esynAdmin->update(array('code' => $_POST['code']), "`id` = :id", array('id' => (int)$_POST['hook']));
		$esynAdmin->resetTable();
	}
	exit;
}

$gBc[0]['title'] = $esynI18N['manage_database'];
$gBc[0]['url'] = 'controller.php?file=database&amp;page=sql';

switch ($_GET['page'])
{
	case 'export':

		// check for huge data
		$esynAdmin->setTable('listings');
		$num_listings = $esynAdmin->one('COUNT(*)');
		$esynAdmin->resetTable();

		if ($num_listings > 50000)
		{
			esynMessages::setMessage($esynI18N['info_export_huge_data'], esynMessages::ALERT);
		}

		$dirname = IA_HOME . $esynConfig->getConfig('backup');
		if (!is_writable($dirname))
		{
			$backup_is_not_writeable = str_replace('{dirname}', $dirname, $esynI18N['directory_not_writable']);
		}

		$gBc[1]['title'] = $esynI18N['export'];

		break;

	case 'sql':
		$gBc[1]['title'] = $esynI18N['sql_management'];

		break;

	case 'import':
		$gBc[1]['title'] = $esynI18N['import'];

		break;

	case 'consistency':
		$gBc[1]['title'] = $esynI18N['check_consistency'];

		// collect statistics
		$stats_items = array();

		$sql = "SELECT SQL_CALC_FOUND_ROWS `id` FROM {$esynAdmin->mPrefix}categories";
		$esynAdmin->query($sql);
		$stats_items['categories'] = $esynAdmin->foundRows();

		$sql = "SELECT SQL_CALC_FOUND_ROWS `id` FROM {$esynAdmin->mPrefix}listings";
		$esynAdmin->query($sql);
		$stats_items['listings'] = $esynAdmin->foundRows();

		$esynAdmin->startHook('phpAdminDatabaseConsistencyStats');

		break;

	case 'reset':
		$gBc[1]['title'] = $esynI18N['reset'];

		esynMessages::setMessage($esynI18N['reset_backup_alert'], esynMessages::SYSTEM);

		break;

	case 'hook_editor':
		$gBc[1]['title'] = $esynI18N['hook_editor'];

		esynMessages::setMessage($esynI18N['hook_editor_alert'], esynMessages::SYSTEM);

		$hooks = array();

		$esynAdmin->setTable("hooks");
		$all_hooks = $esynAdmin->all("`plugin`, `id`, `name`", "1 = 1 ORDER BY `plugin`");
		$esynAdmin->resetTable();

		if (!empty($all_hooks))
		{
			$hooks['coreversion']['title'] = 'Core';
			foreach ($all_hooks as $_hook)
			{
				$plugin = $_hook['plugin'];
				if ($plugin && in_array($plugin, array_keys($esynAdmin->mPlugins)))
				{
					$hooks[$plugin]['title'] = $plugin;
					$hooks[$plugin]['code'][] = $_hook;
				}
				else
				{
					$hooks['coreversion']['code'][] = $_hook;
				}
			}
		}

		break;

	default:
		break;

}

$gTitle = $gBc[1]['title'];

$actions = array(
	array("url" => "controller.php?file=database&amp;page=reset", "icon" => "reset_database.png", "label" => $esynI18N['reset']),
	array("url" => "controller.php?file=database&amp;page=consistency", "icon" => "tools.png", "label" => $esynI18N['check_consistency']),
	array("url" => "controller.php?file=database&amp;page=sql", "icon" => "view_database.png", "label" => $esynI18N['sql_management']),
	array("url" => "controller.php?file=database&amp;page=export", "icon" => "export.png", "label" => $esynI18N['export']),
	array("url" => "controller.php?file=database&amp;page=import", "icon" => "import.png", "label" => $esynI18N['import']),
	array("url" => "controller.php?file=database&amp;page=hook_editor", "icon" => "hook_editor.png", "label" => $esynI18N['hook_editor']),
	array("url" => 'adminer.php" target="_blank', "icon" => "data.png", "label" => 'Adminer')
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_POST['exec_query']) && !empty($queryOut))
{
	$esynSmarty->assign('queryOut', $queryOut);
}

if (isset($sql_query))
{
	$esynSmarty->assign('sql_query', $sql_query);
}

// gets sql files that exist in updates directory and converts files in array
$upgrades = array();

$path = IA_HOME . 'updates' . IA_DS;
if (is_dir($path))
{
	$files = scandir($path);
	foreach($files as $file)
	{
		if (substr($file, 0, 1) != ".")
		{
			if (is_file($path . $file))
			{
				$upgrades[] = substr($file, 0, count($file) - 5);
			}
		}
	}
}

if (isset($backup_is_not_writeable))
{
	$esynSmarty->assign('backup_is_not_writeable', $backup_is_not_writeable);
}

if (isset($out_sql))
{
	$esynSmarty->assign('out_sql', $out_sql);
}

if (isset($hooks))
{
	$esynSmarty->assign('hooks', $hooks);
}

if(isset($stats_items))
{
	$esynSmarty->assign('stats_items', $stats_items);
}

$esynSmarty->assign('plugins', $esynAdmin->mPlugins);
$esynSmarty->assign('upgrades', $upgrades);
$esynSmarty->assign('tables', $tables);
$esynSmarty->assign('reset_options', $reset_options);

$esynSmarty->display('database.tpl');
