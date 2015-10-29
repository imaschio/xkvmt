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

define('IA_REALM', "configuration");

esynUtil::checkAccess();

$error = false;

$columns_num_array = array('1' => '12', '2' => '6', '3' => '4', '4' => '3');

if (isset($_GET['download_htaccess']))
{
	header("Content-Type: text/plain");
	header("Content-Disposition: attachment;filename=\"htaccess.file\"");

	//built htaccess file
	$esynAdmin->setTable("config");
	$all_htacces = $esynAdmin->keyvalue("REPLACE(`name`, 'htaccessfile_', ''),`value`", "`name` LIKE 'htaccessfile_%'");
	$esynAdmin->resetTable();

	if ($all_htacces)
	{
		ksort($all_htacces);

		foreach($all_htacces as $kline => $vline)
		{
			echo str_replace(array("&lt;","&gt;","\r\n"),array("<",">","\n"),$vline)."\n";
		}
	}
	exit;
}

if (isset($_POST['do']) && 'save_htaccess' == $_POST['do'])
{
	$sections = $_POST['sections'];

	$htaccess_code = '';

	foreach ($sections as $key => $section)
	{
		$keyConfig = 'htaccessfile_' . $key;

		// append 2 new lines
		$section .= str_repeat(PHP_EOL, 2);

		$esynConfig->setConfig($keyConfig, $section, true);
		$esynAdmin->mConfig[$keyConfig] = $section;
	}

	$esynAdmin->mCacher->clearAll('', true);

	if (is_writeable(IA_HOME . '.htaccess'))
	{
		$esynAdmin->factory("Plugin");

		$result = $esynPlugin->updateHtaccess();

		if ($result)
		{
			$msg[] = _t('htaccess_saved');
		}
		else
		{
			$error = true;
			$msg[] = _t('error_while_rebuild_htaccess');
		}
	}
	else
	{
		$msg[] = _t('you_should_reupload_htaccess');
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_POST['save']))
{
	$esynAdmin->startHook('adminConfigurationChange');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	/** strip new values **/
	if (is_array($_POST['param']) && !empty($_POST['param']))
	{
		$error = false;

		foreach($_POST['param'] as $key => $value)
		{
			if (is_array($value))
			{
				$value = implode(",", $value);
			}

			if (!utf8_is_valid($value))
			{
				$value = utf8_bad_replace($value);
				trigger_error("Bad UTF-8 detected (replacing with '?') in configuration", E_USER_NOTICE);
			}

			if (!empty($_FILES[$key]['name']))
			{
				$imgtypes = array(
					"image/gif"		=> "gif",
					"image/jpeg"	=> "jpg",
					"image/pjpeg"	=> "jpg",
					"image/png"		=> "png",
					"image/x-png"	=> "png"
				);

				if ((bool)$_FILES[$key]['error'])
				{
					$error = true;
					$msg = $esynI18N['site_logo_image_error'];
				}
				else
				{
					if (@is_uploaded_file($_FILES[$key]['tmp_name']))
					{
						$ext = strtolower(utf8_substr($_FILES[$key]['name'], -3));

						// if jpeg
						if ($ext == 'peg')
						{
							$ext = 'jpg';
						}

						if (!array_key_exists(strtolower($_FILES[$key]['type']), $imgtypes) || !in_array($ext, $imgtypes, true) || !getimagesize($_FILES[$key]['tmp_name']))
						{
							$error = true;

							$a = implode(", ", array_unique($imgtypes));

							$err_msg = str_replace("{types}", $a, $esynI18N['wrong_site_logo_image_type']);

							$msg[] = $err_msg;
						}
						else
						{
							if ('' != $esynConfig->getConfig('site_logo'))
							{
								$f_path = IA_UPLOADS . $esynConfig->getConfig($key);

								if (file_exists($f_path) && is_file($f_path))
								{
									unlink($f_path);
								}
							}

							$token = esynUtil::getNewToken();
							$file_name = 'img_'.$token.'.'.$ext;
							$fname = IA_HOME . 'uploads' . IA_DS . $file_name;

							$value = $file_name;

							// set logo_height config
							list($width, $height) = getimagesize($_FILES[$key]['tmp_name']);
							$height = $height / 2;
							$esynConfig->setConfig('logo_height', $height, true);
							$esynAdmin->mConfig['logo_height'] = $height;

							@move_uploaded_file($_FILES[$key]['tmp_name'], $fname);
							@chmod($fname, 0777);
						}
					}
				}
			}

			if (isset($_FILES[$key]['name']) && empty($_FILES[$key]['name']))
			{
				$value = $esynConfig->getConfig($key);
			}

			/** writes to database **/
			$esynConfig->setConfig($key, $value, true);
			$esynAdmin->mConfig[$key] = $value;
		}

		$esynAdmin->mCacher->clearAll('', true);
	}

	$msg = $error ? $msg : $esynI18N['changes_saved'];

	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']))
{
	$out = array();

	if ('htaccess' == $_GET['action'])
	{
		$out = '';

		$esynAdmin->setTable("config");
		$all_htacces = $esynAdmin->keyvalue("REPLACE(`name`, 'htaccessfile_', ''),`value`", "`name` LIKE 'htaccessfile_%'");
		$esynAdmin->resetTable();

		if (!empty($all_htacces))
		{
			$out = '';
			ksort($all_htacces);

			echo '<div id="htaccess_code">';

			foreach($all_htacces as $kline => $vline)
			{
				$vline = htmlspecialchars($vline);
				$vline = nl2br($vline);

				$out .= $vline;
			}

			echo $out;

			echo '</div>';
		}
	}

	if ('permission' == $_GET['action'])
	{
		$permission = is_writeable(IA_HOME . '.htaccess') ? true : false;

		echo esynUtil::jsonEncode($permission);
	}

	if ('rebuild' == $_GET['action'])
	{
		$esynAdmin->factory("Plugin");

		$result = $esynPlugin->updateHtaccess();

		echo esynUtil::jsonEncode($result);
	}

	if ('remove_image' == $_GET['action'])
	{
		if (file_exists(IA_UPLOADS . $esynConfig->getConfig($_GET['conf'])))
		{
			unlink(IA_UPLOADS . $esynConfig->getConfig($_GET['conf']));
		}

		// delete logo_height
		if ('site_logo' == $_GET['conf'])
		{
			$esynConfig->setConfig('logo_height', '0', true);
		}

		$esynConfig->setConfig($_GET['conf'], '', true);
	}

	if ('get_image' == $_GET['action'])
	{
		if (!file_exists(IA_UPLOADS . $esynConfig->getConfig($_GET['conf'])))
		{
			echo '<div style="padding: 15px; margin: 0; background: #FFE269 none repeat scroll 0 0;">' . $esynI18N['image_not_found'] . '</div>';
		}
		else
		{
			echo '<img src="'. IA_URL .'uploads/'. $esynConfig->getConfig($_GET['conf']) .'" alt="" />';
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

		if ((bool)$_FILES[$_GET['conf']]['error'])
		{
			$out['error'] = true;
			$out['msg'] = $esynI18N['upload_image_error'];
		}
		else
		{
			if (is_uploaded_file($_FILES[$_GET['conf']]['tmp_name']))
			{
				$ext = substr($_FILES[$_GET['conf']]['name'], -3);

				// if 'jpeg'
				if ($ext == 'peg')
				{
					$ext = 'jpg';
				}

				if (!array_key_exists($_FILES[$_GET['conf']]['type'], $imgtypes) || !in_array($ext, $imgtypes))
				{
					$out['error'] = true;

					$a = implode(", ", array_unique($imgtypes));

					$out['msg'] = str_replace("{types}", $a, $esynI18N['wrong_image_type']);
				}
				else
				{
					if ('' != $esynConfig->getConfig($_GET['conf']))
					{
						if (file_exists(IA_UPLOADS . $esynConfig->getConfig($_GET['conf'])))
						{
							unlink(IA_UPLOADS . $esynConfig->getConfig($_GET['conf']));
						}
					}

					$token = esynUtil::getNewToken();
					$file_name = $_GET['conf'].'_'.$token.'.'.$ext;
					$fname = IA_UPLOADS . $file_name;

					if (@move_uploaded_file($_FILES[$_GET['conf']]['tmp_name'], $fname))
					{
						$out['error'] = false;
						$out['msg'] = str_replace('{name}', $_FILES[$_GET['conf']]['name'], $esynI18N['image_uploaded']);
						$out['file_name'] = $file_name;

						$esynConfig->setConfig($_GET['conf'], $file_name, true);

						@chmod($fname, 0777);
					}
				}
			}
		}

		$out['success'] = !$out['error'];

		echo esynUtil::jsonEncode($out);
	}

	exit;
}

/*
 * Get only groups which have configs
 */
$sql = "SELECT `groups`.`name`, `groups`.`title` FROM `{$esynAdmin->mPrefix}config_groups` `groups` ";
$sql .= "JOIN `{$esynAdmin->mPrefix}config` `config` ";
$sql .= "ON `groups`.`name` = `config`.`group_name` ";
$sql .= "GROUP BY `groups`.`name` ";
$sql .= "ORDER BY `groups`.`order` ASC";

$groups = $esynAdmin->getKeyvalue($sql);

$group = isset($_GET['group']) ? esynSanitize::sql($_GET['group']) : 'general';

if ($group && isset($groups[$group]))
{
	$gBc[0]['title'] = $esynI18N['configuration'];
	$gBc[0]['url'] = 'controller.php?file=configuration';

	$gBc[1]['title'] = $groups[$group];
	$gTitle = $gBc[1]['title'];
}
else
{
	$gBc[0]['title'] = $esynI18N['configuration'];
	$gBc[0]['url'] = 'controller.php?file=configuration';

	$gTitle = $esynI18N['configuration'];
}

$actions = array(
	array("url" => "#", "icon" => "save.png", "label" => 'Download .htaccess', "attributes" => 'id="download"'),
	array("url" => "#", "icon" => "view.png", "label" => 'View .htaccess', "attributes" => 'id="show_htaccess"'),
	array("url" => "controller.php?file=email-templates", "icon" => "email_templates.png", "label" => _t('manage_email_templates')),
);

require_once IA_ADMIN_HOME . 'view.php';

if (false !== $group)
{
	$esynAdmin->setTable("config");

	// if user choosed to edit 'Email templates' and user choosed specific email template type
	if (!isset($_GET['show']))
	{
		$_GET['show'] = 'plaintext';
	}
	if ($group == 'email_templates' && isset($_GET['show']) && in_array($_GET['show'], array("plaintext","html"), true))
	{
		if ($_GET['show'] == 'plaintext')
		{
			$where = "`group_name` = '{$group}' AND `name` NOT LIKE '%\_body\_html' ORDER BY `order`";
		}
		else
		{
			$where = "`group_name` = '{$group}' AND `name` NOT LIKE '%\_body\_plaintext' ORDER BY `order`";
		}
	}
	else
	{
		$where = "`group_name` = '{$group}' ORDER BY `order`";
	}

	$params = $esynAdmin->all("*", $where);
	$esynAdmin->resetTable();

	if ('miscellaneous' == $group)
	{
		require_once IA_PLUGINS . $esynConfig->getConfig('captcha_name') . IA_DS . 'includes' . IA_DS . 'classes' . IA_DS . 'captcha.php';

		$captcha = new captcha();

		ob_start();
		echo $captcha->getPreview();
		$captcha_preview = ob_get_contents();
		ob_end_clean();

		$esynSmarty->assign('captcha_preview', $captcha_preview);
	}

	$esynSmarty->assign('params', $params);
}

$directory = opendir(IA_TEMPLATES);

while (false !== ($file=readdir($directory)))
{
	if (substr($file,0,1) != ".")
	{
		if (is_dir(IA_TEMPLATES.$file))
		{
			$templates[] = $file;
		}
	}
}
closedir($directory);

define('IA_ADMIN_TEMPLATES', IA_ADMIN_HOME . 'templates' . IA_DS);
$directory = opendir(IA_ADMIN_TEMPLATES);
while (false !== ($file=readdir($directory)))
{
	if (substr($file,0,1) != ".")
	{
		if (is_dir(IA_ADMIN_TEMPLATES . $file))
		{
			$admin_templates[] = $file;
		}
	}
}
closedir($directory);

$esynAdmin->setTable("config");
$htaccess_code = $esynAdmin->keyvalue("REPLACE(`name`, 'htaccessfile_', ''),`value`", "`name` LIKE 'htaccessfile_%'");
$esynAdmin->resetTable();

if (!empty($htaccess_code))
{
	ksort($htaccess_code);

	foreach ($htaccess_code as $key => $code)
	{
		$htaccess_code[$key] = trim($code);
	}
}

/*
 * Get tips
 */
$esynAdmin->setTable("language");

$esyn_tips = $esynAdmin->all("`key`, `value`", "`category` = 'tooltip' AND `code` = '". IA_LANGUAGE ."'");

$esynAdmin->resetTable();

if (!empty($esyn_tips))
{
	$esynSmarty->assign('esyn_tips', $esyn_tips);
}

$esynSmarty->assign('templates', $templates);
$esynSmarty->assign('admin_templates', $admin_templates);
$esynSmarty->assign('group', $group);
$esynSmarty->assign('groups', $groups);
$esynSmarty->assign('htaccess_code', $htaccess_code);

$esynSmarty->display('configuration.tpl');
