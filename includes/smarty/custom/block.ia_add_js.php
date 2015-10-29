<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_block_ia_add_js($params, $content, &$smarty)
{
    if (is_null($content))
	{
        return;
    }

    $order = isset($params['order']) ? $params['order'] : 4;

	if (!isset($_SESSION['js_files'][$order]))
	{
		$_SESSION['js_files'][$order] = array();
	}

    $_SESSION['js_files'][$order][] = $content;
}
