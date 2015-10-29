<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {navigation} function plugin
 *
 * Type:     function<br>
 * Name:     print_categories<br>
 * Purpose:  print navigation menu<br>
 * @author Sergey Ten
 * @param array
 * @param Smarty
 */
function smarty_function_navigation($params, &$smarty)
{
	if (!$params['aTotal'])
	{
		return false;
	}

	global $esynI18N, $eSyndiCat;

	// generate tiles actions
	$actions['tiles'] = '';
	if (!isset($params['notiles']) && !$smarty->pagination)
	{
		$actions['tiles'] .= '<div class="btn-group"><button class="js-switch-display-type btn btn-mini btn-info' . (!isset($_COOKIE['listing_display_type']) ? ' disabled' : '') . '" data-type="list"><i class="icon-th-list icon-white"></i></button>';
		$actions['tiles'] .= '<button class="js-switch-display-type btn btn-mini btn-info' . (isset($_COOKIE['listing_display_type']) ? ' disabled' : '') . '" data-type="tile"><i class="icon-th icon-white"></i></button></div>';

		$smarty->pagination = true;
	}

	// generate sorting
	$actions['sorting'] = '';
	if ($params['sorting'] && $eSyndiCat->getConfig('visitor_sorting'))
	{
		$eSyndiCat->setTable('config');
		$sortings = explode("','", trim($eSyndiCat->one("`multiple_values`", "`name` = 'listings_sorting'"), "'"));
		$eSyndiCat->resetTable();

		$sortings_text = '';
		foreach($sortings as $sort)
		{
			if ($sort != $eSyndiCat->getConfig('listings_sorting'))
			{
				$sortings_text .= '<li><a href="' . IA_CANONICAL . '?order=' . $sort . '">' . $esynI18N[$sort] . '</a></li>';
			}
		}

		$canonical = IA_CANONICAL;
		if ('ascending' == $eSyndiCat->getConfig('listings_sorting_type'))
		{
			$actions['direction'] = <<<DIRECTION
<div class="btn-group">
	<button class="btn btn-mini btn-info">{$esynI18N['ascending']}</button>
	<button class="btn btn-mini dropdown-toggle btn-info" data-toggle="dropdown">
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu pull-right">
		<li><a href="{$canonical}?order_type=descending">{$esynI18N['descending']}</a></li>
	</ul>
</div>
DIRECTION;
		}
		else
		{
			$actions['direction'] = <<<DIRECTION
<div class="btn-group">
	<button class="btn btn-mini btn-info">{$esynI18N['descending']}</button>
	<button class="btn btn-mini dropdown-toggle btn-info" data-toggle="dropdown">
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu pull-right">
		<li><a href="{$canonical}?order_type=ascending">{$esynI18N['ascending']}</a></li>
	</ul>
</div>
DIRECTION;
		}

		$current_sort = $eSyndiCat->getConfig('listings_sorting');
		$actions['sorting'] = <<<SORTING
<div class="btn-group">
	<button class="btn btn-mini btn-info">{$esynI18N[$current_sort]}</button>
	<button class="btn btn-mini btn-info dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu pull-right">
		<!-- dropdown menu links -->
		{$sortings_text}
	</ul>
</div>
SORTING;
	}

	$actions_text = '';
	if ($actions)
	{
		$actions_text .= '<div class="pull-right">' . implode(' ', $actions) . '</div>';
	}

	$out = '<div class="pagination pagination-small clearfix">';

	$eSyndiCat->startHook('phpFrontNavigationBeforeActions');

	echo $actions_text;

	$eSyndiCat->startHook('phpFrontNavigationAfterActions');

	// process pagination
	if ($params['aTotal'] > $params['aItemsPerPage'])
	{
		$regex = '/(_?{page})|([\?|&]page={page})|(index{page}\.html)/';

		$params['aTruncateParam'] = isset($params['aTruncateParam']) ? $params['aTruncateParam'] : 0;

		$num_pages = ceil($params['aTotal'] / $params['aItemsPerPage']);
		$current_page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
		$current_page = min($current_page, $num_pages);

		$left_offset = ceil($params['aNumPageItems'] / 2) - 1;
		
		$first = $current_page - $left_offset;
		$first = ($first < 1) ? 1 : $first;

		$last = $first + $params['aNumPageItems'] - 1;
		$last = min($last, $num_pages);

		$first = $last - $params['aNumPageItems'] + 1;
		$first = ($first < 1) ? 1 : $first;

		$pages = range($first, $last);

		$out .= '<ul class="pull-left">';

		foreach ($pages as $page)
		{
			if ($current_page == $page)
			{
				// $out .= "<span>{$esynI18N['page']} {$page} / {$num_pages}</span>&nbsp;";
				break;
			}
		}

		// the first and previous items menu
		if ($current_page > 1)
		{
			$prev = $current_page - 1;

			$first_url = preg_replace($regex, '', $params['aTemplate']);
			$previous_url = (1 == $prev) ? preg_replace($regex, '', $params['aTemplate']) : str_replace('{page}', $prev, $params['aTemplate']);

			$out .= "<li><a href=\"{$first_url}\" title=\"{$esynI18N['first']}\">&#171;</a></li>";
			$out .= "<li><a href=\"{$previous_url}\" title=\"{$esynI18N['previous']}\">&lt;</a></li>";
		}

		// the pages items
		foreach ($pages as $page)
		{
			if ($current_page == $page)
			{
				$out .= '<li class="active"><a href="#">'.$page.'</a></li>';
			}
			else
			{
				if(1 == $page)
				{
					$page_url = preg_replace($regex, '', $params['aTemplate']);
				}
				else
				{
					$page_url = str_replace('{page}', $page, $params['aTemplate']);
				}

				$out .= "<li><a href=\"{$page_url}\">{$page}</a></li>";
			}
		}

		// the next and last items menu
		if ($current_page < $num_pages)
		{
			$next = $current_page + 1;

			$next_url = str_replace('{page}', $next, $params['aTemplate']);
			$last_url = str_replace('{page}', $num_pages, $params['aTemplate']);

			$out .= "<li><a href=\"{$next_url}\" title=\"{$esynI18N['next']}\">&gt;</a></li>";
			$out .= "<li><a href=\"{$last_url}\" title=\"{$esynI18N['last']}\">&#187;</a></li>";
		}

		$out .= '</ul>';
	}
	$out .= '</div>';

	return $out;
}
