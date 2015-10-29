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

define('IA_REALM', "purchase");
$replace_handler = false;

global $transaction;
global $error;
global $msg;
global $replace_handler;

$eSyndiCat->factory('Layout');

if (!empty($transaction))
{
	if (!isset($transaction['plan_title']) || empty($transaction['plan_title']))
	{
		$eSyndiCat->setTable("plans");
		$transaction['plan_title'] = $eSyndiCat->one("title", "`id` = :id AND `item` = '{$transaction['item']}'", array('id' => $transaction['plan_id']));
		$eSyndiCat->resetTable();
	}

	$eSyndiCat->setTable("transactions");
	$transaction_id = $eSyndiCat->insert($transaction, array('date' => 'NOW()'));
	$eSyndiCat->resetTable();

	$eSyndiCat->startHook('phpFrontPurchaseBeforeHandler');

	if (!$replace_handler)
	{
		$item = array('transaction_id' => $transaction_id);
		$item['status'] = !$error ? 'active' : 'approval';

		$eSyndiCat->setTable($transaction['item']);
		$eSyndiCat->update($item, "`id` = :id", array('id' => $transaction['item_id']));
		$item = $eSyndiCat->row("*", "`id` = :id", array('id' => $transaction['item_id']));
		$eSyndiCat->resetTable();

		if ($item['account_id'] != 0)
		{
		    $eSyndiCat->setTable('accounts');
	    	$item['email'] = $eSyndiCat->one("email", "`id` = :id", array('id' => $item['account_id']));
	    	$eSyndiCat->resetTable();
		}
		elseif (!isset($item['email']))
		{
		    $item['email'] = "";
		}

		$item['plan'] = $transaction['plan_title'];

		$eSyndiCat->mMailer->AddAddress($item['email']);
		$replace = array(
			"cost" => $transaction['total'],
			"transactionID" => $transaction['transaction_id'],
			"subscriptionID" => isset($transaction['subscr_id']) ? $transaction['subscr_id'] : '',
		);
		$eSyndiCat->mMailer->add_replace($replace);
		$eSyndiCat->mMailer->Send('item_payment', $item['account_id']);
	}
}

include IA_INCLUDES . 'view.inc.php';

$esynSmarty->assign('title', $esynI18N['payment_notif']);

$esynSmarty->display('purchase.tpl');
