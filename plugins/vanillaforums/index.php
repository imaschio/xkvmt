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

global $esynConfig, $esynAccountInfo;

// include jsConnect library
require_once IA_PLUGINS . 'vanillaforums' . IA_DS . 'includes' . IA_DS . 'functions.jsconnect.php';

$client_id = $esynConfig->getConfig('vanilla_client_id');
$secret_key = $esynConfig->getConfig('vanilla_secret_key');

if (!empty($client_id) && !empty($secret_key))
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Pragma: no-cache");

	header('Content-Type: application/json');

	$vanilla_user = array();
	if ($esynAccountInfo)
	{
		// fill in the user information in a way that Vanilla can understand
		$vanilla_user['uniqueid']	= $esynAccountInfo['id'];
		$vanilla_user['name'] 		= $esynAccountInfo['username'];
		$vanilla_user['email']		= $esynAccountInfo['email'];
		$vanilla_user['photourl']	= IA_URL . 'uploads/' . $esynAccountInfo['avatar'];
	}

	// generate the jsConnect string
	WriteJsConnect($vanilla_user, $_GET, $client_id, $secret_key, false);
	exit;
}