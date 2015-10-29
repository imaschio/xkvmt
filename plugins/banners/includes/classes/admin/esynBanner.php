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

class esynBanner extends esynAdmin
{
	var $mTable = "banners";

	function Banner()
	{
		global $gAdminDb;

		$config			= & Config::instance();
		$this->mConfig	= & $config->config;
		$this->mTable 	= $this->mConfig['prefix'].$this->mTable;
		$this->mPrefix	= $this->mConfig['prefix'];
		$this->mLink	= & $gAdminDb->mLink;
	}

	function all($fields, $where='', $start=0, $limit=null, $calcRows=false)
	{
		return parent::get("all", $fields, $where, $start, $limit, $calcRows);
	}

	function keyvalue($fields, $where='', $start=0, $limit=null, $calcRows=false)
	{
		return parent::get("keyval", $fields, $where, $start, $limit, $calcRows);
	}

	function row($fields, $where='', $start=0, $limit=null)
	{
		return parent::get("row", $fields, $where, $start, $limit);
	}

	function exists($where='')
	{
		return parent::get("row", '1', $where, 0, 1);
	}

	function one($fields, $where='', $start=0, $limit=null)
	{
		return parent::one($fields, $where);
	}

	#admin

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
	function update($banner, $where = '')
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

	/**
	* Deletes banner record
	*
	* @param int $aId banner id
	*
	* @return bool
	*/
	function delete($aId)
	{
		$image = $this->one("image","id='".$aId."'");
		parent::delete("`id` = '".$aId."'");
		// validate it once more... (remove all the / and \ characters)
		$image = str_replace(array('/',"\\"), "", $image);
		if (is_file(IA_HOME."uploads/".$image))
		{
			unlink(IA_HOME."uploads".IA_DS.$image);
		}

		$this->setTable("banners_categories");
		parent::delete("`banner_id` = '{$aId}'");
		$this->resetTable();

		$this->setTable("banner_clicks");
		parent::delete("`id_banner` = '{$aId}'");
		$this->resetTable();

		return true;
	}
}
