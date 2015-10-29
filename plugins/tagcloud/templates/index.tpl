{if isset($tag)}
	{if $tag_listings}
		{include file='ia-listings.tpl' listings=$tag_listings}
	{else}
		<div class="alert alert-info">{lang key='tags_no_listings'}</div>
	{/if}
{elseif $all_tags}
	{foreach from=$all_tags item=tag}
		<a href="mod/tagcloud/{$tag.tag|escape:"html"|urlencode}" style="font-size: {$tag.size}%">{$tag.tag|escape:'html'}</a>{if !$tag@last}, {/if}
	{/foreach}
{else}
	<div class="alert alert-info">{lang key='tags_no_listings'}</div>
{/if}