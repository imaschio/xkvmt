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

global $esynConfig, $eSyndiCat, $what, $total_listings, $total_categories;

// if word exists
if (!empty($what))
{
	$eSyndiCat->setTable('searches');
	$row = $eSyndiCat->row("`search_id` as `id`, `search_count` as `count`","`search_word`='".$what."'");
	$search_result = array(
		'listings' => $total_listings,
		'categories' => $total_categories
	);

	$eSyndiCat->startHook('pluginSearchesLogSearchResults');

	$fields = array(
		'search_result' => serialize($search_result)
	);
	//if no rows with this search
	if (!$row)
	{
		$fields['search_word'] = $what;
		// insert in DB new row with search word
		$eSyndiCat->insert($fields, array('last_search'=>"NOW()"));

	}
	else
	{
		$fields['search_count'] = $row['count']+1;
		// update row in DB
		$eSyndiCat->update($fields,"`search_id` = '".$row['id']."'", array(),array('last_search'=>"NOW()"));
	}
	$eSyndiCat->resetTable();
}