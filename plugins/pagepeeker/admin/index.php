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

if ($_GET['type'] == 'generatethumbs')
{
	$start = (int)$_GET['start'];
	$limit = (int)$_GET['limit'];
	$level = (int)$_GET['level'];

	global $esynAdmin, $esynListing;

	$esynAdmin->setTable('listings');
	$sql = "SELECT SQL_CALC_FOUND_ROWS `url`, `domain` FROM {$esynAdmin->mPrefix}listings LIMIT {$start}, {$limit}";
	$listings = $esynAdmin->getAll($sql);

	$total_listings = $esynAdmin->one("COUNT(`id`)");
	$esynAdmin->resetTable();

	$cnt = 0;

	if (isset($listings) && !empty($listings))
	{
		foreach ($listings as $listing)
		{
			$cnt++;

			$domain = !empty($listing['domain']) ? $listing['domain'] : esynUtil::getDomain($listing['url']);

			if ($domain)
			{
				$path = IA_UPLOADS . 'thumbnails' ;
				if (!file_exists($path) || !is_dir($path))
				{
					@mkdir($path);
				}

				$thumbname = $domain . '.' . $esynAdmin->mConfig['pagepeeker_format'];
				$ch = curl_init('http://free.pagepeeker.com/v2/thumbs.php?size=m&url=' . $domain);
				$fp = fopen($path . IA_DS . $thumbname, 'wb');
				curl_setopt($ch, CURLOPT_FILE, $fp);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_exec($ch);
				curl_close($ch);
				fclose($fp);
			}
		}
		$out['msg'] = 'ok';
		$out['num_updated'] = $cnt;
	}

	echo esynUtil::jsonEncode($out);
	exit;
}