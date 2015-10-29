{ia_hooker name='tplFrontLoginAfterHeader'}

<form action="{$smarty.const.IA_URL}login.php" method="post" class="ia-form">
	<label for="username">{lang key='username'}</label>
	<input type="text" class="span3" tabindex="4" name="username" value="{if isset($smarty.post.username) && !empty($smarty.post.username)}{$smarty.post.username|escape:'html'}{/if}">

	<label for="password">{lang key='password'}</label>
	<input type="password" class="span3" tabindex="5" name="password" value="">

	<label for="rememberme" class="checkbox">
		<input type="checkbox" tabindex="3" name="rememberme" value="1" id="rememberme" {if isset($smarty.post.rememberme) && $smarty.post.rememberme == '1'}checked="checked"{/if}> {lang key='rememberme'}
	</label>

	<div class="actions">
		<input type="submit" tabindex="6" name="login" value="{lang key='login'}" class="btn btn-primary">
		<a href="{$smarty.const.IA_URL}forgot.php">{lang key='forgot'}</a>
	</div>
</form>

<p>{lang key='register_account'} <a href="{$smarty.const.IA_URL}register.php" rel="nofollow">{lang key='register'}</a></p>

{ia_hooker name='loginBeforeFooter'}