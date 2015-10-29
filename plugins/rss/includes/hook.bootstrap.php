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

global $category, $esynSmarty, $eSyndiCat;

$eSyndiCat->loadPluginClass("Rss", 'rss', "esyn");
$esynRss = new esynRss();
$esynRss->ifRss();
$rss = $esynRss->getRss($category['id']);

if (!empty($rss))
{
	foreach($rss as $value)
	{
		$refresh = 1 * 60 * 60;
		if (isset($value['refresh']))
		{
			$refresh = (int)$value['refresh'];
			unset($value['refresh']);
		}
		$refresh = max(10 *60, $refresh);

		$url = trim($value['url']);

		if (!empty($url))
		{
			$filemtime = 0;
			$cache = IA_TMP . 'cache' . IA_DS . 'rss_' . $value['id'];

			if (file_exists($cache))
			{
				$filemtime = filemtime($cache) + $refresh;
			}

			if (time() > $filemtime)
			{
				$rssContent = $esynRss->parse($value['url']);

				$eSyndiCat->mCacher->write('rss_' . $value['id'], $rssContent);
			}
			else
			{
				$rssContent = unserialize(file_get_contents($cache));
			}

			if (is_array($rssContent))
			{
				$rssContent = array_slice($rssContent, 0, $value['num']);
				$esynSmarty->assign("rss_{$value['id']}", $rssContent);
			}
		}
	}
}