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

if ($esynConfig->getConfig('paypal_demo'))
{
	$form_action = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
}
else
{
	$form_action = 'https://www.paypal.com/cgi-bin/webscr';
}

$form_values['demo'] = $esynConfig->getConfig('paypal_demo');
$form_values['business'] = $esynConfig->getConfig('paypal_email');
$form_values['currency_code'] = $esynConfig->getConfig('paypal_currency_code');
$form_values['item_name'] = 'Payment for: ' . $invoice['item'];

$form_values['return'] = IA_URL . 'post_pay.php?action=complete&sec_key=' . $invoice['sec_key'];
$form_values['cancel_return'] = IA_URL . 'post_pay.php?action=canceled&sec_key=' . $invoice['sec_key'];
$form_values['notify_url'] = IA_URL . 'post_pay.php?file=ipn&sec_key=' . $invoice['sec_key'];
$form_values['sbt'] = 'Return to ' . $esynConfig->getConfig('site');

// Label of return button
$form_values['no_note'] = '1';
$form_values['rm'] = '2';
$form_values['no_shipping'] = '1';

// Recurring payment
if (isset($plan) && !empty($plan) && (bool)$plan['recurring'])
{
	$form_values['cmd'] = '_xclick-subscriptions';
	$form_values['a3'] = $invoice['total'];
	$form_values['p3'] = $plan['duration'];
	$form_values['t3'] = strtoupper($plan['units_duration']);
	$form_values['src'] = '1';
	$form_values['sra'] = '1';
}
else
{
	$form_values['cmd'] = '_xclick';
	$form_values['amount'] = $invoice['total'];
}