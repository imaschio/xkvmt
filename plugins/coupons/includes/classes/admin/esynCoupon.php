<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.0.3
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
 *	 Copyright 2007-2011 Intelliants LLC
 *	 http://www.intelliants.com/
 *
 ******************************************************************************/

class esynCoupon extends esynAdmin
{
	var $mTable = 'coupons';

	function Coupon()
	{
		global $gAdminDb;

		$config			= & Config::instance();
		$this->mConfig	= & $config->config;
		$this->mTable 	= $this->mConfig['prefix'].$this->mTable;
		$this->mPrefix	= $this->mConfig['prefix'];
		$this->mLink	= & $gAdminDb->mLink;
	}

	/**
	* Returns coupons
	*
	* @param int $aStart starting position
	* @param int $aLimit number of coupons to be returned
	*
	* @return arr
	*/
	function getByStatus($aStatus = '', $aStart = 0, $aLimit = 0)
	{
		$sql = "SELECT t1.* ";
		$sql .= "FROM `".$this->mTable."` t1 ";
		$sql .= $aStatus ? "WHERE t1.`status` = '".$aStatus."' " : '';
		$sql .= "ORDER BY t1.`id` DESC ";
		$sql .= $aLimit ? " LIMIT ".$aStart.", ".$aLimit : '';
		return $this->getAll($sql);
	}

	/**
	* Updates coupon status
	*
	* @param int $aId coupon id
	* @param str $aStatus coupon new status
	*/
	function updateStatus($aId, $aStatus)
	{
		parent::update(array("id"=>$aId,"status"=>$aStatus));
	}

	function randValue()
	{
		$chars = "1234567890abcdefGHIJKLMNOPQRSTUVWxyzABCDEFghijklmnopqrstuvwXYZ1234567890";
		$value = '';
		for($i=0;$i<7;$i++)
		{
			$value .= $chars{rand() % 39};
		}
		 return $value;
	}

}
