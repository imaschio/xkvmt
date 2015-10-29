{if isset($single_news) && !empty($single_news)}
	<div class="ia-item news-body">
		<p class="date"><i class="icon-calendar"></i> {$lang.posted} {$single_news.date|date_format:$config.date_format}</p>
		{if isset($single_news.image) && !empty($single_news.image)}
			<div class="thumbnail ia-thumbnail">
				<img alt="" src="{$smarty.const.IA_URL}uploads/{$single_news.image}" />
			</div>
		{/if}

		<div class="description">{$single_news.body}</div>
	</div>
{else}
	{if $all_news}
		{navigation aTotal=$total_news aTemplate=$url aItemsPerPage=$config.news_number aNumPageItems=5 aTruncateParam=true notiles=true}
		
		{foreach from=$all_news item=onews}
			<div class="media ia-item">
				{if isset($onews.image) && !empty($onews.image)}
					<div class="pull-left">
						{print_img ups='true' fl="small_{$onews.image}" full='true' title=$one_news.title class='media-object'}
					</div>
				{/if}
				<div class="media-body">
					<h4 class="media-heading">
						<a href="{$config.esyn_url}news/{$onews.id}-{$onews.alias}.html">{$onews.title}</a>
					</h4>
					<p class="date"><i class="icon-calendar"></i> {$lang.posted}{$onews.date|date_format:$config.date_format}</p>
					<div class="description">
						{$onews.body|strip_tags:false|truncate:$config.news_max:'...'} <a href="{$config.esyn_url}news/{$onews.id}-{$onews.alias}.html">{lang key='more'}</a>
					</div>
				</div>
			</div>

			{if !$onews@last}<hr />{/if}
		{/foreach}
		
		{navigation aTotal=$total_news aTemplate=$url aItemsPerPage=$config.news_number aNumPageItems=5 aTruncateParam=true notiles=true}
		
	{else}
		<div class="alert alert-info">{lang key='no_news'}</div>
	{/if}
{/if}

{ia_print_css files='plugins/news/templates/css/style'}

{ia_add_js}
	$(function ()
	{
		var rss_div = '<li class="xml"><a href="{$smarty.const.IA_URL}feed.php?from=news"><i class="icon-rss-sign"></i></li>';
		$(".nav-social").append(rss_div);
	});
{/ia_add_js}