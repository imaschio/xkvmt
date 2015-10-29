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

global $invoice, $item, $esynConfig, $eSyndiCat, $plan, $esynSmarty;

if ($esynConfig->getConfig('off_line_payment_send_email'))
{
	$mail_AddAddress = '';
	$mail = $eSyndiCat->mMailer;

	if (!empty($item))
	{
		if ('listing' == $invoice['item'])
		{
			$eSyndiCat->setTable('categories');
			$item['path'] = $eSyndiCat->one("`path`", "`id` = '".$item['category_id']."'");
			$eSyndiCat->resetTable();
		}

		if (isset($item['email']))
		{
			$mail_AddAddress = $item['email'];
		}
		elseif (isset($item['account_id']))
		{
			$eSyndiCat->setTable('accounts');
			$mail_AddAddress = $eSyndiCat->one("`email`", "`id` = '".$item['account_id']."'");
			$eSyndiCat->resetTable();
		}
		else
		{
			exit;
		}

		if (!empty($item['title']))
		{
			$item['title'] = esynUtil::getAlias($item['title']);
		}

		if (!empty($item['path']))
		{
			$url_listing = $dirlink = IA_URL.$item['path']."/".$item['title']."-l{$item['id']}.html";
		}
		else
		{
			$url_listing = '';
		}

		$replace = array(
			"url_listing" => $url_listing,
			"payment_for_submit" => $plan['cost'],
			"currency_symbol" => $esynConfig->getConfig('currency_symbol')
		);

		$mail->add_replace($replace);

		if (!empty($mail_AddAddress))
		{
			$account = !empty($item['account_id']) ? $item['account_id'] : '';
			$listing = $invoice['item'] == 'listing' ? $invoice['item_id'] : '';

			$mail->AddAddress($mail_AddAddress);
			$mail->Send("off_line_payment", $account, $listing);
			$mail->ClearAddresses();
		}
	}
}

esynUtil::go2(IA_URL . 'post_pay.php?action=complete&sec_key=' . $invoice['sec_key']);
