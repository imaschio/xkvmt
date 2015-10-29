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

define('IA_REALM', "banners");

$error = false;

$esynAdmin->LoadClass("JSON");

$id = false;
if (isset($_GET['id']) && !preg_match('/\D/', $_GET['id']))
{
	$id = (int)$_GET['id'];
}

$ads = IA_HOME."uploads";

$targets = array(
	"_blank"	=>	$esynI18N["_blank"],
	"_self"		=>	$esynI18N["_self"],
	"_parent"	=>	$esynI18N["_parent"],
	"_top"		=>	$esynI18N["_top"]
);

$statuses = array(
	"inactive"	=>	$esynI18N["inactive"],
	"active"	=>	$esynI18N["active"]
);

$types = array(
	"local"		=> $esynI18N["local"],
	"remote"	=> $esynI18N["remote"],
	"text"		=> $esynI18N["text"],
	"html"		=> $esynI18N["html"],
);

$positions = explode(",", $esynConfig->config['esyndicat_block_positions']);

$imgtypes = array(
	"image/gif"		=> "gif",
	"image/jpeg"	=> "jpg",
	"image/pjpeg"	=> "jpg",
	"image/png"		=> "png"
);

$one_banner = array(
	'type'=>null,	'image'=>null,	'position'=>null,
	'title'=>null,	'alt'=>null,	'content'=>null,	'planetext_content'=>null,
	'width'=>null,	'height'=>null,	'url'=>null,	    'target'=>null
);

$esynAdmin->loadPluginClass("Banner", "banners", 'esyn', true);
$esynBanner = new esynBanner();

if (!defined('IA_NOUTF'))
{
	require_once(IA_CLASSES.'esynUtf8.php');

	esynUtf8::loadUTF8Core();
	esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
}
/*
 * ACTIONS
 */

if (isset($_POST['save']))
{
	if (!empty($_POST['title']) && !utf8_is_valid($_POST['title']))
	{
		$_POST['title'] = utf8_bad_replace($_POST['title']);
	}

	if (!empty($_POST['alt']) && !utf8_is_valid($_POST['alt']))
	{
		$_POST['alt'] = utf8_bad_replace($_POST['alt']);
	}

	if (!empty($_POST['content']) && !utf8_is_valid($_POST['content']))
	{
		$_POST['content'] = utf8_bad_replace($_POST['content']);
	}

    if (!empty($_POST['planetext_content']) && !utf8_is_valid($_POST['planetext_content']))
	{
		$_POST['planetext_content'] = utf8_bad_replace($_POST['planetext_content']);
	}

	$title		= $_POST['title'];
	$alt		= $_POST['alt'];
	$url		= !strstr($_POST['url'],"http://") ? "http://".$_POST['url'] : $_POST['url'];
	$imageUrl	= $_POST['image'];
	$target		= $_POST['targetframe'];

	$no_follow  = (int)isset($_POST['no_follow']);
	$status		= (isset($_POST['status']) && array_key_exists($_POST['status'], $statuses)) ? $_POST['status'] : 'inactive';
	$position	= in_array($_POST['position'], $positions) ? $_POST['position'] : 'top';

	if (!$title)
	{
		$error = true;
		$msg[] = $esynI18N['banner_title_is_empty'];
	}

	if (isset($_POST['type']) && array_key_exists($_POST['type'], $types))
	{
		$type = $_POST['type'];
	}
	elseif ('add' == $_GET['do'])
	{
		$type = 'local';
	}
	else
	{
		$error = true;
		$msg = $esynI18N['banner_type_incorrect'];
	}

	$jsOk = $imageOk = $flashOk = false;

	if ('local' == $type)
	{
		if (is_uploaded_file($_FILES['uploadfile']['tmp_name']))
		{
			if (array_key_exists($_FILES['uploadfile']['type'], $imgtypes))
			{
				$imageOk = true;
			}
			elseif ($_FILES['uploadfile']['type'] == 'application/x-shockwave-flash')
			{
				$flashOk = true;
				$type = 'flash';
			}
			else
			{
				$error = true;
				$msg = $esynI18N['incorrect_filetype'];
			}
		}
		elseif ('add' == $_GET['do'])
		{
			$error = true;
			$msg = $esynI18N['unknown_upload'];
		}
	}

	if ('html' == $type)
	{
		$content =$_POST['content'];
		$planetext_content = '';
	}
	elseif ('text' == $type)
	{
		$planetext_content = $_POST['planetext_content'];
		$content = '';
	}
	else
	{
	    $planetext_content = '';
	    $content = '';
	}

	if (empty($content) && empty($planetext_content) && in_array($type,array('html','text')))
	{
		$error = true;
		$msg = $esynI18N['content_incorrect'];
	}

	if (empty($_POST['image']) &&  'remote' == $type)
	{
		$error = true;
		$msg = $esynI18N['remote_url_incorrect'];
	}

	if (empty($_POST['url']) && 'html' != $type)
	{
		$error = true;
		$msg = $esynI18N['banner_url_incorrect'];
	}


	if (!$error)
	{
		$image_params = (int)$_POST['params'];
		if (!$image_params)
		{
			$width = (int)$_POST['width'];
			$height	= (int)$_POST['height'];
		}
		else
		{
			$width = 0;
			$height = 0;
		}

		$bn_prefix = $esynConfig->getConfig('banner_prefix');
		$recursive = (int)isset($_POST['recursive']);

		if (isset($_POST['categories']))
		{
			$_POST['categories'] = explode('|', $_POST['categories']);
			$_POST['categories'] = array_unique(array_map("intval", $_POST['categories']));
		}
		else
		{
			$_POST['categories'] = array();
		}

		if (isset($_POST['do']) && 'edit' == $_POST['do'])
		{
			$banner = array(
				'id'=> $id,
				'categories'=> $_POST['categories'],
				'title'=> $title,
				'alt'=> $alt,
				'url'=> $url,
				'image'=> $imageUrl,
				'recursive'=> $recursive,
				'type'=> $type,
				'content'=> $content,
				'planetext_content'=> $planetext_content,
				'position'=> $position,
				'height'=> $height,
				'width'=> $width,
				'target'=> $target,
				"status" => $status,
				'no_follow'=> $no_follow
			);

			$one_banner = $esynBanner->row("*","`id`='{$id}'");
			$esynBanner->update($banner,"`id` = '{$id}'");
			//clear_cache();
			if ($esynBanner->exists("`status`='active'"))
			{
				$esynConfig->setConfig("banners_exist", "1", true);
			}
			else
			{
				$esynConfig->setConfig("banners_exist", "", true);
			}
			// is new image is uploaded
			if ($imageOk)
			{
				if (!empty($one_banner['image']) && file_exists($ads.IA_DS.$one_banner['image']))
				{
					unlink($ads.IA_DS.$one_banner['image']);
				}
				$fname = $ads.IA_DS.$bn_prefix.$id.".".$imgtypes[$_FILES['uploadfile']['type']];
				list($iwidth,$iheight) = getimagesize($_FILES['uploadfile']['tmp_name']);

				if ($width == 0 || $height == 0)
				{
					$width = $iwidth;
					$height = $iheight;
				}

			    if (isset($_POST['keep_ratio']) and isset($_POST['fit']))
				{
				    $imageResizeOption = 1003;
				}
				elseif (isset($_POST['keep_ratio']) and !isset($_POST['fit']))
				{
				    $imageResizeOption = 1001;
				}
				else
				{
				    $imageResizeOption = 1002;
				}
				// set image
				$image = $bn_prefix.$id.".".$imgtypes[$_FILES['uploadfile']['type']];

				//TODO: change resize image class
				$esynAdmin->loadClass('Image');

				if (!move_uploaded_file($_FILES['uploadfile']['tmp_name'],$fname))
				{
					$error = true;
					$msg = $esynI18N['upload_correct_permission'];
				}
				else
				{
					//if needs to be resized
					if ($iwidth != $width && $iheight != $height)
					{

    					$rimg = new esynImage();
    					$aFile['tmp_name'] = $fname;
    					$rimg->processImage($aFile, $fname, $width, $height, $imageResizeOption);
					}
					chmod($fname, 0755);
				}
				list($width,$height) = getimagesize($fname);
				$banner = array('image'=>$image, 'width'=>$width,'height'=>$height);
				$esynBanner->update($banner, "`id`='{$id}'");
			}// flash uploading
			elseif ($flashOk)
			{
				if (!empty($one_banner['image']) && file_exists($ads.IA_DS.$one_banner['image']))
				{
					unlink($ads.IA_DS.$one_banner['image']);
				}
				$fname = $ads.IA_DS.$bn_prefix.$id.".swf";

				list($iwidth, $iheight) = @getimagesize($_FILES['uploadfile']['tmp_name']);

		    	if (isset($_POST['keep_ratio']))
				{
					$image_per = 0;
					if ($iwidth > $width)
					{
						$image_per = floor(($width * 100) / $iwidth);
					}
					if (floor(($iheight * $image_per)/100) > $height)
					{
						$image_per = floor(($height * 100) / $iheight);
					}
					$width = ($iwidth*$image_per) / 100;
					$height = ($iheight*$image_per) / 100;
				}

				// set image
				$image = $bn_prefix.$id.".swf";
				$banner = array('image'=>$image, 'width'=>$width,'height'=>$height);
				$esynBanner->update($banner, "`id`='".$id."'");

				if (!move_uploaded_file($_FILES['uploadfile']['tmp_name'],$fname))
				{
					$error = true;
					$msg = $esynI18N['upload_correct_permission'];
				}
				else
				{
					chmod($fname, 0755);
				}
			} // user just wants to resize existing media
			elseif (!empty($one_banner['image']) && file_exists($ads.IA_DS.basename($one_banner['image']))  && !is_uploaded_file($_FILES['uploadfile']['tmp_name']))
			{
				if ($type == 'local')
				{
					$fname = $ads.IA_DS.basename($one_banner['image']);

					if ($width == 0 || $height == 0)
					{
						$width  = $one_banner['width'];
						$height = $one_banner['height'];
					}
					list($iwidth, $iheight) = @getimagesize($fname);

					$type = substr($one_banner['image'], -3) == 'swf' ? 'flash' : 'local';

					if ('flash' == $type)
					{
        				if (isset($_POST['keep_ratio']))
        				{
        					$image_per = 0;
        					if ($iwidth > $width)
        					{
        						$image_per = floor(($width * 100) / $iwidth);
        					}
        					if (floor(($iheight * $image_per)/100) > $height)
        					{
        						$image_per = floor(($height * 100) / $iheight);
        					}
        					$width = ($iwidth*$image_per) / 100;
        					$height = ($iheight*$image_per) / 100;
        				}
					}else{
					    if (isset($_POST['keep_ratio']) and isset($_POST['fit']))
        				{
        				    $imageResizeOption = 1003;
        				}
        				elseif (isset($_POST['keep_ratio']) and !isset($_POST['fit']))
        				{
        				    $imageResizeOption = 1001;
        				}
        				else
        				{
        				    $imageResizeOption = 1002;
        				}
        				$esynAdmin->loadClass('Image');
        				if ($iwidth != $width || $iheight != $height)
    					{
    					    $fname = $ads.IA_DS.$one_banner['image'];
        					$rimg = new esynImage();
        					$aFile['tmp_name'] = $fname;
        					$rimg->processImage($aFile, $fname, $width, $height, $imageResizeOption);
    					}
    					chmod($fname, 0755);
    					list($width, $height) = @getimagesize($fname);
					}

					// change width and height of the image
					$banner = array('id'=>$id,'width'=>$width,'height'=>$height, 'type'=>$type);

					$esynBanner->update($banner,"`id`='".$id."'");
				}
				else
				{
					// if type of the banner is changed then delete
					// validate it... (remove all the / and \ characters)
					$one_banner['image'] = preg_replace("#[/\\\]#","",$one_banner['image']);
					if (!empty($one_banner['image']) && file_exists($ads.IA_DS.$one_banner['image']))
					{
						unlink($ads.IA_DS.$one_banner['image']);
					}
				}
			}
			else
			{
				if ($type!='flash' && $type!='local')
				{
					// if type of the banner is changed then delete
					// validate it once more... (remove all the / and \ characters)
					$one_banner['image'] = preg_replace("#[/\\\]#", "", $one_banner['image']);
					if (!empty($one_banner['image']) && file_exists($ads.IA_DS.$one_banner['image']))
					{
						unlink($ads.IA_DS.$one_banner['image']);
					}
				}
			}

			$msg[] = $esynI18N['changes_saved'];
			// reload data..
			$one_banner = $esynBanner->row("*", "`id` = '{$id}'");
		}
		else
		{
			$banner = array(
				'categories'=> $_POST['categories'],
				'title'=>$title,
				'alt'=>$alt,
				'url'=>$url,
				'image'=>$imageUrl,
				'recursive'=>$recursive,
				'type'=>$type,
				'content'=>$content,
				'planetext_content'=>$planetext_content,
				'position'=>$position,
				'height'=>$height,
				'width'=>$width,
				'target'=>$target,
				"status" => "$status",
				'no_follow'=>$no_follow
			);
			$lastid = $esynBanner->insert($banner);
			if ($esynBanner->exists("`status` = 'active'"))
			{
				$esynConfig->setConfig("banners_exist", "1", true);
			}
			else
			{
				$esynConfig->setConfig("banners_exist", "", true);
			}
			//clear_cache();
			$msg[] = $esynI18N['banner_added'];

			if ($imageOk)
			{
				$fname = $ads.IA_DS.$bn_prefix.$lastid.".".$imgtypes[$_FILES['uploadfile']['type']];
				list($iwidth,$iheight) = getimagesize($_FILES['uploadfile']['tmp_name']);

			    if (isset($_POST['keep_ratio']) and isset($_POST['fit']))
				{
				    $imageResizeOption = 1003;
				}
				elseif (isset($_POST['keep_ratio']) and !isset($_POST['fit']))
				{
				    $imageResizeOption = 1001;
				}
				else
				{
				    $imageResizeOption = 1002;
				}

				if ($width == 0 || $height == 0)
				{
					$width = $iwidth;
					$height = $iheight;
				}

				// set image
				$image = $bn_prefix.$lastid.".".$imgtypes[$_FILES['uploadfile']['type']];

				if (!move_uploaded_file($_FILES['uploadfile']['tmp_name'], $fname))
				{
					$error = true;
					$msg = $esynI18N['upload_correct_permission'];
				}
				else
				{
					//if needs to be resized
					if ($iwidth != $width || $iheight != $height)
					{
						$esynAdmin->loadClass('Image');
						$rimg = new esynImage();
    					$aFile['tmp_name'] = $fname;
    					$rimg->processImage($aFile, $fname, $width, $height, $imageResizeOption);
					}
					chmod($fname, 0755);
				}
				$banner = array(
					'image' => $image,
					'width' => $width,
					'height' => $height
				);

				$banner['id'] = $lastid;
				$esynBanner->update($banner,"`id` = '{$lastid}'");
				list($width, $height) = @getimagesize($fname);
			}
			elseif ($flashOk)
			{
				$fname = $ads.IA_DS.$bn_prefix.$lastid.".swf";
				list($iwidth,$iheight) = getimagesize($_FILES['uploadfile']['tmp_name']);

			    if (isset($_POST['keep_ratio']))
				{
					$image_per = 0;
					if ($iwidth > $width)
					{
						$image_per = floor(($width * 100) / $iwidth);
					}
					if (floor(($iheight * $image_per)/100) > $height)
					{
						$image_per = floor(($height * 100) / $iheight);
					}
					$width = ($iwidth*$image_per) / 100;
					$height = ($iheight*$image_per) / 100;
				}

				if ($width == 0 || $height == 0)
				{
					$width = $iwidth;
					$height = $iheight;
				}
				// set image
				$image = $bn_prefix.$lastid.".swf";

				$banner = array(
					'image'=>$image,
				   	'width'=>$width,
					'height'=>$height
				);

				$esynBanner->update($banner,"`id`='{$lastid}'");

				if (!move_uploaded_file($_FILES['uploadfile']['tmp_name'],$fname))
				{
					$error = true;
					$msg = $esynI18N['upload_correct_permission'];
				}
				else
				{
					chmod($fname, 0755);
				}
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
	$json = new Services_JSON();

	if ('get' == $_GET['action'])
	{
		$start = (int)$_GET['start'];
		$limit = (int)$_GET['limit'];

		$dir = isset($_GET['dir']) ? $_GET['dir'] : '';
		$sort = isset($_GET['sort']) ? 'ORDER BY `'.$_GET['sort'].'` '.$dir.' ' : 'ORDER BY `added` DESC ';

		$out = array('data' => '', 'total' => 0);

		$esynAdmin->setTable("banners");

		$out['total'] = $esynAdmin->one("COUNT(*)");
		$sql = 'SELECT `bl`.`id` `block_id`, `bn`.* FROM `'.$esynAdmin->mPrefix.'banners` `bn` ';
		$sql .= 'LEFT JOIN `'.$esynAdmin->mPrefix.'blocks` `bl` ';
		$sql .= "ON `bn`.`position` = `bl`.`position` AND `bl`.`plugin` = 'banners' ";
		$sql .= $sort;
		$sql .= "LIMIT  $start, $limit";
		$out['data'] = $esynAdmin->getAll($sql);

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
	$json = new Services_JSON();

	if ('remove' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => true);

		$banner = $_POST['ids'];

		if (!is_array($banner) || empty($banner))
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}
		else
		{
			$banner = array_map(array('esynSanitize', 'sql'), $banner);
			$out['error'] = false;
		}

		if (!$out['error'])
		{
			if (is_array($banner))
			{
				foreach($banner as $new)
				{
					$ids[] = (int)$new;
					$esynBanner->delete((int)$new);
				}

			//	$where = "`id` IN ('".join("','", $ids)."')";
			}
			else
			{
				$id = (int)$new;
				$esynBanner->delete($id);
			//	$where = "`id` = '{$id}'";
			}

		/*	$esynAdmin->setTable("banners");
			$esynAdmin->delete($where);
			$esynAdmin->resetTable();*/

			$out['msg'] = (count($banner) > 1) ? $esynI18N['banners'] : $esynI18N['banner'];
			$out['msg'] .= ' '.$esynI18N['deleted'];

			$out['error'] = false;
		}
	}

	if ('update' == $_POST['action'])
	{
		$out = array('msg' => 'Unknown error', 'error' => true);

		$field = $_POST['field'];
		$value = $_POST['value'];

		if (is_array($_POST['ids']))
		{
			$banner = $_POST['ids'];
		}
		elseif (!empty($accounts))
		{
			$banner[] = $_POST['ids'];
		}
		else
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}

		if (!empty($field) && !empty($value) && !empty($banner))
		{
			foreach($banner as $new)
			{
				$ids[] = (int)$new;
			}

			$where = "`id` IN ('".join("','", $ids)."')";

			$esynAdmin->setTable("banners");
			$esynAdmin->update(array($field => $value), $where);
			$esynAdmin->resetTable();

			$out['msg'] = 'Changes saved';
			$out['error'] = false;
		}
		else
		{
			$out['msg'] = 'Wrong params';
			$out['error'] = true;
		}
	}

	echo esynUtil::jsonEncode($out);
	exit;
}
/*
 * ACTIONS
 */

$gNoBc = false;

$gTitle = $esynI18N['manage_banners'];

$gBc[1]['title'] = $esynI18N['manage_banners'];
$gBc[1]['url'] = 'controller.php?plugin=banners';

if (isset($_GET['do']))
{
	if (('add' == $_GET['do']) || ('edit' == $_GET['do']))
	{
		$gBc[1]['title'] = $esynI18N['manage_banners'];
		$gBc[1]['url'] = 'controller.php?plugin=banners';

		$gBc[2]['title'] = ('edit' == $_GET['do']) ? $esynI18N['edit_banner'] : $esynI18N['add_banner'];
		$gTitle = $gBc[2]['title'];
	}
}

$actions = array(
	array("url" => "controller.php?plugin=banners&amp;do=add", "icon" => "add.png", "label" => $esynI18N['add_banner']),
	array("url" => "controller.php?plugin=banners", "icon" => "view.png", "label" => $esynI18N['view']),
	array("url" => "controller.php?plugin=banners&amp;file=banner_plans", "icon" => "plans.png", "label" => $esynI18N['manage_banner_plans'])
);

require_once(IA_ADMIN_HOME.'view.php');


if (isset($_GET['do']))
{
	if ('edit' == $_GET['do'])
	{
		//$id = (int)$_GET['id'];

		$esynAdmin->setTable("banners");
		$banner = $esynAdmin->row("*", "`id` = '{$id}'");
		$esynAdmin->resetTable();
		$esynAdmin->setTable("banners_categories");
		$cats = $esynAdmin->all("`category_id`", "`banner_id` = '{$id}'");
		$esynAdmin->resetTable();

		if (!empty($cats))
		{
			foreach($cats as $category)
			{
				$categories[] = $category['category_id'];
			}
			$selected_categories = implode("|", $categories);

			$esynSmarty->assign('selected_categories', $selected_categories);
		}

		$esynSmarty->assign('one_banner', $banner);
	}

	if ('add' == $_GET['do'])
	{
		if (!is_writable(IA_HOME."uploads"))
		{

		}
		$esynSmarty->assign('one_banner', array('type' => "", 'position' => "", 'no_follow' => "0", 'target' => "", 'status' => ""));
	}

	$esynSmarty->assign('targets', $targets);
	$esynSmarty->assign('types', $types);
	$esynSmarty->assign('positions', $positions);
	$esynSmarty->assign('statuses', $statuses);
}


$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');
