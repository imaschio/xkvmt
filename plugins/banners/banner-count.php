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

define('IA_REALM', "banner_count");

//slight change
if (empty($_GET['id']) || preg_match("/\D/", $_GET['id']) || ($id=(int)$_GET['id']) < 1)
{
	header("HTTP/1.1 404 Not found");
	print("Powered by <b><a href=\"http://www.esyndicat.com\" style=\"color:red;text-decoration:underline;\" >eSyndicat Pro</a></b>");
	exit;
}

global $eSyndiCat;
$ip = esynUtil::getIpAddress();

$eSyndiCat->esynFactory("banner", "banners");

if (!$esynBanner->checkClick($id, $ip))
{
	$esynBanner->click($id, $ip);
}