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

define('IA_REALM', "banner_plans");

esynUtil::checkAccess();

$esynAdmin->loadPluginClass("BannerPlans", "banners", 'esyn', true);
$esynBannerPlan = new esynBannerPlans();

$esynAdmin->setTable('blocks');
$blocks = $esynAdmin->keyvalue("`position`, `title`", "`plugin` = 'banners'");
$esynAdmin->resetTable();
/*
 * ACTIONS
 */
$upl_writable = is_writable(IA_HOME."uploads") ? true : false;
$disable_bp = (array_key_exists("paypal",$esynAdmin->mPlugins) || array_key_exists("2checkout",$esynAdmin->mPlugins) || array_key_exists("moneybookers",$esynAdmin->mPlugins) || array_key_exists("authorize",$esynAdmin->mPlugins)) ? false : true;

if (isset($_POST['save']))
{
	$esynAdmin->startHook('adminAddPlanValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once(IA_CLASSES.'esynUtf8.php');

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error = false;

	$banner_plan = array();
	
	$banner_plan['recurring'] 		= isset($_POST['recurring']) 		? $_POST['recurring'] 		: 0;
	$banner_plan['units_duration'] 	= isset($_POST['units_duration']) 	? $_POST['units_duration'] 	: '';
	$banner_plan['duration'] 		= isset($_POST['duration']) 		? $_POST['duration'] 		: '';

	$banner_plan['lang'] = IA_LANGUAGE;
	if (!empty($_POST['lang']) && array_key_exists($_POST['lang'], $esynAdmin->mLanguages))
	{

		$banner_plan['lang'] = $_POST['lang'];
	}

	$banner_plan['title'] = $_POST['title'];
	if (!utf8_is_valid($banner_plan['title']))
	{
		$banner_plan['title'] = utf8_bad_replace($banner_plan['title']);
	}

	$banner_plan['description'] = $_POST['description'];
	if (!utf8_is_valid($banner_plan['description']))
	{
		$banner_plan['description'] = utf8_bad_replace($banner_plan['description']);
	}

	if (!$banner_plan['title'])
	{
		$error = true;
		$msg[] = $esynI18N['error_title'];
	}

	if (!$banner_plan['description'])
	{
		$error = true;
		$msg[] = $esynI18N['error_description'];
	}

	$banner_plan['period'] = $_POST['period'];
	if (!ctype_digit($banner_plan['period']))
	{
		$error = true;
		$msg[] = $esynI18N['error_plan_number_days'];
	}

	$banner_plan['cost'] = $_POST['cost'];
	if (!preg_match('/^[0-9\.]+$/', $banner_plan['cost']))
	{
		$error = true;
		$msg[] = $esynI18N['error_plan_cost'];
	}

	$banner_plan['blocks']       = isset($_POST['blocks'])       ? $_POST['blocks']       : array();
	$banner_plan['email_expire'] = isset($_POST['email_expire']) ? $_POST['email_expire'] : '';
	$banner_plan['mark_as']      = (isset($_POST['markas']) && in_array($_POST['markas'], array('active', 'inactive'))) ? $_POST['markas'] : '';

	if (isset($_POST['action_expire']) && !empty($_POST['action_expire']))
	{
		$actions = array('remove', 'active', 'inactive');

		$banner_plan['action_expire'] = in_array($_POST['action_expire'], $actions) ? $_POST['action_expire'] : '';
	}
	else
	{
		$banner_plan['action_expire'] = '';
	}

	if (!$error)
	{
		if ('add' == $_POST['do'])
		{
			$result = $esynBannerPlan->insert($banner_plan);

			if ($result)
			{
				$msg[] = $esynI18N['plan_added'];
			}
			else
			{
				$error = true;
				$msg[] = $esynBannerPlan->getMessage();
			}
		}
		elseif ('edit' == $_POST['do'])
		{
			$result = $esynBannerPlan->update($banner_plan, (int)$_POST['id']);

			if ($result)
			{
				$msg[] = $esynI18N['changes_saved'];
			}
			else
			{
				$error = true;
				$msg[] = $esynBannerPlan->getMessage();
			}
		}

		$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

		esynMessages::setMessage($msg, $error);

		esynUtil::reload(array("do" => $do));
	}

	esynMessages::setMessage($msg, $error);
}


if (isset($_GET['action']))
{


	$start = (int)$_GET['start'];
	$limit = (int)$_GET['limit'];

	$out = array('data' => '', 'total' => 0);

	if ('get' == $_GET['action'])
	{
		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		if (!empty($sort) && !empty($dir))
		{
			$order = " ORDER BY `{$sort}` {$dir}";
		}

		$out['total'] = $esynBannerPlan->one("COUNT(*)");
		$out['data'] = $esynBannerPlan->all("*, `id` `edit`, '1' `remove`", "1 {$order}", array(), $start, $limit);
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


	$out = array('msg' => 'Unknown error', 'error' => true);

	if ('remove' == $_POST['action'])
	{
		if (empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}
		else
		{
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($_POST['ids']))
			{
				foreach($_POST['ids'] as $id)
				{
					$ids[] = (int)$id;
				}
			}
			else
			{
				$ids = (int)$_POST['ids'];
			}

			$esynBannerPlan->delete($ids);

			$out['msg'] = (count($ids) > 1) ? $esynI18N['plans_deleted'] : $esynI18N['plan_deleted'];
		}
	}

	/*
	 * Update grid field
	 */
	if ('update' == $_POST['action'])
	{
		$field = $_POST['field'];
		$value = $_POST['value'];

		if (empty($field) || empty($value) || empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}
		else
		{
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($_POST['ids']))
			{
				foreach($_POST['ids'] as $id)
				{
					$ids[] = (int)$id;
				}

				$where = "`id` IN ('".join("','", $ids)."')";
			}
			else
			{
				$id = (int)$_POST['ids'];

				$where = "`id` = '{$id}'";
			}

			$esynAdmin->setTable("banner_plans");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

/*
 * ACTIONS
 */

$gNoBc = false;

$gTitle = $esynI18N['manage_banner_plans'];

$gBc[1]['title'] = $esynI18N['manage_banners'];
$gBc[1]['url'] = 'controller.php?plugin=banners';
$gBc[2]['title'] = $esynI18N['manage_banner_plans'];
$gBc[2]['url'] = 'controller.php?plugin=banners&file=banner_plans';

if (!$upl_writable)
{
	esynMessages::setMessage($esynI18N['uploads_not_writable'], 'alert');
}

if ($disable_bp)
{
	esynMessages::setMessage($esynI18N['paypal_not_installed'], 'alert');
}
else
{
	$actions[] = array("url" => "controller.php?plugin=banners&amp;file=banner_plans&amp;do=add", "icon" => "add.png", "label" => $esynI18N['add_banner']);
}

$actions[] = array("url" => "controller.php?plugin=banners&amp;file=banner_plans", "icon" => "view.png", "label" => $esynI18N['view']);
$actions[] = array("url" => "controller.php?plugin=banners", "icon" => "back.png", "label" => $esynI18N['manage_banner_plans']);


require_once(IA_ADMIN_HOME.'view.php');

$esynAdmin->startHook('plansAfterViewInclude');

$esynSmarty->assign('blocks', $blocks);

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$banner_plan = $esynBannerPlan->row("*", "`id` = :id", array('id' => (int)$_GET['id']));
	$esynBannerPlan->setTable('banner_plan_blocks');
	$banner_plan['blocks'] = $esynBannerPlan->keyvalue("`id`, `block_pos`", "`plan_id` = '{$banner_plan['id']}'");
	$esynBannerPlan->resetTable();

	$esynSmarty->assign('banner_plan', $banner_plan);
}

$esynAdmin->startHook('plansBeforeDisplay');

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'banner_plans.tpl');
