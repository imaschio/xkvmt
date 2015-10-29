{if !empty($messages)}
	{foreach from=$messages item=message name=messages}
		{if $message.status eq 'approval' and !$esynAccountInfo}
			{if $message.sess_id eq $sess_id}
				<div class="comment-approval">{lang key='message_approval'}</div>
				<div class="posted">
					{lang key='message_author'} <strong>{$message.author|escape:"html"}</strong>
					(<a href="{$message.author_url|escape:"html"}">{$message.author_url|escape:"html"}</a>) /
					{$message.date|date_format:$config.date_format}
				</div>
				<div class="comment">
					{$message.body|escape:"html"}
				</div>
				<hr />
			{/if}
		{else}
			<div class="guestbook-comment">
				<div class="info">
					{lang key='message_author'}: <strong>{$message.author|escape:"html"}</strong> <span class="sep">/</span> <a href="{$message.author_url|escape:"html"}" target="_blank">{$message.author_url|escape:"html"}</a> <span class="sep">/</span> {$message.date|date_format:$config.date_format}
				</div>
				<div class="comment">
					{$message.body}
				</div>
			</div>
		{/if}
	{/foreach}

	{navigation aTotal=$total_messages aTemplate=$url aItemsPerPage=$config.gb_messages_per_page aNumPageItems=5 aTruncateParam=$config.use_html_path notiles=true}
{else}
	<div class="alert alert-info">{lang key='guestbook_no_messages'}</div>
{/if}

{if !$config.gb_account_submissions_only || $esynAccountInfo}
	{ia_block caption={lang key='add_message'} id='guestbook_add_message' collapsible='0'}

	{include file='notification.tpl'}

	<div id="listingComment_container">
		<div id="error" class="alert alert-danger" style="display:none;"></div>

		<form action="" method="post" id="guestbook" class="ia-form">
			{if $esynAccountInfo}
				<input type="hidden" name="g_author" value="{$esynAccountInfo.username}" />
				<input type="hidden" name="g_email" value="{$esynAccountInfo.email}" />
			{else}
				<label>{lang key='message_author'}</label>
				<input type="text" name="g_author" value="{if isset($smarty.post.author)}{$smarty.post.author|escape:"html"}{/if}">

				<label>{lang key='author_email'}</label>
				<input type="text" name="g_email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:"html"}{/if}">
			{/if}

			<label>{lang key='author_url'}</label>
			<input type="text" name="g_aurl" value="{if isset($smarty.post.aurl)}{$smarty.post.aurl|escape:"html"}{else}http://{/if}">

			<textarea name="g_message" class="ckeditor_textarea input-block-level" style="margin-top: 5px; width: 99%;" rows="6" cols="40" id="guestbook_form">{if isset($body) && !empty($body)}{$body|escape:"html"}{/if}</textarea>
			<p class="help-block text-right">{lang key='characters_left'} <input type="text" class="char-counter" id="gb_counter"></p>

			{include file='captcha.tpl'}

			<div class="actions">
				<input type="hidden" name="listing_id" value="{$listing.id}">
				<input type="submit" id="add_message" name="add_message" value="{lang key='leave_message'}" class="btn btn-success">
			</div>
		</form>
	</div>

	{/ia_block}
{/if}

{ia_add_media files="js:js/ckeditor/ckeditor, js:js/intelli/intelli.textcounter, js:js/jquery/plugins/jquery.validate, js:plugins/{$smarty.const.IA_CURRENT_PLUGIN}/js/frontend/index, css:plugins/{$smarty.const.IA_CURRENT_PLUGIN}/templates/css/style"}