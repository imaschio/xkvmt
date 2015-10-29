<div id="notification" style="display: none;">
	<div class="alert alert-error"></div>
</div>
{if $is_yours}
	{$its_your_listing}
{else}
	<h3>1. {lang key='meta_validation'}</h3>
	<p>{$text}</p>

	<h3>2. {lang key='file_validation'}</h3>
	<p>{$text1}</p>

	<div class="actions">
		<input type="hidden" name="code" value="{$owner_code}" id="code" />
		<input type="button" tabindex="1" name="check" value="{lang key='validate_listing_owner'}" class="btn btn-primary" id="check-code-owner" data-listingid="{$id}"/>
	</div>
	
	{ia_print_js files='plugins/claimlisting/js/frontend/index'}
{/if}