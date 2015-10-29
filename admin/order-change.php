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

$valid_types = array('adminblocks');

if (empty($_GET['type']) && !in_array($_GET['type'], $valid_types))
{
	die("Valid type required");
}

esynUtil::checkAccess();

switch($_GET['type'])
{
	case 'adminblocks':
		if (isset($_GET['left-column']) && is_array($_GET['left-column']) && !empty($_GET['left-column']))
		{
			if (!empty($currentAdmin['state']))
			{
				$state = unserialize($currentAdmin['state']);
			}

			$o = array();

			foreach($_GET['left-column'] as $order => $v)
			{
				$name = str_replace('menu_box_', '', $v);
				$open = isset($_GET['amenu_' . $name]) ? $_GET['amenu_' . $name] : '1';

				$o[] = $name;
			}

			$state['admin_blocks_order'] = $o;

			$currentAdmin['state'] = $state = serialize($state);

			$esynAdmin->setTable('admins');
			$esynAdmin->update(array('state' => $state), "`id` = '{$currentAdmin['id']}'");
			$esynAdmin->resetTable();
		}

		break;
	case 'menu_close':
		if (isset($_GET['state']) && is_array($_GET['state']) && !empty($_GET['state']))
		{
			if (!empty($currentAdmin['state']))
			{
				$state = unserialize($currentAdmin['state']);
			}

			$o = array();

			foreach($_GET['state'] as $name => $v)
			{
				$o[$name] = $v;
			}

			$state['admin_blocks_close'] = $o;

			$currentAdmin['state'] = $state = serialize($state);

			$esynAdmin->setTable('admins');
			$esynAdmin->update(array('state' => $state), "`id` = '{$currentAdmin['id']}'");
			$esynAdmin->resetTable();
		}

		break;
}

echo 'ok';
exit;
