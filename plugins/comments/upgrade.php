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

global $esynAdmin,$esynConfig;

$block1['order'] = '1';
$block1['contents'] = '
{if isset($latest_comments)}
    <div class="latest-comments">
	{foreach from=$latest_comments item=comment name=latest_comments}
	    <div class="one-latest-comment-{$comment.id}">
            <div class="posted">
                {$lang.comment_author} <strong>{$comment.author|escape:"html"}</strong> / {$comment.date|date_format:$config.date_format}
            </div>
            <div class="comment">{$comment.body|truncate:100}<br /></div>
            <div style="float:right;">
                {if $comment.item eq "listings"}
                    <a href="{print_listing_url listing=$comment._item details=true}">{$lang.check_listing}</a>
                {elseif $comment.item eq "articles"}
                    {assign var="oarticle" value=$comment._item}
                    <a href="{$smarty.const.IA_URL}articles/{$oarticle.alias}-a{$oarticle.id}.html">{$lang.check_article}</a>
                {elseif $comment.item eq "news"}
                    {assign var="onews" value=$comment._item}
                    <a href="{$config.esyn_url}news/{$onews.alias}-n{$onews.id}.html">{$lang.check_news}</a>
                {else}
                    <script type="text/javascript">$("one-latest-comment-{$comment.id}").remove();</script>
                {/if}
            </div>
            <div style="clear:both;"></div>
            {if !$smarty.foreach.latest_comments.last}<hr />{/if}
        </div>
	{/foreach}
{/if}
';

$block2['order'] = '2';
$block2['contents'] = '
{if isset($comment_rating) && !empty($comment_rating)}

    <div id="comments_ratings"></div><br />

    {assign var=comments_rating_block_max value="comments_"|cat:$item.item|cat:"_rating_block_max"}
    {$lang.comment_rating}:&nbsp;<span id="ratings_text">{$comment_rating.rating|string_format:"%.2f"}&nbsp;/&nbsp;{$config.$comments_rating_block_max}&nbsp;({$comment_rating.num_votes}&nbsp;{if $comment_rating.num_votes > 1}{$lang.votes_cast}{else}{$lang.vote_cast}{/if})</span>

    <input type="hidden" id="item_id" value="{$item.id}" />
    <input type="hidden" id="item_name" value="{$item.item}" />
    <input type="hidden" id="current_comment_rating" value="{$comment_rating.rating}" />
    <input type="hidden" id="comment_voted" value="{if $comment_rating.voted}1{else}0{/if}" />

    {include_file js="plugins/comments/js/frontend/exstars, plugins/comments/js/frontend/ratings"}
{/if}
';

$esynAdmin->setTable('blocks');
$esynAdmin->update($block1, "`plugin` = 'comments' AND `contents` LIKE '%latest_comments%'");
$esynAdmin->update($block2, "`plugin` = 'comments' AND `contents` LIKE '%rating%'");
$esynAdmin->resetTable();

$esynAdmin->setTable('plugins');
$plugin_version = $esynAdmin->one("version", "`name` = 'comments'");
$esynAdmin->resetTable();

if (version_compare($plugin_version, '2.1', '<'))
{
	$esynAdmin->setTable('hooks');
	$esynAdmin->delete("`name` IN ('viewListing','viewListingBeforeFooter') AND `plugin` = 'comments'");
	$esynAdmin->resetTable();

	$esynAdmin->setTable('config');
	$esynAdmin->delete("`name` IN ('rate_period','listing_rating_block_max','listing_rating_max','listing_rating','div_rating','num_latest_comments','comment_max_chars','comment_min_chars','comments_approval','html_comments','listing_comments_accounts','allow_listing_comments_submission','div_comments') AND `plugin` = 'comments'");
	$esynAdmin->resetTable();

	$esynAdmin->query("ALTER TABLE `".$esynAdmin->mPrefix."comments` CHANGE `listing_id` `item_id` INT( 8 ) NOT null DEFAULT '0', ADD `item` VARCHAR( 50 ) NOT null DEFAULT 'listings';");
	$esynAdmin->query("ALTER TABLE `".$esynAdmin->mPrefix."votes` CHANGE `listing_id` `item_id` INT( 8 ) NOT null DEFAULT '0', ADD `item` VARCHAR( 50 ) NOT null DEFAULT 'listings';");
}

RemoveDir(IA_TMP.'admin');
RemoveDir(IA_TMP.$esynConfig->getConfig('tmpl'));

function RemoveDir($path)
{
	if (file_exists($path) && is_dir($path))
	{
		$dirHandle = opendir($path);
		while (false !== ($file = readdir($dirHandle)))
		{
			if ($file!='.' && $file!='..')
			{
				$tmpPath=$path.'/'.$file;
				chmod($tmpPath, 0777);

				if (is_dir($tmpPath))
	  			{
					RemoveDir($tmpPath);
			   	}
	  			else
	  			{
	  				if (file_exists($tmpPath))
					{
	  					unlink($tmpPath);
					}
	  			}
			}
		}
		closedir($dirHandle);

		if (file_exists($path))
		{
			rmdir($path);
		}
	}
}