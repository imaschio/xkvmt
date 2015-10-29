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

class esynRss extends eSyndiCat
{
	var $mTable = 'rss';

	var $mRss = array();
	var $mRssCacheDir =  'rss';

	var $parentCategories = null;

	function esynRss()
	{
		parent::eSyndiCat();

		require_once(IA_HOME . 'plugins' . IA_DS . 'rss' . IA_DS . 'includes' . IA_DS . 'magpierss' . IA_DS . 'rss_fetch.inc');
	}

	function ifRss()
	{
		$sql = "SELECT `rss`.`id` FROM `{$this->mTable}` rss ";
		$sql .= "WHERE `rss`.`id` NOT IN (SELECT `rss_categories`.`rss_id` FROM `{$this->mTable}_categories` rss_categories)";

		$rIds = $this->getAll($sql);

		if (!empty($rIds))
		{
			$this->setTable('rss_categories');

			foreach($rIds as $rIds)
			{
				$this->insert(array("rss_id"=>$rIds['id'], "category_id"=>"0"));
			}

			$this->resetTable();
		}
	}

	function getRss($currentCategory = false)
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

			$sql = "SELECT `rss`.* FROM `{$this->mTable}_categories` `rss_categories` ";
			$sql .= "LEFT JOIN `{$this->mTable}` `rss` ";
			$sql .= "ON `rss_categories`.`rss_id` = `rss`.`id` ";
			$sql .= "WHERE ";
			$where = "`status` = 'active' ";
			$where .= "AND (`rss_categories`.`category_id` = '{$currentCategory}' ";
			$where .= "OR `rss`.`recursive` = '1' ";

			if ($currentCategory != $parents)
			{
				 $where .= " AND `rss_categories`.`category_id` IN('{$parents}')";
			}
			else
			{
				 $where.=" AND `rss_categories`.`category_id` = '{$currentCategory}'";
			}
			$where .= ')';
		}
		else
		{
			$sql = "SELECT `rss`.* FROM `{$this->mTable}` `rss` WHERE ";
			$where = "`status` = 'active'";
		}
		$sql .= $where;
		$b = $this->getAll($sql);

		return $b;
	}

	function getParentCategories($cId)
	{
		$sql = "SELECT `parent_id`, 1 FROM `{$this->mPrefix}flat_structure` ";
		$sql .= "WHERE `category_id` = '{$cId}' AND `parent_id` <> '{$cId}'";

		return $this->getKeyValue($sql);
	}

	function parse($url = null)
	{
		if (null == $url)
		{
			return false;
		}

		if (!esynValidator::isUrl($url))
		{
			return false;
		}

		$rss = fetch_rss($url);

		return $rss->items;
	}
}