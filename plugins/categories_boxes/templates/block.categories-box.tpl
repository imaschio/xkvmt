{if !empty($boxes_categories.top) || !empty($boxes_categories.popular)}
<ul class="nav nav-tabs" id="categoriesTabs">
	{if $config.num_top_cat > '0'}
		<li class="tab_top{if $config.num_popular_cat == '0'} active{else} active{/if}"><a href="#tab-pane_catTop" data-toggle="tab">{lang key='categories_box_top'}</a></li>
	{/if}
	{if $config.num_popular_cat > '0'}
		<li class="tab_popular{if $config.num_top_cat == '0'} active{/if}"><a href="#tab-pane_catPopular" data-toggle="tab">{lang key='categories_box_popular'}</a></li>
	{/if}
</ul>

<div class="tab-content" id="categoriesTabsContent">
	{if $config.num_top_cat > '0'}
		<div class="tab-pane{if $config.num_popular_cat == '0'} active{else} active{/if}" id="tab-pane_catTop">
			<div class="ia-wrap">
				{if !empty($boxes_categories.top)}
					<ul class="nav nav-actions">
						{foreach from=$boxes_categories.top item=top_categories}
							<li><a href="{print_category_url cat=$top_categories}"{if $top_categories.no_follow} rel="nofollow"{/if}>{$top_categories.title}</a></li>
						{/foreach}
					</ul>
				{else}
					<div class="alert alert-info">{lang key='no_categories'}</div>
				{/if}
			</div>
		</div>
	{/if}
	{if $config.num_popular_cat > '0'}
		<div class="tab-pane{if $config.num_top_cat == '0'} active{/if}" id="tab-pane_catPopular">
			<div class="ia-wrap">
				{if !empty($boxes_categories.popular)}
					<ul class="nav nav-actions">
						{foreach from=$boxes_categories.popular item=popular_category}
							<li><a href="{print_category_url cat=$popular_category}"{if $popular_category.no_follow} rel="nofollow"{/if}>{$popular_category.title} <sup title="{$lang.clicks}">{$popular_category.clicks}</sup></a></li>
						{/foreach}
					</ul>
				{else}
					<div class="alert alert-info">{lang key='no_categories'}</div>
				{/if}
			</div>
		</div>
	{/if}
</div>
{/if}