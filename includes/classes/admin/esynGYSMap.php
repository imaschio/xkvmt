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

class esynGYSMap extends esynAdmin
{

	var $mTable = '';

	var $out = '';

	var $path_to_file = '';

	function init ()
	{
		esynUtil::mkdir($this->path_to_file."yahoo");
		esynUtil::mkdir($this->path_to_file."google");
	}

	function getGoogleHeader ()
	{
		$header_google  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$header_google .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\n";
		$header_google .= '<url>';
		$header_google .= ' <loc><![CDATA['.IA_URL.']]></loc>';
		$header_google .= ' <changefreq>daily</changefreq>';
		$header_google .= ' <priority>0.9</priority>';
		$header_google .= '</url>'."\n";

		return $header_google;
	}

	function getGoogleFooter ()
	{
		return '</urlset>';
	}

	function getTotal()
	{
		/* Count all categories */
		$this->setTable('categories');
		$categories = $this->all("SQL_CALC_FOUND_ROWS `id`, `num_listings`, `num_all_listings`", "`status`='active'");
		$this->resetTable();

		$items['categories'] = $this->foundRows();

		/* Count all listings and listings pages */
		$num_listings = $this->mConfig['show_children_listings'] ? 'num_all_listings' : 'num_listings' ;
		$listings_per_page = $this->mConfig['num_index_listings'];

		$items['listings_pages'] = 0;
		$items['listings'] = 0;

		foreach ($categories as $key => $category)
		{
			$nl = $category['id'] > 0 ? $num_listings : 'num_listings';
			$num_pages = ceil($category[$nl]/$listings_per_page);
			$num_pages = $num_pages > 1 ? $num_pages - 1 : 0;
			$items['listings'] += $category['num_listings'];
			$items['listings_pages'] += $num_pages;
		}

		/* Count all pages */
		$this->setTable('pages');
		$items['pages']= $this->one("COUNT(`id`)", "`status`='active' AND `nofollow` = '0'");
		$this->resetTable();

		/* Count all accouns */
		$this->setTable('accounts');
		$items['accounts']= $this->one("COUNT(`id`)", "`status`='active'");
		$this->resetTable();

		return $items;
	}

	function writeGoogleHF ($aFilename, $aHf)
	{
		$hf = 'getGoogle'.$aHf;

		if (!$this->writeToFile($aFilename, $this->$hf()))
		{
			$error = true;
			$msg = "Cannot write to file ".$aFilename;
			echo "{error: '{$error}', msg: '{$msg}'}";
	        exit;
		}
	}

	function buildXML($aUrl, $aSitemap)
	{
		$feed = '';
		if (empty($aSitemap) || empty($aUrl))
		{
			return false;
		}

		if ('google' == $aSitemap)
		{
			$feed = "
				<url>
					<loc><![CDATA[{$aUrl}]]></loc>
					<changefreq>weekly</changefreq>
					<priority>0.5</priority>
				</url>
			";

			$_SESSION['num_records']++;

            if (30000 == $_SESSION['num_records'])
            {
                $this->writeGoogleHF($this->path_to_file."google".IA_DS."sitemap".$_SESSION['sm_file'].".xml", 'Footer');

                $_SESSION['sm_file']++;
                $_SESSION['num_records'] = 0;

                $this->writeGoogleHF($this->path_to_file."google".IA_DS."sitemap".$_SESSION['sm_file'].".xml", 'Header');
            }
		}
		elseif ('yahoo' == $aSitemap)
		{
			$feed = $aUrl."\n";
		}

		return $feed;
	}

	function buildCategoriesMap ($aStart, $aLimit, $aSitemap)
	{
		$this->setTable('categories');
		$categories = $this->all('`id`, `path`, `num_listings`, `num_all_listings`',"`status`='active' AND `id` > '{$_SESSION['start_categories']}' LIMIT {$aLimit}");
		$this->resetTable();

		$temp_c = end($categories);
		$_SESSION['start_categories'] = $temp_c['id'];

		$listings_per_page = $this->mConfig['num_index_listings'];
		$jt_cat_url = $this->mConfig['use_html_path'] ? '.html' : '/';
		$jt_cat_url_page = $this->mConfig['use_html_path'] ? '_' : '/index';

		$num_listings = $this->mConfig['show_children_listings'] ? 'num_all_listings' : 'num_listings' ;

		$feed = '';

		if (!empty($categories))
		{
			foreach ($categories as $category)
			{
				$nl = $category['id'] > 0 ? $num_listings : 'num_listings';
				$numPagesForCat = ceil($category[$nl]/$listings_per_page);

				if ($category['id'] > 0)
				{
					$path = IA_URL.$category['path'].$jt_cat_url;

					$feed .= $this->buildXML($path, $aSitemap);
				}

				// We have to add as many pages to the Sitemap
				// as the category contains.

				if ($numPagesForCat > 1)
				{
					$this->setTable('gysmap');
					$insert = array();
					for ($i=2; $i <= $numPagesForCat; $i++)
					{
						$path = IA_URL.$category['path'].$jt_cat_url_page.$i.'.html';
						$insert[] = array('path' => $path);
					}
					$this->insert($insert);
				    $this->resetTable();
				}
			}
		}

		return $feed;
	}

	function buildListingsPagesMap($aStart, $aLimit, $aItemsCount, $aSitemap)
	{
		$feed = '';
		$final = $aStart + $aLimit;
		$final = $final < $aItemsCount ? $final : $aItemsCount;

		$this->setTable('gysmap');
		$paths = $this->all("*", "1", array(), $aStart, $aLimit);
		foreach ($paths as $path)
		{
			$feed .= $this->buildXML($path['path'], $aSitemap);
		}
		$this->resetTable();

		return $feed;
	}

	function buildListingsMap ($aStart, $aLimit, $aSitemap)
	{
		$sql = "SELECT `cat`.`path`, `link`.`id` `id`, `link`.`title`, `link`.`status` as link_status, `cat`.`status` as cat_status ";
		$sql .= "FROM `".$this->mPrefix."categories` AS `cat` ";
		$sql .= "LEFT JOIN `".$this->mPrefix."listings` AS `link` ";
		$sql .= "ON `link`.`category_id` = `cat`.`id` ";
		//$sql .= "WHERE `cat`.`status` = 'active' ";
		//$sql .= "AND `link`.`status` = 'active' ";
		$sql .= "WHERE `link`.`id` > '{$_SESSION['start_listings']}' ";
		$sql .= "ORDER BY `link`.`id` ";
		$sql .= "LIMIT ".$aLimit;

		$listings = $this->getAll($sql);

		$feed = '';

		include_once IA_CLASSES . 'esynLayout.php';

		if (!empty($listings))
		{
			foreach ($listings as $listing)
			{
				if ('active' == $listing['link_status'] && 'active' == $listing['cat_status'])
				{
					$path = esynLayout::printForwardUrl($listing);
					$feed .= $this->buildXML($path, $aSitemap);
				}
			}
			$temp_l = end($listings);
			$_SESSION['start_listings'] = $temp_l['id'];
		}

		return $feed;
	}

	function buildPagesMap($aStart, $aLimit, $aSitemap)
	{
		$this->setTable("pages");
		$pages = $this->all("`id`,`name`,`order`,`custom_url`,`unique_url`,`nofollow`", "`status` = 'active' AND `nofollow` = '0' AND `readonly` = '0' AND `group` != 'miscellaneous' ORDER BY `order` LIMIT {$aStart}, {$aLimit}");
		$this->resetTable();

		$feed = '';

		if (!empty($pages))
		{
			foreach ($pages as $page)
			{
				$path = IA_URL;

				if (!empty($page['unique_url']))
				{
					$path .= $page['unique_url'];
				}
				else
				{
					$path .= !empty($page['custom_url']) ? $page['custom_url'].'.html' : $page['name'].'.html';
				}

				$feed .= $this->buildXML($path, $aSitemap);
			}
		}

		return $feed;
	}

	function buildAccountsMap ($aStart, $aLimit, $aSitemap)
	{
		$this->setTable("accounts");
		$accounts = $this->all("`id`,`username`", "`status` = 'active' ORDER BY `date_reg` LIMIT {$aStart}, {$aLimit}");
		$this->resetTable();

		$feed = '';

		if (!empty($accounts))
		{
			foreach ($accounts as $account)
			{
				$path = IA_URL.'accounts/'.urlencode($account['username']).'.html';
				$feed .= $this->buildXML($path, $aSitemap);
			}
		}

		return $feed;
	}

	function deleteOldSitemaps ($aType_sitemap)
	{
		if ('google' == $aType_sitemap)
		{
			$this->query('TRUNCATE `'.$this->mPrefix.'gysmap`');

			$sitemap_files = scandir($this->path_to_file."google".IA_DS);
			$sitemap_files = array_slice($sitemap_files,2);

			if (is_array($sitemap_files) && !empty($sitemap_files))
			{
				foreach ($sitemap_files as $sitemap_file)
				{
					unlink($this->path_to_file."google".IA_DS.$sitemap_file);
				}
			}
		}
		if ('yahoo' == $aType_sitemap)
		{
			if (file_exists($this->path_to_file."yahoo".IA_DS."urllist.txt"))
			{
				unlink($this->path_to_file."yahoo".IA_DS."urllist.txt");
			}
		}
	}

	function writeToFile ($aFile, $aStr, $aMode = "a")
	{
		if (!$fp = $this->openFile($aFile, $aMode))
		{
			return false;
		}

		if (fwrite($fp, $aStr) === false)
		{
	        return false;
	    }
	    return true;
	}

	function openFile ($aFile, $aMode)
	{
		if (!$fp = fopen($aFile,$aMode))
		{
			return false;
		}
		return $fp;
	}

	function getGoogleSMIndex ()
	{
		$aFile = $_SESSION['sm_file'];

		$str  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$str .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

		while ($aFile > 0)
		{
			$str .= "<sitemap>\n";
      		$str .= "<loc><![CDATA[".IA_URL."sitemap{$aFile}.xml]]></loc>\n";
      		$str .= "<lastmod>".date("Y-m-d")."</lastmod>\n";
   			$str .= "</sitemap>\n";
   			$aFile--;
		}

		$str .= '</sitemapindex>';

		if (!$this->writeToFile($this->path_to_file."google".IA_DS."sitemap_index.xml", $str, "w"))
		{
		     $error = "Cannot write to file ".$this->path_to_file."google".IA_DS."sitemap_index.xml";
		     return $error;
		}
	}
}
