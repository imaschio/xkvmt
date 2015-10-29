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

define('IA_REALM', "categoriesmap");

$eSyndiCat->factory("Category", "Layout");

require_once IA_INCLUDES . 'view.inc.php';

$title = $esynI18N['categories_map'];

esynBreadcrumb::add($title);

$level = 0;
$tree = '';
print_sitemap(-1, $tree, $level, $out);

$esynSmarty->assignByRef('tree', $tree);

$esynSmarty->assign('title', strip_tags($title));

$esynSmarty->display(IA_PLUGIN_TEMPLATE . 'index.tpl');

function print_sitemap($aIdCategory, &$tree, $level, &$out)
{
	global $esynConfig, $eSyndiCat, $esynI18N, $esynLayout, $esynCategory;

	$internal_level = $level;

	$categories_top = $esynCategory->getAllByParent($aIdCategory,0,false);

	if(!empty($categories_top))
	{
		$level = $internal_level+1;

		foreach($categories_top as $category)
		{
			$tree .= '<li';
			$tree .= ('-1' == $category['parent_id'] ) ? ' class="open"' : '';
			$tree .='>';
			$tree .= '<i class="icon-folder-open icon-blue"></i>';
			$tree .= '<a style="text-decoration:none;" href="';
			$tree .= $esynLayout->printCategoryUrl(array('cat' => $category));
			$tree .='">'.esynSanitize::html($category['title']).'</a>';

			/* Print subcategories */
			$eSyndiCat->setTable('categories');
			$subcategories = $esynCategory->getAllByParent($category['id'], 0, false);
			$eSyndiCat->resetTable();

			if($subcategories)
			{
				$tree .= '<ul>';
				print_sitemap($category['id'], $tree, $level, $out);
				$tree .= '</ul>';
			}
		}
	}
}