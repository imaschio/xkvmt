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

define('IA_REALM', "redirect");

if (empty($_GET['id']) || $_GET['id']{0} == '0' || preg_match("/\D/", $_GET['id']))
{
	$_GET['error'] = "404";
	include './error.php';
	die();
}

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$id	= (int)$_GET['id'];

$eSyndiCat->factory("Listing");

$listing_url = $esynListing->one("`url`", "`id`='{$id}'");

if (empty($listing_url))
{
	$_GET['error'] = "404";
	include IA_HOME . 'error.php';
}
else
{
	header('Location: '.$listing_url);
}
exit;
