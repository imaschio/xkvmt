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

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

if (isset($_POST['status']) && $_POST['status'] == 'connected')
{
	$out = array();

	$eSyndiCat->setTable('accounts');

	$fb_user = $_POST['fb_user'];

	$acc = $eSyndiCat->row('*', "`fb_id`='" . $fb_user['id'] . "'");

	if ($acc === false)
	{
		$username = $fb_user['username'];

		if ($eSyndiCat->exists("`username` = '" . $username . "'") || empty($username))
		{
			$username = 'fb_' . $fb_user['id'];
		}

		$acc = array(
			'fb_id' => $fb_user['id'],
			'username' => $username,
			'password' => md5( rand() . rand() ),
			'email' => $fb_user['email'],
			'status' => 'active'
		);

		$acc['id'] = $eSyndiCat->insert($acc, array('date_reg' => 'NOW()'));
	}

	$pwd = crypt($acc['password'], IA_SALT_STRING);

	$expireTime = time() + 60 * 60 * 24 * 14;

	setcookie("account_id", $acc['id'], $expireTime, DIRECTORY_SEPARATOR);
	setcookie("account_pwd", $pwd, $expireTime, DIRECTORY_SEPARATOR);

	$eSyndiCat->resetTable();

	$out['status'] = $_POST['status'];

	echo esynUtil::jsonEncode($out);
}