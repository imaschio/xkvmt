{if isset($box_listings.related) && !empty($box_listings.related)}
	{foreach from=$box_listings.related item=related_listing}
		<div class="ia-item list">
			<a href="{print_listing_url listing=$related_listing}">{$related_listing.title}</a>
		</div>
	{/foreach}
{/if}