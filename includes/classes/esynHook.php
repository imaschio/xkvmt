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
 * esynHook
 *
 * @uses eSyndiCat
 * @package
 * @version $id$
 */
class esynHook extends eSyndiCat
{
	/**
	 * mTable
	 *
	 * @var string
	 * @access public
	 */
	var $mTable = 'hooks';

	/**
	 * mLink
	 *
	 * @var mixed
	 * @access public
	 */
	var $mLink 	= null;

	/**
	 * esynHook
	 *
	 * @access public
	 * @return void
	 */
	function esynHook()
	{
		parent::eSyndiCat();
	}
}
