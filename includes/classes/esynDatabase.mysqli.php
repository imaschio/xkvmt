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
 * esynDatabase
 *
 * Implements generic class needed for work with database
 *
 * @package
 * @version $id$
 */
class esynDatabase
{

	var $mLink = null;

	var $mConfig;

	var $mTable = '';

	var $_tableNameBackup = '';


	public function connect($newLink = false)
	{
		static $linkName;

		if (null == $linkName || $newLink)
		{
			$this->mLink = mysqli_init();

			if (!$this->mLink)
			{
				die('mysqli_init failed.');
			}

			if (!mysqli_options($this->mLink, MYSQLI_OPT_CONNECT_TIMEOUT, 5))
			{
				die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed.');
			}

			mysqli_real_connect($this->mLink, $this->mConfig['dbhost'], $this->mConfig['dbuser'], $this->mConfig['dbpass'], $this->mConfig['dbname'], $this->mConfig['dbport']);
			$linkName = $this->mLink;

			if (!$this->mLink)
			{
				trigger_error("Database Connection Error | db_connect_error | Could not connect to database.", E_USER_ERROR);
			}

			// set active database again
			mysqli_select_db($this->mLink, $this->mConfig['dbname']);

			if (IA_MYSQLVER > 40)
			{
				$this->query("SET NAMES 'utf8'");
			}
		}
		elseif ($linkName)
		{
			$this->mLink = $linkName;
		}
	}

	public function setTable($tablename, $prefix = true)
	{
		// store for further usage in resetTable
		$this->_tableNameBackup = $this->mTable;

		$this->mTable = $prefix ? $this->mPrefix . $tablename : $tablename;
	}

	public function resetTable()
	{
		if (empty($this->_tableNameBackup))
		{
			return false;
		}

		// store for further usage in resetTable
		$this->mTable = $this->_tableNameBackup;
		$this->_tableNameBackup = '';
	}

	public function query($aSql, $aTable = '')
	{
		if (!$this->mLink)
		{
			$this->connect();
		}

		if (IA_DEBUG === 2)
		{
			$t = _time();
		}

		if ($aTable)
		{
			$this->setTable($aTable);
		}

		$result = mysqli_query($this->mLink, $aSql);

		if ($aTable)
		{
			$this->resetTable();
		}

		if (IA_DEBUG === 2)
		{
			$t = round(_time() - $t, 4);
			$GLOBALS['debug_sql'][] = array('sql' => $aSql, 'time' => $t/*, 'backtrace' => $backtrace*/);
		}

		if (!$result && mysqli_errno($this->mLink) != 2013)
		{
			$error = mysqli_error($this->mLink);

			ob_start();
			echo '<div style="border: 1px solid #F00; font: 12px verdana; width: 500px; margin: 0 auto; color: #F00; background-color: #EFEFEF; clear: both;font-weight:bold;">';
			echo "<div style=\"border-bottom: 1px solid #F00; padding: 10px;\"><strong>Error:</strong> " . $error . "</div>";
			echo "<div style=\"background-color: #EAEAEA; padding: 10px;\">" . $aSql . "</div>";
			echo '</div>';

			echo "<PRE style=\"color:red;background-color:white;padding:5px;font-size:16px;font-weight:bold;\">";
			echo "\n\n<h3>Debug backtrace:</h3>\n";
			debug_print_backtrace();
			echo "<hr />";
			echo "</PRE>";
			$data = ob_get_clean();
			if (IA_DEBUG)
			{
				echo $data;
			}
			else
			{
				echo "<PRE>" . mysqli_errno($this->mLink) . ": " . $error . "</PRE>";
			}
			trigger_error("Database query error: " . strip_tags($data), E_USER_ERROR);

			die("Fatal database error");
		}

		return $result;
	}

	public function getRow($aSql, $aValues = array())
	{
		$this->mysql_bind($aSql, $aValues);

		$result = false;

		$query = $this->query($aSql);
		if ($this->getNumRows($query) > 0)
		{
			$result = mysqli_fetch_assoc($query);
		}
		mysqli_free_result($query);

		return $result;
	}

	public function getAll($aSql, $aValues = array())
	{
		$this->mysql_bind($aSql, $aValues);

		$result = array();

		$query = $this->query($aSql);
		if ($this->getNumRows($query) > 0)
		{
			while ($row = mysqli_fetch_assoc($query))
			{
				$result[] = $row;
			}
		}
		mysqli_free_result($query);

		return $result;
	}

	public function getAssoc($aSql, $aValues = array(), $singleRow = false)
	{
		$this->mysql_bind($aSql, $aValues);

		$result = array();

		$query = $this->query($aSql);
		if ($this->getNumRows($query))
		{
			while ($row = mysqli_fetch_assoc($query))
			{
				$key = array_shift($row);
				if ($singleRow)
				{
					$result[$key] = $row;
				}
				else
				{
					$result[$key][] = $row;
				}
			}
		}
		mysqli_free_result($query);

		return $result;
	}

	public function getKeyValue($aSql, $aValues = array())
	{
		$this->mysql_bind($aSql, $aValues);

		$result = array();

		$query = $this->query($aSql);
		if ($this->getNumRows($query) > 0)
		{
			$array = mysqli_fetch_row($query);
			$asArray = false;
			if (count($array) > 2)
			{
				$result[$array[0]] = $array;
				$asArray = true;
			}
			else
			{
				$result[$array[0]] = $array[1];
			}

			while ($array = mysqli_fetch_row($query))
			{
				$result[$array[0]] = $asArray ? $array : $array[1];
			}
		}
		mysqli_free_result($query);

		return $result;
	}

	public function getOne($aSql, $aValues = array(), $aTable = '')
	{
		$this->mysql_bind($aSql, $aValues);

		if ($aTable)
		{
			$this->setTable($aTable);
		}
		$query = $this->query($aSql);
		if ($aTable)
		{
			$this->resetTable();
		}

		return ($this->getNumRows($query) > 0) ? $this->mysqli_result($query, 0, 0) : false;
	}

	public function mysqli_result($result, $row, $field = 0)
	{
		$result->data_seek($row);
		$datarow = $result->fetch_array();

		return $datarow[$field];
	}

	/**
	 * Returns the error text from the last MySQL function
	 *
	 * @return string
	 */
	public function getError()
	{
		return mysqli_error($this->mLink);
	}

	/**
	 * Returns the numerical value of the error message from previous MySQL operation
	 *
	 * @return int
	 */
	public function getErrorNumber()
	{
		return mysqli_errno($this->mLink);
	}

	/**
	 * Returns a string that represents various information on a connected database
	 *
	 * @param $type information type
	 *
	 * @return string
	 */
	public function getInfo($type)
	{
		$function = 'mysqli_get_' . $type;

		return $function($this->mLink);
	}

	public function getArray($aSql, $aValues = array())
	{
		$this->mysql_bind($aSql, $aValues);

		$r = $this->getAll($aSql);

		if (!$r)
		{
			return false;
		}

		$ret = array();

		$temp = array_keys($r[0]);
		$field = $temp[0];

		foreach($r as $r1)
		{
			$ret[] = $r1[$field];
		}

		return $ret;
	}

	public function exists($where, $aValues = array())
	{
		$this->mysql_bind($where, $aValues);

		$sql = "SELECT 1 FROM `" . $this->mTable;
		$sql .= "` WHERE " . $where;
		$r = $this->query($sql);

		return $this->getNumRows($r) > 0;
	}

	/**
	 * Returns the ID generated in the last query
	 *
	 * @return int
	 */
	public function getInsertId()
	{
		return mysqli_insert_id($this->mLink);
	}

	/**
	 * Returns a number of affected rows in previous MySQL operation
	 *
	 * @return int
	 */
	public function getAffected()
	{
		return mysqli_affected_rows($this->mLink);
	}

	/**
	 * Returns number of found rows of the previous query with SQL_CALC_FOUND_ROWS
	 * Note: this SQL function is MySQL specific
	 *
	 * @return int
	 */
	public function foundRows()
	{
		return (int)$this->getOne("SELECT FOUND_ROWS()");
	}

	/**
	 * Returns a number of rows in result
	 *
	 * @param resource $resource query resource
	 *
	 * @return int
	 */
	public function getNumRows($resource)
	{
		if (is_object($resource))
		{
			return mysqli_num_rows($resource);
		}

		return 0;
	}

	/**
	 * Retrieves the number of fields from a query
	 *
	 * @param $result query result
	 *
	 * @return int
	 */
	public function getNumFields($result)
	{
		return mysqli_num_fields($result);
	}

	/**
	 * Returns an array of objects which contains field definition information or FALSE if no field information is available
	 *
	 * @param $result query result
	 *
	 * @return array|bool
	 */
	public function getFieldNames($result)
	{
		return mysqli_fetch_fields($result);
	}

	/**
	 * Fetches one row of data from the result set and returns it as an enumerated array
	 *
	 * @param $result query result
	 *
	 * @return array|null
	 */
	public function fetchRow($result)
	{
		return mysqli_fetch_row($result);
	}

	public function close($aConn = null)
	{
		if (null == $aConn)
		{
			$aConn = $this->mLink;
		}

		return mysqli_close($aConn);
	}

	public function scanForId($str, $f='id')
	{
		$id = false;
		if (preg_match("/.?" . $f . ".?\s*=\s*'?(\d+)/", $str, $m))
		{
			$id = (int)$m[1];
		}

		return $id;
	}

	/**
	 * Provides information about the columns in a table
	 *
	 * @param null $tableName table name
	 * @param bool $addPrefix avoid prefix addition if false
	 * @return array
	 */
	public function describe($tableName = null, $addPrefix = false)
	{
		if (empty($tableName))
		{
			$tableName = $this->mTable;
		}
		else
		{
			$tableName = ($addPrefix ? $this->mPrefix : '') . $tableName;
		}

		$sql = sprintf('DESCRIBE `%s`', $tableName);

		return $this->getAll($sql);
	}

	public function onefield($field, $where = '', $values = array(), $start=0, $limit=null)
	{
		if (false !== strpos($field, ","))
		{
			return false;
		}

		$r = $this->get("all", $field, $where, $values, $start, $limit);

		if (!$r)
		{
			return false;
		}

		$ret = array();
		$field = str_replace("`", "", $field);
		foreach($r as $r1)
		{
			$ret[] = $r1[$field];
		}

		return $ret;
	}

	public function all($fields, $where='', $values = array(), $start=0, $limit=null)
	{
		return $this->get("all", $fields, $where, $values, $start, $limit);
	}

	/**
	 * Returns associative array of rows, first column of the query is used as a key
	 *
	 * @param string $fields fields to be selected
	 * @param string $condition condition for the selection
	 * @param string|null $tableName table name to select records from, null uses current set table
	 * @param int $start start position
	 * @param int|null $limit number of records to be returned
	 *
	 * @return array
	 */
	public function assoc($fields = '*', $condition = '', $tableName = null, $start = 0, $limit = null)
	{
		if (is_null($tableName))
		{
			$result = $this->get('assoc', $fields, $condition, $start, $limit);
		}
		else
		{
			$this->setTable($tableName);
			$result = $this->get('assoc', $fields, $condition, array(), $start, $limit);
			$this->resetTable();
		}

		return $result;
	}

	public function keyvalue($fields, $where = '', $values = array(), $start = 0, $limit=null, $calcRows=false)
	{
		return $this->get('keyval', $fields, $where, $values, $start, $limit, $calcRows);
	}

	public function row($fields, $where = '', $values = array(), $start = 0, $limit = null)
	{
		return $this->get("row", $fields, $where, $values, $start, 1);
	}

	public function one($field, $where = '', $values = array(), $start=0, $limit=null)
	{
		$x = $this->row($field, $where, $values, $start, 1);

		return is_bool($x) ? $x : array_shift($x);
	}

	public function get($type, $fields, $where = '', $values = array(), $start = 0, $limit = false)
	{
		if (is_array($fields))
		{
			$fields = implode("`,`", $fields);
			$fields = "`" . $fields . "`";
		}

		$this->mysql_bind($where, $values);

		if (!empty($where))
		{
			$where = " WHERE " . $where;
			if ('row' == $type)
			{
				$where .= " LIMIT 0, 1";
			}
			elseif ($limit)
			{
				$where .= " LIMIT " . $start . ", " . $limit;
			}
		}

		$q = "SELECT " . $fields . " FROM `" . $this->mTable . "` " . $where;

		switch($type)
		{
			case 'all':
				return $this->getAll($q);
			case 'keyval':
				return $this->getKeyValue($q);
			case 'assoc':
				return $this->getAssoc($q, array(), true);
			default:
				return $this->getRow($q);
		}

		return $this->getRow($q);
	}

	public function update($fields, $where = '', $values = array(), $addit = null)
	{
		if (empty($this->mTable))
		{
			return false;
		}

		$this->mysql_bind($where, $values);

		if (!empty($where))
		{
			$where = "WHERE " . $where;
		}
		elseif (isset($fields['id']))
		{
			$where = "WHERE `id` = '{$fields['id']}'";
			unset($fields['id']);
		}

		$chain = array();

		if (!empty($fields))
		{
			foreach($fields as $field => $value)
			{
				$value = $this->escape_sql($value);
				$chain[] = "`{$field}` = '{$value}'";
			}
		}

		if (!empty($addit))
		{
			foreach($addit as $field => $value)
			{
				$value = $this->escape_sql($value);

				$chain[] = "`{$field}` = {$value}";
			}
		}

		if (empty($chain))
		{
			return false;
		}

		$this->query("UPDATE `{$this->mTable}` SET " . implode(',', $chain) . " " . $where);

		$return = $this->getAffected();

		return $return;
	}

	public function insert($fields, $addit = null)
	{
		if (empty($this->mTable))
		{
			return false;
		}

		// not for multiple insert
		$f = array_slice($fields, 0, 1);
		if (!is_array(array_shift($f)))
		{
			$fields = array($fields);
		}

		// fields list
		$flds = '`' . implode("`,`", array_keys($fields[0])) . '`';

		// additional values will be appended to the values
		$additValues = '';
		if (!empty($addit))
		{
			foreach($addit as $field => $value)
			{
				$value = $this->escape_sql($value);

				$flds .= ",`$field`";
				$additValues .= ",$value";
			}
			$additValues = substr($additValues, 1);
		}

		$query = "INSERT INTO `" . $this->mTable . "` (" . $flds . ") VALUES";

		$vals = '';

		foreach($fields as $i => $row)
		{
			$vals .= '(';
			$v = '';

			foreach($row as $r)
			{
				$r = $this->escape_sql($r);
				$v .= "'$r',";
			}

			if (empty($additValues))
			{
				$v = substr($v, 0, -1);
			}

			$v .= $additValues;

			$vals .= $v . '),';
		}

		$vals = substr($vals, 0, -1);

		$this->query($query . $vals);

		return $this->getInsertId();
	}

	public function delete($where = '', $values = array(), $truncate=false)
	{
		if (empty($where))
		{
			trigger_error(__CLASS__ . '::delete Parameters required "where clause"). All rows deletion is restricted', E_USER_ERROR);
		}

		$this->mysql_bind($where, $values);

		$sql = "DELETE FROM `" . $this->mTable . "` WHERE " . $where;

		$this->query($sql);

		return $this->getAffected();
	}

	/*
	 * a simple named binding function for queries that makes SQL more readable:
	 * $sql = "SELECT * FROM users WHERE user = :user AND password = :password";
	 * mysql_bind($sql, array('user' => $user, 'password' => $password));
	 * mysql_query($sql);
	 */
	public function mysql_bind(&$sql, $vals)
	{
		if (!empty($vals) && is_array($vals))
		{
			foreach ($vals as $name => $val)
			{
				$sql = str_replace(":$name", "'" . $this->escape_sql($val) . "'", $sql);
			}
		}
	}

	public function escape_sql($sql)
	{
		return mysqli_real_escape_string($this->mLink, $sql);
	}

	public function split_sql($sql)
	{
		$out = array();

		$sql = trim($sql);

		if (substr($sql, -1) != ";")
		{
			$sql .= ";";
		}

		preg_match_all("/(?>[^;']|(''|(?>'([^']|\\')*[^\\\]')))+;/ixU", $sql, $matches, PREG_SET_ORDER);

		foreach ($matches as $match)
		{
			$out[] = substr($match[0], 0, -1);
		}

		return $out;
	}

	public function strip_sql_comment($sql)
	{
		$t = substr($sql, 0, 2);

		if ($t == '--')
		{
			$sql = '';
		}

		if ($t == '/*')
		{
			$sql = '';
		}

		if ($t[0] == '#')
		{
			$sql = '';
		}

		return $sql;
	}

	public function order_by_rand($max, $id_name = '`id`', $pieces = 12, $delimeter = 100)
	{
		$where = '';
		$pieces = max($pieces, 6);
		$delimeter = max($delimeter, 10);
		if ($pieces * $delimeter > 5000)
		{
			$pieces = 12;
			$delimeter = 100;
		}
		if ($max > 2000)
		{
			$piece_first = ceil($max / $pieces);
			$piece_second = ceil($piece_first / $delimeter);
			$where = array();
			for($i = 0; $i < $pieces; $i++)
			{
				$start = mt_rand(0, $piece_second) * $delimeter + $piece_first * $i;
				$end = $start + $delimeter;
				$where[] = '(' . $id_name . ' >= ' . $start . ' AND ' . $id_name . ' <= ' . $end . ')';
			}
			$where = 'AND (' . implode(' OR ', $where) . ')';
		}

		return $where;
	}

	public function get_column_names($table)
	{
		$fields = array();

		$sql = "SHOW COLUMNS FROM `{$this->mPrefix}{$table}`";

		if (($result = $this->query($sql)))
		{
			while ($row = mysqli_fetch_array($result))
			{
				$fields[] = $row['Field'];
			}
		}

		return $fields;
	}
}
