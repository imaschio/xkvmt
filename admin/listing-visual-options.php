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

define('IA_REALM', 'listing_visual_options');

if (isset($_POST['save']))
{
	$esynAdmin->setTable('listing_visual_options');

	// set all to inactive
	$esynAdmin->update(array('show' => '0'));

	foreach ($_POST['options'] as $key => $value)
	{
		$esynAdmin->update(array('show' => $_POST['show'][$key] || 0, 'value' => $_POST[$key], 'price' => $_POST['price'][$key]), "`name` = '" . $key . "'");
	}
	$esynAdmin->resetTable();

	esynMessages::setMessage($esynI18N['changes_saved']);
}

if (isset($_GET['action']))
{
	$out = array('data' => '', 'total' => 0, 'error' => false);

	if ('upload_icon' == $_GET['action'])
	{
		$imgtypes = array(
			"image/gif"		=> "gif",
			"image/jpeg"	=> "jpg",
			"image/pjpeg"	=> "jpg",
			"image/png"		=> "png"
		);

		if (is_uploaded_file($_FILES['icon']['tmp_name']))
		{
			$ext = strtolower(substr($_FILES['icon']['name'], -3));

			// if 'jpeg'
			if ($ext == 'peg')
			{
				$ext = 'jpg';
			}

			if (!array_key_exists($_FILES['icon']['type'], $imgtypes) || !in_array($ext, $imgtypes))
			{
				$out['error'] = true;

				$images_types = join(",", array_unique($imgtypes));
				$tmp_msg = str_replace("{types}", $images_types, $esynI18N['wrong_image_type']);
				$out['msg'] = strip_tags(str_replace("{name}", 'Icon', $tmp_msg));
			}
			else
			{
				// convert image name to lower case
				$_FILES['icon']['name'] = strtolower($_FILES['icon']['name']);
				$upload_dir = IA_UPLOADS . "bid_icons";

				if (!is_writable(IA_HOME . "uploads"))
				{
					if (chmod(IA_HOME . "uploads", 777))
					{
						$out['error'] = true;
						$out['msg'] = $esynI18N['bid_uploads_isnt_writable'];
					}
				}
				if (!is_dir($upload_dir))
				{
					if (!mkdir($upload_dir))
					{
						$out['error'] = true;
						$out['msg'] = $esynI18N['bid_cant_create'];
					}
				}
				elseif (!is_writable($upload_dir))
				{
					if (chmod($upload_dir, 777))
					{
						$out['error'] = true;
						$out['msg'] = $esynI18N['bid_folder_isnt_writable'];
					}
				}

				if (move_uploaded_file($_FILES['icon']['tmp_name'], $upload_dir . IA_DS . $_FILES['icon']['name']))
				{
					$out['error'] = false;
					$out['msg'] = $esynI18N['icon'] . "&nbsp;" . $_FILES['icon']['name'] . '&nbsp;' . $esynI18N['uploaded'];
					$out['src'] = 'uploads' . IA_DS . 'bid_icons' . IA_DS . $_FILES['icon']['name'];

					$esynAdmin->setTable('listing_visual_options');
					$esynAdmin->update(array("value" => $out['src']), "`name` = '" . $_GET['icon'] . "'");
					$esynAdmin->resetTable();
				}
				else
				{
					$out['error'] = true;
					$out['msg'] = $esynI18N['error'] . "&nbsp;" . $esynI18N['icon'] . '&nbsp;' . $_FILES['icon']['name'] . '&nbsp;' . $esynI18N['not_uploaded'];
				}
			}
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynI18N['bid_icon_error'];
		}

		$out['success'] = true;
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gBc[0]['title']= $esynI18N['manage_plans'];
$gBc[0]['url'] = 'controller.php?file=plans';

$gBc[1]['title']= $esynI18N['manage_visual_options'];

$gTitle = $gBc[1]['title'];

$actions[] = array(
	'url' => 'controller.php?file=plans&amp;do=add',
	'icon' => 'add.png',
	'label' => $esynI18N['create'],
);

$actions[] = array(
	'url' => 'controller.php?file=plans',
	'icon' => 'view.png',
	'label' => _t('manage_plans'),
);

$actions[] = array(
	'url' => 'controller.php?file=listing-visual-options',
	'icon' => 'tools.png',
	'label' => $esynI18N['manage_visual_options'],
);

$esynAdmin->setTable('listing_visual_options');
$options = $esynAdmin->all("*");
$esynAdmin->resetTable();

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->assign("options", $options);

$esynSmarty->display('listing-visual-options.tpl');
