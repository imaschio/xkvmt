{if $box_listings.new}
	<h2>{lang key='new_listings'}</h2>

	{include file='ia-listings.tpl' listings=$box_listings.new}
	{ia_print_js files='js/intelli/intelli.tree'}
{/if}