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
 * esynBannerPlans
 *
 * @uses esynAdmin
 * @package
 * @version $id$
 */
class esynBannerPlans extends esynAdmin
{

	/**
	 * mTable
	 *
	 * @var string
	 * @access public
	 */
	var $mTable = 'banner_plans';

	/**
	 * update
	 *
	 * @param mixed $fields
	 * @param string $where
	 * @param array $addit
	 * @access public
	 * @return void
	 */
	function update($plan, $ids)
	{
		$this->startHook('afterPlanInsert');

		if (empty($plan))
		{
			$this->message = 'The Plan parameter is empty.';

			return false;
		}

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		if (isset($plan['blocks']))
		{
			if (!empty($plan['blocks']))
			{
				$data = array();

				foreach($plan['blocks'] as $block)
				{
					$data[] = array(
						"block_pos"	=> $block,
						"plan_id"	=> $ids
					);
				}

				parent::setTable("banner_plan_blocks");
				parent::delete("`plan_id` = :id", array('id' => (int)$ids));
				parent::insert($data);
				parent::resetTable();
			}

			unset($plan['blocks']);
		}

		$where = $this->convertIds('id', $ids);

		parent::update($plan, $where);

		$this->startHook('afterPlanInsert');

		return true;
	}

	/**
	 * insert
	 *
	 * Adds plan to database
	 *
	 * @param mixed $plan
	 * @access public
	 * @return void
	 */
	function insert($plan)
	{
		$this->startHook('afterPlanInsert');

		if (empty($plan))
		{
			$this->message = 'The plan parameter is empty.';

			return false;
		}

		$order = $this->one("MAX(`order`) + 1");
		$plan['order'] = (NULL == $order) ? 1 : $order;

		if (isset($plan['blocks']) && !empty($plan['blocks']))
		{
			$blocks = $plan['blocks'];

			unset($plan['blocks']);
		}

		$id = parent::insert($plan);

		if (isset($blocks))
		{
			$data = array();

			foreach($blocks as $block)
			{
				$data[] = array(
					"block_pos"	=> $block,
					"plan_id"	=> $id
				);
			}

			parent::setTable("banner_plan_blocks");
			parent::insert($data);
			parent::resetTable();
		}

		$this->startHook('afterPlanInsert');

		return true;
	}

	/**
	 * delete
	 *
	 * Deletes plan from database
	 *
	 * @param mixed $aId plan id or array of ids
	 * @access public
	 * @return void
	 */
	function delete($ids)
	{
		$this->startHook('beforePlanDelete');

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		if (!is_array($ids))
		{
			$ids = array($ids);
		}

		foreach($ids as $id)
		{
			parent::delete("`id` = :id", array('id' => (int)$id));

			parent::setTable("banners");
			parent::update(array('sponsored_start' => '0'), "`plan_id` = :id", array('id' => $id), array('transaction_id' => '0'));
			parent::resetTable();
		}

		$where = $this->convertIds('plan_id', $ids);
		$this->cascadeDelete(array("banner_plan_blocks"), $where);

		$this->startHook('afterPlanDelete');

		return true;
	}

	/**
	 * set
	 *
	 * Assigns sponsored plan for a listing
	 *
	 * @param mixed $listing_id listing id
	 * @param mixed $id plan id
	 * @access public
	 * @return void
	 */
	function set($banner_id, $id)
	{
		// Update banner
		$this->setTable("banners");
		$x = parent::update(array("sponsored"=>"1", "plan_id"=>$id), "`id`='".$banner_id."'", array("sponsored_start"=>"NOW()"));
		$this->resetTable();

		return $x;
	}

	/**
	 * getAllPlansByblock
	 *
	 * Returns all plan by block
	 *
	 * @param mixed $id
	 * @param string $where
	 * @access public
	 * @return arr
	 */
	function getAllPlansByblock($id, $where = '')
	{
		$sql = "SELECT p.* FROM `".$this->mTable."` p ";
		$sql .= "LEFT JOIN `".$this->mPrefix."banner_plan_blocks` pc ON pc.`plan_id`=p.`id` ";
		$this->setTable("flat_structure");
		$parents = parent::onefield("`parent_id`", "`block_id`='".$id."' AND `parent_id`<>  '".$id."'");
		if (!$parents)
		{
			$parents = '0';
		}
		else
		{
			$parents = join(",",$parents);
		}
		$this->resetTable();
		$sql .= "WHERE (
					 	pc.block_id='".$id."'
							OR
						p.recursive='1'";
		if ($id != $parents)
		{
			 $sql .=" AND pc.block_id IN(".$parents.")";
		}
		else
		{
			 $sql .= " AND pc.`block_id`='".$id."'";
		}
		$sql .= ')';

		if ($where)
		{
			$sql .= " AND ".$where;
		}
		$sql .= " ORDER BY p.`order` ASC ";

		return $this->getAll($sql);
	}
}
