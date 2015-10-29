{if $listings}
	{include file='ia-listings.tpl' listings=$listings}
{else}
	<div class="alert alert-info">{lang key='no_pagerank_listings'}</div>
{/if}

{ia_hooker name='listingsBeforeFooter'}