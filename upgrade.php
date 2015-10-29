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

define('IA_REALM', "upgrade");

$msg    = array();
$error  = false;

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$listing_id = (isset($_GET['id']) && !empty($_GET['id'])) ? (int)$_GET['id'] : false;
$salt = (isset($_GET['salt']) && !empty($_GET['salt'])) ? $_GET['salt'] : false;

if (!$listing_id || !$salt)
{
	$_GET['error'] = "404";
	require IA_HOME . 'error.php';
	exit;
}

$eSyndiCat->factory("Listing", "Category", "Plan", "Layout");

$listing = $esynListing->row('*', "`id` = :id AND `expire_salt` = :salt", array('id' => $listing_id, 'salt' => $salt));

if (!$listing)
{
	$_GET['error'] = "404";
	require IA_HOME . 'error.php';
	exit;
}

$plans = $esynPlan->all('*', "`status` = 'active' AND `item` = 'listing' AND FIND_IN_SET('upgrade', `pages`)");

require_once IA_INCLUDES . 'view.inc.php';

if (isset($_POST['upgrade']))
{
	$plan_id = isset($_POST['plan']) ? (int)$_POST['plan'] : false;

	$update = array();
	$addit = array();

	$update['expire_salt'] = '';

	if ($plan_id)
	{
		$plan = $esynPlan->row('*', '`id` = :plan_id', array('plan_id' => $plan_id));
	}

	if ($plan)
	{
		$update['plan_id'] = $plan['id'];

		if ($plan['cost'] > 0)
		{
			$item['item'] = 'listing';
			$item['method'] = 'postPaymentUpgrade';

			$sec_key = $esynPlan->preparePayment($listing, $_POST['payment_gateway'], $plan['cost'], $plan);

			$redirect_url = IA_URL . 'pre_pay.php?sec_key=' . $sec_key;

			esynUtil::go2($redirect_url);
		}
		else
		{
			$update['status'] = 'approval';

			if ($plan['period'] > 0)
			{
				$update['expire_notif'] = $plan['expire_notif'];
				$update['expire_action'] = $plan['expire_action'];

				$addit['expire_date'] = "`expire_date` + INTERVAL {$plan['period']} DAY";
				$addit['last_modified'] = 'NOW()';
			}
		}
	}
	else
	{
		$update['status'] = 'approval';

		if ($esynConfig->getConfig('expire_period') > 0)
		{
			$period = (int)$esynConfig->getConfig('expire_period');

			$update['expire_notif'] = (int)$esynConfig->getConfig('expire_notif');
			$update['expire_action'] = $esynConfig->getConfig('expire_action');

			$addit['expire_date'] = "`expire_date` + INTERVAL {$period} DAY";
			$addit['last_modified'] = 'NOW()';
		}
	}

	$eSyndiCat->setTable('listings');
	$eSyndiCat->update($update, "`id` = '{$listing['id']}'", array(), $addit);
	$eSyndiCat->resetTable();

	// get updated listing
	$sql = "SELECT `l`.*, `c`.`path` "
		 . "FROM `{$eSyndiCat->mPrefix}listings` `l` "
		 . "LEFT JOIN `{$eSyndiCat->mPrefix}categories` `c` "
		 . "ON `l`.`category_id` = `c`.`id` "
		 . "WHERE `l`.`id` = '{$listing['id']}'";

	$listing = $esynListing->getRow($sql);

	$replace['expire_date'] = strftime($esynConfig->getConfig('date_format'), strtotime($listing['expire_date']));

	$eSyndiCat->mMailer->add_replace($replace);
	$eSyndiCat->mMailer->AddAddress($listing['email']);

	$eSyndiCat->mMailer->Send('listing_upgraded', $listing['account_id'], $listing);

	$msg[] = _t('success_upgrade_listing');
}

/** defines page title **/
$title = $esynI18N[IA_REALM];

$esynSmarty->assign('title', $title);
$esynSmarty->assign('listing', $listing);
$esynSmarty->assign('plans', $plans);

$esynSmarty->display('upgrade.tpl');
