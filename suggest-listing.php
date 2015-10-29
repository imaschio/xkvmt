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

$cid = 0; // category ID
$listing_id = isset($_POST['listing_id']) ? (int)$_POST['listing_id'] : false;
$listing_id = false === $listing_id && isset($_GET['edit']) ? (int)$_GET['edit'] : $listing_id;
$hide_form = false;
$mode = $listing_id ? 'edit' : 'suggest';

define('IA_REALM', $mode . "_listing");

$msg = array();
$error = false;

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';
$eSyndiCat->factory("Listing", "Category", "Plan", "Layout");

include IA_INCLUDES . 'view.inc.php';

if (isset($_GET['action']))
{
	if (empty($esynAccountInfo))
	{
		exit;
	}

	if ('removeimg' == $_GET['action'])
	{
		$eSyndiCat->setTable('listings');

		$imageName = $eSyndiCat->one($_POST['field'], "`id` = :id AND `account_id` = :account_id", array('id' => (int)$_POST['id'], 'account_id' => $esynAccountInfo['id']));

		if (!empty($imageName))
		{
			$imageName = explode(',', $imageName);

			foreach ($imageName as $key => $image)
			{
				if ($image == $_POST['image'])
				{
					if (file_exists(IA_UPLOADS . $image))
					{
						unlink(IA_UPLOADS . $image);
					}

					if (file_exists(IA_UPLOADS . 'small_' . $image))
					{
						unlink(IA_UPLOADS . 'small_' . $image);
					}

					unset($imageName[$key]);
				}
			}

			$imageName = implode(',', $imageName);

			$eSyndiCat->update(array($_POST['field'] => $imageName), "`id` = :id", array('id' => (int)$_POST['id']));

			$eSyndiCat->resetTable();
		}

		exit;
	}

	if ('removefile' == $_GET['action'])
	{
		$eSyndiCat->setTable('listings');

		$file = $eSyndiCat->one($_POST['field'], "`id` = :id AND `account_id` = :account_id", array('id' => (int)$_POST['id'], 'account_id' => $esynAccountInfo['id']));

		if (!empty($file))
		{
			if ($file == $_POST['file'])
			{
				if (file_exists(IA_UPLOADS . $file))
				{
					unlink(IA_UPLOADS . $file);
				}

				if (file_exists(IA_UPLOADS . 'small_' . $file))
				{
					unlink(IA_UPLOADS . 'small_' . $file);
				}

				unset($file);
			}

			$eSyndiCat->update(array($_POST['field'] => ''), "`id` = :id", array('id' => (int)$_POST['id']));

			$eSyndiCat->resetTable();
		}

		exit;
	}
}

// listing submission disabled
if ('suggest' == $mode && !$esynConfig->getConfig('allow_listings_submission'))
{
	$_GET['error'] = "671";
	require IA_HOME . 'error.php';
	exit;
}

$listing = $listing_id ? $esynListing->row("*", "`id` = {$listing_id}") : false;

if (isset($_POST['category_id']))
{
	$cid = (int)$_POST['category_id'];
}
elseif ('edit' == $mode)
{
	$cid = $listing['category_id'];
}
elseif (isset($_GET['cid']))
{
	$cid = (int)$_GET['cid'];
}

// listing submission disabled for not authenticated
if (empty($esynAccountInfo) && !$esynConfig->getConfig('allow_listings_submit_guest'))
{
	$_SESSION['esyn_last_page'] = IA_URL . 'suggest-listing.php?id=' . $cid;
	$_SESSION['esyn_msg'] = _t('login_create_account_submit_listing');

	esynUtil::go2(IA_URL . 'login.php');
	exit;
}

// submission limited for accounts
$num_allowed_listings = isset($esynAccountInfo) && isset($esynAccountInfo['num_allowed_listings']) ? $esynAccountInfo['num_allowed_listings'] : $esynConfig->getConfig('account_listing_limit');

if (isset($esynAccountInfo) && '-1' != $num_allowed_listings)
{
	if ('suggest' == $mode && $esynAccountInfo['num_listings'] >= $num_allowed_listings)
	{
		$_GET['error'] = "674";
		require IA_HOME . 'error.php';
		exit;
	}
}

// listing not found
if ('edit' == $mode && empty($listing))
{
	$_GET['error'] = "404";
	require IA_HOME . 'error.php';
	exit;
}

// The listing is not owned by this account
if ('edit' == $mode && (empty($esynAccountInfo) || $listing['account_id'] != $esynAccountInfo['id']))
{
	$_GET['error'] = "671";
	require IA_HOME . 'error.php';
	exit;
}

if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
{
	$eSyndiCat->factory("Captcha");
}

$category = $esynCategory->row("*", "`id` = :category", array('category' => $cid));
$planId = isset($_POST['plan']) ? (int)$_POST['plan'] : $listing['plan_id'];
$plan = $esynPlan->row("*", "`id` = :plan", array('plan' => $planId));

$esynSmarty->assign('clearData', false);

if (isset($_POST['save_changes']))
{
	if (isset($_POST['plan_cost']) && $_POST['plan_cost'])
	{
		if ('edit' == $mode)
		{
			if ($listing['plan_id'] == $plan['id'])
			{
				// get current visual option prices sum
				$voptions = explode(',', $listing['visual_options']);
				$voptions = implode("','", $voptions);

				$eSyndiCat->setTable('listing_visual_options');
				$cur_visual_options = $eSyndiCat->assoc("`name`, `price`", "`show` = '1' AND `name` IN('{$voptions}')");
				$eSyndiCat->resetTable();

				foreach ($cur_visual_options as $value)
				{
					$cur_visual_options_sum += $value['price'];
				}

				$cur_visual_options_sum = $cur_visual_options_sum + $plan['cost'];
			}

			$plan['cost'] = $_POST['plan_cost'];
		}
		else
		{
			$plan['cost'] = $_POST['plan_cost'];
		}
	}

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';
		$esynUtf8 = new esynUtf8();

		$esynUtf8->loadUTF8Core();
		$esynUtf8->loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$addit = array();
	$fields = $esynListing->getFieldsByPage($mode, $category, $plan);

	list($data, $error, $msg) = $esynListing->processFields($fields, $listing);

	$eSyndiCat->startHook('editListingValidation');

	if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
	{
		if (!$esynCaptcha->validate())
		{
			$error = true;
			$msg[] = $esynI18N['error_captcha'];
		}
	}
	unset($temp);

	$data['ip_address'] = esynUtil::getIpAddress();
	$data['account_id'] = $esynAccountInfo['id'];
	$data['category_id'] = $cid;
	$data['plan_id'] = $planId;

	if ($category['locked'])
	{
		$error = true;
		$msg[] = $esynI18N['error_category_locked'];
	}

	// check URL
	if (!$error && !empty($data['url']) && 'http://' != $data['url'])
	{
		$data['domain'] = esynUtil::getDomain($data['url']);
		$data['pagerank'] = $esynConfig->getConfig('pagerank') ? PageRank::getPageRank($data['domain']) : -1;

		// check if listing already exists
		if ($esynConfig->getConfig('duplicate_checking'))
		{
			$check = $esynConfig->getConfig('duplicate_type') == 'domain' ? $data['domain'] : $data['url'];
			$res = $esynListing->checkDuplicateListings($check, $esynConfig->getConfig('duplicate_type'));
			if ($res && $listing['id'] != $res)
			{
				$error = true;
				$msg[] = $esynI18N['error_listing_present'];
			}
		}

		// Check if listing is broken or not.
		$listing_header = 200;
		if ($esynConfig->getConfig('listing_check'))
		{
			$listing_header = 1;
			$headers = esynUtil::getPageHeaders($data['url']);

			$isIIS = isset($headers['Server']) && (false !== strpos($headers['Server'], "IIS"));

			if (!empty($headers))
			{
				$listing_header = (int)$headers['Status'];
			}

			// Some (IIS) web servers don't allow HEAD methods
			// and return 403 or 405 errors, while the page
			// exists and GET method would return 200.
			// So, 403 and 405 are considered valid return
			// codes here.
			$allow = $isIIS && ($listing_header == 403 || $listing_header == 405);
			if (!$allow && !in_array((string)$listing_header, explode(',', $esynConfig->getConfig('http_headers')), true))
			{
				$error = true;
				$msg[] = $esynI18N['error_broken_listing'];
			}
		}
		$data['listing_header'] = $listing_header;
	}

	// reciprocal link checking
	if (!$error && $esynConfig->getConfig('reciprocal_check') && isset($data['reciprocal']))
	{
		$pageContent = esynUtil::getPageContent($data['reciprocal']);

		if ($esynConfig->getConfig('reciprocal_domain'))
		{
			if (esynUtil::getDomain($data['reciprocal']) != $data['domain'])
			{
				$error = true;
				$msg[] = $esynI18N['error_reciprocal_domain'];
			}
			else
			{
				$rcodes = explode("\r\n", $esynConfig->getConfig('reciprocal_link'));
				foreach ($rcodes as $r)
				{
					$data['recip_valid'] = esynValidator::hasUrl($pageContent, $r);
					if ($data['recip_valid'])
					{
						break;
					}
				}

				if (!$data['recip_valid'])
				{
					if (!$esynConfig->getConfig('reciprocal_required_only_for_free') || ($esynConfig->getConfig('reciprocal_required_only_for_free') && empty($plan['cost'])))
					{
						$error = true;
						$msg[] = $esynI18N['error_reciprocal_listing'];
					}
				}
			}
		}
		else
		{
			$rcodes = explode("\r\n", $esynConfig->getConfig('reciprocal_link'));
			foreach ($rcodes as $r)
			{
				$data['recip_valid'] = esynValidator::hasUrl($pageContent, $r);
				if ($data['recip_valid'])
				{
					break;
				}
			}

			if (!$data['recip_valid'])
			{
				$error = true;
				$msg[] = $esynI18N['error_reciprocal_listing'];
			}
		}

		if ($data['recip_valid'] && $esynConfig->getConfig('recip_featured'))
		{
			$data['featured'] = '1';
			$addit['featured_start'] = "NOW()";
		}
		unset($pageContent, $recipCode);
	}

	// Deep links
	if (!empty($_POST['deep_links']) && $plan['deep_links'] > 1)
	{
		$deep_cnt = 0;
		foreach($_POST['deep_links'] as $key => $deep_link)
		{
			// valid url, not empty title and is deep link
			if (!empty($deep_link['url']))
			{
				if (false !== strpos($deep_link['url'], $data['url']) || !$esynConfig->getConfig('deep_links_validate'))
				{
					$data['_deep_links'][] = $deep_link;
					$deep_cnt++;
				}
				else
				{
					$error = true;
					$msg[] = str_replace('{url}', $deep_link['url'], $esynI18N['error_deep_link_not_similar']);
				}
			}
			if ($deep_cnt >= $plan['deep_links'])
			{
				break;
			}
		}
	}

	// multicross
	if ($esynConfig->getConfig('mcross_functionality'))
	{
		$multi_crossed = array();

		if (!empty($_POST['multi_crossed']))
		{
			$multi_crossed = explode(",", $_POST['multi_crossed']);

			// validate to integer values
			$multi_crossed = array_map('intval', $multi_crossed);
			$multi_crossed = array_unique($multi_crossed);
		}

		if (!empty($multi_crossed))
		{
			$data['multi_crossed'] = $multi_crossed;
		}
	}

	// listing expiration
	if ($plan && 'suggest' == $mode)
	{
		$data['expire_notif'] = $plan['expire_notif'];
		$data['expire_action'] = $plan['expire_action'];

		$addit['expire_date'] = "CURRENT_DATE + INTERVAL {$plan['period']} DAY";

		if (!empty($plan['mark_as']))
		{
			if (in_array($plan['mark_as'], array('sponsored', 'featured', 'partner')))
			{
				$data[$plan['mark_as']] = 1;
				$addit[$plan['mark_as'] . '_start'] = "NOW()";
			}
		}
	}

	if (!$plan && 'suggest' == $mode && $esynConfig->getConfig('expire_period') > 0)
	{
		$data['expire_notif'] = (int)$esynConfig->getConfig('expire_notif');
		$data['expire_action'] = $esynConfig->getConfig('expire_action');

		$expire_period = (int)$esynConfig->getConfig('expire_period');

		$addit['expire_date'] = "CURRENT_DATE + INTERVAL {$expire_period} DAY";
	}
	elseif (!$plan && $esynAccountInfo && 'suggest' == $mode)
	{
		if ($esynAccountInfo['mark_as'])
		{
			if (in_array($esynAccountInfo['mark_as'], array('sponsored', 'featured', 'partner')))
			{
				$data[$esynAccountInfo['mark_as']] = 1;
				$addit[$esynAccountInfo['mark_as'] . '_start'] = "NOW()";
			}

			$addit['expire_date'] = "CURRENT_DATE + INTERVAL {$esynAccountInfo['period']} DAY";
		}
	}

	if (!$error)
	{
		$data['status'] = $plan['cost'] > 0 ? 'suspended' : 'approval';

		$data['title_alias'] = esynUtil::getAlias($data['title']);

		if (!empty($_POST['visual_options']))
		{
			$data['visual_options'] = implode(',', $_POST['visual_options']);
		}

		if ($esynConfig->getConfig('auto_approval') && 'approval' == $data['status'])
		{
			$data['status'] = 'active';
		}

		if ('suggest' == $mode)
		{
			// generate meta keywords
			if (!isset($data['meta_keywords']) || !$data['meta_keywords'])
			{
				include IA_INCLUDES . 'utils' . IA_DS . 'keywordsgenerator.php';
				$data['meta_keywords'] = KeywordsGenerator::generateKeywords($data['title'] . strip_tags($data['description']));
			}

			$data['id'] = $esynListing->insert($data, $addit);
			$msg[] = $esynI18N['listing_submitted'];
		}

		if ('edit' == $mode)
		{
			$temp_data = serialize($data);
			$data['id'] = $listing['id'];

			if ('0' == $_POST['plan_cost'] && !$cur_visual_options_sum || $data['plan_id'] == $_POST['old_plan_id'])
			{
				$esynListing->update($data);
			}
			else
			{
				$sql = "UPDATE {$eSyndiCat->mPrefix}listings SET `changes_temp` = '{$temp_data}' WHERE `id` = '{$listing['id']}'";
				$eSyndiCat->query($sql);
			}

			//$eSyndiCat->update($data);
			$msg[] = $esynI18N['listing_changed'];

			// update old category listing count
			$esynCategory->adjustNumListings($listing['category_id']);
		}


		// $hide_form = true;

		$esynSmarty->assign('clearData', true);

		$item['id'] = $data['id'];
		$item['item'] = 'listing';
		$item['method'] = ('suggest' == $mode) ? 'postPayment' : 'postPaymentEdit';
		$item['account_id'] = $esynAccountInfo['id'];

		if ('edit' == $mode)
		{
			$item['temp_data'] = $temp_data;
		}

		if ($listing && $listing['plan_id'] == $plan['id'])
		{
			if ($_POST['plan_cost'] > $cur_visual_options_sum)
			{
				$plan['cost'] = $_POST['plan_cost'];
			}
			else
			{
				$plan['cost'] = 0;
			}
		}

		$eSyndiCat->startHook('afterEditListing');

		if (isset($_POST['payment_gateway']) && !empty($_POST['payment_gateway']) && !empty($plan))
		{
			if ($plan['cost'] > 0)
			{
				$sec_key = $esynPlan->preparePayment($item, $_POST['payment_gateway'], $plan['cost'], $plan);

				$redirect_url = IA_URL . 'pre_pay.php?sec_key=' . $sec_key;

				esynUtil::go2($redirect_url);
			}
		}
	}
}

// Gets crossed information
// Script needs to know the IDs of categories which will be checked in tree by default
if (!empty($data))
{
	$crossed = isset($data['multi_crossed']) ? $data['multi_crossed'] : array();
}
elseif ('edit' == $mode)
{
	$eSyndiCat->setTable("listing_categories");
	$crossed = $eSyndiCat->onefield("`category_id`", "`listing_id` = '{$listing['id']}'");
	$eSyndiCat->resetTable();
}
else
{
	$crossed = array();
}

if ('edit' == $mode)
{
	$eSyndiCat->setTable('deep_links');
	$listing['deep_links'] = $eSyndiCat->keyvalue('`url`, `title`', "`listing_id`='{$listing['id']}'");
}

$esynSmarty->assign('listing', $listing);
$esynSmarty->assign('category', $category);

if (!empty($crossed))
{
	$crossed_categories = $esynCategory->all("`id`, `title`", "`id` IN ('" . join("','", $crossed) . "')");

	$esynSmarty->assign('crossed', $crossed);
	$esynSmarty->assign('crossed_categories', $crossed_categories);
}

$parent_ids = $esynCategory->getParentIds($cid);

if (!empty($parent_ids))
{
	$parent_ids = array_reverse($parent_ids);
	$parent_path = implode('/', $parent_ids);

	$esynSmarty->assign('parent_path', $parent_path);
}

$title = $esynI18N[IA_REALM];
$esynSmarty->assign('title', $title);

$categories_exist = $esynCategory->exists("`parent_id` != '-1' AND `status` = 'active'");
$plans_exist = $esynPlan->exists("`status` = 'active' AND `item` = 'listing'");

$esynSmarty->assign('categories_exist', $categories_exist);
$esynSmarty->assign('plans_exist', $plans_exist);

// breadcrumb formation
esynBreadcrumb::replaceEnd($title);
if ($category)
{
	esynBreadcrumb::addArray(esynUtil::getBreadcrumb($category['id']));
}

$esynSmarty->assign('hide_form', $hide_form);
$esynSmarty->display('suggest-listing.tpl');
