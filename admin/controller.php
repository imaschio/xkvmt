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

if (isset($_GET['plugin']))
{
	$_plugin = esynSanitize::paranoid($_GET['plugin']);
}

// set controller file
$file = (isset($_GET['file']) && !empty($_GET['file'])) ? esynSanitize::paranoid($_GET['file']) : 'index';

$esynAdmin->setTable("admin_pages");
$fileExist = $esynAdmin->exists("`file` = :file", array('file' => $file));
$esynAdmin->resetTable();

if ($fileExist)
{
	$includefile = !empty($_plugin) ? IA_PLUGINS . $_plugin . IA_DS . 'admin' . IA_DS . $file . '.php' : IA_ADMIN_HOME . $file . '.php';

	if (is_file($includefile) && file_exists($includefile))
	{
		if (!empty($_plugin))
		{
			define('IA_CURRENT_PLUGIN', $_plugin);
			define('IA_PLUGIN_TEMPLATE_URL', IA_URL . 'plugins' . IA_DS . IA_CURRENT_PLUGIN . IA_DS . 'admin' . IA_DS . 'templates' . IA_DS);
			define('IA_PLUGIN_TEMPLATE', IA_PLUGINS . IA_CURRENT_PLUGIN . IA_DS . 'admin' . IA_DS . 'templates' . IA_DS);
			define('IA_PLUGIN_INCLUDES', IA_PLUGINS . IA_CURRENT_PLUGIN . IA_DS . 'includes' . IA_DS);
		}

		$gBc[0]['title'] = $esynI18N['manage_plugins'];
		$gBc[0]['url'] = 'controller.php?file=plugins';

		require_once $includefile;
	}
	else
	{
		$msg = "Cannot find the following file: {$includefile}";

		$gTitle = 'Error';

		require_once '.' . IA_DS . 'view.php';

		$esynSmarty->assign('gTitle', 'Error');
		$esynSmarty->assign('error', $msg);
		$esynSmarty->display('error.tpl');
	}
}
else
{
	$msg = "The file parameter is wrong. Please check the URL.";

	$gTitle = 'Error';

	require_once '.' . IA_DS . 'view.php';

	$esynSmarty->assign('gTitle', 'Error');
	$esynSmarty->assign('error', $msg);
	$esynSmarty->display('error.tpl');
}
