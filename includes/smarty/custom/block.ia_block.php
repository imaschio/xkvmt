<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_block_ia_block($params, $content, &$smarty)
{
	if (!empty($content))
	{
		$smarty->assignGlobal('caption', isset($params['caption']) ? $params['caption'] : '');
		$smarty->assignGlobal('block', isset($params['block']) ? $params['block'] : array());
		$smarty->assignGlobal('collapsible', isset($params['collapsible']) ? $params['collapsible'] : 0);
		$smarty->assignGlobal('collapsed', isset($params['collapsed']) ? $params['collapsed'] : 0);
		$smarty->assignGlobal('id', isset($params['id']) ? $params['id'] : null);
		$smarty->assignGlobal('style', isset($params['style']) ? $params['style'] : '');
		$smarty->assignGlobal('_block_content_', $content);

		$smarty->display('block.tpl');
	}
}
