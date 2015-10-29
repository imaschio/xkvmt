<div class="page-description">{lang key='listing_status_description'}</div>

<form action="" method="post" class="form-inline">

	{lang key='check_link_status_url'}:
	<input type="text" class="text" name="link_url" size="35" value="{if isset($smarty.post.link_url)}{$smarty.post.link_url|escape:'html'}{else}http://{/if}" />

	<input class="btn btn-primary" type="submit" name="contact" value="{lang key='submit'}" />
</form>