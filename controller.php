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

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$plugin_alias = isset($_GET['plugin']) ? esynSanitize::sql($_GET['plugin']) : $vals[0];

if (!empty($plugin_alias))
{
	$file = (isset($_GET['file']) && !empty($_GET['file'])) ? esynSanitize::paranoid($_GET['file']) : 'index';
	$plugin_alias = esynSanitize::paranoid($plugin_alias);

	$aliases = array();
	foreach ($eSyndiCat->mPlugins as $name => $alias)
	{
		$aliases[$name] = (isset($eSyndiCat->mPluginsAliases[$name])) ? $eSyndiCat->mPluginsAliases[$name] : $name;
	}

	if (!in_array($plugin_alias, $aliases))
	{
		$_GET['error'] = esynUtil::ERROR_NOT_FOUND;
		include IA_HOME . 'error.php';
		exit;
	}

	$plugin = array_search($plugin_alias, $aliases);

	if (is_file(IA_HOME . 'plugins' . IA_DS . $plugin . IA_DS . $file . '.php'))
	{
		define('IA_CURRENT_PLUGIN', $plugin);
		define('IA_PLUGIN_TEMPLATE', IA_PLUGINS . IA_CURRENT_PLUGIN . IA_DS . 'templates' . IA_DS);

		require_once IA_PLUGINS . $plugin . IA_DS . $file . '.php';
	}
	else
	{
		$_GET['error'] = esynUtil::ERROR_NOT_FOUND;
		include IA_HOME . 'error.php';
		exit;
	}
}
else
{
	$_GET['error'] = esynUtil::ERROR_NOT_FOUND;
	include IA_HOME . 'error.php';
	exit;
}
