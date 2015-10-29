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

/**
 * esynListing
 *
 * @uses esynAdmin
 * @package
 * @version $id$
 */
class esynListing extends eSyndiCat
{

	/**
	 * mTable
	 *
	 * @var string
	 * @access public
	 */
	var $mTable = 'listings';

	/**
	 * field
	 *
	 * @var mixed
	 * @access public
	 */
	var $field	= null;

	/**
	 * fields
	 *
	 * @var array
	 * @access public
	 */
	var $fields = array();

	/**
	 * fieldLoaded
	 *
	 * flag
	 *
	 * @var mixed
	 * @access public
	 */
	var $fieldLoaded = false;

	/**
	 * loadFieldClass
	 *
	 * Proxy pattern
	 *
	 * @access public
	 * @return void
	 */
	public function loadFieldClass()
	{
		$this->loadClass("ListingField", 'esyn', true);

		$this->field = new esynListingField;
	}

	/**
	 * &field
	 *
	 * @access public
	 * @return void
	 */
	public function &field()
	{
		if (!$this->fieldLoaded)
		{
			$this->loadFieldClass();
		}

		return $this->field;
	}

	/**
	 * insert
	 *
	 * Adds new listing to database and send email
	 *
	 * @param array $aListing link information
	 * @param array $addit additional information
	 * @access public
	 * @return int the id of the newly inserted listing or 0 otherwise
	 */
	public function insert($aListing, $addit = array())
	{
		$retval = 0;
		$notify = !empty($aListing['_notify']);
		// not to generate (_notify='value') in $sql
		unset($aListing['_notify']);

		// Generate and execute the query for adding the link.
		$sql = "INSERT INTO `".$this->mTable."` (";
		foreach($aListing as $key => $value)
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

		foreach($aListing as $key => $value)
		{
			$sql .= !in_array($key, array('multi_crossed', '_deep_links'), true) ? " '".esynSanitize::sql($value)."', " : '';
		}

		if ($addit)
		{
			foreach($addit as $value)
			{
				$value = esynSanitize::sql($value);

				$sql .= " {$value}, ";
			}
		}

		$sql .= "NOW())";
		$this->query($sql);

		// Generate and execute the query for adding the
		// link <-> category connection.
		$aListing['id'] = $this->getInsertId();

		// MCross ----
		if (!empty($aListing['multi_crossed']) && is_array($aListing['multi_crossed']))
		{
			foreach ($aListing['multi_crossed'] as $one_cross)
			{
				$sql = "INSERT INTO `".$this->mPrefix."listing_categories` ";
				$sql .= "(`listing_id`, `category_id`) ";
				$sql .= "VALUES ('".$aListing['id']."', '".$one_cross."')";

				$this->query($sql);
			}
		}
		// ---- MCross

		// Deep links
		if (isset($aListing['_deep_links']) && !empty($aListing['_deep_links']))
		{
			$this->setTable("deep_links");
			$data = array();
			foreach($aListing['_deep_links'] as $key => $deep_link)
			{
				$deep_link['listing_id'] = $aListing['id'];

				$data[] = $deep_link;
			}
			parent::insert($data);
			unset($data);
			$this->resetTable();
		}
		// end Deep links

		$this->setTable('categories');
		$category = $this->row("*","`id`='".$aListing['category_id']."'");
		$this->resetTable();

		if ($aListing['status'] == 'active')
		{
//			$this->increaseNumListings($aListing['category_id']);
		}

		if ($aListing['email'] && $notify)
		{
			$this->mMailer->AddAddress($aListing['email']);
			$this->mMailer->Send("listing_admin_submit", $aListing['account_id'], $aListing);

		}

		return $aListing['id'];
	}

	/**
	 * getListingsByUrl
	 *
	 * Returns listings by url
	 *
	 * @param string $aUrl link url
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @access public
	 * @return arr
	 */
	public function getListingsByUrl($aUrl, $aStart = 0, $aLimit = 0)
	{
		$cause = "WHERE t1.`url` = '{$aUrl}' ";

		return $this->getByCriteria($aStart, $aLimit, $cause);
	}

	/**
	 * getSearch
	 *
	 * Returns listings by search
	 *
	 * @param mixed $aWhat
	 * @param int $aType
	 * @param int $aStart
	 * @param int $aLimit
	 * @access public
	 * @return arr
	 */
	public function getSearch($aWhat, $aType = 1, $aStart = 0, $aLimit = 0)
	{
		$cause = $this->getSearchCriterias($aWhat, $aType);

		return $this->getByCriteria($aStart, $aLimit, $cause, true);
	}

	/**
	 * getByStatus
	 *
	 * @param mixed $state
	 * @param mixed $aCategory
	 * @param int $aStart
	 * @param int $aLimit
	 * @param mixed $aCalc
	 * @access public
	 * @return void
	 */
	public function getByStatus($state,$aCategory, $aStart=0, $aLimit=0, $aCalc = false)
	{
		$aCalc = $aCalc ? "SQL_CALC_FOUND_ROWS" : '';
		$sql = "SELECT $aCalc *, t1.`title` `title` ";
		$sql .= "FROM `".$this->mTable."` t1 ";
		$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t2 ";
		$sql .= "ON t2.`listing_id` = t1.`id` ";
		$sql .= "LEFT JOIN `".$this->mPrefix."categories` t3 ";
		$sql .= "ON t2.`category_id` = t3.`id` ";
		$sql .= "WHERE t1.`status` = 'active' ";
		$sql .= "AND `t1`.`".$state."` = '1' ";
		$sql .= "AND t3.`status` = 'active' ";
		$sql .= $aCategory ? "AND t2.`category_id` <> '".$aCategory."' " : '';

		$sql .= "ORDER BY RAND() ";
		$sql .= $aLimit ? "LIMIT ".$aStart.", ".$aLimit : '';
	}

	/**
	 * getByCriteria
	 *
	 * Returns listings by some value
	 *
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @param string $aCause sql condition on select listings
	 * @param mixed $aCalc
	 * @access public
	 * @return arr
	 */
	public function getByCriteria($aStart = 0, $aLimit = 0, $aCause = '', $aCalc=false)
	{
		$aCalc = $aCalc ? "SQL_CALC_FOUND_ROWS" : '';
		$sql = "SELECT ".$aCalc." t1.*, t44.`id` category_id, ";
		$sql .= "t44.`title` category_title, t44.`path` path ";
		$sql .= "FROM `".$this->mTable."` t1 ";
		$sql .= "LEFT JOIN `".$this->mPrefix."categories` t44 ";
		$sql .= "ON t44.`id` = t1.`category_id` ";
		$sql .= $aCause;

		$order = 'title';
		$order_type = 'ASC';
		$sql .= "ORDER BY t1.`".$order."` ".$order_type." ";

		$sql .= $aLimit ? "LIMIT ".$aStart.", ".$aLimit : '';

		return $this->getAll($sql);
	}

	/**
	 * getSearchCriterias
	 *
	 * Returns cause for a query
	 *
	 * @param string $aWhat string to search for
	 * @param int $aType search type
	 * @access public
	 * @return string
	 */
	public function getSearchCriterias($aWhat, $aType)
	{
		$sql = '';
		$words = preg_split('/[\s]+/', $aWhat);
		$cnt = count($words);
		if (1 == $aType)
		{
			$sql .= "WHERE (";
			$i = 1;
			foreach ($words as $word)
			{
				$sql .= "(CONCAT(`t1`.`url`,' ',`t1`.`title`,' ',`t1`.`description`) LIKE '%{$word}%') ";
				if ($i < $cnt)
				{
					$sql .= " OR ";
				}
				$i++;
			}
			$sql .= ") ";
		}
		else if (2 == $aType)
		{
			$sql .= "WHERE ";
			$i = 1;
			foreach ($words as $word)
			{
				$sql .= "(CONCAT(`t1`.`url`,' ',`t1`.`title`,' ',`t1`.`description`) LIKE '%{$word}%') ";
				if ($i < $cnt)
				{
					$sql .= " AND ";
				}
				$i++;
			}
		}
		else if (3 == $aType)
		{
			$sql .= "WHERE (CONCAT(`t1`.`url`,' ',`t1`.`title`,' ',`t1`.`description`) LIKE '%{$aWhat}%') ";
		}

		return $sql;
	}

	/**
	 * getListingsByPagerank
	 *
	 * Returns listings by its exact pagerank
	 *
	 * @param int $aPr pagerank
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @access public
	 * @return arr
	 */
	public function getListingsByPagerank($aPr, $aStart = 0, $aLimit = 0)
	{
		$cause = "WHERE t1.`pagerank` = '$aPr' ";
		return $this->getByCriteria($aStart, $aLimit, $cause);
	}

	/**
	 * getListingsByDates
	 *
	 * Returns listings by date range
	 *
	 * @param int $from from date (in MySQL datetime format)
	 * @param int $to todate (in MySQL datetime format)
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @access public
	 * @return arr
	 */
	public function getListingsByDates($from, $to, $aStart, $aLimit = 0)
	{
		$cause = "WHERE t1.`date` BETWEEN '{$from}' and '{$to}'";

		return $this->getByCriteria($aStart, $aLimit, $cause);
	}

	/**
	 * getNumListingsByDates
	 *
	 * Returns number of listings by date range
	 *
	 * @param int $from from date (in MySQL datetime format)
	 * @param int $to todate (in MySQL datetime format)
	 * @access public
	 * @return int
	 */
	public function getNumListingsByDates($from, $to)
	{
		$cause = "WHERE t1.`date` BETWEEN '{$from}' and '{$to}'";

		return $this->getNumListingsBy($cause);
	}

	/**
	 * getListingsBy
	 *
	 * Returns listings by some value
	 *
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @param string $aCause sql condition on select listings
	 * @param mixed $aCalc
	 * @access public
	 * @return arr
	 */
	public function getListingsBy($aStart = 0, $aLimit = 0, $aCause = '', $aCalc)
	{
		$aCalc = $aCalc ? "SQL_CALC_FOUND_ROWS" : '';
		$sql = "SELECT $aCalc t1.*, `t1`.`comments_total` `comments`, t41.`id` category_id, ";
		$sql .= "t41.`title` category_title ";
		$sql .= "FROM `".$this->mTable."` t1 ";

		$sql .= "LEFT JOIN `".$this->mPrefix."categories` t41 ";
		$sql .= "ON t1.`category_id` = t41.`id` ";
		$sql .= $aCause;

		$sql .= "ORDER BY `date` DESC ";
		$sql .= $aLimit ? "LIMIT {$aStart}, {$aLimit}" : '';

		$listings &= $this->getAll($sql);

		/** get categories for every link **/
		if (!empty($listings))
		{
			$i = 0;
			$sql = '';
			foreach ($listings as $key => $value)
			{
				if ($i > 0)
				{
					$sql .= 'UNION ALL ';
				}
				$sql .= "(SELECT t2.`listing_id`, t1.`id`, t1.`title` ";
				$sql .= "FROM `".$this->mPrefix."categories` t1 ";
				$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t2 ";
				$sql .= "ON t1.`id` = t2.`category_id` ";
				$sql .= "WHERE `listing_id` = {$value['id']} ";
				$sql .= "ORDER BY t1.`title` ) ";
				$i++;
			}
			if ($sql)
			{
				$categories =& $this->getAssoc($sql);
			}

			/** assign categories to listings **/
			if (!empty($categories))
			{
				foreach ($listings as $key => $value)
				{
					$listings[$key]['categories'] =& $categories[$value['id']];
				}
			}
		}

		return $listings;
	}

	/**
	 * getListingsByCategory
	 *
	 * Returns listings by category
	 *
	 * @param int $aCategory category id
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @access public
	 * @return arr
	 */
	public function getListingsByCategory($aCategory = 0, $aStart = 0, $aLimit = 0)
	{
		$sql = "SELECT t1.*, `t1`.`comments_total` `comments`, ";
		$sql .= "t11.`crossed` crossed ";
		$sql .= "FROM  `".$this->mTable."` t1 ";
		$sql .= "RIGHT JOIN `".$this->mPrefix."listing_categories` t11 ";
		$sql .= "ON t11.`listing_id` = t1.`id` ";
		$sql .= "WHERE t11.`category_id` = '{$aCategory}' ";
		$sql .= "ORDER BY `date` DESC ";
		$sql .= $aLimit ? "LIMIT {$aStart}, {$aLimit}" : '';

		$listings =& $this->getAll($sql);

		/** get categories for every link **/
		if (!empty($listings))
		{
			$i = 0;
			$sql = '';
			foreach ($listings as $key => $value)
			{
				if ($i > 0)
				{
					$sql .= 'UNION ALL ';
				}
				$sql .= "(SELECT t2.`listing_id`, t1.`id`, t1.`title` ";
				$sql .= "FROM `".$this->mPrefix."categories` t1 ";
				$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t2 ";
				$sql .= "ON t1.`id` = t2.`category_id` ";
				$sql .= "WHERE `listing_id` = {$value['id']} ";
				$sql .= "ORDER BY t1.`title`) ";
				$i++;
			}
			if ($sql)
			{
				$categories =& $this->getAssoc($sql);
			}

			/** assign categories to listings **/
			if (!empty($categories))
			{
				foreach ($listings as $key => $value)
				{
					$listings[$key]['categories'] =& $categories[$value['id']];
				}
			}
		}

		return $listings;
	}

	/**
	 * getListingById
	 *
	 * Returns link information
	 *
	 * @param int $aListing link id
	 * @access public
	 * @return arr
	 */
	public function getListingById($aListing)
	{
		$sql = "SELECT t1.*, `t1`.`comments_total` `comments`, ";
		$sql .= "t8.`category_id` category_id ";
		$sql .= "FROM `".$this->mTable."` t1 ";
		$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t8 ";
		$sql .= "ON t1.`id` = t8.`listing_id` ";
		$sql .= "AND t8.`crossed` = '0' ";
		$sql .= "WHERE t1.`id` = '{$aListing}' ";

		$link = $this->getRow($sql);

		//** get categories for every link **
		if (!empty($link))
		{
			$sql = "SELECT t1.`id`, t1.`title` ";
			$sql .= "FROM `".$this->mPrefix."categories` t1 ";
			$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t2 ";
			$sql .= "ON t1.`id` = t2.`category_id` ";
			$sql .= "WHERE t2.`listing_id` = '{$aListing}' ";
			$sql .= "ORDER BY t1.`title` ";

			$categories = $this->getAll($sql);
		}

		$link['categories'] =& $categories;

		return $link;
	}

	/**
	 * getCause
	 *
	 * Returns cause for a query
	 *
	 * @param string $aWhat string to search for
	 * @param int $aType search type
	 * @access public
	 * @return string
	 */
	public function getCause($aWhat, $aType)
	{
		$sql = '';
		$words = preg_split('/\s+/', $aWhat);
		$cnt = count($words);
		if (1 == $aType)
		{
			$sql .= "WHERE ";
			$i = 1;
			foreach ($words as $word)
			{
				$sql .= "(CONCAT(`t1`.`url`,' ',`t1`.`title`,' ',`t1`.`description`) LIKE '%{$word}%') ";
				if ($i < $cnt)
				{
					$sql .= " OR ";
				}
				$i++;
			}
		}
		else if (2 == $aType)
		{
			$sql .= "WHERE ";
			$i = 1;
			foreach ($words as $word)
			{
				$sql .= "(CONCAT(`t1`.`url`,' ',`t1`.`title`,' ',`t1`.`description`) LIKE '%{$word}%') ";
				if ($i < $cnt)
				{
					$sql .= " AND ";
				}
				$i++;
			}
		}
		else if (3 == $aType)
		{
			$sql .= "WHERE (CONCAT(`t1`.`url`,' ',`t1`.`title`,' ',`t1`.`description`) LIKE '%{$aWhat}%') ";
		}

		return $sql;
	}

	/**
	 * getNumListingsBy
	 *
	 * Returns number of listings by some cause
	 *
	 * @param string $aCause sql condition on select count
	 * @access public
	 * @return int
	 */
	public function getNumListingsBy($aCause = ' WHERE')
	{
		$sql = "SELECT COUNT(t1.`id`) ";
		$sql .= "FROM `".$this->mTable."` t1 ";
		$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t2 ";
		$sql .= "ON t1.`id` = t2.`listing_id` ";
		$sql .= $aCause;

		return $this->getOne($sql);
	}

	/**
	 * getNumByStatus
	 *
	 * Returns number of listings by status
	 *
	 * @param string $aStatus listings status
	 * @access public
	 * @return int
	 */
	public function getNumByStatus($aStatus = '')
	{
		$cause = $aStatus ? "WHERE t1.`status` = '".$aStatus."'" : '';

		return $this->getNumListingsBy($cause);
	}

	/**
	 * getNumListingsByPagerank
	 *
	 * Returns number of listings by pagerank
	 *
	 * @param int $aPr pagerank
	 * @access public
	 * @return int
	 */
	public function getNumListingsByPagerank($aPr=0)
	{
		$cause = "WHERE t1.`pagerank` = '{$aPr}'";
		return $this->getNumListingsBy($cause);
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

	/**
	 * getNumListingsByHeader
	 *
	 * Returns number of listings by HTTP header
	 *
	 * @param int $aHeader
	 * @access public
	 * @return int
	 */
	public function getNumListingsByHeader($aHeader = 200)
	{
		if (($aHeader == '') || ($aHeader == 200))
		{
			$cause = "WHERE `listing_header` <> '200' ";
		}
		else
		{
			$cause = "WHERE `listing_header` = '{$aHeader}' ";
		}

		return $this->getNumListingsBy($cause);
	}

	/**
	 * getListingsByHeader
	 *
	 * Returns listings by header
	 *
	 * @param int $aHeader link header
	 * @param int $aStart starting position
	 * @param int $aLimit number of broken listings to be returned
	 * @access public
	 * @return arr
	 */
	public function getListingsByHeader($aHeader = 200, $aStart = 0, $aLimit = 0)
	{
		if (($aHeader == '') || ($aHeader == 200))
		{
			$cause = "WHERE t1.`listing_header` <> '200' ";
		}
		else
		{
			$cause = "WHERE t1.`listing_header` = '{$aHeader}'";
		}

		return $this->getByCriteria($aStart, $aLimit, $cause);
	}

	/**
	 * getNumRecip
	 *
	 * Returns number of listings with correct recipocal listings
	 *
	 * @param string $aStatus link status
	 * @access public
	 * @return int
	 */
	public function getNumRecip($aStatus='')
	{
		$sql = "SELECT COUNT(*) ";
		$sql .= "FROM `".$this->mTable."` ";
		$sql .= "WHERE `recip_valid` = '1' ";
		$sql .= $aStatus ? "AND `status` = '{$aStatus}'" : '';

		return $this->getOne($sql);
	}

	/**
	 * getRecip
	 *
	 * Returns listings that have correct reciprocal listings
	 *
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @param string $aStatus link status
	 * @param mixed $aCalc
	 * @access public
	 * @return arr
	 */
	public function getRecip($aStart = 0, $aLimit = 0, $aStatus='',$aCalc=false)
	{
		$cause = "WHERE t1.`recip_valid` = '1' ";
		$cause .= $aStatus ? " AND t1.`status`='".$aStatus."'" : '';

		return $this->getByCriteria($aStart, $aLimit, $cause, $aCalc);
	}

	/**
	 * getNumNorecip
	 *
	 * Returns number of listings with invalid recipocal listings
	 *
	 * @param string $aStatus link status
	 * @access public
	 * @return int
	 */
	public function getNumNorecip($aStatus='')
	{
		$sql = "SELECT COUNT(*) ";
		$sql .= "FROM `".$this->mTable."` ";
		$sql .= "WHERE `recip_valid` = '0' ";
		if (!empty($aStatus)) {
			$sql .=" and status='$aStatus'";
		}
		return $this->getOne($sql);
	}

	/**
	 * getNorecip
	 *
	 * Returns listings that have no correct reciprocal listings
	 *
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @param string $aStatus link status
	 * @param mixed $aCalc
	 * @access public
	 * @return arr
	 */
	public function getNorecip($aStart = 0, $aLimit = 0,$aStatus='', $aCalc = false)
	{
		$cause = "WHERE t1.`recip_valid` = '0' ";
		if (!empty($aStatus))
		{
			$cause .=" and t1.`status`='".$aStatus."'";
		}

		return $this->getByCriteria($aStart, $aLimit, $cause, $aCalc);
	}

	/**
	 * getNumListingsByCategory
	 *
	 * Returns number of listings by category id
	 *
	 * @param int $aCategory category id
	 * @access public
	 * @return int
	 */
	public function getNumListingsByCategory($aCategory)
	{
		$cause = "WHERE `category_id` = '{$aCategory}'";

		return $this->getNumListingsBy($cause);
	}

	/**
	 * getListingsByStatus
	 *
	 * Returns listings by their status
	 *
	 * @param string $aStatus listings status
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @param mixed $calcRows
	 * @access public
	 * @return arr
	 */
	public function getListingsByStatus($aStatus = '', $aStart = 0, $aLimit = 0, $calcRows=false)
	{
		$cause = $aStatus ? "WHERE t1.`status` = '".$aStatus."' " : '';

		return $this->getByCriteria($aStart, $aLimit, $cause,$calcRows);
	}

	/**
	 * getListings
	 *
	 * Returns all listings
	 *
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @access public
	 * @return arr
	 */
	public function getListings($aStart = 0, $aLimit = 0)
	{
		return $this->getByCriteria($aStart, $aLimit, '',false);
	}

	/**
	 * updatePageRank
	 *
	 * Updates link pagerank
	 *
	 * @param int $aListing link id
	 * @param int $aPr url pagerank
	 * @access public
	 * @return bool
	 */
	public function updatePageRank($aListing, $aPr)
	{
		$sql = "UPDATE `".$this->mTable."` ";
		$sql .= "SET `pagerank` = '{$aPr}' ";
		$sql .= "WHERE `id` = '{$aListing}'";

		return $this->query($sql);
	}

	/**
	 * moveCrossListing
	 *
	 * Moves cross listing to other category
	 *
	 * @param int $aListing listing id
	 * @param int $aCategory category id where listing should be moved
	 * @param int $aCategoryFrom category id where listing is moved from
	 * @access public
	 * @return bool
	 */
	public function moveCrossListing($aListing, $aCategory, $aCategoryFrom)
	{
		$this->setTable("listing_categories");
			$deleted = parent::delete("`listing_id`='".$aListing."' AND `category_id` = '".$aCategoryFrom."'");

			// nothing to move
			if (!$deleted)
			{
				return false;
			}
			$this->decreaseNumListings($aCategoryFrom);

			$already = parent::exists("`listing_id`='".$aListing."' AND `category_id`='".$aCategory."'");
			if (!$already)
			{
				parent::insert(array(
					"listing_id"=>$aListing,
					"category_id" => $aCategory
				));

				$this->increaseNumListings($aCategory);
			}

		$this->resetTable();

		return true;
	}

	/**
	 * Moves listing to other category
	 *
	 * @param mixed $listing listing id or listing itself
	 * @param int $aCategory category id where listing should be moved
	 * @param string $aOldCategory
	 * @param bool $notify sends email in case true
	 *
	 * @return bool
	 */
	public function move($listing, $aCategory, $aOldCategory = '-1', $notify = false)
	{
		if (!is_array($listing))
		{
			$listing = $this->row("*", "`id`='".$listing."'");
		}

		if (isset($listing['status']) && $listing['status'] == 'active')
		{
			// destination category increase
			$this->increaseNumListings($aCategory);
			// decrease source category
			if ('-1' != $aOldCategory)
			{
				$this->decreaseNumListings($aOldCategory);
			}
			else
			{
				$this->decreaseNumListings($listing['category_id']);
			}
		}

		parent::update(array("category_id" => $aCategory), "`id`='".$listing['id']."'");
		$listing['category_id'] = $aCategory;

		if (isset($listing['email']) && !empty($listing['email']) && $notify)
		{
			$this->setTable('categories');
				$category = $this->row("`path`","`id`='".$listing['category_id']."'");
			$this->resetTable();

			$this->mMailer->AddAddress($listing['email']);
			$this->mMailer->Send('listing_move', $listing['account_id'], $listing);
		}

		return true;
	}

	/**
	 * copy
	 *
	 * Copies link to another category
	 *
	 * @param int $aListing link id
	 * @param int $aCategory new category id
	 * @access public
	 * @return bool
	 */
	public function copy($aListing, $aCategory = 0)
	{
		$this->setTable("listing_categories");
		$x = parent::insert(array("listing_id"=>$aListing, "category_id"=>$aCategory));
		$this->resetTable();

		//$this->increaseNumListings($aCategory);

		return $x;
	}

	/**
	 * deleteCrossListing
	 *
	 * Removes crosslink
	 *
	 * @param int $aListing link id
	 * @param int $aCategory category id
	 * @access public
	 * @return bool
	 */
	public function deleteCrossListing($aListing, $aCategory)
	{
		$this->setTable("listings");
			$status = parent::one("`status`", "`id`='".$aListing."'");
		$this->resetTable();
		$sql = "DELETE FROM `".$this->mPrefix."listing_categories` ";
		$sql .= "WHERE `listing_id` = '".$aListing."' AND `category_id` = '".$aCategory."'";

		$this->query($sql);

		if ($status == 'active')
		{
			$this->decreaseNumListings($aCategory);
		}

		return $this->getAffected();
	}

	/**
	 * updateStatus
	 *
	 * @param mixed $aListing
	 * @param string $aStatus
	 * @param mixed $aSendmail
	 * @access public
	 * @return void
	 */
	public function updateStatus($aListing, $aStatus = 'active', $aSendmail = false)
	{
		return $this->updateListingStatus($aListing, $aStatus, $aSendmail);
	}

	/**
	 * updateListingStatus
	 *
	 * Updates status for link
	 *
	 * @param int $aListing link id
	 * @param string $aStatus link status
	 * @param bool $aSendmail if true send mail
	 * @access public
	 * @return bool
	 */
	public function updateListingStatus($aListing, $aStatus = 'active', $aSendmail = false)
	{
		$sql = "UPDATE `".$this->mTable."` SET `status` = '{$aStatus}' ";
		$sql .= "WHERE `id` = '{$aListing}'";

		$cats = $this->one("`category_id`", "`id` = '{$aListing}'");
		$currentStatus = $this->one('`status`', "`id` = '{$aListing}'");

		/** send email in case link email exist and option is enabled **/
		$action = '';

		if ($aStatus == 'banned')
		{
			$action = 'listing_banned';
		}
		elseif ($aStatus == 'active')
		{
			$action = 'listing_approve';
		}
		elseif ($aStatus == 'approval')
		{
			$action = 'listing_disapprove';
		}

		if ($currentStatus != $aStatus)
		{
			if ($aStatus == 'active')
			{
				$this->increaseNumListings($cats);
			}
			else
			{
				$this->decreaseNumListings($cats);
			}
		}

		if ($action)
		{
			$listing = $this->row('*', "`id` = '{$aListing}'");

			if ($listing['email'])
			{
				$this->mMailer->AddAddress($listing['email']);
				$this->mMailer->Send($action, $listing['account_id'], $listing);
			}
		}

		return $this->query($sql);
	}

	/**
	 * delete
	 *
	 * Deletes link from database
	 *
	 * @param string $where
	 * @param string $reason
	 * @param bool $compat - compatibility for PHP 5.4, does nothing
	 * @access public
	 * @return bool
	 */
	public function delete($where = '', $reason=false, $compat= false)
	{
		$aListings = $this->all("*", $where);

		if (!$aListings)
		{
			return 0;
		}

		if (!$this->fields)
		{
			$this->setTable("listing_fields");
			$this->fields = $this->keyvalue("`name`,`type`");
			$this->resetTable();
		}

		$deleted = parent::delete($where);
		$ids = array();

		foreach ($aListings as $aListing)
		{
			foreach ($this->fields as $name => $type)
			{
				if (!empty($aListing[$name]) && in_array($type, array('image', 'storage', 'pictures')))
				{
					if ('pictures' == $type)
					{
						$img_files = explode(',', $aListing[$name]);

						if (!empty($img_files))
						{
							foreach ($img_files as $img_file)
							{
								$img_path = IA_HOME . 'uploads' . IA_DS . $img_file;
								$img_small_path = IA_HOME . 'uploads' . IA_DS . 'small_' . $img_file;

								if (file_exists($img_path))
								{
									unlink($img_path);
								}

								if (file_exists($img_small_path))
								{
									unlink($img_small_path);
								}
							}
						}
					}
					else
					{
						$img_path = IA_HOME . 'uploads' . IA_DS . $aListing[$name];
						$img_small_path = IA_HOME . 'uploads' . IA_DS . 'small_' . $aListing[$name];

						if (file_exists($img_path))
						{
							unlink($img_path);
						}

						if (file_exists($img_small_path))
						{
							unlink($img_small_path);
						}
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

			// Send email in case the link email exists
			// and the corresponding email notification option [listing_delete] is enabled
			if ($aListing['email'])
			{
				$this->mMailer->AddAddress($aListing['email']);

				$replace = array('reason' => $reason);
				$this->mMailer->add_replace($replace);

				$this->mMailer->Send('listing_delete', $aListing['account_id'], $aListing);
			}

			$ids[] = $aListing['id'];

			if ('active' == $aListing['status'])
			{
				$this->decreaseNumListings($aListing['category_id'], 1);
			}
		}

		// work with crossed link. adjust num listings
		$this->setTable("listing_categories");
		$map = parent::keyvalue("`category_id`, count(`category_id`)", "`listing_id` IN('".join("','", $ids)."') GROUP BY `category_id`");
		parent::delete("`listing_id` IN ('".join("','", $ids)."')");
		$this->resetTable();

		if (is_array($map))
		{
			foreach($map as $cat => $count)
			{
				$this->decreaseNumListings($cat, $count);
			}
		}

		return $deleted;
	}

	/**
	 * update
	 *
	 * Updates listing information
	 *
	 * @param arr $aListing listing info array
	 * @param string $where
	 * @param mixed $addit
	 * @param bool $compat - compatibility for PHP 5.4, does nothing
	 * @access public
	 * @return bool
	 */
	public function update($aListing, $where='', $addit=null, $compat = false)
	{
		$newCategory = isset($aListing['category_id']) ? $aListing['category_id'] : false;
		$notify = !empty($aListing['_notify']);
		unset($aListing['_notify']);

		$newStatus = isset($aListing['status']) ? $aListing['status'] : false;
		$x = $this->row("`id`, `status`, `category_id`", isset($aListing['id']) ? "`id`='".$aListing['id']."'" : $where);
		$aListing['id'] = $id = $x['id'];
		$oldStatus = $x['status'];
		$oldCategory = $x['category_id'];

		unset($x);

		if (isset($aListing['crossed']))
		{
			unset($aListing['crossed']);
		}

		if (isset($aListing['crossed_expand_path']))
		{
			unset($aListing['crossed_expand_path']);
		}

		if (isset($aListing['expire_notif_date']))
		{
			unset($aListing['expire_notif_date']);
		}

		// MCross ----
		if (isset($aListing['multi_crossed']))
		{
			parent::setTable("listing_categories");
			parent::delete("`listing_id` = '{$aListing['id']}'");
			parent::resetTable();

			if (!empty($aListing['multi_crossed']))
			{
				foreach ($aListing['multi_crossed'] as $one_cross)
				{
					$sql = "INSERT INTO `".$this->mPrefix."listing_categories` ";
					$sql .= "(`listing_id`, `category_id`) ";
					$sql .= "VALUES ('".$aListing['id']."', '".$one_cross."')";

					$this->query($sql);
				}
			}

			unset($aListing['multi_crossed']);
		}

		// Deep links
		if (isset($aListing['_deep_links']))
		{
			$this->setTable("deep_links");

			if ($id)
			{
				parent::delete("`listing_id` = '{$id}'");
			}
			else
			{
				// TODO: WRONG
				parent::delete($where);
			}

			$data = array();

			foreach($aListing['_deep_links'] as $key => $deep_link)
			{
				$deep_link['listing_id'] = $aListing['id'];

				$data[] = $deep_link;
			}

			parent::insert($data);

			unset($aListing['_deep_links']);
			unset($data);

			$this->resetTable();
		}
		// end Deep links

		$sql = "UPDATE `".$this->mTable."` SET ";
		foreach($aListing as $key => $value)
		{
			$value = esynSanitize::sql($value);
			$sql .= "`".$key."` = '".$value."', ";
		}
		if ($addit)
		{
			foreach($addit as $key => $value)
			{
				$sql .= "`".$key."` = ".$value.", ";
			}
		}

		$sql = rtrim($sql, ", ");

		if (!empty($where))
		{
			$where = " WHERE ".$where;
			if ($id!==false)
			{
				$where .= " AND `id`='".$id."'";
			}
		}
		elseif ($id!==false)
		{
			$where .= " WHERE `id`='".$id."'";
		}

		$sql .= $where;

		$this->query($sql);
		$r = $this->getAffected();

		// two scenarios
		// 1. status changed but not category
		// 2. status not changed but category changed
		if ((int)$newCategory == (int)$oldCategory)
		{
			// 1. scenario
			if ($newStatus && $newStatus != $oldStatus && in_array("active", array($newStatus, $oldStatus), true))
			{
				if ($newStatus == 'active')
				{
					$this->increaseNumListings($newCategory);
				}
				else
				{
					$this->decreaseNumListings($oldCategory);
				}
			}
		}
		else
		{
			$this->move($aListing, $newCategory, $oldCategory, $notify);
		}

		/** send email in case listing email exist and option is enabled **/
		if (isset($aListing['email']) && !empty($aListing['email']) && $notify)
		{
			$this->mMailer->AddAddress($aListing['email']);
			$this->mMailer->Send('listing_modify', $aListing['account_id'], $aListing);
		}

		return $r;
	}

	/**
	 * getSponsored
	 *
	 * Returns sponsored listings
	 *
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @param string $aStatus link status
	 * @param mixed $aCalc
	 * @access public
	 * @return arr
	 */
	public function getSponsored($aStart =0, $aLimit = 0,$aStatus='', $aCalc=false)
	{
		$aCalc = $aCalc ? "SQL_CALC_FOUND_ROWS" : '';
		$sql = "SELECT $aCalc t2.*, `t2`.`sponsored_start` `start`, `t2`.`comments_total` `comments` ";
		$sql .= "FROM `".$this->mTable."` t2 ";
		$sql .= "LEFT JOIN `".$this->mPrefix."listing_comments` t5 ";
		$sql .= "ON t5.`listing_id` = t2.`id` ";
		$sql .= 'WHERE `t2`.`sponsored` = \'1\' ';
		if (!empty($aStatus))
		{
			$sql .=" and t2.`status`='".$aStatus."' ";
		}
		$sql .= "GROUP BY t2.`id` ";
		$sql .= $aLimit ? "LIMIT {$aStart}, {$aLimit}" : '';

		$listings = $this->getAll($sql);

		//** get categories for every link
		if (!empty($listings))
		{
			$i = 0;
			$sql = '';
			foreach ($listings as $key => $value)
			{
				if ($i > 0)
				{
					$sql .= 'UNION ALL ';
				}
				$sql .= "(SELECT t2.`listing_id`, t1.`id`, t1.`title` ";
				$sql .= "FROM `".$this->mPrefix."categories` t1 ";
				$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t2 ";
				$sql .= "ON t1.`id` = t2.`category_id` ";
				$sql .= "WHERE `listing_id` = {$value['id']} ";
				$sql .= "ORDER BY t1.`title`) ";
				$i++;
			}
			if ($sql)
			{
				$categories =& $this->getAssoc($sql);
			}

			//** assign categories to listings
			if (!empty($categories))
			{
				foreach ($listings as $key => $value)
				{
					$listings[$key]['categories'] =& $categories[$value['id']];
				}
			}
		}

		return $listings;
	}

	/**
	 * checkSponsored
	 *
	 * Checks if the link is not sponsored
	 *
	 * @param int $aListing link id
	 * @access public
	 * @return bool true if the link is sponsored, false otherwise
	 */
	public function checkSponsored($aListing)
	{
		$sql = 'SELECT COUNT(`id`) ';
		$sql .= 'FROM `'.$this->mTable.'` ';
		$sql .= 'WHERE `id` = \''.$aListing.'\' AND `sponsored` = \'1\'';
		return (bool)$this->getOne($sql);
	}

	/**
	 * checkFeatured
	 *
	 * Check if the link is marked as Featured
	 *
	 * @param int $aListing link id
	 * @access public
	 * @return bool true if featured, false otherwise
	 */
	public function checkFeatured($aListing)
	{
		$sql = 'SELECT COUNT(`id`) ';
		$sql .= 'FROM `'.$this->mTable.'` ';
		$sql .= 'WHERE `featured` = \'1\' AND `id` = \''.$aListing.'\'';
		return (bool)$this->getOne($sql);
	}

	/**
	 * setFeaturedListing
	 *
	 * Marks the link as Featured
	 *
	 * @param int $aListing link id
	 * @access public
	 * @return void
	 */
	public function setFeaturedListing($aListing)
	{
		$sql = 'UPDATE `'.$this->mTable.'` ';
		$sql .= 'SET `featured` = \'1\', `feature_start` = NOW() ';
		$sql .= 'WHERE `id` = \''.$aListing.'\'';
		$this->query($sql);
	}

	/**
	 * setPartner
	 *
	 * Mark link as Partner
	 *
	 * @param int $aListing link id
	 * @access public
	 * @return void
	 */
	public function setPartner($aListing)
	{
		$sql = 'UPDATE `'.$this->mTable.'` ';
		$sql .= 'SET `partner` = \'1\', `partners_start` = NOW() ';
		$sql .= 'WHERE `id` = \''.$aListing.'\'';
		$this->query($sql);
	}

	/**
	 * checkPartner
	 *
	 * Check if link is not yet marked as Partner
	 *
	 * @param int $aListing link id
	 * @access public
	 * @return bool true if already is Partner, false otherwise
	 */
	public function checkPartner($aListing)
	{
		$sql = 'SELECT COUNT(`id`) ';
		$sql .= 'FROM `'.$this->mTable.'` ';
		$sql .= 'WHERE `partner` = \'1\' AND `id` = \''.$aListing.'\'';
		return (bool)$this->getOne($sql);
	}

	/**
	 * getPartner
	 *
	 * Returns partner listings
	 *
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @param string $aStatus listings status
	 * @param mixed $aCalc
	 * @access public
	 * @return arr
	 */
	public function getPartner($aStart =0, $aLimit = 0, $aStatus = '', $aCalc=false)
	{
		$aCalc = $aCalc ? "SQL_CALC_FOUND_ROWS" : '';
		$sql = "SELECT $aCalc t2.*, t2.`partners_start` `start`, `t2`.`comments_total` `comments` ";
		$sql .= "FROM `".$this->mTable."` t2 ";
		$sql .= 'WHERE `t2`.`partner` = \'1\' ';
		$status = ($aStatus == 'all') ? '' : $aStatus;
		$sql .= $status ? "AND `status` = '".$status."'" : '';
		$sql .= $aLimit ? "LIMIT ".$aStart.", ".$aLimit : '';

		$listings = $this->getAll($sql);

		/** get categories for every link **/
		if (!empty($listings))
		{
			$i = 0;
			$sql = '';
			foreach ($listings as $key => $value)
			{
				if ($i > 0)
				{
					$sql .= 'UNION ALL ';
				}
				$sql .= "(SELECT t2.`listing_id`, t1.`id`, t1.`title` ";
				$sql .= "FROM `".$this->mPrefix."categories` t1 ";
				$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t2 ";
				$sql .= "ON t1.`id` = t2.`category_id` ";
				$sql .= "WHERE `listing_id` = {$value['id']} ";
				$sql .= "ORDER BY t1.`title`) ";
				$i++;
			}
			if ($sql)
			{
				$categories =& $this->getAssoc($sql);
			}

			/** assign categories to listings **/
			if (!empty($categories))
			{
				foreach ($listings as $key => $value)
				{
					$listings[$key]['categories'] =& $categories[$value['id']];
				}
			}
		}

		return $listings;
	}

	/**
	 * getFeatured
	 *
	 * Returns partner listings
	 *
	 * @param int $aStart starting position
	 * @param int $aLimit number of listings to be returned
	 * @param string $aStatus listings status
	 * @param mixed $aCalc
	 * @access public
	 * @return arr
	 */
	public function getFeatured($aStart =0, $aLimit = 0, $aStatus = '', $aCalc = false)
	{
		$aCalc = $aCalc ? "SQL_CALC_FOUND_ROWS" : '';
		$sql = "SELECT $aCalc t2.*, t2.`feature_start` `start`, `t2`.`comments_total` `comments` ";
		$sql .= "FROM `".$this->mTable."` t2 ";
		$sql .= 'WHERE `featured` = \'1\' ';
		$status = ($aStatus == 'all') ? '' : $aStatus;
		$sql .= $status ? "AND `status` = '{$status}'" : '';
		$sql .= $aLimit ? "LIMIT {$aStart}, {$aLimit}" : '';

		$listings =& $this->getAll($sql);

		//** get categories for every link
		if (!empty($listings))
		{
			$i = 0;
			$sql = '';
			foreach ($listings as $key => $value)
			{
				if ($i > 0)
				{
					$sql .= 'UNION ALL ';
				}
				$sql .= "(SELECT t2.`listing_id`, t1.`id`, t1.`title` ";
				$sql .= "FROM `".$this->mPrefix."categories` t1 ";
				$sql .= "LEFT JOIN `".$this->mPrefix."listing_categories` t2 ";
				$sql .= "ON t1.`id` = t2.`category_id` ";
				$sql .= "WHERE `listing_id` = {$value['id']} ";
				$sql .= "ORDER BY t1.`title`) ";
				$i++;
			}
			if ($sql)
			{
				$categories =& $this->getAssoc($sql);
			}

			//** assign categories to listings
			if (!empty($categories))
			{
				foreach ($listings as $key => $value)
				{
					$listings[$key]['categories'] =& $categories[$value['id']];
				}
			}
		}

		return $listings;
	}

	public function getNumBroken()
	{
		$headers = $this->mConfig['http_headers'];

		$correct_headers = explode(',', $headers);

		$where = "`listing_header` NOT IN ('" . implode("','", $correct_headers) . "')";

		return $this->one('COUNT(*)', $where);
	}

	public function getBroken($aStart = 0, $aLimit = 0, $aStatus='', $aCalc=false)
	{
		$cause = "WHERE t1.`listing_header` NOT IN('200', '301','302')";
		if (!empty($aStatus))
		{
			$cause .=" and t1.`status`='".$aStatus."'";
		}

		return $this->getByCriteria($aStart, $aLimit, $cause,$aCalc);
	}

	public function setPlan($aListing, $aId)
	{
		return parent::update(array("plan_id" => $aId), "`id` = :id", array('id' => $aListing), array("sponsored_start"=>"NOW()"));
	}

	public function resetPlan($aListing)
	{
		return parent::update(array("plan_id" => "0", "sponsored_start" => "0000-00-00 00:00:00", "visual_options" => ""), "`id` = :id", array('id' => $aListing));
	}

	public function changePlan($aListing, $aPlan)
	{
		$this->setTable("plans");
		// Get sponsored plan name
		$plan_name = $this->one("`name`", "`id`='".$aPlan."'");
		$this->resetTable();

		// Update link
		$sql = 'UPDATE `'.$this->mPrefix.'listings` ';
		$sql .= 'SET `sponsored` = \'1\', `sponsored_plan_id` = \''.$aPlan.'\' ';
		$sql .= 'WHERE `id` = \''.$aListing.'\'';

		$this->query($sql);
	}

	public function increaseNumListings($id, $count=1)
	{
		$addit = array(
			'num_listings' => '`num_listings`+' . $count,
			'num_all_listings' => '`num_all_listings`+' . $count,
		);

		parent::setTable('flat_structure');
		$cats = parent::onefield('`parent_id`', "`category_id` = '{$id}'");
		parent::resetTable();

		if (!empty($cats))
		{
			parent::setTable('categories');

			foreach ($cats as $cat)
			{
				$tmp_addit = $addit;

				if ($cat != $id)
				{
					unset($tmp_addit['num_listings']);
				}

				parent::update(array(), "`id` = '{$cat}'", array(), $tmp_addit);
			}

			parent::resetTable();

			$this->mCacher->clearAll('categories');
		}
	}

	public function decreaseNumListings($id, $count=1)
	{

		$addit = array(
			'num_listings' => '`num_listings`-' . $count,
			'num_all_listings' => '`num_all_listings`-' . $count,
		);

		// set this for negative UNSIGNED
		parent::query("SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'");

		parent::setTable('flat_structure');
		$cats = parent::onefield('`parent_id`', "`category_id` = '{$id}'");
		parent::resetTable();

		if (!empty($cats))
		{
			parent::setTable('categories');

			foreach ($cats as $cat)
			{
				$tmp_addit = $addit;

				if ($cat != $id)
				{
					unset($tmp_addit['num_listings']);
				}
				parent::update(array(), "`id` = '{$cat}'", array(), $tmp_addit);
			}

			parent::resetTable();

			$this->mCacher->clearAll('categories');
		}
	}

	public function adjustClicks($id)
	{
		$this->setTable("listing_clicks");
		$count = $this->one("count(*)", "`listing_id`='".$id."'");
		$this->resetTable();

		parent::update(array("clicks"=>$count, "id"=>$id));
	}
}
