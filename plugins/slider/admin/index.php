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

define("ESYN_REALM", "slider");

$error = false;

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$id = (int)$_GET['id'];

	$esynAdmin->setTable("slides");
	$slide = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();

	$category_id = isset($_POST['category_id']) && ctype_digit($_POST['category_id']) ? $_POST['category_id'] : $slide['category_id'];

	$esynAdmin->setTable("categories");
	$category = $esynAdmin->row("*", "`id` = '{$category_id}'");
	$esynAdmin->resetTable();
}
else
{
	$category_id = isset($_POST['category_id']) && ctype_digit($_POST['category_id']) ? $_POST['category_id'] : 0;

	$esynAdmin->setTable("categories");
	$category = $esynAdmin->row("*", "`id` = '{$category_id}'");
	$esynAdmin->resetTable();
}

if (isset($_POST['save']))
{
	require_once IA_CLASSES . 'esynUtf8.php';
	require_once IA_INCLUDES . 'safehtml/safehtml.php';
	$safehtml = new safehtml();

	esynUtf8::loadUTF8Core();
	esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');

	$imgtypes = array(
		"image/gif" => "gif",
		"image/jpeg" => "jpg",
		"image/pjpeg" => "jpg",
		"image/png" => "png"
	);

	if (empty($_FILES['image']) && 'edit' != $_GET['do'])
	{
		$error = true;
		$err_mes = str_replace('{field}', $esynI18N['field_' . 'image'], $esynI18N['field_is_empty']);
		$msg .= "<li>".$err_mes."</li>";
	}

	if (isset($_POST['image']))
	{
		$image = $_POST['image'];
	}
	elseif (@is_uploaded_file($_FILES['image']['tmp_name']) && !$_FILES['image']['error'])
	{
		$ext = strtolower(utf8_substr($_FILES['image']['name'], -3));

		// if jpeg
		if ($ext == 'peg')
		{
			$ext = 'jpg';
		}

		if (!array_key_exists($_FILES['image']['type'], $imgtypes) || !in_array($ext, $imgtypes, true) || !getimagesize($_FILES['image']['tmp_name']))
		{
			$error = true;

			$a = implode(",", array_unique($imgtypes));

			$err_msg = str_replace("{types}", $a, $esynI18N['wrong_image_type']);
			$err_msg = str_replace("{name}", $field_name, $err_msg);

			$msg[] = $err_msg;
		}
		else
		{
			$file_name = $_FILES['image']['name'];

			$params = array(
				'image_width' => $esynConfig->getConfig('slider_width'),
				'image_height' => $esynConfig->getConfig('slider_height'),
				'resize_mode' => 'crop'
			);

			$esynAdmin->loadClass('Image');
			$image = new esynImage();

			$image->processImage($_FILES['image'], IA_UPLOADS, $file_name, $params);
		}
	}

	$title = trim(strip_tags($_POST['title']));

	$order = trim(strip_tags($_POST['order']));
	if (!utf8_is_valid($order))
	{
		$order = utf8_bad_replace($order);
	}

	$description = trim($_POST['description']);
	$description = $safehtml->parse($description);
	unset($safehtml);

	if (!utf8_is_valid($description))
	{
		$description = utf8_bad_replace($description);
	}

	$tab_title = trim($_POST['tab_title']);

	if (!$title)
	{
		$error = true;
		$msg[] = $esynI18N['error_title'];
	}
	if (!$description)
	{
		$error = true;
		$msg[] = $esynI18N['error_description'];
	}

	$cid = (int)$_POST['category_id'];

	$classname = trim(strip_tags($_POST['classname']));

	if (!$error)
	{
		if(isset($_POST['do']) && 'edit' == $_POST['do'])
		{
			$slide = array(
				"id"			=> (int)$_POST['id'],
				"title"			=> $title,
				"classname"		=> $classname,
				"description"	=> $description,
				"tab_title"		=> $tab_title,
				"category_id"	=> $cid,
				"order"			=> $order,
				"status"		=> "active",
			);

			if(isset($file_name))
			{
				$slide['image'] = $file_name;
			}

			$esynAdmin->setTable("slides");
			$esynAdmin->update($slide);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['changes_saved'];
		}
		else
		{
			$slide = array(
				"title"			=> $title,
				"classname"		=> $classname,
				"description"	=> $description,
				"tab_title"		=> $tab_title,
				"category_id" 	=> $cid,
				"order"			=> $order,
				"status" 		=> "active",
			);

			if(isset($file_name))
			{
				$slide['image'] = $file_name;
			}

			$esynAdmin->setTable("slides");
			$esynAdmin->insert($slide);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['slide_added'];
		}

		$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;
		esynMessages::setMessage($msg, $error);

		esynUtil::reload(array("do" => $do));
	}
	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']))
{
	$esynAdmin->factory('View');

	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		/* slides sorting */
		$sort = $_GET['sort'];
		$dir = in_array($_GET['dir'], array('ASC', 'DESC')) ? $_GET['dir'] : 'ASC';

		if (!empty($sort) && !empty($dir))
		{
			if ('parents' == $sort)
			{
				$order = " ORDER BY `categories`.`title` {$dir} ";
			}
			else
			{
				$order = " ORDER BY `{$sort}` {$dir} ";
			}
		}

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("slides");

		/* search query */
		$where = '1 ';

		if (isset($_GET['status']) && 'All' != $_GET['status'] && !empty($_GET['status']))
		{
			$where .= " AND `status` = '".esynSanitize::sql($_GET['status'])."'";
		}

		if (!empty($_GET['q']))
		{
			$q = esynSanitize::sql($_GET['q']);
			$where .= " AND `title` LIKE '%$q%' ";
		}

		$out['total'] = $esynAdmin->one("COUNT(`id`)", $where . $order);
		$out['data'] = $esynAdmin->all("`id`, `title`, `category_id`, `status`, `order`, `id` `edit`", $where . $order, array(), $start, $limit);
		$esynAdmin->resetTable();

		/* create categories chain */
		if(is_array($out['data']))
		{
			foreach($out['data'] as $key => $slide)
			{
				$out['data'][$key]['slide_details'] = '';

				esynView::getBreadcrumb($slide['category_id'], $categories_chain, true);

				if(!empty($categories_chain))
				{
					$parents = array();
					$categories_chain = array_reverse($categories_chain);

					if(count($categories_chain) > 1)
					{
						unset($categories_chain[0]);
					}

					foreach($categories_chain as $chain)
					{
						$parents[] = '<a href="controller.php?file=browse&id=' . $chain['id'] . '">' . $chain['title'] . '</a>';
					}

					$out['data'][$key]['parents'] = implode('&nbsp;/&nbsp;', $parents);
				}
				unset($categories_chain);
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
	if ('remove' == $_POST['action'])
	{
		$out = array('msg' => 'Unknow error', 'error' => true);

		$slide = $_POST['ids'];

		if(!is_array($slide) || empty($slide))
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}
		else
		{
			$slide = array_map(array('esynSanitize', 'sql'), $slide);
			$out['error'] = false;
		}

		if(!$out['error'])
		{
			if(is_array($slide))
			{
				foreach($slide as $new)
				{
					$ids[] = (int)$new;
				}

				$where = "`id` IN ('".join("','", $ids)."')";
			}
			else
			{
				$id = (int)$new;

				$where = "`id` = '{$id}'";
			}

			$esynAdmin->setTable("slides");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = (count($slide) > 1) ? $esynI18N['slides_deleted'] : $esynI18N['slide_deleted'];

			$out['error'] = false;
		}
	}

	if ('update' == $_POST['action'])
	{
		$out = array('msg' => 'Unknow error', 'error' => true);

		$field = $_POST['field'];
		$value = $_POST['value'];

		if (is_array($_POST['ids']))
		{
			$slide = array_map(array('esynSanitize', 'sql'), $_POST['ids']);
		}
		elseif(!empty($accounts))
		{
			$slide[] = esynSanitize::sql($_POST['ids']);
		}
		else
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}

		if (!empty($field) && !empty($value) && !empty($slide))
		{
			foreach($slide as $new)
			{
				$ids[] = (int)$new;
			}

			$where = "`id` IN ('".join("','", $ids)."')";

			$esynAdmin->setTable("slides");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = 'Changes saved';
			$out['error'] = false;
		}
		else
		{
			$out['msg'] = 'Wrong parametes';
			$out['error'] = true;
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

$gTitle = $esynI18N['manage_slide'];

$gBc[1]['title'] = $esynI18N['manage_slide'];
$gBc[1]['url'] = 'controller.php?plugin=slider';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[2]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_slide'] : $esynI18N['add_slide'];
		$gTitle = $gBc[2]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?plugin=slider&amp;do=add", "icon" => "add.png", "label" => $esynI18N['add_slide']),
	array("url" => "controller.php?plugin=slider", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->assign('slide', isset($slide) ? $slide : null);
$esynSmarty->assign('category', isset($category) ? $category : null);

$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'index.tpl');