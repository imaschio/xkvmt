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
 * esynListingField
 *
 * @uses eSyndiCat
 * @package
 * @version $id$
 */
class esynListingField extends eSyndiCat
{
	/**
	 * mTable
	 *
	 * @var string
	 * @access public
	 */
	var $mTable = 'listing_fields';

	/**
	 * getAllFieldsByCategory
	 *
	 * Returns all editable fields by category
	 *
	 * @param mixed $id
	 * @access public
	 * @return arr
	 */
	function getAllFieldsByCategory($id)
	{
		$sql = 'SELECT f.* FROM `'.$this->mTable.'` `f` ';
		$sql.= 'LEFT JOIN `'.$this->mPrefix.'field_categories` `fc` ON `fc`.`field_id` = `f`.`id` ';

		$sql.= "WHERE f.`editable`='1' AND ( fc.`category_id` = '{$id}'";

		if ($id > 0)
		{
			$this->setTable("flat_structure");
			$parents = parent::onefield('`parent_id`', "`category_id`='{$id}' AND `parent_id`<>'{$id}'");
			$this->resetTable();
			$sql.= " OR ( `f`.`recursive`='1' AND `fc`.`category_id` IN ('".implode("','",$parents)."'))";
		}
		$sql.= ") ORDER BY f.`order` ASC ";

		return $this->getAll($sql);
	}

	/**
	 * getAllFieldsByPlan
	 *
	 * Returns all editable fields by plan
	 *
	 * @param mixed $id
	 * @access public
	 * @return arr
	 */
	function getAllFieldsByPlan($id = false)
	{
		$sql = 'SELECT f.* FROM `'.$this->mTable.'` `f` ';
		$sql.= 'LEFT JOIN `'.$this->mPrefix.'field_plans` `fp` ON `fp`.`field_id` = `f`.`id` ';
		$sql.= "WHERE f.`editable`='1' ";
		$sql.= $id ? "AND fp.`plan_id` = '{$id}' " : '';
		$sql.= "ORDER BY f.`order` ASC ";

		return $this->getAll($sql);
	}
}
