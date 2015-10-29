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

define('IA_REALM', "listing_fields");

esynUtil::checkAccess();

$esynAdmin->factory("ListingField");

if (isset($_POST['save']))
{
	$esynAdmin->startHook('adminAddListingFieldValidation');

	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error = false;

	$field = array();

	$field['name'] = $_POST['name'];
	$field['title'] = $_POST['title'];
	$field['type'] = $_POST['field_type'];
	$field['tooltip'] = strip_tags($_POST['tooltip']);
	$field['required'] = (int)$_POST['required'];
	$field['adminonly'] = isset($_POST['adminonly']) ? (int)$_POST['adminonly'] : 0;
	$field['check_all'] = isset($_POST['check_all']) ? (int)$_POST['check_all'] : 0;
	$field['pic_type'] = '';

	if ('edit' == $_POST['do'])
	{
		$field['type'] = $_POST['old_field_type'];
		$field['old_name'] = $_POST['old_name'];
	}
	$field['editor'] = ('textarea' == $field['type'] && isset($_POST['editor'])) ? (int)$_POST['editor'] : 0;

	if (!utf8_is_ascii($field['name']))
	{
		$error = true;
		$msg[] = $esynI18N['ascii_required'];
	}
	else
	{
		$field['name'] = strtolower($field['name']);
	}

	if (!$error && !preg_match('/^[a-z0-9\-_]{2,20}$/', $field['name']))
	{
		$error = true;
		$msg[] = $esynI18N['field_name_incorrect'];
	}

	// validate unique name when adding a new field
	if ('add' == $_POST['do'])
	{
		if (in_array($field['name'], $esynAdmin->get_column_names('listings')))
		{
			$error = true;
			$msg[] = $esynI18N['field_already_exists'];
		}
	}

	foreach($esynAdmin->mLanguages as $code => $lang)
	{
		if (!empty($field['title'][$code]))
		{
			if (!utf8_is_valid($field['title'][$code]))
			{
				$field['title'][$code] = utf8_bad_replace($field['title'][$code]);
			}
		}
		else
		{
			$error = true;
			$msg[] = str_replace("{field}", $lang . " " . $esynI18N['title'], $esynI18N['field_is_empty']);
			break;
		}
	}

	if (!utf8_is_ascii($field['type']))
	{
		$error = true;
		$msg[] = $esynI18N['ascii_required'];
	}

	if (empty($field['type']))
	{
		$error = true;
		$msg[] = $esynI18N['notice_field_type_empty'];
	}

	$field['length'] = '';
	if ('text' == $field['type'])
	{
		if (isset($_POST['length']) && ((int)$_POST['length'] > 255 || (int)$_POST['length'] < 1))
		{
			// default value
			$field['length'] = 100;
		}
		else
		{
			$field['length'] = (int)$_POST['length'];
		}
	}

	if (in_array($field['type'], array('storage', 'image')) && !is_writeable(IA_UPLOADS))
	{
		$error = true;
		$msg[] = $esynI18N['upload_writable_permission'];
	}

	if (in_array($field['type'], array('combo', 'radio', 'checkbox')))
	{
		if (!is_array($_POST['lang_values']))
		{
			$error = true;
			$msg[] = 'Item values must be filled';
		}

		if (empty($_POST['multiple_default']) && 0 != $field['required'])
		{
			$error = true;
			$msg[] = $esynI18N['field_default_incorrect'];

			$field['values'] = $_POST['lang_values'][IA_LANGUAGE];
		}

		elseif (count($esynAdmin->mLanguages) > 1 && is_array($_POST['lang_values']))
		{
			foreach($esynAdmin->mLanguages as $lang_key => $full_lang)
			{
				if ($esynConfig->getConfig('lang') == $lang_key)
				{
					continue;
				}

				foreach($_POST['lang_values'][$lang_key] as $key => $oneitem)
				{
					if (!empty($oneitem))
					{
						if (!utf8_is_valid($oneitem))
						{
							$_POST['lang_values'][$onelang][$key] = utf8_bad_replace($oneitem);
						}
					}
					else
					{
						$error = true;
						$msg[] = 'Extra Language Item values must be filled';
						break;
					}
				}
			}
		}
	}

	$field['pages'] = isset($_POST['pages']) ? join(",", $_POST['pages']) : '';

	// add group
	$esynAdmin->setTable('field_groups');
	$groups = $esynAdmin->onefield('`name`');
	$esynAdmin->resetTable();

	$field['group'] = '';

	if (isset($_POST['group']) && !empty($_POST['group']) && in_array($_POST['group'], $groups))
	{
		$field['group'] = $_POST['group'];
	}

	switch ($field['type'])
	{
		case 'text':
			$field['default'] = $_POST['text_default'];
			break;
		case 'number':
			$field['default'] = $_POST['text_default'];
			break;
		case 'textarea':
			$length = '';
			if ('' != $_POST['min_length'])
			{
				$length = (int)$_POST['min_length'];
			}
			$length .= ',';
			if ('' != $_POST['max_length'])
			{
				$length .= (int)$_POST['max_length'];
			}
			$field['length'] = $length;
			break;
		case 'image':
			$field['instead_thumbnail'] = $_POST['instead_thumbnail'];

			if ($field['instead_thumbnail'])
			{
				$field['image_height'] = (int)$_POST['image_height'];
				$field['image_width'] = (int)$_POST['image_width'];
				$field['resize_mode'] = 'crop';
				$field['file_prefix'] = 'thumb_';
			}
			else
			{
				$field['image_title_length'] = isset($_POST['image_title_length']) ? (int)$_POST['image_title_length'] : 50;
				$field['image_height'] = (int)$_POST['image_height'];
				$field['image_width'] = (int)$_POST['image_width'];
				$field['thumb_width'] = (int)$_POST['thumb_width'];
				$field['thumb_height'] = (int)$_POST['thumb_height'];
				$field['resize_mode'] = isset($_POST['resize_mode']) && in_array($_POST['resize_mode'], array('crop', 'fit')) ? $_POST['resize_mode'] : 'crop';
				$field['file_prefix'] = $_POST['file_prefix'];
			}
			break;
		case 'pictures':
			$field['image_height'] = $_POST['pic_image_height'];
			$field['image_width'] = $_POST['pic_image_width'];
			$field['thumb_width'] = $_POST['pic_thumb_width'];
			$field['thumb_height'] = $_POST['pic_thumb_height'];
			$field['resize_mode'] = isset($_POST['pic_resize_mode']) && in_array($_POST['pic_resize_mode'], array('crop', 'fit')) ? $_POST['pic_resize_mode'] : 'crop';
			$field['file_prefix'] = $_POST['pic_file_prefix'];
			$field['pic_type'] = isset($_POST['pic_type']) && in_array($_POST['pic_type'], array('gallery', 'separate')) ? $_POST['pic_type'] : 'gallery';

			$field['length'] = isset($_POST['pic_max_images']) ? (int)$_POST['pic_max_images'] : 5;
			$field['image_title_length'] = isset($_POST['pic_title_length']) ? (int)$_POST['pic_title_length'] : 50;
			break;
		default:
			if (!empty($_POST['lang_values'][IA_LANGUAGE]) && is_array($_POST['lang_values'][IA_LANGUAGE]))
			{
				$field['default'] = '';
				$jt_multiple = explode('|', $_POST['multiple_default']);

				foreach($_POST['lang_values'][IA_LANGUAGE] as $key => $cause_item)
				{
					if (!utf8_is_valid($key))
					{
						$key = utf8_bad_replace($key);
					}

					if ('checkbox' == $field['type'])
					{
						if (in_array($cause_item, $jt_multiple))
						{
							$field['default'] = '' == $field['default'] ? $key : $field['default'].','.$key;
						}
					}
					else
					{
						// multiple default is available for checkboxes only
						$_POST['multiple_default'] = str_replace(strstr($_POST['multiple_default'],'|'),'',$_POST['multiple_default']);
						$field['default'] = ($cause_item == $_POST['multiple_default']) ? $key : $field['default'];
					}
				}

				unset($jt_multiple);

				$field['values'] = $_POST['lang_values'][IA_LANGUAGE];
			}
			break;
	}

	// 'title' is array and array_map is not recursive
	$titles = $field['title'];
	unset($field['title']);

	if (in_array($field['type'], array('combo', 'radio', 'checkbox')))
	{
		$vvs = $field['values'];
		unset($field['values']);

		$vvs = is_array($vvs) ? array_map(array("esynSanitize", "sql"), $vvs) : array();
	}

	if (isset($_POST['file_types']))
	{
		$file_types = str_replace(" ", "", $_POST['file_types']);
		$field['file_types'] = $file_types;
	}

	$field = array_map(array("esynSanitize", "sql"),$field);

	$field['title'] = $titles;
	unset($titles);

	if (in_array($field['type'], array('combo', 'radio', 'checkbox')))
	{
		$field['values'] = $vvs;
		unset($vvs);
	}

	if (isset($_POST['lang_values']) && is_array($_POST['lang_values']) && in_array($field['type'], array('combo', 'radio', 'checkbox')))
	{
		$field['lang_values'] = $_POST['lang_values'];
	}

	$field['searchable'] = isset($_POST['searchable']) ? (int)$_POST['searchable'] : 0;

	if (isset($_POST['show_as']) && in_array($_POST['show_as'], array("combo","radio","checkbox")))
	{
		$field['show_as'] = $_POST['show_as'];
	}

	if (isset($_POST['any_meta']) && is_array($_POST['any_meta']))
	{
		$x = array();
		foreach($_POST['any_meta'] as $l=>$m)
		{
			$x[$l] = esynSanitize::sql($m);
		}
		$field['any_meta'] = $x;
		unset($x);
	}

	/*if (isset($field['type']) && $field['type'] == 'number' && !empty($_POST['_values']))
	{
		$field['_numberRangeForSearch'] = array();
		foreach($_POST['_values'] as $k=>$v)
		{
			if (!empty($_POST['_titles'][$k]))
			{
				$field['_numberRangeForSearch'][] = array($v, $_POST['_titles'][$k]);
			}
		}
	}*/

	if (!empty($_POST['plans']))
	{
		$_POST['plans'] = array_unique(array_map("intval", $_POST['plans']));

		// use underscore to avoid name conflicts
		$field['_plans'] = $_POST['plans'];
	}
	else
	{
		$esynAdmin->setTable('plans');
		$ids_plan = $esynAdmin->all("`id`", "`item` = 'listing' AND `cost` > 0");
		$esynAdmin->resetTable();

		if (!empty($ids_plan))
		{
			foreach($ids_plan as $id_plan)
			{
				$field['_plans'][] = $id_plan['id'];
			}
		}
	}

	$field["categories"] = $_POST['categories'];
	$field["recursive"] = (int)isset($_POST['recursive']);

	if (isset($field["categories"]) && '' != $field["categories"])
	{
		$field["categories"] = explode('|', $field["categories"]);
		$field["categories"] = array_unique(array_map("intval", $field["categories"]));
	}
	else
	{
		// if there is no bind to any categories
		// bind listing field to ROOT
		$esynAdmin->setTable("categories");
		$root_id = $esynAdmin->one('id', "`parent_id` = '-1'");
		$esynAdmin->resetTable();

		$field['categories'] = array($root_id);
		$field['recursive'] = 1;
	}

	if (!$error)
	{
		if ('edit' == $_POST['do'])
		{
			if ($field['searchable'] && !empty($_POST['make_fulltext']))
			{
				$field['searchable'] = 2;
			}

			$result = $esynListingField->update($field);

			if ($result)
			{
				$msg[] = $esynI18N['changes_saved'];
			}
			else
			{
				$error = true;
				$msg[] = $esynListingField->getMessage();
			}
		}
		else
		{
			if ($field['searchable'] && !empty($_POST['make_fulltext']))
			{
				$field['searchable'] = 2;
			}

			$result = $esynListingField->insert($field);

			if ($result)
			{
				$msg[] = $esynI18N['field_added'];
			}
			else
			{
				$error = true;
				$msg[] = $esynListingField->getMessage();
			}
		}

		$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

		$esynAdmin->mCacher->clearAll('language');
		$esynAdmin->mCacher->clearAll('intelli.lang');
		$esynAdmin->mCacher->clearAll('intelli.admin.lang');

		esynMessages::setMessage($msg, $error);

		esynUtil::reload(array("do" => $do));
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']))
{
	$start = isset($_GET['start']) ? (int)$_GET['start'] : false;
	$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : false;

	$out = array('data' => '', 'total' => 0);

	if ('get' == $_GET['action'])
	{
		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		if (!empty($sort) && !empty($dir))
		{
			$order = " ORDER BY `{$sort}` {$dir} ";
		}

		$where = array();

		if (isset($_GET['what']) && !empty($_GET['what']))
		{
			$what = esynSanitize::sql($_GET['what']);
			$words = preg_split('/[\s]+/', $what);

			$tmp = array();

			foreach ($words as $word)
			{
				$tmp[] = "CONCAT(`name`,' ',`l`.`value`) LIKE '%{$word}%' ";
			}

			$where[] = '(' . implode(" OR ", $tmp) . ')';
		}

		if (isset($_GET['type']) &&	!empty($_GET['type']))
		{
			$type = esynSanitize::sql($_GET['type']);

			$where[] = "`lf`.`type` = '{$type}'";
		}

		if (!empty($where))
		{
			$where = implode(' AND ', $where);
		}
		else
		{
			$where = '1=1';
		}

		$out['total'] = $esynListingField->one("COUNT(*)");

		$sql = "SELECT DISTINCT `lf`.*, `l`.`value` `title`, `l1`.`value` `group_title`, `lf`.`id` `edit`, "
			 . "if (`lf`.`name` IN('" . implode("','", $esynListingField->readOnlyFields) . "'), '0', '1') `remove` "
			 . "FROM `{$esynAdmin->mPrefix}listing_fields` `lf` "
			 . "LEFT JOIN `{$esynAdmin->mPrefix}language` `l` "
			 . "ON `l`.`key` = CONCAT('field_', `lf`.`name` ) "
			 . "LEFT JOIN `{$esynAdmin->mPrefix}language` `l1` "
			 . "ON `l1`.`key` = CONCAT('field_group_title_', `lf`.`group`) "
			 . "WHERE {$where} "
			 . "GROUP BY `lf`.`name` "
			 . $order
			 . "LIMIT {$start}, {$limit}";

		$out['data'] = $esynListingField->getAll($sql);
    }

	if ('getgroups' == $_GET['action'])
	{
		$sql = "SELECT `fg`.`name`, `l`.`value` `title` "
			 . "FROM `{$esynAdmin->mPrefix}field_groups` `fg` "
			 . "LEFT JOIN `{$esynAdmin->mPrefix}language` `l` "
			 . "ON `l`.`key` = CONCAT('field_group_title_', `fg`.`name`) "
			 . "WHERE `l`.`code` = '" . IA_LANGUAGE . "'";

		$out['data'] = $esynListingField->getAll($sql);
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
		$result = $esynListingField->delete($_POST['ids']);

		if ($result)
		{
			$out['error'] = false;
			$out['msg']	= $esynI18N['deleted'];
		}
		else
		{
			$out['error'] = true;
			$out['msg'] = $esynListingField->getMessage();
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

			$esynAdmin->setTable("listing_fields");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['changes_saved'];
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_fields'];

$gBc[0]['title'] = $esynI18N['manage_fields'];
$gBc[0]['url'] = 'controller.php?file=listing-fields';

if (isset($_GET['do']))
{
	$gBc[1]['title'] = ('add' == $_GET['do']) ? $esynI18N['add_field'] : $esynI18N['edit_field'];
	$gTitle = $gBc[1]['title'];
}

$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? $_GET['id'] : 0;

$actions = array(
	array("url" => "controller.php?file=listing-fields&amp;do=add", "icon" => "add.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?file=listing-fields", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']) && 'edit' == $_GET['do'] && isset($_GET['id']))
{
	$field = $esynListingField->row("*", "`id` = :id", array('id' => (int)$_GET['id']));

	$field_pages = !empty($field['pages']) ? explode(',', $field['pages']) : array();

	$esynAdmin->setTable("field_plans");
	$field_plans = $esynAdmin->onefield("`plan_id`", "`field_id` = :id", array('id' => (int)$_GET['id']));
	$esynAdmin->resetTable();

	$field_plans = !empty($field_plans) ? $field_plans : array();

	$esynAdmin->setTable("field_categories");
	$field_categories = $esynAdmin->onefield("`category_id`", "`field_id` = :id", array('id' => (int)$_GET['id']));
	$esynAdmin->resetTable();

	$field_categories_parents = array();

	if (!empty($field_categories))
	{
		$esynAdmin->setTable("flat_structure");

		foreach($field_categories as $field_category)
		{
			$parents = $esynAdmin->all("`parent_id`", "`category_id` = :id", array('id' => $field_category));

			if (!empty($parents))
			{
				$field_parents = array();

				foreach($parents as $parent)
				{
					$field_parents[] = $parent['parent_id'];
				}

				array_pop($field_parents);

				$field_parents = array_reverse($field_parents);

				$field_categories_parents[] = '/'.join('/', $field_parents).'/';
			}
		}

		$esynAdmin->resetTable();
	}

	$field_categories = !empty($field_categories) ? join('|', $field_categories) : '';
	$field_categories_parents = !empty($field_categories_parents) ? join('|', $field_categories_parents) : '';

	if (in_array($field['type'], array('combo', 'radio', 'checkbox')))
	{
		$esynAdmin->setTable('language');

		$values_count = $esynAdmin->one("COUNT(*)", "`key` LIKE 'field_{$field['name']}_%' AND `code` = '".IA_LANGUAGE."'");

		if (!empty($esynAdmin->mLanguages))
		{
			for($i = 0; $i < $values_count; $i++)
			{
				foreach($esynAdmin->mLanguages as $code => $value)
				{
					$field_values[$i][$code] = $esynAdmin->one("`value`", "`key` = 'field_{$field['name']}_{$i}' AND `code` = '{$code}'");
				}
			}
		}

		$esynAdmin->resetTable();
	}

	$field_titles = array();

	if (!empty($esynAdmin->mLanguages))
	{
		$esynAdmin->setTable('language');

		foreach($esynAdmin->mLanguages as $code => $value)
		{
			$field_titles[$code] = $esynAdmin->one("value", "`key` = 'field_{$field['name']}' AND `code` = '{$code}'");
		}

		$esynAdmin->resetTable();
	}

	if (isset($field['default']) && '' != $field['default'] && in_array($field['type'], array('combo', 'radio', 'checkbox')))
	{
		$field['default'] = explode(',', $field['default']);

		$esynAdmin->setTable('language');

		foreach($field['default'] as $key => $default)
		{
			$default_values[] = $esynAdmin->one("`value`", "`key` = 'field_{$field['name']}_{$default}' AND `code` = '".IA_LANGUAGE."'");
		}

		if (!empty($default_values))
		{
			$field['default'] = join("|", $default_values);
		}

		$esynAdmin->resetTable();
	}

	$esynSmarty->assign('field', $field);
	$esynSmarty->assign('pages', $field_pages);
	$esynSmarty->assign('field_plans', $field_plans);
	$esynSmarty->assign('field_categories', $field_categories);
	$esynSmarty->assign('field_categories_parents', $field_categories_parents);

	if (isset($field_values) && !empty($field_values))
	{
		$esynSmarty->assign('field_values', $field_values);
	}

	$esynSmarty->assign('field_titles', $field_titles);
}

if (isset($_GET['do']) && 'add' == $_GET['do'])
{
	$pages = isset($_POST['pages']) ? $_POST['pages'] : array();

	$esynSmarty->assign('pages', $pages);
}

if (isset($_POST['lang_values']) && !empty($_POST['lang_values']))
{
	$values_count = count($_POST['lang_values'][IA_LANGUAGE]);

	if (!empty($esynAdmin->mLanguages))
	{
		for($i = 0; $i < $values_count; $i++)
		{
			foreach($esynAdmin->mLanguages as $code => $value)
			{
				$field_values[$i][$code] = $_POST['lang_values'][$code][$i];
			}
		}
	}

	$esynSmarty->assign('field_values', $field_values);
}

$esynAdmin->setTable('field_groups');
$field_groups = $esynAdmin->all('*', '1 ORDER BY `order`');
$esynAdmin->resetTable();

$field_types = array(
	'text'		=> 'Text',
	'textarea'	=> 'Textarea',
	'number'	=> 'Number',
	'combo'		=> 'Dropdown List',
	'radio'		=> 'Radios Set',
	'checkbox'	=> 'Checkboxes Set',
	'storage'	=> 'File Storage',
	'image'		=> 'Image',
	'pictures'	=> 'Gallery of images'
);

$esynAdmin->startHook('adminAfterGetFieldTypes');

$esynAdmin->setTable("plans");
$plans = $esynAdmin->all('*', "`item` = 'listing' AND `status` = 'active'");
$esynAdmin->resetTable();

$esynSmarty->assign('field_groups', $field_groups);
$esynSmarty->assign('field_types', $field_types);
$esynSmarty->assign('plans', $plans);

$esynSmarty->display('listing-fields.tpl');
