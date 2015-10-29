<!-- listings box start -->
<div class="ia-listings">
	{if isset($num_display_listings)}
		{assign var='num_listings_per_page' value="{$num_display_listings}"}
	{else}
		{assign var='num_listings_per_page' value="{$config.num_index_listings}"}
	{/if}

	{navigation aTotal=$total_listings aTemplate=$url aItemsPerPage=$num_listings_per_page aNumPageItems=5 aTruncateParam=1 sorting=$sorting|default:false}

	{assign var='type' value=$smarty.cookies.listing_display_type|default:'list'}
	{if $type == 'tile'}<ul class="thumbnails">{/if}
	{foreach from=$listings item=listing name=listings}
		{include file="listing-display-{$type}.tpl"}
	{/foreach}
	{if $type == 'tile'}</ul>{/if}

	{navigation aTotal=$total_listings aTemplate=$url aItemsPerPage=$num_listings_per_page aNumPageItems=5 aTruncateParam=1 sorting=$sorting|default:false}
</div>
<!-- listings box end -->