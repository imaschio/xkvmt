<?php

define("IA_REALM", "result");

include IA_INCLUDES . "view.inc.php";

$eSyndiCat->factory("Listing", "Account", "Layout");

$eSyndiCat->loadPluginClass("AdditionalOptions", 'additional_options', "esyn");
$options = new esynAdditionalOptions();
$removed = $options->deleteListing($_GET['id'], $esynAccountInfo['id']);

if ($removed == 1)
{
	$action_message = $esynI18N['listing_removed'];
}
else
{
	$action_message = $esynI18N['error'];
}

$title = $esynI18N['remove_listing'];

esynBreadcrumb::add($title);

$esynSmarty->assign('action_message', $action_message);
$esynSmarty->assign('title', $title);
$esynSmarty->display(IA_PLUGIN_TEMPLATE.'result.tpl');