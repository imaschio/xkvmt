<div id="check_field"></div>

{if isset($msg)}
	{if !$error}
		<script type="text/javascript">
			sessvars.$.clearMem();
		</script>
	{/if}
{/if}

{if 'us' == $smarty.get.contact}
	<div class="page-description">{lang key='contact_us_page_description'}</div>
{/if}

<form action="" method="post" class="ia-form">
	<label for="fullname">{lang key='contacts_full_name'}:</label>
	<input type="text" name="fullname" value="{if $esynAccountInfo.username}{$esynAccountInfo.username}{elseif isset($smarty.post.fullname)}{$smarty.post.fullname|escape:'html'}{/if}">

	<label for="email">{lang key='contacts_email'}:</label>
	<input type="text" name="email" value="{if $esynAccountInfo.email}{$esynAccountInfo.email}{elseif isset($smarty.post.email)}{$smarty.post.email|escape:'html'}{/if}">

	<label for="subject">{lang key='contacts_subject'}:</label>
	<input type="text" name="subject" value="{if isset($smarty.post.subject)}{$smarty.post.subject|escape:'html'}{/if}">

	<label for="body">{if $smarty.get.contact == 'member'}{lang key='contacts_body'}{else}{lang key='contacts_reason'}{/if}:</label>
	<textarea name="body" rows="8" class="input-block-level">{if isset($smarty.post.body)}{$smarty.post.body|escape:'html'}{/if}</textarea>

	{include file='captcha.tpl'}

	<div class="actions">
		<input type="hidden" name="id" value="{if isset($smarty.get.id)}{$smarty.get.id}{/if}"/>
		<input type="submit" class="btn btn-primary" name="contact" value="{lang key='send'}" />
	</div>
</form>