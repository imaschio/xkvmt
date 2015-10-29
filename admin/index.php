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

define('IA_REALM', "index");

require_once '.' . DIRECTORY_SEPARATOR . 'header.php';

// get action
if (isset($_GET['action']) && 'get-state' == $_GET['action'])
{
	header('Content-Type: text/javascript');

	$state = array();

	if (!empty($currentAdmin['state']))
	{
		$state = unserialize($currentAdmin['state']);
	}

	echo 'Ext.appState = ';
	if (isset($state['index_blocks']) && !empty($state['index_blocks']))
	{
		echo $state['index_blocks'];
	}
	else
	{
		echo '[]';
	}
	echo ';';
	die();
}

if (isset($_GET['info']))
{
	die(phpinfo());
}

$esynAdmin->factory('Category', 'Listing', 'Account');

// get listings by status
$statuses = array('Approval', 'Banned', 'Suspended', 'Active', 'Deleted');

foreach($statuses as $key => $status)
{
	$count = $esynListing->one("COUNT(*)", "`status` = '{$status}'");

	$listings[] = array(
		'statuses'	=> $status,
		'total'		=> $count
	);
}

if (isset($_GET['action']))
{
	if ('getlistingschart' == $_GET['action'])
	{
		$out = $listings;
	}

	if (empty($out))
	{
		$out = '';
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

if (isset($_POST['action']))
{
	if ('saveState' == $_POST['action'])
	{
		$state = unserialize($currentAdmin['state']);

		if (isset($_POST['data']) && !empty($_POST['data']))
		{
			$state['index_blocks'] = $_POST['data'];
		}

		$state = $currentAdmin['state'] = serialize($state);

		$esynAdmin->setTable('admins');
		$esynAdmin->update(array('state' => $state), "`id` = '{$currentAdmin['id']}'");
		$esynAdmin->resetTable();

		$out['error'] = false;
		$out['msg'] = 'ok';
	}

	if ('submitrequest' == $_POST['action'])
	{
		mail('support@esyndicat.com', $esynConfig->getConfig('site').' - '.$_POST['subject'], $_POST['body'], "From: ".$esynConfig->getConfig('site_email'));

		$out['msg'] = $esynI18N['request_submitted'];
	}

	if (empty($out))
	{
		$out = '';
	}

	echo esynUtil::jsonEncode($out);
	exit;
}

// delete all temporary uploaded files that was not deleted
// For example: user tried to submit a link and uploaded some file but due to some (validating) errors listing wasn't submited.
// the temporary file still exist.
$md = date("md");

if (is_dir(IA_HOME.'uploads'.IA_DS))
{
	chdir(IA_HOME.'uploads'.IA_DS);

	$temp_files = glob("*.BAK-*");

	if (!empty($temp_files))
	{
		foreach($temp_files as $fn)
		{
			if (!preg_match("/.*\.BAK-".$md."/", $fn))
			{
				unlink($fn);
			}
		}
	}

	chdir(dirname(__FILE__));
}

$gTitle = $esynI18N['admin_panel'];

$gBc[0]['title'] = $esynI18N['admin_panel'];
$gBc[0]['url'] = 'controller.php?file=index';

if (!ini_get('safe_mode'))
{
	set_time_limit(100);
}

$actions = array(
	array("url" => "controller.php?file=browse", "icon" => "browse.png", "label" => $esynI18N['browse']),
	array("url" => "controller.php?file=suggest-category&amp;id=0", "icon" => "create_category.png", "label" => $esynI18N['create_category']),
	array("url" => "controller.php?file=suggest-listing&amp;id=0", "icon" => "create_listing.png", "label" => $esynI18N['create_listing'])
);

require_once IA_ADMIN_HOME . 'view.php';

// categories box
$catstats = $esynCategory->keyvalue("`status`, count(*)",'1 GROUP BY `status`');
$rootStatus = $esynCategory->one("`status`", "`id`='0'");
$approval = isset($catstats['approval']) ? $catstats['approval'] : 0;

if ($approval > 0 && $rootStatus == 'approval')
{
	$approval--;
}

$active = isset($catstats['active']) ? $catstats['active'] : 0;

if ($active > 0 && $rootStatus == 'active')
{
	$active--;
}

$summary = $approval + $active;

$esynSmarty->assign('approval', $approval);
$esynSmarty->assign('active', $active);
$esynSmarty->assign('summary', $summary);

// listings box
$broken_listings = $esynListing->getNumBroken();

$listingstats = $esynListing->keyvalue("CASE `recip_valid` WHEN '0' THEN 'no' WHEN '1' THEN 'yes' END, count(*) as n","1 GROUP BY `recip_valid`");
$no_reciprocal_listings = isset($listingstats['no']) ? $listingstats['no'] : 0;

$reciprocal_listings = isset($listingstats['yes']) ? $listingstats['yes'] : 0;
$sponsored_listings = $esynListing->one("count(*)","`sponsored`='1'");
$featured_listings = $esynListing->one("count(*)","`featured`='1'");
$partner_listings = $esynListing->one("count(*)","`partner`='1'");

$all_listings = $esynListing->one("count(*)");

$esynSmarty->assign('broken_listings', $broken_listings);
$esynSmarty->assign('no_reciprocal_listings', $no_reciprocal_listings);
$esynSmarty->assign('reciprocal_listings', $reciprocal_listings);

if ($esynConfig->getConfig('sponsored_listings'))
{
	$esynSmarty->assign('sponsored_listings', $sponsored_listings);
}

$esynSmarty->assign('listings', $listings);
$esynSmarty->assign('featured_listings', $featured_listings);
$esynSmarty->assign('partner_listings', $partner_listings);
$esynSmarty->assign('all_listings', $all_listings);

// account box
if ($esynConfig->getConfig('accounts') && $currentAdmin['super'])
{
	$stats = $esynAccount->keyvalue("`status`, count(id)", '1=1 GROUP BY `status`');

	$approval_accounts = !empty($stats['approval']) ? $stats['approval'] : 0;
	$active_accounts = !empty($stats['active']) ? $stats['active'] : 0;
	$unconfirmed_accounts = !empty($stats['unconfirmed']) ? $stats['unconfirmed'] : 0;

	$all_accounts = $approval_accounts + $active_accounts + $unconfirmed_accounts;

	$esynSmarty->assign('approval_accounts', $approval_accounts);
	$esynSmarty->assign('active_accounts', $active_accounts);
	$esynSmarty->assign('unconfirmed_accounts', $unconfirmed_accounts);
	$esynSmarty->assign('all_accounts', $all_accounts);
}

// twitter widget
if ($esynConfig->getConfig('display_twitter'))
{
	$response = esynUtil::getPageContent('http://tools.intelliants.com/timeline/');
	empty($response) || $esynSmarty->assign('timeline', esynUtil::jsonDecode($response));
}

if ($esynConfig->getConfig('display_changelog') && is_file(IA_HOME . 'changelog.txt'))
{
	$index = 0;
	$class = 'undefined';
	$changelog = array();
	$changelog_titles = array();
	$lines = file(IA_HOME . 'changelog.txt');

	foreach ($lines as $line_num => $line)
	{
		$line = trim($line);
		if ($line)
		{
			if ($line[0] == '>')
			{
				$index++;
				$changelog[$index] = array(
					'title' => trim($line, '<> '),
					'added' => '',
					'modified' => '',
					'bugfixes' => '',
					'other' => '',
				);
				$changelog_titles[trim($line, '<> ')] = $index;
			}
			elseif ($index > 0)
			{
				switch ($line[0])
				{
					case '+':
						$class = 'added';
						break;
					case '-':
						$class = 'bugfixes';
						break;
					case '*':
						$class = 'modified';
						break;
					default:
						$class = 'other';
						break;
				}

				$issue = preg_replace('/#(\d+)/', '<a href="http://dev.esyndicat.com/issues/$1" target="_blank">#$1</a>', ltrim($line, '+-* '));

				$changelog[$index][$class] .= '<li>' . $issue . '</li>';
			}
		}
	}

	unset($changelog[0]);
	ksort($changelog_titles);

	$changelog_titles = array_reverse($changelog_titles);
	$esynSmarty->assign('changelog_titles', $changelog_titles);
	$esynSmarty->assign('changelog', $changelog);
}

$esynAdmin->startHook('adminIndexBeforeDisplay');

$esynSmarty->display('index.tpl');
