<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_function_print_listing_url($params, &$smarty)
{
	global $esynLayout, $eSyndiCat;

	$url = $esynLayout->printListingUrl($params);

	$eSyndiCat->startHook('phpPostPrintListingURL');

	return $url;
}
