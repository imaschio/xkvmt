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
 * The class for including the UTF8 function
 */
class esynUtf8
{
	public static function loadUTF8Core()
	{
		static $loaded = false;

		$type = 'native';

		if ($loaded)
		{
			return false;
		}

		$p = IA_INCLUDES . 'phputf8' . IA_DS;

		if (extension_loaded('mbstring'))
		{
			mb_internal_encoding('UTF-8');

			$type = 'mbstring';
		}

		require_once $p . $type . IA_DS . 'core.php';

		$loaded = true;

		return true;
	}

	public static function loadUTF8Function($fn)
	{
		$p = IA_INCLUDES . 'phputf8' . IA_DS . $fn . ".php";

		if (file_exists($p))
		{
			require_once $p;

			if (function_exists($fn))
			{
				return true;
			}

			trigger_error("No such function from phputf8 package: '$fn'", E_USER_ERROR);
		}
	}

	public static function loadUTF8Util()
	{
		if (func_num_args() == 0)
		{
			return false;
		}

		foreach(func_get_args() as $fn)
		{
			require_once IA_INCLUDES . 'phputf8' . IA_DS . 'utils' . IA_DS . $fn . ".php";
		}
	}
}
