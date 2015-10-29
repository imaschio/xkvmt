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

define('IA_REALM', 'templates');

esynUtil::checkAccess();

$esynAdmin->factory('Template');

if ($_POST)
{
	$msg = array();
	$error = false;
	$template = $_POST['template'];

	if (empty($template))
	{
		$error = true;
		$msg[] = $esynI18N['template_empty'];
	}

	if (!$error && isset($_POST['download_template']))
	{
		$result = $esynAdmin->getDownloadFromSite($template, 'template');
		$esynAdmin->mCacher->clearAll();

		if (!$result)
		{
			$msg[] = 'Template cannot be downloaded.';
			$error = true;
		}
		else
		{
			$msg[] = 'Template has been downloaded. Now you can go on installing it.';
		}
	}

	if (!$error && isset($_POST['set_template']))
	{
		if (!is_dir(IA_TEMPLATES . $template) && !$error)
		{
			$error = true;
			$msg[] = $esynI18N['template_folder_error'];
		}

		$infoXmlFile = IA_TEMPLATES . $template . IA_DS . 'info' . IA_DS .'install.xml';
		if (!file_exists($infoXmlFile) && !$error)
		{
			$esynI18N['template_xmlfile_error'] = str_replace('{template}', $template, $esynI18N['template_xmlfile_error']);

			$error = true;
			$msg[] = $esynI18N['template_xmlfile_error'];
		}

		if (!$error)
		{
			// uninstall previous template
			$esynPreviousTemplate = new esynTemplate();
			$esynPreviousTemplate->getFromPath(IA_TEMPLATES . $esynConfig->getConfig('tmpl') . IA_DS . 'info' . IA_DS . 'install.xml');
			$esynPreviousTemplate->parse();
			$esynPreviousTemplate->checkFields();
			$esynPreviousTemplate->uninstall($esynConfig->getConfig('tmpl'));

			// install new template
			$esynTemplate = new esynTemplate();
			$esynTemplate->getFromPath($infoXmlFile);
			$esynTemplate->parse();
			$esynTemplate->checkFields();
			$esynTemplate->install();

			if ($esynTemplate->error)
			{
				$error = true;
				$msg[] = $esynTemplate->getMessage();
			}
			else
			{
				$esynI18N['template_installed'] = str_replace('{template}', $esynTemplate->title, $esynI18N['template_installed']);

				$msg[] = $esynI18N['template_installed'];
				$msg[] = $esynTemplate->getNotes();
			}
		}
	}

	esynMessages::setMessage($msg, $error);
}

$gBc[0]['title'] = $esynI18N['manage_templates'];
$gBc[0]['url'] = 'controller.php?file=templates';

$gTitle = $esynI18N['manage_templates'];

require_once IA_ADMIN_HOME . 'view.php';

$tmpl = $esynConfig->getConfig('tmpl', true);

$templates = array();
$xml_files = array();

$directory = opendir(IA_TEMPLATES);
while (false !== ($file = readdir($directory)))
{
	if (substr($file, 0, 1) != ".")
	{
		if (is_dir(IA_TEMPLATES . $file))
		{
			$infoXmlFile = IA_TEMPLATES . $file . IA_DS . 'info' . IA_DS . 'install.xml';

			if (file_exists($infoXmlFile))
			{
				$xml_files[$file] = $infoXmlFile;
			}
		}
	}
}
closedir($directory);

// get local templates list
if (!empty($xml_files))
{
	foreach ($xml_files as $infoXmlFile)
	{
		$esynTemplate = new esynTemplate();

		$esynTemplate->getFromPath($infoXmlFile);
		$esynTemplate->parse();
		$esynTemplate->checkFields();

		if (!$esynTemplate->error)
		{
			if (!isset($esynTemplate->mobile) || empty($esynTemplate->mobile))
			{
				$templates[] = array(
					'name'			=> $esynTemplate->name,
					'title'			=> $esynTemplate->title,
					'author'		=> $esynTemplate->author,
					'contributor'	=> $esynTemplate->contributor,
					'date'			=> $esynTemplate->date,
					'description'	=> $esynTemplate->summary,
					'version'		=> $esynTemplate->version,
					'local'			=> true,
					'compatibility'	=> $esynTemplate->compatibility,
					'screenshots'	=> $esynTemplate->getScreenshots()
				);
			}
		}

		unset($esynTemplate);
	}
}

// get remote templates list
$remoteTemplates = $esynAdmin->mCacher->get('esyndicat_templates', 3600, true);
if (empty($remoteTemplates))
{
	$response = esynUtil::getPageContent('http://tools.esyndicat.com/get-templates-list.php?version=' . IA_VERSION);

	// create templates list cache
	if ($response)
	{
		$remote = esynUtil::jsonDecode($response);

		foreach ($remote['templates'] as $entry)
		{
			// exclude installed templates
			if (!array_key_exists($entry['name'], $xml_files))
			{
				$remoteTemplates[] = (array)$entry;
			}
		}

		if ($remoteTemplates)
		{
			// cache well-formed results
			$esynAdmin->mCacher->write('esyndicat_templates', $remoteTemplates);
		}
	}
}
if ($remoteTemplates)
{
	$templates = array_merge($templates, $remoteTemplates);
}

$esynSmarty->assign('templates', $templates);
$esynSmarty->assign('tmpl', $tmpl);

$esynSmarty->display('templates.tpl');
