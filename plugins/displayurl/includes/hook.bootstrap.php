<?php

global $eSyndiCat, $esynConfig, $esynSmarty, $id;

$eSyndiCat->factory("Listing");

global $esynListing;


/** get sponsored listings **/
if ($esynConfig->getConfig('sponsored_listings'))
{
	$listings = $esynListing->getSponsored($id, 0, $esynConfig->getConfig('num_sponsored_display'));
	
	if(!empty($listings))
	{
		foreach ($listings as $key => $listing)
		{
			if (!empty($listing['display_url']) && 'http://' != $listing['display_url'])
			{
				$listings[$key]['real_url'] = $listing['url'];
				$listings[$key]['url'] = $listing['display_url'];
			}

			$esynSmarty->assign('sponsored_listings', $listings);
		}
	}
}

$listings = $esynListing->getFeatured($id, 0, $esynConfig->getConfig('num_featured_display'));

if(!empty($listings))
{
	foreach ($listings as $key => $listing)
	{
		if (!empty($listing['display_url']) && 'http://' != $listing['display_url'])
		{
			$listings[$key]['real_url'] = $listing['url'];
			$listings[$key]['url'] = $listing['display_url'];
		}
	}

	$esynSmarty->assign('featured_listings', $listings);
}

$listings = $esynListing->getPartner($id, 0, $esynConfig->getConfig('num_partner_display'));

if(!empty($listings))
{
	foreach ($listings as $key => $listing)

	{
		if (!empty($listing['display_url']) && 'http://' != $listing['display_url'])
		{
			$listings[$key]['real_url'] = $listing['url'];
			$listings[$key]['url'] = $listing['display_url'];
		}
	}

	$esynSmarty->assign('partner_listings', $listings);
}