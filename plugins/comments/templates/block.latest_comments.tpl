{if !empty($latest_comments)}
	{foreach from=$latest_comments item=comment name=latest_comments}
		<div class="media ia-item one-latest-comment-{$comment.id}">
			<div class="media-body">
				<div class="date text-small">{$comment.author|escape:'html'} / {$comment.date|date_format:$config.date_format}</div>
				<div>
					{$comment.body|strip_tags|truncate:100}
				</div>
				<div class="text-small">
					{if $comment.item == 'listings'}
						<a href="{print_listing_url listing=$comment._item details=true}">{$lang.check_listing}</a>
					{elseif $comment.item == 'articles'}
						{assign var="oarticle" value=$comment._item}
						<a href="{$smarty.const.IA_URL}articles/{$oarticle.id}-{$oarticle.alias}.html">{$lang.check_article}</a>
					{elseif $comment.item == 'news'}
						{assign var="onews" value=$comment._item}
						<a href="{$config.esyn_url}news/{$onews.id}-{$onews.alias}.html">{$lang.check_news}</a>
					{else}
						<script type="text/javascript">$("one-latest-comment-{$comment.id}").remove();</script>
					{/if}
				</div>
			</div>
		</div>
		{if !$comment@last}<hr />{/if}
	{/foreach}
{/if}