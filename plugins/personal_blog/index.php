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

define('IA_REALM', "admin_blog");

include IA_INCLUDES . 'view.inc.php';

$esynSmarty->caching = false;

$id = (int)end($vals);

// breadcrumb formation
$eSyndiCat->factory("Layout", "Captcha");

$count_article=$esynConfig->getConfig('blogs_number');
$order_article=$esynConfig->getConfig('blogs_order');

require_once IA_CLASSES . 'esynUtf8.php';

esynUtf8::loadUTF8Core();
esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');

if (isset($_POST['add_comment']) && isset($_POST['id']))
{
	if (!$esynAccountInfo)
	{
		if ($esynConfig->getConfig('captcha') && '' != $esynConfig->getConfig('captcha_name'))
		{
			if (!$esynCaptcha->validate())
			{
				$error = true;
				$msg[] = $esynI18N['error_captcha'];
			}
		}
		$tmp=array
		(
			"author" 	=> $_POST['author'],
			"email" 	=> $_POST['email']
		);
		$insert_comment['author'] = $tmp['author'] = $_POST['author'];
		/** check for author name **/
		if (!$tmp['author'])
		{
			$error = true;
			$msg[] = $esynI18N['error_comment_author'];
		}
		elseif (!utf8_is_valid($tmp['author']))
		{
			$tmp['author'] = utf8_bad_replace($tmp['author']);
		}

		$insert_comment['email'] = $tmp['email'] = $_POST['email'];
		/** check for author email **/
		if (!esynValidator::isEmail($tmp['email']))
		{
			$error = true;
			$msg[] = $esynI18N['error_comment_email'];
		}
	}
	$tmp['body'] = $_POST['comment'];
	if (!utf8_is_valid($tmp['body']))
	{
		$tmp['body'] = utf8_bad_replace($tmp['body']);
	}

	if (utf8_is_ascii($tmp['body']))
	{
		$len = strlen($tmp['body']);
	}
	else
	{
		$len = utf8_strlen($tmp['body']);
	}

/** check for minimum chars **/
	if ($esynConfig->getConfig('blogs_comment_min') > 0)
	{
		if ($len < $esynConfig->getConfig('blogs_comment_min'))
		{
			$error = true;
			$esynI18N['blog_error_min_comment'] = str_replace('{minLength}', $esynConfig->getConfig('blogs_comment_min'), $esynI18N['blog_error_min_comment']);
			$msg[] = $esynI18N['blog_error_min_comment'];
		}
	}

	/** check for maximum chars **/
	if ($esynConfig->getConfig('blogs_comment_max') > 0)
	{
		if ($len > $esynConfig->getConfig('blogs_comment_max'))
		{
			$error = true;
			$esynI18N['blog_error_max_comment'] = str_replace('{maxLength}', $esynConfig->getConfig('blogs_comment_max'), $esynI18N['blog_error_max_comment']);
			$msg[] = $esynI18N['blog_error_max_comment'];
		}
	}
	if (!$tmp['body'])
	{
		$error = true;
		$msg[] = $esynI18N['error_comment'];
	}

	if (!$error)
	{
		if (!$esynAccountInfo)
		{
			setcookie("visitor_name", $tmp['author'], time() + 3600, '/');
			setcookie("visitor_email", $tmp['email'], time() + 3600, '/');
		}

		if (!empty($_POST['form_rating']) && ctype_digit($_POST['form_rating']))
		{
			$tmp['rating'] = (int)$_POST['form_rating'];
		}
		if (!empty($esynAccountInfo['id']) && ctype_digit($esynAccountInfo['id']))
		{
			(int)$esynAccountInfo['id'];
			$eSyndiCat->setTable('accounts');
			$insert_comment['author']=$eSyndiCat->one("username","`id`={$esynAccountInfo['id']}");
			$insert_comment['email']=$eSyndiCat->one("email","`id`={$esynAccountInfo['id']}");
			$eSyndiCat->resetTable();
		}
		if (!empty($_POST['id']) && ctype_digit($_POST['id']))
		{
			$tmp['listing_id'] = (int)$_POST['id'];
		}
		$tmp['ip_address']	= esynUtil::getIpAddress();
		$tmp['status']		= $esynConfig->getConfig('comments_approval') ? 'active' : 'approval';
		$tmp['sess_id'] = md5(session_id());

		$insert_comment['id_blog']=$_POST['id'];
		$insert_comment['comment']=$tmp['body'];
		$insert_comment['status']="approval";
		$eSyndiCat->setTable('article_blog_comment');
		$eSyndiCat->insert($insert_comment,array("date"=>"NOW()"));
		$eSyndiCat->resetTable();
		$msg[] = $esynI18N['comment_added'];

		$_POST = array();
	}

	$type = $error ? 'error' : 'notification';
	if ((isset($_COOKIE['visitor_name']) && isset($_COOKIE['visitor_email'])) && (!$error))
	{
		$esynSmarty->assign('author', $_COOKIE['visitor_name']);
		$esynSmarty->assign('email', $_COOKIE['visitor_email']);
		$esynSmarty->assign('body', $tmp['body'] ? $tmp['body'] : '');
	}

	if ($error)
	{
		$esynSmarty->assign('author', $tmp['author']);
		$esynSmarty->assign('email', $tmp['email']);
		$esynSmarty->assign('body', $tmp['body']);
	}
}

if ($id)
{
	// get one article array by article id
	$eSyndiCat->setTable("article_blog");
	$single_article = $eSyndiCat->row("*", "id = '{$id}'");
	$eSyndiCat->resetTable();

	if (!empty($single_article['tags']))
	{
		$keyword_cloud = '';
		$kwords = explode(",", $single_article['tags']);
		foreach( $kwords as $kw )
		{
			$kw	= trim($kw);
			$keyword_cloud .= ($keyword_cloud)?', ':'';
			$keyword_cloud .= '<a href="'.IA_URL.'search.php?what='.htmlspecialchars(urlencode($kw)).'" title="'.$kw.'">'.$kw.'</a>';
		}
		$single_article['tags'] = $keyword_cloud;
	}

	$esynSmarty->assignByRef('blog_article', $single_article);
	$esynSmarty->assign('description', $single_article['meta_description']);

	$esynSmarty->assign('keywords', $single_article['meta_keywords']);

	$esynSmarty->assign('body', '');

	esynBreadcrumb::add($esynI18N['admin_blog'], 'mod/blog/');
	esynBreadcrumb::add($single_article['title']);

	$title = $single_article['title'];
	$description = $single_article['body'];
	$tags = $single_article['tags'];

	$eSyndiCat->setTable("article_blog_comment");
	$comments = $eSyndiCat->all("*", "id_blog = '{$id}' and `status` = 'active'");
	$eSyndiCat->resetTable();
	$esynSmarty->assign('comments', $comments);
}
else
{
	// gets current page and defines start position
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
	$page = ($page < 1) ? 1 : $page;
	$start = ($page - 1) * (int)$count_article;
	$eSyndiCat->setTable("article_blog");

	// gets number of all listings
	$total_articles = $eSyndiCat->one("count(*)");
	// get article by status
	$where = "`status`='active' AND `lang`='".IA_LANGUAGE."' ORDER BY `$order_article` DESC, `date` DESC ";
	$all_articles = $eSyndiCat->all('*',$where, array(), $start, $count_article);
	$eSyndiCat->resetTable();

	$url = 'mod/blog/index{page}.html';
	$esynSmarty->assignByRef('url', $url);

	$esynSmarty->assignByRef('count_article', $total_articles);
	$esynSmarty->assignByRef('blog_articles', $all_articles);

	esynBreadcrumb::add($esynI18N['admin_blog']);

	$title = $esynI18N['admin_blog'];
	$description = $esynI18N['admin_blog'];
	$tags = $esynI18N['admin_blog'];

	$single_article['meta_keywords'] = $esynI18N['admin_blog'];
	$single_article['meta_description'] = $esynI18N['admin_blog'];
}

$esynSmarty->assign('title', strip_tags($title));
$esynSmarty->assign('keywords', strip_tags($single_article['meta_keywords']));
$esynSmarty->assign('description', strip_tags($single_article['meta_description']));

$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'index.tpl');