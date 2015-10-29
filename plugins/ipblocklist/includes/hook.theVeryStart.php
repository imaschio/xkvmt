<?php
function ipblocklist_getip()
{
	$proxy_headers = array(
		'CLIENT_IP',
		'FORWARDED',
		'FORWARDED_FOR',
		'FORWARDED_FOR_IP',
		'HTTP_CLIENT_IP',
		'HTTP_FORWARDED',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED_FOR_IP',
		'HTTP_PC_REMOTE_ADDR',
		'HTTP_PROXY_CONNECTION',
		'HTTP_VIA',
		'HTTP_X_FORWARDED',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED_FOR_IP',
		'HTTP_X_IMFORWARDS',
		'HTTP_XROXY_CONNECTION',
		'VIA',
		'X_FORWARDED',
		'X_FORWARDED_FOR'
	);

	foreach($proxy_headers as $proxy_header)
	{
		if (isset($_SERVER[$proxy_header]) && preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $_SERVER[$proxy_header])) /* HEADER ist gesetzt und dies ist eine gültige IP */
		{
		    return $_SERVER[$proxy_header];
		}
		else if (stristr(',', $_SERVER[$proxy_header]) !== FALSE) /* Behandle mehrere IPs in einer Anfrage(z.B.: X-Forwarded-For: client1, proxy1, proxy2) */
		{
			$proxy_header_temp = trim(array_shift(explode(',', $_SERVER[$proxy_header]))); /* Teile in einzelne IPs, gib die letzte zurück und entferne Leerzeichen */

			if (($pos_temp = stripos($proxy_header_temp, ':')) !== FALSE)
			{
				$proxy_header_temp = substr($proxy_header_temp, 0, $pos_temp);
			} /* Entferne den Port */

			if (preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $proxy_header_temp))
			{
				return $proxy_header_temp;
			}
		}
	}

	return $_SERVER['REMOTE_ADDR'];
}

global $esynConfig;

if ($esynConfig->getConfig('enable_block_list'))
{
	$ip_addr = ipblocklist_getip();
	$ip_block_list = explode("\r\n", $esynConfig->getConfig('ip_block_list'));

	if (in_array($ip_addr, $ip_block_list))
	{
		$error = $esynConfig->getConfig('ip_block_text');

		$content = file_get_contents(IA_INCLUDES.'common'.IA_DS.'error_handler.html');

		$error_solutions = '';
		$error_description = $error;
		$error_title = '&nbsp;';

		$search = array('{title}', '{base_url}', '{error_title}', '{error_description}', '{error_solutions}', '{additional}');
		$replace = array($esynConfig->getConfig('site').' '.$esynConfig->getConfig('suffix'), IA_URL, $error_title, $error_description, $error_solutions, '');

		$content = str_replace($search, $replace, $content);
		$content = preg_replace('/<p class="solution">.*<\/p>/i', ' ', $content);

		echo $content;
		exit;
	}
}