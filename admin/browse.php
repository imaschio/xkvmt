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

define('IA_REALM', "browse");

esynUtil::checkAccess();

if (	isset($_GET['id']) && preg_match("/\D/", $_GET['id']) || isset($_GET['listing']) && preg_match("/\D/", $_GET['listing']))
{
	die('Powered By eSyndiCat');
}

$esynAdmin->factory("Category", "Listing");

$category_id = isset($_GET['id']) ? (int)$_GET['id'] : false;

if (!$category_id)
{
	$category_id = $esynCategory->one("`id`", "`title` = 'ROOT'");
}

if (isset($_GET['do']))
{
	if ('lock' == $_GET['do'])
	{
		$subcategories = (isset($_GET['subcategories']) && 'true' == $_GET['subcategories']) ? true : false;

		$esynCategory->lock($category_id, true, $subcategories);

		$msg = $esynI18N['category_locked'];
	}

	if ('unlock' == $_GET['do'])
	{
		$subcategories = (isset($_GET['subcategories']) && 'true' == $_GET['subcategories']) ? true : false;

		$esynCategory->unlock($category_id, $subcategories);

		$msg = $esynI18N['category_unlocked'];
	}

	if ('unique' == $_GET['do'])
	{
		$esynCategory->setUnique($category_id);

		$msg = $esynI18N['category_unique']." ( index{$category_id}.tpl )";
	}

	if ('ununique' == $_GET['do'])
	{
		$esynCategory->setUnique($category_id, false);

		$msg = $esynI18N['category_ununique'];
	}

	if ('delete' == $_GET['do'])
	{
		$id = (int)$_GET['id'];

		$parent = $esynCategory->one("parent_id", "`id` = '{$id}'");

		$esynCategory->delete($id);

		$msg = $esynI18N['category_deleted'];

		esynMessages::setMessage($msg);
		esynUtil::reload(array("id" => $parent, "do" => null));
	}

	esynMessages::setMessage($msg);
}

if (isset($_GET['action']))
{
	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$category = !empty($_GET['category']) ? (int)$_GET['category'] : 0;

		$where[] = "`listing_categories`.`category_id` = '{$category}' OR `listings`.`category_id` = '{$category}'";

		if (isset($_GET['status']) && 'all' != $_GET['status'] && !empty($_GET['status']))
		{
			$where[] = "`listings`.`status` = '" . esynSanitize::sql($_GET['status']) . "'";
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
			if ('destvalid' == $_GET['state'])
			{
				$where[] = "`listings`.`listing_header` IN('200', '301', '302')";
			}
			elseif ('destbroken' == $_GET['state'])
			{
				$where[] = "`listings`.`listing_header` NOT IN('200', '301', '302')";
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

		$out = array('data' => '', 'total' => 0);

		$sql = "SELECT SQL_CALC_FOUND_ROWS `listings`.*, `listings`.`id` `edit`, `accounts`.`username`, `categories`.`id` `category_id`, `categories`.`path`, `categories`.`title` `category`, `accounts`.`email` `email` ";
		$sql .= "FROM `{$esynAdmin->mPrefix}listings` `listings` ";
		$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}accounts` `accounts` ";
		$sql .= "ON `listings`.`account_id` = `accounts`.`id` ";
		$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}categories` `categories` ";
		$sql .= "ON `listings`.`category_id` = `categories`.`id` ";
		$sql .= "LEFT JOIN `{$esynAdmin->mPrefix}listing_categories` `listing_categories` ";
		$sql .= "ON `listings`.`id` = `listing_categories`.`listing_id` ";

		if (!empty($where))
		{
			$sql .= "WHERE ";
			$sql .= join(' AND ', $where);
		}

		if (!empty($_GET['sort']))
		{
			$sql .= sprintf(" ORDER BY `%s` %s ", $esynAdmin->escape_sql($_GET['sort']), ('ASC' == $_GET['dir'] ? 'ASC' : 'DESC'));
		}

		$sql .= "LIMIT {$start}, {$limit}";

		$out['data'] = $esynListing->getAll($sql);
		$out['total'] = $esynListing->foundRows();
		$out['data'] = esynSanitize::applyFn($out['data'], "html");
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
	$out = array('msg' => '', 'error' => true);

	if ('catcopy' == $_POST['action'])
	{
		$id = (int)$_POST['id']; // category id you are coping
		$categories = $_POST['category']; // destination category id

		if (!empty($categories))
		{
			foreach($categories as $category)
			{
				$category = (int)$category;
				if ($category != $id)
				{
					// Checking copy availability
					// don't allow category to be copied to itself nor it's child
					$temp_parent = $id;
					$parents = array();

					// get all parents until ROOT and break
					for($k = 0; $k < 25; $k++)
					{
						$parents[] = (int)$temp_parent;
						$temp_parent = $esynCategory->one("parent_id", "`id` = '{$temp_parent}'");

						if ('0' == $temp_parent)
						{
							break;
						}
					}

					// $k also is a flag against recursive hell 25 level(iterations)
					if ($k == 25)
					{
						trigger_error("Possible recursive hell while copying category #$category_id to category #$move_to",E_USER_WARNING);
					}

					if (!in_array($category, $parents, true))
					{
						$out['error'] = false;
						$esynCategory->copySubCategories($id, $category, $recursive = true, true);

						$out['msg'] = $esynI18N['subcategories_copied'];
					}
					else
					{
						trigger_error("Attempting to copy category #$category_id to it's child category #$move_to", E_USER_NOTICE);

						$out['error'] = true;
						$title = $esynCategory->one("`title`", "id = '{$cat}'");

						$out['msg'] = str_replace("{copy_to}", $title, $esynI18N['category_cannot_be_copied']);
					}
				}
			}
		}
	}

	if ('catmove' == $_POST['action'])
	{
		$id = (int)$_POST['id']; // category id you are moving
		$category = (int)$_POST['category'][0]; // destination category id

		// Checking move availability
		// IN NO WAY any category cannot be moved to itself nor it's child
		$esynCategory->setTable("flat_structure");
		$exists = $esynCategory->exists("`category_id`='{$category}' AND `parent_id` = '{$id}'");
		$esynCategory->resetTable();

		if ($exists || $id == $category)
		{
			trigger_error("Attempting to move category #$id to it's child category #$category", E_USER_NOTICE);

			$out['error'] = true;

			$title = $esynCategory->one("`title`", "`id` = '{$category}'");

			$out['msg'] = str_replace("{move_to}", $title, $esynI18N['category_cannot_be_moved']);
		}
		else
		{
			$esynCategory->move($id, $category);

			$parent = $esynCategory->one("parent_id", "`id` = '{$id}'");
			$parent2 = $esynCategory->one("parent_id", "`id` = '{$category}'");

			$out['error'] = false;
			$out['msg'] = _t('category_moved');
		}
	}

	if ('related' == $_POST['action'] && is_array($_POST['categories']) && !empty($_POST['categories']))
	{
		$id = (int)$_POST['id'];

		foreach($_POST['categories'] as $cat)
		{
			$cat = (int)$cat;

			$esynCategory->setTable("related");
			$exist = $esynCategory->exists("category_id = '{$id}' AND `related_id` = '{$cat}'");
			$esynCategory->resetTable();

			if (!$exist)
			{
				$esynCategory->addRelated($id, $cat);
			}
		}

		$out['error'] = false;
		$out['msg'] = $esynI18N['related_added'];
	}

	if ('crossed' == $_POST['action'] && is_array($_POST['categories']) && !empty($_POST['categories']))
	{
		$id = (int)$_POST['id'];

		foreach($_POST['categories'] as $cat)
		{
			$cat = (int)$cat;

			$esynCategory->setTable("crossed");
			$exist = $esynCategory->exists("category_id = '{$id}' AND `crossed_id` = '{$cat}'");
			$esynCategory->resetTable();

			if (!$exist)
			{
				$esynCategory->addCrossed($id, $cat);
			}
		}

		$out['error'] = false;
		$out['msg'] = $esynI18N['crossed_added'];
	}

	if ('update' == $_POST['action'])
	{
		$field = $_POST['field'];
		$value = $_POST['value'];

		if (empty($field) || empty($value) || empty($_POST['ids']))
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

			$esynListing->delete($where, $reason);

			$cat_ids = $esynListing->onefield("`category_id`", $where);
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
		$sendEmail = ($esynConfig->getConfig('listing_move')) ? true : false;

		$esynAdmin->setTable("listing_categories");

		foreach($listings as $value)
		{
			// Don't allow moving
			if ($esynAdmin->exists("`category_id` = '{$category}' AND `listing_id` = '{$value}'"))
			{
				$crosslink = true;
				continue;
			}

			$esynListing->move($value, $category, $sendEmail);
		}

		$esynCategory->adjustNumListings($category);

		$esynAdmin->resetTable();

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

			// don't allow create two cross listings
			if (!$esynAdmin->exists("`listing_id` = '{$value}' AND `category_id` = '{$category}'"))
			{
				$esynListing->copy($value, $category);
			}

			$esynAdmin->resetTable();
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
		$listings = array_map("intval", $_POST['ids']);

		$esynAdmin->setTable("listings");
		foreach($listings as $value)
		{
			$listing_header = esynUtil::getListingHeader($esynListing->one("url", "`id` = '{$value}'"));

			$esynAdmin->update(array("listing_header" => $listing_header), "`id` = '{$value}'");
		}
		$esynAdmin->resetTable();

		$out['msg'] = $esynI18N['done'];
	}

	if ('recip_recheck' == $_POST['action'])
	{
		$listings = array_map("intval", $_POST['ids']);

		$recipText = $esynConfig->getConfig('reciprocal_link');

		$esynAdmin->setTable("listings");
		foreach($listings as $value)
		{
			$recipValid = esynValidator::hasUrl($esynListing->one("reciprocal", "`id` = '{$value}'"), $recipText);

			$data = array("recip_valid" => (int)$recipValid, "id" => $value);
			$addit = array();

			if ($esynConfig->getConfig('recip_featured') && $recipValid)
			{
				$data['featured'] = '1';
				$addit['featured_start'] = "NOW()";
			}

			$esynAdmin->update($data, "`id` = '{$value}'", array(), $addit);
		}
		$esynAdmin->resetTable();

		$out['msg'] = $esynI18N['done'];
	}

	if ('remove_related' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
		{
			$category_id = (int)$_POST['category'];

			$where = $esynAdmin->convertIds('related_id', $_POST['ids']);
			$where .= "AND `category_id` = '{$category_id}'";

			$esynAdmin->setTable("related");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$esynAdmin->mCacher->remove("categoriesByParent_" . $category_id);

			$out['error'] = false;
			$out['msg'] = $esynI18N['related_deleted'];
		}
	}

	if ('remove_crossed' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
		{
			$category_id = (int)$_POST['category'];

			$where = $esynAdmin->convertIds('crossed_id', $_POST['ids']);
			$where .= "AND `category_id` = '{$category_id}'";

			$esynAdmin->setTable("crossed");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$esynAdmin->mCacher->remove("categoriesByParent_" . $category_id);

			$out['error'] = false;
			$out['msg'] = $esynI18N['crossed_deleted'];
		}
	}

	if ('edit_related' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
		{
			$category_id = (int)$_POST['category'];

			$where = $esynAdmin->convertIds('related_id', $_POST['ids']);
			$where .= "AND `category_id` = '{$category_id}'";

			$esynAdmin->setTable("related");
			$esynAdmin->update(array("category_title"=>$_POST['title']), $where);
			$esynAdmin->resetTable();

			$esynAdmin->mCacher->remove("categoriesByParent_" . $category_id);

			$out['error'] = false;
			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	if ('edit_crossed' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
		{
			$category_id = (int)$_POST['category'];

			$where = $esynAdmin->convertIds('crossed_id', $_POST['ids']);
			$where .= "AND `category_id` = '{$category_id}'";

			$esynAdmin->setTable("crossed");
			$esynAdmin->update(array("category_title"=>$_POST['title']), $where);
			$esynAdmin->resetTable();

			$esynAdmin->mCacher->remove("categoriesByParent_" . $category_id);

			$out['error'] = false;
			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$category = $esynCategory->row("*", "`id` = '{$category_id}'");

if (!empty($category))
{
	$category['title'] = esynSanitize::html($category['title']);
}

$gTitle = $esynI18N['browse'];

$gBc[0]['title'] = $esynI18N['browse'];
$gBc[0]['url'] = "controller.php?file=browse";

$actions[] = array(
	"url"	=> "controller.php?file=suggest-category&amp;id={$category['id']}",
	"icon"	=> "create_category.png",
	"label"	=> $esynI18N['add_category_to'] . $category['title']
);

$actions[] = array(
	"url"	=> "controller.php?file=suggest-category&amp;do=edit&amp;id={$category['id']}",
	"icon"	=> "edit_category.png",
	"label"	=> $esynI18N['edit'].' '.$category['title']
);

if ($category['unique_tpl'])
{
	$actions[] = array(
		"url"	=> "controller.php?file=browse&amp;do=ununique&amp;id={$category['id']}",
		"icon"	=> "ununique_category.png",
		"label"	=> $esynI18N['category_ununique']
	);
}
else
{
	$actions[] = array(
		"url"	=> "controller.php?file=browse&amp;do=unique&amp;id={$category['id']}",
		"icon"	=> "unique_category.png",
		"label"	=> $esynI18N['category_unique']
	);
}

if ($category['locked'])
{
	$actions[] = array(
		"url"			=> "controller.php?file=browse&amp;do=unlock&amp;id={$category['id']}",
		"icon"			=> "unlock_category.png",
		"label"			=> $esynI18N['unlock'].' '.$category['title'],
		"attributes"	=> 'class="actions_catunlock_'.$category['id'].'"'
	);
}
else
{
	$actions[] = array(
		"url"			=> "controller.php?file=browse&amp;do=lock&amp;id={$category['id']}",
		"icon"			=> "lock_category.png",
		"label"			=> $esynI18N['lock'].' '.$category['title'],
		"attributes"	=> 'class="actions_catlock_'.$category['id'].'"'
	);
}

if ($category['id'] > 0)
{
	$actions[] = array(
		"url"			=> "copy",
		"icon"			=> "copy_subcategories.png",
		"label"			=> $esynI18N['copy'].' subcategories of '.$category['title'],
		"attributes"	=> 'class="actions_catcopy_'.$category['id'].'"'
	);

	$actions[] = array(
		"url"			=> "move",
		"icon"			=> "move_category.png",
		"label"			=> $esynI18N['move'].' '.$category['title'],
		"attributes"	=> 'class="actions_catmove_'.$category['id'].'"'
	);

	$actions[] = array(
		"url"			=> "controller.php?file=browse&amp;do=delete&amp;id={$category['id']}",
		"icon"			=> "delete_category.png",
		"attributes"	=> 'id="delete_category"',
		"label"			=> $esynI18N['delete'].' '.$category['title']
	);

	if ($esynConfig->getConfig('related'))
	{
		$actions[] = array(
			"url"			=> "related",
			"icon"			=> "add_related.png",
			"label"			=> $esynI18N['add_related'].' to '.$category['title'],
			"attributes"	=> 'class="actions_related_'.$category['id'].'"'
		);
	}

	$actions[] = array(
		"url"			=> "crossed",
		"icon"			=> "add_crossed.png",
		"label"			=> $esynI18N['add_crossed'].' to '.$category['title'],
		"attributes"	=> 'class="actions_crossed_'.$category['id'].'"'
	);
}

$actions[] = array(
	"url"	=> "controller.php?file=suggest-listing&amp;id={$category['id']}",
	"icon"	=> "create_listing.png",
	"label"	=> $esynI18N['add_listing_to'] . $category['title']
);

require_once IA_ADMIN_HOME . 'view.php';

$categories = $esynCategory->getAllByParent($category['id'], false, $esynConfig->getConfig('subcats_display'));

$esynAdmin->setTable("related");
$related_id = $esynAdmin->onefield("`related_id`", "`category_id` = '{$category['id']}'");
$esynAdmin->resetTable();

if (!empty($related_id))
{
	$related_categories = $esynCategory->getRelated($category['id']);

	$esynSmarty->assign('related_categories', $related_categories);
}

$esynSmarty->assign('categories', $categories);

$esynSmarty->display('browse.tpl');
