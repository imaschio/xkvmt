<?php

global $eSyndiCat, $esynSmarty, $esynConfig, $category, $esynAccountInfo;

$eSyndiCat->loadPluginClass('Banner', 'banners', 'esyn');

$esynBanner = new esynBanner();

$paid_positions = $esynBanner->get_blocks();

$temp = false;
$bids = array();
$eSyndiCat->setTable('blocks');
$positions = $eSyndiCat->keyvalue('`id`,`position`', "`plugin` = 'banners'");
$eSyndiCat->resetTable();

if ('view_listing' == IA_REALM)
{
	global$esynCategory;
	if (!isset($_GET['cat']) || !$esynCategory->validPath($_GET['cat']))
	{
		$_GET['cat'] = "";
	}

	$category['id'] = $esynCategory->one("`id`", "`path` = '".$_GET['cat']."'");
}

foreach($positions as $bpos)
{
	$temp = $esynBanner->getBanner($bpos, $category['id'], $esynConfig->getConfig("num_{$bpos}_banners"));

	if(!empty($temp))
	{
		if(!file_exists(IA_PLUGINS."banners".IA_DS."templates".IA_DS."banner.tpl"))
		{
			$bannerError = "This file can not be found in template directory: banner.tpl";

			$esynSmarty->assignByRef('bannerError', $bannerError);
		}
		else
		{
			$esynSmarty->assign($bpos.'_banner', $temp);
		}

		foreach($temp as $banner)
		{
			$bids[] = $banner['id'];
		}
	}
	
	$add_ads = $paid_positions && esynUtil::ArraySearchRecursive($bpos, $paid_positions) ? $bpos : false;
	$add_ads = !$esynConfig->getConfig('banner_guests_submit') && !$esynAccountInfo ? false : $add_ads;
	$esynSmarty->assign('add_ads_'.$bpos, $add_ads);
}
if(!empty($bids))
{
	// Just mini optimize :) to avoid IN(<single element>)
	if(count($bids) > 1)
	{
		$wh = "`id` IN('".implode("','",$bids)."')";
	}
	else
	{
		$wh = "`id`='".$bids[0]."'";
	}

	// first parameters expects actual values
	// while third expects RDBMS functions, variables etc. like NOW(), UNIX_TIMESTAMP()
	// e.g first params will generate "showed='showed+1'" while third ("showed=showed+1")
	$esynBanner->query("UPDATE `$esynBanner->mTable` SET `showed` = `showed`+'1' WHERE $wh");
}
unset($temp);