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

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	define('IA_REALM', "edit_listing");
}
else
{
	define('IA_REALM', "create_listing");
}

esynUtil::checkAccess();

$esynAdmin->factory("Category", "Listing", "Plan", "Account", "ListingField");

$imgtypes = array(
	"image/gif"=>"gif",
	"image/jpeg"=>"jpg",
	"image/pjpeg"=>"jpg",
	"image/png"=>"png"
);

$error = false;
$msg = array();

$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? $_GET['id'] : 0;

//require_once IA_ADMIN_HOME . 'view.php';

if ((isset($_GET['do']) && 'edit' == $_GET['do']) || isset($_POST['category_id']))
{
	$error = false;

	/** get listing information **/
	$listing = $old_listing = $esynListing->row("*, CAST(`date` AS DATE) `date`, CAST(`date` AS TIME) `time`, `expire_date` - INTERVAL `expire_notif` DAY `expire_notif_date`", "`id` = {$id}");

	if (isset($_POST['category_id']))
	{
		$cid = (int)$_POST['category_id'];
	}
	else
	{
		$cid = $listing['category_id'];
	}

	// get information about parent category
	$category = $esynCategory->row("*", "`id` = '{$cid}'");

	$esynCategory->setTable("flat_structure");
	$parents = $esynCategory->all("`parent_id`", "`category_id` = :id", array('id' => $category['id']));
	$esynCategory->resetTable();

	$esynAdmin->setTable("listing_categories");
	$crossed = $esynAdmin->onefield("`category_id`", "`listing_id` = '{$listing['id']}'");
	$esynAdmin->resetTable();

	/** get account info **/
	if ($listing['account_id'] > 0)
	{
		$account = $esynAccount->row("*", "`id` = '{$listing['account_id']}'");
	}

	if (!empty($parents))
	{
		foreach($parents as $parent)
		{
			$category['parents'][] = $parent['parent_id'];
		}

		$category['parents'] = array_reverse($category['parents']);
		sort($category['parents']);

		$category['parents'] = '/'.join('/', $category['parents']).'/';
	}

	if (!empty($crossed))
	{
		/*
		 * To display the list of crossed categories
		 */
		$crossed_html = '';

		/*
		 * The IDs of crossed categories
		 */
		$listing['crossed'] = $crossed;

		/*
		 * Script needs to know the parents of each crossed category to expand categories in the tree
		 */
		$esynAdmin->setTable("flat_structure");

		foreach($crossed as $cross)
		{
			$crossed_parents = $esynAdmin->onefield("`parent_id`", "`category_id` = '{$cross}'");
			$crossed_parents = array_reverse($crossed_parents);
			sort($crossed_parents);

			$listing['crossed_expand_path'][] = '/' . join('/', $crossed_parents) . '/';
		}

		$esynAdmin->resetTable();

		// Create HTML for displaying the list of crossed categories
		$crossed_categories = $esynCategory->all("*", "`id` IN ('" . join("','", $crossed) . "')");

		$crossed_html .= '<p class="field">' . $esynI18N['crossed_to'] . ': <br />';

		foreach($crossed_categories as $key => $crossed_category)
		{
			$crossed_html .= '<a href="controller.php?file=browse&id=' . $crossed_category['id'] . '">';
			$crossed_html .= '<b>' . $crossed_category['title'] . '</b></a><br />';
		}

		$crossed_html .= '</p>';
	}
	$esynAdmin->startHook('adminSuggestListingEditSection');
}
else
{
	$listing = array();

	// get information about parent category
	$category = $esynCategory->row("*", "`id` = '{$id}'");
}

$parent = $esynCategory->row("*", "`id` = '{$category['id']}'");

// get extra fields
$fields = $esynListingField->all("*", "1 ORDER BY `order`");

// get groups
$esynAdmin->setTable('field_groups');
$field_groups = $esynAdmin->onefield('`name`', "1 ORDER BY `order`");
$esynAdmin->resetTable();

// get all plans
$plans = $esynPlan->all("*", "`item` = 'listing' ORDER BY `order`");

if (isset($_POST['do']) && 'delete' == $_POST['do'])
{
	$where = "`id` = " . (int)$_GET['id'];

	$listing = $esynListing->row('*', $where);

	if (!empty($listing))
	{
		$esynListing->delete($where);

		$esynCategory->adjustNumListings($listing['category_id']);

		$msg[] = $esynI18N['listing_removed'];
	}
	else
	{
		$error = true;
		$msg[] = $esynI18N['listing_not_exist'];
	}

	esynMessages::setMessage($msg, $error);

	if (isset($_POST['goto']) && 'browse' == $_POST['goto'])
	{
		esynUtil::go2("controller.php?file=browse&id={$listing['category_id']}");
	}
	else
	{
		esynUtil::go2('controller.php?file=listings');
	}
}

if (isset($_POST['save']))
{
	$listing = array();
	$account = array();
	$additional = array();

	$esynAdmin->startHook('adminSuggestListingValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$listing['status'] = in_array($_POST['status'], array("approval", "active", "suspended", "banned", 'deleted')) ? $_POST['status'] : '';
	$listing['ip_address'] = esynUtil::getIpAddress();
	$listing['email'] = trim($_POST['email']);
	$listing['account_id'] = 0;
	$listing['rank'] = isset($_POST['rank']) && $_POST['rank'] > 0 ? (int)$_POST['rank'] : '0';

	if (isset($_POST['date']) && !empty($_POST['date']))
	{
		if (isset($_POST['time']) && !empty($_POST['time']))
		{
			$listing['date'] = esynSanitize::sql($_POST['date']) . ' ' . esynSanitize::sql($_POST['time']);
		}
		else
		{
			$listing['date'] = esynSanitize::sql($_POST['date']);
		}
	}

	$listing['category_id'] = $cid = $category['id'];
	// if parent choosed from the TREE
	if (isset($_POST['category_id']) && ctype_digit($_POST['category_id']))
	{
		$listing['category_id'] = (int)$_POST['category_id'];

		if (!$esynCategory->exists("`id`='" . $listing['category_id'] . "'"))
		{
			$listing['category_id'] = $category['id'];
		}
	}

	// listing expiration
	$plan_id = isset($_POST['assign_plan']) ? (int)$_POST['assign_plan'] : 0;

	$esynAdmin->setTable('plans');
	$plan = $esynAdmin->row('*', "`id` = '{$plan_id}'");
	$esynAdmin->resetTable();

	if (isset($_POST['expire']) && !empty($_POST['expire']))
	{
		$listing['expire_notif'] = isset($plan) ? $plan['expire_notif'] : (int)$esynConfig->getConfig('expire_notif');
		$listing['expire_action'] = isset($plan) ? $plan['expire_action'] : $esynConfig->getConfig('expire_action');

		$listing['expire_date'] = $_POST['expire'];
	}

	// Old Functionality
	/* 
	if ($plan)
	{
		$listing['expire_notif'] = $plan['expire_notif'];
		$listing['expire_action'] = $plan['expire_action'];

		$additional['expire_date'] = "CURRENT_DATE + INTERVAL {$plan['period']} DAY";
	}
	else
	{
		if (isset($_POST['expire']) && !empty($_POST['expire']))
		{
			$listing['expire_notif'] = (int)$_POST['expire_notif'];
			$listing['expire_action'] = $_POST['expire_action'];

			$expire_period = (int)$_POST['expire'];

			$additional['expire_date'] = "CURRENT_DATE + INTERVAL {$expire_period} DAY";
		}
		elseif ($esynConfig->getConfig('expire_period') > 0)
		{
			$listing['expire_notif'] = (int)$esynConfig->getConfig('expire_notif');
			$listing['expire_action'] = $esynConfig->getConfig('expire_action');

			$expire_period = (int)$esynConfig->getConfig('expire_period');

			$additional['expire_date'] = "CURRENT_DATE + INTERVAL {$expire_period} DAY";
		}
	}
	*/

	// account auto-creating
	if (isset($_POST['assign_account']) && '0' != $_POST['assign_account'])
	{
		if ('1' == $_POST['assign_account'])
		{
			$account['username'] = $_POST['new_account'];
			$account['email'] = $_POST['new_account_email'];
			$account['status'] = 'active';

			/** check username **/
			if (!preg_match("/^[\w\s]{3,30}$/", $account['username']))
			{
				$error = true;
				$msg[] = $esynI18N['error_username_incorrect'];
			}
			elseif (empty($account['username']))
			{
				$error = true;
				$msg[] = $esynI18N['error_username_empty'];
			}
			elseif ($esynAccount->exists("`username`='".$account['username']."'"))
			{
				$error = true;
				$msg[] = $esynI18N['error_username_exists'];
			}
			else
			{
				$account['username'] = esynSanitize::sql($account['username']);
			}

			// check email
			if (!esynValidator::isEmail($account['email']))
			{
				$error = true;
				$msg[] = $esynI18N['error_email_incorrect'];
			}
			elseif ($esynAccount->exists("`email`='".esynSanitize::sql($account['email'])."'"))
			{
				$error = true;
				$msg[] = $esynI18N['account_email_exists'];
			}
			else
			{
				$account['email'] = esynSanitize::sql($account['email']);
			}
		}

		if ('2' == $_POST['assign_account'])
		{
			if (isset($_POST['account']))
			{
				//$listing['account_id'] = (int)$_POST['account'];
				$account['account_id'] = (int)$_POST['account'];
			}
		}

		if ('3' == $_POST['assign_account'])
		{
			$listing['account_id'] = '0';
		}
	}

	if (!empty($listing['email']) && !esynValidator::isEmail($listing['email']))
	{
		$error = true;
		$msg[] = $esynI18N['error_email_incorrect'];
	}
	else
	{
		$listing['email'] = esynSanitize::sql($listing['email']);
	}

	if ($fields)
	{
		$url_required = false;

		foreach($fields as $key=>$value)
		{
			$field_name = $value['name'];

			if (in_array($value['type'], array('storage', 'image', 'pictures')))
			{
				if (isset($_FILES[$field_name]))
				{
					if (!is_writeable(IA_UPLOADS))
					{
						$error = true;
						$msg[] = $esynI18N['upload_writable_permission'];
					}
				}
				else
				{
					continue;
				}
			}
			else
			{
				if (is_array($_POST[$field_name]))
				{
					$field_value = join(",", $_POST[$field_name]);
					$field_value = trim($field_value, ',');
				}
				else
				{
					$field_value = $_POST[$field_name];
				}

				if (!utf8_is_valid($field_value))
				{
					$field_value = utf8_bad_replace($field_value);
					trigger_error("Bad UTF-8 detected (replacing with '?') in suggest listing", E_USER_NOTICE);
				}
			}

			// magic quotes stripping for text and textarea fields
			if (($value['type'] == 'text') || ($value['type'] == 'textarea'))
			{
				$listing[$field_name] = $field_value;

				if ($field_name == 'url')
				{
					$url_required = $value['required'];
				}

				if ($field_name == 'title')
				{
					if (utf8_strlen($listing['title']) > (int)$value['length'])
					{
						$listing['title'] = utf8_substr($listing['title'], 0, (int)$value['length']);
					}
				}
			}
			elseif ('storage' == $value['type'])
			{
				if (!$_FILES[$field_name]['error'])
				{
					$ext = substr($_FILES[$field_name]['name'], -3);
					$token = esynUtil::getNewToken();

					$file_name = $value['file_prefix'].$cid."-".$token.".".$ext;
					if (esynUtil::upload($field_name, IA_UPLOADS . $file_name))
					{
						$listing[$field_name] = $file_name;
					}
					else
					{
						$error = true;
						$msg[] = $esynI18N['unknown_upload'];
					}
				}
			}
			elseif ('image' == $value['type'])
			{
				$field_title_name = $value['name'] . '_title';
				if (isset($_POST[$field_title_name]))
				{
					$listing[$field_title_name] = $_POST[$field_title_name];
				}

				if (isset($_FILES[$field_name]) && !$_FILES[$field_name]['error'])
				{
					if (is_uploaded_file($_FILES[$field_name]['tmp_name']))
					{
						$ext = strtolower(utf8_substr($_FILES[$field_name]['name'], -3));

						// if 'jpeg'
						if ($ext == 'peg')
						{
							$ext = 'jpg';
						}

						if (!array_key_exists($_FILES[$field_name]['type'], $imgtypes) || !in_array($ext, $imgtypes) || !getimagesize($_FILES[$field_name]['tmp_name']))
						{
							$error	= true;
							$a		= join(",", array_unique($imgtypes));
							$tmp 	= str_replace("{types}", $a, $esynI18N['wrong_image_type']);
							$tmp 	= str_replace("{name}", $field_name, $tmp);

							$msg[] = $tmp;
						}
						else
						{
							if (isset($_GET['do']) && 'edit' == $_GET['do'])
							{
								if (!empty($listing[$field_name]) && file_exists(IA_UPLOADS . $listing[$field_name]))
								{
									unlink(IA_UPLOADS . $listing[$field_name]);
								}

								if (!empty($listing[$field_name]) && file_exists(IA_UPLOADS . 'small_' . $listing[$field_name]))
								{
									unlink(IA_UPLOADS . 'small_' . $listing[$field_name]);
								}
							}

							// filename generation
							$token = esynUtil::getNewToken();
							$file_name = $value['file_prefix'] . $cid . '-' . $token . '.' . $ext;
							$listing[$field_name] = $file_name;

							// process image
							$esynAdmin->loadClass('Image');
							$image = new esynImage();
							$image->processImage($_FILES[$field_name], IA_UPLOADS, $file_name, $value);
						}
					}
				}
			}
			elseif ('pictures' == $value['type'])
			{
				$listing[$field_name . '_titles'] = implode(',', $_POST[$field_name . '_titles']);

				$picture_names = array();

				$esynAdmin->loadClass('Image');
				$image = new esynImage();

				foreach($_FILES[$field_name]['tmp_name'] as $key => $tmp_name)
				{
					if ((bool)$value['required'] && (bool)$_FILES[$field_name]['error'][$key])
					{
						$error = true;
						$err_mes = str_replace('{field}', $esynI18N['field_'.$field_name], $esynI18N['field_is_empty']);
						$msg[] = $err_mes;
					}
					else
					{
						if (@is_uploaded_file($_FILES[$field_name]['tmp_name'][$key]))
						{
							$ext = strtolower(utf8_substr($_FILES[$field_name]['name'][$key], -3));

							// if jpeg
							if ($ext == 'peg')
							{
								$ext = 'jpg';
							}

							if (!array_key_exists($_FILES[$field_name]['type'][$key], $imgtypes) || !in_array($ext, $imgtypes, true) || !getimagesize($_FILES[$field_name]['tmp_name'][$key]))
							{
								$error = true;

								$a = implode(",",array_unique($imgtypes));

								$err_msg = str_replace("{types}", $a, $esynI18N['wrong_image_type']);
								$err_msg = str_replace("{name}", $field_name, $err_msg);

								$msg[] = $err_msg;
							}
							else
							{
								$token = esynUtil::getNewToken();

								$file_name = $value['file_prefix'] . $cid . '-' . $token . '.' . $ext;

								$picture_names[] = $file_name;

								$file = array();

								foreach ($_FILES[$field_name] as $key1 => $tmp_name)
								{
									$file[$key1] = $_FILES[$field_name][$key1][$key];
								}

								// process image
								$image->processImage($file, IA_UPLOADS, $file_name, $value);
							}
						}
					}
				}

				if (!empty($picture_names))
				{
					if (isset($_GET['do']) && 'edit' == $_GET['do'])
					{
						if (!empty($old_listing[$field_name]))
						{
							$existing_picture_names = explode(',', $old_listing[$field_name]);
							$picture_names = array_merge($picture_names, $existing_picture_names);
						}
					}

					$listing[$field_name] = implode(',', $picture_names);
				}
			}
			else
			{
				$listing[$field_name] = $field_value;
			}
		}
	}

	if (isset($listing['url']) && !empty($listing['url']))
	{
		$listing['url'] = trim($listing['url']);

		if (false === strstr($listing['url'], 'http'))
		{
			$listing['url']  = 'http://' . $listing['url'];
		}
	}

	$listing['listing_header'] = 200;
	$listing['pagerank'] = -1;

	$valid_url = esynValidator::isUrl($_POST['url']);
	if (($url_required && !$valid_url) || !empty($listing['url']) && !$valid_url && 'http://' != $listing['url'])
	{
		$error = true;
		$msg[] = $esynI18N['error_url'];
	}
	elseif ($valid_url)
	{
		// get domain name
		$listing['domain'] = esynUtil::getDomain($listing['url']);

		// get pagerank value
		if ($_POST['pagerank'])
		{
			$listing['pagerank'] = (int)$_POST['pagerank'];
		}
		elseif ($esynConfig->getConfig('pagerank'))
		{
			$listing['pagerank'] = PageRank::getPageRank($listing['domain']);
		}

		// check broken URL
		if ($esynConfig->getConfig('listing_check') && !$esynConfig->getConfig('broken_visitors'))
		{
			$listing['listing_header'] = esynUtil::getListingHeader($listing['url']);

			$correct_headers = explode(',', $esynConfig->getConfig('http_headers'));
			if (!in_array($listing['listing_header'], $correct_headers))
			{
				$error = true;
				$msg[] = $esynI18N['error_broken_listing'];
			}
		}
	}

	if (!$listing['title'])
	{
		$error = true;
		$msg[] = $esynI18N['title_incorrect'];
	}

	// Deep links
	if (isset($_POST['assign_plan']))
	{
		if (isset($_POST['deep_links']) && isset($_POST['deep_links'][$_POST['assign_plan']]) && !empty($_POST['deep_links'][$_POST['assign_plan']]))
		{
			if (!empty($listing['url']))
			{
				$deep_links = $_POST['deep_links'][$_POST['assign_plan']];

				foreach($deep_links as $key => $deep_link)
				{
					if (isset($deep_link['url']) && !empty($deep_link['url']))
					{
						$deep_link['url'] = trim($deep_link['url']);

						if (false === strstr($deep_link['url'], 'http'))
						{
							$deep_link['url']  = "http://" . $deep_link['url'];
						}
					}

					// valid url, not empty title and is deep link
					if (false !== strpos($deep_link['url'], $listing['url']) || !$esynConfig->getConfig('deep_links_validate'))
					{
						$listing['_deep_links'][] = $deep_link;
					}
				}
			}
		}
	}
	// end Deep links

	// check reciprocal link
	if ($valid_url && esynValidator::isUrl($_POST['reciprocal']) && $esynConfig->getConfig('reciprocal_check') && $esynConfig->getConfig('reciprocal_visitors'))
	{
		if ($esynConfig->getConfig('reciprocal_domain'))
		{
			if (esynUtil::getDomain($_POST['reciprocal']) != esynUtil::getDomain($_POST['url']))
			{
				$error = true;
				$msg[] = 'Reciprocal link seems to be placed on different domain.';
			}
		}

		$rcodes = explode("\r\n", $esynConfig->getConfig('reciprocal_link'));
		foreach ($rcodes as $r)
		{
			$listing['recip_valid'] = esynValidator::hasUrl($_POST['reciprocal'], $r);
			if ($listing['recip_valid'])
			{
				break;
			}
		}

		if (!$listing['recip_valid'])
		{
			$error = true;
			$msg[] = $esynI18N['no_backlink'];
		}
	}

	// check duplicate link
	if ($esynConfig->getConfig('duplicate_checking') && !$esynConfig->getConfig('duplicate_visitors'))
	{
		if (!isset($_GET['do']) || (isset($_GET['do']) && 'edit' == $_GET['do'] && $old_listing['url'] != $_POST['url']))
		{
			$domain = esynUtil::getDomain($_POST['url']);

			$check = $esynConfig->getConfig('duplicate_type') == 'domain' ? $domain : $_POST['url'];

			$res = $esynListing->checkDuplicateListings($check, $esynConfig->getConfig('duplicate_type'));

			if ($res && $_GET['id'] != $res)
			{
				$error = true;
				$msg[] = $esynI18N['error_listing_present'];
			}
		}
	}

	if (isset($_POST['multi_crossed']))
	{
		$multi_crossed = empty($_POST['multi_crossed']) ? array() : explode("|", $_POST['multi_crossed']);

		//validate to integer and unique values
		$multi_crossed = array_unique(array_map("intval",$multi_crossed));

		$listing['multi_crossed'] = $multi_crossed;
	}

	if (!$error)
	{
		$listing['_notify'] = isset($_POST['send_email']) ? (bool)$_POST['send_email'] : false;

		$listing['featured'] = (int)$_POST['featured'];
		if ('1' == $_POST['featured'])
		{
			$additional['featured_start'] = 'NOW()';
		}
		else
		{
			$listing['featured_start'] = '0000-00-00 00:00:00';
		}

		$listing['partner'] = (int)$_POST['partner'];
		if ('1' == $_POST['partner'])
		{
			$additional['partner_start'] = 'NOW()';
		}
		else
		{
			$listing['partner_start'] = '0000-00-00 00:00:00';
		}

		$listing['sponsored'] = (int)$_POST['sponsored'];
		if ('1' == $_POST['sponsored'])
		{
			$additional['sponsored_start'] = 'NOW()';
		}
		else
		{
			$listing['sponsored_start'] = '0000-00-00 00:00:00';
		}

		if (isset($_GET['do']) && 'edit' == $_GET['do'])
		{
			if (isset($_POST['title_alias']) && !empty($_POST['title_alias']) && $_POST['title_alias'] != $_POST['old_alias'])
			{
				$listing['title_alias'] = $_POST['title_alias'];
			}
			else
			{
				$listing['title_alias'] = esynUtil::getAlias($_POST['title']);
			}
		}
		else
		{
			if (isset($_POST['title_alias']) && !empty($_POST['title_alias']))
			{
				$listing['title_alias'] = $_POST['title_alias'];
			}
			else
			{
				$listing['title_alias'] = esynUtil::getAlias($_POST['title']);
			}
		}

		// generate meta keywords
		if (!$_POST['meta_keywords'])
		{
			include IA_INCLUDES . 'utils' . IA_DS . 'keywordsgenerator.php';
			$listing['meta_keywords'] = KeywordsGenerator::generateKeywords($listing['title'] . esynSanitize::striptags($listing['description']));
		}

		if (isset($_POST['visual_options']))
		{
			$listing['visual_options'] = implode(",", $_POST['visual_options']);
		}

		if (isset($_POST['do']) && 'edit' == $_POST['do'])
		{
			$esynAdmin->startHook('phpAdminSuggestListingBeforeListingUpdate');

			$listing_new_id = $esynListing->update($listing, "`id` = '{$id}'", $additional);
			$listing_new_id = $id;

			$msg[] = $esynI18N['changes_saved'];
		}
		else
		{
			$esynAdmin->startHook('phpAdminSuggestListingBeforeListingInsert');

			$listing_new_id = $esynListing->insert($listing, $additional);

			$esynAdmin->startHook('phpAdminSuggestListingAfterListingInsert');

			$msg[] = $esynI18N['admin_listing_submitted'];
		}

		$esynCategory->adjustNumListings($listing['category_id']);

		if (!empty($account))
		{
			if (isset($account['account_id']))
			{
				$listing['account_id'] = $account['account_id'];
			}
			else
			{
				$listing['path'] = $esynCategory->one("`path`", "`id` = '{$listing['category_id']}'");
				$listing['id'] = $listing_new_id;
				$listing['account_id'] = $esynAccount->registerAccount($account, $listing);
			}

			$esynAdmin->setTable("listings");
			$esynAdmin->update(array('account_id' => $listing['account_id']), "`id` = '{$listing_new_id}'");
			$esynAdmin->resetTable();
		}

		if (isset($_POST['assign_plan']))
		{
			if ((int)$_POST['assign_plan'] > 0)
			{
				$esynListing->setPlan($listing_new_id, (int)$_POST['assign_plan']);
			}

			if ('-1' == $_POST['assign_plan'])
			{
				$esynListing->resetPlan($listing_new_id);
			}
		}

		$parent = $esynCategory->one("parent_id", "`id`='".$listing['category_id']."'");

		if (isset($multi_crossed[0]))
		{
			$msg[] = $esynI18N['cross_listing_created'];
		}

		esynMessages::setMessage($msg, $error);

		if (isset($_POST['goto']))
		{
			if ('add' == $_POST['goto'])
			{
				esynUtil::reload();
			}
			elseif ('browse' == $_POST['goto'])
			{
				esynUtil::go2("controller.php?file=browse&id={$listing['category_id']}");
			}
			elseif ('addtosame' == $_POST['goto'])
			{
				esynUtil::go2("controller.php?file=suggest-listing&id={$listing['category_id']}");
			}
			elseif ('list' == $_POST['goto'])
			{
				$status = isset($_GET['status']) && in_array($_GET['status'], array('active', 'approval', 'suspended', 'banned')) ? '&status=' . $_GET['status'] : '';

				esynUtil::go2("controller.php?file=listings" . $status);
			}
		}
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']))
{
	if ('clear' == $_GET['action'])
	{
		$esynAdmin->setTable('listings');

		$imageName = $esynAdmin->one($_GET['field'], "`id` = :id", array('id' => $_GET['id']));

		$imageName = explode(',', $imageName);

		foreach($imageName as $key => $image)
		{
			if ($image == $_GET['image'])
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

		$esynAdmin->update(array($_GET['field'] => $imageName), "`id` = :id", array('id' => $_GET['id']));

		$esynAdmin->resetTable();

		$out['error'] = false;
		$out['msg'] = $esynI18N['image_deleted'];

		echo esynUtil::jsonEncode($out);
		exit;
	}

	if ('getlistingurl' == $_GET['action'])
	{
		$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
		$titlepath = isset($_GET['title']) ? $_GET['title'] : '';

		if (null !== $category_id && !empty($titlepath))
		{
			$esynAdmin->loadClass("Layout");
			$esynLayout = new esynLayout();

			$category_path = $esynCategory->one('`path`', "`id` = '{$category_id}'");
			$max_id = $esynListing->one('MAX(`id`) + 1');

			$titlepath = esynUtil::getAlias($titlepath);

			$path = $esynCategory->getPath($category_path, '');

			$out['data'] = $esynLayout->printForwardUrl(array('title_alias' => $titlepath, 'path' => $path, 'id' => $max_id));
		}

		if (empty($out['data']))
		{
			$out['data'] = "";
		}

		echo esynUtil::jsonEncode($out);
		exit;
	}

	if ('getaccounts' == $_GET['action'])
	{
		$query = isset($_GET['query']) ? esynSanitize::sql(trim($_GET['query'])) : '';

		$esynAdmin->setTable("accounts");
		$out['data'] = $esynAdmin->all("`id`, `username`", "`username` LIKE '{$query}%'");
		$out['total'] = $esynAdmin->one("COUNT(*)", "`username` LIKE '{$query}%'");
		$esynAdmin->resetTable();

		if (empty($out['data']))
		{
			$out['data'] = "";
		}

		echo esynUtil::jsonEncode($out);
		exit;
	}

	if ('remove_deep' == $_GET['action'])
	{
		if (isset($_GET['ids']) && !empty($_GET['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_GET['ids']);

			$esynAdmin->setTable("deep_links");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();
		}

		exit;
	}
}

$gBc = array();

$gBc[0]['title'] = $esynI18N['manage_listings'];
$gBc[0]['url'] = 'controller.php?file=listings';
$gBc[0]['item_url'] = 'controller.php?file=browse';

$gBc[1]['title'] = $esynI18N['create_listing'];
$gBc[1]['url'] = 'controller.php?file=suggest-listing&amp;id='.$id;

$gTitle = $esynI18N['create_listing'];

if (isset($_GET['do']) && isset($_GET['id']) && ctype_digit($_GET['id']))
{
	if ('edit' == $_GET['do'])
	{
		$gBc[1]['title'] = $esynI18N['edit_listing'];
		$gBc[1]['url'] = 'controller.php?file=suggest-listing&amp;do=edit&amp;id='.$id;

		$gTitle = $esynI18N['edit_listing'];

		if ($listing)
		{
			$listing['old_alias'] = $listing['title_alias'];
		}

		$esynAdmin->setTable("deep_links");
		$listing['deep_links'] = $esynAdmin->all("`id`, `title`, `url`", "`listing_id` = '{$listing['id']}'");
		$esynAdmin->resetTable();
	}
}

/** sort fields by group **/
$temp = array();
foreach ($fields as $key => $field)
{
	if (in_array($field['group'], $field_groups))
	{
		$temp[$field['group']][] = $field;
		unset($fields[$key]);
	}
}

//TODO: check non_group
if (!empty($fields))
{
	$temp['non_group'] = $fields;
}

$fields = $temp;
unset($temp);

$esynAdmin->startHook('phpAdminSuggestListingBeforeView');

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->assign('listing', $listing);
$esynSmarty->assign('category', $category);
$esynSmarty->assign('fields', $fields);
$esynSmarty->assign('plans', $plans);
$esynSmarty->assign('parent', $parent);

if (isset($account) && !empty($account))
{
	$esynSmarty->assign('account', $account);
}

if (isset($crossed_html) && !empty($crossed_html))
{
	$esynSmarty->assign('crossed_html', $crossed_html);
}

$esynSmarty->display('suggest-listing.tpl');
