<?php

global $eSyndiCat, $esynSmarty, $esynI18N;

$today = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
$today = date("Y-m-d H:i:s", $today);

// get statistics
$eSyndiCat->setTable('whoisonline');

$statistics['total_users'] = $eSyndiCat->one("COUNT(*)", "`status` = 'active' AND `bot` = ''");
$statistics['accounts'] = $eSyndiCat->one("COUNT(*)", "`username` != '' AND `status` = 'active'");
$statistics['usernames'] = $eSyndiCat->all("`username`", "`username` != '' AND `status` = 'active'");
$statistics['num_visits_today'] = $eSyndiCat->one("COUNT(*)","`date` >= '{$today}' AND `bot` = ''");
$statistics['num_total_visits'] = $eSyndiCat->one("COUNT(*)", "`bot` = ''");
$statistics['num_other_bots'] = $eSyndiCat->one("COUNT(*)", "`status` = 'active' AND `bot` = 'bot'");

$name_bots = $eSyndiCat->onefield("`bot`", "`status` = 'active' AND `bot` != '' AND `bot` != 'bot' GROUP BY `bot`");
$name_bots = is_array($name_bots) ? implode(", ", $name_bots) : '';
$statistics['botnames'] = (int)$num_other_bots != 0 ? (!empty($name_bots) ? $name_bots . ", " : '') . " " . $esynI18N['other_bots'] . "(" . (int)$num_other_bots . ")" : $name_bots;

$eSyndiCat->resetTable();

$esynSmarty->assign('whois_statistics', $statistics);