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

class esynAdditionalOptions extends eSyndiCat
{
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
	function loadFieldClass()
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
	function &field()
	{
		if(!$this->fieldLoaded)
		{
			$this->loadFieldClass();
		}

		return $this->field; 
	}


	function esynAdditionalOptions()
	{
		parent::eSyndiCat();
	}

	/**
	 * deleteListing
	 *
	 * Deletes link from database
	 * 
	 * @param string $where 
	 * @param string $reason 
	 * @access public
	 * @return bool
	 */
	function deleteListing($listing_id, $account_id)
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
		
		foreach($this->fields as $n=>$type)
		{
			if($type=='image' || $type == 'storage')
			{
				if(is_file(IA_HOME.'uploads'.IA_DS.$aListing[$n]))
				{
					unlink(IA_HOME.'uploads'.IA_DS.$aListing[$n]);
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
}