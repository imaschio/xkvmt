{if $category.confirmation && !isset($smarty.cookies.{'confirm_'|cat:$category.id})}
	<div class="page-desc">{$category.confirmation_text|escape:'html'}</div>
	<div>
		<input type="button" class="btn btn-primary" name="confirm_answer" id="continue" value="{lang key='yes'}" data-category="{$category.id}"/>
		<input type="button" class="btn" name="confirm_answer" id="back" value="{lang key='no'}" />
	</div>
{else}
	{if $category.description}
		<div class="page-description">{$category.description}</div>
	{/if}

	{assign var='num_columns' value=(($category.num_cols > 0) ? $category.num_cols : $config.num_categories_cols)}
	{if $categories}
		<div class="ia-categories">
			{include file='ia-categories.tpl' categories=$categories}
		</div>
	{/if}

	<div class="js-groupWrapper" data-position="center">
		{include file='parse-blocks.tpl' pos=$blocks.center|default:null}
	</div>
	
	{ia_hooker name='indexBeforeListings'}

	{if $listings}

		{include file='ia-listings.tpl' listings=$listings sorting=true}

		{ia_print_js files='js/intelli/intelli.tree'}
	{/if}

	{if isset($related_categories) && !empty($related_categories)}
		<!-- related categories box start -->
		{ia_block caption=$lang.related_categories}
			<div class="ia-categories">
				{include file='ia-categories.tpl' categories=$related_categories}
			</div>
		{/ia_block}
	{/if}

	{if isset($neighbour_categories) && !empty($neighbour_categories)}
		<!-- neighbour categories box start -->
		{ia_block caption=$lang.neighbour_categories}
			<div class="ia-categories">
				{include file='ia-categories.tpl' categories=$neighbour_categories}
			</div>
		{/ia_block}
		<!-- neighbour categories box end -->
	{/if}
{/if}

{ia_add_js}
$(function()
{
	$('#back').click(function()
	{
		history.back(1);
		return false;
	});

	$('input[name="confirm_answer"]').click(function()
	{
		intelli.createCookie('confirm_' + $(this).data('category'), '1');
		window.location = window.location.href;
	});
});
{/ia_add_js}

{ia_hooker name='indexBeforeFooter'}