<p>{lang key='listings_found'} {$counts}</p>

{if $listingsbydate}
	{include file='ia-listings.tpl' listings=$listingsbydate}
{/if}