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

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header-lite.php';

// process actions
$out = array();
if (isset($_GET))
{
	switch ($_GET['action'])
	{
		case 'getpagerank':

			$url = $_GET['url'] ? $_GET['url'] : '';

			if ($url)
			{
				echo PageRank::getPageRank($url);
			}
			exit;
			break;

		case 'fetchmetas':

			$url = $_GET['url'] ? $_GET['url'] : '';
			if ($url)
			{
				$tags = get_meta_tags($url);
				echo $tags['description'];
			}
			exit;
			break;

		case 'blocks':

			if (!$_SESSION['frontendManageMode'])
			{
				esynUtil::accessDenied();
			}

			// validate id
			$ids = isset($_GET['blocks']) ? $_GET['blocks'] : array();
			if (empty($ids) || !is_array($_GET['blocks']))
			{
				die('Incorrect block id passed.');
			}

			// validate position
			$position = isset($_GET['position']) ? $_GET['position'] : '';
			$blocks_positions = $eSyndiCat->getConfig('esyndicat_block_positions');
			if (empty($position) || !in_array($position, explode(',', $blocks_positions)))
			{
				die('Incorrect block position passed.');
			}

			// update block info
			$eSyndiCat->setTable('blocks');
			$i = 0;
			foreach($ids as $blockid)
			{
				if ((int)$blockid)
				{
					$i++;
					$eSyndiCat->update(array("id" => $blockid, "position" => $position, 'order' => $i));
				}
			}
			$eSyndiCat->resetTable();

			echo "Ok";
			die();

			break;

		case 'catsfilter':

			if (isset($_GET['q']))
			{
				$eSyndiCat->factory('Category');

				$request = esynSanitize::sql($_GET['q']);
				$categories = $esynCategory->getCatByCriteria(0, 15, "WHERE t44.`title` LIKE '{$request}%' ");
				if (is_array($categories))
				{
					foreach($categories as $category)
					{
						$parents = $esynCategory->getParents(array('title', 'id', 'path'), $category['id']);
						if ($parents)
						{
							$title = '';
							$i = 0;
							$id = array();
							$cnt = count($parents);
							foreach($parents as $parent)
							{
								$i++;
								$title .= $parent['title'] . ($cnt != $i ? ' > ' : '');
								$id[] = $parent['id'];
								$display = $parent['title'];
								$path = $parent['path'];
							}
						}
						else
						{
							$display = $title = $category['title'];
							$id = $category['id'];
						}
						$out[] = array('title' => $title, 'id' => $id, 'display' => $display, 'path' => $path);
					}
				}
			}

			break;

		case 'autocomplete':

			if (isset($_GET['q']))
			{
				$eSyndiCat->factory('Listing', 'Layout');

				$request = esynSanitize::sql($_GET['q']);
				$listings = $esynListing->getByCriteria(0, 15, "`listings`.`title` LIKE '%{$request}%' ");
				if (is_array($listings))
				{
					foreach($listings as $listing)
					{
						$out[] = array('title' => $listing['title'], 'url' => $esynLayout->printListingUrl(array('listing' => $listing, 'details' => true)));
					}
				}
			}

			break;

		case 'getplans':

			$eSyndiCat->factory('Plan');

			$cid = isset($_GET['idcategory']) ? (int)$_GET['idcategory'] : 0;
			$type = isset($_GET['type']) && in_array($_GET['type'], array('listing', 'account')) ? $_GET['type'] : 'listing';
			$page = isset($_GET['page']) && in_array($_GET['page'], array('suggest', 'edit')) ? $_GET['page'] : 'suggest';

			$out = $esynPlan->getAllPlansByCategory($cid, "`item` = '{$type}' AND FIND_IN_SET('{$page}', `pages`) AND `lang` = '" . IA_LANGUAGE . "'");

			if ($out)
			{
				$eSyndiCat->setTable('listing_visual_options');
				foreach ($out as $key => $plan)
				{
					if (!empty($plan['visual_options']))
					{
						$out[$key]['options'] = $eSyndiCat->all("*", "`show` = '1' AND `name` IN({$plan['visual_options']})");
					}
				}
				$eSyndiCat->resetTable();
			}

			break;

		case 'get-visual-options':

			$option_ids = $_GET['option_ids'] ? implode("','", $_GET['option_ids']) : '';

			$eSyndiCat->setTable('listing_visual_options');
			$out = $eSyndiCat->assoc("`name`, `value`", "`show` = '1' AND `name` IN('{$option_ids}')");
			$eSyndiCat->resetTable();

			break;

		case 'admin-get-visual-options':

			$option_ids = $_GET['option_ids'] ? $_GET['option_ids'] : '';

			$eSyndiCat->setTable('listing_visual_options');
			$out = $eSyndiCat->all("`name`, `value`,`price`", "`show` = '1' AND `name` IN({$option_ids})");
			$eSyndiCat->resetTable();

			break;

		case 'moving':

			$eSyndiCat->factory('Listing');

			$idLink = (int)$_GET['idlink'];
			$idCat = (int)$_GET['idcat'];

			$listing = $esynListing->row("`id`,`title`,`category_id`,`moved_from`,`status`", "`id` = " . $idLink);

			$eSyndiCat->setTable('listing_categories');
			$crossed_categories = $eSyndiCat->all("`category_id`", "`listing_id` = " . $idLink);
			$eSyndiCat->resetTable();

			if (!empty($crossed_categories))
			{
				foreach($crossed_categories as $onecategory)
				{
					$exception[] = $onecategory['category_id'];
				}
			}

			if (!empty($exception))
			{
				if (in_array($idCat, $exception))
				{
					$msg = 'Unexpected error!';
					die();
				}
			}

			$eSyndiCat->setTable('language');
			$esynI18N = $eSyndiCat->keyvalue("`key`,`value`", "`code` = '" . IA_LANGUAGE . "' AND `key` IN ('listing_returned', 'listing_moved')");
			$eSyndiCat->resetTable();

			$updater = array();

			$updater['id'] = $idLink;
			$updater['category_id'] = $idCat;

			if (-1 == (int)$listing['moved_from'])
			{
				$updater['moved_from'] = $listing['category_id'];
			}
			elseif ($idCat == (int)$listing['moved_from'])
			{
				$updater['moved_from'] = -1;
			}

			if ('approval' != $listing['status'])
			{
				$updater['status'] = 'approval';
			}

			$check = $esynListing->update($updater);

			if ($check)
			{
				$eSyndiCat->factory('Category');

				$category = $esynCategory->row('`id`,`title`,`path`', "`id` = '{$idCat}'");

				$url = IA_URL;
				$linkUrl = $category['path'] . esynUtil::convertStr(array('string' => $listing['title'])) . '-l' . $listing['id'] . '.html';
				$categoryUrl = $esynConfig->getConfig('use_html_path') ? $category['path'] . '.html' : $category['path'];

				if ($idCat == (int)$listing['moved_from'])
				{
					$msg = "Listing <a href=\"{$url}{$linkUrl}\">{$listing['title']}</a> was returned back to <a href=\"{$url}{$categoryUrl}\">{$category['title']}</a>";
				}
				else
				{
					$msg = "Listing <a href=\"{$url}{$linkUrl}\">{$listing['title']}</a> successfully moved to <a href=\"{$url}{$categoryUrl}\">{$category['title']}</a>";
				}

				if ('active' == $listing['status'])
				{
					$esynCategory->adjustNumListings($idCat);
				}
			}

			echo '<p class="field"><strong>' . $msg . '</strong></p>';
			exit;

			break;

		default:
			break;
	}
}

if (isset($_POST))
{
	$eSyndiCat->factory('Listing');

	switch ($_POST['action'])
	{
		case 'reset_filter':

			if (isset($_SESSION['searchFilters']) && $_SESSION['searchFilters'])
			{
				$searchFilters = unserialize($_SESSION['searchFilters']);
			}
			unset($searchFilters[$_POST['name']]);

			$_SESSION['searchFilters'] = serialize($searchFilters);

			break;

		case 'report':

			$id = (int)$_POST['id'];

			$out['error'] = true;
			if ($listing = $esynListing->row("`id`,`url`,`title`", "`id`='" . $id . "'"))
			{
				$out['error'] = false;
				$out['msg'] = $esynI18N['broken_report_sent'];

				// set reported as broken flag
				$esynListing->update(array('reported_as_broken' => 1, 'id' => $listing['id']));

				// notify administrators about
				$eSyndiCat->mMailer->notifyAdmins('listing_broken_report', 0, $listing['id']);
			}

			break;

		case 'count':

			$id = (int)$_POST['id'];
			$ip = esynUtil::getIpAddress();
			$item = $_POST['item'];

			$eSyndiCat->startHook('clickCountItem');

			if ('listings' == $item)
			{
				if ($esynListing->exists("`id` = :id", array('id' => $id)) && !$esynListing->checkClick($id, $ip))
				{
					$esynListing->click($id, $ip);
				}
			}

			break;

		case 'favorites':

			$listing_id	= (int)$_POST['listing_id'];
			$account_id = (int)$_POST['account_id'];

			if ('add' == $_POST['trigger'])
			{
				$duplicate = $esynListing->exists("`id` = '{$listing_id}' AND `fav_accounts_set` LIKE '%{$account_id},%'");
				if (!$duplicate)
				{
					$sql = "UPDATE `{$eSyndiCat->mPrefix}listings` SET `fav_accounts_set` = CONCAT(`fav_accounts_set`, '{$account_id},') ";
					$sql .= "WHERE `id` = '{$listing_id}'";

					$return = $eSyndiCat->query($sql, 'listings');
				}
			}
			elseif ('remove' == $_POST['trigger'])
			{
				$sql = "SELECT `fav_accounts_set` FROM `{$eSyndiCat->mPrefix}listings` ";
				$sql .= "WHERE `id` = '{$listing_id}' ";
				$sql .= "AND `fav_accounts_set` LIKE '%{$account_id},%'";
				$accounts_id = $eSyndiCat->getOne($sql, array(), 'listings');
				if ($accounts_id)
				{
					$newValue = str_replace("{$account_id},", '', $accounts_id);
					$sql = "UPDATE `{$eSyndiCat->mPrefix}listings` SET `fav_accounts_set` = '{$newValue}' ";
					$sql .= "WHERE `id` = '{$listing_id}'";

					$return = $eSyndiCat->query($sql, 'listings');
				}
			}

			break;

		default:
			break;
	}
}

// return json format
echo esynUtil::jsonEncode($out);
