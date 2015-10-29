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

define('IA_REALM', "suggest_listing");

$id     = isset($_GET['id'])    && ctype_digit($_GET['id'])    ? $_GET['id']    : false;
$planId = isset($_POST['plan']) && ctype_digit($_POST['plan']) ? $_POST['plan'] : 0;
$banner_pos    = isset($_GET['banner_pos']) ? $_GET['banner_pos'] : false;

$addit = array();

$imgtypes = array(
	"image/gif" => "gif",
	"image/jpeg" => "jpg",
	"image/pjpeg" => "jpg",
	"image/png" => "png"
);

/** defines tab name for this page **/
$GLOBALS['currentTab'] = 'suggest-banner';

// requires common header file
require_once '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'header.php';

$category_id = 0;

$eSyndiCat->factory("Category", "Plan");

$category = $esynCategory->row("*", "id=".$category_id);
$cid = $category['id'];

$category['path'] = $esynConfig->getConfig('use_html_path') ? $category['path'].".html" : $category['path']."/";

require_once IA_INCLUDES . 'view.inc.php';

$ads = IA_HOME."uploads";
$esynSmarty->caching = false;
$esynSmarty->assign('category', $category);


$esynSmarty->assign('id', $id);

$types = array(
	"local"		=> "Local (image or flash)",
	"remote"	=> "Remote",
	"text"		=> "Plain Text",
	"html"		=> "HTML or JS",
);
$esynSmarty->assign('types', $types);

$targets = array(
	"_blank"	=>	"New window",
	"_self"		=>	"Current window",
	"_parent"	=>	"Parent frame",
	"_top"		=>	"Top frame"
);

$statuses = array(
	"inactive"	=>	"Inactive",
	"active"	=>	"Active"
);

$esynSmarty->assign('banner_pos', $banner_pos);
$esynSmarty->assign('statuses', $statuses);
$esynSmarty->assign('targets', $targets);

$root_disable = (0 == $category['id']) ? $category['locked'] : $esynCategory->one("`locked`", "`id`='0'");
$esynSmarty->assign('rootLocked', $root_disable);

$eSyndiCat->factory("Layout");

$eSyndiCat->loadPluginClass("Banner", "banners", "esyn");
$esynBanner = new esynBanner();

/* START MOD // vbalakleiskii */
$plans = $esynBanner->getPlan($banner_pos);
$esynSmarty->assign('plans', $plans);
/* END MOD // vbalakleiskii */

if ($id)
{
    $one_banner = $esynBanner->one('*', "`id` = '$id'");
    $esynSmarty->assign('one_banner', $one_banner);
}else {
    //$one_banner = array("type"=>"", "image"=>"", "title"=>"", "alt"=>"", "content"=>"", "planetext_content"=>"", "no_follow"=>"0", "width"=>"", "height"=>"", "url"=>"", "target"=>"", "recursive"=>"", "status"=>"", "id"=>"");
}

if (isset($_POST['save']))
{
    if (!defined('IA_NOUTF'))
	{
		require_once(IA_CLASSES.'esynUtf8.php');

		esynUtf8::loadUTF8Core();
		esynUtf8::loadUTF8Util('ascii', 'validation', 'bad', 'utf8_to_ascii');
	}
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
	$url		= $_POST['url'];
	$imageUrl	= $_POST['image'];
	$target		= $_POST['targetframe'];

	$no_follow  = (int)isset($_POST['no_follow']);
	$status		= (isset($_POST['status']) && array_key_exists($_POST['status'], $statuses)) ? $_POST['status'] : 'inactive';

    if (!$planId)
    {
        $error = true;
        $msg[] = $esynI18N['banner_plan_not_selected'];
    }

	if (!$title)
	{
		$error = true;
		$msg[] = $esynI18N['banner_title_is_empty'];
	}

	if (isset($_POST['type']) && array_key_exists($_POST['type'], $types))
	{
		$type = $_POST['type'];
	}else{
		$type = 'local';
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
		else
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

	if (isset($esynAccountInfo) && !empty($esynAccountInfo))
	{
		$banner_email = $esynAccountInfo['email'];
	}
	elseif (isset($_POST['email']) && (empty($_POST['email']) || !esynValidator::isEmail($_POST['email'])))
	{
		$error = true;
		$msg = $esynI18N['banner_email_incorrect'];
	}else{
	    $banner_email = $_POST['email'];
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
				'position'=> $banner_pos,
				'height'=> $height,
				'width'=> $width,
				'target'=> $target,
				"status" => "inactive",
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

				$eSyndiCat->loadClass('Image');

				if (!move_uploaded_file($_FILES['uploadfile']['tmp_name'],$fname))
				{
					$error = true;
					$msg = $esynI18N['upload_correct_permission'];
				}
				else
				{
					//if needs to be resized
					if ($iwidth != $width || $iheight != $height)
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
        				$eSyndiCat->loadClass('Image');
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
		    $account_id = $esynAccountInfo['id'] ? $esynAccountInfo['id'] : 0;
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
				'position'=>$banner_pos,
				'height'=>$height,
				'width'=>$width,
				'target'=>$target,
				"status" => "inactive",
				'no_follow'=>$no_follow,
				"account_id" => $account_id,
			    'email' => $banner_email
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
						$eSyndiCat->loadClass('Image');
						$rimg = new esynImage();
    					$aFile['tmp_name'] = $fname;
    					$rimg->processImage($aFile, $fname, $width, $height, $imageResizeOption);
					}
					chmod($fname, 0755);
				}

				list($width, $height) = @getimagesize($fname);
				$banner = array(
					'image' => $image,
					'width' => $width,
					'height' => $height
				);

				$esynBanner->update($banner,"`id` = '{$lastid}'");
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

			$planId = (int)$_POST['plan']; // mod vbalakleiskii

			$sql  = "SELECT banner_plans.* FROM `{$esynBanner->mPrefix}banner_plans` AS banner_plans ";
			$sql .= "LEFT JOIN `{$esynBanner->mPrefix}banner_plan_blocks` AS banner_plan_blocks";
			$sql .= " ON banner_plans.`id` = {$planId} WHERE banner_plan_blocks.`block_pos` = '$banner_pos'"; // mod vbalakleiskii

			$plan = $esynBanner->getRow($sql);

            $item['id'] = $lastid;
            $item['item'] = 'banner';
            $item['plugin'] = IA_CURRENT_PLUGIN;
            $item['method'] = 'postPayment';
			$item['account_id'] = $esynAccountInfo['id'];

            if (isset($_POST['payment_gateway']) && !empty($_POST['payment_gateway']) && !empty($plan))
            {
                if ($plan['cost'] > 0)
                {
                    $sec_key = $esynPlan->preparePayment($item, $_POST['payment_gateway'], $plan['cost'], $plan);

                    $redirect_url = IA_URL . 'pre_pay.php?sec_key=' . $sec_key;

                    esynUtil::go2($redirect_url);
                }
            }
		}
	}
}

/** defines page title **/
$esynSmarty->assign('title', $esynI18N['suggest_banner']);

// breadcrumb formation
esynBreadcrumb::add($esynI18N['suggest_banner']);

$upl_writable = is_writable(IA_HOME."uploads") ? true : false;
$esynSmarty->assign('upl_writable', $upl_writable);

$tpl = 'suggest-banner.tpl';
$esynSmarty->assign('msg', $msg);
$esynSmarty->assign('error', $error);
$esynSmarty->display(IA_PLUGIN_TEMPLATE.$tpl);