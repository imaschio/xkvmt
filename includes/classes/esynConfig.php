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
 * esynConfig
 *
 * @uses esynDatabase
 * @package
 */
class esynConfig extends esynDatabase
{
	var $config;
	var $mCacher = null;

	function esynConfig($arg = false)
	{
		$this->mConfig['dbhost'] = IA_DBHOST;
		$this->mConfig['dbuser'] = IA_DBUSER;
		$this->mConfig['dbpass'] = IA_DBPASS;
		$this->mConfig['dbname'] = IA_DBNAME;
		$this->mConfig['dbport'] = IA_DBPORT;
		$this->mConfig['prefix'] = $this->mPrefix = IA_DBPREFIX;

		$this->mTable = $this->mPrefix.$this->mTable;

		$this->connect();

		$this->mCacher = new esynCacher();

		$this->config = (false == $arg) ? array() : $arg;

		if (empty($this->config))
		{
			$this->config = $this->mCacher->get("config", 604800, true);

			if (IA_DEBUG > 0 || empty($this->config))
			{
				$this->setTable('config');
				$this->config = $this->keyvalue("`name`,`value`", "`type` NOT IN('divider')");
				$this->resetTable();

				$this->mCacher->write("config", $this->config);
			}
		}
	}

	/**
	 * getConfig returns config value by a given key
	 *
	 * @param string $key config key
	 * @param boolean $db [optional] true: value fetched from db, false: value fetched from cached file
	 *
	 * @return string
	 */
	function getConfig($key, $db = false, $default = false)
	{
		if (!$db)
		{
			if (isset($this->config[$key]))
			{
				return $this->config[$key];
			}
			else
			{
				//cronjob don't work
				//e('<b>'.$key.'</b> not exists in configs array');
			}
		}

		$this->setTable("config");
		$value = $this->one("`value`", "`name` = :key", array('key' => $key));
		$this->resetTable();
		if (!$value)
		{
			//cronjob don't work
			//e('<b>'.$key.'</b> not exists in configs table');
			$value = $default;
		}
		$this->config[$key] = $value;

		return $this->config[$key];
	}

	function gC($key, $db = false, $default = false)
	{
		return $this->getConfig($key, $db, $default);
	}

	/**
	 * setConfig method modifies configuration array
	 *
	 * @param string $key configuration array key
	 * @param object $value configuration array value
	 * @param object $db [optional] true: writes to database, false: changes config array for execution only
	 *
	 * @return bool
	 */
	function setConfig($key, $value, $db = false)
	{
		$result = true;

		if ($db && !is_scalar($value))
		{
			trigger_error("Couldn't store non scalar value to the database".__CLASS__."::set", E_USER_ERROR);
		}

		$this->config[$key] = $value;

		if ($db)
		{
			$this->setTable("config");
			$result = (bool)$this->update(array("value" => $value), "`name` = :key", array('key' => $key));
			$this->resetTable();

			$this->mCacher->remove("config");
			$this->mCacher->remove("intelli.lang");
			$this->mCacher->remove("intelli.admin.lang");
			$this->mCacher->remove("intelli.config");
		}

		return $result;
	}

	static function instance($arg = false)
	{
		static $s;

		if ($s == null)
		{
			$s = new esynConfig($arg);
		}
		elseif ($arg)
		{
			$s->config = $arg;
		}

		return $s;
	}
}
