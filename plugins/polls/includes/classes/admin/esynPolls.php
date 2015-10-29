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

class esynPolls extends esynAdmin
{
	var $mTable = 'polls';

	function row($fields, $where = '', $values = Array(), $start = 0, $limit = NULL)
	{
		$poll = parent::row($fields,$where);
		$this->setTable("poll_options");
		$where = $t =str_replace("id", "poll_id", $where);
		$poll['options'] = $this->all("*", $where);
		$this->resetTable();

		return $poll;
	}

	/*
		Delete poll and its options
	*/
	function delete($where = '', $values = Array(), $truncate = false)
	{
		parent::delete($where, $truncate);
		$where = str_replace("id","poll_id", $where);

		$this->setTable("poll_options");
		parent::delete($where);
		$this->resetTable();

		$this->setTable("polls_categories");
		parent::delete($where);
		$this->resetTable();

		$this->setTable("poll_clicks");
		parent::delete($where);
		$this->resetTable();
	}

	function update($fields, $where = '', $values = Array(), $addit = NULL)
	{
		$categories = array();
		if (array_key_exists('categories',$fields))
		{
			$categories = $fields['categories'];
			unset($fields['categories']);
		}

		$opt = $fields['newoptions'];
		unset($fields['newoptions']);

		// update existant options
		$options = $fields['options'];
		unset($fields['options']);

		$this->setTable("poll_options");
		if (!empty($options))
		{
			$deleteOptionsIds = array();
			// updat existant options
			foreach($options as $id=>$t)
			{
				if (empty($t))
				{
					$deleteOptionsIds[] = $id;
				}
				else
				{
					parent::update(array("id"=>$id,"title"=>$t));
				}
			}
			if (!empty($deleteOptionsIds))
			{
				$ids = implode(",", $deleteOptionsIds);
				parent::delete("`id` IN(".$ids.")");
			}
		}
		$this->resetTable();
		// end update existant options

		// update poll itself
		parent::update($fields,$where);

		if (!empty($opt))
		{
			// insert new options
			$this->setTable("poll_options");
			$options = array();
			foreach($opt as $title)
			{
				$options[] = array("title" => $title, "votes" => 0, "poll_id" => $fields['id']);
			}
			parent::insert($options);
			$this->resetTable();
		}
		if (!empty($categories))
		{
			$this->setTable("polls_categories");
			parent::delete("`poll_id`='".$fields['id']."'");
			$cats = array();
			foreach($categories as $cId)
			{
				$cats[] = array("poll_id"=>$fields['id'],"category_id"=>$cId);
			}
			parent::insert($cats);
			$this->resetTable();
		}else{
			$this->setTable("polls_categories");
			parent::delete("`poll_id`='".$fields['id']."'");
			parent::insert(array("poll_id"=>$fields['id'],"category_id"=>'0'));
			$this->resetTable();
		}
	}

	function insert($fields, $addit = array())
	{
		$categories = array();
		if (array_key_exists('categories',$fields))
		{
			$categories = $fields['categories'];
			unset($fields['categories']);
		}

		$opt = $fields['options'];
		unset($fields['options']);

		$poll_id = parent::insert($fields);

		if (!empty($categories))
		{
			$this->setTable("polls_categories");
			$cats = array();
			foreach($categories as $cId)
			{
				$cats[] = array("poll_id"=>$poll_id,"category_id"=>$cId);
			}
			parent::insert($cats);
			$this->resetTable();
		}else{
			$this->setTable("polls_categories");
			parent::insert(array("poll_id"=>$poll_id,"category_id"=>'0'));
			$this->resetTable();
		}

		$this->setTable("poll_options");
		$options = array();
		foreach($opt as $title)
		{
			$options[] = array("title" => $title, "votes" => 0, "poll_id" => $poll_id);
		}
		parent::insert($options);
		$this->resetTable();

		return $poll_id;
	}

	/**
	* Checks if a link was already voted
	*
	* @param str $aIp vote ip
	* @param int $aId poll id
	*
	* @return int
	*/
	function isVoted($aIp, $aId)
	{
		return $this->exists("`poll_id` = '".$aId."' AND `ip_address` = '".$aIp."' AND (TO_DAYS(NOW()) - TO_DAYS(`date`)) <= 1 ");
	}

	function num()
	{
		return parent::one("COUNT(*) `num`");
	}

	function getRelatedCategories($aId)
	{
		$sql = "SELECT category_id FROM ".$this->mTable."_categories WHERE poll_id='$aId'";
		return $this->getAll($sql);
	}
}