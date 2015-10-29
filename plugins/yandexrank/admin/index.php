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

if (isset($_POST['start']) && ('recountyandex' && $_POST['action']))
{
	global $esynAdmin;

	$esynAdmin->loadClass("JSON");
	$json = new Services_JSON();

	$start = isset($_POST['start']) ? $_POST['start'] : 0;
	$limit = 3;

    $esynAdmin->setTable('listings');
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `{$esynAdmin->mPrefix}listings` WHERE `status` = 'active' LIMIT {$start}, {$limit}";
    $listings = $esynAdmin->getAll($sql);

	$esynAdmin->loadPluginClass('YandexRank', 'yandexrank', 'esyn');
	$esynYandexRank = new esynYandexRank();

    if (!isset($_SESSION['total_listings']))
    {
        $_SESSION['total_listings'] = $esynAdmin->one("COUNT(`id`)", "`status` = 'active'");
    }
	$esynAdmin->resetTable();

    if (isset($listings) && !empty($listings))
    {
        foreach ($listings as $listing)
        {
            $domain = $listing['domain'];

            if (empty($domain) && !empty($listing['url']))
            {
                $domain = esynUtil::getDomain($listing['url']);
                $esynAdmin->setTable('listings');
                $esynAdmin->update(array("domain" => $domain), "`id` = '".$listing['id']."'");
                $esynAdmin->resetTable();
            }

            if (!empty($domain))
            {
                $ya_rank = intval($esynYandexRank->getYandexRank($domain));

                if ($ya_rank)
                {
                    $esynYandexRank->setYandexRank($ya_rank, $listing['id'] );
                }
            }
        }
    }

    $start = empty($listings) || ($start+$limit) > $_SESSION['total_listings'] ? 0 : $start + $limit;
    $percent = ($start*100)/$_SESSION['total_listings'];
    $percent = round($percent, 2);

	$percent = isset($percent) ? $percent : 0;

	$msg = "<div>Progress: {$percent}%</div>";

	$out['num'] = isset($start) ? $start : 0;
	$out['msg'] = $msg;
	$out['percent'] = $percent;

	echo $json->encode($out);
	exit;
}