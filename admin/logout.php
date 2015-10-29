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

unset($_SESSION['admin_name'], $_SESSION['admin_pwd'], $_SESSION['admin_lastAction'], $_SESSION['frontendManageMode']);

setcookie('admin_lasturl', '', time() - 3600, '/');

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->display('logout.tpl');
