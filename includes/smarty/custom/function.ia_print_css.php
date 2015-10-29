<?php
/**
 * Includes CSS file in template
 * 
 * @param array $params parameters to include: $params['files'] - array of filenames
 */
function smarty_function_ia_print_css($params, &$smarty)
{
	return $smarty->smarty->add_css($params);
}
