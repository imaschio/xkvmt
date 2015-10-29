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

define('IA_REALM', "order_change");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

if (!$_SESSION['frontendManageMode'])
{
	esynUtil::accessDenied();
}

if (empty($_GET['type']))
{
	die("Type required");
}

$eSyndiCat->setTable("blocks");

$positions = $eSyndiCat->onefield("`position`", "1 GROUP BY `position`");

$positions[] = "left";
$positions[] = "right";
$positions[] = "center";
$positions[] = "user1";
$positions[] = "user2";
$positions[] = "top";
$positions[] = "bottom";
$positions[] = "verybottom";

$positions = array_unique($positions);

switch($_GET['type'])
{
	case "blocks":
		foreach($positions as $p)
		{
			$blockPosition = $p . 'Blocks';
			if (isset($_GET[$blockPosition]) && is_array($_GET[$blockPosition]) && !empty($_GET[$blockPosition]))
			{
				foreach($_GET[$blockPosition] as $k => $v)
				{
					$v = explode("_", $v);

					if (ctype_digit($v[1]))
					{
						$eSyndiCat->update(array(
							"id"		=> $v[1],
							"position"	=> $p,
							"order"		=> $k + 1
						));
					}
				}
			}
		}
}
$eSyndiCat->resetTable();

echo "Ok";
die();
