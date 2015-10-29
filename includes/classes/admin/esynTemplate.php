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

/**
 * esynTemplate
 *
 * @uses esynAdmin
 * @package
 */
class esynTemplate extends esynAdmin
{

	private $_blocks;

	var $inTag;

	var $level = 0;

	var $path;

	var $attributes;

	var $xml;

	var $upgrade = false;

	var $upgradeCode;

	var $name;

	var $title;

	var $status;

	var $summary;

	var $version;

	var $message;

	var $author;

	var $contributor;

	var $notes;

	var $error = false;

	var $phrases;

	var $config;

	var $date;

	var $compatibility = false;

	function parse()
	{
		require_once(IA_INCLUDES . 'xml' . IA_DS . '/xml_saxy_parser.php');

		$xmlParser = new SAXY_Parser();

		$xmlParser->xml_set_element_handler(array(&$this, "startElement"), array(&$this, "endElement"));
		$xmlParser->xml_set_character_data_handler(array(&$this, "charData"));
		$xmlParser->xml_set_comment_handler(array(&$this, "commentElement"));

		$xmlParser->parse($this->xml);
	}

	function checkFields()
	{
		$mandatoryFields = array("name", "title", "version", "summary", "author", "contributor");

		$notExist = array();

		$vars = get_object_vars($this);

		foreach ($mandatoryFields as $field)
		{
			if (!array_key_exists($field, $vars) || empty($vars[$field]))
			{
				$this->error = true;
				$notExist[] = $field;
			}
		}

		if ($this->error)
		{
			if (empty($notExist))
			{
				$this->message = "Fatal error: Probably specified file is not XML file or is not acceptable.";
			}
			else
			{
				$this->message = "Fatal error: The following fields are required: ";
				$this->message .= join(", ", $notExist);
			}
		}
	}

	function install()
	{
		$this->setTable('config');
		$this->update(array('value' => $this->name), "`name` = 'tmpl'");
		$this->resetTable();

		// add new phrases
		if (!empty($this->phrases))
		{
			if (!array_key_exists('en', $this->mLanguages))
			{
				foreach ($this->mLanguages as $code => $language)
				{
					foreach ($this->phrases as $key => $phrase)
					{
						$this->phrases[$key]['lang'] = $language;
						$this->phrases[$key]['code'] = $code;
					}
				}
			}
			else
			{
				foreach ($this->mLanguages as $code => $language)
				{
					if ('en' != $code)
					{
						foreach($this->phrases as $key => $phrase)
						{
							$new_phrases[] = array(
								"key"		=> $phrase['key'],
								"value"		=> $phrase['value'],
								"lang"		=> $language,
								"category"	=> $phrase['category'],
								"code"		=> $code,
								"plugin"	=> $this->name
							);
						}
					}
				}
			}

			$this->setTable('language');
			foreach ($this->phrases as $phrase)
			{
				if ($this->exists("`key` = '{$phrase['key']}' AND `code` = '{$phrase['code']}'"))
				{
					$this->update($phrase, "`key` = '{$phrase['key']}' AND `code` = '{$phrase['code']}'");
				}
				else
				{
					$this->insert($phrase);
				}
			}
			if (!empty($new_phrases))
			{
				foreach ($new_phrases as $phrase)
				{
					if ($this->exists("`key` = '{$phrase['key']}' AND `code` = '{$phrase['code']}'"))
					{
						$this->update($phrase, "`key` = '{$phrase['key']}' AND `code` = '{$phrase['code']}'");
					}
					else
					{
						$this->insert($phrase);
					}
				}
			}
			$this->resetTable();
		}

		// add new configs
		if (!empty($this->config))
		{
			$this->setTable('config');

			$maxorder = $this->one("MAX(`order`) + 1");
			$maxorder = (null == $maxorder) ? 1 : $maxorder;

			foreach ($this->config as $config)
			{
				if ($this->exists("`name` = '{$config['name']}'"))
				{
					$this->update($config, "`name` = '{$config['name']}'");
				}
				else
				{
					$this->insert($config, array("order" => $maxorder));
					$maxorder++;
				}
			}
			$this->resetTable();
		}

		if (!empty($this->_blocks))
		{
			$this->setTable('pages');
			$pages = $this->onefield("`name`");
			$this->resetTable();

			$this->setTable("blocks");

			$maxorder = $this->one("MAX(`order`) + 1");
			$maxorder = (null == $maxorder) ? 1 : $maxorder;

			foreach ($this->_blocks as $block)
			{
				// update block
				if ('change' == $block['action'] && $block['name'])
				{
					$possible_fields = array(
						'position',
						'status',
						'order',
						'show_header',
						'collapsible',
						'collapsed',
						'sticky',
						'rss',
						'classname',
						'external'
					);

					$block_update = array();
					foreach ($possible_fields as $field)
					{
						if (isset($block[$field]))
						{
							$block_update[$field] = $block[$field];
						}
					}

					$this->setTable('blocks');
					$this->update($block_update, "`name` = '{$block['name']}'");
					$this->resetTable();
				}
				else
				{
					$blockPages = $block['pages'];
					$blockExceptPages = $block['pagesexcept'];

					unset($block['pages'], $block['pagesexcept'], $block['added'], $block['action']);

					if (isset($block['contents']) && !empty($block['contents']))
					{
						$block['contents'] = str_replace('{PLUGIN}', $this->name, $block['contents']);
					}

					if (!empty($block['name']))
					{
						$block['name'] = preg_replace("/[^a-z0-9-_]/iu", "", $block['name']);
					}

					$id = $this->insert($block, array("order" => $maxorder));

					if (empty($block['name']))
					{
						$this->update(array('name' => 'block_' . $id), "`id` = '{$id}'");
					}

					if (!empty($blockPages))
					{
						if (!empty($blockExceptPages))
						{
							$blockExceptPages = explode(',', $blockExceptPages);
						}

						$pages_from = (!is_array($blockPages) && 'all' == strtolower($blockPages)) ? $pages : explode(',', $blockPages);
						foreach ($pages_from as $page)
						{
							if (!in_array($page, $blockExceptPages))
							{
								$block_pages[] = array(
									"page"		=> esynSanitize::sql($page),
									"block_id"	=> $id
								);
							}
						}

						/*
						if ('all' == strtolower($blockPages))
						{
							foreach ($pages as $page)
							{
								if (!in_array($page, $blockExceptPages))
								{
									$pluginAco[] = array(
										"page"		=> esynSanitize::sql($page),
										"block_id"	=> $id
									);
								}
							}
						}
						elseif (!$block['sticky'])
						{
							foreach ($blockPages as $page)
							{
								if (!in_array($page, $blockExceptPages))
								{
									$pluginAco[] = array(
										"page"		=> esynSanitize::sql($page),
										"block_id"	=> $id
									);
								}
							}
						}
						*/

						$this->setTable("block_show");
						$this->insert($block_pages);
						$this->resetTable();
					}

					$maxorder++;
				}
			}

			$this->resetTable();
		}

		$this->mCacher->clearAll('', true);

		return true;
	}

	function getScreenshots()
	{
		$screenshots = array();

		if (!empty($this->name))
		{
			$template_path = IA_TEMPLATES . $this->name . IA_DS . 'info' . IA_DS . 'screenshots' . IA_DS;
			$directory = opendir($template_path);

			while (false !== ($file = readdir($directory)))
			{
				if (substr($file, 0, 1) != ".")
				{
					$ext = substr($file, strrpos($file, '.') + 1);

					if (is_file($template_path . $file) && 'jpg' == $ext)
					{
						if ('index.jpg' != $file)
						{
							$screenshots[] = $file;
						}
					}
				}
			}

			closedir($directory);
		}

		$screenshots[] = 'index.jpg';

		return array_reverse($screenshots);
	}

	function setXml($str)
	{
		$this->xml = $str;
	}

	function getFromPath($filePath)
	{
		if (empty($filePath))
		{
			trigger_error("Install XML path wasn't specified.", E_USER_ERROR);

			return false;
		}

		$this->xml = file_get_contents($filePath);
	}

	function startElement($parser, $name, $attributes)
	{
		$this->level++;
		$this->inTag = $name;
		$this->attributes = $attributes;

		if ($this->inTag == 'template' && isset($attributes['name']))
		{
			$this->name = $attributes['name'];
		}

		$this->path[] = $name;
	}

	function endElement($parser, $name)
	{
		$this->level--;
		array_pop($this->path);
	}

	function charData($parser, $text)
	{
		$text = trim($text);

		if ('config' == $this->inTag)
		{
			$this->config[] = array(
				"group_name"		=> isset($this->attributes['group']) ? $this->attributes['group'] : $this->attributes['configgroup'],
				"name"				=> $this->attributes['name'],
				"value"				=> $text,
				"multiple_values"	=> isset($this->attributes['multiplevalues']) ? $this->attributes['multiplevalues'] : '',
				"type"				=> $this->attributes['type'],
				"description"		=> isset($this->attributes['description']) ? $this->attributes['description'] : '',
				"plugin"			=> $this->name
			);
		}

		if ('block' == $this->inTag)
		{
			$status = 'inactive';

			if (isset($this->attributes['status']))
			{
				$status = in_array($this->attributes['status'], array("active", "inactive"), true) ? $this->attributes['status'] : 'inactive';
			}

			$this->_blocks[] = array(
				"action"			=> $this->_getAttr('action'),
				"plugin"			=> $this->name,
				"title"				=> $this->_getAttr('title'),
				"name"              => $this->_getAttr('name'),
				"contents"			=> $text,
				"lang"				=> $this->_getAttr('lang', 'en'),
				"position"			=> $this->_getAttr('position'),
				"type"				=> $this->_getAttr('type', 'smarty'),
				"status"			=> $status,
				"show_header"		=> $this->_getAttr('show_header', 0),
				"collapsible"		=> $this->_getAttr('collapsible', 0),
				"sticky"			=> $this->_getAttr('sticky', 0),
				"multi_language"	=> $this->_getAttr('multi_language', 1),
				"pages"				=> isset($this->attributes['pages']) ? $this->attributes['pages'] : '',
				"pagesexcept"		=> isset($this->attributes['pagesexcept']) ? $this->attributes['pagesexcept'] : array(),
				"added"				=> $this->_getAttr('added'),
				"rss"				=> $this->_getAttr('rss'),
				"classname"			=> $this->_getAttr('classname'),
				"external"			=> $this->_getAttr('external', 0)
			);
		}

		if (in_array('phrases', $this->path) && 'phrase' == $this->inTag)
		{
			$this->phrases[] = array(
				"key"		=> $this->attributes['key'],
				"value"		=> $text,
				"lang"		=> $this->_getAttr('lang', 'en'),
				"category"	=> $this->attributes['category'],
				"code"		=> $this->attributes['code'],
				"plugin"	=> $this->name
			);
		}

		if (in_array($this->inTag, array('version', 'summary', 'title', 'author', 'contributor', 'notes', 'status', 'date', 'compatibility', 'mobile')))
		{
			$this->{$this->inTag} = $text;
		}
	}

	private function _getAttr($name, $default = null)
	{
		return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
	}

	function getMessage()
	{
		return $this->message;
	}

	function getNotes()
	{
		return $this->notes;
	}

	function commentElement($parser, $name)
	{
	}

	function uninstall($template)
	{
		$template = esynSanitize::sql($template);
		if (empty($template))
		{
			$this->error = true;
			$this->message = "The plugin name is empty.";

			return false;
		}

		$this->parse();
		$this->checkFields();

		$this->setTable('pages');
		$plugin_pages = $this->keyvalue('`id`, `name`', "`plugin` = '{$template}'");
		$this->resetTable();

		if (!empty($plugin_pages))
		{
			$this->setTable('block_pages');
			foreach ($plugin_pages as $page)
			{
				$this->delete("`page_name` = '{$page}'");
			}
			$this->resetTable();
		}

		$this->cascadeDelete(array("admin_blocks", "admin_pages", "language", "config_groups", "config", "pages", "hooks", "field_groups"), "`plugin` = '{$template}'");

		$this->setTable("blocks");
		$block_ids = $this->onefield("id", "`plugin` = '{$template}'");
		$this->resetTable();

		if ($block_ids)
		{
			$this->setTable("blocks");
			$this->delete("`id` IN('".join("','", $block_ids)."')");
			$this->resetTable();

			$this->setTable("block_show");
			$this->delete("`block_id` IN('".join("','", $block_ids)."')");
			$this->resetTable();
		}

		$this->setTable("actions");
		$actions = $this->onefield("name", "`plugin` = '{$template}'");
		$this->delete("`plugin` = '{$template}'");
		$this->resetTable();

		if (!empty($actions))
		{
			$this->setTable("action_show");
			$this->delete("`action_name` IN('".join("','", $actions)."')");
			$this->resetTable();
		}

		$this->mCacher->clearAll('', true);

		return true;
	}
}
