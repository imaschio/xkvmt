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

if (!isset($_GET['idcategory']) || !preg_match("/^(\d)+((_crs)?)$/", $_GET['idcategory']))
{
	header("HTTP/1.1 404 Not found");
	die('Powered By eSyndiCat');
}

if (isset($_GET['idplan']) && preg_match("/\D/", $_GET['idplan']))
{
	header("HTTP/1.1 404 Not found");
	die('Powered By eSyndiCat');
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$eSyndiCat->factory("Listing");

if (isset($_GET['action']))
{
	if ('getfields' == $_GET['action'])
	{
		$id_category = (int)$_GET['idcategory'];
		$id_plan = isset($_GET['idplan']) ? (int)$_GET['idplan'] : null;
		$id_listing = isset($_GET['idlisting']) ? (int)$_GET['idlisting'] : null;
		$labelsType = array('combo', 'radio', 'checkbox');

		$part = isset($_GET['part']) && !empty($_GET['part']) && in_array($_GET['part'], array('suggest', 'edit')) ? $_GET['part'] : 'suggest';

		$eSyndiCat->setTable('categories');
		$category = $eSyndiCat->row("*", "`id` = '{$id_category}'");
		$eSyndiCat->resetTable();

		$eSyndiCat->setTable('plans');
		$plan = $eSyndiCat->row("*", "`id` = '{$id_plan}'");
		$eSyndiCat->resetTable();

		$field_groups = $eSyndiCat->assoc('`name`, `collapsible`, `collapsed`', "FIND_IN_SET('{$part}', `pages`) ORDER BY `order`", 'field_groups');

		$fields = $esynListing->getFieldsByPage($part, $category, $plan);

		if ($id_listing > 0)
		{
			$listing = $esynListing->getListingById($id_listing, (int)$esynAccountInfo['id']);
		}

		$out = array();

		if (!empty($fields))
		{
			foreach($fields as $key => $field)
			{
				$fields[$key]['title'] = isset($esynI18N['field_' . $field['name']]) ? $esynI18N['field_' . $field['name']] : 'Unknown';

				if ('email' == $fields[$key]['name'])
				{
					$fields[$key]['default'] = $esynAccountInfo ? $esynAccountInfo['email'] : '';
				}

				if (in_array($field['type'], $labelsType))
				{
					$values = explode(',', $field['values']);

					foreach($values as $value)
					{
						$fields[$key]['labels'][$value] = $esynI18N['field_' . $field['name'] . '_' . $value];
					}
				}

				if (isset($listing))
				{
					$fields[$key]['default'] = $listing[$field['name']];
				}

				if (!empty($field['group']) && in_array($field['group'], array_keys($field_groups)))
				{
					$out[$field['group']]['fields'][] = $fields[$key];
				}
				else
				{
					$out['non_group']['fields'][] = $fields[$key];
				}
			}

			$temp = array();
			if (!empty($field_groups))
			{
				foreach ($field_groups as $groupName => $group)
				{
					if (isset($out[$groupName]['fields']) && !empty($out[$groupName]['fields']))
					{
						$temp[$groupName] = $group;
						$temp[$groupName]['fields'] = $out[$groupName]['fields'];
					}
				}
			}

			if (!empty($out['non_group']))
			{
				$temp['non_group'] = $out['non_group'];
			}

			unset($out['non_group']);

			$out = $temp;
		}

		echo esynUtil::jsonEncode($out);
	}

	exit;
}
