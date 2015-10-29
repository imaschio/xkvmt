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
 * esynAdmin
 *
 * Implements main class for eSyndiCat administration board
 *
 * @uses esynDatabase
 * @package
 * @version $id$
 */
class esynAdmin extends eSyndiCat
{
	/**
	 * getMaxOrder
	 *
	 * Returns max order
	 *
	 * @access public
	 * @return int
	 */
	public function getMaxOrder()
	{
		$where = '';
		if ($this->mTable == $this->mPrefix . "categories" && func_num_args() > 0 && ($aCategory = func_get_arg(0)) > 0)
		{
			$where = " `parent_id` = '" . $aCategory . "'";
		}

		return $this->one("MAX(`order`)", $where);
	}

	/**
	 * cleanTable
	 *
	 * Cleans table
	 *
	 * @param mixed $aTable
	 * @access public
	 * @return bool
	 */
	public function cleanTable($aTable=false)
	{
		if ($aTable == false)
		{
			$aTable = $this->mTable;
		}

		return $this->query("TRUNCATE TABLE `" . $aTable . "`");
	}

	/**
	 * getKeys
	 *
	 * Returns keys of a table
	 *
	 * @param string $aTable table name
	 * @access public
	 * @return arr
	 */
	public function getKeys($aTable)
	{
		return $this->getAll("SHOW KEYS FROM `" . $aTable . "` ");
	}

	/**
	 * getDatabases
	 *
	 * Return the list of databases
	 *
	 * @access public
	 * @return void
	 */
	public function getDatabases()
	{
		$out 	= false;
		$sql 	= "SHOW DATABASES";
		$r 		= $this->query($sql);

		if ($this->getNumRows($r) > 0)
		{
			$out = array();
			while ($row = mysql_fetch_row($r))
			{
				$out[] = $row[0];
			}
		}

		return $out;
	}
}
