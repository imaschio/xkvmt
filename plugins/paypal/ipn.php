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

define("DEBUG_IPN", true);

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'post')
{
	$response = validateIPN();
	global $invoice;

	if (!empty($response) && strcmp($response[1], 'VERIFIED') != 0)
	{
		// Exit since PayPal returned status other than VERIFIED
		exit;
	}
	// TODO: Check total
	// Check crypted sum
	/*if (strcmp($crypted_sum, crypt(sprintf("%.2f", $total), $esynConfig->getConfig('paypal_secret_word'))) != 0)
	{
		$error = 666;
	}*/

	$transaction = $invoice;

	$transaction['email'] = $_POST['payer_email'];
	if ('Completed' == $_POST['payment_status'])
	{
		if ('web_accept' == $_POST['txn_type'])
		{
			$transaction['subscr'] = 0;
		}

		if (in_array($_POST['txn_type'], array('subscr_payment', 'subscr_eot')))
		{
			$transaction['subscr'] = 1;
			$transaction['subscr_id'] = $_POST['subscr_id'];
			if ('subscr_payment' == $_POST['txn_type'])
			{
				$transaction['subscr_type'] = 'payment';
			}

			if ('subscr_eot' == $_POST['txn_type'])
			{
				$transaction['subscr_type'] = 'eot';
			}
		}

		$transaction['order_number'] = $_POST['txn_id'];
		$transaction['status'] = 'passed';
	}
	elseif ('Pending' == $_POST['payment_status'])
	{
		$transaction['status'] = 'pending';
		$transaction['pending_reason'] = $_POST['pending_reason'];
	}
	elseif ('Failed' == $_POST['payment_status'])
	{
		$transaction['status'] = 'failed';
	}
}

function validateIPN()
{
	global $esynConfig;

	$response = array();

	// Generate request

	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value)
	{
		$value = urlencode($value);
		$req .= "&{$key}={$value}";
	}

	$host = $esynConfig->getConfig('paypal_demo') ? 'www.sandbox.paypal.com' : 'www.paypal.com';
	// Post back to PayPal to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Host: {$host}\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen('ssl://' . $host, 443, $errno, $errstr, 30);
	if ($fp)
	{
		fwrite($fp, $header . $req);
		$res = '';

		while (!feof($fp))
		{
			$res .= fgets($fp, 1024);
		}
		fclose($fp);
		// Process paypal response
		$response = explode("\r\n\r\n", $res);
		if (DEBUG_IPN)
		{
			ob_start();

			echo "POST VARIABLES:\r\n\r\n";
			print_r($_POST);
			echo "\r\n\r\n";
			echo "RESPONSE:\r\n\r\n";
			print_r($response);
			echo "\r\n";

			$debug = ob_get_contents();
			ob_end_clean();

			$fp = fopen(IA_TMP . 'ipndebug.txt', 'a');
			fwrite($fp, $debug);
			fclose($fp);
		}
	}
	return $response;
}