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

class esynBanner extends eSyndiCat
{
	var $mTable = "banners";
	var $order;

	/**
	*	This property needed to reduce getParentCategories method calling to one.
	*	The method returns parent categories of the current category.
	*	The method invokes from `get` method
	**/
	var $parentCategories = null;

	function esynBanner()
	{
	    global $esynConfig;

		parent::eSyndiCat();

		$this->order = ('random' == $esynConfig->getConfig('banners_order')) ? 'RAND() ' : '`added` DESC ';
	}

	/**
	* Returns array of top parent categories
	* this is needed to show banner also in subcategories if recursive enabled (see also 'get' method)
	*
	* @param int $cId category id
	*
	* @return arr
	*/
	function getParentCategories($cId)
	{
		$sql = "SELECT parent_id, 1 FROM `".$this->mPrefix."flat_structure`";
		$sql .= "WHERE category_id = '".$cId."' AND parent_id <> '".$cId."'";

		return $this->getKeyValue($sql);
	}

	/**
	* Return one banner by either id or by category id and if recursive then it gets child categories
	*
	* @param str $position the position of the banner (top,left,bottom,right ...)
	* @param int $currentCategory current category
	*
	* @return arr
	*/
	function getBanner($position = '', $currentCategory = false, $limit = 1)
	{
		$where = '';
		if (false !== $currentCategory)
		{
			if (null === ($parents = $this->parentCategories))
			{
				$parents = $this->getParentCategories($currentCategory);
				if (!$parents)
				{
					$parents = '0';
				}
				else
				{
					$parents = array_keys($parents);
					$parents = implode("','",$parents);
				}

				$this->parentCategories = $parents;
			}

			$sql = "SELECT `b`.* FROM `{$this->mTable}_categories` `bc` ";
			$sql .= "LEFT JOIN `{$this->mTable}` `b` ";
			$sql .= "ON `bc`.`banner_id` = `b`.`id` ";
			$sql .= "WHERE ";
			$where = "`status` = 'active' AND `position` = '{$position}' ";
			$where .= "AND (`bc`.`category_id` = '{$currentCategory}' ";
			$where .= "OR `b`.`recursive` = '1' ";


			if ($currentCategory != $parents)
			{
				 $where .= " AND `bc`.`category_id` IN('{$parents}')";
			}
			else
			{
				 $where.=" AND `bc`.`category_id` = '{$currentCategory}'";
			}
			$where .= ')';
		}
		else
		{
			$sql = "SELECT `b`.* FROM `{$this->mTable}` `b` WHERE ";
			$where = "`status` = 'active' AND `position` = '{$position}'";
		}

		$sql .= $where;
		$sql .= " ORDER BY $this->order ";
		$sql .= "LIMIT 0, {$limit}";

		$b = $this->getAll($sql);

		return $b;
	}

	/**
	* Used by banner router to increase number of clicks
	*
	* @return bool
	*/
	function click($bId,$aIp)
	{
		$this->setTable("banner_clicks");
		$f = array(
			"id_banner"=>$bId,
			"ip"=>$aIp
		);
		parent::insert($f,array("date"=>"NOW()"));
		$this->resetTable();
		parent::query("UPDATE `$this->mTable` SET `clicked` = `clicked`+'1' WHERE `id`='".$bId."'");

		return true;
	}

	/**
	* Checks if a link was already clicked
	*
	* @param str $aIp ip address
	*
	* @return int
	*/
	function checkClick($aId, $aIp)
	{
		$sql = "SELECT `id` ";
		$sql .= "FROM `".$this->mPrefix."banner_clicks` ";
		$sql .= "WHERE `ip` = '".$aIp."' ";
		$sql .= "AND `id_banner` = '".$aId."' ";
		$sql .= "AND (TO_DAYS(NOW()) - TO_DAYS(`date`)) <= 1 ";

		return $this->getOne($sql);
	}


	/**
	* Adds banner record in table
	*
	* @param str $banner banner info
	* @param arr $categories list of the categories where to show the banner
	*
	* @return int
	*/
	function insert($banner, $addit=null)
	{
		$categories = array();

		if (isset($banner['categories']))
		{
			$categories = $banner['categories'];
			unset($banner['categories']);
		}
		if (!isset($addit['added']))
		{
			$addit['added'] = 'NOW()';
		}

		$this->setTable("banners");
		$lastId = parent::insert($banner, $addit);
		$this->resetTable();

		if (!empty($categories))
		{
			$this->setTable("banners_categories");
			$cats = array();

			foreach($categories as $cId)
			{
				$cats[] = array("banner_id"=>$lastId,"category_id"=>$cId);
			}
			parent::insert($cats);
			$this->resetTable();
		}

		return $lastId;
	}

	/**
	* Updates banner info
	*
	* @param int $aId banner id
	* @param str $aTitle banner title
	* @param str $aAlt alternative text
	* @param str $aUrl link url
	* @param str $aImage url to banner url
	*
	* @return bool
	*/
	function update($banner, $where = '', $values = Array(), $addit = NULL)
	{
		$id = isset($banner['id']) ? $banner['id'] : '' ;
		unset($banner['id']);

		$categories = array();
		if (isset($banner['categories']))
		{
			$categories = $banner['categories'];
			unset($banner['categories']);
		}

		$this->setTable("banners");
		$r = parent::update($banner, $where);
		$this->resetTable();

		if (!empty($categories))
		{
			$this->setTable("banners_categories");
			parent::delete("`banner_id` = '{$id}'");

			foreach($categories as $cId)
			{
				parent::insert(array("banner_id"=>$id, "category_id"=>$cId));
			}
			$this->resetTable();
		}

		return $r;
	}

	function get_blocks ()
	{
	    $sql = "SELECT b.* FROM `{$this->mPrefix}blocks` as b RIGHT JOIN `{$this->mPrefix}banner_plan_blocks` AS pb ON b.`position` = pb.`block_pos` WHERE b.`plugin` = 'banners'";
	    return $this->getAll($sql);
	}

	/**
	 * setPlan
	 *
	 * @param array $banner
	 * @access public
	 */
	function setPlan($banner)
	{
		parent::update($banner, "", array(), array("sponsored_start"=>"NOW()"));
	}

	/**
	 * getPlan
	 *
	 * @param mixed $bannerPos
	 * @access public
	 * @return void
	 */
	function getPlan($bannerPos)
	{
		$sql = "SELECT p.* FROM `{$this->mPrefix}banner_plans` as p LEFT JOIN `{$this->mPrefix}banner_plan_blocks` AS pb ON p.`id` = pb.`plan_id` WHERE pb.`block_pos` = '{$bannerPos}'";
	    return $this->getAll($sql);
	}

    function postPayment ($item, $plan, $invoice, $transaction)
    {
	    $banner['id'] = $invoice['item_id'];
        $banner['status'] = 'approval';

	    if ('passed' == $transaction['status'])
	    {
			$banner['transaction_id'] = $transaction['id'];
			$banner['plan_id'] = $invoice['plan_id'];
			$banner['status'] = 'active';
	    }

        $this->setPlan($banner);
    }
}