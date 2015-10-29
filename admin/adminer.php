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

define('IA_REALM', 'adminer');

require_once '.' . DIRECTORY_SEPARATOR . 'header.php';

esynUtil::checkAccess();

// process admin panel actions
if (isset($_GET['file']) && $_GET['file'] == 'default.css')
{
	header('Content-Type: text/css');
	echo file_get_contents(IA_INCLUDES . 'adminer' . IA_DS . 'adminer.css');
	exit;
}

define('SID', true);

$DB_HOST = IA_DBHOST;
$DB_USER = IA_DBUSER;
$DB_PASSWORD = IA_DBPASS;
$DB_DRIVER = 'server';
$DB_PERMANENT = 0;

$_GET['username'] = IA_DBUSER;
$_GET['server'] = IA_DBHOST;
$_GET['driver'] = 'mysql';
$_GET['db'] = IA_DBNAME;
$_SESSION['pwds']['server'][IA_DBHOST][IA_DBUSER] = IA_DBPASS;

include IA_INCLUDES . 'adminer' . IA_DS . 'adminer.php';
die();
