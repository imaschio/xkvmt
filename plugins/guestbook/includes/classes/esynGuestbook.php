<?php

class Guestbook extends esynAdmin
{
	var $mTable = 'guestbook';

	/**
	* Adds guestbook message
	*
	* @param arr $guestbook message information array
	*/
	function insert($message)
	{
		$r = parent::insert($message,array("date"=>"NOW()"));
		return $r;
	}

	/**
	* Returns messages
	*
	* @param int $aStart starting position
	* @param int $aLimit number of messages to be returned
	*
	* @return arr
	*/
	function getByStatus($aStatus = '', $aStart = 0, $aLimit = 0)
	{
		$sql = "SELECT t1.*, ";
		$sql .= "IF (t1.`account_id` > 0, t2.`username`, t1.`author`) author, ";
		$sql .= "IF (t1.`account_id` > 0, t2.`email`, t1.`email`) email ";
		$sql .= "FROM `".$this->mTable."` t1 ";
		$sql .= "LEFT JOIN `".$this->mPrefix."accounts` t2 ";
		$sql .= "ON t1.`account_id` = t2.`id` ";
		$sql .= $aStatus ? "WHERE t1.`status` = '".$aStatus."' " : '';
		$sql .= "ORDER BY t1.`date` DESC ";
		$sql .= $aLimit ? " LIMIT ".$aStart.", ".$aLimit : '';
		return $this->getAll($sql);
	}

	/**
	* Updates message status
	*
	* @param int $aId message id
	* @param str $aStatus message new status
	*/
	function updateStatus($aId, $aStatus)
	{
		parent::update(array("status" => $aStatus), "`id` = '{$aId}'");
	}

	function delete($where='',$truncate=false)
	{
		$x	= parent::delete($where,$truncate);
		return $x;
	}
}