<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty clearemptyblocks outputfilter plugin
 *
 * File:     outputfilter.clearemptyblocks.php<br>
 * Type:     outputfilter<br>
 * Name:     clearemptyblocks<br>
 * Date:     Nov 21, 2007<br>
 * Purpose:  clear output content which generates by Esyndicat script
 *           Each block should begins with comment <!--__b_{BLOCK_ID}-->
 *           and ends with <!--__e_{BLOCK_ID}-->
 *           Each block content should begins with comment <!--__b_c_{BLOCK_ID}-->
 *           and ends with <!--__e_c_{BLOCK_ID}-->
 * Install:  Drop into the plugin directory, call
 *           <code>$smarty->load_filter('output','clearemptyblocks');</code>
 *           from application.
 * @author   Adigamov Ruslan <radigamov at intelliants dot com>
 * @author Contributions from Bastov Pavel <pbastov@intelliants.com>
 * @version  1.0
 * @param string
 * @param Smarty
 */
function smarty_outputfilter_clearemptyblocks($source, &$smarty)
{
	$sides = array('l' => 'left', 'r' => 'right');

	$pattern = '/<!--__b_(\d+).*?-->.*?<!--__b_c_\1-->(.*?)<!--__e_c_\1-->.*?<!--__e_\1-->/s';
	$pattern_block_contaier = '/<!--__s_{side}-->(.*?)<!--__se_{side}-->/s';
	$pattern_blocks_content = '/<!--__s_{side}_c-->(.*?)<!--__s_{side}_ce-->/s';

	// clear empty blocks
	if (preg_match_all($pattern, $source, $matches))
	{
		if ($matches)
		{
			foreach ($matches[0] as $key => $value)
			{
				$content = trim($matches[2][$key]);

				if (empty($content))
				{
					$source = str_replace($value, '', $source);
				}
			}
		}
	}

	// clear empty block containers
	foreach ($sides as $side_k => $side)
	{
		$tmp_pattern = str_replace('{side}', $side_k, $pattern_blocks_content);

		if (preg_match_all($tmp_pattern, $source, $matches))
		{
			if ($matches)
			{
				foreach ($matches[1] as $key => $value)
				{
					$content = trim($value);

					if (empty($content))
					{
						$tmp_pattern = str_replace('{side}', $side_k, $pattern_block_contaier);

						$source = preg_replace($tmp_pattern, '', $source);
					}
				}

			}
		}
	}

	return $source;
}
