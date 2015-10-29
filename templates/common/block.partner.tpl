{if isset($partner_listings)}
	{foreach $partner_listings as $partner_listing}
		<div class="partner-listing">
			<a href="{print_listing_url listing=$partner_listing}" class="js-count title" id="l_{$partner_listing.id}" data-id="{$partner_listing.id}" data-item="listings" {if $config.new_window}target="_blank"{/if}>{$partner_listing.title}</a>
		</div>
	{/foreach}
{/if}