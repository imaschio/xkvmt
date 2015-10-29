<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {include_captcha} plugin
 *
 * Type:     function<br>
 * Name:     include_captcha<br>
 * Purpose:  include captcha
 * @author  Sergey Ten <sergei.ten at gmail dot com>
 * @param array
 * @param Smarty
 * @return string
 *
 */
function smarty_function_include_captcha($params, &$smarty)
{
	if(!isset($params['name']) || empty($params['name']))
	{
		echo 'Captcha name is empty';

		return false;
	}

	require_once(IA_PLUGINS.$params['name'].IA_DS.'includes'.IA_DS.'classes'.IA_DS.'captcha.php');

	$captcha = new captcha();

	echo $captcha->getImage();
}

?>
