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
 * esynPage
 *
 * @uses esynAdmin
 * @package
 * @version $id$
 */
class esynPage extends eSyndiCat
{

	var $mTable = "pages";

	public function insert($page, $arg1 = null)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforePageInsert", $params);

		if (empty($page))
		{
			$this->message = "Page parameter is empty.";

			return false;
		}

		if ($this->exists("`name` = :name", array('name' => $page['name'])) && empty($page['unique_url']))
		{
			$exist_page = $this->row("*", "`name` = :name", array('name' => $page['name']));

			if ('draft' == $page['status'])
			{
				$this->update($page, $exist_page['id']);

				return true;
			}
			else
			{
				if ('draft' == $exist_page['status'])
				{
					$this->update($page, $exist_page['id']);

					return true;
				}
				else
				{

					$this->message = $this->mI18N['page_name_exists'];

					return false;
				}
			}
		}

		if (!empty($page['unique_url']) && $this->exists("`unique_url` = :url", array('url' => $page['unique_url'])))
		{
			$this->message = $this->mI18N['page_url_exists'];

			return false;
		}

		$order = $this->one("MAX(`order`) + 1");
		$page['order'] = (null == $order) ? 1 : $order;

		if (isset($page['titles']) && is_array($page['titles']))
		{
			parent::setTable("language");

			foreach($page['titles'] as $lngcode => $lngvalue)
			{
				$phrase = array(
					"key"		=> 'page_title_'.$page['name'],
					"value"		=> $lngvalue,
					"code"		=> $lngcode,
					"lang"		=> $this->mLanguages[$lngcode],
					"category"	=> "page"
				);

				parent::insert($phrase);
			}

			parent::resetTable();

			unset($page['titles']);
		}

		if (isset($page['contents']) && is_array($page['contents']))
		{
			parent::setTable("language");

			foreach($page['contents'] as $lngcode => $lngvalue)
			{
				$phrase = array(
					"key"		=> 'page_content_'.$page['name'],
					"value"		=> $lngvalue,
					"code"		=> $lngcode,
					"lang"		=> $this->mLanguages[$lngcode],
					"category"	=> "page"
				);

				parent::insert($phrase);
			}

			parent::resetTable();

			unset($page['contents']);
		}

		if (isset($page['menus']) && !empty($page['menus']))
		{
			parent::setTable('block_pages');

			foreach ($page['menus'] as $menu)
			{
				$ins_menu = array(
					'block_name' => $menu,
					'page_name' => $page['name'],
				);

				parent::insert($ins_menu);
			}

			parent::resetTable();

			unset($page['menus']);
		}
		else
		if (isset($page['menus']) && empty($page['menus']))
		{
			unset($page['menus']);
		}

		$id = parent::insert($page, array('last_updated' => 'NOW()'));

		$this->mCacher->clearAll('language');

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterPageInsert", $params);

		return $id;
	}

	public function update($page, $ids = '', $arg1 = array(), $arg2 = null)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforePageUpdate", $params);

		if (empty($page))
		{
			$this->message = 'The page parameter is empty.';

			return false;
		}

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		if (isset($page['titles']) && is_array($page['titles']))
		{
			parent::setTable("language");

			foreach($page['titles'] as $lngcode => $lngvalue)
			{
				$where = "`key` = 'page_title_{$page['name']}' AND `code` = :code AND `lang` = '{$this->mLanguages[$lngcode]}'";

				if ($this->one('`key`', $where, array('code' => $lngcode)))
				{
					parent::update(array("value" => $lngvalue), $where, array('code' => $lngcode));
				}
				else
				{
					$phrase = array(
						"key"		=> 'page_title_'.$page['name'],
						"value"		=> $lngvalue,
						"code"		=> $lngcode,
						"lang"		=> $this->mLanguages[$lngcode],
						"category"	=> 'page'
					);

					parent::insert($phrase);
				}
			}

			parent::resetTable();

			unset($page['titles']);
		}

		if (isset($page['contents']) && is_array($page['contents']))
		{
			parent::setTable("language");

			foreach($page['contents'] as $lngcode => $lngvalue)
			{
				$where = "`key` = 'page_content_{$page['name']}' AND `code` = :code AND `lang` = '{$this->mLanguages[$lngcode]}'";

				if (parent::one('`key`', $where, array('code' => $lngcode)))
				{
					parent::update(array("value" => $lngvalue), $where, array('code' => $lngcode));
				}
				else
				{
					$phrase = array(
						"key"		=> 'page_content_'.$page['name'],
						"value"		=> $lngvalue,
						"code"		=> $lngcode,
						"lang"		=> $this->mLanguages[$lngcode],
						"category"	=> 'page'
					);

					parent::insert($phrase);
				}
			}

			parent::resetTable();

			unset($page['contents']);
		}

		if (isset($page['menus']))
		{
			parent::setTable('block_pages');

			parent::delete("`page_name` = '{$page['name']}' AND `block_name` NOT IN ('".implode("', '", $page['menus'])."')");

			$block_names = $this->onefield("`block_name`", "`page_name` = :name", array('name' => $page['name']));

			if ($block_names)
			{
				foreach ($page['menus'] as $menu)
				{
					if (!in_array($menu, $block_names))
					{
						$ins_menu = array(
							'block_name' => $menu,
							'page_name' => $page['name'],
						);

						parent::insert($ins_menu);
					}
				}
			}
			else
			{
				foreach ($page['menus'] as $menu)
				{
					$ins_menu = array(
						'block_name' => $menu,
						'page_name' => $page['name'],
					);

					parent::insert($ins_menu);
				}
			}

			parent::resetTable();

			unset($page['menus']);
		}

		$where = $this->convertIds('id', $ids);

		parent::update($page, $where, array(), array('last_updated' => 'NOW()'));

		$this->mCacher->clearAll('language');

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterPageUpdate", $params);

		return true;
	}

	public function delete($ids = '', $arg1 = array(), $arg2 = false)
	{
		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("beforePageDelete", $params);

		if (empty($ids))
		{
			$this->message = 'The ID parameter is empty.';

			return false;
		}

		if (!is_array($ids))
		{
			$ids = array($ids);
		}

		foreach ($ids as $id)
		{
			$page_name = parent::one('`name`', "`id` = :id", array('id' => (int)$id));

			parent::delete("`id` = :id", array('id' => (int)$id));

			parent::setTable("language");
			parent::delete("`key` IN ('page_title_{$page_name}', 'page_content_{$page_name}')");
			parent::resetTable();

			parent::setTable('block_pages');
			parent::delete("`page_name` = '{$page_name}'");
			parent::resetTable();
		}

		$this->mCacher->clearAll('language');

		$vars = get_defined_vars();

		if (!empty($vars))
		{
			foreach ($vars as $key => $var)
			{
				$params[$key] = &${$key};
			}
		}

		$this->startHook("afterPageDelete", $params);

		return true;
	}

	public function getPages($where = '')
	{
		$this->setTable('pages');
		$pages = $this->all("*", $where);
		$this->resetTable();

		if (!empty($pages))
		{
			$this->setTable('language');
			foreach($pages as $key => $page)
			{
				if (!empty($page['unique_url']) && esynValidator::isUrl($page['unique_url']) && !stristr($page['unique_url'], IA_URL))
				{
					unset($pages[$key]);
					continue;
				}

				$pages[$key]['title'] = $this->one("`value`", "`key` = 'page_title_{$page['name']}' AND `code` = '" . IA_LANGUAGE . "'");
			}
			$this->resetTable();
		}

		return $pages;
	}

	public function getPageGroups()
	{
		return $this->onefield("`group`", "1=1 GROUP BY `group` ORDER BY `group` ASC");
	}
}
