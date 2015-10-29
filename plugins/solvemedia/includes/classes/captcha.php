<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.0.4
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
 *	 Copyright 2007-2011 Intelliants LLC
 *	 http://www.intelliants.com/
 *
 ******************************************************************************/

class captcha
{
	var $captcha = null;

	// Get a key from http://recaptcha.net/api/getkey
	var $ckey = '';
	var $vkey = '';
	var $hkey = '';

	// the response from reCAPTCHA
	var $resp = null;
	// the error code from reCAPTCHA, if any
	var $error = null;

	function captcha()
	{
		global $esynConfig;

		require_once dirname(__FILE__) . IA_DS . 'solvemedialib.php';

		$this->ckey = $esynConfig->getConfig('solvemedia_ckey');
		$this->vkey = $esynConfig->getConfig('solvemedia_vkey');
		$this->hkey = $esynConfig->getConfig('solvemedia_hkey');
	}

	function getImage()
	{
		return solvemedia_get_html($this->ckey);	//outputs the widget
	}

	function validate()
	{
		$solvemedia_response = solvemedia_check_answer($this->vkey,
					$_SERVER["REMOTE_ADDR"],
					$_POST["adcopy_challenge"],
					$_POST["adcopy_response"],
					$this->hkey);

		if ($solvemedia_response->is_valid)
		{
			return true;
		}

		return false;
	}

	function getPreview()
	{
		return $this->getImage();
	}
}