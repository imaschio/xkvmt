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

define("IA_REALM", "listingstatus");

// requires common header file
require_once('.'.IA_DS.'includes'.IA_DS.'header.php');

$msg = '';
$error = false;
$output = null;

include(IA_INCLUDES.'view.inc.php');

$esynSmarty->caching = false;

if (isset($_POST['link_url']))
{
	require_once(IA_CLASSES.'esynUtf8.php');

	esynUtf8::loadUTF8Core();
	esynUtf8::loadUTF8Util('ascii', 'validation', 'bad');
	
	$url = $_POST['link_url'];

	if (!esynValidator::isUrl($url))
	{
		$error = true;
		$msg[] = $esynI18N['error_empty_link_status_url'];
	}
	else 
	{
		$eSyndiCat->setTable("listings");
		$url = (substr($url, -1) == "/") ? substr($url, 0, -1) : $url;

		$or_display_url = array_key_exists('displayurl', $eSyndiCat->mPlugins) ? "OR TRIM(TRAILING '/' FROM `display_url`) LIKE '{$url}'" : '';

		$result_status = $eSyndiCat->row("`status`, `date`", "TRIM(TRAILING '/' FROM `url`) LIKE '{$url}' $or_display_url ");

		if (empty($result_status))
		{
			$error = true;
			$msg = $esynI18N['error_not_found_link_status_url'];
		}
		else
		{
			$result_status['date'] = date("j, M Y", strtotime($result_status['date']));
		}
		
		if (!$error)
		{
			switch ($result_status['status'])
			{
				case "active":
					$msg[] = str_replace( "{date}", $result_status['date'], $esynI18N['active_link_status_url'] );
					break;
				case "banned":
					$msg[] = str_replace( "{date}", $result_status['date'], $esynI18N['banned_link_status_url'] );
					break;
				case "suspended":
					$msg[] = str_replace( "{date}", $result_status['date'], $esynI18N['suspended_link_status_url'] );
					break;
				case "approval":
					$msg[] = str_replace( "{date}", $result_status['date'], $esynI18N['approval_link_status_url'] );
					break;
			}
			
			$error = false;
		}
	}
}

// defines page title
$esynSmarty->assignByRef('title', $esynI18N['check_link_status']);

$eSyndiCat->factory("Layout");

// breadcrumb formation
esynBreadcrumb::add($esynI18N['check_link_status']);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');