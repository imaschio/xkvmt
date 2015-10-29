{if $form}
	<form action="{$smarty.const.IA_URL}forgot.php" method="post" class="ia-form form-inline">
		{lang key='email'}:
		<input type="text" class="input-large" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'html'}{/if}" size="35" />
		<input type="submit" name="restore" value="{lang key='submit'}" class="btn btn-primary" />
	</form>
{/if}

<div>{lang key='resend_confirm_email_text'}</div>