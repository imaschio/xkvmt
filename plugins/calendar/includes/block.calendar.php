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

global $eSyndiCat;

$eSyndiCat->loadPluginClass("Calendar", "calendar", "esyn");
$esynCalendar = new esynCalendar();

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? $_GET['month'] : date('m');

$year = esynSanitize::sql($year);
$month = esynSanitize::sql($month);

$days = $esynCalendar->getActiveDays($year, $month);

$links = array();

if ($days)
{
	foreach($days as $key => $val)
	{
		$links[(int)$val['date']] = array(IA_URL."date/{$year}/{$month}/{$val['date']}/", 'linked-day');
	}
}

echo $esynCalendar->generateCalendar($year, $month, $links);