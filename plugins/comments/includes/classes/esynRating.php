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

class esynRating extends eSyndiCat
{
	var $mTable = 'votes';

	function esynRating()
	{
		parent::eSyndiCat();
	}

	/**
	* Checks if a link was already voted
	*
	* @param str $aIp vote ip
	* @param int $aId link id
	*
	* @return int
	*/
	function isVoted($aIp, $aId, $aItem)
	{
		return $this->exists("`item_id` = '{$aId}' AND `item` = '{$aItem}' AND `ip_address` = '{$aIp}' AND (TO_DAYS(NOW()) - TO_DAYS(`date`)) <= " . $this->mConfig['comments_'.$aItem.'_rate_period']);
	}

	/**
	* Adds vote record
	*
	* @param int $aId link id
	* @param int $aVote vote rank
	*/
	function ratingInsert($aId, $aVote, $aIp, $aItem)
	{
		$sql  = "INSERT INTO `{$this->mPrefix}votes` ";
		$sql .= "(`item_id`, `item`, `vote_value`, `ip_address`, `date`) ";
		$sql .= "VALUES ('{$aId}', '{$aItem}', '{$aVote}', '{$aIp}', NOW())";
		$this->query($sql);

		/*// Update link rating info
		$sql  = 'SELECT COUNT(`vote_value`) `num_votes`, AVG(`vote_value`) `rating`, MIN(`vote_value`) `min_rating`, MAX(`vote_value`) `max_rating` ';
		$sql .= "FROM `{$this->mPrefix}votes` ";
		$sql .= "WHERE `item_id` = '$aId' AND `item` = '$aItem' ";
		$vote = $this->getRow($sql);

		$sql  = 'UPDATE `'.$this->mPrefix.'listings` SET `num_votes` = \''.$vote['num_votes'].'\', `rating` = \''.$vote['rating'].'\', `min_rating` = \''.$vote['min_rating'].'\', `max_rating` = \''.$vote['max_rating'].'\' ';
		$sql .= 'WHERE `id` = \''.$aId.'\'';
		$this->query($sql);*/
	}
	/**
	* Return listing rating by id
	*
	* @param int $aId listing id
	*/
	function getRating($aId, $aItem)
	{
		/*if ('listings' == $aItem)
		{
			$sql  = "SELECT `num_votes`, `rating` ";
			$sql .= "FROM `{$this->mPrefix}listings` ";
			$sql .= "WHERE `id` = '{$aId}'";
		}
		else
		{*/
			$sql = 'SELECT COUNT(`vote_value`) `num_votes`, AVG(`vote_value`) `rating` ';
			$sql .= "FROM `{$this->mPrefix}votes` ";
			$sql .= "WHERE `item_id` = '$aId' AND `item` = '$aItem' ";
		//}

		return $this->getRow($sql);
	}
}