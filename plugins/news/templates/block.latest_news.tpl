{if isset($news) && !empty($news)}
	{foreach from=$news item=one_news}
		<div class="media ia-item">
			{if isset($one_news.image) && !empty($one_news.image)}
				<div class="pull-left">
					{print_img ups='true' fl="small_{$one_news.image}" full='true' title=$one_news.title class='media-object'}
				</div>
			{/if}
			<div class="media-body">
				<h5 class="media-heading"><a href="{$smarty.const.IA_URL}news/{$one_news.id}-{$one_news.alias}.html" id="news_{$one_news.id}">{$one_news.title}</a></h5>
				<p class="date"><i class="icon-calendar icon-gray"></i> {$one_news.date|date_format:$config.date_format}</p>
			</div>

			<div class="description">
				{$one_news.body|strip_tags|truncate:$config.news_max:'...'}
			</div>
		</div>
	{/foreach}
	<p class="text-right"><a href="{$smarty.const.IA_URL}news.html" class="btn btn-mini btn-success">{lang key='view_all_news'}</a></p>
{/if}