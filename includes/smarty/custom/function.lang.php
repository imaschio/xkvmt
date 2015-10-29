<?php
/**
 * gI18n
 * 
 * @param string $params
 */
function smarty_function_lang($params)
{
	if(!isset($params['key'])) $params['key'] = '';
	if(!isset($params['default'])) $params['default'] = '';
	
	return _t($params['key'], $params['default']);
}
?>
