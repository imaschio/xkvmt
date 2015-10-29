{if $latestBlogs}
	{foreach from=$latestBlogs item=one_blog}
		<div class="media ia-item">
			<div class="media-body">
				<h5 class="media-heading"><a href="{$smarty.const.IA_URL}mod/blog/{$one_blog.id}-{$one_blog.alias}.html">{$one_blog.title}</a></h5>
				<p class="date"><i class="icon-calendar"></i>
					{$one_blog.date|date_format:$config.date_format}
				</p>
				<div class="description">
					{$one_blog.body|strip_tags|truncate:100:'...'}
				</div>
			</div>
		</div>
	{/foreach}

	<p class="text-right"><a href="{$smarty.const.IA_URL}mod/blog/" class="btn btn-mini btn-success">{lang key='view_all_blogs'}</a></p>
{/if}