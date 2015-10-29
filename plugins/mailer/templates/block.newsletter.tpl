<form action="{$smarty.const.IA_URL}mod/mailer/" method="post">
	<p>{lang key='newsletter_block_text'}</p>
	<label for="realname">{lang key='realname'}</label>
	<input type="text" class="input-block-level" tabindex="4" name="realname" id="realname">

	<label for="email">{lang key='email'}</label>
	<input type="text" class="input-block-level" tabindex="5" name="email" id="email">

	<div class="captcha-block" style="display:none;">
		{include file='captcha.tpl'}
	</div>

	<input type="submit" value="{lang key='subscribe'}" name="subscribe" id="subscribe" tabindex="6" class="btn btn-success">
	<input type="submit" value="{lang key='unsubscribe'}" name="unsubscribe" id="unsubscribe" tabindex="6" class="btn btn-danger">
</form>

{ia_print_js files='plugins/mailer/js/frontend/index'}