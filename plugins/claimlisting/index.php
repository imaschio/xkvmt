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

define('IA_REALM', "claimlisting");

// requires common header file
require_once IA_INCLUDES . 'view.inc.php';

$eSyndiCat->factory('Layout');

$eSyndiCat->loadPluginClass("CheckOwner", 'claimlisting', "esyn");
$esynCheckOwner = new esynCheckOwner();

if (isset($_POST['listing_string']) && $_POST['action'] == 'check')
{
    $temp = $esynCheckOwner->CheckString($_POST['listing_string'], (int)$_POST['id_listing']);

    if ($temp == 1)
    {
        if ($esynCheckOwner->CheckStringOnUrl($_POST['listing_string'], (int)$_POST['id_listing']) == 1)
        {
            echo 1;
            exit();
        }
        echo 0;
    }
    else
    {
        echo $temp;
    }
	exit();
}

if (isset($_GET['listing']) && !empty($_GET['listing']))
{
    $idlisting = (int)$_GET['listing'];

    if (isset($_SESSION['string_url_update']) && $_SESSION['string_url_update'] == $esynCheckOwner->GetCheckOwnerCod($idlisting))
    {
    	if (isset($esynAccountInfo) && !empty($esynAccountInfo['id']))
    	{
	    	$eSyndiCat->setTable('listings');
	    	$eSyndiCat->update(array('account_id'=>$esynAccountInfo['id']), "`id` = '$idlisting'");
	    	$eSyndiCat->resetTable();
    	}
	    unset($_SESSION['string_url_update']);
        esynUtil::go2(IA_URL."suggest-listing.php?edit=".$idlisting);
    }
    else
    {
    	unset($_SESSION['string_url_update']);
        esynUtil::go2(IA_URL);
    }
}

if (isset($vals[1]) && ctype_digit($vals[1]))
{
	$id_listing = (int)$vals[1];

	$eSyndiCat->setTable('listings');
	$listing_acc_id = $eSyndiCat->one("account_id", "`id` = '{$id_listing}'");
	$eSyndiCat->resetTable();
	if ($listing_acc_id != $esynAccountInfo['id'])
	{
		$string = $esynCheckOwner->GetCheckOwnerCod($id_listing);
	
		$eSyndiCat->setTable('listings');
		$url = $eSyndiCat->one("url", "`id` = '{$id_listing}'");
		$url = trim($url, " /");
		$eSyndiCat->resetTable();
	
	    $text = str_replace('{url}', $url, $esynI18N['claim_text']);
	    $text = str_replace('{owner_code}', $string, $text);
	    $esynSmarty->assign('text', $text);
	    $esynSmarty->assign('owner_code', $string);
	    $esynSmarty->assign('id', $id_listing);
	
	    $text = str_replace('{url}', $url, $esynI18N['claim_text2']);
	    $text = str_replace('{owner_code}', $string, $text);
	    $domain = "verification";
	    $text = str_replace('{domain}', $domain, $text);
	    $esynSmarty->assign('text1', $text);
		$esynSmarty->assign('is_yours', false);
	
	    $tmp = 1;
	}
	else
	{
		$url = IA_URL."suggest-listing.php?edit=".$id_listing;
		$its_your_listing = str_replace('{url}', $url, $esynI18N['its_your_listing']);
		$esynSmarty->assign('its_your_listing', $its_your_listing);
		$esynSmarty->assign('is_yours', true);
	}
}
else
{
    $_GET['error'] = "404";
    require(IA_HOME."error.php");
    exit;
}

$esynSmarty->assign("title", $esynI18N['claim_this_link']);

esynBreadcrumb::add($esynI18N['claim_this_link']);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');