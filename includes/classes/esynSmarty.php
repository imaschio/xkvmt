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

if (!defined('SMARTY_DIR'))
{
	define('SMARTY_DIR', IA_INCLUDES . 'smarty' . IA_DS);
}
require_once SMARTY_DIR . 'SmartyBC.class.php';

/**
 * esynSmarty
 *
 * Implements class-connector between script code and smarty class
 *
 * @uses Smarty
 * @package
 * @version $id$
 */
class esynSmarty extends SmartyBC
{
	public $pagination = false;

	protected $_header = true;

	protected $_footer = true;

	protected $_css_files = array();

	protected $_js_files = array();

	protected $_plugin_files = array();

	/**
	 * Description of the Variable
	 * @var		mixed
	 * @access	public
	 */
	var $mHooks = array();

	/**
	 * Description of the Variable
	 * @var		mixed
	 * @access	public
	 */
	var $tmpl = '';

	function __construct($tmpl, &$config = array())
	{
		parent::__construct();

		$this->tmpl = $tmpl;
		$this->setTemplateDir(array(IA_TEMPLATES.$tmpl.IA_DS, IA_TEMPLATES.'common'.IA_DS));

		// create template directory if it does not exist
		if (!is_dir(IA_TMP.$tmpl.IA_DS))
		{
			esynUtil::mkdir( IA_TMP . $tmpl );
		}

		if (!is_writable(IA_TMP.$tmpl.IA_DS))
		{
			trigger_error("Directory Permissions Error | dir_permissions_error | The '" . IA_TMP_NAME . "/{$tmpl}' directory is not writable. Please set writable permissions.", E_USER_ERROR);
		}

		$this->setCompileDir(IA_TMP . $tmpl . IA_DS);
		$this->setConfigDir('configs' . IA_DS);
		$this->setCacheDir(IA_TMP . 'smartycache' . IA_DS);

		$this->setPluginsDir(array(SMARTY_DIR . 'custom' . IA_DS, SMARTY_DIR . 'plugins' . IA_DS));

		if (IA_CACHING)
		{
			if (!is_dir($this->getCacheDir()))
			{
				$result = esynUtil::mkdir($this->getCacheDir());

				if (!$result)
				{
					trigger_error("Directory Creation Error | tmp_dir_permissions | Can not create the '" . IA_TMP_NAME . "/smartycache' directory.", E_USER_ERROR);
				}
			}

			if (!is_writable($this->getCacheDir()))
			{
				trigger_error("Directory Permissions Error | dir_permissions_error | The '" . IA_TMP_NAME . "/smartycache' directory is not writable. Please set writable permissions.", E_USER_ERROR);
			}
		}

		$this->caching = IA_CACHING;
		$this->cache_modified_check = true;
		$this->debugging = false;

		$this->registerPlugin('function', "convertStr", array("esynUtil", "convertStr"));

		if (!defined('IA_IN_ADMIN'))
		{
			unset($_SESSION['js_files']);

			// init media
			$this->_plugin_files = array(
				'jquery' => 'js:js/jquery/jquery, js:js/utils/sessvars',
				'intelli' => 'js:js/intelli/intelli, js:' . IA_TMP_NAME . '/cache/intelli.config, js:' . IA_TMP_NAME . '/cache/intelli.lang.' . $config['lang'],
				'footer' => 'js:js/intelli/intelli.minmax, js:js/intelli/intelli.common, js:js/frontend/footer, js:_IA_TPL_app',
				'bootstrap' => 'css:_IA_TPL_css/iabootstrap, css:_IA_TPL_css/iabootstrap-responsive, js:_IA_TPL_iabootstrap.min',
				'bootstrap-awesome' => 'css:_IA_TPL_css/iabootstrap, css:_IA_TPL_css/iabootstrap-responsive, js:_IA_TPL_iabootstrap.min, css:js/bootstrap/css/font-awesome.min',
				'managemode' => 'js:js/jquery/plugins/jquery.color.min, js:js/jquery/ui/jquery-ui-1.9.2.custom.min, js:js/frontend/visual-mode, css:_IA_TPL_css/managemode'
			);
			$this->registerPlugin('function', 'ia_add_media', array($this, "add_media"));

			$this->registerPlugin('function', 'ia_parse_block', array($this, "_ia_parse_block"));
		}
	}

	public function display($resource_name = null, $cache_id = null, $compile_id = null, $parent = null)
	{
		if ($this->templateExists($resource_name))
		{
			$resource_file = $resource_name;
		}
		elseif (file_exists($this->getTemplateDir(0).$resource_name))
		{
			$resource_file = $this->getTemplateDir(0).$resource_name;
		}
		else
		{
			$resource_file = IA_TEMPLATES . 'common' . IA_DS . $resource_name;
		}

		if (empty($resource_file))
		{
			$error = "This file can not be found in template directory: {$resource_name}";
			$this->assign('error', $error);

			parent::display($this->getTemplateDir(0) . 'error.tpl', $cache_id, $compile_id, $parent);
		}
		else
		{
			if (empty($_SESSION['frontendManageMode']))
			{
				$this->loadFilter('output', 'clearemptyblocks');
			}

			// display template
			if (defined('IA_IN_ADMIN') && IA_IN_ADMIN)
			{
				parent::display($resource_file, $cache_id, $compile_id, $parent);
			}
			elseif ($this->_header && $this->_footer)
			{
				// process content
				$this->assignGlobal('_content_', $this->fetch($resource_file));

				if (!defined('IN_HEADER'))
				{
					define('IN_HEADER', true);
				}

				// process css files
				$this->assignGlobal('css_files', $this->_css_files);

				// process js files
				$this->assignGlobal('js_files', $this->_js_files);

				$this->assignGlobal('breadcrumb', esynBreadcrumb::render());

				parent::display('layout.tpl', $cache_id, $compile_id, $parent);
			}
			else
			{
				echo $this->fetch($resource_file);
			}

			if (2 == IA_DEBUG)
			{
				require_once IA_CLASSES . 'debug.php';
			}

			if (IA_COMPRESS && !defined('IA_IN_ADMIN'))
			{
				require_once IA_INCLUDES . 'php_speedy' . IA_DS . 'php_speedy.php';

				$compressor->finish();
			}
		}
	}

	public function setNoRender()
	{
		$this->_header = false;
		$this->_footer = false;

		return true;
	}

	public function _ia_parse_block($params)
	{
		$block = $params['block'];
		$content = $params['content'];

		if (empty($params['block']) && empty($block['type']))
		{
			return false;
		}

		switch ($block['type'])
		{
			// print html contents
			case 'html':

				echo $block['external'] ? $this->_processTplPaths($block) : $block['contents'];

				break;

			// print smarty contents
			case 'smarty':

				if ($block['external'] && $this->_processTplPaths($block))
				{
					echo $this->fetch($this->_processTplPaths($block));
				}
				elseif (!$block['external'])
				{
					echo $this->fetch('eval:' . $block['contents']);
				}

				break;

			// execute php code
			case 'php':

				if ($block['external'] && $this->_processPhpPaths($block))
				{
					include_once $this->_processPhpPaths($block);
				}
				elseif (!$block['external'])
				{
					eval($block['contents']);
				}

				break;

			// print smarty menus
			case 'menu':

				$this->assignGlobal('block', $block);

				$filename = IA_TEMPLATE . 'render-menu.tpl';
				if (!file_exists($filename))
				{
					$filename = IA_TEMPLATES . 'common' . IA_DS . 'render-menu.tpl';
				}
				echo $this->fetch('eval:' . file_get_contents($filename));

				break;

			case 'plain':

			default:

				echo htmlentities($block['contents']);

				break;
		}
	}

	private function _processPhpPaths($block)
	{
		$block_name = $block['name'] . '.php';
		if ($block['plugin'])
		{
			$filename = IA_PLUGINS . $block['plugin'] . IA_DS . 'includes' . IA_DS . 'block.' . $block_name;
		}
		else
		{
			$filename = IA_INCLUDES . 'block.' . $block_name;
		}

		if (!file_exists($filename))
		{
			echo 'This file can not be found: ' . $filename;

			return false;
		}

		return $filename;
	}

	private function _processTplPaths($block)
	{
		$block_name = $block['name'] . '.tpl';
		if ($block['plugin'] && $this->tmpl != $block['plugin'])
		{
			$filename = IA_TEMPLATE . 'plugins' . IA_DS . $block['plugin'] . '.block.' . $block_name;

			if (!file_exists($filename))
			{
				$filename = IA_PLUGINS . $block['plugin'] . IA_DS . 'templates' . IA_DS . 'block.' . $block_name;
			}
		}
		else
		{
			$filename = IA_TEMPLATE . 'block.' . $block_name;
			if (!file_exists($filename))
			{
				$filename = IA_TEMPLATES . 'common' . IA_DS . 'block.' . $block_name;
			}
		}

		if (!file_exists($filename))
		{
			echo 'This file can not be found: ' . $filename;

			return false;
		}

		return $filename;
	}

	public function add_css($params)
	{
		static $stylesList = array();

		if (isset($params['files']) && !empty($params['files']))
		{
			if (!defined('IN_HEADER'))
			{
				if (is_array($this->_css_files))
				{
					$this->_css_files = implode(',', $this->_css_files);
				}
				if (is_array($params['files']))
				{
					$params['files'] = implode(',', $params['files']);
				}
				$this->_css_files .= ',' . $params['files'];
			}
			else
			{
				$css_files = explode(',', $params['files']);
				foreach ($css_files as $file)
				{
					$file = trim($file);
					if ($file && !in_array($file, $stylesList))
					{
						$stylesList[] = $file;

						$url = (strpos($file, '_IA_TPL_') !== false) ? str_replace('_IA_TPL_', $this->getTemplateVars('templates'), $file) : IA_URL . $file;

						echo PHP_EOL . "\t\t" . '<link rel="stylesheet" type="text/css" href="' . $url . '.css" />';
					}
				}
			}
		}
	}

	public function add_js($params)
	{
		static $jsList = array();

		if (isset($params['files']) && !empty($params['files']))
		{
			if (!defined('IN_HEADER'))
			{
				if (is_array($this->_js_files))
				{
					$this->_js_files = implode(',', $this->_js_files);
				}
				if (is_array($params['files']))
				{
					$params['files'] = implode(',', $params['files']);
				}
				$this->_js_files .= ',' . $params['files'];
			}
			else
			{
				$js_files = explode(',', $params['files']);
				foreach ($js_files as $file)
				{
					$file = trim($file);
					if ($file && !in_array($file, $jsList))
					{
						$jsList[] = $file;

						$url = (strpos($file, '_IA_TPL_') !== false) ? str_replace('_IA_TPL_', $this->getTemplateVars('templates') . 'js/', $file) : IA_URL . $file;

						echo PHP_EOL . "\t\t" . '<script type="text/javascript" src="' . $url . '.js"></script>';
					}
				}
			}
		}

		// prints
		if (isset($params['printcode']) && isset($_SESSION['js_files']))
		{
			sort($_SESSION['js_files']);
			$code2print = '';
			foreach ($_SESSION['js_files'] as $codes)
			{
				$codes = implode('// ------------------- ' . PHP_EOL, $codes);
				$code2print .= $codes;
			}
			echo PHP_EOL . "\t\t" . '<script type="text/javascript">' . $code2print . '</script>';
		}
	}

	public function add_text($text, $order = 3)
	{
		if (is_array($text))
		{
			$order = isset($text['order']) ? $text['order'] : '';
			$text = $text['files'];

		}
		if (strpos($text, 'text:') !== 0)
		{
			$text = 'text:' . $text;
		}

		$_SESSION['js_files'][$order][] = array('url' => $text);
	}

	public function add_media($params)
	{
		$order = isset($params['order']) ? $params['order'] : 3;
		if (isset($params['files']))
		{
			$files = explode(',', $params['files']);
			foreach ($files as $file)
			{
				$file = trim($file);
				if ($file)
				{
					if (isset($this->_plugin_files[$file]))
					{
						$ps = array(
							'files' => $this->_plugin_files[$file],
							'order' => $order
						);
						$this->add_media($ps);
					}
					else
					{
						$type = false;
						foreach (array('js', 'css', 'text') as $_type)
						{
							if (strpos($file, $_type . ':') === 0)
							{
								$type = $_type;
							}
						}
						if ($type)
						{
							$file = str_replace($type . ':', '', $file);
							$this->{'add_' . $type}(array(
								'files' => $file,
								'order' => $order
							));
						}
					}
				}
			}
		}
	}

	function pass($aVars, $aName)
	{
		if (is_array($aVars))
		{
			$out = "var {$aName} = " . esynUtil::jsonEncode($aVars) . ";";
			$this->assign('phpVariables', $out);
		}
	}
}
