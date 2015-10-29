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

global $category, $esynConfig, $eSyndiCat, $esynSmarty, $esynI18N;

$eSyndiCat->loadPluginClass("Polls", 'polls', "esyn");
$esynPolls = new esynPolls();

$category_id = (isset($category['id']) && !empty($category['id'])) ? $category['id'] : 0;
$polls = $esynPolls->getForCategory($category_id);

if (!empty($polls))
{
	$votedPolls = false;
	$refresh = false;

	if (isset($_COOKIE['votedPolls']) && !empty($_COOKIE['votedPolls']))
	{
		$votedPolls = explode(",", $_COOKIE['votedPolls']);
	}

	foreach($polls as $k => $p)
	{
		$eSyndiCat->setTable("poll_options");
		$polls[$k]['alreadyVoted']	= false;
		$polls[$k]['options'] = $eSyndiCat->all("*", "`poll_id`='".$p['id']."'");
		$eSyndiCat->resetTable();

		if (!empty($votedPolls) && in_array($p['id'], $votedPolls))
		{
			$options  = & $polls[$k]['options'];
			$total = 0;
			foreach($options as $j=>$o)
			{
				$options[$j]['votes'] = (int)$options[$j]['votes'];
				$total+=$options[$j]['votes'];
			}

			if ($total == 0)
			{
				$i = array_search($p['id'], $votedPolls);
				unset($votedPolls[$i]);
				$refresh = true;
				continue;
			}

			$colors = array('info', 'success', 'warning', 'danger');
			$colorsNum = count($colors);

			$str = '<p class="help-block">';
			$str .= str_replace("{num}", $total, $esynI18N['total_votes']);
			$str .= "</p>";
			foreach($options as $key => $o)
			{
				$percent    = $o['votes'] > 0 ? $total/$o['votes'] : 0;
				$w          = $percent > 0 ? round(100/$percent,2) : 0;
				$colorIndex = $key % $colorsNum;
				$str .= '<div class="poll-item">
							<p class="poll-option-title">' . $o['title'] . ' <span class="poll-option-percent">' . $w . '% (' . $o['votes'] . ' ' . $esynI18N['votes'] . ')</span></p>
							<div class="progress progress-' . $colors[$colorIndex] . '">
								<div class="bar" style="width:' . $w . '%;"></div>
							</div>
				</div>';
			}

			$polls[$k]['results'] = $str;

			unset($polls[$k]['options']);
			$polls[$k]['alreadyVoted']	= true;
		}
	}

	if ($refresh)
	{
		setcookie("votedPolls", implode(",", $votedPolls), $_SERVER['REQUEST_TIME'] + 600);
	}
}
$esynSmarty->assign("polls", $polls);