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

class esynPage extends eSyndiCat
{

	var $mTable = 'pages';

	var $mConfig;

	public function getPageByRealm()
	{
		return $this->row('*', "`name` = '" . IA_REALM . "'");
	}

	public function getPages($category_id = 0)
	{
		$out = array();
		$where = array();

		$this->setTable('blocks');
		$active_menus = $this->onefield('`name`', "`type` = 'menu' AND `status` = 'active'");
		$this->resetTable();

		if (empty($active_menus))
		{
			return false;
		}

		$where[] = "`bp`.`block_name` IN('" . join("','", $active_menus) . "')";
		$where[] = "`status` = 'active' ";

		if (!$this->mConfig['suggest_category'])
		{
			$where[] = "`name` != 'suggest_category'";
		}

		if (!$this->mConfig['accounts'])
		{
			$where[] = "`name` != 'accounts'";
		}

		if (!$this->mConfig['allow_listings_submission'])
		{
			$where[] = "`name` != 'suggest_listing'";
		}

		if (!empty($this->mPlugins))
		{
			$where[] = "`plugin` IN('', '" . join("','", array_keys($this->mPlugins)) . "')";
		}

		$where = implode(' AND ', $where);

		$sql = "SELECT `p`.*, `bp`.`block_name` "
			 . "FROM `{$this->mPrefix}block_pages` `bp` "
			 . "LEFT JOIN `{$this->mPrefix}pages` `p` "
			 . "ON `bp`.`page_name` = `p`.`name` "
			 . 'WHERE '
			 . $where . ' '
			 . 'ORDER BY `order`';

		$menus = $this->getAll($sql);

		if (!empty($menus))
		{
			foreach ($menus as $key => $menu)
			{
				$tmp_menu = array();

				$tmp_menu['title'] = _t('page_title_' . $menu['name']);
				$tmp_menu['order'] = $menu['order'];

				if (!empty($menu['unique_url']))
				{
					$tmp_menu['url'] = $menu['unique_url'];
				}
				else
				{
					if (!empty($menu['custom_url']))
					{
						$tmp_menu['url'] = $menu['custom_url'].'.html';
					}
					else
					{
						$tmp_menu['url'] = $menu['name'].'.html';
					}
				}

				$tmp_menu['url'] = str_replace('{idcat}', $category_id, $tmp_menu['url']);

				$tmp_menu['nofollow'] = $menu['nofollow'];
				$tmp_menu['name'] = $menu['name'];
				$tmp_menu['new_window'] = $menu['new_window'];

				$out[$menu['block_name']][] = $tmp_menu;
			}
		}

		return $out;
	}

	public function preparePages($pages, $category_id = 0)
	{
		if (!empty($pages))
		{
			foreach ($pages as $key => $page)
			{
				if (strstr($page['page_name'], "|"))
				{
					$url = IA_URL;
					if (isset($page['path']) && !empty($page['path']))
					{
						$url .= $page['path'] . '/';
					}
					$pages[$key]['url'] = $url;
					$pages[$key]['name'] = $page['page_name'];
				}
				else
				{
					$pages[$key]['title'] = _t('page_title_' . $page['name']);

					if (!empty($page['unique_url']))
					{
						if ($page['unique_url'] == '/')
						{
							$pages[$key]['url'] = IA_URL;
						}
						else
						{
							$pages[$key]['url'] = $page['unique_url'];
						}
					}
					else
					{
						if (!empty($page['custom_url']))
						{
							$pages[$key]['url'] = IA_URL . $page['custom_url'] . '.html';
						}
						else
						{
							$pages[$key]['url'] = IA_URL . $page['name'].'.html';
						}
					}

					$pages[$key]['url'] = str_replace('{idcat}', $category_id, $pages[$key]['url']);
				}
			}
		}

		return $pages;
	}

	public function getPagesForSearch($what)
	{
		// get language results
		$sql = "SELECT REPLACE(`key`, 'page_content_', ''), `value` ";
		$sql .= "FROM `{$this->mPrefix}language` ";
		$sql .= "WHERE `category` = 'page' AND `plugin` IS null AND `code` = '" . IA_LANGUAGE . "' ";
		$sql .= "AND `key` LIKE 'page_content_%' AND `value` LIKE '%{$what}%'";
		$lang_results = $this->getKeyValue($sql);

		if ($lang_results)
		{
			$keys = implode(array_keys($lang_results), "','");

			// get real pages
			$sql = "SELECT `name` `page_name`, `name`, `status`, `unique_url`, `custom_url` FROM `{$this->mTable}` WHERE `name` IN ('{$keys}') AND `status` = 'active'";
			$pages = $this->getAssoc($sql, array(), true);

			if ($pages)
			{
				$pages = $this->preparePages($pages);
				foreach($pages as $page)
				{
					$pages[$page['name']]['body'] = $this->_excerpt(strip_tags($lang_results[$page['name']]), $what);
				}

				return $pages;
			}
		}

		return false;
	}

	protected function _excerpt($text, $phrase, $radius = 50, $ending = '...')
	{
		$phraseLen = strlen($phrase);
		if ($radius < $phraseLen) {
			$radius = $phraseLen;
		}

		$phrases = explode (' ',$phrase);

		foreach ($phrases as $phrase)
		{
			$pos = strpos(strtolower($text), strtolower($phrase));
			if ($pos > -1) break;
		}

		$startPos = 0;
		if ($pos > $radius)
		{
			$startPos = $pos - $radius;
		}

		$textLen = strlen($text);

		$endPos = $pos + $phraseLen + $radius;
		if ($endPos >= $textLen)
		{
			$endPos = $textLen;
		}

		$excerpt = substr($text, $startPos, $endPos - $startPos);
		if ($startPos != 0)
		{
			$excerpt = substr_replace($excerpt, $ending, 0, $phraseLen);
		}

		if ($endPos != $textLen)
		{
			$excerpt = substr_replace($excerpt, $ending, -$phraseLen);
		}

		return $excerpt;
	}
}
