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

define('IA_REALM', "update");

esynUtil::checkAccess();

$esynAdmin->factory("Update");

$msg = '';
$error = false;
$success = false;
$notification = array();

/**
 * ACTIONS
 */
if (isset($_POST['update']))
{
	$esynUpdate->doUpdateCore();

	$msg[] = $esynUpdate->getMsg();
	$msg[] = $esynUpdate->getUpdateInfo();
	$success = $esynUpdate->success;
	$error = !$success;

	esynMessages::setMessage($msg, $error);
}

$gTitle = $esynI18N['update_version'];

$gBc[0]['title'] = $esynI18N['update_version'];
$gBc[0]['url'] = 'controller.php?file=update';

require_once IA_ADMIN_HOME . 'view.php';

if (!$success)
{
	esynMessages::setMessage($esynI18N['update_notice'], esynMessages::SYSTEM);
}

$esynSmarty->assign('success', $success);

$esynSmarty->display('update.tpl');
