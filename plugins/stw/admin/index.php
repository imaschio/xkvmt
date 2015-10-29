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

define('IA_REALM', "stw");

/*
 * ACTIONS
 */
$path = IA_UPLOADS . 'stw_thumbs/';

if (isset($_POST['clear_system_thumbs']))
{
	$system_files = array(
		'no_response.jpg',
		'quota.jpg',
		'bandwidth.jpg'		
	);

	if ($handle = opendir($path))
	{
		while (false !== ($entry = readdir($handle)))
		{
			if (in_array($entry, $system_files))
			{
				unlink($path.$entry);
			}
		}
	}
}

if (isset($_POST['clear_all_thumbs']))
{
	$files = glob($path . '*', GLOB_MARK);
	foreach ($files as $file)
	{
		unlink($file);
	}
}


$gNoBc = false;

$gTitle = $esynI18N['manage_stw'];

$gBc[1]['title'] = $esynI18N['manage_stw'];
$gBc[1]['url'] = 'controller.php?plugin=stw';

require_once(IA_ADMIN_HOME.'view.php');

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');