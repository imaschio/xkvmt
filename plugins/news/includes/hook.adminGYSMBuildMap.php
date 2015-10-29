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

global $esynConfig;

if ($esynConfig->getConfig('news'))
{
	global $item, $sitemap, $start, $limit, $type_sitemap, $esynAdmin;

	if ('news' == $item)
	{
		$esynAdmin->setTable("news");
		$news = $esynAdmin->all("`id`, `alias`", "`status`='active' ORDER BY `date` DESC", array(), $start, $limit);
		$esynAdmin->resetTable();

		if (!empty($news))
		{
			foreach ($news as $onenews)
			{
				if ('google' == $type_sitemap)
				{
					$sitemap .= '<url>'."\n".'<loc><![CDATA['.IA_URL;
					$sitemap .= 'news/'.$onenews['id'].'-'.$onenews['alias'].'.html';

					$sitemap .= ']]></loc>'."\n".'<changefreq>monthly</changefreq>'."\n";
					$sitemap .= '<priority>0.6</priority>'."\n".'</url>'."\n";
				}
			    elseif ('yahoo' == $type_sitemap)
			    {
	                $sitemap .= IA_URL.'news/'.$onenews['id'].'-'.$onenews['alias'].'.html'."\n";
			    }
			}
		}
	}
}