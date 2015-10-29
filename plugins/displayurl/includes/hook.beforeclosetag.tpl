{if isset($listings)}
	{foreach from=$listings item=listing}
		{if not empty($listing.display_url) && $listing.display_url neq 'http://' && $config.forward_to_listing_details eq '0'}
			<input type="hidden" id="real_url_{$listing.id}" value="{$listing.real_url}" />
		{/if}
	{/foreach}
{/if}

{if isset($sponsored_listings)}
	{foreach from=$sponsored_listings item=listing}
		{if not empty($listing.display_url) && $listing.display_url neq 'http://' && $config.forward_to_listing_details eq '0'}
			<input type="hidden" id="real_url_{$listing.id}" value="{$listing.real_url}" />
		{/if}
	{/foreach}
{/if}

{if isset($featured_listings)}
	{foreach from=$featured_listings item=listing}
		{if not empty($listing.display_url) && $listing.display_url neq 'http://' && $config.forward_to_listing_details eq '0'}
			<input type="hidden" id="real_url_{$listing.id}" value="{$listing.real_url}" />
		{/if}
	{/foreach}
{/if}

{if isset($partner_listings)}
	{foreach from=$partner_listings item=listing}
		{if not empty($listing.display_url) && $listing.display_url neq 'http://' && $config.forward_to_listing_details eq '0'}
			<input type="hidden" id="real_url_{$listing.id}" value="{$listing.real_url}" />
		{/if}
	{/foreach}
{/if}

{ia_print_js files="plugins/displayurl/js/frontend/display_url"}