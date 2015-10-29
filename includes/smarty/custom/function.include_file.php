<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {include_file} plugin
 *
 * Type:     function<br>
 * Name:     include_file<br>
 * Purpose:  include javascript|css files
 * @author  Sergey Ten <sergei.ten at gmail dot com>
 * @param array
 * @param Smarty
 * @return string
 *
 */
function smarty_function_include_file($params, &$smarty)
{
	$base = IA_URL;

	if (isset($params['base']) && !empty($params['base']))
	{
		$params['base'] = str_replace('TMPL', $smarty->tmpl, $params['base']);

		$base .= $params['base'];
	}

	if (isset($params['js']) && !empty($params['js']))
	{
		foreach(explode(',', $params['js']) as $js)
		{
			$js = trim($js);
			$js = str_replace(IA_HOME, '', $js);
			$path = (false !== stristr($js, IA_URL)) ? $js : $base . $js;
			echo "<script type=\"text/javascript\" src=\"{$path}.js\"></script>\n";
		}
	}

	if (isset($params['css']) && !empty($params['css']))
	{
		foreach(explode(',', $params['css']) as $css)
		{
			$css = trim($css);
			$css = str_replace(IA_HOME, '', $css);

			$path = (false !== stristr($css, IA_URL)) ? $css : $base . $css;
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$path}.css\" />\n";
		}
	}
}

?>
