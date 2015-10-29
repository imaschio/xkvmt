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

define('IA_REALM', "post_pay");

$sec_key = isset($_GET['sec_key']) ? $_GET['sec_key'] : false;

$msg = array();
$error = false;

$plan = array();
$item = array();

$transaction = array();

$replace_handler = false;

$payment_page = 'purchase.tpl';

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory('Layout');

include IA_INCLUDES . 'view.inc.php';

$eSyndiCat->startHook('phpFrontPostPayBeforeProcess');

if (!$sec_key)
{
	$_GET['error'] = '404';
	require IA_HOME . 'error.php';
	exit;
}

$eSyndiCat->setTable('transactions');
$invoice = $eSyndiCat->row('*', "`sec_key` = :sec_key ORDER BY `id` DESC", array('sec_key' => $sec_key));
$eSyndiCat->resetTable();

if (empty($invoice))
{
	$_GET['error'] = '404';
	require IA_HOME . 'error.php';
	exit;
}

if (!empty($invoice['plan_id']))
{
	$eSyndiCat->setTable('plans');
	$plan = $eSyndiCat->row('*', "`id` = '{$invoice['plan_id']}'");
	$eSyndiCat->resetTable();
}

if (!empty($invoice['item']) && !empty($invoice['item_id']))
{
	$table = $invoice['item'] . 's';
	$table_exists = $eSyndiCat->getOne("SHOW TABLES LIKE '" . $eSyndiCat->mPrefix . $table . "'");

	if ($table_exists)
	{
		$eSyndiCat->setTable($table);
		$item = $eSyndiCat->row('*', "`id` = '{$invoice['item_id']}'");
		$eSyndiCat->resetTable();
	}
}

$eSyndiCat->startHook('phpFrontPostPayAfterItem');

$gTitle = $esynI18N['purchase_page'];

esynBreadcrumb::add($gTitle);

$gateway = $invoice['payment_gateway'];

if (!empty($gateway))
{
	$file = isset($_GET['file']) ? $_GET['file'] : 'post';

	$post_payment = IA_PLUGINS . $gateway . IA_DS . $file . '.php';

	if (file_exists($post_payment))
	{
		require_once $post_payment;
	}
}

if ('passed' == $transaction['status'])
{
	$msg[] = $esynI18N['purchase_successful'];
}
elseif ('pending' == $transaction['status'])
{
	$msg[] = $esynI18N['payment_pending'];
}
elseif ('failed' == $transaction['status'])
{
	$msg[] = $esynI18N['payment_failed'];
	$error = true;
}
else
{
	$msg[] = $esynI18N['payment_unknown_status'];
	$error = true;
}

$transaction['id'] = $invoice['id'];

$eSyndiCat->startHook('phpFrontPostPayBeforeHandler');

if (!$replace_handler)
{
	$update_transaction['email'] = $transaction['email'];
	$update_transaction['order_number'] = $transaction['order_number'];
	$update_transaction['status'] = $transaction['status'];

	if ('pending' == $transaction['status'] && isset($transaction['pending_reason']))
	{
		$update_transaction['pending_reason'] = $transaction['pending_reason'];
	}

	$eSyndiCat->setTable('transactions');

	if (isset($transaction['subscr']) && $transaction['subscr'] && "" == $invoice['status'])
	{
		$update_transaction['item_id'] = $invoice['item_id'];
		$update_transaction['plan_id'] = $invoice['plan_id'];
		$update_transaction['payment_gateway'] = $invoice['payment_gateway'];
		$update_transaction['total'] = $invoice['total'];
		$update_transaction['item'] = $invoice['item'];
		$update_transaction['sec_key'] = $invoice['sec_key'];
		$update_transaction['method'] = $invoice['method'];
		$update_transaction['subscr_id'] = $transaction['subscr_id'];

		$transaction['id'] = $eSyndiCat->insert($update_transaction, array('date' => 'NOW()'));
	}
	else
	{
		$eSyndiCat->update($update_transaction, '`sec_key` = :sec_key', array('sec_key' => $sec_key));
	}

	$eSyndiCat->resetTable();

	$itemClass = $eSyndiCat->esynFactory($invoice['item'], $invoice['plugin']);

	$method = 'postPayment';

	if (!empty($invoice['method']))
	{
		if (method_exists($itemClass, $invoice['method']))
		{
			$method = $invoice['method'];
		}
	}

	$itemClass->$method($item, $plan, $invoice, $transaction);
}

$esynSmarty->assign('title', $gTitle);

$esynSmarty->assign('invoice', $invoice);
$esynSmarty->assign('plan', $plan);
$esynSmarty->assign('item', $item);
$esynSmarty->assign('transaction', $transaction);

$esynSmarty->display($payment_page);
