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
 * iaBreadcrumb
 *
 * @package core
 * @version $id$
 */
class esynBreadcrumb
{
	const POSITION_ROOT = -1000;
	const POSITION_FIRST = -100;
	const POSITION_LAST = 100;

	const BEHAVE_REPLACE = true;
	const BEHAVE_INSERT_AFTER = false;
	const BEHAVE_APPEND = 2;

	/**
	 * Current breadcrumb list
	 * @var array
	 */
	protected static $_list;

	/**
	 * Set root breadcrumb
	 * @static
	 * @param string $caption
	 * @param string $url
	 * @return void
	 */
	public static function root($caption = '', $url = '')
	{
		self::set($caption, $url, self::POSITION_ROOT, self::BEHAVE_REPLACE);
	}

	/**
	 * Add in first section
	 * @static
	 * @param string $caption
	 * @param string $url
	 * @return void
	 */
	public static function first($caption = '', $url = '')
	{
		self::replace($caption, $url, self::POSITION_FIRST);
	}

	/**
	 * Add in last section
	 * @static
	 * @param string $caption
	 * @param string $url
	 * @return void
	 */
	public static function toEnd($caption = '', $url = '')
	{
		self::insert($caption, $url, self::POSITION_LAST);
	}

	/**
	 * Replace all last section
	 * @static
	 * @param string $caption
	 * @param string $url
	 * @return void
	 */
	public static function replaceEnd($caption = '', $url = '')
	{
		self::replace($caption, $url, self::POSITION_LAST);
	}

	/**
	 * Add in middle section
	 * @static
	 * @param string $caption
	 * @param string $url
	 * @return void
	 */
	public static function add($caption = '', $url = '')
	{
		self::set($caption, $url, false);
	}

	public static function addArray($items)
	{
		if (is_array($items))
		{
			foreach ($items as $item)
			{
				self::add($item['caption'], $item['url']);
			}
		}
	}

	/**
	 * Insert in some index
	 * @static
	 * @param string $caption
	 * @param string $url
	 * @param bool $index
	 * @return void
	 */
	public static function insert($caption = '', $url = '', $index = false)
	{
		self::set($caption, $url, $index, self::BEHAVE_INSERT_AFTER);
	}

	/**
	 * Replace in some index
	 * @static
	 * @param string $caption
	 * @param string $url
	 * @param bool $index
	 * @return void
	 */
	public static function replace($caption = '', $url = '', $index = false)
	{
		self::set($caption, $url, $index, self::BEHAVE_REPLACE);
	}

	public static function remove($index)
	{
		if (isset(self::$_list[$index]))
		{
			unset(self::$_list[$index]);
			return true;
		}

		return false;
	}

	public static function clear()
	{
		self::$_list = array();
	}

	public static function render()
	{
		$items = self::$_list;
		if ($items)
		{
			ksort($items);
			$list = array();
			foreach ($items as $val)
			{
				if (!isset($val['caption']))
				{
					foreach($val as $v)
					{
						$list[] = $v;
					}
				}
				else
				{
					$list[] = $val;
				}
			}

			return $list;
		}

		return false;
	}

	/**
	 * Add element to breadcrumb
	 * @static
	 * @param string $caption
	 * @param string $url
	 * @param bool $index
	 * @param int $replace - 2: in the end of list if exists, true: replace, false: insert between(after this element)
	 * @return void
	 */
	static private function set($caption = '', $url = '', $index = false, $replace = self::BEHAVE_APPEND)
	{
		if ($index === false)
		{
			$index = count(self::$_list) + 1;
		}
		$item = array('caption' => $caption, 'url' => $url);

		if ($replace === self::BEHAVE_REPLACE)
		{
			self::$_list[$index] = $item;
		}
		elseif ($replace === self::BEHAVE_INSERT_AFTER && isset(self::$_list[$index]))
		{
			$next = 1;
			if (isset(self::$_list[$index]['caption']))
			{
				self::$_list[$index] = array(self::$_list[$index]);
			}
			while (isset(self::$_list[$index][$next]))
			{
				$next++;
			}
			self::$_list[$index][$next] = $item;
		}
		else
		{
			if ($replace == self::BEHAVE_APPEND)
			{
				while (isset(self::$_list[$index]))
				{
					$index++;
				}
			}
			self::$_list[$index] = $item;
		}
	}
}
