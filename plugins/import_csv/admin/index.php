<?php
/******************************************************************************
 *
 *	 COMPANY: Intelliants LLC
 *	 PROJECT: eSyndiCat Directory Software
 *	 VERSION: 3.1.1
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
 *	 Copyright 2007-2011 Intelliants LLC
 *	 http://www.intelliants.com/
 *
 ******************************************************************************/

define('IA_REALM', "import_csv");
ini_set('auto_detect_line_endings', true);

$esynAdmin->factory('Category', 'Listing');
$msg = '';
$error = false;

$statuses = array(
	'approval' => $esynI18N['approval'],
	'banned' => $esynI18N['banned'],
	'suspended' => $esynI18N['suspended'],
	'active' => $esynI18N['active']
);

function getListingFields()
{
	global $esynAdmin;
	$out = array();

	$columns = $esynAdmin->getAll("SHOW COLUMNS FROM `{$esynAdmin->mPrefix}listings`");

	foreach($columns as $c)
	{
		$out[] = $c['Field'];
	}

	return $out;
}

function makeFieldsDrop()
{
	global $esynI18N;
	$out = '';
	$fields = getListingFields();

	$out = "<select name=\"field[]\">";
    $out .= "<option value=\"\">".$esynI18N['select']."</option>";
    $out .= "<option value=\"category_path\">category_path</option>";
	foreach($fields as $field)
	{
		if ('date' != $field && 'status' != $field)
		{
			$out .= "<option value=\"{$field}\">{$field}</option>";
		}
	}
	$out .= "</select>";

	return $out;
}

$csvFile = IA_TMP.'csvtemp.csv';

if (!file_exists($csvFile))
{
	$fp = fopen($csvFile, 'w');
	fclose($fp);
}

if (isset($_POST['import']))
{
	foreach($_POST['index'] as $indexKey => $indexValue)
	{
		if ('1' == $indexValue)
		{
			foreach($_POST['field'] as $fieldKey => $fieldValue)
			{
				if (!empty($fieldValue))
				{
                    if ('category_path' == $fieldValue)
                    {
                        $path = ($_POST['csv'][$indexKey][$fieldKey]);
                        $path = preg_replace("/[^a-z0-9\/-]+/i", "-", $path);
                        $path = trim($path, "-/ ");

                        if ($esynConfig->getConfig('lowercase_urls'))
                        {
                            $path = strtolower($path);
                        }

                        $category_id = $esynCategory->one('id', "`path` = '$path'");

                        if ($category_id)
                        {
                            $listings[$indexKey]['category_id'] = $category_id;
                        }
                    }
                    else
                    {
                        $listings[$indexKey][$fieldValue] = ($_POST['csv'][$indexKey][$fieldKey]);
                    }
				}
			}
			if (!isset($listings[$indexKey]['category_id']))
			{
				$listings[$indexKey]['category_id'] = $_POST['category_id'];
			}
			$listings[$indexKey]['status'] = $_POST['status'];
			$listings[$indexKey]['email'] = isset($listings[$indexKey]['email']) ? $listings[$indexKey]['email'] : '';
		}
	}

	foreach($listings as $listing)
	{
		$esynListing->insert($listing);
		$cat_ids[] = $listing['category_id'];
	}
	$esynCategory->adjustNumListings($cat_ids);

	esynMessages::setMessage($esynI18N['listings_added']);
	esynUtil::reload();
}

$gNoBc = false;

$gBc[1]['title']= $esynI18N['import_csv'];
$gBc[1]['url']	= 'controller.php?plugin=import_csv';
$gTitle 		= $esynI18N['import_csv'];;

require_once(IA_ADMIN_HOME.'view.php');

if (isset($_POST['parse']))
{
	if ('\t' == $_POST['delimeter'])
	{
		$_POST['delimeter'] = "\t";
	}

	if (is_uploaded_file($_FILES['csvfile']['tmp_name']))
	{
		if (!move_uploaded_file($_FILES['csvfile']['tmp_name'], $csvFile))
		{

			$error = true;
			$msg = $esynI18N['error_upload_csv'];
		}
	}
	else
	{
		$error = true;
		$msg = $esynI18N['error_upload_csv'];
	}

	$csvDelimeter = $_POST['delimeter'];
	$fp = fopen($csvFile, "r");

	$csvData = array();
	$row = '';
	while (($row = fgetcsv($fp, 4000, $csvDelimeter)) !== FALSE)
	{
		$row = esynSanitize::notags($row);
		$row = str_replace('"', "", $row);
		$row = str_replace("'", "", $row);
        $csvData[] = $row;
	}

	$csvNumRows = count($csvData[0]);
	$csvFieldsMenu = makeFieldsDrop();

	$esynSmarty->assign('csvNumRows', $csvNumRows);
	$esynSmarty->assign('statuses', $statuses);

	$csvFieldsMenus = '';
	for($i = 0; $i < $csvNumRows; $i++)
	{
		$csvFieldsMenus .= "<td>$csvFieldsMenu</td>";
	}

	$esynSmarty->assign('csvFieldsMenus', $csvFieldsMenus);
	$esynSmarty->assign('csvData', $csvData);

	esynMessages::setMessage($msg, $error);
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');