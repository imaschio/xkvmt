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

class esynPolls extends eSyndiCat
{
	var $mTable = 'polls';

	function esynPolls()
	{
		parent::eSyndiCat();
	}

	function row($fields, $where = '', $values = Array(), $start = 0, $limit = NULL)
	{
		$poll = parent::row($fields,$where);
		$this->setTable("poll_options");
		$where = str_replace("id", "poll_id", $where);
		$poll['options'] = $this->all("*", $where);
		$this->resetTable();

		return $poll;
	}

	function getParentCategories($cId)
	{
		$sql = "SELECT parent_id, 1 FROM `".$this->mPrefix."flat_structure`";
		$sql .= "WHERE category_id='".$cId."' AND parent_id <> '".$cId."'";

		return $this->getKeyValue($sql);
	}

	function getForCategory($currentCategory = false)
	{
		$parents = $this->getParentCategories($currentCategory);

		if (!$parents)
		{
			$parents = '0';
		}
		else
		{
			$parents = array_keys($parents);
			$parents = implode(",",$parents);
		}

		$sql = "SELECT DISTINCT b.id, b.title FROM `".$this->mTable."` b";
		$sql .= " LEFT JOIN `".$this->mTable."_categories` bc ON bc.poll_id = b.id ";
		$where = "WHERE `expires` > NOW() AND `lang`='" . IA_LANGUAGE . "' ";
		$where .= "AND (bc.`category_id`='{$currentCategory}' OR b.`recursive`='1'";
		if ($currentCategory != $parents)
		{
			$where.=" AND bc.`category_id` IN(".$parents.")";
		}
		else
		{
			$where.=" AND bc.`category_id`='".$currentCategory."'";
		}
		$where .= ')';

		$sql .= $where . " ORDER BY `expires` DESC";

		return $this->getAll($sql);
	}

	function checkClick($aId, $aIp)
	{
		$this->setTable("poll_clicks");
		$x = $this->exists("`poll_id`='".$aId."' AND `ip`='".$aIp."' AND (TO_DAYS(NOW()) - TO_DAYS(`date`)) <= 1 ");
		$this->resetTable();

		return $x;
	}

	function click($aId, $aIp)
	{
		$this->setTable("poll_clicks");
		$f = array(
			"poll_id"=>$aId,
			"ip"=>$aIp
		);
		parent::insert($f,array("date"=>"NOW()"));
		$this->resetTable();

		return true;
	}
}