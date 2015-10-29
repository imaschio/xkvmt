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

define('IA_REALM', "pre_pay");

$sec_key = isset($_GET['sec_key']) ? $_GET['sec_key'] : false;

$msg = array();
$error = false;

$plan = array();
$item = array();

$form_values = array();
$form_action = '';
$form_custom = '';

$payment_page = 'pay.tpl';

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

if (!$sec_key)
{
	$_GET['error'] = '404';
	require IA_HOME . 'error.php';
	exit;
}

$eSyndiCat->setTable('transactions');
$invoice = $eSyndiCat->row('*', "`sec_key` = :sec_key", array('sec_key' => $sec_key));
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

$eSyndiCat->startHook('phpFrontPrePayAfterItem');

include IA_INCLUDES . 'view.inc.php';

$gTitle = $esynI18N['purchase_page'];
esynBreadcrumb::add($gTitle);

$gateway = $invoice['payment_gateway'];

if (!empty($gateway))
{
	$pre_payment = IA_PLUGINS . $gateway . IA_DS . 'pre.php';
	if (file_exists($pre_payment))
	{
		require_once $pre_payment;
	}

	$form_custom = IA_PLUGINS . $gateway . IA_DS . 'templates' . IA_DS . 'form.tpl';
	if (file_exists($form_custom))
	{
		$esynSmarty->assign('form_custom', $form_custom);
	}
}

$esynSmarty->assign('title', $gTitle);

$esynSmarty->assign('invoice', $invoice);
$esynSmarty->assign('plan', $plan);
$esynSmarty->assign('item', $item);

$esynSmarty->assign('form_values', $form_values);
$esynSmarty->assign('form_action', $form_action);

$esynSmarty->display($payment_page);
