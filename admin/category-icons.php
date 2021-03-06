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

define('IA_REALM', "category_icons");

esynUtil::checkAccess();

define("IA_CATEGORY_ICONS_DIR", IA_UPLOADS . 'category-icons' . IA_DS);

if (!file_exists(IA_CATEGORY_ICONS_DIR))
{
	if (!is_writeable(IA_HOME.'uploads'.IA_DS))
	{
		trigger_error('Icons Category Directory Permissions | dir_permissions_error | The uploads directory is not writeable. Please set writeable permissions.', E_USER_ERROR);
	}

	mkdir(IA_CATEGORY_ICONS_DIR);
	chmod(IA_CATEGORY_ICONS_DIR, 0777);
}

if (isset($_GET['action']))
{

	$out = array('data' => '', 'total' => 0, 'error' => false);

	if ('getimages' == $_GET['action'])
	{
		$directories = array(
			IA_TEMPLATES . 'common' . IA_DS . 'img' . IA_DS . 'category_icons' . IA_DS,
			IA_TEMPLATES . $esynAdmin->mConfig['tmpl'] . IA_DS . 'img'. IA_DS . 'category_icons' . IA_DS,
			IA_CATEGORY_ICONS_DIR
		);

		foreach ($directories as $d)
		{
			if (file_exists($d))
			{
				$directory = opendir($d);

				while (false !== ($file = readdir($directory)))
				{
					if (substr($file, 0, 1) != ".")
					{
						if (preg_match('/\.(jpg|gif|png)$/', strtolower($file)))
						{
							$size = filesize($d . $file);
							$lastmod = filemtime($d . $file) * 1000;

							$url = str_replace(IA_HOME, '', $d);
							$url = str_replace(IA_DS, '/', $url);

							$out['data'][] = array(
								'name'			=> $file,
								'size'			=> $size,
								'lastmod'		=> $lastmod,
								'url'			=> $url . $file,
								'removeable'	=> $d == IA_CATEGORY_ICONS_DIR ? true : false,
								'default'		=> $url . $file == $esynAdmin->mConfig['default_categories_icon'] ? true : false
							);
						}
					}
				}
				closedir($directory);
			}
		}
	}

	if ('upload' == $_GET['action'])
	{
		$imgtypes = array(
			"image/gif"		=> "gif",
			"image/jpeg"	=> "jpg",
			"image/pjpeg"	=> "jpg",
			"image/png"		=> "png"
		);

		if ((bool)$_FILES['icon']['error'])
		{
			$out['error'] = true;
			$out['msg'] = 'Error occurs while icon is uploading.';
		}
		else
		{
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

					if (move_uploaded_file($_FILES['icon']['tmp_name'], IA_CATEGORY_ICONS_DIR.$_FILES['icon']['name']))
					{
						$out['error'] = false;
						$out['msg'] = "Icon {$_FILES['icon']['name']} is successfully uploaded.";
					}
				}
			}
			else
			{
				$out['error'] = true;
				$out['msg'] = 'Template can not be uploaded.';
			}
		}

		$out['success'] = true;
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{

	$out = array('msg' => array(), 'error' => false);

	if ('unset_icon' == $_POST['action'])
	{
		$out['error'] = false;
		$out['msg'] = 'Category icon configuration has been unset.';

		$esynAdmin->setConfig('default_categories_icon', '', true);
	}

	if ('remove' == $_POST['action'])
	{
		if (empty($_POST['icons']))
		{
			$out['error'] = true;
			$out['msg'][] = 'Param is wrong';
		}

		if (!$out['error'])
		{
			foreach($_POST['icons'] as $icon)
			{
				if (file_exists(IA_CATEGORY_ICONS_DIR.$icon))
				{
					if ($esynConfig->getConfig('default_categories_icon') == 'uploads/category-icons/' . $icon)
					{
						$esynConfig->setConfig('default_categories_icon', '', true);
					}

					$esynAdmin->setTable('categories');
					$categories = $esynAdmin->all('id, icon', "`icon` = 'uploads/category-icons/{$icon}'");
					if ($categories)
					{
						foreach ($categories as $category)
						{
							$esynAdmin->update(array('icon' => ''), '`id` = ' . $category['id']);
						}

						//clear cache
						$esynAdmin->mCacher->clearAll('categories');
					}
					$esynAdmin->resetTable();

					unlink(IA_CATEGORY_ICONS_DIR . $icon);
				}
			}

			$out['msg'] = 'Icons have been removed.';
		}
	}

	if ('default' == $_POST['action'])
	{
		if (empty($_POST['url']))
		{
			$out['error'] = true;
			$out['msg'][] = 'Param is wrong';
		}

		if (!$out['error'])
		{
			$icon = str_replace('\\', IA_URL_DELIMITER, $_POST['url']);

			if (file_exists(IA_HOME . $icon))
			{
				$esynConfig->setConfig('default_categories_icon', $icon, true);
			}

			$out['msg'] = '<b><i>' . IA_URL . $icon . '</i></b> ' . $esynI18N['image_set_as_default_icon_categories'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gBc[0]['title'] = $esynI18N['category_icons'];
$gBc[0]['url'] = 'controller.php?file=category_icons';

$gTitle = $esynI18N['category_icons'];

$actions = array(
	array("url" => "#", "icon" => "add.png", "label" => 'Upload new category icon', "attributes" => 'id="upload_icon"'),
);

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->display('category-icons.tpl');
