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

define('IA_REALM', "importer");

esynUtil::checkAccess();

$error = false;
$msg = array();

$importers_path = IA_INCLUDES . 'imports' . IA_DS;

if (isset($_POST['action']))
{
	if ('import' == $_POST['action'])
	{
		$importer = $_POST['importer'];
		$item = $_POST['item'];
		$table = isset($_POST['table']) ? $_POST['table'] : '';

		// init class
		require_once $importers_path . $importer . IA_DS . 'esynDataImporter.php';
		$esynDI = new esynDataImporter();

		$esynDI->mConfig['dbhost'] = $_POST['host'];
		$esynDI->mConfig['dbuser'] = $_POST['username'];
		$esynDI->mConfig['dbpass'] = $_POST['password'];
		$esynDI->mConfig['dbname'] = $_POST['database'];
		$esynDI->mConfig['dbport'] = '3306';
		$esynDI->mConfig['prefix'] = $esynDI->mPrefix = $_POST['prefix'];
		$esynDI->connect(true);

		$esynDI->setMigrationItem($item, $table);

		$start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
		$limit = !empty($esynDI->mItemsToMigrate[$item]['limit']) ? $esynDI->mItemsToMigrate[$item]['limit'] : 100;

		// get total number of records
		$total = $esynDI->getTotal();

		// get records
		$records = $esynDI->getRecords($start, $limit);

		$start = empty($records) || ($start + $limit) > $total ? 0 : $start + $limit;
		if ($records)
		{
			// process item records
			$esynDI->processItem($records);

			$percent = $start * 100 / $total;
			$percent = round($percent, 2);

			$out['nums'] = $start;
		}
		else
		{
			$percent = 100;
		}
		$out['msgs'] = "<div>Progress: {$percent}%</div>";
		$out['percents'] = $percent;

		// run postProcess actions for the item
		if (100 >= $percent)
		{
			$esynDI->postProcess();
		}

		echo esynUtil::jsonEncode($out);
		exit;
	}
	elseif ('check' == $_POST['action'])
	{
		$error = false;
		$msg = array();

		$importer = $_POST['importer'];
		$host     = $_POST['host'];
		$database = $_POST['database'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$prefix   = $_POST['prefix'];

		if (empty($importer))
		{
			$error = true;
			$msg[] = 'Please choose version of importer.';
		}

		if (empty($host))
		{
			$error = true;
			$msg[] = 'Please fill up database host.';
		}

		if (empty($database))
		{
			$error = true;
			$msg[] = 'Please fill up database name.';
		}

		if (empty($username))
		{
			$error = true;
			$msg[] = 'Please fill up database username.';
		}

		if (empty($password))
		{
			$error = true;
			$msg[] = 'Please fill up database password.';
		}

		if (!$error)
		{
			$link = mysql_connect($host, $username, $password);

			if (!$link)
			{
				$error = true;
				$msg[] = $esynI18N['database_connection_error'];
			}

			if (!mysql_select_db($database, $link))
			{
				$error = true;
				$msg[] = $esynI18N['database_selection_error'];
			}

			if (!$error && file_exists($importers_path . $importer))
			{
				require_once $importers_path . $importer . IA_DS . 'esynDataImporter.php';
				$esynDI = new esynDataImporter();

				$esynDI->mConfig['dbhost'] = $_POST['host'];
				$esynDI->mConfig['dbuser'] = $_POST['username'];
				$esynDI->mConfig['dbpass'] = $_POST['password'];
				$esynDI->mConfig['dbname'] = $_POST['database'];
				$esynDI->mConfig['dbport'] = '3306';
				$esynDI->mConfig['prefix'] = $esynDI->mPrefix = $_POST['prefix'];
				$esynDI->connect(true);

				$importer_chunk = explode('_', $importer);
				$chosen_version = $importer_chunk[1];

				if (!$esynDI->validateVersion($chosen_version))
				{
					$error = true;
					$msg[] = 'Versions mismatch. Probably incorrect prefix or database tables are used for a different version.';
				}
				else
				{
					$items_to_migrate = $esynDI->getItemsToMigrate();
					foreach($items_to_migrate as $item_to_migrate)
					{
						$esynDI->setMigrationItem($item_to_migrate);

						$out['items'][] = array('name' => $item_to_migrate, 'total' => $esynDI->getTotal());
					}
				}
			}
		}

		$out['error'] = $error;
		$out['msg'] = $msg;

		echo esynUtil::jsonEncode($out);
	}
	exit;
}

if (isset($_GET['action']))
{
	if ('getdatabases' == $_GET['action'])
	{
		$databases = $esynAdmin->getDatabases();

		if (empty($databases))
		{
			$databases = '';
		}

		echo esynUtil::jsonEncode($databases);
	}
	exit;
}

$importers = array();

$gBc[0]['title'] = $esynI18N['manage_importer'];
$gBc[0]['url'] = 'controller.php?file=importer';

$gTitle = $esynI18N['manage_importer'];

require_once IA_ADMIN_HOME . 'view.php';

if (file_exists($importers_path))
{
	if (is_dir($importers_path))
	{
		$files = scandir($importers_path);

		foreach($files as $file)
		{
			if (substr($file, 0, 1) != ".")
			{
				if (is_dir($importers_path . $file))
				{
					$importers[] = $file;
				}
			}
		}
	}
}
else
{
	esynMessages::setMessage($esynI18N['no_importers'], esynMessages::SYSTEM);
}

if (isset($success))
{
	$esynSmarty->assign('success', $success);
}

$esynSmarty->assign('importers', $importers);

$esynSmarty->display('importer.tpl');
