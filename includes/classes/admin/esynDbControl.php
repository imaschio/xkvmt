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
 * esynDbControl
 *
 * @uses esynAdmin
 * @package
 * @version $id$
 */
class esynDbControl extends esynAdmin
{
	/**
	 * makeStructureBackup
	 *
	 * Return structure sql dump
	 *
	 * @param string $aTable table name
	 * @param bool $aDrop if true use DROP TABLE
	 * @param bool $aPrefix if true use prefix
	 * @access public
	 * @return string
	 */
	public function makeStructureBackup($aTable, $aDrop = false, $aPrefix = true)
	{
		$out = $aDrop ? "DROP TABLE IF EXISTS `$aTable`;\n" : '';

		$res = $this->getRow("SHOW CREATE TABLE `$aTable`");
		$out .= $res ? array_pop($res) . ';' : false;
		$out = !$aPrefix ? str_replace($this->mPrefix, '{prefix}', $out) : $out;
		return $out;
	}

	/**
	 * makeDataBackup
	 *
	 * Return data sql dump
	 *
	 * @param string $aTable $aTable table name
	 * @param bool $aComplete if true use complete inserts
	 * @param bool $aPrefix if true use prefix
	 * @access public
	 * @return string
	 */
	public function makeDataBackup($aTable, $aComplete = false, $aPrefix = true)
	{
		$this->setTable($aTable, false);

		$table_name = !$aPrefix ? str_replace($this->mPrefix, "{prefix}", $aTable) : $aTable;

		$out = '';
		$complete = '';

		if ($aComplete)
		{
			if ($fields = $this->describe($table_name))
			{
				$complete = ' (';
				foreach ($fields as $value)
				{
					$complete .= "`" . $value['Field'] . "`, ";
				}
				$complete = preg_replace("/, $/", "", $complete);
				$complete .= ')';
			}
		}

		if ($data = $this->all("*"))
		{
			foreach($data as $key => $value)
			{
				$out .= "INSERT INTO `" . $table_name . "`" . $complete . " VALUES (";
				foreach($value as $key2 => $value2)
				{
					if (!isset($value[$key2]))
					{
						$out .= "NULL, ";
					}
					elseif ($value[$key2] != "")
					{
						$out .= "'" . esynSanitize::sql($value[$key2]) . "', ";
					}
					else
					{
						$out .= "'', ";
					}
				}
				$out = rtrim($out, ', ');

				$out .= ");\n";
			}
		}

		$this->resetTable();

		return $out;
	}

	/**
	 * makeFullBackup
	 *
	 * Return data + structure sql dump
	 *
	 * @param string $aTable table name
	 * @param bool $aDrop if true use DROP TABLE
	 * @param bool $aComplete if true use complete inserts
	 * @param bool $aPrefix if true use prefix
	 * @access public
	 * @return string
	 */
	public function makeFullBackup($aTable, $aDrop = false, $aComplete = false, $aPrefix = true)
	{
		$out = $this->makeStructureBackup($aTable, $aDrop, $aPrefix);
		$out .= "\n\n";
		$out .= $this->makeDataBackup($aTable, $aComplete, $aPrefix);
		$out .= "\n\n";

		return $out;
	}

	/**
	 * makeDbStructureBackup
	 *
	 * Returns structure dump of a database
	 *
	 * @param bool $aDrop if true use DROP TABLE
	 * @param bool $aPrefix if true use prefix
	 * @access public
	 * @return string
	 */
	public function makeDbStructureBackup($aDrop = false, $aPrefix = true)
	{
		$out = "CREATE DATABASE `{$this->mConfig['dbname']}`;\n\n";

		$tables = $this->getTables();

		foreach($tables as $table)
		{
			$out .= $this->makeStructureBackup($table, $aDrop, $aPrefix);
			$out .= "\n\n";
		}

		return $out;
	}

	/**
	 * makeDbDataBackup
	 *
	 * Returns data dump of a database
	 *
	 * @param bool $aComplete if true use complete inserts
	 * @param bool $aPrefix if true use prefix
	 * @access public
	 * @return string
	 */
	public function makeDbDataBackup($aComplete = false, $aPrefix = true)
	{
		$tables = $this->getTables();

		$out = '';
		foreach($tables as $table)
		{
			$out .= $this->makeDataBackup($table, $aComplete, $aPrefix);
			$out .= "\n\n";
		}

		return $out;
	}

	/**
	 * makeDbBackup
	 *
	 * Returns whole database dump
	 *
	 * @param bool $aDrop if true use DROP TABLE
	 * @param bool $aComplete if true use complete inserts
	 * @param bool $aPrefix if true use prefix
	 * @access public
	 * @return string
	 */
	public function makeDbBackup($aDrop = false, $aComplete = false, $aPrefix = true)
	{
		$out = "CREATE DATABASE `".$this->mConfig['dbname']."`;\n\n";

		$tables = $this->getTables();

		foreach($tables as $table)
		{
			$out .= $this->makeStructureBackup($table, $aDrop, $aPrefix);
			$out .= "\n\n";
			$out .= $this->makeDataBackup($table, $aComplete, $aPrefix);
			$out .= "\n\n";
		}

		return $out;
	}

	public function rebuildFlat($aStart = 0, $aLimit = 100)
	{
		// gets ids and paths for all categories
		$this->setTable("categories");
		$cats = $this->all("`id`, `parent_id`, `path`", "1=1 LIMIT {$aStart},{$aLimit}");
		$this->resetTable();

		if ($cats)
		{
			$count_cats = count($cats);
			$i = 0;
			foreach ($cats as $value)
			{
				$i++;
				$this->setTable("categories");
				// there should be / on the end of path for each category excludes ROOT
				$slash = $value['parent_id'] == '-1' ? "" : "/";

				// gets ALL childs for each category by path of category
				$child_ids = $this->all("`id`", "`path` LIKE '".$value['path'].$slash."%' AND `id` != '0'");
				$this->resetTable();

				$values[] = "(" . $value['id'] . ", " . $value['id'] . ")";

				if (!empty($child_ids))
				{
					foreach ($child_ids as $val)
					{
						$values[] = "(" . $value['id'] . ", " . $val['id'] . ")";
					}
				}

				// collect data for query. for 20000 values it will be ~ 1Mb. for most servers max allowed size is 2~8 Mb
				if (count($values) >= 20000 || $i == $count_cats)
				{
					$sql = "INSERT INTO {$this->mPrefix}flat_structure (`parent_id`, `category_id`) VALUES ";
					$sql .= implode(",", $values);
					$values = array();
					$this->query($sql);
				}
			}
		}
		$this->resetTable();
	}

	/**
	 * recalculateListingsCount
	 *
	 * @param mixed $parent
	 * @access public
	 * @return void
	 */
	public function recalculateListingsCount($parent)
	{
		global $listings;
		global $parents;
		global $num_all_listings_map;
		global $called;

		$total = 0;
		foreach($parents[$parent] as $id)
		{
			if (isset($num_all_listings_map[$id]))
			{
				// num listings of all its children
				$total += $num_all_listings_map[$id] - $listings[$id];
			}
			elseif (isset($parents[$id]))
			{
				$this->recalculateListingsCount($id);
				$called[] = $id;
			}

			if (!in_array($id, $called))
			{
				$total += $listings[$id];
			}
			else
			{
				$total += $num_all_listings_map[$id];
			}
		}

		if (!isset($num_all_listings_map[$parent]))
		{
			$num_all_listings_map[$parent] = 0;
		}

		if (!in_array($parent, $called))
		{
			if (isset($listings[$parent]))
			{
				$total += $listings[$parent];
			}

			$num_all_listings_map[$parent] += $total;
		}
	}

	/**
	 * Returns array of tables
	 *
	 * @return array|bool
	 */
	public function getTables()
	{
		$result = false;

		$query = $this->query('SHOW TABLES');
		if ($this->getNumRows($query) > 0)
		{
			$result = array();
			$function = IA_CONNECT_ADAPTER . '_fetch_row';
			while ($row = $function($query))
			{
				if (0 === strpos($row[0], $this->mPrefix))
				{
					$result[] = $row[0];
				}
			}
		}

		return $result;
	}

	/**
	 * optimize
	 *
	 * @param string $table
	 * @access public
	 * @return void
	 */
	public function optimize($table='')
	{
		if (empty($table))
		{
			$table = $this->mTable;
		}
		else
		{
			$table = $this->mPrefix . $table;
		}
		return $this->getAll("OPTIMIZE TABLE `" . $table . "`");
	}

	/**
	 * repair
	 *
	 * @param string $table
	 * @access public
	 * @return arr
	 */
	public function repair($table='')
	{
		if (empty($table))
		{
			$table = $this->mTable;
		}
		else
		{
			$table = $this->mPrefix.$table;
		}

		return $this->getAll("REPAIR TABLE `".$this->mPrefix.$table."`");
	}

	/**
	 * getTableStatus
	 *
	 * @param string $table
	 * @access public
	 * @return arr
	 */
	public function getTableStatus($table='')
	{
		if (empty($table))
		{
			$table = $this->mTable;
		}
		else
		{
			$table = $this->mPrefix.$table;
		}

		$sql = "SHOW TABLE STATUS `".$this->mPrefix.$table."`";

		return $this->getRow($sql);
	}

	public function reset($option)
	{
		if (empty($option))
		{
			return false;
		}

		$method_name = '_reset_' . $option;

		if (!method_exists($this, $method_name))
		{
			return false;
		}

		$reset_dependence = array(
			'categories' => array('categories', 'category_clicks', 'field_categories', 'flat_structure', 'listing_categories', 'plan_categories'),
			'listings' => array('listings', 'listing_categories', 'listing_clicks', 'deep_links'),
			'accounts' => array('accounts', 'listings')
		);

		$this->$method_name();
	}

	public function _reset_categories()
	{
		/*
		 * Clear categories table
		 *
		 * Remove all categories except ROOT category
		 *
		 */
		parent::setTable("categories");
		parent::delete("`parent_id` != '-1'");
		$root_id = parent::one('`id`', "`parent_id` = '-1'");
		parent::resetTable();

		/*
		 * Clear all dependence tables
		 */
		$reset_dependence = array('category_clicks', /*'field_categories',*/ 'flat_structure', 'listing_categories', 'plan_categories');

		foreach($reset_dependence as $table)
		{
			$this->_truncate_table($table);
		}

		parent::setTable('field_categories');
		parent::delete("`category_id` != '{$root_id}'");
		parent::resetTable();

		$this->factory("DbControl");

		global $esynDbControl;

		$esynDbControl->rebuildFlat();
	}

	public function _reset_listings()
	{
		$reset_dependence = array('listings', 'listing_categories', 'listing_clicks', 'deep_links');

		/*
		 * Clear all dependence tables
		 */
		foreach($reset_dependence as $table)
		{
			$this->_truncate_table($table);
		}

		$this->factory("Category");

		global $esynCategory;

		$esynCategory->adjustNumListings();
	}

	public function _reset_accounts()
	{
		/*
		 * Clear account table
		 */
		$this->_truncate_table('accounts');

		/*
		 * Set the account id value to zero in the listings table
		 */
		parent::setTable("listings");
		parent::update(array("account_id" => '0'));
		parent::resetTable();
	}

	private function _reset_category_clicks()
	{
		// reset clicks tables
		$this->_truncate_table('category_clicks');

		// reset stats
		parent::setTable("categories");
		parent::update(array("clicks" => '0'));
		parent::resetTable();
	}

	private function _reset_listing_clicks()
	{
		// reset clicks tables
		$this->_truncate_table('listing_clicks');

		// reset stats
		parent::setTable("listings");
		parent::update(array("clicks" => '0'));
		parent::resetTable();
	}

	public function _truncate_table($table)
	{
		if (empty($table))
		{
			return false;
		}

		parent::query("TRUNCATE TABLE `{$this->mPrefix}{$table}`");
	}
}
