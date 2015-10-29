{if $categories}
	<div class="row-fluid cats">
		{foreach $categories as $category}
			{assign var=class_names value=array('span12', 'span6', 'span4', 'span3')}

			<div class="{$class_names[$num_columns - 1]}">
				<div class="cat-wrap">
					{if $config.categories_icon_display}
						{if $category.icon}
							<img src="{$category.icon}" alt="{$category.title}" style="height: {$config.categories_icon_height}px; width: {$config.categories_icon_width}px;">
						{elseif $config.default_categories_icon}
							<img src="{$config.default_categories_icon}" alt="{$category.title}" style="height: {$config.categories_icon_height}px; width: {$config.categories_icon_width}px;">
						{else}
							<i class="icon-folder-open icon-blue"></i>
						{/if}
					{/if}

					{if $category.crossed}@&nbsp;{/if}<a href="{print_category_url cat=$category}" {if isset($category.no_follow) && $category.no_follow}rel="nofollow"{/if}>{$category.category_title}</a>
					{if $config.num_listings_display}
						&mdash; {$category.num_all_listings}
					{/if}

					{if $config.subcats_display && $category.subcategories}
						<div class="subcat-wrap">
							{foreach $category.subcategories as $subcategory}
								<a href="{print_category_url cat=$subcategory}" {if isset($category.no_follow) && $category.no_follow}rel="nofollow"{/if}>{$subcategory.title}</a>{if !$subcategory@last}, {/if}
							{/foreach}
						</div>
					{/if}
				</div>
			</div>

			{if $category@iteration % $num_columns == 0}
				</div>
				<div class="row-fluid cats">
			{/if}
		{/foreach}
	</div>
{/if}