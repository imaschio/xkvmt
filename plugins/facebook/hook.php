<?php

// we have to backup $_SERVER['REQUEST_URI'], because it's redefined in the index.php :-/
if (!defined('CURRENT_URL'))
{
	$url = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
	$url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	define('CURRENT_URL', $url);
}

if (!empty($_COOKIE['account_id']))
{
	return;
}

global $eSyndiCat;

if (!empty($_GET['code']))
{
	$token_url = "https://graph.facebook.com/oauth/access_token?"
		. "client_id=" . $eSyndiCat->mConfig['fb_app_id'] . "&redirect_uri=" . urlencode(IA_URL)
		. "&client_secret=" . $eSyndiCat->mConfig['fb_app_secret'] . "&code=" . $_GET['code'];
	$response = esynUtil::getPageContent($token_url);
    $params = null;
	parse_str($response, $params);

	$graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
	$user = esynUtil::jsonDecode(esynUtil::getPageContent($graph_url));
}

if (!empty($_GET['code']) && $user->id)
{
	$eSyndiCat->setTable('accounts');
	$acc = $eSyndiCat->row('*', "`fb_id`='{$user->id}'");
	if (!$acc)
	{
		// create new account
		$username = $user->first_name;
		$username = str_replace(' ', '.', $username);
		$similar = $eSyndiCat->onefield('username', "`username` LIKE '{$username}%'");
		$similar = $similar ? $similar : array();

		// generate username
		$i = '';
		while (in_array( $username . $i, $similar ))
		{
			$i++;
		}

		$acc = array(
			'fb_id' => $user->id,
			'username' => $username,
			'password' => md5( rand() . rand() ),
			'email' => $user->email,
			'status' => ($eSyndiCat->mConfig['accounts_autoapproval'] ? 'active' : 'approval'),
		);
		$acc['id'] = $eSyndiCat->insert($acc, array('date_reg' => 'NOW()'));
	}
	setcookie("account_id",  $acc['id']);
	setcookie("account_pwd", crypt($acc['password'], IA_SALT_STRING));
	header('Location: ' . IA_URL);
	exit;
}