<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {ia_hooker} function plugin
 *
 * Type:     function<br>
 * Name:     esyn_hooker<br>
 * Purpose:  calls registered hooks (work only as part of eSyndiCat installation starting from eSyndiCat Pro 2.2 version<br>
 * @author B*a*k*y*t N*i*y*a*z*o*v <bakytn at gmail dot com>
 * @param array
 * @param Smarty
 */
function smarty_function_ia_hooker($params, &$smarty)
{
	if (!isset($params['name']))
 	{
  		$smarty->trigger_error("ia_hooker: missing 'name' parameter");
    	return;
  	}

	if(empty($smarty->mHooks[$params['name']]) || !array_key_exists($params['name'], $smarty->mHooks))
	{
    	return false;
	}

	$params['type'] = (isset($params['type']) && !empty($params['type'])) ? $params['type'] : 'php';

	foreach($smarty->mHooks[$params['name']] as $hook)
	{
		if (IA_DEBUG === 2)
		{
			$t = _time();
		}

		$hook['type'] = (in_array($hook['type'], array('php', 'html', 'plain', 'smarty'))) ? $hook['type'] : 'php';

		if ('php' == $hook['type'])
		{
			if(!empty($hook['file']))
			{
				$files = explode(',', $hook['file']);

				if (!empty($files))
				{
					foreach($files as $file)
					{
						$file = IA_HOME . $file;
				
						if (file_exists($file) && is_file($file))
						{
							require_once($file);
						}
					}
				}
			}
			
			eval($hook['code']);
		}
		elseif ('smarty' == $hook['type'])
		{
			require_once SMARTY_DIR . "custom" . IA_DS . "function.eval.php";
			
			if (!empty($hook['file']))
			{
				$files = explode(',', $hook['file']);
				
				if (!empty($files))
				{
					foreach($files as $file)
					{
						$file = IA_HOME . $file;

						if (file_exists($file) && is_file($file))
						{
							echo $smarty->fetch($file);
						}
					}
				}
			}

			if (!empty($hook['code']))
			{
				echo smarty_function_eval(array('var' => $hook['code']), $smarty);
			}
		}
		elseif ('html' == $hook['type'])
		{
			echo $hook['code'];
		}
		elseif ('plain' == $hook['type'])
		{
			echo htmlspecialchars($hook['code']);
		}

		if (IA_DEBUG === 2)
		{
			$t = round(_time() - $t, 4);

			$a = array(
				'name'		=> $params['name'],
				'plugin'	=> $hook['plugin'],
				'code'		=> $hook['code'],
				'type'		=> $hook['type'],
				'time'		=> $t
			);
			
			d($a, '', 'hook_debug');
		}
	}
}
