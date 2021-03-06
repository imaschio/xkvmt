{ia_hooker name='viewListingAfterHeading'}

<div class="media ia-item" id="listing-{$listing.id}">
	<div class="pull-right">
		{include file='thumbshots.tpl' listing=$listing lightbox=true}

		{if $listing.rank}
			<p class="text-center">{section name=star loop=$listing.rank}{print_img fl='star.png' full=true}{/section}</p>
		{/if}

		{ia_hooker name='tplFrontViewListingAfterThumbshot'}

	</div>

	<div class="media-body">
		<dl class="dl-horizontal">
			{assign var='view_fields' value=","|explode:$config.viewlisting_fields}
			{if in_array(0, $view_fields)}
				<dt>{lang key='title'}</dt>
				<dd><a href="{$listing.url}" id="l_{$listing.id}" {if $config.new_window}target="_blank"{/if} class="js-count" data-id="{$listing.id}" {if $config.external_no_follow} rel="nofollow"{/if}data-item="listings">{$listing.title}</a></dd>
			{/if}
			{if in_array(1, $view_fields)}
				<dt>{lang key='category'}</dt>
				<dd>
					{if isset($cats_chain) && $cats_chain}
						{foreach $cats_chain as $cat}
							<a href="{print_category_url cat=$cat}">{$cat.title}</a>
							{if !$cat@last} / {/if}
						{/foreach}
					{else}
						<a href="{print_category_url cat=$category}">{$category.title}</a>
					{/if}
				</dd>
				{if isset($crossed_categories) && !empty($crossed_categories)}
					<dt>{lang key='crossed_to'}</dt>
					<dd>
						{foreach $crossed_categories as $crossed_category}
							<a href="{print_category_url cat=$crossed_category}">{$crossed_category.title}</a>
							{if !$crossed_category@last},{/if}
						{/foreach}
					</dd>
				{/if}
			{/if}
			{if $config.accounts && $listing.account_username}
				<dt>{lang key='account'}</dt>
				<dd><a href="{print_account_url account=$listing prefix='account_'}">{$listing.account_username}</a></dd>
			{/if}

			{if in_array(2, $view_fields)}
				<dt>{lang key='clicks'}</dt>
				<dd>{$listing.clicks}</dd>
			{/if}
			{if in_array(3, $view_fields)}
				<dt>{lang key='listing_added'}</dt>
				<dd>{$listing.date|date_format:$config.date_format}</dd>
			{/if}
			{if $config.pagerank && in_array(4, $view_fields)}
				<dt>{lang key='pagerank'}</dt>
				<dd>{if $listing.pagerank != '-1'}{$listing.pagerank}{else}{lang key='no_pagerank'}{/if}</dd>
			{/if}

			{ia_hooker name='viewListingAfterMainFieldsDisplay'}
		</dl>
	</div>

	{ia_hooker name='viewListingBeforeDescription'}

	<div class="description">
		{$listing.description}
	</div>

	<div class="info-panel">
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style">
			<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
			<a class="addthis_button_tweet"></a>
			<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
			<a class="addthis_counter addthis_pill_style"></a>
		</div>
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#username=xa-4c6e050a3d706b83"></script>
		<!-- AddThis Button END -->
	</div>
</div>

{include file='field-generator.tpl'}

{ia_hooker name='tplFrontViewListingBeforeDeepLinks'}

{if $listing._deep_links}
	{ia_block caption=$lang.quick_links}
	<ul class="nav nav-actions">
		{foreach from=$listing._deep_links item=t key=u}
			<li><a href="{$u|escape:'html'}">{$t|escape:'html'}</a></li>
		{/foreach}
	</ul>
	{/ia_block}
{/if}

{ia_print_js files='js/frontend/view-listing'}

{ia_hooker name='viewListingBeforeFooter'}