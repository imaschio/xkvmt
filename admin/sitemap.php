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

define('IA_REALM', "sitemap");
ini_set('memory_limit','512M');

$esynAdmin->factory('GYSMap');

$gBc[0]['title'] = $esynI18N['create_gy_sitemap'];
$gBc[0]['url'] = '';
$gTitle	= $esynI18N['create_gy_sitemap'];

require_once IA_ADMIN_HOME . 'view.php';

$error = false;
$msg = '';
global $msg;

$esynGYSMap->path_to_file = IA_TMP . 'sitemap' . IA_DS;

$esynSmarty->assign("disabled", "");

if (!isset($_POST['action']))
{
	$error_msg = $esynGYSMap->init();

	if (!empty($error_msg))
	{
		$error = true;
		esynMessages::setMessage($esynI18N[$error_msg], $error);
		$esynSmarty->assign("disabled", "disabled='disabled'");
	}
}

esynMessages::setMessage($esynI18N['recommend_recount'], esynMessages::SYSTEM);

if (isset($_POST['action']) && 'create' == $_POST['action'])
{
	$_SESSION['total'] = (int)$_POST['total'];
	$type_sitemap = $_POST['type_sitemap'];
	$items_count = (int)$_POST['items_count'];
	$stage_all = (int)$_POST['stage_all'];
	$start = (int)$_POST['start'];
	$limit = (int)$_POST['limit'];
	$stage = (int)$_POST['stage'];
	$item  = $_POST['item'];

	if (1 == $stage)
	{
		$_SESSION['num_records'] = 0;
		$_SESSION['start_categories'] = 0;
		$_SESSION['start_listings'] = 0;
		$_SESSION['query'] = '';
		$esynGYSMap->deleteOldSitemaps($type_sitemap);
		if ("google" == $type_sitemap)
		{
			$_SESSION['sm_file'] = $_SESSION['total'] > 40000 ? '1' : '';
			$esynGYSMap->writeGoogleHF($esynGYSMap->path_to_file."google".IA_DS."sitemap".$_SESSION['sm_file'].".xml", 'Header');
		}
		elseif ("yahoo" == $type_sitemap)
		{
			$esynGYSMap->writeToFile($esynGYSMap->path_to_file."yahoo".IA_DS."urllist.txt", IA_URL."\n");
		}
	}

	$sitemap = '';

	if ('categories' == $item)
	{
		$sitemap .= $esynGYSMap->buildCategoriesMap ($start, $limit, $type_sitemap);
	}

	if ('listings_pages' == $item)
	{
		$sitemap .= $esynGYSMap->buildListingsPagesMap ($start, $limit, $items_count, $type_sitemap);
	}

	if ('listings' == $item)
	{
		$sitemap .= $esynGYSMap->buildListingsMap ($start, $limit, $type_sitemap);
	}

	if ('pages' == $item)
	{
		$sitemap .= $esynGYSMap->buildPagesMap ($start, $limit, $type_sitemap);
	}

	if ('accounts' == $item)
	{
		$sitemap .= $esynGYSMap->buildAccountsMap ($start, $limit, $type_sitemap);
	}

	$esynAdmin->startHook('adminGYSMBuildMap');

	if ('google' == $type_sitemap)
	{
		$filename = $esynGYSMap->path_to_file."google".IA_DS."sitemap{$_SESSION['sm_file']}.xml";

		if ($stage == $stage_all)
		{
			$sitemap = $sitemap.$esynGYSMap->getGoogleFooter();
		}

		if (!$esynGYSMap->writeToFile($filename, $sitemap))
		{
			$error = true;
			$msg = "Cannot write to file ".$filename;
			echo "{error: '{$error}', msg: '{$msg}'}";
	        exit;
		}

	    if ($stage == $stage_all && $_SESSION['sm_file'] > 0)
	    {
	    	$msg = $esynGYSMap->getGoogleSMIndex($_SESSION['sm_file']);
	    	if (!empty($msg))
	    	{
	    		$error = true;
	    		echo "{error: '{$error}', msg: '{$msg}'}";
	       		exit;
	    	}
	    }
	}

	if ('yahoo' == $type_sitemap)
	{
		if (!$esynGYSMap->writeToFile($esynGYSMap->path_to_file."yahoo".IA_DS."urllist.txt", $sitemap))
		{
			$error = true;
			$msg = "Cannot write to file ".$esynGYSMap->path_to_file."yahoo".IA_DS."urllist.txt";
			echo "{error: '{$error}', msg: '{$msg}'}";
	        exit;
		}

	}

	echo "{msg: '{$msg}', error: '{$error}'}";
	exit;
}
else
{
	$items = $esynGYSMap->getTotal();

	$esynAdmin->startHook('adminGYSMTotalItems');

	$esynSmarty->assignByRef('items', $items);
}

$esynSmarty->display('sitemap.tpl');
