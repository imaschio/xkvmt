{if isset($sponsored_listings) && $sponsored_listings}
	<div class="slider-box flexslider">
		<ul class="slides">
			{foreach $sponsored_listings as $sponsored_listing}
				<li>
					<div class="media ia-item">
						{include file='thumbshots.tpl' listing=$sponsored_listing}

						<div class="media-body">
							<h5 class="media-heading"><a href="{print_listing_url listing=$sponsored_listing}" class="js-count title" id="sponsored-l_{$sponsored_listing.id}" data-id="{$sponsored_listing.id}" data-item="listings" {if $config.new_window}target="_blank"{/if}>{$sponsored_listing.title}</a></h5>
							<p class="date">
								<i class="icon-calendar icon-gray"></i> {$sponsored_listing.date|date_format:$config.date_format}
							</p>
						</div>

						<div class="description">
							{$sponsored_listing.description|strip_tags|truncate:150:'...'}
						</div>
					</div>
				</li>
			{/foreach}
		</ul>
	</div>
{/if}