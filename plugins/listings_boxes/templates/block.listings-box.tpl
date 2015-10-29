{if $box_listings.top || $box_listings.random}
	<ul class="nav nav-tabs" id="listingsTabs">
		<li class="tab_top active"><a href="#tab-pane_listingsTop" data-toggle="tab">{lang key='listings_box_top'}</a></li>
		<li class="tab_random"><a href="#tab-pane_listingsRandom" data-toggle="tab">{lang key='listings_box_random'}</a></li>
	</ul>

	<div class="tab-content" id="listingsTabsContent">
		<div class="tab-pane active" id="tab-pane_listingsTop">
			<div class="ia-wrap">
				{foreach from=$box_listings.top item=top_listing}
					<div class="ia-item list">
						{if $top_listing.rank}
							{section name=star loop=$top_listing.rank}{print_img fl='star.png' full=true}{/section}
						{/if}
						<a href="{print_listing_url listing=$top_listing}">{$top_listing.title}</a>
						<br>
						<span class="text-small"><i class="icon-time icon-gray"></i> {$top_listing.date|date_format:$config.date_format}</span>
						<span class="text-small"><i class="icon-folder-open icon-gray"></i> {$top_listing.category_title}</span>
						<p class="text-small muted">{$top_listing.description|truncate:100:'...'}</p>
					</div>
				{/foreach}
			</div>
		</div>
		<div class="tab-pane" id="tab-pane_listingsRandom">
			<div class="ia-wrap">
				{foreach from=$box_listings.random item=random_listing}
					<div class="ia-item list">
						<a href="{print_listing_url listing=$random_listing}" {if $config.new_window}target="_blank"{/if}>{$random_listing.title}</a> &mdash; <span class="muted">{$random_listing.category_title}</span>
					</div>
				{/foreach}
			</div>
		</div>
	</div>
{/if}