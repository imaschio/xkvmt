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

defined("IA_REALM") || define('IA_REALM', "error");

// error not specified then 404 :)
if (empty($_GET['error']) || preg_match("/\D/", $_GET['error']))
{
	$error = '404';
}
else
{
	$error = $_GET['error'];
}
// don't need them.
$_GET = $_POST = array();

if ($error == '404')
{
	header("HTTP/1.1 404 Not found");
}

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';
require_once IA_INCLUDES . 'view.inc.php';

$eSyndiCat->factory('Layout');

if (!array_key_exists($error, $esynI18N))
{
	$error = '404';
	header("HTTP/1.1 404 Not found");
}

if ($error == '671')
{
	header("HTTP/1.1 403 Forbidden");
}

$eSyndiCat->startHook('error');

$esynSmarty->assign('title', $esynI18N[$error]);
$esynSmarty->assign('header', $esynI18N['error'] . ': ' . $esynI18N[$error]);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['error']);

$esynSmarty->display('page.tpl');
