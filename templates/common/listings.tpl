{if $listings}
	{include file='ia-listings.tpl' listings=$listings}
{else}
	<div class="alert alert-info">
	{if 'favorites' == $view}
		{lang key='no_favorites'}
	{else}
		{lang key='no_listings'}
	{/if}
	</div>
{/if}