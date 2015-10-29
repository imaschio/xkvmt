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

define('IA_REALM', "faq");

require_once (IA_INCLUDES . 'view.inc.php');

$eSyndiCat->factory("Layout");

$eSyndiCat->setTable('faq_categories');
$faq_cats = $eSyndiCat->all("SQL_CALC_FOUND_ROWS *", "`lang` = '".IA_LANGUAGE."' OR `lang` = ''", array());
$eSyndiCat->resetTable();

$total_faq_cats = $eSyndiCat->foundRows();

$eSyndiCat->setTable('faq');
$i = 0;
if (!empty($faq_cats))
{
	foreach ($faq_cats as $faq_cat)
	{
		$faq_cats[$i]['faqs'] = $eSyndiCat->all("*", "`status`='active' AND `category` = '{$faq_cat['id']}' AND `lang` = '".IA_LANGUAGE."'");
		$i++;
	}
}
/*if (empty($faq_cats))
{
	$faq_cats[$i]['faqs'] = $eSyndiCat->all("*", "`status`='active' AND `category` = '-1'");
}*/
$eSyndiCat->resetTable();

$esynSmarty->assignByRef('total_faq_cats', $total_faq_cats);
$esynSmarty->assignByRef('faq_cats', $faq_cats);
$esynSmarty->assign("title", $esynI18N['faq']);

esynBreadcrumb::add($esynI18N['faq']);

$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'index.tpl');