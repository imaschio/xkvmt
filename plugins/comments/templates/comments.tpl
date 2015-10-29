<div id="comments_container" class="ia-comments">
	<div class="comments">
		{if $comments}
			<h3>{lang key='comments'}</h3>
			{foreach from=$comments item=comment name=comments}
				<div class="ia-item">
					<p class="date"><i class="icon-calendar"></i> {$comment.date|date_format:$config.date_format} <i class="icon-user"></i> {$comment.author|escape:'html'}</p>
					{if $config.comments_rating}
						<p>{section name=star loop=$comment.rating}<img src="plugins/comments/templates/img/gold.png" alt="" />{/section}</p>
					{/if}
					<div class="description">{$comment.body}</div>
				</div>
				{if !$comment@last}<hr />{/if}
			{/foreach}
		{/if}
	</div>

	<div id="error" class="alert alert-danger" style="display:none;"></div>

	<h3>{lang key='leave_comment'}</h3>
	<div class="comments-form">
		{assign var=comments_allow_submission value="comments_allow_"|cat:$item.item|cat:"_submission"}
		{assign var=comments_accounts value="comments_"|cat:$item.item|cat:"_accounts"}
		{assign var=_comments_submission_disabled value=$item.item|cat:"_comments_submission_disabled"}
		{assign var=error_comment_logged value=$item.item|cat:"_error_comment_logged"}

		{if !$config.$comments_allow_submission}
			<div class="alert alert-info">{$lang.$_comments_submission_disabled}</div>
		{elseif !$config.$comments_accounts && !$esynAccountInfo}
			<div class="alert alert-info">{$lang.$error_comment_logged}</div>
		{else}
			{if isset($msg)}
				{if !$error}
					<script type="text/javascript">
						sessvars.$.clearMem();
					</script>
				{/if}
			{/if}
			<form action="" method="post" id="comment" class="ia-form">
				{if $esynAccountInfo}
					<input type="hidden" name="author" value="{$esynAccountInfo.username}" />
					<input type="hidden" name="email" value="{$esynAccountInfo.email}" />
				{else}
					<div class="row-fluid">
						<div class="span6">
							<input type="text" class="input-block-level" value="{if isset($smarty.post.author)}{$smarty.post.author|escape:'html'}{/if}" name="author" size="25" placeholder="{$lang.comment_author}" />
						</div>
						<div class="span6">
							<input type="text" class="input-block-level" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'html'}{/if}" name="email" size="25" placeholder="{$lang.author_email}" />
						</div>
					</div>
				{/if}

				{if $config.comments_rating}
					<div id="comment-rating" class="clearfix" style="margin-bottom: 12px;"></div>
				{/if}

				<label for="comment">{lang key='comment'}</label>
				<textarea name="comment" class="input-block-level ckeditor_textarea" rows="6" cols="40" id="comment_form">{if isset($body) && !empty($body)}{$body}{/if}</textarea>
				<p class="help-block text-right">{$lang.characters_left}: <input type="text" class="char-counter" id="comment_counter" /></p>

				{include file='captcha.tpl' style='fixed'}

				<div class="actions">
					<input type="hidden" name="item_id" value="{$item.id}" />
					<input type="hidden" name="item_name" value="{$item.item}" />
					<input type="submit" id="add_comment" name="add_comment" value="{$lang.leave_comment}" class="btn btn-info"/>
				</div>
			</form>
			{ia_add_media files='js:js/ckeditor/ckeditor, js:js/intelli/intelli.textcounter, js:js/jquery/plugins/jquery.validate, js:plugins/comments/js/frontend/comment-rating, js:plugins/comments/js/frontend/comments, css:plugins/comments/templates/css/style'}
		{/if}
	</div>
</div>