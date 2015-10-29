<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.0.3
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
 *	 Copyright 2007-2011 Intelliants LLC
 *	 http://www.intelliants.com/
 *
 ******************************************************************************/

define("IA_REALM", "coupons");

$id = false;
if(isset($_GET['id']) && !preg_match('/\D/', $_GET['id']))
{
	$id = (int)$_GET['id'];
}

$esynAdmin->LoadClass("JSON");
$esynAdmin->loadPluginClass("Coupon", "coupons", 'esyn', true);
$Coupon = new esynCoupon;

$statuses = array(
	"inactive"	=>	$esynI18N["inactive"],
	"active"	=>	$esynI18N["active"]
);

$error = false;
$msg = array();

define("ITEMS_PER_PAGE", 20);

if (isset($_POST['save']))
{
	if(!defined('IA_NOUTF'))
	{
		require_once(IA_CLASSES.'esynUtf8.php');

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$temp['coupon_code']	= $_POST['coupon_code'];
	$temp['discount']		= $_POST['discount'];
	$temp['description']	= $_POST['description'];
	$temp['time_used']		= intval($_POST['time_used']);

	if(utf8_is_valid($temp['description']))
	{
		$temp['description'] = utf8_bad_replace($temp['description']);
	}

	$temp['status']	= (isset($_POST['status']) && array_key_exists($_POST['status'], $statuses)) ? $_POST['status'] : 'approval';

	if(isset($_POST['do']) && 'edit' == $_POST['do'])
	{
		$Coupon->update($temp, "`id` = '".intval($_POST['id'])."'");
		$msg[] = $esynI18N['changes_saved'];
	}
	elseif (isset($_POST['do']) && 'add' == $_POST['do'])
	{
		$Coupon->insert($temp);
		$msg[] = $esynI18N['coupon'].' '.$esynI18N['added'];
	}
	esynUtil::reload(array("do" => null));
}

/*
 * AJAX
 */
if(isset($_GET['action']))
{
	$json = new Services_JSON();

	if('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$out = array('data' => '', 'total' => 0);

		$out['total'] = $Coupon->one("COUNT(*)");
		$out['data'] = $Coupon->getByStatus('', $start, $limit);
	}

	if(empty($out['data']))
	{
		$out['data'] = '';
	}

	echo $json->encode($out);
	exit;
}

if(isset($_POST['action']))
{
	$json = new Services_JSON();

	if('remove' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => true);

		$coupon = $_POST['ids'];

		if(!is_array($coupon) || empty($coupon))
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}
		else
		{
			$coupon = array_map(array('esynSanitize', 'sql'), $coupon);
			$out['error'] = false;
		}

		if(!$out['error'])
		{
			if(is_array($coupon))
			{
				foreach($coupon as $new)
				{
					$ids[] = (int)$new;
					$Coupon->delete("`id` = '".(int)$new."'");
				}
			}
			else
			{
				$id = (int)$new;
				$Coupon->delete("`id` = '$id'");
			}

			$out['msg'] = count($coupon) > 1 ? $esynI18N['coupons_deleted'] : $esynI18N['coupon_deleted'];
			$out['error'] = false;
		}
	}

	if ('update' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => true);

		$field = $_POST['field'];
		$value = $_POST['value'];

		if(is_array($_POST['ids']))
		{
			$coupon = $_POST['ids'];
		}
		elseif(!empty($accounts))
		{
			$coupon[] = $_POST['ids'];
		}
		else
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}

		if(!empty($field) && !empty($value) && !empty($coupon))
		{
			foreach($coupon as $new)
			{
				$ids[] = (int)$new;
			}

			$where = "`id` IN ('".join("','", $ids)."')";

			$Coupon->update(array($field => $value), $where);

			$out['msg'] = 'Changes saved';
			$out['error'] = false;
		}
		else
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}
	}

	echo $json->encode($out);
	exit;
}

$gNoBc = false;

$gBc[1]['title'] = $esynI18N['manage'].' '.$esynI18N['coupons'];
$gBc[1]['url'] = '';

if (isset($_GET['action']) && 'edit' == $_GET['action'])
{
	$gBc[2]['title'] = $esynI18N['edit'].' '.$esynI18N['coupon'];
	$gTitle = $gBc[2]['title'];
}
else
{
	$gTitle = $esynI18N['manage'].' '.$esynI18N['coupons'];
}

$actions = array(
	array("url" => "controller.php?plugin=coupons&amp;do=add", "icon" => "add.png", "label" => $esynI18N['add_coupon']),
	array("url" => "controller.php?plugin=coupons", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once(IA_ADMIN_HOME.'view.php');

if(isset($_GET['do']))
{
	if('edit' == $_GET['do'])
	{
		$coupon = $Coupon->row("*", "`id` = '{$id}'");

		$esynSmarty->assign('coupon', $coupon);
	}

	if('add' == $_GET['do'])
	{
		$coupon = array("coupon_code" => $Coupon->randValue());
		$esynSmarty->assign('coupon', $coupon);
	}
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');
?>
