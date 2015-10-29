<div class="page-description"><a href="{$smarty.const.IA_URL}">{lang key='click_here'}</a> {lang key='thank_text'}</div>

{if 'member' == $smarty.get.contact || 'listing' == $smarty.get.contact}
	<a href="{print_listing_url listing=$listing}">{lang key='click_here'}</a> {lang key='get_back_to'}
{/if}