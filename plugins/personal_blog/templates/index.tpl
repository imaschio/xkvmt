{if isset($blog_articles) && $blog_articles}
	{navigation aTotal=$count_article aTemplate=$url aItemsPerPage=$config.blogs_number aNumPageItems=5 aTruncateParam=$config.use_html_path notiles=true}

	{foreach from=$blog_articles item='article'}
		<div class="media ia-item">
			<div class="media-body">
				<h4 class="media-heading">
					<a href="{$smarty.const.IA_URL}mod/blog/{$article.id}-{$article.alias}.html">{$article.title}</a>
				</h4>
				<p class="date"><i class="icon-calendar"></i> {$article.date|date_format:$config.date_format}</p>
				<div class="description">{$article.body|strip_tags|truncate:250} <a href="{$smarty.const.IA_URL}mod/blog/{$article.id}-{$article.alias}.html">{lang key='more'}</a></div>
			</div>
		</div>

		{if !$article@last}<hr />{/if}
	{/foreach}

	{navigation aTotal=$count_article aTemplate=$url aItemsPerPage=$config.blogs_number aNumPageItems=5 aTruncateParam=$config.use_html_path notiles=true}
{elseif isset($blog_articles) && !$blog_articles}
	<div class="alert alert-info">{lang key='no_admin_blog_entries'}</div>
{elseif isset($blog_article)}

	<div class="ia-item news-body">
		<p class="date"><i class="icon-calendar"></i> {$lang.posted} {$blog_article.date|date_format:$config.date_format}</p>
		<div class="description">{$blog_article.body}</div>
	</div>

	<div class="info-panel">
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style ">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_preferred_2"></a>
			<a class="addthis_button_preferred_3"></a>
			<a class="addthis_button_preferred_4"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-511dfb4f5bdf57f0"></script>
		<!-- AddThis Button END -->
	</div>

	{ia_block caption=$lang.comments}
		<div class="ia-comments">
			<div class="comments">
				{if $comments}
					{foreach from=$comments item='comment'}
						<div class="ia-item">
							<p class="date"><i class="icon-calendar"></i> {$comment.date|date_format:$config.date_format} <i class="icon-user"></i> {$comment.author|escape:'html'}</p>
							<div class="description">{$comment.comment|escape:'html'|nl2br}</div>
						</div>
						{if !$comment@last}<hr />{/if}
					{/foreach}
				{/if}
			</div>

			<h3>{$lang.leave_comment}</h3>
			<div class="comments-form">
				<form action="" method="post" id="comment" class="ia-form">
					{if !$esynAccountInfo}
						<div class="row-fluid">
							<div class="span6">
								<input type="text" class="input-block-level" name="author" size="25" placeholder="{lang key='blog_comment_author_name'}" />
							</div>
							<div class="span6">
								<input type="text" class="input-block-level" name="email" size="25" placeholder="{lang key='blog_comment_author_email'}" />
							</div>
						</div>
					{/if}

					<label for="comment">{lang key='comment'}</label>
					<textarea name="comment" class="input-block-level" rows="6" cols="40" id="comment_form">{$body|escape:'html'}</textarea>
					<p class="help-block text-right">{$lang.chars_min}: {$config.blogs_comment_min} / {$lang.chars_max}: {$config.blogs_comment_max}</p>

					{include file='captcha.tpl' style='fixed'}

					<div class="actions">
						<input type="submit" name="add_comment" value="{$lang.leave_comment}" class="btn btn-info" />
						<input type="hidden" name="id" value="{$blog_article.id}" />
					</div>
				</form>
			</div>
		</div>
	{/ia_block}
{/if}