{ia_hooker name='beforeListingDisplay'}

{assign options array_intersect(array_keys($visual_options), explode(',', $listing.visual_options))}

<li class="span2">
	<div class="ia-item tile{if $listing.featured} featured{/if} {$listing.status}{if in_array('add_border', $options)} ia-visual-option--border{/if}" id="listing-{$listing.id}">
		{if $listing.partner}
			<span class="ia-badge partner">{lang key='partner'}</span>
		{/if}
		{if $listing.featured}
			<span class="ia-badge featured">{lang key='featured'}</span>
		{/if}
		{if $listing.sponsored}
			<span class="ia-badge sponsored">{lang key='sponsored'}</span>
		{/if}
		{if 'approval' == $listing.status}
			<span class="ia-badge approval"></span>
		{/if}

		{if in_array('add_badge', $options)}
			<div class="pull-right ia-visual-option--badge">
				<img src="{$visual_options.add_badge}" alt="Badge">
			</div>
		{/if}

		<div class="tile-body">
			{if $listing.featured || $listing.sponsored || $listing.partner}
				{include file='thumbshots.tpl' listing=$listing tile=true}
			{/if}

			<div class="description{if !$listing.featured && !$listing.sponsored && !$listing.partner} regular{/if}">
				<h4>
					{if isset($listing.crossed) && $listing.crossed == '1'}
						<i class="icon-random icon-gray"></i> 
					{/if}
					<a href="{print_listing_url listing=$listing}" {if !$config.forward_to_listing_details && $config.new_window}target="_blank"{/if} {if $config.external_no_follow} rel="nofollow"{/if}class="js-count" id="l_{$listing.id}" data-id="{$listing.id}" data-item="listings">{$listing.title}</a>
				</h4>
				{$listing.description|strip_tags|truncate:100:'...'}
				<div class="tile-info">
					{if 'index_browse' != $smarty.const.IA_REALM}
						<span class="category" title="{$listing.category_title}"><i class="icon-folder-open icon-white"></i> <a href="{print_category_url cat=$listing fprefix='category'}">{$listing.category_title}</a></span>
					{else}
						<span class="date" title="{lang key='listing_added'}"><i class="icon-calendar icon-white"></i> {$listing.date|date_format:$config.date_format}</span>
					{/if}
				</div>

				{ia_hooker name='listingDisplayPanelLinks'}
			</div>	
			{if isset($listing.interval) && (1 == $listing.interval)}
				<span class="label label-important">{lang key='new'}</span>
			{/if}

			{ia_hooker name='listingDisplayFieldsArea'}
		</div>

		{ia_hooker name='listingDisplayBeforeStats'}
	</div>
</li>