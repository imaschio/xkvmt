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

define('IA_REALM', "blog");

define("IA_ITEMS_PER_PAGE", 20);

$error = false;

if (isset($_POST['save']))
{
	$lang = $_POST['lang'];

	require_once IA_CLASSES . 'esynUtf8.php';
	require_once(IA_INCLUDES.'safehtml/safehtml.php');
	$safehtml = new safehtml();

	esynUtf8::loadUTF8Core();
	esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');

	$meta_keywords = $_POST['meta_keywords'];

	if (!utf8_is_valid($meta_keywords)) {

		$meta_keywords = utf8_bad_replace($meta_keywords);
	}

	$title = $_POST['title'];

	if (!utf8_is_valid($title))
	{
		$title = utf8_bad_replace($title);
	}

	$body 	= $_POST['body'];
	$body = trim($_POST['body']);
	$body = $safehtml->parse($body);

	$meta_description = trim($_POST['meta_description']);

	unset($safehtml);
	if (!utf8_is_valid($body))
	{
		$body = utf8_bad_replace($body);
	}

	if (!utf8_is_valid($meta_description)) {

		$meta_description = utf8_bad_replace($meta_description);
	}

	$alias 	= $title;

	if (!$body)
	{
		$error = true;
		$msg[] = $esynI18N['body_empty'];
	}

	if (!$title)
	{
		$error = true;
		$msg[] = $esynI18N['title_empty'];
	}

	$alias = $title;
	$date = $_POST['date']." ".$_POST['time'];

	if (!$error)
	{
		$alias = esynUtil::getAlias($alias);

		if (isset($_POST['do']) && 'add' == $_POST['do'])
		{
			$f = array(
				"title"		=> $title,
				"body"		=> $body,
				"meta_description" => $meta_description,
				"meta_keywords" => $meta_keywords,
				"alias"		=> $alias,
				"date"		=> $date,
				"lang"		=> $lang,
				"status" 	=> "active",
			);
			$esynAdmin->setTable("article_blog");

			$esynAdmin->insert($f);
			$esynAdmin->resetTable();
			$msg[] = $esynI18N['article_added'];
		}
		elseif (isset($_POST['do']) && 'edit' == $_POST['do'])
		{
			$article= array(
				"id" 		=> (int)$_POST['id'],
				"title" 	=> $title,
				"body" 		=> $body,
				"meta_description" => $meta_description,
				"meta_keywords" => $meta_keywords,
				"alias"		=> $alias,
				"date" 		=> $date,
				"lang" 		=> $lang,
				"status" 	=> $_POST['status'],
			);
			$esynAdmin->setTable("article_blog");
			$esynAdmin->update($article);
			$esynAdmin->resetTable();

			$msg[] = $esynI18N['changes_saved'];
		}

		$do = (isset($_POST['goto']) && 'add' == $_POST['goto']) ? 'add' : null;
		esynMessages::setMessage($msg, $error);

		esynUtil::reload(array("do" => $do));
	}
}

if (isset($_GET['action']))
{
	$esynAdmin->LoadClass("JSON");

	$json = new Services_JSON();

	if ('get_articles' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("article_blog");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", "1=1 ORDER BY `date` DESC", array(), $start, $limit);

		$esynAdmin->resetTable();
	}

	if ('get_comments' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];
		$id = (int)$_GET['id'];

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("article_blog_comment");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$out['data'] = $esynAdmin->all("*, `id` `edit`", "`id_blog` = '$id'", array(), $start, $limit);

		$esynAdmin->resetTable();
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
		if (!empty($_POST['ids']))
		{
			$where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("article_blog");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = (count($article) > 1) ? $esynI18N['articles'] : $esynI18N['article'];
			$out['msg'] .= ' '.$esynI18N['deleted'];

			$out['error'] = false;
		}
		else
		{
			$out['msg'] = $esynI18N['params_wrong'];
            $out['error'] = true;
		}
	}

	if ('remove_comment' == $_POST['action'])
	{
		if (!empty($_POST['ids']))
        {
            $where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("article_blog_comment");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();

			$out['msg'] = (count($article) > 1) ? $esynI18N['articles'] : $esynI18N['article'];
			$out['msg'] .= ' '.$esynI18N['deleted'];

			$out['error'] = false;
		}
		else
		{
			$out['msg'] = $esynI18N['params_wrong'];
            $out['error'] = true;
		}
	}

    if ('update' == $_POST['action'])
    {
        $field = $_POST['field'];
        $value = $_POST['value'];

	    if (!empty($field) && !empty($value) && !empty($_POST['ids']))
        {
            $where = $esynAdmin->convertIds('id', $_POST['ids']);

            $esynAdmin->setTable("article_blog");
            $esynAdmin->update(array($field => $value), $where);
            $esynAdmin->resetTable();

            $out['msg'] = $esynI18N['changes_saved'];
            $out['error'] = false;
        }
        else
        {
            $out['msg'] = $esynI18N['params_wrong'];
            $out['error'] = true;
        }
    }

	if ('update_comment' == $_POST['action'])
	{
		$field = $_POST['field'];
		$value = $_POST['value'];

		if (!empty($field) && !empty($value) && !empty($_POST['ids']))
        {
            $where = $esynAdmin->convertIds('id', $_POST['ids']);

			$esynAdmin->setTable("article_blog_comment");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

	        $out['msg'] = $esynI18N['changes_saved'];
            $out['error'] = false;
        }
        else
        {
            $out['msg'] = $esynI18N['params_wrong'];
            $out['error'] = true;
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_GET['do']) && 'edit' == $_GET['do'])
{
	$id = (int)$_GET['id'];

	$esynAdmin->setTable("article_blog");
	$article = $esynAdmin->row("*", "`id` = '{$id}'");
	$esynAdmin->resetTable();
	$temp_date = explode(' ', $article['date']);
	list($article['date'], $article['time']) = $temp_date;
}

$gTitle = $esynI18N['manage_blog'];
$gBc[1]['title'] = $gTitle;
$gBc[1]['url'] = 'controller.php?plugin=personal_blog';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[2]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_article'] : $esynI18N['add_article'];
		$gTitle = $gBc[2]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?plugin=personal_blog&amp;do=add", "icon" => "add.png", "label" => $esynI18N['add_article']),
	array("url" => "controller.php?plugin=personal_blog", "icon" => "view.png", "label" => $esynI18N['view'])
);

require_once IA_ADMIN_HOME . 'view.php';

$esynSmarty->assign('one_article', isset($article) ? $article : null);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');
