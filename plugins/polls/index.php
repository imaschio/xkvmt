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

define('IA_REALM', "polls");

// requires common header file
if (isset($_POST['action']))
{
	$str = "";
	$msg = array();
	$error = false;

	$eSyndiCat->loadPluginClass("Polls", "polls", "esyn", false);
	$esynPolls = new esynPolls();

	$ip = esynUtil::getIpAddress();

	$votedPolls = array();
	if (!empty($_COOKIE['votedPolls']))
	{
		$votedPolls = explode(",", $_COOKIE['votedPolls']);
	}

	$affected = $alreadyVoted = true;

	if (!$esynPolls->checkClick($_POST['poll_id'], $ip))
	{
		$esynPolls->click($_POST['poll_id'], $ip);
		$alreadyVoted = false;

		$eSyndiCat->setTable("poll_options");
		$affected = $eSyndiCat->update(array(),"`id`='".$_POST['id']."' and `poll_id`='".$_POST['poll_id']."'", null, array("votes"=>"`votes`+1"));
		$eSyndiCat->resetTable();
	}

	if ($alreadyVoted || $affected) // exists
	{
		$eSyndiCat->setTable("poll_options");
		$options  = $eSyndiCat->all("*", "`poll_id`='".$_POST['poll_id']."' ORDER BY `votes` DESC");
		$eSyndiCat->resetTable();
		$total = 0;

		foreach($options as $k=>$o)
		{
			$options[$k]['votes'] = (int)$options[$k]['votes'];
			$total+=$options[$k]['votes'];
		}

		$colors = array('info', 'success', 'warning', 'danger');
		$colorsNum = count($colors);

		$str  = '<p class="help-block">';
		$str .= str_replace("{num}", $total, $esynI18N['total_votes']);
		$str .= '</p>';
		foreach($options as $o)
		{
			$percent = $o['votes'] > 0 ? $total/$o['votes'] : 0;
			$w = $percent > 0 ? round(100/$percent,2) : 0;
			$colorIndex = $key % $colorsNum;
			$str .= '<div class="poll-item">
						<p class="poll-option-title clearfix">' . $o['title'] . ' <span class="poll-option-percent">' . $w . '% (' . $o['votes'] . $esynI18N['votes'] . ' )</span></p>
						<div class="progress progress-' . $colors[$colorIndex] . '">
							<div class="bar" style="width:' . $w . '%;"></div>
						</div>
			</div>';
		}
		// $str .= "</table>";

		$votedPolls[] = $_POST['poll_id'];
		$votedPolls	= array_unique($votedPolls);

		// 1 hour
		setcookie("votedPolls", implode(",", $votedPolls), $_SERVER['REQUEST_TIME'] + 3600);
	}
	else
	{
		$error = true;
	}

	if ($alreadyVoted && !isset($_POST['showResults']))
	{
		$msg[] = $esynI18N['error_already_voted'];
	}

	$out['error'] = $error;
	$out['msg'] = $msg;
	$out['data'] = $str;

	echo esynUtil::jsonEncode($out);
	exit;
}