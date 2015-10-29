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

define('IA_REALM', "email_templates");

esynUtil::checkAccess();

if (!defined('IA_NOUTF'))
{
	require_once IA_CLASSES . 'esynUtf8.php';

	esynUtf8::loadUTF8Core();
	esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
}

if (isset($_POST['subject']))
{
	$error = false;

	if($_POST['new_tpl_name'])
	{
		// create new email template
		$new_tpl['name'] = $_POST['new_tpl_name'] = !utf8_is_ascii($_POST['new_tpl_name']) ? utf8_to_ascii($_POST['new_tpl_name']) : $_POST['new_tpl_name'];
		$new_tpl['name'] = str_replace(" ", "_", $new_tpl['name']);
		$new_tpl['subject'] = !empty($_POST['subject']) ? $_POST['subject'] : '';
		$new_tpl['body'] = !empty($_POST['body']) ? $_POST['body'] : '';
		$new_tpl['code'] = !empty($_POST['lang']) ? $_POST['lang'] : 'en';
		$new_tpl['template'] = !empty($_POST['template']) ? 1 : 0;

		$esynAdmin->setTable('language');

		$esynAdmin->resetTable();

		if($new_tpl)
		{
			$tpl = array(
				'value'			=> $new_tpl['template'],
				'type'			=> 'hidden',
				'group_name'	=> 'mail',
				'name'			=> 'custom_' . $new_tpl['name']
			);
			$esynAdmin->setTable("config");
			$esynAdmin->insert($tpl);
			$esynAdmin->resetTable();

			$esynAdmin->setTable("language");
			$sql = "SELECT  DISTINCT `lang`, `code` FROM `{$esynAdmin->mPrefix}language` WHERE `lang` != ''";
			$langs = $esynAdmin->getAll($sql);

			foreach ($langs as $lang)
			{
				$lng[] = array(
					'key'			=> 'tpl_custom_' . $new_tpl['name'] . '_subject',
					'value'			=> $new_tpl['subject'],
					'code'			=> $lang['code'],
					'lang'			=> $lang['lang'],
					'category'		=> 'email'
				);

				$lng[] = array(
					'key'			=> 'tpl_custom_' . $new_tpl['name'] . '_body',
					'value'			=> $new_tpl['body'],
					'code'			=> $lang['code'],
					'lang'			=> $lang['lang'],
					'category'		=> 'email'
				);

				$esynAdmin->insert($lng);
			}
			$esynAdmin->resetTable();
		}
	}
	else
	{
		preg_match('/tpl_(.+)_subject/', $_POST['tpl'], $key);
		if ($key)
		{

			$lang = isset($_POST['lang']) && !empty($_POST['lang']) ? esynSanitize::sql($_POST['lang']) : 'en';
			$key = $key[1];
			$esynAdmin->setTable('language');
			$data = array('value' => $_POST['subject']);
			$esynAdmin->update($data, sprintf("`key`='tpl_%s_subject' AND `code`='%s'", $key, $lang));

			$data = array('value' => $_POST['body']);
			$esynAdmin->update($data, sprintf("`key`='tpl_%s_body' AND `code`='%s'", $key, $lang));
		}
	}

	// remove cache
	foreach (glob(IA_CACHEDIR . 'language_*') as $f)
	{
		unlink($f);
	}
	print 'ok';
	exit;
}

if (isset($_GET['action']))
{
	if ('setconfig' == $_GET['action'])
	{
		if (!empty($_GET['tmpl']) && isset($esynAdmin->mConfig[$_GET['tmpl']]))
		{
			$key = $_GET['tmpl'];

			$esynConfig->setConfig($key, (int)$_GET['val'], true);
		}
		echo 'ok';
	}

	if ('get-tpl' == $_GET['action'])
	{
		$code = isset($_GET['code']) && !empty($_GET['code']) ? esynSanitize::sql($_GET['code']) : 'en';
		$out = array('subject' => '', 'body' => '');
		preg_match('/tpl_(.+)_subject/', $_GET['id'], $key);
		if ($key)
		{
			$key = $key[1];

			$esynAdmin->setTable("language");
			$template = $esynAdmin->keyvalue("`key`,`value`", "`code` = '{$code}' AND `key` LIKE 'tpl_{$key}_%' AND `category` = 'email'");
			$esynAdmin->resetTable();

			$out['subject'] = $template['tpl_' . $key . '_subject'];
			$out['body'] = $template['tpl_' . $key . '_body'];
			$out['config'] = $esynConfig->gC($key);
		}
		print esynUtil::jsonEncode($out);
	}
	if ('check-name' == $_GET['action'])
	{
		if (!empty($_GET['name']))
		{
			$name = 'custom_' . str_replace(' ', '_', trim($_GET['name']));
			if(!$esynConfig->getConfig($name))
			{
				$out['continue'] = 'ok';
				print esynUtil::jsonEncode($out);
			}
		}
	}
	exit;
}

$gBc[0]['title'] = _t('manage_email_templates');
$gBc[0]['url'] = 'controller.php?file=email_templates';

$gTitle = _t('manage_email_templates');

$actions[] = array(
	'url'	=> '#',
	'icon'	=> 'list.png',
	'label'	=> _t('email_templates_tags'),
	'attributes' => 'id="tags"'
);

require_once IA_ADMIN_HOME . 'view.php';

$email_groups = $esynConfig->getConfig('email_groups');

if (empty($email_groups))
{
	$email_groups = 'account,listing';
}

if (!empty($esynAdmin->mPlugins))
{
	$email_groups .= ',' . implode(',', array_keys($esynAdmin->mPlugins));
}

$email_groups = explode(',', $email_groups);

$esynAdmin->setTable('language');

$templates = $esynAdmin->keyvalue('`key`, `value`', "`key` LIKE 'tpl_%_subject' AND `code`='" . IA_LANGUAGE . "'");

$tmpls = array();

if (!empty($templates))
{
	foreach ($templates as $key => $tmpl)
	{
		foreach ($email_groups as $group)
		{
			if (false !== stristr($key, 'tpl_' . $group) || false !== stristr($key, 'tpl_notif_' . $group))
			{
				$tmpls[$group][$key] = $tmpl;

				unset($templates[$key]);

				break;
			}
		}
	}

	// add non-group templates
	if (!empty($templates))
	{
		$tmpls['other'] = $templates;
	}
}

// sort templates
if (!empty($tmpls))
{
	foreach ($tmpls as $key => $tmpl)
	{
		asort($tmpl);

		$tmpls[$key] = $tmpl;
	}
}

$esynSmarty->assign('tmpls', $tmpls);
$esynSmarty->assign('email_groups', $email_groups);

$esynSmarty->display('email-templates.tpl');
