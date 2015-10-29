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

define('IA_REALM', "plugins");

esynUtil::checkAccess();

define('IA_VALIDATE_URL', 'http://tools.esyndicat.com/get-plugins-list.php');
define('IA_PLUGIN_INFO', 'http://tools.esyndicat.com/get-plugin-info.php');

$esynAdmin->factory("Plugin");

if (isset($_GET['action']))
{
	if ('available' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
		$dir = isset($_GET['dir']) && in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';
		$type = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : '';

		$out = array('data' => '', 'total' => 0);
		$key = 0;

		// get array of installed plugins
		$esynAdmin->setTable('plugins');
		$installedPlugins = $esynAdmin->keyvalue("`name`, `version`");
		$esynAdmin->resetTable();

		if ('remote' == $type)
		{
			$remotePlugins = $esynAdmin->mCacher->get('esyndicat_plugins', 3600, true);
			if (empty($remotePlugins))
			{
				$response = esynUtil::getPageContent(IA_VALIDATE_URL . '?version=' . IA_VERSION);

				// create plugins list cache
				if ($response)
				{
					$plugins = esynUtil::jsonDecode($response);

					if (!empty($plugins['error']))
					{
						$out['msg'][] = $plugins['error'];
						$out['error'] = true;
					}
					elseif ($plugins['total'] > 0)
					{
						$remotePlugins = $plugins['plugins'];
						if (is_array($remotePlugins))
						{
							foreach ($remotePlugins as $entry)
							{
								++$out['total'];
								$plugin_info = (array)$entry;

								$plugin_info['type'] = 'remote';
								$plugin_info['install'] = 0;

								// exclude installed plugins
								if (!array_key_exists($plugin_info['name'], $installedPlugins))
								{
									if (isset($plugin_info['compatibility']) && version_compare($plugin_info['compatibility'], IA_VERSION, '<='))
									{
										$plugin_info['install'] = 1;
									}
									$plugin_info['file'] = $plugin_info['name'];
									$plugin_info['readme'] = false;
									$plugin_info['reinstall'] = false;
									$plugin_info['uninstall'] = false;
									$plugin_info['remove'] = false;
									$plugin_info['removable'] = false;

									$out['data'][] = $plugin_info;
								}
							}

							// cache well-formed results
							$esynAdmin->mCacher->write('esyndicat_plugins', $out['data']);
						}
						else
						{
							$out['msg'][] = $esynI18N['Incorrect format returned.'];
							$out['error'] = true;
						}
					}
				}
				else
				{
					$out['msg'][] = $esynI18N['Incorrect response returned.'];
					$out['error'] = true;
				}
			}
			else
			{
				$out['data'] = $remotePlugins;
			}
		}
		elseif ('local' == $type)
		{
			$dirname = IA_HOME . 'plugins' . IA_DS;
			$directory = opendir($dirname);

			if (!$installedPlugins)
			{
				$installedPlugins = array();
			}

			while (false !== ($file = readdir($directory)))
			{
				if (substr($file, 0, 1) != '.')
				{
					if (is_dir($dirname . $file))
					{
						$installationFile = $dirname . $file . IA_DS . 'install.xml';

						if (is_file($installationFile))
						{
							$filecontent = file_get_contents($installationFile);

							if (!empty($filecontent))
							{
								$esynPlugin = new esynPlugin();

								$esynPlugin->setXml($filecontent);
								$esynPlugin->parse();

								if (!array_key_exists($esynPlugin->name, $installedPlugins)
									&& ($esynPlugin->compatibility && version_compare(IA_VERSION, $esynPlugin->compatibility, ">=")))
								{
									$out['data'][$key]['title']			= $esynPlugin->title;
									$out['data'][$key]['version']		= $esynPlugin->version;
									$out['data'][$key]['description']	= $esynPlugin->summary;
									$out['data'][$key]['author']		= $esynPlugin->author;
									$out['data'][$key]['date']			= $esynPlugin->date;
									$out['data'][$key]['file']			= $file;
									$out['data'][$key]['install']		= 1;

									$key++;
								}

								unset($esynPlugin);
							}
						}
					}
				}
			}
			closedir($directory);

			if (!empty($sort) && !empty($out['data']))
			{
				$tmp = array();

				foreach($out['data'] as $key => $plugin)
				{
					$tmp[$key] = $plugin['title'];
				}

				natcasesort($tmp);
				reset($tmp);

				if ('DESC' == $dir)
				{
					$tmp = array_reverse($tmp, true);
				}

				$plugin_data = $out['data'];

				unset($out['data']);

				$i = 0;

				foreach($tmp as $key => $plugin)
				{
					$out['data'][$i] = $plugin_data[$key];

					$i++;
				}
			}
		}
	}

	if ('installed' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$sort = isset($_GET['sort']) && !empty($_GET['sort']) ? $_GET['sort'] : '';
		$dir = isset($_GET['dir']) && in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		$where = '';

		$out = array('data' => '', 'total' => 0);

		$dirname = IA_HOME.'plugins'.IA_DS;

		$where = '1=1 ';

		if (!empty($sort))
		{
			$where .= "ORDER BY `{$sort}` {$dir} ";
		}

		$where .= "LIMIT {$start}, {$limit}";

		$out['data'] = $esynPlugin->all('*, `summary` `description`', $where);
		$out['total'] = $esynPlugin->one("COUNT(*)");

		if (!empty($out['data']))
		{
			foreach($out['data'] as $key => $plugin)
			{
				$esynAdmin->setTable("config");
				$plugin_config = $esynAdmin->row("*", "`plugin` = '{$plugin['name']}' ORDER BY `order` ASC");
				$esynAdmin->resetTable();

				$esynAdmin->setTable("admin_pages");
				$plugin_admin_page = $esynAdmin->exists("`plugin` = '{$plugin['name']}' AND `block_name` != 'divider'");
				$esynAdmin->resetTable();

				if ($plugin_config)
				{
					$out['data'][$key]['config'] = $plugin_config['group_name'] . '|' . $plugin_config['name'];
				}

				if ($plugin_admin_page)
				{
					$out['data'][$key]['manage'] = $plugin['name'];
				}

				if (is_dir($dirname . $plugin['name']))
				{
					$installationFile = $dirname . $plugin['name'] . IA_DS . 'install.xml';

					if (file_exists($installationFile))
					{
						$filecontent = file_get_contents($installationFile);

						$esynPlugin = new esynPlugin();

						$esynPlugin->setXml($filecontent);
						$esynPlugin->parse();

						if (($esynPlugin->compatibility && version_compare(IA_VERSION, $esynPlugin->compatibility, ">=")) && version_compare($esynPlugin->version, $plugin['version'], ">"))
						{
							$out['data'][$key]['upgrade'] = $plugin['name'];
						}

						unset($esynPlugin);
					}
				}
			}
		}
		else
		{
			$out['data'] = "";
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{
	if ('install' == $_POST['action'])
	{
		$out = array('msg' => '', 'error' => true, 'notes' => '');

		$pluginFolder = $_POST['plugin'];

		// process remote plugins installation
		if (isset($_POST['type']) && 'remote' == $_POST['type'] && $pluginFolder)
		{
			$result = $esynAdmin->getDownloadFromSite($pluginFolder);

			if (!$result)
			{
				$out['msg'][] = $esynI18N['plugin_cannot_be_downloaded'];
			}
		}

		$pluginInstallFile = IA_HOME . 'plugins' . IA_DS . $pluginFolder . IA_DS . 'install.xml';
		if (file_exists($pluginInstallFile))
		{
			$esynPlugin->setXml(file_get_contents($pluginInstallFile));

			$esynPlugin->doAction('install');

			if ($esynPlugin->error)
			{
				$out['msg'][] = $esynPlugin->getMessage();
			}
			else
			{
				if ($esynPlugin->upgrade)
				{
					$out['msg'][] = $esynI18N['plugin_successfully_updated'];

					if ($esynPlugin->getNotes())
					{
						$out['msg'][] = $esynPlugin->getNotes();
					}
				}
				else
				{
					$out['msg'][] = $esynI18N['plugin_installed'];

					if ($esynPlugin->getNotes())
					{
						$out['msg'][] = $esynPlugin->getNotes();
					}

					$out['error'] = false;
				}
			}
		}
		else
		{
			$out['msg'][] = $esynI18N['file_doesnt_exist'];
		}
	}

	if ('uninstall' == $_POST['action'])
	{
		$out = array('error' => true, 'notes' => '');

		$plugins = $_POST['plugins'];

		if (!is_array($plugins) || empty($plugins))
		{
			$out['msg'] = 'Unknown plugin name.';
			$out['error'] = true;
		}
		else
		{
			$plugins = array_map(array('esynSanitize', 'sql'), $plugins);
		}

		if ($plugins)
		{
			foreach($plugins as $plugin)
			{
				if ($esynPlugin->exists("`name` = '{$plugin}'"))
				{
					$pluginInstallFile = IA_HOME.'plugins'.IA_DS.$plugin.IA_DS.'install.xml';

					if (!file_exists($pluginInstallFile))
					{
						$out['msg'] = $esynI18N['file_doesnt_exist'];
						$out['error'] = true;
					}
					else
					{
						$esynPlugin->setXml(file_get_contents($pluginInstallFile));
						$out['error'] = false;
					}

					if (!$out['error'])
					{
						$esynPlugin->uninstall($plugin);

						$out['msg'][] = $esynI18N['plugin_uninstalled'];

						if ($esynPlugin->getNotes())
						{
							$out['msg'][] = $esynPlugin->getNotes();
						}

						$out['error'] = false;
					}
				}
			}
		}
		else
		{
			$out['msg'] = 'Unknown plugin name';
			$out['error'] = true;
		}
	}

	if ('update' == $_POST['action'])
	{
		$out = array('msg' => '', 'error' => true);

		$field = esynSanitize::sql($_POST['field']);
		$value = esynSanitize::sql($_POST['value']);

		if (!empty($field) && !empty($value))
		{
			if (is_array($_POST['plugin']))
			{
				$plugin = array_map(array("esynSanitize", "sql"), $_POST['plugin']);

				$where = "`name` IN ('".implode("','", $plugin)."')";
			}
			else
			{
				$plugin = esynSanitize::sql($_POST['plugin']);

				$where = "`name` = '{$plugin}'";
			}

			$esynPlugin->update(array($field => $value), $where);

			$out['msg'] = 'Changes saved';
			$out['error'] = false;
		}
		else
		{
			$out['msg'] = 'Wrong parametes';
			$out['error'] = true;
		}
	}

	if ('getdoctabs' == $_POST['action'])
	{
		$out = array();

		$plugin = $_POST['plugin'];
		$fields_to_replace = array('icon', 'name', 'author', 'contributor', 'version', 'date', 'compatibility');

		if ('remote' == $_POST['type'])
		{
			$pluginInfo = $esynAdmin->mCacher->get($plugin . '_plugin_info', 3600, true);
			if (empty($pluginInfo))
			{
				$response = esynUtil::getPageContent(IA_PLUGIN_INFO . '?version=' . IA_VERSION . '&plugin=' . $plugin);
				if ($response)
				{
					$response_info = esynUtil::jsonDecode($response);

					if (!empty($response_info['error']))
					{
						$out['msg'][] = $response_info['error'];
						$out['error'] = true;
					}
					else
					{
						foreach($fields_to_replace as $one_field)
						{
							$plugin_info[$one_field] = $response_info['plugin_info'][$one_field];
						}

						$plugin_info['name'] = $response_info['plugin_info']['title'];
						$plugin_info['contributor'] = $response_info['plugin_info']['author'];
						$plugin_info['version'] = $response_info['plugin_info']['plugin_version'];
						$plugin_info['compatibility'] = $response_info['plugin_info']['core_version'];
						$plugin_info['doc_tabs'] = $response_info['plugin_info']['doc_tabs'];

						// cache well-formed results
						$esynAdmin->mCacher->write($plugin . '_plugin_info', $plugin_info);
					}
				}
				else
				{
					$out['msg'] = 'Plugin documentation cannot be accessed.';
					$out['error'] = true;
				}
			}
			else
			{
				$plugin_info = $pluginInfo;
			}
		}
		else
		{
			$plugin_doc_path = IA_PLUGINS . $plugin . IA_DS . 'docs' . IA_DS;

			if (file_exists($plugin_doc_path))
			{
				$plugin_info['doc_tabs'] = array();
				$docs = scandir($plugin_doc_path);
				foreach($docs as $doc)
				{
					if (substr($doc, 0, 1) != '.')
					{
						if (is_file($plugin_doc_path . $doc))
						{
							$name = substr($doc, 0, count($doc) - 6);

							$plugin_info['doc_tabs'][] = array(
								'title' => $esynI18N['plugin_' . $name],
								'html' => file_get_contents($plugin_doc_path.$doc)
							);
						}
					}
				}

				$esynPlugin->setXml(file_get_contents(IA_PLUGINS . $plugin . IA_DS . 'install.xml'));
				$esynPlugin->parse();

				foreach($fields_to_replace as $one_field)
				{
					$plugin_info[$one_field] = $esynPlugin->$one_field;
				}

				if (file_exists(IA_PLUGINS.$plugin.IA_DS.'docs'.IA_DS.'img'.IA_DS.'icon.png'))
				{
					$plugin_info['icon'] = '<tr><td class="plugin-icon"><img src="' . IA_URL . 'plugins/' . $plugin . '/docs/img/icon.png" alt="" /></td></tr>';
				}
			}
			else
			{
				$out['doc_tabs'] = null;
			}
		}

		// use plugin information template
		$plugin_info_template = file_get_contents(IA_INCLUDES . 'common' . IA_DS . 'plugin_information.html');

		foreach($fields_to_replace as $one_field)
		{
			$plugin_info_template = str_replace('{' . $one_field . '}', $plugin_info[$one_field], $plugin_info_template);
		}

		$out['plugin_info'] = $plugin_info_template;
		$out['doc_tabs'] = $plugin_info['doc_tabs'];
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_plugins'];

$gBc[0]['title'] = $esynI18N['manage_plugins'];
$gBc[0]['url'] = 'controller.php?file=plugins';

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->display('plugins.tpl');
