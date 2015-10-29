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
 * esynPlan
 *
 * @uses esynAdmin
 * @package
 * @version $id$
 */
class esynPlan extends eSyndiCat
{

	/**
	 * mTable
	 *
	 * @var string
	 * @access public
	 */
	var $mTable = 'plans';

	/**
	 * update
	 *
	 * @param mixed $fields
	 * @param string $where
	 * @param array $addit
	 * @param bool $compat - compatibility for PHP 5.4, does nothing
	 * @param bool $compat2 - compatibility for PHP 5.4, does nothing
	 * @access public
	 * @return void
	 */
	function update($plan, $ids = '', $compat = false, $compat2 = false)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforePlanUpdate", $params);

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

		if (isset($plan['visual_options']))
		{
			$plan['visual_options'] = "'" . implode("','", $plan['visual_options']) . "'";
		}

		if (isset($plan['categories']))
		{
			parent::setTable("plan_categories");
			parent::delete("`plan_id` = :id", array('id' => (int)$ids));

			if ('' != $plan['categories'])
			{
				$data = array();

				$plan['categories'] = explode('|', $plan['categories']);

				foreach($plan['categories'] as $category)
				{
					$data[] = array(
						"category_id"	=> (int)$category,
						"plan_id"		=> (int)$ids
					);
				}

				parent::insert($data);
			}

			parent::resetTable();

			unset($plan['categories']);
		}

		if (isset($plan['fields']))
		{
			parent::setTable("field_plans");
			parent::delete("`plan_id` = :id", array('id' => (int)$ids));

			if (!empty($plan['fields']))
			{
				$data = array();

				foreach($plan['fields'] as $field)
				{
					$data[] = array(
						'field_id'	=> (int)$field,
						'plan_id'	=> (int)$ids
					);
				}

				parent::insert($data);
			}

			parent::resetTable();

			unset($plan['fields']);
		}

		$where = $this->convertIds('id', $ids);

		parent::update($plan, $where);

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterPlanUpdate", $params);

		return true;
	}

	/**
	 * insert
	 *
	 * Adds plan to database
	 *
	 * @param mixed $plan
	 * @param bool $compat - compatibility for PHP 5.4, does nothing
	 * @access public
	 * @return void
	 */
	function insert($plan, $compat = false)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforePlanInsert", $params);

		if (empty($plan))
		{
			$this->message = 'The plan parameter is empty.';

			return false;
		}

		$order = $this->one("MAX(`order`) + 1");
		$plan['order'] = (null == $order) ? 1 : $order;

		if (isset($plan['categories']))
		{
			$categories = $plan['categories'];

			unset($plan['categories']);
		}

		if (isset($plan['visual_options']))
		{
			$plan['visual_options'] = "'" . implode("','", $plan['visual_options']) . "'";
		}

		if (isset($plan['fields']))
		{
			if (!empty($plan['fields']))
			{
				$fields = $plan['fields'];
			}

			unset($plan['fields']);
		}

		$id = parent::insert($plan);

		if (isset($categories))
		{
			$data = array();

			$categories = explode('|', $categories);

			foreach($categories as $category)
			{
				$data[] = array(
					"category_id"	=> (int)$category,
					"plan_id"		=> $id
				);
			}

			parent::setTable("plan_categories");
			parent::insert($data);
			parent::resetTable();
		}

		if (isset($fields))
		{
			$data = array();

			foreach($fields as $field)
			{
				$data[] = array(
					'field_id'	=> (int)$field,
					'plan_id'	=> $id
				);
			}

			parent::setTable("field_plans");
			parent::insert($data);
			parent::resetTable();
		}

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterPlanInsert", $params);

		return $id;
	}

	/**
	 * delete
	 *
	 * Deletes plan from database
	 *
	 * @param mixed $aId plan id or array of ids
	 * @param bool $compat - compatibility for PHP 5.4, does nothing
	 * @param bool $compat2 - compatibility for PHP 5.4, does nothing
	 * @access public
	 * @return void
	 */
	function delete($ids = '', $comapt = false, $comapt2 = false)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforePlanDelete", $params);

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

			parent::setTable("listings");
			parent::update(array('sponsored' => '0'), "`plan_id` = :id", array('id' => $id), array('sponsored_start' => '0', 'transaction_id' => '0'));
			parent::resetTable();
		}

		$where = $this->convertIds('plan_id', $ids);
		$this->cascadeDelete(array("plan_categories", "field_plans"), $where);

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterPlanDelete", $params);

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
	function set($listing_id, $id)
	{
		// Update listing
		$this->setTable("listings");
		$x = parent::update(array("sponsored"=>"1", "plan_id"=>$id), "`id`='".$listing_id."'", array("sponsored_start"=>"NOW()"));
		$this->resetTable();

		return $x;
	}

	/**
	 * getAllPlansByCategory
	 *
	 * Returns all plan by category
	 *
	 * @param mixed $id
	 * @param string $where
	 * @access public
	 * @return arr
	 */
	function getAllPlansByCategory($id, $where = '')
	{
		$sql = "SELECT p.* FROM `".$this->mTable."` p ";
		$sql .= "LEFT JOIN `".$this->mPrefix."plan_categories` pc ON pc.`plan_id`=p.`id` ";
		$this->setTable("flat_structure");
			$parents = parent::onefield("`parent_id`", "`category_id`='".$id."' AND `parent_id`<>  '".$id."'");
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
					 	pc.category_id='".$id."'
							OR
						p.recursive='1'";
		if ($id != $parents)
		{
			 $sql .=" AND pc.category_id IN(".$parents.")";
		}
		else
		{
			 $sql .= " AND pc.`category_id`='".$id."'";
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
