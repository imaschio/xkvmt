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

global $esynConfig, $eSyndiCat;

$eSyndiCat->factory('Category');

global $esynCategory;

// expire action
$sql = "SELECT `l`.*, `c`.`path` "
	 . "FROM `{$eSyndiCat->mPrefix}listings` `l` "
	 . "LEFT JOIN `{$eSyndiCat->mPrefix}categories` `c` "
	 . "ON `l`.`category_id` = `c`.`id` "
	 . "WHERE "
	 . "DATE(`expire_date`) <= CURRENT_DATE "
	 . "AND `l`.`status` = 'active'";

$eSyndiCat->setTable('listings');
if ($listings = $eSyndiCat->getAll($sql))
{
	foreach ($listings as $listing)
	{
		if (in_array($listing['expire_action'], array('approval', 'banned', 'suspended')))
		{
			$eSyndiCat->update(array('status' => $listing['expire_action']), "`id` = '{$listing['id']}'");
		}
		elseif (in_array($listing['expire_action'], array('regular', 'featured', 'partner')))
		{
			if ('regular' == $listing['expire_action'])
			{
				$fields = array(
					'sponsored' => '0',
					'partner'   => '0',
					'featured'  => '0',
					'plan_id'   => '0',
				);

				$eSyndiCat->update($fields, "`id` = '{$listing['id']}'");
			}
			else
			{
				$eSyndiCat->update(array($listing['expire_action'] => '1'), "`id` = '{$listing['id']}'");
			}
		}
		elseif ('remove' == $listing['expire_action'])
		{
			$eSyndiCat->delete("`id` = '{$listing['id']}'");
		}
		$cat_ids[] = $listing['category_id'];
	}
	$esynCategory->adjustNumListings($cat_ids);
}
$eSyndiCat->resetTable();

unset($listings);

// expire notification
$sql = "SELECT `l`.*, `c`.`path` "
	 . "FROM `{$eSyndiCat->mPrefix}listings` `l` "
	 . "LEFT JOIN `{$eSyndiCat->mPrefix}categories` `c` "
	 . "ON `l`.`category_id` = `c`.`id` "
	 . "WHERE "
	 . "DATE(`l`.`expire_date` - INTERVAL `l`.`expire_notif` DAY) = CURRENT_DATE "
	 . "AND `l`.`status` = 'active'";

$eSyndiCat->setTable('listings');
if ($listings = $eSyndiCat->getAll($sql))
{
	foreach ($listings as $listing)
	{
		$text = '';
		$salt = '';

		$replace = array();

		if (in_array($listing['expire_action'], array('approval', 'banned', 'suspended')))
		{
			$text = "Once it is expired the status will be changed to {$listing['expire_action']}.";
		}
		elseif (in_array($listing['expire_action'], array('regular', 'featured', 'partner')))
		{
			$text = "Once it is expired the type will be changed to {$listing['expire_action']}.";
		}
		elseif ('remove' == $listing['expire_action'])
		{
			$text = 'Once it is expired listing will be removed';
		}

		$salt = esynUtil::getNewToken(16);

		$replace = array(
			"expire_action" => $text,
			"salt" => $salt,
			"expire_date" => strftime($esynConfig->getConfig('date_format'), strtotime($listing['expire_date'])),
		);

		$eSyndiCat->mMailer->add_replace($replace);
		$eSyndiCat->mMailer->AddAddress($listing['email']);

		$result = $eSyndiCat->mMailer->Send('payment_expiration', $listing['account_id'], $listing);

		$eSyndiCat->update(array('expire_salt' => $salt), "`id` = '{$listing['id']}'");
	}
}
$eSyndiCat->resetTable();

// check expired accounts
$eSyndiCat->setTable('accounts');
if ($accounts = $eSyndiCat->all('*', "DATE(`sponsored_expire_date`) <= CURRENT_DATE "))
{
	foreach ($accounts as $account)
	{
		$update = array('id' => $account['id'], 'plan_id' => '', 'sponsored_start' => '0000-00-00 00:00:00');
		$eSyndiCat->update($update);
	}
}
$eSyndiCat->resetTable();
