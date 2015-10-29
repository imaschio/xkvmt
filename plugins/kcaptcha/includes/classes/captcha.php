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
	var $config = null;

	function getImage()
	{
		global $esynConfig;
		global $esynI18N;

		$url = IA_URL . "controller.php?plugin=kcaptcha";
		$num_chars = $esynConfig->getConfig('captcha_num_chars');


		$html = "<img id=\"captcha_image_1\" src=\"{$url}\" onclick=\"$('#captcha_image_1').attr('src', '{$url}&amp;h=' + Math.random())\" title=\"{$esynI18N['redraw_captcha']}\" alt=\"captcha\" style=\"cursor:pointer; margin-right: 10px;\" align=\"left\" />";
		$html .= "{$esynI18N['text_captcha']}<br />{$esynI18N['redraw_captcha']}<br />";
		$html .= "<input type=\"text\" class=\"span1\" name=\"security_code\" size=\"{$num_chars}\" maxlength=\"{$num_chars}\" id=\"securityCode\" />";

		return $html;
	}

	function validate()
	{
		global $esynConfig;

		$sc1 = $_POST['security_code'];
		$sc2 = $_SESSION['pass'];
		
		$func = $esynConfig->getConfig('captcha_case_sensitive') ? "strcmp" : "strcasecmp";

		if (empty($_SESSION['pass']) || $func($sc1, $sc2) !== 0)
		{
			return false;
		}

		$_SESSION['pass'] = '';

		return true;
	}

	function getPreview()
	{
		$url = IA_URL . "controller.php?plugin=kcaptcha";

		$html = "<img id=\"captcha_image_1\" src=\"{$url}\" onclick=\"$('#captcha_image_1').attr('src', '{$url}&amp;h='+Math.random())\" title=\"\" alt=\"captcha\" style=\"cursor:pointer; margin-right: 10px;\" align=\"left\" />";

		return $html;
	}
}