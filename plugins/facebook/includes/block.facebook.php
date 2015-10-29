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

global $esynConfig;

$page_url = strpos(CURRENT_URL, '?') ? substr(CURRENT_URL, 0, strpos(CURRENT_URL, '?')) : CURRENT_URL;

if ($esynConfig->getConfig('fb_like'))
{
	$faces = $esynConfig->getConfig('fb_like_faces') ? 'true' : 'false';
	printf ('<fb:like href="%s" send="true" show_faces="%s"></fb:like>', $page_url, $faces);
}

if ($esynConfig->getConfig('fb_comments'))
{
	printf ('<fb:comments href="%s" num_posts="%d" width="%d"></fb:comments>', $page_url, $esynConfig->getConfig('fb_num_comment'), $esynConfig->getConfig('fb_comments_width'));
}