{ia_hooker name='beforeListingDisplay'}

{assign options array_intersect(array_keys($visual_options), explode(',', $listing.visual_options))}

<div class="media ia-item bordered {if $listing.featured} featured{/if} {$listing.status}{if in_array('add_border', $options)} ia-visual-option--border{/if}" id="listing-{$listing.id}">
	{if $listing.featured || $listing.sponsored || $listing.partner}
		{include file='thumbshots.tpl' listing=$listing}
	{/if}

	{if in_array('add_badge', $options)}
		<div class="pull-right ia-visual-option--badge">
			<img src="{$visual_options.add_badge}" alt="Badge">
		</div>
	{/if}

	<div class="media-body">
		<h4 class="media-heading">
			{if isset($listing.crossed) && $listing.crossed == '1'}
				<i class="icon-random icon-gray"></i> 
			{/if}

			{if in_array('add_star', $options)}
				<img src="{$visual_options.add_star}" alt="Star" class="ia-visual-option--star">
			{/if}

			<a href="{print_listing_url listing=$listing}" id="l_{$listing.id}" {if $config.new_window}target="_blank"{/if}{if $config.external_no_follow} rel="nofollow"{/if}data-id="{$listing.id}" data-item="listings" style="
				{if in_array('highlight', $options)}background:{$visual_options.highlight};{/if}
				{if in_array('color_link', $options)}color:{$visual_options.color_link};{/if}
				{if in_array('link_big', $options)}font-size:{$visual_options.link_big}px;{/if}
			" class="js-count{if in_array('itali_link', $options)} ia-visual-option--italic-link{/if}">{$listing.title}</a>

			{if isset($listing.interval) && (1 == $listing.interval)}
				<span class="label label-important">{lang key='new'}</span>
			{/if}
		</h4>

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
		{if 'deleted' == $listing.status}
			<span class="ia-badge deleted"></span>
		{/if}
		{if isset($listing.rank) && !empty($listing.rank)}
			{section name=star loop=$listing.rank}{print_img fl='star.png' full=true}{/section}
		{/if}

		<div class="description
			{if in_array('desc_bold', $options)} ia-visual-option--bold-desc{/if}
			{if in_array('desc_ital', $options)} ia-visual-option--italic-desc{/if}
		">
			{$listing.description|strip_tags|truncate:200:'...'}
		</div>

		{ia_hooker name='listingDisplayFieldsArea'}
	</div>

	{ia_hooker name='listingDisplayBeforeStats'}

	<div class="panel clearfix">
		{if $listing.account_username}
			<span class="account" title="{lang key='account'}"><i class="icon-user icon-blue"></i> <a href="{$smarty.const.IA_URL}accounts/{$listing.account_username}.html">{$listing.account_username}</a></span>
		{/if}

		<span class="date" title="{lang key='listing_added'}"><i class="icon-calendar icon-blue"></i> {$listing.date|date_format:$config.date_format}</span>

		{if 'index_browse' != $smarty.const.IA_REALM}
			<span class="category" title="{lang key='category'}"><i class="icon-folder-open icon-blue"></i> <a href="{print_category_url cat=$listing fprefix='category'}">{$listing.category_title}</a></span>
		{/if}

		<span class="clicks"><i class="icon-hand-right icon-blue"></i> {lang key='clicks'}: {$listing.clicks}</span>

		{if $config.pagerank}
			<span class="rank"><i class="icon-signal icon-blue"></i> {lang key='pagerank'}:&nbsp;{if $listing.pagerank != '-1'}{$listing.pagerank}{else}{lang key='no_pagerank'}{/if}</span>
		{/if}

		{ia_hooker name='listingDisplayPanelLinks'}

		<div class="toolbar pull-right">

			{ia_hooker name='listingDisplayToolbarLinks'}

			<a href="{print_listing_url listing=$listing details=true}" class="js-count" data-id="{$listing.id}" data-item="listings" title="{lang key='view_listing'}"><i class="icon-info-sign icon-blue"></i></a>
			<a href="#" class="toolbar-toggle"><i class="icon-cog icon-blue"></i></a>

			<div class="toolbar-actions">

				{ia_hooker name='listingDisplayToolbarActions'}

				{if $esynAccountInfo.id == $listing.account_id}
					<a href="{$smarty.const.IA_URL}suggest-listing.php?edit={$listing.id}" class="js-tooltip" title="{lang key='edit_listing'}">
						<i class="icon-edit icon-white"></i>
					</a>
				{/if}

				{if $config.allow_listings_deletion && 'deleted' != $listing.status && $esynAccountInfo.id == $listing.account_id}
					<a href="#" class="js-delete js-tooltip" data-id="{$listing.id}" title="{lang key='remove_listing'}">
						<i class="icon-remove icon-white"></i>
					</a>
				{/if}

				{if $config.broken_listings_report && !($esynAccountInfo.id == $listing.account_id)}
					<a href="#" class="js-report js-tooltip" data-id="{$listing.id}" title="{lang key='report_broken_listing'}" rel="nofollow">
						<i class="icon-warning-sign icon-white"></i>
					</a>
				{/if}

				{if $esynAccountInfo && $esynAccountInfo.id != $listing.account_id}
					{if isset($listing.favorite) && !$listing.favorite}
						<a href="#" class="js-favorites js-tooltip" data-id="{$listing.id}" data-account="{$esynAccountInfo.id}" data-action="add" rel="nofollow" title="{lang key='add_to_favorites'}">
							<i class="icon-star-empty icon-white"></i>
						</a>
					{else}
						<a href="#" class="js-favorites js-tooltip" data-id="{$listing.id}" data-account="{$esynAccountInfo.id}" data-action="remove" rel="nofollow" title="{lang key='remove_from_favorites'}">
							<i class="icon-star icon-white"></i>
						</a>
					{/if}
				{/if}

				{if isset($listing.crossed) && $listing.crossed == '0' && $esynAccountInfo.id == $listing.account_id}
					<a href="#" class="js-move js-tooltip" data-id="{$listing.id}" data-category="{$category_id.id}" title="{lang key='move_listing'}"><i class="icon-move icon-white"></i></a><br />
					{if 'account_listings' == $smarty.const.IA_REALM}{lang key='category'}: <a href="{$smarty.const.IA_URL}{if $config.use_html_path}{$listing.path|cat:".html"}{else}{$listing.path}{/if}">{$listing.category_title|escape:'html'}</a>{/if}
				{/if}
			</div>
		</div>
	</div>
</div>