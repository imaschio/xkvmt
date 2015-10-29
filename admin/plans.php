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

define('IA_REALM', "plans");

esynUtil::checkAccess();

$esynAdmin->factory("Plan");

$valid_items = array('listing', 'account');
$valid_statuses = array('active', 'inactive');

if (isset($_POST['save']))
{
	$esynAdmin->startHook('adminAddPlanValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error = false;

	$plan = array();

	$plan['lang'] = IA_LANGUAGE;

	if (!empty($_POST['lang']) && array_key_exists($_POST['lang'], $esynAdmin->mLanguages))
	{
		$plan['lang'] = $_POST['lang'];
	}

	$plan['title'] = $_POST['title'];

	if (!utf8_is_valid($plan['title']))
	{
		$plan['title'] = utf8_bad_replace($plan['title']);
	}

	$plan['description'] = $_POST['description'];

	if (!utf8_is_valid($plan['description']))
	{
		$plan['description'] = utf8_bad_replace($plan['description']);
	}

	if (!$plan['title'])
	{
		$error = true;
		$msg[] = $esynI18N['error_title'];
	}

	if (!$plan['description'])
	{
		$error = true;
		$msg[] = $esynI18N['error_description'];
	}

	$plan['period'] = (isset($_POST['period']) && !empty($_POST['period'])) ? $_POST['period'] : '0';

	if (!ctype_digit($plan['period']))
	{
		$error = true;
		$msg[] = $esynI18N['error_plan_number_days'];
	}

	$plan['cost'] = (isset($_POST['cost']) && !empty($_POST['cost'])) ? $_POST['cost'] : '0';

	if (!preg_match('/^[0-9\.]+$/', $plan['cost']))
	{
		$error = true;
		$msg[] = $esynI18N['error_plan_cost'];
	}

	if ('-1' == $_POST['num_allowed_listings_type'])
	{
		$plan['num_allowed_listings'] = '-1';
	}
	elseif ('0' == $_POST['num_allowed_listings_type'])
	{
		$plan['num_allowed_listings'] = $esynConfig->getConfig('account_listing_limit');
	}
	else
	{
		$plan['num_allowed_listings'] = $_POST['num_allowed_listings'];
	}

	$plan['deep_links'] = (isset($_POST['deep_links']) && !empty($_POST['deep_links'])) ? $_POST['deep_links'] : '0';

	if (!ctype_digit($plan['deep_links']))
	{
		$error = true;
		$msg[] = $esynI18N['error_plan_deep_links'];
	}

	$plan['multicross'] = (isset($_POST['multicross']) && !empty($_POST['multicross'])) ? $_POST['multicross'] : '0';

	if (!preg_match('/^[0-9\.]+$/', $plan['multicross']))
	{
		$error = true;
		$msg[] = $esynI18N['error_plan_multicross'];
	}

	$plan['categories'] = isset($_POST['categories']) ? $_POST['categories'] : array();
	$plan['expire_notif'] = isset($_POST['expire_notif']) ? $_POST['expire_notif'] : '';
	$plan['mark_as'] = (isset($_POST['markas']) && in_array($_POST['markas'], array('sponsored', 'featured', 'partner', 'regular'))) ? $_POST['markas'] : '';
	$plan['set_status'] = (isset($_POST['set_status']) && in_array($_POST['set_status'], array('active', 'approval', 'suspended'))) ? $_POST['set_status'] : '';
	$plan['fields'] = isset($_POST['fields']) && is_array($_POST['fields']) ? $_POST['fields'] : array();
	$plan['item'] = isset($_POST['item']) && in_array($_POST['item'], $valid_items) ? $_POST['item'] : 'listing';
	$plan['pages'] = isset($_POST['pages'][$_POST['item']]) ? join(",", $_POST['pages'][$_POST['item']]) : '';

	$plan['visual_options'] = isset($_POST['visual_options']) && is_array($_POST['visual_options']) ? $_POST['visual_options'] : array();

	if (isset($_POST['expire_action']) && !empty($_POST['expire_action']))
	{
		$actions = array(
			'remove',
			'approval',
			'banned',
			'suspended',
			'regular',
			'featured',
			'partner',
		);

		$plan['expire_action'] = in_array($_POST['expire_action'], $actions) ? $_POST['expire_action'] : '';
	}
	else
	{
		$plan['expire_action'] = '';
	}

	$plan['recursive'] = isset($_POST['recursive']) ? (int)$_POST['recursive'] : 0;

	$esynAdmin->startHook('adminPlanCommonFieldFilled');

	if (!$error)
	{
		if ('add' == $_POST['do'])
		{
			$result = $esynPlan->insert($plan);

			if ($result)
			{
				$msg[] = $esynI18N['plan_added'];
			}
			else
			{
				$error = true;
				$msg[] = $esynPlan->getMessage();
			}
		}
		elseif ('edit' == $_POST['do'])
		{
			$result = $esynPlan->update($plan, (int)$_POST['id']);

			if ($result)
			{
				$msg[] = $esynI18N['changes_saved'];
			}
			else
			{
				$error = true;
				$msg[] = $esynPlan->getMessage();
			}
		}

		$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

		esynMessages::setMessage($msg, $error);

		esynUtil::reload(array('do' => $do, 'item' => $plan['item']));
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
		$fields = $esynAdmin->get_column_names('plans');

		$status = isset($_GET['status']) && in_array($_GET['status'], $valid_statuses) ? $_GET['status'] : '';
		$item = isset($_GET['item']) && in_array($_GET['item'], $valid_items) ? $_GET['item'] : '';

		$sort = isset($_GET['sort']) && in_array($_GET['sort'], $fields) ? $_GET['sort'] : 'title';
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		$order = " ORDER BY `{$sort}` {$dir}";

		$where = "1=1 ";

		if (!empty($item))
		{
			$where .= "AND `item` = '$item' ";
		}
		if (!empty($status))
		{
			$where .= "AND `status` = '$status' ";
		}

		$out['total'] = $esynPlan->one("COUNT(*)");
		$out['data'] = $esynPlan->all("*, `id` `edit`, '1' `remove`", "{$where} {$order}", array(), $start, $limit);

		$units = array(
			"D" => $esynI18N['days'],
			"W" => $esynI18N['weeks'],
			"M" => $esynI18N['months'],
			"Y" => $esynI18N['years'],
		);

		if (is_array($out['data']))
		{
			foreach ($out['data'] as $key => $value)
			{
				if (isset($value['recurring']) && $value['recurring'])
				{
					$out['data'][$key]['unit'] = $units[$value['units_duration']];
				}
			}
		}
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

			$esynPlan->delete($ids);

			$out['msg'] = (count($ids) > 1) ? $esynI18N['plans_deleted'] : $esynI18N['plan_deleted'];
		}
	}

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

				$where = "`id` IN ('" . join("','", $ids) . "')";
			}
			else
			{
				$id = (int)$_POST['ids'];

				$where = "`id` = '{$id}'";
			}

			$esynAdmin->setTable("plans");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = _t("manage_plans");

$gBc[0]['title'] = $esynI18N['manage_plans'];
$gBc[0]['url'] = 'controller.php?file=plans';

if (isset($_GET['do']))
{
	if ('edit' == $_GET['do'] || 'add' == $_GET['do'])
	{
		$gBc[1]['title'] = ('add' == $_GET['do']) ? $esynI18N['add_plan'] : $esynI18N['edit_plan'];
		$gTitle = $gBc[1]['title'];
	}
}

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

$esynAdmin->setTable("listing_fields");
$fields = $esynAdmin->all('`id`, `name`', "`adminonly` = '0'");
$esynAdmin->resetTable();

$esynAdmin->setTable("listing_visual_options");
$visual_options = $esynAdmin->all('`name`, `price`', "`show` != '0'");
$esynAdmin->resetTable();

require_once IA_ADMIN_HOME . 'view.php';

$esynAdmin->startHook('plansAfterViewInclude');

if (isset($_GET['do']) && 'edit' == $_GET['do'] && isset($_GET['id']) && ctype_digit($_GET['id']))
{
	$plan = $esynPlan->row("*", "`id` = :id", array('id' => (int)$_GET['id']));

	$esynAdmin->setTable("field_plans");
	$plan['fields'] = $esynAdmin->onefield("`field_id`", "`plan_id` = :id", array('id' => (int)$_GET['id']));
	$esynAdmin->resetTable();

	$esynAdmin->setTable("plan_categories");
	$plan_categories = $esynAdmin->onefield("`category_id`", "`plan_id` = :id", array('id' => (int)$_GET['id']));
	$esynAdmin->resetTable();

	if (isset($plan['visual_options']))
	{
		$plan['visual_options'] = explode("','", trim($plan['visual_options'], "'"));
	}

	$plan_pages = explode(',', $plan['pages']);

	$plan_categories_parents = array();

	if (!empty($plan_categories))
	{
		$esynAdmin->setTable("flat_structure");

		foreach ($plan_categories as $plan_category)
		{
			$parents = $esynAdmin->all("`parent_id`", "`category_id` = :id", array('id' => $plan_category));

			if (!empty($parents))
			{
				$plan_parents = array();

				foreach ($parents as $parent)
				{
					$plan_parents[] = $parent['parent_id'];
				}

				array_pop($plan_parents);

				$plan_parents = array_reverse($plan_parents);

				$plan_categories_parents[] = '/' . join('/', $plan_parents) . '/';
			}
		}

		$esynAdmin->resetTable();
	}

	$plan_categories = !empty($plan_categories) ? join('|', $plan_categories) : '';
	$plan_categories_parents = !empty($plan_categories_parents) ? join('|', $plan_categories_parents) : '';

	$esynSmarty->assign('plan', $plan);
	$esynSmarty->assign('plan_categories', $plan_categories);
	$esynSmarty->assign('plan_categories_parents', $plan_categories_parents);
	$esynSmarty->assign('plan_pages', $plan_pages);
}

$esynSmarty->assign('fields', $fields);
$esynSmarty->assign('visual_options', $visual_options);
$esynSmarty->assign('valid_items', $valid_items);

$esynAdmin->startHook('plansBeforeDisplay');

$esynSmarty->display('plans.tpl');
