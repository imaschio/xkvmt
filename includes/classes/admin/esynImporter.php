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

class esynImporter extends esynDatabase
{
	var $mConfig	= null;
	var $dbLink		= null;
	var $mItemsToMigrate = array();
	var $mItem = '';
	var $mSourceTable = '';
	var $mDestTable = '';

	protected $_truncated = false;

	public function __construct($item = '')
	{
		global $items_map, $esynAdmin;

		$this->destDb =& $esynAdmin;

		$this->mItemsToMigrate = $items_map;
	}

	public function checkConnection()
	{
		$this->dbLink = @mysql_connect($this->mConfig['dbhost'].":".$this->mConfig['dbport'], $this->mConfig['dbuser'], $this->mConfig['dbpass'], true);

		if (!$this->dbLink)
		{
			return false;
		}

		return true;
	}

	public function selectDatabase()
	{
		$select = @mysql_select_db($this->mConfig['dbname'], $this->dbLink);

		if (!$select)
		{
			return false;
		}

		return true;
	}

	public function _ifTableExists($table, $link = false)
	{
		$_link = $link ? $this : $this->destDb;
		$_prefix = $link ? $this->mPrefix : IA_DBPREFIX;

		return (mysql_num_rows($_link->query("SHOW TABLES LIKE '" . $_prefix . $table . "'")) == 1);
	}

	public function validateVersion($chosen_version)
	{
		if ($this->_ifTableExists('config', true))
		{
			$sql = "SELECT `value` FROM `{$this->mPrefix}config` ";
			$sql .= "WHERE `name` = 'version'";
			$_version = $this->getOne($sql);

			if ($_version == $chosen_version)
			{
				return true;
			}
		}

		return false;
	}

	public function getItemsToMigrate()
	{
		$result = array();

		if ($this->mItemsToMigrate)
		{
			foreach (array_keys($this->mItemsToMigrate) as $table)
			{
				$_table = $this->mItemsToMigrate[$table]['table'][key($this->mItemsToMigrate[$table]['table'])];

				if ($this->_ifTableExists($_table))
				{
					$result[] = $_table;
				}
			}
		}

		return $result;
	}

	public function setMigrationItem($item, $table = '')
	{
		$this->mItem = $item;

		if ($table)
		{
			$this->mSourceTable = $table;
			$this->mDestTable = $this->mItemsToMigrate[$item]['extra_tables'][$table]['name'];
		}
		else
		{
			$this->mSourceTable = key($this->mItemsToMigrate[$item]['table']);
			$this->mDestTable = array_shift($this->mItemsToMigrate[$item]['table']);
		}
	}

	public function getTotal()
	{
		$this->setTable($this->mSourceTable);
		$total = $this->one('COUNT(*)');
		$this->resetTable();

		return $total;
	}

	public function getRecords($start = 0, $limit = null, $table = '')
	{
		$_table = $table ? $table : $this->mSourceTable;

		$method_name = 'getRecords' . ucfirst($this->mItem);
		if (method_exists($this, $method_name))
		{
			$records = $this->$method_name($start, $limit, $_table);
		}
		else
		{
			$this->setTable($_table);
			$records = $this->all("*", '', array(), $start, $limit);
			$this->resetTable();
		}

		return $records;
	}

	public function processItem($records)
	{
		// truncate existing records
		$this->truncateItemRecords();

		$method_name = 'processItem' . ucfirst($this->mItem);
		if (method_exists($this, $method_name))
		{
			$this->$method_name($records);
		}
		else
		{
			// process table records
			$this->destDb->setTable($this->mDestTable);
			foreach($records as $record)
			{
				$insert_record = $this->processRecord($record);
				$this->destDb->insert($insert_record);
			}
			$this->destDb->resetTable();
		}

		return true;
	}

	public function truncateItemRecords()
	{
		$result = false;

		// run truncation once, set it to false back once completed
		if (!$this->_truncated)
		{
			$method_name = 'truncate' . ucfirst($this->mItem);
			if (method_exists($this, $method_name))
			{
				$result = $this->$method_name();
			}
			else
			{
				$result = $this->destDb->cleanTable(IA_DBPREFIX . $this->mDestTable);
			}

			$this->_truncated = true;
		}

		return $result;
	}

	public function truncateCategories()
	{
		$this->destDb->cleanTable(IA_DBPREFIX . 'categories');
		$this->destDb->cleanTable(IA_DBPREFIX . 'flat_structure');

		return true;
	}

	public function processRecord($record, $fields = array())
	{
		$method_name = 'processRecord' . ucfirst($this->mItem);
		if (method_exists($this, $method_name))
		{
			$result = $this->$method_name($record, $fields);
		}
		else
		{
			$result = $this->processMapping($record, $fields);
		}

		return $result;
	}

	public function processMapping($record, $fields = array())
	{
		$result = array();

		$_fields = $fields ? $fields : $this->mItemsToMigrate[$this->mItem]['fields'];
		foreach($_fields as $source => $dest)
		{
			$result[$dest] = $record[$source];
		}

		return $result;
	}

	public function postProcess()
	{
		$this->_truncated = false;

		$method_name = 'postProcess' . ucfirst($this->mItem);
		if (method_exists($this, $method_name))
		{
			$this->$method_name();
		}

		$this->processExtraTables();

		return true;
	}

	public function processExtraTables()
	{
		$result = false;

		if (isset($this->mItemsToMigrate[$this->mItem]['extra_tables']) && !empty($this->mItemsToMigrate[$this->mItem]['extra_tables']))
		{
			$method_name = 'processExtra' . ucfirst($this->mItem);
			if (method_exists($this, $method_name))
			{
				$result = $this->$method_name();
			}
			else
			{
				foreach($this->mItemsToMigrate[$this->mItem]['extra_tables'] as $key => $table)
				{
					if ($this->_ifTableExists($table['name']))
					{
						$this->destDb->cleanTable(IA_DBPREFIX . $table['name']);

						$records = $this->getRecords(0, null, $key);

						if ($records)
						{
							// process table records
							$this->destDb->setTable($table['name']);
							foreach($records as $record)
							{
								$insert_record = $this->processRecord($record, $table['fields']);
								$this->destDb->insert($insert_record);
							}

							$this->destDb->resetTable();
						}
					}
				}
			}
		}

		return $result;
	}

	public function postProcessCategories()
	{
		global $esynDbControl, $esynCategory;

		// update root category id
		$this->destDb->setTable('categories');
		$this->destDb->update(array('id' => '0'), "`parent_id` = '-1'");
		$this->destDb->resetTable();
	}

	public function execSqlByFile($filename)
	{
		$result = false;

		if (!($f = fopen ($filename, "r" )))
		{
			$error = true;
		}
		else
		{
			$sql = '';

			while ($s = fgets ($f, 10240))
			{
				$s = trim($s);

				if (!empty($s))
				{
					if ($s[0] == '#' || $s[0] == '')
					{
						continue;
					}
				}
				else
				{
					continue;
				}

				if ( $s[strlen($s)-1] == ';' )
				{
					$sql .= $s;
				}
				else
				{
					$sql .= $s;
					continue;
				}

				$sql = str_replace("{prefix}", IA_DBPREFIX, $sql);

				$result = $this->destDb->query($sql);

				$sql = "";
			}
			fclose($f);
		}

		return $result;
	}
}
