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

define('IA_REALM', "news");

/*
 * ACTIONS
 */
if (isset($_POST['save']))
{
	if (!defined('IA_NOUTF'))
	{
		require_once IA_CLASSES . 'esynUtf8.php';

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}

	$error		= false;
	$msg		= '';
	$new_news	= array();

	$new_news['lang']	= IA_LANGUAGE;
	$new_news['title']	= $_POST['title'];
	$new_news['body']	= $_POST['body'];
	$new_news['date']	= $_POST['date'];
	$new_news['status']	= isset($_POST['status']) && !empty($_POST['status']) && in_array($_POST['status'], array('active', 'inactive')) ? $_POST['status'] : 'inactive';

	//Image process
	$imgtypes = array(
		"image/gif" => "gif",
		"image/jpeg" => "jpg",
		"image/pjpeg" => "jpg",
		"image/png" => "png"
	);

	if (isset($_POST['image_del']))
	{
		unlink(IA_HOME . 'uploads' . IA_DS . $_POST['news_cur_image']);
		$new_news['image'] = '';
	}
	else
	{
		$new_news['image'] = isset($_POST['news_cur_image']) ? $_POST['news_cur_image'] : '';
	}

	if (is_uploaded_file($_FILES['news_image']['tmp_name']) && !$_FILES['news_image']['error'])
	{
		$ext = strtolower(utf8_substr($_FILES['news_image']['name'], -3));

		// if jpeg
		if ($ext == 'peg')
		{
			$ext = 'jpg';
		}
		if (!array_key_exists($_FILES['news_image']['type'], $imgtypes) || !in_array($ext, $imgtypes, true) || !getimagesize($_FILES['news_image']['tmp_name']))
		{
			$error = true;

			$a = implode(",",array_unique($imgtypes));

			$err_msg = str_replace("{types}", $a, $esynI18N['wrong_image_type']);
			$err_msg = str_replace("{name}", $field_name, $err_msg);

			$msg[] = $err_msg;
		}
		else
		{
			$esynAdmin->loadClass('Image');

			$file_name = $_FILES['news_image']['name'];

			$new_news['image'] = $file_name;

			if (isset($_POST['news_cur_image']) && is_file(IA_HOME . 'uploads' . IA_DS . $_POST['news_cur_image']))
			{
				unlink(IA_HOME . 'uploads' . IA_DS . $_POST['news_cur_image']);
			}

			$fname = IA_HOME.'uploads'.IA_DS;

			$image = new esynImage();

			$image_info = array(
				'thumb_width' => $esynConfig->getConfig('news_thumb_width'),
				'thumb_height' => $esynConfig->getConfig('news_thumb_height'),
				'image_width' => $esynConfig->getConfig('news_image_width'),
				'image_height' => $esynConfig->getConfig('news_image_height'),
				'resize_mode' => 'crop'
			);

			$image->processImage($_FILES['news_image'], $fname, $file_name, $image_info);
		}
	}

	if (!empty($_POST['lang']) && array_key_exists($_POST['lang'], $esynAdmin->mLanguages))
	{
		$new_news['lang'] = $_POST['lang'];
	}

	if (!utf8_is_valid($new_news['title']))
	{
		$new_news['title'] = utf8_bad_replace($new_news['title']);
	}

	if (!utf8_is_valid($new_news['body']))
	{
		$new_news['body'] = utf8_bad_replace($new_news['body']);
	}

	if (empty($new_news['title']))
	{
		$error = true;
		$msg[] = $esynI18N['error_title'];
	}

	if (empty($new_news['body']))
	{
		$error = true;
		$msg[] = $esynI18N['error_body'];
	}

	$new_news['alias'] = strip_tags($new_news['title']);

	if (!utf8_is_ascii($new_news['alias']))
	{
		$new_news['alias'] = utf8_to_ascii($new_news['alias']);
	}

	$new_news['alias'] = preg_replace("/\s+/", "-", $new_news['alias']);
	$new_news['alias'] = preg_replace("/[^\w\-]/i", "", $new_news['alias']);

	if (!$error)
	{
		if (isset($_GET['do']) && 'edit' == $_GET['do'])
		{
			$new_news['id'] = (int)$_GET['id'];

			$esynAdmin->setTable("news");
			$esynAdmin->update($new_news);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['changes_saved'];
		}
		else
		{
			$esynAdmin->setTable("news");
			$esynAdmin->insert($new_news);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['news_added'];
		}

		$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;

		esynMessages::setMessage($msg, $error);

		esynUtil::reload(array("do" => $do));
	}

	esynMessages::setMessage($msg, $error);
}

if (isset($_GET['action']))
{

	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];
		$order = isset($_GET['sort']) ? "ORDER BY `{$_GET['sort']}` {$_GET['dir']}" : '';

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("news");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", "1=1 ".$order, $start, $limit);

		$esynAdmin->resetTable();
	}

	if (empty($out['data']))
	{
		$out['data'] = '';
	}
	else
	{
		$out['data'] = esynSanitize::applyFn($out['data'], "striptags", array('body'));
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{

	if ('remove' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => false);

		if (empty($_POST['ids']) || !is_array($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}

		if (!$out['error'])
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("news");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = $esynI18N['news'].' '.$esynI18N['deleted'];
		}
	}

	if ('update' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => false);

		if (empty($_POST['field']) || empty($_POST['value']) || empty($_POST['ids']))
		{
			$out['error'] = true;
			$out['msg'] = 'Wrong params';
		}

		if (!$out['error'])
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("news");
			$esynAdmin->update(array($_POST['field'] => $_POST['value']), $where);
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

$gTitle = $esynI18N['manage_news'];

$gBc[1]['title'] = $esynI18N['manage_news'];
$gBc[1]['url'] = 'controller.php?plugin=news';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[2]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_news'] : $esynI18N['add_news'];
		$gTitle = $gBc[2]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?plugin=news&amp;do=add", "icon" => "add.png", "label" => $esynI18N['create']),
	array("url" => "controller.php?plugin=news", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$id = (int)$_GET['id'];

	$esynAdmin->setTable("news");
	$news = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();

	$esynSmarty->assign('news', $news);
}

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');