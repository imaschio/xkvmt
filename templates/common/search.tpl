{if $showForm}
	{if $adv}
		<form action="" method="post" id="advsearch" class="ia-form">
			<div class="text-center">
				<input type="text" class="input-medium" size="22" id="searchquery_domid" value="{if isset($smarty.get.searchquery)}{$smarty.get.searchquery|escape:'html'}{/if}" name="searchquery" />
				<select name="match" class="span1-5">
					<option value="any"{if !isset($smarty.post.match)} selected="selected"{/if}>{lang key='any_word'}</option>
					<option value="exact">{lang key='exact_match'}</option>
					<option value="all">{lang key='all_words'}</option>
				</select>
				<select name="_settings[sort]" class="span1-5">
					<option value="relevance"{if !isset($smarty.post._settings)} selected="selected"{/if}>{lang key='relevance'}</option>
					<option value="date">{lang key='date'}</option>
				</select>
				<input type="submit" value="{lang key='start_search'}" class="btn btn-primary" />
			</div>

			<hr>

			<div class="row-fluid">

				{ia_hooker name='tplFrontAdvSearchForm'}

				<div class="span6">
					<div class="fieldset">
						<h4 class="title">{lang key='listings'}</h4>
						<div class="content collapsible-content">
							{foreach from=$textFields item=f key=i}
								{assign var="fname" value=$f.name}
								{assign var="name" value="field_"|cat:$fname}

								<label for="queryFilter{$i}_domid" class="checkbox">
									<input type="checkbox" id="queryFilter{$i}_domid" name="queryFilter[]" value="{$f.name}"
									{if (!isset($smarty.post.match) && ($f.name == 'title' or $f.name == 'description'))}checked="checked"{/if}>
									{if isset($lang.$name)}{lang key=$name}{else}{lang key='unknown'}{/if}
								</label>
							{/foreach}
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="fieldset">
						<h4 class="title">{lang key='categories'}</h4>
						<div class="content collapsible-content">
							<label for="FilterCat1" class="checkbox">
								<input type="checkbox" id="FilterCat1" name="queryFilterCat[]" value="title"
								{if isset($smarty.post.queryFilterCat.title) || !isset($smarty.post.searchquery)}checked="checked"{/if}>
								{lang key='title'}
							</label>
							<label for="FilterCat2" class="checkbox">
								<input type="checkbox" id="FilterCat2" name="queryFilterCat[]" value="description"
								{if isset($smarty.post.queryFilterCat.description) || !isset($smarty.post.searchquery)}checked="checked"{/if}>
								{lang key='description'}
							</label>
							<label for="FilterCat3" class="checkbox">
								<input type="checkbox" id="FilterCat3" name="queryFilterCat[]" value="meta_keywords"
								{if isset($smarty.post.queryFilterCat.meta_keywords)}checked="checked"{/if}>
								{lang key='meta_keywords'}
							</label>
							<label for="FilterCat4" class="checkbox">
								<input type="checkbox" id="FilterCat4" name="queryFilterCat[]" value="meta_description"
								{if isset($smarty.post.queryFilterCat.meta_description)}checked="checked"{/if}>
								{lang key='meta_description'}
							</label>
						</div>
					</div>
				</div>
			</div>

			{if !empty($field_groups)}
				{foreach from=$field_groups key=key item=group}
					<div class="fieldset collapsible">
						<h4 class="title">{lang key="field_group_title_{$key}"}</h4>
						<div class="content collapsible-content">
							{foreach from=$group item=field}
								{include file='search-field-type.tpl' variable=$field}
							{/foreach}
						</div>
					</div>
				{/foreach}
				<input type="hidden" name="field_groups" value="set" />
			{/if}

			<div class="actions">
				<input type="hidden" name="_settings[page]" id="pageNumber" value="{$page}" />
				<input type="submit" value="{lang key='apply_filters'}" class="btn btn-primary" />
			</div>
		</form>

		<script type="text/javascript">
			var POSTDATA = {$POST_json};
		</script>

		{ia_print_js files='js/frontend/search_adv'}
	{else}
		<form action="{$smarty.const.IA_URL}search.php" method="get" class="form-search">
			<input type="text" class="text" name="what" id="what" size="22" value="{if isset($smarty.get.what)}{$smarty.get.what|escape:'html'}{/if}" />
			<input type="submit" value="{lang key='search'}" class="btn btn-primary" />

			<div class="search-options">
				<label for="any" class="radio"><input type="radio" name="type" value="1" id="any" {if isset($smarty.get.type) && $smarty.get.type == '1'}checked="checked"{elseif !isset($smarty.get.type)}checked="checked"{/if}/> {lang key='any_word'}</label>
				<label for="all" class="radio"><input type="radio" name="type" value="2" id="all" {if isset($smarty.get.type) && $smarty.get.type == '2'}checked="checked"{/if}/> {lang key='all_words'}</label>
				<label for="exact" class="radio"><input type="radio" name="type" value="3" id="exact" {if isset($smarty.get.type) && $smarty.get.type == '3'}checked="checked"{/if}/> {lang key='exact_match'}</label>
			</div>
		</form>
	{/if}
{/if}

{if isset($pages) && !empty($pages)}
	{ia_block caption=$lang.pages id='pages_results' collapsible=true}
		<div class="pages-results media-body">
			{foreach from=$pages item=page_result}
				<div class="page-wrap">
					<div class="description"><a href="{$page_result.url}">{$page_result.title}</a> - {$page_result.body}</div>
				</div>
			{/foreach}
		</div>
	{/ia_block}
{/if}

{if isset($categories) && !empty($categories)}
	{ia_block caption=$lang.categories_found|cat:$total_categories}

		{assign var='num_columns' value=(($category.num_cols > 0) ? $category.num_cols : $config.num_categories_cols)}
		{include file='ia-categories.tpl' categories=$categories}

		{if $total_categories > $config.num_cats_for_search && !isset($smarty.get.cats) && !isset($smarty.post.cats_only)}
			{if isset($smarty.get.adv)}
				<form action="{$smarty.const.IA_URL}search.php?adv" method="post" id="adv_cat_search_form">
					{if isset($smarty.post.queryFilterCat)}
						{foreach from=$smarty.post.queryFilterCat item=filter}
							<input type="hidden" name="queryFilterCat[]" value="{$filter}" />
						{/foreach}
						<input type="hidden" name="cats_only" value="1" />
						<input type="hidden" name="searchquery" value="{$smarty.post.searchquery}"/>
						<input type="hidden" name="match" value="{$smarty.post.match}" />
						<input type="hidden" name="_settings[sort]" value="{$smarty.post._settings.sort}" />
					{/if}
				</form>

				<p class="text-right"><a href="#" id="adv_cat_search_submit" class="btn btn-mini btn-success">{lang key='more'}</a></p>
			{else}
				<p class="text-right"><a href="{$smarty.const.IA_URL}search.php?what={$smarty.get.what|escape:'html'}&cats=true" class="btn btn-mini btn-success">{lang key='more'}</a></p>
			{/if}
		{/if}
	{/ia_block}
{/if}

{ia_hooker name='tplFrontSearchBeforeListings'}

{if (isset($listings) && !empty($listings) || isset($categories) && !empty($categories)) && (isset($smarty.get.what) || isset($smarty.post.searchquery))}
	<script type="text/javascript">
		var pWhat = '{if isset($smarty.post.searchquery)}{$smarty.post.searchquery|replace:"'":""|escape:'html'}{else}{$smarty.get.what|replace:"'":""|escape:'html'}{/if}';
	</script>
{/if}

{if isset($listings) && !empty($listings)}
	{include file='ia-listings.tpl' listings=$listings}
{elseif empty($listings) && ($adv && !$showForm) || isset($smarty.get.what) and !isset($smarty.get.cats)}
	<div class="alert alert-info">{lang key='not_found_listings'}</div>
{/if}

{ia_hooker name='searchBeforeFooter'}

{ia_print_js files='js/jquery/plugins/jquery.numeric, js/frontend/search_highlight'}