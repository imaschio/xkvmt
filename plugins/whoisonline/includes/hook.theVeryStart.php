<?php
global $eSyndiCat;

$eSyndiCat->setTable('whoisonline');

$ip_addr = esynUtil::getIpAddress();
$id_session = session_id();
$date_time = date("Y-m-d H:i:s");

$num = $eSyndiCat->one("COUNT(*) num", "`id_session` = '{$id_session}'");

if ($num > 0)
{
	if (isset($_COOKIE['account_id']))
	{
		$eSyndiCat->factory("Account");
		$esynAccountInfo = $GLOBALS['esynAccount']->getInfo($_COOKIE['account_id']);

		$eSyndiCat->update(array("date" => $date_time, "username" => $esynAccountInfo['username'], "status" => 'active'), "`id_session` = '{$id_session}'");
	}
	else
	{
		$eSyndiCat->update(array("date" => $date_time, "status" => 'active', "username" => ''),"`id_session` = '{$id_session}'");
	}
}
else
{
	$new_user = $eSyndiCat->one("COUNT(*)", "`id_session` = '{$id_session}'" );
	if ($new_user == 0)
	{
		if (isset($_COOKIE['account_id']))
		{
			$eSyndiCat->factory("Account");
			$esynAccountInfo = $GLOBALS['esynAccount']->getInfo($_COOKIE['account_id']);

			$eSyndiCat->insert(array("ip_addr" => $ip_addr, "id_session" => $id_session, "date" => $date_time, "username" => $esynAccountInfo['username'],  "status" => 'active'));
		}
		else
		{
			$eSyndiCat->insert(array("ip_addr" => $ip_addr, "id_session" => $id_session, "date" => $date_time, "status" => 'active', "bot" => detectBot()));
		}
	}
}
$num_rows = $eSyndiCat->all("COUNT(*)");

if ($num_rows != 0)
{
	$eSyndiCat->update(array("status" => 'expired'), "`date` < '{$date_time}' - INTERVAL 10 MINUTE");
	//$eSyndiCat->delete("`date` < '{$date_time}' - INTERVAL 1 DAY");
}

function detectBot()
{
	$crawlers = array(
		'Google'=>'Google',
		'bingbot'=>'Bing bot',
		'adidxbot'=>'Bing bot',
		'msnbot'=>'Bing bot',
		'Rambler'=>'Rambler',
		'Yahoo'=>'Yahoo',
		'AbachoBOT'=> 'AbachoBOT',
		'accoona'=> 'Accoona',
		'AcoiRobot'=> 'AcoiRobot',
		'ASPSeek'=> 'ASPSeek',
		'CrocCrawler'=> 'CrocCrawler',
		'Dumbot'=> 'Dumbot',
		'FAST-WebCrawler'=> 'FAST-WebCrawler',
		'GeonaBot'=> 'GeonaBot',
		'Gigabot'=> 'Gigabot',
		'Lycos'=> 'Lycos spider',
		'MSRBOT'=> 'MSRBOT',
		'Scooter'=> 'Altavista robot',
		'Altavista'=> 'AltaVista robot',
		'IDBot'=> 'ID-Search Bot',
		'eStyle'=> 'eStyle Bot',
		'Scrubby'=> 'Scrubby robot',
	);

	foreach ($crawlers as $bot_ua => $bot_name)
	{
		if (stristr($_SERVER['HTTP_USER_AGENT'], $bot_ua))
		{
			return($bot_name);
		}
	}

	$botre = '/
		# this is an extended regular expression. whitespace is ignored
		# unless escaped, and comments like this are allowed.

		(bot\b)| # words ending in "bot"
		(\bbot)| # words starting with "bot"
		(spider)|(spyder)|(crawl)| # parts of words common in robot UAs
		(bookmark\ search)|

		(url\ control)|(libwww) # Libraries commonly used in bots
		(http\ ole\ control)|
		(wininet)|
		(winhttp)|
		(\blwp)| # some libwwwperl user agents start with this
		(httplib)|


		# Specific bots etc. Note that we dont need anything called "bot"
		# as that will be caught by other rules above.
		(bunnyslippers)|
		(cherrypick)|
		(download)| # a lot of bots are leechers or downloaders
		(check\b)|(checker\b)| # link checkers etc
		(offline\ explorer)|
		(frontpage)|
		(getright)|
		(httrack)|
		(harvest)|
		(webzip)|
		(webstrip)|(webreap)|
		(web[\ \.]image)|
		(web\ sucker)|
		(webcop)|
		(teleport)|
		(webwhack)|
		(wget)|
		(\bzeus)|
		(archive)|
		(frontpage)| # no thanks
		(moget)|(webfetch)|
		(leech)| # No legit UA ever contains the word "leech"
		(test\ user\ agent) # For testing only
		/ix'; // i = case insensitive, x = extended.
	if (preg_match($botre, $_SERVER['HTTP_USER_AGENT']))
	{
		return 'bot';
	}
	return '';
}