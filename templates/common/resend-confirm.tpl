<form action="{$smarty.const.IA_URL}resend-confirm.php" method="post" class="ia-form">
	<label for="username">{lang key='username_or_email'}</label>
	<input type="text" class="span3" name="username" value="{if isset($smarty.get.username)}{$smarty.get.username|escape:'html'}{/if}">
	<div class="actions">
		<input type="submit" name="resend" value="{lang key='submit'}" class="btn btn-primary" />
	</div>
</form>