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

class esynView
{
	/**
	 * Returns all parent categories
	 *
	 * @param int $aCategory category id
	 * @param array $aBreadcrumb breadcrumb array
	 *
	 * @return array
	 */
	static function getBreadcrumb($aCategory, &$aBreadcrumb, $root = false)
	{
		global $esynAdmin;

		static $times = 0;

		if ($times > 75)
		{
			trigger_error("Recursion in esynUtil::getBreadcrumb() in file: ".__FILE__." on line: ".__LINE__, E_USER_ERROR);
		}

		$esynAdmin->setTable("categories");

		$category = $esynAdmin->row("`parent_id`,`title`,`page_title`,`path`, `no_follow`", "`id` = '{$aCategory}'");

		if ('-1' == $category['parent_id'])
		{
			if ($root)
			{
				$aBreadcrumb[] = array(
					'id'			=> $aCategory,
					'title'			=> $category['title'],
					'page_title'	=> $category['page_title'],
					'path'			=> $category['path'],
					'no_follow'		=> $category['no_follow']
				);
			}

			$times = 0;
		}
		else
		{
			$aBreadcrumb[] = array(
				'id'			=> $aCategory,
				'title'			=> $category['title'],
				'page_title'	=> $category['page_title'],
				'path'			=> $category['path'],
				'no_follow'		=> $category['no_follow']
			);

			$times++;
			$esynAdmin->resetTable();
			esynView::getBreadcrumb($category['parent_id'], $aBreadcrumb, $root);
		}
	}

	static function langList($select = false, $name = '')
	{
		if (!$select)
		{
			//default
			$select = IA_LANGUAGE;
		}
		// if only 1 language then make disabled
		$x = count($GLOBALS['langs']) == 1 ? " disabled=\"disabled\"" : "";
		$s = "<select id=\"language_list_".$name."\" name=\"".$name."\"$x>";
		foreach($GLOBALS['langs'] as $code=>$l)
		{
			$s .= "<option value=\"".$code."\"";
			if ($code == $select)
			{
				$s .= " selected=\"selected\"";
			}
			$s .= ">".$l."</option>";
		}
		$s .= "</select>";

		return $s;
	}

	public static function printBreadcrumb($aCategory = '', $aBc = '')
	{
		global $esynI18N;

		$str = '';

		$chain = (count($aBc) > 1) ? count($aBc) : 0;

		if ($aBc[0] || $aCategory)
		{
			$str .= '<div class="breadcrumb">';
			$str .= "<a href=\"index.php\">{$esynI18N['admin_panel']}</a>";

			if ($aCategory)
			{
				$breadcrumb = array();

				$str .= ' / ';
				esynView::getBreadcrumb($aCategory, $breadcrumb);

				$breadcrumb = array_reverse($breadcrumb);

				$size = count($breadcrumb);
				$cnt = 0;

				$str .= (0 == $size) ? "<strong>" . esynSanitize::html($aBc[0]['title']) . "</strong>" : "<a href=\"{$aBc[0]['url']}\">" . esynSanitize::html($aBc[0]['title']) . "</a>";

				// don't make link in browse category section
				if (false !== strpos($_SERVER['QUERY_STRING'], "browse"))
				{
					$cnt++;
				}

				$url = isset($aBc[0]['item_url']) && !empty($aBc[0]['item_url']) ? $aBc[0]['item_url'] : $aBc[0]['url'];

				foreach($breadcrumb as $item)
				{
					$item['title'] = esynSanitize::html($item['title']);

					if ($size == $cnt)
					{
						$str .= " / <strong>{$item['title']}</strong>";
					}
					else
					{
						$str .= " / <a href=\"{$url}&amp;id={$item['id']}\">{$item['title']}</a>";
					}
					$cnt++;
				}

				if ($chain)
				{
					$cnt = 1;
					foreach($aBc as $k => $item)
					{
						if ($k < $cnt)
						{
							continue;
						}
						if ($chain - 1 == $k)
						{
							$str .= " / <strong>" . esynSanitize::html($item['title']) . "</strong>";
						}
						else
						{
							if (isset($item['url']))
							{
								$str .= " / <a href=\"{$item['url']}\">" . esynSanitize::html($item['title']) . "</a>";
							}
							else
							{
								$str .= " / " . esynSanitize::html($item['title']);
							}
						}
						$cnt++;
					}
				}
			}
			else
			{
				if ($chain)
				{
					$size = count($aBc);
					$cnt = 1;
					foreach ($aBc as $item)
					{
						if ($size == $cnt)
						{
							$str .= " / <strong>" . esynSanitize::html($item['title']) . "</strong>";
						}
						else
						{
							$str .= " / <a href=\"{$item['url']}\">" . esynSanitize::html($item['title']) . "</a>";
						}
						$cnt++;
					}
				}
				else
				{
					$str .= " / " . $aBc[0]['title'];
				}
			}

			$str .= '</div>';
		}

		return $str;
	}
}
