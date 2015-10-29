{if $post_action eq "confirmed"}
	{lang key='newsletter_confirmed'}
{elseif $post_action eq "confirm_err"}
	{lang key='newsletter_confirm_err'}
{elseif $post_action eq "unsubscribed_confirm"}
	{lang key='newsletter_unsubscribe_confirm'}
{elseif $post_action eq "unsubscribed_confirm_err"}
	{lang key='newsletter_unsubscribe_confirm_err'}
{elseif $post_action eq "subscribed"}
	{lang key='newsletter_subscribe'}
{elseif $post_action eq "unsubscribed"}
	{lang key='newsletter_unsubscribe'}
{else}
	<form id="subscribeform" action="{$smarty.const.IA_URL}mod/mailer/" method="post">
		<div class="realname">
			<label for="index_realname">{lang key='realname'}</label>
			<input type="text" class="span3" tabindex="4" name="realname" id="index_realname" value="{if isset($smarty.post.realname)}{$smarty.post.realname}{/if}" />
		</div>
		<label for="index_email">{lang key='email'}</label>
		<input type="text" class="span3" tabindex="5" name="email" id="index_email" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" />

		{include file='captcha.tpl'}

		<div class="control-group">
			<input type="submit" value="{lang key='subscribe'}" name="subscribe" id="subscribe" tabindex="6" class="btn btn-success" />
			<input type="submit" value="{lang key='unsubscribe'}" name="unsubscribe" id="unsubscribe" tabindex="6" class="btn btn-danger" />
		</div>
	</form>
{/if}

{ia_print_js files='plugins/mailer/js/frontend/index'}