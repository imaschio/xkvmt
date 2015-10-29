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
 * esynBlock
 *
 * @uses esynAdmin
 * @package
 * @version $id$
 */
class esynBlock extends esynAdmin
{
	/**
	 * mTable
	 *
	 * @var string
	 * @access public
	 */
	var $mTable = "blocks";

	var $types = array('plain', 'html', 'smarty', 'php', 'menu');
	var $status = array('active', 'inactive');
	var $positions = array();

	function esynBlock()
	{
		parent::eSyndiCat();

		$this->positions = explode(",", $this->mConfig['esyndicat_block_positions']);
	}

	/**
	 * insert blocks
	 *
	 * @param array $block
	 * @access public
	 * @return bool
	 */
	function insert($block, $arg1 = null)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeBlockInsert", $params);

		if (empty($block))
		{
			$this->message = 'The Block parameter is empty.';

			return false;
		}

		if (empty($block['lang']) || !array_key_exists($block['lang'], $this->mLanguages))
		{
			$block['lang'] = IA_LANGUAGE;
		}

		if (!isset($block['type']) || !in_array($block['type'], $this->types))
		{
			$block['type'] = 'plain';
		}

		$where_order = 'menu' == $block['type'] ? "`type` = 'menu'" : '';

		$order = $this->one("MAX(`order`) + 1", $where_order);

		$block['order'] = (null == $order) ? 1 : $order;

		if (isset($block['visible_on_pages']))
		{
			if (!empty($block['visible_on_pages']))
			{
				$visible_on_pages = $block['visible_on_pages'];
			}

			unset($block['visible_on_pages']);
		}

		if ('1' != $block['multi_language'])
		{
			if (isset($block['block_languages']))
			{
				$block_languages = $block['block_languages'];
				$title = $block['title'];
				$contents = $block['contents'];

				unset($block['block_languages'], $block['title'], $block['contents']);
			}
		}

		if (isset($block['menus']))
		{
			if (!empty($block['menus']))
			{
				$menus = $block['menus'];
			}

			unset($block['menus']);
		}

		$id = parent::insert($block);

		if (empty($block['name']))
		{
			$block['name'] = 'menu_' . $id;

			parent::update(array('name' => $block['name']), "`id` = '{$id}'");
		}

		if (isset($menus))
		{
			parent::setTable('block_pages');

			foreach ($menus as $menu)
			{
				$ins_menu = array(
					'block_name' => $block['name'],
					'page_name' => $menu['page_name'],
					'parent_name' => $menu['parent_name'],
					'level' => $menu['level']
				);

				parent::insert($ins_menu);
			}

			parent::resetTable();
		}

		if ('1' != $block['multi_language'])
		{
			if (isset($block_languages))
			{
				$language_content = array();

				foreach ($block_languages as $block_language)
				{
					$language_content[] = array(
						'key'		=> 'block_title_blc' . $id,
						'value'		=> $title[$block_language],
						'lang'		=> $this->mLanguages[$block_language],
						'category'	=> 'common',
						'code'		=> $block_language
					);

					$language_content[] = array(
						'key'		=> 'block_content_blc' . $id,
						'value'		=> $contents[$block_language],
						'lang'		=> $this->mLanguages[$block_language],
						'category'	=> 'common',
						'code'		=> $block_language
					);
				}

				parent::setTable("language");
				parent::insert($language_content);
				parent::resetTable();
			}
		}

		if (isset($visible_on_pages) && !empty($visible_on_pages))
		{
			$data = array();

			parent::setTable("block_show");

			foreach ($visible_on_pages as $key => $page)
			{
				$data = array(
					'block_id' => $id,
					'page' => $page
				);

				parent::insert($data);
			}

			parent::resetTable();
		}

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterBlockInsert", $params);

		return $id;
	}

	function delete($ids = '', $arg1 = array(), $arg2 = false)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeBlockDelete", $params);

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		$where = $this->convertIds('id', $ids);

		$blocks = parent::keyvalue('`id`, `name`', $where);

		parent::delete($where);

		$where = $this->convertIds('block_id', $ids);

		parent::setTable('block_show');
		parent::delete($where);
		parent::resetTable();

		if (!empty($blocks))
		{
			foreach ($blocks as $block_id => $block)
			{
				parent::setTable('language');
				parent::delete("`key` IN ('block_title_blc{$block_id}', 'block_content_blc{$block_id}')");
				parent::resetTable();

				parent::setTable('block_pages');
				parent::delete("`block_name` = '{$block}'");
				parent::resetTable();
			}
		}

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterBlockDelete", $params);

		return true;
	}

	function update($block, $id = '', $arg1 = array(), $arg2 = null)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforeBlockUpdate", $params);

		if (empty($block))
		{
			$this->message = 'The block parameter is empty.';

			return false;
		}

		if (empty($id))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		$ids = $id;

		if (isset($block['visible_on_pages']))
		{
			if (!empty($block['visible_on_pages']))
			{
				if (is_array($ids))
				{
					$page_ids = $ids;
				}
				else
				{
					$page_ids[] = $ids;
				}

				$visible_on_pages = $block['visible_on_pages'];

				$data = array();

				foreach($visible_on_pages as $key => $page)
				{
					foreach($page_ids as $pid)
					{
						$data[] = array(
							'block_id' => $pid,
							'page' => $page
						);
					}
				}

				$where = $this->convertIds('block_id', $ids);

				parent::setTable("block_show");
				parent::delete($where);
				parent::insert($data);
				parent::resetTable();
			}

			unset($block['visible_on_pages']);
		}

		if (isset($block['multi_language']) && '1' != $block['multi_language'])
		{
			if (isset($block['block_languages']))
			{
				$block_languages = $block['block_languages'];
				$title = $block['title'];
				$contents = $block['contents'];

				unset($block['block_languages'], $block['title'], $block['contents']);
			}
		}

		if (isset($block['menus']) && !empty($block['menus']))
		{
			$menus = $block['menus'];

			unset($block['menus']);
		}

		$where = $this->convertIds('id', $ids);

		$old_block = parent::row('*', $where);

		if (empty($block['name']))
		{
			$block['name'] = 'menu_' . $id;
		}

		parent::update($block, $where);

		parent::setTable('block_pages');
		parent::delete("`block_name` = '{$old_block['name']}'");
		parent::resetTable();

		if (isset($menus))
		{
			parent::setTable('block_pages');

			foreach ($menus as $menu)
			{
				$ins_menu = array(
					'block_name' => $block['name'],
					'page_name' => $menu['page_name'],
					'parent_name' => $menu['parent_name'],
					'level' => $menu['level']
				);

				parent::insert($ins_menu);
			}

			parent::resetTable();
		}

		if (isset($block['multi_language']) && '1' != $block['multi_language'])
		{
			if (isset($block_languages))
			{
				$language_content_where = array();
				$language_content = array();

				foreach($block_languages as $block_language)
				{
					if (is_array($ids))
					{
						foreach($ids as $id)
						{
							$language_content[] = array(
								'key'		=> 'block_title_blc' . $id,
								'value'		=> $title[$block_language],
								'lang'		=> $this->mLanguages[$block_language],
								'category'	=> 'common',
								'code'		=> $block_language
							);

							$language_content[] = array(
								'key'		=> 'block_content_blc' . $id,
								'value'		=> $contents[$block_language],
								'lang'		=> $this->mLanguages[$block_language],
								'category'	=> 'common',
								'code'		=> $block_language
							);

							$language_content_where[] = 'block_title_blc' . $id;
							$language_content_where[] = 'block_content_blc' . $id;
						}
					}
					else
					{
						$id = $ids;

						$language_content[] = array(
							'key'		=> 'block_title_blc' . $id,
							'value'		=> $title[$block_language],
							'lang'		=> $this->mLanguages[$block_language],
							'category'	=> 'common',
							'code'		=> $block_language
						);

						$language_content[] = array(
							'key'		=> 'block_content_blc' . $id,
							'value'		=> $contents[$block_language],
							'lang'		=> $this->mLanguages[$block_language],
							'category'	=> 'common',
							'code'		=> $block_language
						);

						$language_content_where[] = 'block_title_blc' . $id;
						$language_content_where[] = 'block_content_blc' . $id;
					}
				}

				parent::setTable("language");
				parent::delete("`key` IN ('" . join("','", $language_content_where) . "')");
				parent::insert($language_content);
				parent::resetTable();
			}
		}
		else
		{
			$language_content_where = array();

			if (is_array($ids))
			{
				foreach($ids as $id)
				{
					$language_content_where[] = 'block_title_blc' . $id;
					$language_content_where[] = 'block_content_blc' . $id;
				}
			}
			else
			{
				$language_content_where[] = 'block_title_blc' . $ids;
				$language_content_where[] = 'block_content_blc' . $ids;
			}

			parent::setTable("language");
			parent::delete("`key` IN ('" . join("','", $language_content_where) . "')");
			parent::resetTable();
		}

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterBlockUpdate", $params);

		return true;
	}

	function getBlocks($id)
	{
		$sql = "SELECT * "
			 . "FROM `{$this->mPrefix}block_tabs` `block_tabs` "
			 . "LEFT JOIN `{$this->mPrefix}blocks` `blocks` "
			 . "ON `block_tabs`.`tab` = `blocks`.`id` "
			 . "WHERE `block_tabs`.`block_id` = {$id}";

		return $this->getAll($sql);
	}

	/*
	 * Convert Object into Array
	 *
	 * @param mixed $obj
	 * return array
	 */
	function object2array($obj)
	{
		$arr = array();
		if (is_object($obj) || is_array($obj))
		{
			foreach ($obj as $k => $v)
			{
				$arr[$k] = $this->object2array($v);
			}
		}
		else
		{
			$arr = $obj;
		}
		return $arr;
	}
}
