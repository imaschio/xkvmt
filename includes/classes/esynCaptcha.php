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

global $esynConfig;

require_once IA_PLUGINS . $esynConfig->getConfig('captcha_name') . IA_DS . 'includes' . IA_DS . 'classes' . IA_DS . 'captcha.php';

class esynCaptcha extends captcha
{
	function getImage()
	{
		return parent::getImage();
	}

	function validate()
	{
		return parent::validate();
	}
}
