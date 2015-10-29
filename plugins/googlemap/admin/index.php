<?php

if ($_GET['type'] == 'updatecoordinates')
{
	$start = (int)$_GET['start'];
	$limit = (int)$_GET['limit'];
	$level = (int)$_GET['level'];

	global $esynAdmin, $esynListing;

	$esynAdmin->esynFactory("Geocoder", "googlemap");
	global $esynGeocoder;

	$esynAdmin->setTable('listings');
	$sql = "SELECT SQL_CALC_FOUND_ROWS `id`,`title`,`address`,`city`,`zip`,`state`,`country` ";
	$sql .= "FROM {$esynAdmin->mPrefix}listings WHERE `latitude` = '' OR `longitude` = '' LIMIT {$start}, {$limit}";
	$listings = $esynAdmin->getAll($sql);
	$esynAdmin->resetTable();

	$cnt = 0;

	if (isset($listings) && !empty($listings))
	{
		foreach ($listings as $listing)
		{
			$cnt++;

			$address = $listing['address'] . ', ' . $listing['city'] . ', ' . $listing['state'] . ', ' . $listing['zip'] . ', ' . $listing['country'];

			$latlong = $esynGeocoder->getLocation($address);

			if ($latlong)
			{
				$sql = "UPDATE `{$esynAdmin->mPrefix}listings` ";
				$sql .= "SET `latitude` = '{$latlong['lat']}', `longitude` = '{$latlong['lng']}'";
				$sql .= " WHERE `id` = '{$listing['id']}'";

				$esynAdmin->query($sql);
			}
		}
		$out['msg'] = 'ok';
		$out['num_updated'] = $cnt;
	}

	echo esynUtil::jsonEncode($out);
	exit;
}