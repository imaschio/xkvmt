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

class captcha
{
	var $captcha = null;

	// Get a key from http://recaptcha.net/api/getkey
	var $publickey = "";
	var $privatekey = "";

	// the response from reCAPTCHA
	var $resp = null;
	// the error code from reCAPTCHA, if any
	var $error = null;

	function captcha()
	{
		global $esynConfig;

		require_once(dirname(__FILE__).IA_DS.'..'.IA_DS.'recaptcha'.IA_DS.'recaptchalib.php');

		$this->publickey = $esynConfig->getConfig('recaptcha_publickey');
		$this->privatekey = $esynConfig->getConfig('recaptcha_privatekey');
		$this->theme = $esynConfig->getConfig('recaptcha_theme');
	}

	function getImage()
	{
		return recaptcha_get_html($this->publickey, $this->error, $this->theme);
	}

	function validate()
	{
		$resp = recaptcha_check_answer($this->privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

		if ($resp->is_valid)
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