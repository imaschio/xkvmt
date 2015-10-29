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

$replace_handler = false;

$transaction = $invoice;
$transaction['status'] = "pending";
$transaction['email'] = !empty($item['email']) ? $item['email'] : "";
$transaction['order_number'] = "Alternate Payment";

$payment_page = IA_HOME . 'plugins' . IA_DS . "alternate_payment" . IA_DS . "templates" . IA_DS . "off-line-payment.tpl";

if ('listing' == $invoice['item'])
{
	$eSyndiCat->setTable('categories');
	$item['path'] = $eSyndiCat->one("`path`", "`id` = '".$item['category_id']."'");
	$eSyndiCat->resetTable();

	if (isset($item['title']) && !empty($item['title']))
	{
		$item['title'] = esynUtil::getAlias($item['title']);
	}
}

$url_lisitng = '';

if (!empty($item['path']))
{
	$url_lisitng = $dirlink = IA_URL.$item['path']."/".$item['title']."-l{$item['id']}.html";
}

$alt_text = $esynConfig->getConfig('off_line_payment_msg');

$url_lisitng = !empty($url_lisitng) ? '<a href="'.$url_lisitng.'">'.$url_lisitng.'</a>' : '' ;
$alt_text = str_replace('{url_lisitng}', $url_lisitng, $alt_text);
$alt_text = str_replace('{payment_for_submit}', $invoice['total'], $alt_text);
$alt_text = str_replace('{currency_symbol}', $esynConfig->getConfig('currency_symbol'), $alt_text);

$esynSmarty->assign('title', $esynI18N['alternate_payment_method']);

esynBreadcrumb::add($esynI18N['alternate_payment_method']);

$esynSmarty->assign('alt_text', nl2br($alt_text));