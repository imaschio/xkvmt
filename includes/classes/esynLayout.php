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

class esynLayout extends eSyndiCat
{

	public function getTitle($categories = array(), $aTitle = '', $aPage)
	{
		global $esynI18N;

		$out = '';
		if ($this->mConfig['title_breadcrumb'] && $categories)
		{
			$categories = array_reverse($categories);

			$first = array_shift($categories);
			$out .= empty($first['page_title']) ? $first['title'] : $first['page_title'];
			unset($first);

			if ($aPage > 1)
			{
				$out .= " {$esynI18N['page']} {$aPage} ";
			}

			foreach($categories as $value)
			{
				$out .= ' : ' . esynSanitize::html($value['title']);
			}
		}
		else
		{
			if ($aPage > 1)
			{
				$aTitle .= " {$esynI18N['page']} {$aPage} ";
			}

			$out = $aTitle . ' : ' . $this->mConfig['site'];
		}

		return $out;
	}

	/**
	 * printCategoryUrl displays category url depending on configuration
	 *
	 * @param array $aParams['category'] category information array
	 *
	 * @return void
	 */
	public function printCategoryUrl($aParams)
	{
		if (isset($aParams['prefix']) && !empty($aParams['prefix']))
		{
			$aParams['cat']['id'] = $aParams['cat'][$aParams['prefix'] . '_id'];
			$aParams['cat']['path'] = $aParams['cat'][$aParams['prefix'] . '_path'];
		}

		return $this->{$this->mCatUrlFunction}($aParams['cat']);
	}

	/**
	 * printSeoCatUrl prints category url on mod_rewrite enabled
	 *
	 * @param array $aCategory category information array
	 *
	 * @return string
	 */
	public function printCatUrl($aCategory)
	{
		global $esynConfig;

		$url = IA_URL;
		if (isset($aCategory['path']) && !empty($aCategory['path']))
		{
			if ($esynConfig->getConfig('lowercase_urls'))
			{
				$url .= strtolower($aCategory['path']) . '/';
			}
			else
			{
				$url .= $aCategory['path'] . '/';
			}
		}

		return $url;
	}

	/**
	 * printSeoHtmlCatUrl prints category url on mod_rewrite enabled and .html extension
	 *
	 * @param array $aCategory category information array
	 *
	 * @return string
	 */
	public function printHtmlCatUrl($aCategory)
	{
		global $esynConfig;

		$url = IA_URL;

		if (isset($aCategory['path']) && !empty($aCategory['path']))
		{
			if ($esynConfig->getConfig('lowercase_urls'))
			{
				$url .= strtolower($aCategory['path']) . '.html';
			}
			else
			{
				$url .= $aCategory['path'] . '.html';
			}
		}

		return $url;
	}

	/**
	 * printListingUrl displays listing url depending on configuration
	 *
	 * @param array $aParams['listing'] listing information array
	 *
	 * @return void
	 */
	public function printListingUrl($aParams)
	{
		if (isset($aParams['details']) && (true == $aParams['details']))
		{
			return $this->printForwardUrl($aParams['listing']);
		}

		return $this->{$this->mUrlFunction}($aParams['listing']);
	}

	/**
	 * printUrl prints listing url field value
	 *
	 * @param array $aListing listing informaiton array
	 *
	 * @return string
	 */
	public function printUrl($aListing)
	{
		return strtolower($aListing['url']);
	}

	/**
	 * printForwardUrl prints url to view listing details on mod_rewite disabled
	 *
	 * @param array $aListing listing information array
	 *
	 * @return string
	 */
	public static function printForwardUrl($aListing)
	{
		global $esynConfig;

		$url = IA_URL;

		if (isset($aListing['path']) && !empty($aListing['path']))
		{
			$url .= ($esynConfig->getConfig('lowercase_urls')) ? strtolower($aListing['path']) : $aListing['path'];
			$url .= ('/' == substr($aListing['path'], -1)) ? '' : '/';
		}

		if ($esynConfig->getConfig('lowercase_urls'))
		{
			$aListing['title_alias'] = strtolower($aListing['title_alias']);
		}

		$title_alias = !empty($aListing['title_alias']) ? $aListing['title_alias'] : esynUtil::convertStr(array('string' => $aListing['title']));

		$url .= $title_alias . '-l' . $aListing['id'] . '.html';

		return $url;
	}

	/**
	 * printAccountUrl prints url to view account details on mod_rewite disabled
	 *
	 * @param array $params account information array
	 *
	 * @return string
	 */
	public function printAccountUrl($params)
	{
		$usernameAlias = isset($params) ? $params['account'][$params['prefix'] . 'username'] : $params['account']['username'];

		return IA_URL . 'accounts/' . urlencode($usernameAlias) . '.html';
	}
}
