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

define('IA_REALM', "account_logout");

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

// delete cookies
setcookie('account_id', '', time() - 3600, '/');
setcookie('account_pwd', '', time() - 3600, '/');

(isset($_GET['action']) && 'logout' == $_GET['action']) && esynUtil::go2('logout.php');

$eSyndiCat->factory('Layout');

include IA_INCLUDES . 'view.inc.php';

$eSyndiCat->startHook('afterAccountLogout');

// breadcrumb formation
esynBreadcrumb::add($esynI18N['logout']);

$esynSmarty->assign('title', $esynI18N['logout']);
$esynSmarty->assign('header', _t('logged_out'));

$esynSmarty->display('page.tpl');
