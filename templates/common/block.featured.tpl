{if isset($featured_listings)}
	{foreach $featured_listings as $featured_listing}
		<div class="media ia-item">

			{include file='thumbshots.tpl' listing=$featured_listing}

			<div class="media-body">
				<h5 class="media-heading"><a href="{print_listing_url listing=$featured_listing}" class="js-count title" data-item="listings" id="l_{$featured_listing.id}" data-id="{$featured_listing.id}" {if $config.new_window}target="_blank"{/if}>{$featured_listing.title}</a></h5>
				<p class="date">
					<i class="icon-calendar icon-gray"></i> {$featured_listing.date|date_format:$config.date_format}
				</p>
			</div>

			<div class="description">
				{$featured_listing.description|strip_tags|truncate:150:'...'}
			</div>
		</div>
		{if !$featured_listing@last}<hr />{/if}
	{/foreach}
{/if}