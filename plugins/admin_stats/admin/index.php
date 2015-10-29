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

define("IA_REALM", "admin_stats");

if (isset($_GET['action']))
{
	$esynAdmin->LoadClass("JSON");
	$json = new Services_JSON();

	// get stats by day of week
	if ('getlbd' == $_GET['action'])
	{
		$sql = "SELECT DAYOFWEEK( `date` ) dayday, COUNT(*) `num` ";
		$sql .= "FROM `".IA_DBPREFIX."listings` ";
		//$sql .= "WHERE (DATEDIFF( `date` , NOW( ) ) <=31) ";
		$sql .= "GROUP BY dayday LIMIT 0, 8 ";
		$stats = $esynAdmin->getAssoc($sql);
		
		$daysofweek = array(
			1 => $esynI18N['sunday'],
			2 => $esynI18N['monday'],
			3 => $esynI18N['tuesday'],
			4 => $esynI18N['wednesday'],
			5 => $esynI18N['thursday'],
			6 => $esynI18N['friday'],
			7 => $esynI18N['saturday']
		);
		
		for($i=1; $i<8; $i++)
		{
			$out[] = array(
				'days' => $daysofweek[$i],
				'submissions' => isset($stats[$i][0]['num']) ? (int)$stats[$i][0]['num'] : 0
			);
		}
	}

	// get stats by months
	if ('getlbm' == $_GET['action'])
	{
		$sql = "SELECT MONTH( `date` ) month, COUNT(*) `num` ";
		$sql .= "FROM `".IA_DBPREFIX."listings` ";
		//$sql .= "WHERE (DATEDIFF( `date` , NOW( ) ) <=31) ";
		$sql .= "GROUP BY month LIMIT 0 , 31 ";
		$stats = $esynAdmin->getAssoc($sql);
		
		$daysofweek = array(
			1 => $esynI18N['january'],
			2 => $esynI18N['february'],
			3 => $esynI18N['march'],
			4 => $esynI18N['april'],
			5 => $esynI18N['may'],
			6 => $esynI18N['june'],
			7 => $esynI18N['july'],
			8 => $esynI18N['august'],
			9 => $esynI18N['september'],
			10 => $esynI18N['october'],
			11 => $esynI18N['november'],
			12 => $esynI18N['december']
		);
		
		for($i=1; $i<13; $i++)
		{
			$out[] = array(
				'month' => $daysofweek[$i],
				'submissions' => isset($stats[$i][0]['num']) ? (int)$stats[$i][0]['num'] : 0
			);
		}
	}
	
	if (empty($out))
	{
		$out = '';
	}

	echo $json->encode($out);
	exit;
}