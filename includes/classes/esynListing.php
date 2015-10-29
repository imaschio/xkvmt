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

class esynListing extends eSyndiCat
{

	var $mTable = 'listings';

	public function insert($aListing, $addit = array())
	{
		global $esynCategory;

		$retval = 0;
		$aListing['moved_from'] = '-1';

		// Generate and execute the query for adding the listing.
		$sql = "INSERT INTO `" . $this->mTable . "` (";
		foreach($aListing as $key=>$value)
		{
			$sql .= !in_array($key, array('multi_crossed', '_deep_links'), true) ? " `{$key}`, " : '';
		}
		if ($addit)
		{
			foreach($addit as $key=>$value)
			{
				$sql .= " `{$key}`, ";
			}
		}
		$sql .= "`date`) VALUES (";
		foreach($aListing as $key=>$value)
		{
			if (!in_array($key, array('multi_crossed', '_deep_links'), true))
			{
				$value = esynSanitize::sql($value);

				$sql .= "'{$value}',";
			}
		}

		if ($addit)
		{
			foreach ($addit as $key => $value)
			{
				$value = esynSanitize::sql($value);

				$sql .= " {$value}, ";
			}
		}
		$sql .= "NOW())";
		$this->query($sql);

		// Generate and execute the query for adding the listing <-> category connection.
		$retval = $this->getInsertId();
		$aListing['id']	= $retval;

		if ($this->mConfig['auto_approval'])
		{
			$esynCategory->adjustNumListings($aListing['category_id']);
		}

		// MCross
		if ($this->mConfig['mcross_functionality'] && !empty($aListing['multi_crossed']) && is_array($aListing['multi_crossed']))
		{
			foreach ($aListing['multi_crossed'] as $one_cross)
			{
				$sql = "INSERT INTO `" . $this->mPrefix . "listing_categories` ";
				$sql .= "(`listing_id`, `category_id`) ";
				$sql .= "VALUES ('".$aListing['id']."', '".$one_cross."')";

				$this->query($sql);
			}
		}

		// Deep links
		if (isset($aListing['_deep_links']) && !empty($aListing['_deep_links']))
		{
			$data = array();

			$this->setTable("deep_links");

			foreach($aListing['_deep_links'] as $key => $deep_link)
			{
				if (empty($deep_link['title']))
				{
					$deep_link['title'] = $deep_link['url'];
				}

				$deep_link['listing_id'] = $aListing['id'];
				$deep_link['title'] = htmlspecialchars($deep_link['title']);
				$deep_link['url'] = htmlspecialchars($deep_link['url']);

				$data[] = $deep_link;
			}

			parent::insert($data);

			$this->resetTable();
		}

		if (!empty($aListing['email']) || (int)$aListing['account_id'] != 0)
		{
			$account = $aListing['account_id'];
			$email = $aListing['email'];
			if (empty($email))
			{
				$this->setTable("accounts");
				$account = $this->row("*", "`id` = '" . $account . "'");
				$this->resetTable();
				$email = $account['email'];
			}
			$this->mMailer->AddAddress($email);
			$this->mMailer->Send('listing_submit', $account, $aListing);
		}

		return $retval;
	}

	public function update($aListing, $addit = array(), $values = array(), $where='')
	{
		global $esynCategory;

		$category_id = $aListing['category_id'];

		$id = false;

		if (isset($aListing['id']))
		{
			$id = (int)$aListing['id'];
		}

		// Deep links
		if (isset($aListing['_deep_links']) && !empty($aListing['_deep_links']))
		{
			$this->setTable("deep_links");

			if ($id)
			{
				parent::delete("`listing_id` = '{$id}'");
			}

			$data = array();

			foreach($aListing['_deep_links'] as $key => $deep_link)
			{
				if (empty($deep_link['title']))
				{
					$deep_link['title'] = $deep_link['url'];
				}
				$deep_link['listing_id'] = $aListing['id'];
				$deep_link['title'] = htmlspecialchars($deep_link['title']);
				$deep_link['url'] = htmlspecialchars($deep_link['url']);

				$data[] = $deep_link;
			}

			parent::insert($data);

			unset($data);
			unset($aListing['_deep_links']);

			$this->resetTable();
		}
		// end Deep links

		// MCross ----
		if ($this->mConfig['mcross_functionality'] && !empty($aListing['multi_crossed']) && is_array($aListing['multi_crossed']))
		{
			parent::setTable("listing_categories");
			parent::delete("`listing_id` = '{$aListing['id']}'");
			parent::resetTable();

			foreach ($aListing['multi_crossed'] as $one_cross)
			{
				$sql = "INSERT INTO `" . $this->mPrefix . "listing_categories` ";
				$sql .= "(`listing_id`, `category_id`) ";
				$sql .= "VALUES ('" . $aListing['id'] . "', '" . $one_cross . "')";

				$this->query($sql);
			}

			unset($aListing['multi_crossed']);
		}
		// ---- MCross

		// listing status
		$aListing['status'] = $this->mConfig['auto_approval'] ? 'active' : 'approval';

		$where = $this->convertIds('id', $id);

		if ($addit)
		{
			$addit = array_merge($addit, array('last_modified' => 'NOW()'));
		}

		parent::update($aListing, $where, false, $addit);

		if ($this->mConfig['auto_approval'])
		{
			$esynCategory->adjustNumListings($aListing['category_id']);
		}

		return true;
	}

	public function deleteListing($listing_id, $account_id)
	{
		$where = "`id` = {$listing_id} AND `account_id` = {$account_id}";
		$aListing = $this->one("*", $where);
		if(!$aListing)
		{
			return 0;
		}

		if(!$this->fields)
		{
			$this->setTable("listing_fields");
			$this->fields = $this->keyvalue("`name`,`type`");
			$this->resetTable();
		}

		$deleted = parent::delete($where);
		$ids = array();

		foreach($this->fields as $n => $type)
		{
			if ($type == 'image' || $type == 'storage')
			{
				if (is_file(IA_UPLOADS . $aListing[$n]))
				{
					unlink(IA_UPLOADS . $aListing[$n]);
				}
			}
		}

		$totalDeleted = $this->cascadeDelete(
			array(
				"listing_clicks",
				"deep_links"
			),
			"`listing_id`='".$aListing['id']."'"
		);

		$ids[] = $aListing['id'];

		if('active' == $aListing['status'])
		{
			$this->decreaseNumListings($aListing['category_id'], 1);
		}

		// work with crossed link. adjust num listings
		$this->setTable("listing_categories");
		$map = parent::keyvalue("`category_id`, count(`category_id`)", "`listing_id` IN('".join("','", $ids)."') GROUP BY `category_id`");
		parent::delete("`listing_id` IN ('".join("','", $ids)."')");
		$this->resetTable();

		if(is_array($map))
		{
			foreach($map as $cat => $count)
			{
				$this->decreaseNumListings($cat, $count);
			}
		}

		return $deleted;
	}

	public function checkDuplicateListings($aUrl, $aType = 'exact', $all = false)
	{
		$aUrl = preg_replace('/^www\./is', '', $aUrl);

		$sql = "SELECT `id` ";
		$sql .= "FROM `" . $this->mTable . "` ";
		$cause = ($aType == 'domain') ? "WHERE `domain` LIKE '%{$aUrl}'" : "WHERE `url` = '{$aUrl}'";
		$sql .= ($aType == 'contain') ? "WHERE `url` LIKE '%{$aUrl}%'" : $cause;

		if ($all)
		{
			return $this->getAll($sql);
		}
		else
		{
			return $this->getOne($sql);
		}
	}

	public function getListingById($aListing, $aAccount = false)
	{
		$sql = "SELECT t1.*, ";
		$sql .= "t2.`path`, t2.`title` `category_title`, t2.`path` `category_path`, ";
		$sql .= "(SELECT `username` `account_username` FROM `{$this->mPrefix}accounts` `accounts` WHERE `t1`.`account_id` = `accounts`.`id`) `account_username` ";
		$sql .= "FROM `" . $this->mTable . "` t1 ";
		$sql .= "INNER JOIN `" . $this->mPrefix . "categories` t2 ";
		$sql .= "ON t1.`category_id` = t2.`id` ";
		$sql .= "WHERE t1.`id` = '{$aListing}' ";
		if (empty($aAccount))
		{
			$sql .= "AND `t2`.`status` = 'active' AND `t1`.`status`='active'";
		}
		else
		{
			$sql .= "AND `t2`.`status` = 'active' AND (`t1`.`status`='active' OR `t1`.`account_id`='{$aAccount}' )";
		}

		return $this->getRow($sql);
	}

	public function getFavoriteListingByAccountId($aAccount, $aStart = 0, $aLimit = 0)
	{
		$where = "`listings`.`fav_accounts_set` LIKE '%{$aAccount},%'";

		return $this->getByCriteria($aStart, $aLimit, $where, true, $aAccount);
	}

	public function getTop($aStart = 0, $aLimit = 0, $aAccount = '', $aWhere = '')
	{
		return $this->getByCriteria($aStart, $aLimit, $aWhere, true, $aAccount, '`listings`.`rank` DESC');
	}

	public function getLatest($aStart = 0, $aLimit = 0, $aAccount = '', $aWhere = '')
	{
		return $this->getByCriteria($aStart, $aLimit, $aWhere, true, $aAccount, '`listings`.`date` DESC');
	}

	public function getPopular($aStart = 0, $aLimit = 0, $aAccount = '', $aWhere = '')
	{
		return $this->getByCriteria($aStart, $aLimit, $aWhere, true, $aAccount, '`listings`.`clicks` DESC');
	}

	public function getRandom($num=10, $aAccount = '', $aWhere = '')
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS t1.*, t9.`path`, t9.`title` `category_title`, t9.`path` `category_path`, '0' `account_id_edit`, ";
		$sql .= "(SELECT `username` `account_username` FROM `{$this->mPrefix}accounts` `accounts` WHERE `t1`.`account_id` = `accounts`.`id`) `account_username`, ";

		if ($this->mConfig['listing_marked_as_new'] > 0)
		{
			$sql .= "IF((`t1`.`date` + INTERVAL {$this->mConfig['listing_marked_as_new']} DAY < NOW()), '0', '1') `interval`, ";
		}

		$sql .= $aAccount ? "IF (`fav_accounts_set` LIKE '%{$aAccount},%', '1', '0') `favorite` " : "'0' `favorite` ";
		$sql .= "FROM `" . $this->mTable . "` t1 ";
		$sql .= "LEFT JOIN `" . $this->mPrefix . "categories` t9 ";
		$sql .= "ON t1.`category_id` = t9.`id` ";

		$sql .= "WHERE t9.`status` = 'active' ";
		$sql .= "AND t9.`hidden` = '0' ";
		$sql .= "AND t1.`status` = 'active' ";
		$sql .= !empty($aWhere) ? 'AND ' . $aWhere . ' ' : '';

		$all = $this->one("COUNT(*)");

		// avoiding very expensive ORDER BY RAND()
		if ($all > 1000)
		{
			$ids = array();
			$max_id = $this->one("MAX(`id`)");

			for($i = 1; $i <= $num; $i++)
			{
				$ids[] = mt_rand(0, $max_id);
			}

			$sql .= "AND t1.`id` IN('" . implode("','", $ids) . "') ";
		}
		else
		{
			$sql .= "ORDER BY RAND() ";
		}

		$sql .= "LIMIT " . $num;

		return $this->getAll($sql);
	}

	public function getByCriteria($aStart = 0, $aLimit = 0, $aCause = '', $calcFoundRows = false, $aAccount = false, $aOrder = '')
	{
		$sql = "SELECT " . ($calcFoundRows ? 'SQL_CALC_FOUND_ROWS' : '') . " `listings`.*, ";
		$sql .= "`categories`.`path`, `categories`.`title` `category_title`, `categories`.`path` `category_path`, ";
		$sql .= "`locations`.`title` `location_title`, `locations`.`path` `location_path`, ";
		$sql .= "(SELECT `username` `account_username` FROM `{$this->mPrefix}accounts` `accounts` WHERE `listings`.`account_id` = `accounts`.`id`) `account_username`, ";
		$sql .= "IF (`listings`.`sponsored` = '1', '3', IF (`listings`.`featured` = '1', '2', IF (`listings`.`partner` = '1', '1', '0'))) `listing_type`, ";
		if ($this->mConfig['listing_marked_as_new'])
		{
			$sql .= "IF ((`listings`.`date` + INTERVAL {$this->mConfig['listing_marked_as_new']} DAY < NOW()), '0', '1') `interval`, ";
		}
		if ($aAccount)
		{
			$sql .= "IF (`fav_accounts_set` LIKE '%{$aAccount},%', '1', '0') `favorite`, ";
			$sql .= "IF ((`listings`.`account_id` = 0) OR (`listings`.`account_id` = '0'), '0', '1') `account_id_edit` ";

			$account_sql = "AND (`listings`.`status` = 'active' OR `listings`.`account_id` = {$aAccount}) ";
		}
		else
		{
			$sql .= "'0' `favorite`, '0' `account_id_edit` ";

			$account_sql = "AND `listings`.`status` = 'active' ";
		}
		$sql .= "FROM `" . $this->mTable . "` `listings` ";

		$sql .= "LEFT JOIN `" . $this->mPrefix . "categories` `categories` ";
		$sql .= "ON `categories`.`id` = `listings`.`category_id` ";

		$sql .= "LEFT JOIN `" . $this->mPrefix . "locations_listings` `locations_listings` ";
		$sql .= "ON `locations_listings`.`listing_id` = `listings`.`id` ";
		$sql .= "LEFT JOIN `" . $this->mPrefix . "locations` `locations` ";
		$sql .= "ON `locations`.`id` = `locations_listings`.`location_id` ";

		$sql .= "WHERE `categories`.`status` = 'active' " . $account_sql;
		$sql .= "AND `categories`.`hidden` = '0' ";
		$sql .= $aCause ? 'AND ' . $aCause : '';
		$sql .= " GROUP BY `listings`.`id` ";
		$sql .= "ORDER BY ";
		$sql .= $aOrder ? $aOrder : "`listing_type` DESC, `listings`.`date` DESC ";
		$sql .= $aLimit ? " LIMIT {$aStart}, {$aLimit}" : '';

		return $this->getAll($sql);
	}

	public function getAdvSearchListings($select, $cause, $sortBy = 'search_score', $aStart = 0, $aLimit = 0)
	{
		$cause .= "AND `t44`.`status` = 'active' ";

		$a = "SQL_CALC_FOUND_ROWS " . $select;

		$sql = "SELECT " . $a . " `t1`.*, ";
		$sql .= "`t44`.`path` `path`, t44.`path` `category_path`, t44.`title` `category_title`, ";

		if ($this->mConfig['listing_marked_as_new'] > 0)
		{
			$sql .= "IF((`t1`.`date` + INTERVAL {$this->mConfig['listing_marked_as_new']} DAY < NOW()), '0', '1') `interval`, ";
		}

		$sql .= "IF((`t1`.`sponsored` = '1'), '3', IF((`t1`.`featured` = '1'), '2', IF((`t1`.`partner` = '1'), '1', '0'))) `listing_type`, ";
		$sql .= "(SELECT `username` `account_username` FROM `{$this->mPrefix}accounts` `accounts` WHERE `t1`.`account_id` = `accounts`.`id`) `account_username` ";
		$sql .= "FROM `" . $this->mTable . "` `t1` ";
		$sql .= "LEFT JOIN `" . $this->mPrefix . "categories` `t44` ";
		$sql .= "ON `t44`.`id` = `t1`.`category_id` ";
		$sql .= "WHERE ";
		if (!empty($cause))
		{
			$sql .= $cause . " AND ";
		}
		$sql .= "`t1`.`status` = 'active' AND `t44`.`status` = 'active' ";
		$sql .= "AND t44.`hidden` = '0' ";
		$sql .= " GROUP BY `t1`.`id` ";
		$sql .= "ORDER BY ";
		$sql .= '`listing_type` DESC, ';
		$sql .= $sortBy;
		$sql .= " DESC ";
		$sql .= $aLimit ? "LIMIT ".$aStart.", ".$aLimit."" : '';

		return $this->getAll($sql);
	}

	/**
	 * getNumSearchListings
	 *
	 * Returns number of listings
	 *
	 * @param string $aWhat what to search for
	 * @param int $aType type of search
	 * @access public
	 * @return int
	 */
	public function getNumSearchListings($aWhat = '', $aType = 1)
	{
		$sql = "SELECT COUNT(t1.`id`) FROM `" . $this->mTable . "` t1 ";
		$sql .= "LEFT JOIN `" . $this->mPrefix . "categories` t3 ";
		$sql .= "ON t1.`category_id` = t3.`id` ";
		$sql .= $this->getSearchCriterias($aWhat, $aType);
		$sql .= "AND t3.`status` = 'active' ";
		$sql .= "AND t3.`hidden` = '0' ";

		return $this->getOne($sql);
	}

	public function getByStatus($state, $aCategory=false, $aStart=0, $aLimit=0)
	{
		$where = '';
		$join = '';

		if ('site-wide' != $this->mConfig['sort_listings_in_boxes'] && $aCategory)
		{
			$join = "LEFT JOIN `" . $this->mPrefix . "flat_structure` `t33` ".
					"ON `t33`.`category_id` = `t3`.`id` ";
			$where = "AND `t33`.`parent_id` = '$aCategory' ";
		}
		if ('current category incl. subcategories' != $this->mConfig['sort_listings_in_boxes'] && $aCategory)
		{
			$where .= "AND t1.`category_id` <> '{$aCategory}' ";
		}

		$sql = "SELECT t1.*, t1.`title` `title`, `t3`.`path` `path` ";

		if ($this->mConfig['listing_marked_as_new'] > 0)
		{
			$sql .= ", IF((`t1`.`date` + INTERVAL {$this->mConfig['listing_marked_as_new']} DAY < NOW()), '0', '1') `interval` ";
		}

		$sql .= "FROM `" . $this->mTable . "` t1 ";
		$sql .= "LEFT JOIN `" . $this->mPrefix . "categories` t3 ";
		$sql .= "ON t1.`category_id` = t3.`id` ";
		$sql .= $join;
		$sql .= "WHERE t1.`status` = 'active' ";
		$sql .= "AND `t1`.`".$state."` = '1' ";
		$sql .= "AND t3.`status` = 'active' ";
		$sql .= "AND t3.`hidden` = '0' ";
		$sql .= $where;
		$sql .= "GROUP BY t1.`id` ";
		$sql .= "ORDER BY RAND() ";
		$sql .= $aLimit ? "LIMIT {$aStart}, {$aLimit}" : '';

		return $this->getAll($sql);
	}

	public function getPartner($aCategory = 0, $aStart =0, $aLimit = 0)
	{
		return $this->getByStatus("partner",  $aCategory, $aStart, $aLimit);
	}

	public function getFeatured($aCategory = 0, $aStart =0, $aLimit = 0)
	{
		return $this->getByStatus("featured",  $aCategory, $aStart, $aLimit);
	}

	public function getSponsored($aCategory = 0, $aStart =0, $aLimit = 0)
	{
		return $this->getByStatus("sponsored", $aCategory, $aStart, $aLimit);
	}

	public function getThumbnail($aPage = '', $aCategory = NULL, $aPlan = NULL)
	{
		$sql = "SELECT * ";
		$sql .= "FROM `{$this->mPrefix}listing_fields` `listing_fields` ";
		$sql .= "LEFT JOIN `{$this->mPrefix}field_categories` `field_categories` ";
		$sql .= "ON `field_categories`.`field_id` = `listing_fields`.`id` ";

		if (null != $aPlan)
		{
			$sql .= "LEFT JOIN `{$this->mPrefix}field_plans` `field_plans` ";
			$sql .= "ON `field_plans`.`field_id` = `listing_fields`.`id` ";
		}

		$sql .= "WHERE ";
		$sql .= $aPage ? "FIND_IN_SET('{$aPage}', `pages`) > 0 AND " : '';
		$sql .= "`type`='image' AND `instead_thumbnail`=1 ";
		$sql .= "AND (`field_categories`.`category_id` = '{$aCategory['id']}' ";

		if (null != $aCategory && '-1' != $aCategory['parent_id'])
		{
			$this->setTable("flat_structure");
			$parents = parent::onefield('`parent_id`', "`category_id` = '{$aCategory['id']}' AND `parent_id` <> '{$aCategory['id']}'");
			$this->resetTable();
			$sql.= " OR ( `listing_fields`.`recursive` = '1' AND `field_categories`.`category_id` IN ('".implode("','", $parents)."'))";
		}
		$sql .= ") ";

		if (null != $aPlan)
		{
			$sql .= "AND `field_plans`.`plan_id` = '{$aPlan['id']}' ";
		}

		$sql .= "GROUP BY `listing_fields`.`id` ";
		$sql .= "ORDER BY `order` ASC LIMIT 1";

		$instead_thumbnail = $this->getRow($sql);

		return $instead_thumbnail;
	}

	public function getFieldsByPage($aPage = '', $aCategory = NULL, $aPlan = NULL)
	{
		$sql = "SELECT * ";
		$sql .= "FROM `{$this->mPrefix}listing_fields` `listing_fields` ";
		$sql .= "LEFT JOIN `{$this->mPrefix}field_categories` `field_categories` ";
		$sql .= "ON `field_categories`.`field_id` = `listing_fields`.`id` ";

		if (null != $aPlan)
		{
			$sql .= "LEFT JOIN `{$this->mPrefix}field_plans` `field_plans` ";
			$sql .= "ON `field_plans`.`field_id` = `listing_fields`.`id` ";
		}

		$sql .= "WHERE ";
		$sql .= $aPage ? "FIND_IN_SET('{$aPage}', `pages`) > 0 AND " : '';
		$sql .= "`adminonly` = '0' ";

		if ('view' == $aPage)
		{
			$sql .= "AND `name` NOT IN ('url', 'title') ";
		}

		// exclude instead thumbnail fields
		$sql .= ('view' == $aPage) ? "AND `instead_thumbnail` = '0' " : '';
		$sql .= "AND (`field_categories`.`category_id` = '{$aCategory['id']}' ";

		if (null != $aCategory && '-1' != $aCategory['parent_id'])
		{
			$this->setTable("flat_structure");
			$parents = parent::onefield('`parent_id`', "`category_id` = '{$aCategory['id']}' AND `parent_id` <> '{$aCategory['id']}'");
			$this->resetTable();
			$sql.= " OR ( `listing_fields`.`recursive` = '1' AND `field_categories`.`category_id` IN ('".implode("','", $parents)."'))";
		}
		$sql .= ") ";

		if (null != $aPlan)
		{
			$sql .= "AND `field_plans`.`plan_id` = '{$aPlan['id']}' ";
		}

		$sql .= "GROUP BY `listing_fields`.`id` ";

		if ('view' == $aPage)
		{
			$sql .= "ORDER BY `order_v` ASC ";
		}
		else
		{
			$sql .= "ORDER BY `order` ASC ";
		}

		return $this->getAll($sql);
	}

	public function getSearchCriterias($aWhat, $aType)
	{
		if (is_array($aWhat))
		{
			$what = $aWhat['what'];
			$category = (int)$aWhat['category'];
			$location = (int)$aWhat['location'];
		}
		else
		{
			$what = $aWhat;
		}

		$sql = '';
		$words = preg_split('/[\s]+/u', $what);
		$tmp = array();
		if (1 == $aType || 2 == $aType)
		{
			foreach ($words as $word)
			{
				$tmp[] = "(CONCAT(`listings`.`url`,' ',`listings`.`title`,' ',`listings`.`description`) LIKE '%{$word}%') ";
			}
			$sql .= 1 == $aType ? '(' . implode(" OR ", $tmp).')' : (2 == $aType ? implode(" AND ", $tmp) : '');
		}
		else if (3 == $aType)
		{
			$sql .= "(CONCAT(`listings`.`url`,' ',`listings`.`title`,' ',`listings`.`description`) LIKE '%{$what}%') ";
		}
		$sql .= " AND `listings`.`status` = 'active' AND `categories`.`status` = 'active' ";
		$sql .= "AND `categories`.`hidden` = '0' ";

		// add search by category
		if (isset($category) && !empty($category))
		{
			$this->setTable('flat_structure');
			$children = $this->onefield('category_id', '`parent_id` = ' . $category);
			$this->resetTable();

			$sql .= 'AND listings.`category_id` IN (' . implode(',', $children) . ') ';
		}

		// add search by location
		if (isset($location) && !empty($location))
		{
			$this->setTable('locations');
			$all_locations = $this->one('child', '`id` = :id', array('id' => $location));
			$this->resetTable();

			if($all_locations)
			{
				$sql .= 'AND locations_listings.`location_id` IN (' . $all_locations . ') ';
			}
		}

		if (isset($aWhat['plain']) && $aWhat['plain'])
		{
			$sql .= $aWhat['plain'];
		}

		return $sql;
	}

	public function getListingsByAccountId($aAccount, $aStatus = '', $aStart = 0, $aLimit = 0)
	{
		global $esynAccountInfo;

		$cause = "`listings`.`account_id` = '{$aAccount}' ";
		if (!isset($esynAccountInfo) || $esynAccountInfo['id'] != $aAccount)
		{
			$cause .= "AND `listings`.`status` = 'active'";
		}
		else
		{
			$cause .= !empty($aStatus) ? "AND `listings`.`status` = '{$aStatus}'" : '';
		}

		return $this->getByCriteria($aStart, $aLimit, $cause, true, $aAccount);
	}

	public function getNumListingsByAccountId($aAccount, $aStatus = '')
	{
		$sql = "SELECT COUNT(*) FROM `{$this->mPrefix}listings` ";
		$sql .= "WHERE `account_id` = '{$aAccount}'";
		$sql .= (!empty($aStatus)) ? " AND `status` = '{$aStatus}'" : "";

		return $this->getOne($sql);
	}

	public function checkClick($aId, $aIp)
	{
		$sql = "SELECT `id` FROM `" . $this->mPrefix . "listing_clicks` ";
		$sql .= "WHERE `ip` = '{$aIp}' ";
		$sql .= "AND `listing_id` = '{$aId}' ";
		$sql .= "AND (TO_DAYS(NOW()) - TO_DAYS(`date`)) <= 1 ";

		return $this->getOne($sql);
	}

	public function click($aListing, $aIp)
	{
		parent::setTable("listing_clicks");
		parent::insert(array('listing_id' => $aListing, 'ip' => $aIp), array("date" => "NOW()"));
		parent::resetTable();

		parent::update(array(), "id = :id", array('id' => $aListing), array("clicks" => "clicks+1"));

		return true;
	}

	public function getListingsByCategory ($aCategory = 0, $aStart = 0, $aLimit = 0, $aAccount = '', $sqlFoundRows = false, $sqlCountRows = false, $aFields = array(), $aWhere = '', $aJoin = array())
	{
		// t1 listings
		// t2 listing_categories
		// t10 flat_structure
		// t11 categories
		$a = $sqlFoundRows ? "SQL_CALC_FOUND_ROWS " : '';
		$c = $sqlCountRows ? 'COUNT(*) `num`,' : '';

		$sql = "SELECT {$a} DISTINCTROW {$c} ";
		$sql .= !empty($aFields) && is_array($aFields) ? implode(",", $aFields) . ',' : '';
		$sql .= '`t1`.`id`, `t1`.*, ';

		if ($this->mConfig['listing_marked_as_new'] > 0)
		{
			$sql .= "IF((`t1`.`date` + INTERVAL {$this->mConfig['listing_marked_as_new']} DAY < NOW()), '0', '1') `interval`, ";
		}

		$sql .= "IF((`t1`.`sponsored` = '1'), '3', ";
		$sql .= "IF((`t1`.`featured` = '1'), '2', ";
		$sql .= "IF((`t1`.`partner` = '1'), '1', '0'))) `listing_type`, ";
		$sql .= "(SELECT `username` `account_username` FROM `{$this->mPrefix}accounts` `accounts` WHERE `t1`.`account_id` = `accounts`.`id`) `account_username`, ";
		$sql .= (0 == $aCategory || !$this->mConfig['show_children_listings']) ? '' : '`t11`.`path` `path`, `t11`.`title` `category_title`, ';
		$sql .= $aAccount ? "IF((`t1`.`account_id` = '0'), '0', '1') `account_id_edit`" : "'0' `account_id_edit` ";
		$sql .= ", IF((`t2`.`category_id` = '{$aCategory}'), '1', '0') `crossed`, ";
		$sql .= ($aAccount) ? "IF (`fav_accounts_set` LIKE '%{$aAccount},%', '1', '0') `favorite` " : "'0' `favorite` ";

		if (0 == $aCategory || !$this->mConfig['show_children_listings'])
		{
			$sql .= "FROM `{$this->mPrefix}listings` `t1` ";
		}
		else
		{
			$sql .= "FROM `{$this->mPrefix}flat_structure` `t10` ";
			$sql .= "LEFT JOIN `{$this->mPrefix}listings` `t1` ";
			$sql .= "ON `t10`.`category_id` = `t1`.`category_id` ";
			$sql .= "LEFT JOIN `{$this->mPrefix}categories` `t11` ";
			$sql .= "ON `t11`.`id` = `t1`.`category_id` ";
		}

		if (is_array($aJoin) && !empty($aJoin))
		{
			foreach ($aJoin as $table => $on)
			{
				$sql .= "LEFT JOIN `{$this->mPrefix}{$table}` `{$table}` ON {$on}";
			}
		}

		$sql .= "LEFT JOIN `{$this->mPrefix}listing_categories` `t2` ";
		$sql .= "ON `t1`.`id` = `t2`.`listing_id` ";
		$sql .= "WHERE (`t2`.`category_id` = '{$aCategory}' ";
		$sql .= (0 == $aCategory || !$this->mConfig['show_children_listings']) ? ' ' : "OR `t10`.`parent_id` = '{$aCategory}' ";
		$sql .= "OR `t1`.`category_id` = '{$aCategory}') ";
		$sql .= $aAccount ? "AND (`t1`.`status` = 'active' OR `t1`.`account_id` = '{$aAccount}') " : " AND `t1`.`status` = 'active' ";
		$sql .= !empty($aWhere) ? "AND {$aWhere} " : '';
		$sql .= 'ORDER BY ';

		// Bidding plugin sorting
		if ('bid_amount' != $this->mConfig['listings_sorting'])
		{
			$sql .= '`listing_type` DESC, ';
		}

		// default order
		$order = 'alphabetic' == $this->mConfig['listings_sorting'] ? 'title' : $this->mConfig['listings_sorting'];
		$order_type = 'ascending' == $this->mConfig['listings_sorting_type'] ? 'ASC' : 'DESC';
		$sql .= '`t1`.`' . $order . '` ' . $order_type . ' ';
		$sql .= $aLimit ? 'LIMIT ' . $aStart . ', ' . $aLimit : '';

		return $this->getAll($sql);
	}

	public function getNumListingsByCategory($aCategory = 0, $aAccount = '')
	{
		$row = $this->getListingsByCategory($aCategory, 0, false, $aAccount, false, true);

		return $row['num'];
	}

	public function getFieldsForSearch()
	{
		$sql = "SELECT * ";
		$sql .= "FROM `" . $this->mPrefix . "listing_fields` ";
		$sql .= "WHERE ";
		$sql .= "`adminonly` = '0' AND searchable <> '0'";
		$sql .= "ORDER BY `order` ASC ";

		$temp = array();

		$fields = $this->getAll($sql);

		if (!empty($fields))
		{
			foreach($fields as $f)
			{
				$temp[$f['name']] = $f;
			}
		}

		return $temp;
	}

	public function setPlan($listingId, $planId, $tid='')
	{
		return parent::update(array("sponsored_tid"=>$tid, "sponsored"=>"1", "sponsored_plan_id"=>$planId, "status" => 'active'), "`id`='".$listingId."'", array("sponsored_start"=>"NOW()"));
	}

	public function processFields($aFields, $aOldListing = false)
	{
		global $esynI18N, $eSyndiCat, $cid;

		$listing = $msg = array();
		$error = false;
		$aFields = is_array($aFields) ? $aFields : array();

		$imgtypes = array(
			"image/gif" => "gif",
			"image/jpeg" => "jpg",
			"image/pjpeg" => "jpg",
			"image/png" => "png"
		);

		foreach($aFields as $value)
		{
			$field_name = $value['name'];
			$field_value = isset($_POST[$field_name]) ? $_POST[$field_name] : '';

			// Check the UTF-8 is well formed
			if (is_string($field_value))
			{
				$field_value = trim($field_value);

				if ( !utf8_is_valid($field_value))
				{
					// Strip out bad sequences - replace with ? character
					$field_value = utf8_bad_replace($field_value);
					trigger_error("Bad UTF-8 detected (replacing with '?')", E_USER_NOTICE);
				}
			}

			switch ($value['type'])
			{
				case 'text':

					$value_length = utf8_strlen($field_value);

					if ($value['required'] && empty($field_value) && $field_name != 'url' && $field_name != 'reciprocal')
					{
						if ('email' == $value['name'])
						{
							/** check emails **/
							if (!esynValidator::isEmail(stripslashes($field_value)))
							{
								$error = true;
								$msg[] = $esynI18N['error_email_incorrect'];
							}
						}
						else
						{
							$error = true;
							$msg[] = str_replace('{field}', $esynI18N['field_' . $field_name], $esynI18N['field_is_empty']);
						}
					}
					elseif ($field_name == 'url')
					{
						if (0 !== strpos($field_value, 'http://'))
						{
							$field_value  = 'http://' . $field_value;
						}

						if ($value['required'] && !esynValidator::isUrl($field_value))
						{
							$error = true;
							$msg[] = $esynI18N['error_url'];
						}
						elseif (!$value['required'] && (empty($_POST['url']) || ('http://' == $_POST['url'])))
						{
							$listing['domain'] = '';
							$listing['listing_header'] = '200';
						}
					}
					elseif ($value['required'] && $field_name == 'reciprocal' && !esynValidator::isUrl($field_value))
					{
						$error = true;
						$msg[] = $esynI18N['error_reciprocal_listing'];
					}
					elseif ($value['required'] && $value_length > $value['length'])
					{
						$error = true;

						$tmp = str_replace('{field}', $esynI18N['field_' . $field_name], $esynI18N['error_max_textarea']);
						$tmp = str_replace("{num}", $value['length'], $tmp);

						$msg[] = $tmp;
					}

					$listing[$field_name] = $field_value ? htmlspecialchars($field_value) : '';
					break;

				case 'textarea':

					list($minLength, $maxLength) = explode(',', $value['length']);

					if ($value['required'])
					{
						if (!empty($field_value))
						{
							$fieldlen = utf8_strlen($field_value);

							// check for minimum chars
							if ('' != $minLength && $fieldlen < $minLength)
							{
								$error = true;

								$tmp = str_replace('{field}', $esynI18N['field_'.$field_name], $esynI18N['error_min_textarea']);
								$tmp = str_replace("{num}", $minLength, $tmp);

								$msg[] = $tmp;
							}

							// check for max chars
							if ('' != $maxLength && $fieldlen > $maxLength)
							{
								$error = true;

								$tmp = str_replace('{field}', $esynI18N['field_' . $field_name], $esynI18N['error_max_textarea']);
								$tmp = str_replace("{num}", $maxLength, $tmp);

								$msg[] = $tmp;
							}
						}
						else
						{
							$error = true;
							$msg[] = str_replace('{field}', $esynI18N['field_'.$field_name], $esynI18N['field_is_empty']);
						}
					}

					if (1 == $value['editor'])
					{
						require_once(IA_INCLUDES . 'safehtml/safehtml.php');
						$safehtml = new safehtml();

						$listing[$field_name] = $safehtml->parse($field_value);
					}
					else
					{
						$listing[$field_name] = $field_value ? substr(htmlspecialchars($field_value), 0, $maxLength) : '';
					}

					break;

				case 'combo':
				case 'radio':
					$listing[$field_name] = $field_value;
					break;

				case 'checkbox':
					if (empty($field_value) && $value['required'])
					{
						$error = true;
						$msg[] = str_replace('{field}', $esynI18N['field_'.$field_name], $esynI18N['field_is_empty']);
					}
					else
					{
						if (is_array($field_value))
						{
							$field_value = join(",", $field_value);
							$field_value = trim($field_value, ',');
						}
					}

					$listing[$field_name] = $field_value;
					break;

				case 'storage':
					if ($value['required'] && $_FILES[$field_name]['error'])
					{
						$error = true;
						$msg[] = str_replace('{field}', $esynI18N['field_'.$field_name], $esynI18N['field_is_empty']);
					}
					elseif (!$_FILES[$field_name]['error'])
					{
						$ext	= utf8_substr($_FILES[$field_name]['name'], -3);
						$token	= esynUtil::getNewToken();
						$file_name = $cid."-".$token.".".$ext;
						if (!is_writable(IA_HOME.'uploads'.IA_DS))
						{
							$error = true;
							$msg[] = $esynI18N['error_directory_readonly'];
						}
						else
						{
							if (esynUtil::upload($field_name, IA_UPLOADS . $file_name))
							{
								$listing[$field_name] = $file_name;
							}
							else
							{
								$error = true;
								$msg[] = $esynI18N['error_file_upload'];
							}
						}
					}
					break;

				case 'pictures':
					$picture_names = array();

					if (!empty($aOldListing[$field_name]))
					{
						$picture_names = explode(',', $aOldListing[$field_name]);
					}

					$eSyndiCat->loadClass("Image");
					$image = new esynImage();

					if (isset($_FILES[$field_name]['tmp_name']) && !empty($_FILES[$field_name]['tmp_name']))
					{
						foreach($_FILES[$field_name]['tmp_name'] as $key => $tmp_name)
						{
							if ((bool)$value['required'] && (bool)$_FILES[$field_name]['error'][$key])
							{
								$error = true;
								$msg[] = str_replace('{field}', $esynI18N['field_'.$field_name], $esynI18N['field_is_empty']);
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
										$eSyndiCat->loadClass("Image");

										$token = esynUtil::getNewToken();

										$file_name = $value['file_prefix'].$cid."-".$token.".".$ext;
										$file_folder = IA_HOME . 'uploads' . IA_DS;

										$picture_names[] = $file_name;

										$file = array();

										foreach ($_FILES[$field_name] as $key1 => $tmp_name)
										{
											$file[$key1] = $_FILES[$field_name][$key1][$key];
										}

										$image->processImage($file, $file_folder, $file_name, $value);
									}
								}
							}
						}

						if (!empty($picture_names))
						{
							$listing[$field_name] = implode(',', $picture_names);
							$listing[$field_name . '_titles'] = implode(',', $_POST[$field_name . '_titles']);
						}
					}
					break;

				case 'image':
					if ((bool)$value['required'] && (bool)$_FILES[$field_name]['error'])
					{
						$error = true;
						$msg[] = str_replace('{field}', $esynI18N['field_'.$field_name], $esynI18N['field_is_empty']);
					}
					else
					{
						if (is_uploaded_file($_FILES[$field_name]['tmp_name']))
						{
							$ext = strtolower(utf8_substr($_FILES[$field_name]['name'], -3));

							// if jpeg
							if ($ext == 'peg')
							{
								$ext = 'jpg';
							}

							if (!array_key_exists($_FILES[$field_name]['type'], $imgtypes) || !in_array($ext, $imgtypes, true) || !getimagesize($_FILES[$field_name]['tmp_name']))
							{
								$error = true;

								$a = join(",",array_unique($imgtypes));

								$err_msg = str_replace("{types}", $a, $esynI18N['wrong_image_type']);
								$err_msg = str_replace("{name}", $field_name, $err_msg);

								$msg[] = $err_msg;
							}

							if (is_file(IA_UPLOADS . $aOldListing[$field_name]))
							{
								unlink(IA_UPLOADS . $aOldListing[$field_name]);
							}

							if (is_file(IA_UPLOADS . 'small_'.$aOldListing[$field_name]))
							{
								unlink(IA_UPLOADS . 'small_'.$aOldListing[$field_name]);
							}

							$token = esynUtil::getNewToken();

							$file_name = $value['file_prefix'] . $cid . '-' . $token . '.' . $ext;
							$file_folder = IA_HOME . 'uploads' . IA_DS;

							$listing[$field_name] = $file_name;
							$listing[$field_name . '_title'] = $_POST[$field_name . '_title'];

							$eSyndiCat->loadClass("Image");
							$image = new esynImage();
							$image->processImage($_FILES[$field_name], $file_folder, $file_name, $value);
						}
					}
					break;

				default:
					$listing[$field_name] = htmlspecialchars($field_value);
					break;
			}
		}

		return array($listing, $error, $msg);
	}

	public function postPaymentEdit($item, $plan, $invoice, $transaction)
	{
		$listing = array();
		$update = false;
		$email_template = '';

		$listing['transaction_id'] = $transaction['id'];

		// check for default status
		$listing['status'] = ('passed' == $transaction['status']) ? $plan['set_status'] : 'approval';

		if (!empty($transaction['status']))
		{
			if (isset($transaction['subscr']) && $transaction['subscr'])
			{
				if ('payment' == $transaction['subscr_type'])
				{
					$email_template = 'listing_subscr_payment';
				}

				if ('eot' == $transaction['subscr_type'])
				{
					$email_template = 'listing_subscr_eot';
					$listing['status'] = 'approval';
				}
			}
			else
			{
				$email_template = ('failed' == $transaction['status']) ? 'listing_payment_failed' : 'listing_payment';
			}

			$update = true;
		}

		if ($update)
		{
			if ('passed' == $transaction['status'])
			{
				$this->setTable('listings');
				$data = $this->onefield("`changes_temp`", "`id` = '{$invoice['item_id']}'");
				$this->resetTable();

				$data = unserialize($data[0]);
				$data['changes_temp'] = '';

				parent::update($data, "`id` = '{$invoice['item_id']}'");
			}

			parent::update($listing, "`id` = '{$invoice['item_id']}'");
		}

		$replace = array(
			"payment_system" => $invoice['payment_gateway'],
			"listing_plan" => $plan['title'],
			"cost" => $transaction['total'],
			"payment_status" => $transaction['status'],
			"transactionID" => $transaction['order_number'],
			"subscriptionID" => isset($transaction['subscr_id']) ? $transaction['subscr_id'] : '',
		);

		if (!empty($email_template))
		{
			$this->mMailer->add_notif($email_template);
			$this->mMailer->add_replace($replace);
			$this->mMailer->AddAddress($item['email']);

			$this->mMailer->Send($email_template, $item['account_id'], $item);
		}
	}

	public function postPayment($item, $plan, $invoice, $transaction)
	{
		$listing = array();
		$update = false;
		$email_template = '';

		$listing['transaction_id'] = $transaction['id'];

		// check for default status
		$listing['status'] = ('passed' == $transaction['status']) ? $plan['set_status'] : 'approval';

		if (!empty($transaction['status']))
		{
			if (isset($transaction['subscr']) && $transaction['subscr'])
			{
				if ('payment' == $transaction['subscr_type'])
				{
					$email_template = 'listing_subscr_payment';
				}

				if ('eot' == $transaction['subscr_type'])
				{
					$email_template = 'listing_subscr_eot';
					$listing['status'] = 'approval';
				}
			}
			else
			{
				$email_template = 'listing_payment';
			}

			$update = true;
		}

		if ($update)
		{
			parent::update($listing, "`id` = '{$invoice['item_id']}'");
		}

		$replace = array(
			"payment_system" => $invoice['payment_gateway'],
			"listing_plan" => $plan['title'],
			"cost" => $transaction['total'],
			"transactionID" => $transaction['order_number'],
			"subscriptionID" => isset($transaction['subscr_id']) ? $transaction['subscr_id'] : '',
		);

		if (!empty($email_template))
		{
			$this->mMailer->add_notif ($email_template);
			$this->mMailer->add_replace($replace);
			$this->mMailer->Send($email_template, $item['account_id'], $item);
		}
	}

	public function postPaymentUpgrade($item, $plan, $invoice, $transaction)
	{
		$listing = array();
		$addit = array();

		$listing['transaction_id'] = $transaction['id'];
		$listing['status'] = ('passed' == $transaction['status']) ? 'active' : 'approval';
		$listing['plan_id'] = $plan['id'];

		if ($plan['period'] > 0)
		{
			$listing['expire_notif'] = $plan['expire_notif'];
			$listing['expire_action'] = $plan['expire_action'];

			$addit['expire_date'] = "`expire_date` + INTERVAL {$plan['period']} DAY";
			$addit['last_modified'] = 'NOW()';
		}

		parent::update($listing, "`id` = '{$invoice['item_id']}'", array(), $addit);

		// get updated listing
		$sql = "SELECT `l`.*, `c`.`path` "
			 . "FROM `{$this->mPrefix}listings` `l` "
			 . "LEFT JOIN `{$this->mPrefix}categories` `c` "
			 . "ON `l`.`category_id` = `c`.`id` "
			 . "WHERE `l`.`id` = '{$item['id']}'";

		$listing = $this->getRow($sql);

		$replace = array(
			"expire_date" => strftime($this->mConfig['date_format'], strtotime($listing['expire_date'])),
			"payment_system" => $invoice['payment_gateway'],
			"listing_plan" => $plan['title'],
			"cost" => $transaction['total'],
			"transactionID" => $transaction['order_number'],
			"subscriptionID" => isset($transaction['subscr_id']) ? $transaction['subscr_id'] : '',
		);

		if (!empty($listing['email']))
		{
			$this->mMailer->add_notif ('listing_upgraded');
			$this->mMailer->add_replace($replace);
			$this->mMailer->AddAddress($listing['email']);

			$this->mMailer->Send('listing_upgraded', $listing['account_id'], $listing);
		}
	}
}
