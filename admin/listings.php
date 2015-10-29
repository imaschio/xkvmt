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

define('IA_REALM', 'listings');

esynUtil::checkAccess();

$esynAdmin->factory('Listing', 'Category', 'Plan', 'View');

$error = false;

if (isset($_GET['action']))
{
	$out = array('data' => '', 'total' => 0);

	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		if (!empty($sort) && !empty($dir))
		{
			if ('account_id' == $sort)
			{
				$order = " ORDER BY `accounts`.`username` {$dir} ";
			}
			elseif ('parents' == $sort)
			{
				$order = " ORDER BY `categories`.`title` {$dir} ";
			}
			elseif (isset($_GET['mode']) && 'duplicate' == $_GET['mode'])
			{
				$order = " ORDER BY `listings`.`" . $esynConfig->getConfig('duplicate_type') . "` ASC ";
			}
			else
			{
				$order = " ORDER BY `{$sort}` {$dir} ";
			}
		}

		if (isset($_GET['what']) && !empty($_GET['what']))
		{
			$what = esynSanitize::sql($_GET['what']);
			$type = (isset($_GET['type']) && in_array($_GET['type'], array('any', 'all', 'exact'))) ? $_GET['type'] : 'any';

			$words = preg_split('/[\s]+/', $what);

			if (ctype_digit($what))
			{
				$where[] = "`listings`.`id` = '{$what}' ";
			}
			else
			{
				if ('any' == $type || 'all' == $type)
				{
					foreach ($words as $word)
					{
						if (isset($_GET['from']) && 'quick' == $_GET['from'])
						{
							$tmp[] = "(CONCAT(`listings`.`url`,' ',`listings`.`title`,' ',`listings`.`description`) LIKE '%{$word}%') ";
						}
						else
						{
							$tmp[] = "(CONCAT(`listings`.`url`,' ',`listings`.`title`,' ',`listings`.`description`) LIKE '%{$word}%') ";
						}
					}
					$where[] = ('any' == $type) ? ' (' . implode(" OR ", $tmp) . ')' : ('all' == $type) ? implode(" AND ", $tmp) : '';
				}
				elseif ('exact' == $type)
				{
					if (isset($_GET['from']) && 'quick' == $_GET['from'])
					{
						$where[] = "(CONCAT(`listings`.`url`,' ',`listings`.`title`,' ',`listings`.`description`) LIKE '%{$what}%') ";
					}
					else
					{
						$where[] = "(CONCAT(`listings`.`url`,' ',`listings`.`title`,' ',`listings`.`description`) LIKE '%{$what}%') ";
					}
				}
			}
		}

		if (isset($_GET['status']) && 'all' != $_GET['status'] && !empty($_GET['status']))
		{
			$where[] = "`listings`.`status` = '".esynSanitize::sql($_GET['status'])."'";
		}

		if (isset($_GET['type']) && !empty($_GET['type']))
		{
			if ('featured' == $_GET['type'])
			{
				$where[] = "`listings`.`featured` = '1'";
			}
			elseif ('partner' == $_GET['type'])
			{
				$where[] = "`listings`.`partner` = '1'";
			}
			elseif ('sponsored' == $_GET['type'])
			{
				$where[] = "`listings`.`sponsored` = '1' ";
			}
			elseif ('regular' == $_GET['type'])
			{
				$where[] = "`listings`.`partner` = '0' AND `listings`.`featured` = '0' AND `listings`.`sponsored` = '0'";
			}
		}

		if (isset($_GET['state']) && !empty($_GET['state']))
		{
			if ('reported_as_broken' == $_GET['state'])
			{
				$where[] = "`listings`.`reported_as_broken` = '1'";
			}
			elseif ('destvalid' == $_GET['state'])
			{
				$headers = $esynConfig->gC('http_headers');

				$correct_headers = explode(',', $headers);
				$correct_headers = implode("','", $correct_headers);

				$where[] = "`listings`.`listing_header` IN('{$correct_headers}')";
			}
			elseif ('destbroken' == $_GET['state'])
			{
				$headers = $esynConfig->gC('http_headers');

				$correct_headers = explode(',', $headers);
				$correct_headers = implode("','", $correct_headers);

				$where[] = "`listings`.`listing_header` NOT IN('{$correct_headers}')";
			}
			elseif ('recipvalid' == $_GET['state'])
			{
				$where[] = "`listings`.`recip_valid` = '1'";
			}
			elseif ('recipbroken' == $_GET['state'])
			{
				$where[] = "`listings`.`recip_valid` = '0'";
			}
		}

		if (isset($_GET['account']) && !empty($_GET['account']))
		{
			$account = (int)$_GET['account'];

			$where[] = "`listings`.`account_id` = '{$account}'";
		}

		if (isset($_GET['mode']) && 'duplicate' == $_GET['mode'])
		{
			$where[] = "`listings`.`duplicate` = '1'";
		}

		$sql = "SELECT `listings`.*, `listings`.`id` `edit`, `accounts`.`username`, `categories`.`id` `category_id`, `categories`.`path`, `categories`.`title` `category` ";
		//MOD: Display payment ID
		//$sql .= ",`transactions`.`order_number` `payment_id`, `transactions`.`status` `payment_status` ";
		$sql .= "FROM `{$esynAdmin->mPrefix}listings` `listings` ";
		$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}accounts` `accounts` ";
		$sql .= "ON `listings`.`account_id` = `accounts`.`id` ";
		$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}categories` `categories` ";
		$sql .= "ON `listings`.`category_id` = `categories`.`id` ";
//		$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}transactions` `transactions` ";
//		$sql .= "ON `listings`.`id` = `transactions`.`item_id` ";

		if (!empty($where))
		{
			$sql .= "WHERE ";
			$sql .= join(' AND ', $where);
		}

		//MOD: Display payment ID
		//$sql .= " AND `transactions`.`item` = 'listings'";

		$sql .= $order;

		if (!isset($_GET['mode']) || empty($_GET['mode']))
		{
			$sql .= "LIMIT {$start}, {$limit}";
		}

		$out['data'] = $esynListing->getAll($sql);

		if (!empty($out['data']))
		{
			$out['data'] = esynSanitize::applyFn($out['data'], "html", array('title'));
			$out['data'] = esynSanitize::applyFn($out['data'], "striptags", array('description'));

			foreach($out['data'] as $key => $listing)
			{
				$out['data'][$key]['listing_details'] = '';

				esynView::getBreadcrumb($listing['category_id'], $categories_chain, true);

				if (!empty($categories_chain))
				{
					$parents = array();
					$categories_chain = array_reverse($categories_chain);

					if (count($categories_chain) > 1)
					{
						unset($categories_chain[0]);
					}

					foreach($categories_chain as $chain)
					{
						$parents[] = '<a href="controller.php?file=browse&id=' . $chain['id'] . '">' . $chain['title'] . '</a>';
					}

					$out['data'][$key]['parents'] = implode('&nbsp;/&nbsp;', $parents);
				}

				if ($esynConfig->getConfig('pagerank'))
				{
					$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['pagerank'] . '</b>&nbsp;:&nbsp;' . $listing['pagerank'] . '<br />';
				}

				$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['clicks'] . '</b>&nbsp;:&nbsp;' . $listing['clicks'] . '<br />';

				/* modification */
				/*$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['url'] . '</b>&nbsp;:&nbsp;' . $listing['url'] . '<br />';
				$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['email'] . '</b>&nbsp;:&nbsp;' . $listing['email'] . '<br />';
				$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['reciprocal'] . '</b>&nbsp;:&nbsp;' . $listing['reciprocal'] . '<br />';*/

				if ($listing['featured'])
				{
					$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['featured_since'] . '</b>&nbsp;:&nbsp;' . esynUtil::dateFormat($listing['featured_start']) . '<br />';
				}

				if ($listing['partner'])
				{
					$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['partner_since'] . '</b>&nbsp;:&nbsp;' . esynUtil::dateFormat($listing['partner_start']) . '<br />';
				}

				if ($listing['sponsored'])
				{
					$plan_title = $esynPlan->one("`title`", "`id` = '{$listing['plan_id']}'");
					$expire_date = esynUtil::dateFormat($listing['expire_date']);
					$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['sponsored'] . '</b>&nbsp;:&nbsp;' . $plan_title . '&nbsp;' . $esynI18N['listing_expired'] . '&nbsp;' . ($expire_date ? $expire_date : $esynI18N['never']) . '<br />';
				}

				$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['submitted'] . '</b>&nbsp;:&nbsp;' . esynUtil::dateFormat($listing['date']) . '<br />';
				$out['data'][$key]['listing_details'] .= '<b>' . $esynI18N['description'] . '</b>&nbsp;:&nbsp;' . $out['data'][$key]['description'] . '<br /l>';

				unset($categories_chain);
			}
		}

		if (!empty($where))
		{
			$where = join(" AND ", $where);
			$where = str_replace("`listings`.", "", $where);

			if (isset($_GET['mode']) && 'duplicate' == $_GET['mode'])
			{
				$where .= ' GROUP BY `' . $esynConfig->gC('duplicate_type') . '` ';
			}

			$total = $esynListing->one("COUNT(*)", $where);
		}
		else
		{
			$total = $esynListing->one("COUNT(*)");
		}

		$out['total'] = $total;
	}

	if ('getaccounts' == $_GET['action'])
	{
		$query = isset($_GET['query']) ? esynSanitize::sql(trim($_GET['query'])) : '';

		$esynAdmin->setTable("accounts");
		$out['data'] = $esynAdmin->all("`id`, `username`", "`username` LIKE '{$query}%'");
		$out['total'] = $esynAdmin->one("COUNT(*)", "`username` LIKE '{$query}%'");
		$esynAdmin->resetTable();
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{
	$out = array('msg' => 'Unknown error', 'error' => false);

	if ('update' == $_POST['action'])
	{
		$field = $_POST['field'];
		$value = $_POST['value'];

		if (empty($field) || empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}
		else
		{
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($_POST['ids']))
			{
				foreach($_POST['ids'] as $id)
				{
					$ids[] = (int)$id;
				}

				$where = "`id` IN ('".join("','", $ids)."')";
			}
			else
			{
				$id = (int)$_POST['ids'];

				$where = "`id` = '{$id}'";
			}

			if ('status' == $field)
			{
				foreach($_POST['ids'] as $id)
				{
					$esynListing->updateStatus((int)$id, $value);
				}

				$esynAdmin->mCacher->clearAll('categories');
			}
			else
			{
				$esynAdmin->setTable('listings');
				$esynAdmin->update(array($field => $value), $where);
				$esynAdmin->resetTable();
			}

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	if ('remove' == $_POST['action'])
	{
		if (empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}
		else
		{
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($_POST['ids']))
			{
				foreach($_POST['ids'] as $id)
				{
					$ids[] = (int)$id;
				}

				$where = "`id` IN ('".join("','", $ids)."')";
			}
			else
			{
				$id = (int)$_POST['ids'];

				$where = "`id` = '{$id}'";
			}

			$reason = isset($_POST['reason']) && !empty($_POST['reason']) ? $_POST['reason'] : '';

			$cat_ids = $esynListing->onefield("`category_id`", $where);

			$esynListing->delete($where, $reason);

			$esynCategory->adjustNumListings($cat_ids);

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	if ('move' == $_POST['action'])
	{
		$listings = array_map("intval", $_POST['ids']);
		$category = (int)$_POST['category'];

		$count = count($listings);
		$crosslink = false;

		foreach($listings as $value)
		{
			// Don't allow moving
			$esynAdmin->setTable("listing_categories");

			if ($esynAdmin->exists("`category_id` = '{$category}' AND `listing_id` = '{$value}'"))
			{
				$crosslink = true;
				continue;
			}

			$esynAdmin->resetTable();

			$esynListing->move($value, $category, -1, (bool)$esynConfig->getConfig('listing_move'));
		}

		$esynCategory->adjustNumListings($category);

		if ($crosslink && $count == 1)
		{
			$out['msg'] = $esynI18N['cannot_move_due_to_crosslink'];
		}
		else
		{
			$out['msg'] = ($count > 1) ? $esynI18N['listings_moved'] : $esynI18N['listing_moved'];
		}
	}

	if ('cross' == $_POST['action'])
	{
		$listings = array_map("intval", $_POST['ids']);
		$category = (int)$_POST['category'];

		foreach($listings as $value)
		{
			$esynAdmin->setTable("listing_categories");
			$exists = $esynAdmin->exists("`listing_id` = '{$value}' AND `category_id` = '{$category}'");
			$esynAdmin->resetTable();

			// don't allow create two cross listings
			if (!$exists)
			{
				$esynListing->copy($value, $category);
			}
		}

		$esynCategory->adjustNumListings($category);

		$out['msg'] = (count($listings) > 1) ? $esynI18N['listings_crossed'] : $esynI18N['listing_crossed'];
	}

	if ('copy' == $_POST['action'])
	{
		$listings = array_map("intval", $_POST['ids']);
		$category = (int)$_POST['category'];

		$esynAdmin->setTable("listings");

		foreach($listings as $value)
		{
			$link = $esynAdmin->row("*", "`id` = '{$value}'");

			unset($link['id']);

			$link['category_id'] = $category;

			$esynAdmin->insert($link);
		}

		$esynAdmin->resetTable();

		$esynCategory->adjustNumListings($category);

		$out['msg'] = (count($listings) > 1) ? $esynI18N['listings_copied'] : $esynI18N['listing_copied'];
	}

	if ('update_pagerank' == $_POST['action'])
	{
		$where = $esynAdmin->convertIds('id', $_POST['ids']);

		$msg = '';

		$esynAdmin->setTable("listings");

		$listings = $esynAdmin->all("`id`, `title`, `domain`", $where);

		foreach ($listings as $listing)
		{
			$pr = PageRank::getPageRank($listing['domain']);

			$esynAdmin->update(array("pagerank" => $pr), "`id` = '{$listing['id']}'");

			$msg .= $listing['title'] . ' ' . _t('listing_pagerank_updated_to') . ' ' . $pr . '<br />';
		}

		$esynAdmin->resetTable();

		$out['msg'] = $msg;
	}

	if ('check_broken' == $_POST['action'])
	{
		$where = $esynAdmin->convertIds('id', $_POST['ids']);

		$msg = '';

		$esynAdmin->setTable("listings");

		$listings = $esynListing->all("`id`, `title`, `url`", $where);

		foreach ($listings as $listing)
		{
			$listing_header = esynUtil::getListingHeader($listing['url']);

			$esynAdmin->update(array("listing_header" => $listing_header), "`id` = '{$listing['id']}'");

			$msg .= $listing['title'] . ' ' . _t('listing_header_updated_to') . ' ' . $listing_header . '<br />';
		}

		$esynAdmin->resetTable();

		$out['msg'] = $msg;
	}

	if ('recip_recheck' == $_POST['action'])
	{
		$listings = array_map("intval", $_POST['ids']);

		$recipText = $esynConfig->getConfig('reciprocal_link');
		$recipText = explode("\r\n", $recipText);

		$esynAdmin->setTable("listings");

		$msg = '';

		foreach($listings as $value)
		{
			$listing = $esynListing->row("`reciprocal`, `title`", "`id` = '{$value}'");

			foreach ($recipText as $r)
			{
				$recipValid = esynValidator::hasUrl($listing['reciprocal'], $r);

				if ($recipValid)
				{
					break;
				}
			}

			$data = array("recip_valid" => (int)$recipValid, "id" => $value);

			$addit = array();

			if ($recipValid)
			{
				if ($esynConfig->getConfig('recip_featured'))
				{
					$data['featured'] = "1";
					$addit['featured_start'] = "NOW()";
				}

				$msg .= $listing['title'] . ' ' . _t('has_reciprocal_link') . ' ' . '<br />';
			}
			else
			{
				$msg .= $listing['title'] . ' ' . _t('has_no_reciprocal_link') . ' ' . '<br />';
			}

			$esynAdmin->update($data, "`id` = '{$value}'", array(), $addit);
		}

		$out['msg'] = $msg;

		$esynAdmin->resetTable();
	}

	if ('unbroken' == $_POST['action'])
	{
		$where = $esynAdmin->convertIds('id', $_POST['ids']);

		$esynAdmin->setTable("listings");
		$esynAdmin->update(array('listing_header' => 200), $where);
		$esynAdmin->resetTable();

		$out['msg'] = $esynI18N['done'];
	}

	if ('send_email' == $_POST['action'])
	{
		if (!defined('IA_NOUTF'))
		{
			require_once IA_CLASSES . 'esynUtf8.php';

			esynUtf8::loadUTF8Core();
			esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
		}

		if (empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}
		else
		{
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($_POST['ids']))
			{
				$ids = implode(',', $_POST['ids']);
				$send_listings = $esynListing->all("*", "`id` IN ({$ids})");
			}
			else
			{
				$send_listings[] = $esynListing->row("*", "`id` = '{$id}'");
			}

			$content['subject'] = isset($_POST['subject']) && !empty($_POST['subject']) ? $_POST['subject'] : '';
			$content['body'] = isset($_POST['body']) && !empty($_POST['body']) ? $_POST['body'] : '';

			foreach ($send_listings as $key => $listing)
			{
				if (!empty($listing['email']) || (int)$listing['account_id'] != 0)
				{
					$account = $listing['account_id'];
					if (empty($email))
					{
						$esynAdmin->setTable("accounts");
						$account = $esynAdmin->row("*", "`id` = '".$account."'");
						$esynAdmin->resetTable();
					}

					$email = $listing['email'];
					if($_POST['email'] == 'account')
					{
						$email = $account['email'];
					}

					if($email)
					{
						$esynAdmin->mMailer->AddAddress($email);
						$esynAdmin->mMailer->SendCustom($content, $account, $listing);
					}
				}
			}

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_listings'];

$gBc[0]['title'] = $gTitle;
$gBc[0]['url'] = 'controller.php?file=listings';

if (isset($_GET['mode']) && 'duplicate' == $_GET['mode'])
{
	$gTitle = _t('manage_duplicate');

	$gBc[0]['title'] = $gTitle;
	$gBc[0]['url'] = 'controller.php?file=listings&mode=duplicate';
}

$actions[] = array("url" => "controller.php?file=suggest-listing&amp;id=0",	"icon" => "create_listing.png", "label"	=> $esynI18N['create_listing']);
$actions[] = array("url" => "controller.php?file=listings&amp;mode=duplicate", "icon" => "view_block.png", "label"	=> $esynI18N['manage_duplicate']);

require_once IA_ADMIN_HOME . 'view.php';

// get email templates
$email_groups = $esynConfig->getConfig('email_groups');

if (empty($email_groups))
{
	$email_groups = 'account,listing';
}

if (!empty($esynAdmin->mPlugins))
{
	$email_groups .= ',' . implode(',', array_keys($esynAdmin->mPlugins));
}
$email_groups = explode(',', $email_groups);

$esynAdmin->setTable('language');

$templates = $esynAdmin->keyvalue('`key`, `value`', "`key` LIKE 'tpl_%_subject' AND `code`='" . IA_LANGUAGE . "'");

$tmpls = array();

if (!empty($templates))
{
	foreach ($templates as $key => $tmpl)
	{
		foreach ($email_groups as $group)
		{
			if (false !== stristr($key, 'tpl_' . $group) || false !== stristr($key, 'tpl_notif_' . $group))
			{
				$tmpls[$group][$key] = $tmpl;

				unset($templates[$key]);

				break;
			}
		}
	}

	// add non-group templates
	if (!empty($templates))
	{
		$tmpls['other'] = $templates;
	}
}

// sort templates
if (!empty($tmpls))
{
	foreach ($tmpls as $key => $tmpl)
	{
		asort($tmpl);

		$tmpls[$key] = $tmpl;
	}
}
$esynSmarty->assign('tmpls', $tmpls);
$esynSmarty->assign('email_groups', $email_groups);

$esynSmarty->display('listings.tpl');
