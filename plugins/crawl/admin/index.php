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

define('IA_REALM', "crawl");

$config = esynConfig::instance();

$esynAdmin->loadClass("JSON");
$esynAdmin->factory("Category", "Listing");

function detect_encoding($url) {
  // SEARCH FOR ENCODING INFO IN HTTP HEADERS START
    $httpheader = get_headers($url);
    if ($httpheader !== FALSE) {
        foreach ($httpheader as $httpheader_line) {
            $encoding_given = preg_match('/charset=([a-z0-9_-]+)/i', $httpheader_line, $encoding_found);
            if ($encoding_given) {
                if (isset($encoding_found[1])) {
                    return $encoding_found[1];
                }
            }
        }
    }
    // SEARCH FOR ENCODING INFO IN HTTP HEADERS END
    // SEARCH FOR ENCODING INFO IN XML START
    $xmlcontent = file_get_contents($url);
    if ($xmlcontent !== FALSE) {
        $encoding_given = preg_match('/encoding=([W]{1})([a-z0-9_-]+)/i', $xmlcontent, $encoding_found);
        if ($encoding_given) {
            if (isset($encoding_found[2])) {
                return $encoding_found[2];
            }
        }
    }
    // SEARCH FOR ENCODING INFO IN XML END
    return FALSE;
}
function correct_encoding($text, $source) {
  $encoding = detect_encoding($source);
  if ($encoding === FALSE) { return FALSE; }
  $text = iconv($encoding, 'UTF-8', $text);

  return $text;
}

$statuses = array('active' => 'Active', 'approval' => 'Approval', 'suspended' => 'Suspended', 'banned' => 'Banned');

if (isset($_POST['parse']))
{
	$json = new Services_JSON();
	$result = array();

	if (!empty($_POST['urls']))
	{
		$urls = explode(PHP_EOL, $_POST['urls']);
		if ($urls)
		{
			foreach($urls as $key => $url)
			{
				if ('http://' != substr($url, 0, 7))
				{
					$url = 'http://'.$url;
				}

				$url = trim($url);

				if (esynValidator::isUrl($url))
				{
					$content = esynUtil::getPageContent($url);

					if ($content)
					{
						$encoding_given = preg_match('/charset=([a-z0-9_-]+)/i', $content, $encoding_found);

						//var_dump($encoding_given, $encoding_found);
						if ($encoding_given && 'utf-8' != strtolower($encoding_found[1]))
						{
							$content = iconv($encoding_found[1], 'utf-8', $content);
						}
						elseif (isset($_POST['encoding']) && !empty($_POST['encoding']))
						{
							$content = iconv($_POST['encoding'], 'utf-8', $content);
						}

						$result[$key]['url'] = $url;

						preg_match('/<title.*?>(.*?)<\/title>/smi', $content, $match);
						$result[$key]['title'] = ($match[1]);

						preg_match('/<meta.*?name=(?:[\'\"]{0,1})description(?:[\'\"]{0,1}).*?content="(.*?)".*?(?:\/){0,1}>/smi', $content, $match);
						$result[$key]['description'] = ($match[1]);

						preg_match('/<meta.*?name=(?:[\'\"]{0,1})keywords(?:[\'\"]{0,1}).*?content="(.*?)".*?(?:\/){0,1}>/smi', $content, $match);
						$result[$key]['keywords'] = ($match[1]);
					}
				}
			}
		}
	}
}

if (isset($_GET['action']) && 'add' == $_GET['action'])
{
	$esynAdmin->setTable('listing_fields');
	$existKeywords = $esynAdmin->exists("`name` = 'keywords'");
	$esynAdmin->resetTable();

	$links = array();
	$status = (isset($_POST['status']) && array_key_exists($_POST['status'], $statuses)) ? $_POST['status'] : 'approval';

	if (isset($_POST['index']))
	{
		foreach($_POST['index'] as $key => $value)
		{
			if ('on' == $value)
			{
				$links[$key]['url'] = $_POST['urls'][$key];
				$links[$key]['domain'] = esynUtil::getDomain($_POST['urls'][$key]);
				$links[$key]['title'] = $_POST['titles'][$key];
				$links[$key]['description'] = $_POST['descriptions'][$key];
				$links[$key]['meta_keywords'] = $_POST['keywords'][$key];
				$links[$key]['meta_description'] = $_POST['descriptions'][$key];
				$pagerank = PageRank::getPageRank($_POST['urls'][$key]);
				$links[$key]['pagerank'] = $pagerank > 0 ? $pagerank : -1;
				$links[$key]['category_id'] = $_POST['category_id'];
				$links[$key]['status'] = $status;
				$links[$key]['listing_header'] = esynUtil::getListingHeader($_POST['urls'][$key]);
			}
		}
	}

	if ($links)
	{
		foreach($links as $link)
		{
			$esynListing->insert($link);
		}

		$esynCategory->adjustNumListings((int)$_POST['category_id']);

		esynMessages::setMessage($esynI18N['listings_added']);
		esynUtil::reload();
	}
}

$esynI18N['crawl'] = 'Crawler';

$gBc[0]['title'] = $esynI18N['crawl'];
$gBc[0]['url'] = 'controller.php?plugin=crawl';

$gTitle = $esynI18N['crawl'];

require_once IA_ADMIN_HOME . 'view.php';

if (isset($result) && !empty($result))
{
	$esynSmarty->assign('result', $result);
}

$esynSmarty->assign('statuses', $statuses);

$esynSmarty->display(IA_PLUGIN_TEMPLATE.'index.tpl');