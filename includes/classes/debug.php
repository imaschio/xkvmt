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

if (!class_exists('iaDebug')){

class iaDebug
{
	var $style = 0;
	var $endtimer = '';

	const IA_DEBUG_MAX_SQL_TIME = 0.05;
	const IA_DEBUG_MAX_HOOK_TIME = 0.1;

	function iaDebug()
	{
		$debug = 'view';
		if (isset($_COOKIE['debug']))
		{
			if ($_COOKIE['debug'] == 'closed')
			{
				$debug = 'closed';
			}
		}

		$this->endtimer = end_time_render();
		$this->debug_css();
		echo '<div class="_debug_" style="display:none;"><div id="debug" class="'.$debug.'">';
		$this->box('info');
		$this->box('timer');
		$this->box('sql');
		$this->box('hooks');
		if (isset($_SESSION['error']))
		{
			$this->box('error');
		}
		if (isset($_SESSION['debug']))
		{
			$this->box('debug', 'view');
		}
		echo '<div id="close_debug" onclick="document.getElementById(\'debug\').className=\'closed\';dSetCookie(\'debug\', \'closed\', 30*24*60*60, \'/\')">Close</div>
<div id="open_debug" onclick="document.getElementById(\'debug\').className=\'view\';dSetCookie(\'debug\', \'view\', 30*24*60*60, \'/\')">Open</div>
</div></div>';
		$this->debug_javascript();
	}

	function debug_css()
	{
		echo '
<link href="' . IA_URL . 'js/debug/hl.css" type="text/css" rel="stylesheet">
<link href="' . IA_URL . 'js/debug/debug.css" type="text/css" rel="stylesheet">
';
	}

	function debug_javascript()
	{
		echo '
<script type="text/javascript" src="' . IA_URL . 'js/debug/hl.js"></script>
<script type="text/javascript" src="' . IA_URL . 'js/debug/debug.js"></script>
';
	}

	function box($type = 'info', $debug = 'none')
	{
		if ($debug == 'none' || !in_array($debug, array('view', 'closed')))
		{
			$debug = 'closed';
			if (isset($_COOKIE['dtext_'.$type]))
			{
				if ($_COOKIE['dtext_'.$type] == 'view')
				{
					$debug = 'view';
				}
			}
		}

		ob_start();

		$func = 'debug_' . $type;
		$this->$func();

		if ($response = ob_get_clean())
		{
			echo '<div class="debug_btn" id="dbtn_'.$type.'" data-open="dtext_'.$type.'">'.ucfirst($type).'</div>
			<div class="debug_div ' . $debug . '" id="dtext_'.$type.'">
				<input type="button" class="debug_div_close" data-close="dtext_'.$type.'" value="&times;">
				<div class="debug_text">';
			echo $response;
			echo '</div></div>';
		}
	}

	function debug_info()
	{
		_v($_SERVER, '$_SERVER');
		_v($_SESSION, '$_SESSION');
		_v($_COOKIE, '$_COOKIE');
		_v($_POST, '$_POST');
		_v($_FILES, '$_FILES');
		_v($_GET, '$_GET');
	}

	function debug_debug()
	{
		foreach($_SESSION['debug'] as $key => $val)
		{
			_v($val, (!is_int($key) ? $key : ''));
		}
		unset($_SESSION['debug']);
	}

	function debug_error()
	{
		foreach($_SESSION['error'] as $key => $val)
		{
			_v($val, (!is_int($key) ? $key : ''));
		}
		unset($_SESSION['error']);
	}

	function debug_sql()
	{
		$sum = 0;
		$sql = array();
		$duplicated = array();

		echo '<table class="debug">';
		echo '<tr>';
		echo '<th>#</th>';
		echo '<th width="5%">TIME</th>';
		echo '<th>SQL Query</th>';
		echo '</tr>';
		foreach ($GLOBALS['debug_sql'] as $key => $val)
		{
			$c = 'green';
			$hash = md5($val['sql']);
			if (in_array($hash, $sql))
			{
				if (!isset($duplicated[$hash]))
				{
					$duplicated[$hash] = array(
						'sql' => $val['sql'],
						'count' => 1,
					);
				}
				else
				{
					$duplicated[$hash]['count']++;
				}
			}
			else
			{
				$sql[] = $hash;
			}

			$sum += $val['time'];

			if ($val['time'] > self::IA_DEBUG_MAX_SQL_TIME)
			{
				$c = 'red';
			}

			echo '<tr>';
			echo '<td>' . ++$key . '.</td>';
			echo '<td>';
			echo '<span style="color: '. $c . ';">' . $val['time'] . '</span>';
			echo '</td>';
			echo '<td>';
			echo '<pre><code class="sql">';
			echo $this->format_sql($val['sql']);
			echo '</pre></code>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';

		echo 'Total: ' . $sum . '<hr>';

		_v($duplicated, 'Duplicate Queries:');

		unset($GLOBALS['debug_sql']);
	}

	function debug_hooks()
	{
		$type_map = array(
			'php'		=> 'php',
			'smarty'	=> 'html',
			'html'		=> 'html',
			'plain'		=> 'html'
		);

		$sum = 0;

		if ($_SESSION['hook_debug'])
		{
			echo '<table class="debug">';
			echo '<tr>';
			echo '<th width="5%">TIME</th>';
			echo '<th>Plugin</th>';
			echo '<th width="20%">Name</th>';
			echo '</tr>';

			foreach ($_SESSION['hook_debug'] as $val)
			{
				$c = 'green';
				$t = $type_map[$val['type']];

				$sum += $val['time'];

				if ($val['time'] > self::IA_DEBUG_MAX_HOOK_TIME)
				{
					$c = 'red';
				}

				echo '<tr>';

				echo '<td>';
				echo '<span style="color: '. $c . ';">' . $val['time'] . '</span>';
				echo '</td>';

				echo '<td>';
				echo $val['plugin'];
				echo '</td>';

				echo '<td>';
				echo $val['name'];
				echo '</td>';

				echo '</tr>';

				echo '<tr>';
				echo '<td colspan="3">';
				echo '<pre><code class="' . $t . '">' . htmlentities($val['code']) . '</code></pre>';
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
			echo '<b>Total:&nbsp;' . $sum . '</b>';
		}

		unset($_SESSION['hook_debug']);
	}

	function debug_timer()
	{
		echo $this->endtimer;
	}

	function format_sql($sql)
	{
		$nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;';

		$search = array(
			'FROM',
			'SELECT',
			' AS ',
			' LIKE ',
			' ON ',
			' AND ',
			' OR ',
			'WHERE',
			'INNER JOIN',
			'RIGHT JOIN',
			'LEFT JOIN',
			'LEFT OUTER',
			' JOIN',
			'ORDER BY',
			'GROUP BY',
			'LIMIT',
			'IF',
			'IN('
		);

		$replace = array(
			"<br><b>FROM</b>",
			"<b>SELECT</b>",
			"<b>AS</b> ",
			"<b>LIKE</b> ",
			"<br>{$nbsp}{$nbsp}<b>ON</b> ",
			"<b>AND</b> ",
			"<b>OR</b> ",
			"<br><b>WHERE</b>",
			"<br>{$nbsp}{$nbsp}<b>INNER</b> <b>JOIN</b>",
			"<br>{$nbsp}{$nbsp}<b>RIGHT</b> <b>JOIN</b>",
			"<br>{$nbsp}<b>LEFT</b> <b>JOIN</b>",
			"<br>{$nbsp}{$nbsp}<b>LEFT</b> <b>OUTER</b>",
			"<br>{$nbsp}{$nbsp}<b>JOIN</b>",
			"<br><b>ORDER BY</b>",
			"<br><b>GROUP BY</b>",
			"<br><b>LIMIT</b>",
			"<br>{$nbsp}<b>IF</b>",
			"<br>{$nbsp}<b>AND</b>",
			"<br>{$nbsp}<b>OR</b>",
			"<br>{$nbsp}<b>IN</b>("
		);

		$sql = preg_replace('#--(.*?)\n#i', '--\1', $sql);
		$sql = str_replace($search, $replace, $sql);

		return $sql;
	}
}}

if (!stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0') && !stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') && !stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0'))
{
	$iaDebug = new iaDebug();
}
