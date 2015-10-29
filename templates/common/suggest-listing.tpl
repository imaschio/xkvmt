<div id="msg"></div>

{if empty($msg) || $clearData}
	<script type="text/javascript">
		$(function()
		{
			sessvars.$.clearMem();
		});
	</script>
{/if}

{if !$hide_form}
	<form action="{$smarty.const.IA_URL}suggest-listing.php{if $listing}?edit={$listing.id}{/if}" method="post" id="form_listing" class="ia-form bordered" enctype="multipart/form-data">
		{if !empty($categories_exist)}
			<div class="fieldset collapsible" id="fieldCategories">
				<h4 id="categoryTitle" class="title">{lang key='category'}: <span>{$category.title}{if $category.locked} <i class="icon-lock icon-red"></i>{/if}</span></h4>
				<div id="treeContainer" class="content collapsible-content">
					<div id="tree" class="tree"></div>
				</div>
			</div>
		{/if}

		{if $config.mcross_functionality}
			<div class="fieldset collapsible" id="fieldCrossed">
				<h4 class="title">{lang key='crossed_categories'}</h4>
				<div id="mCrossContainer" class="content collapsible-content">
					<div class="alert alert-info">
						{lang key='add_as_crossed_to_other_categories'}
					</div>
					<div id="mCrossTree"></div>
					<div id="crossedCategories">
						{if !empty($crossed_categories)}
							{foreach $crossed_categories as $crossed_category}
								<span class="label" id="mcrossedCat_{$crossed_category.id}">{$crossed_category.title}</span>
							{/foreach}
						{/if}
					</div>
				</div>
			</div>
		{/if}

		<div id="previeListingModal" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>{lang key='preview_listing'}</h3>
			</div>
			<div class="modal-body">
				<script type="text/html" id="OpList">
					{include file='listing-preview.tpl'}
				</script>
				<div id="optionsPreview"></div>
			</div>
		</div>

		<script type="text/html" id="plansList">
			<%
				_.each(plans,function(plan)
				{
					plan.description = plan.description.replace(/\r\n/g, '<br />');
					plan.description = plan.description.replace(/\n/g, '<br />');
			%>
				<div class="b-plan">
					<input type="hidden" id="planCrossedMax_<%= plan.id %>" value="<%= plan.multicross %>">
					<input type="hidden" id="planDeepLinks_<%= plan.id %>" value="<%= plan.deep_links %>">
					<input type="hidden" id="planCost_<%= plan.id %>" value="<%= plan.cost %>">
					
					<label for="p<%= plan.id %>" class="radio b-plan__title">
						<input type="radio" name="plan" value="<%= plan.id %>" id="p<%= plan.id %>">
						<b><%= plan.title %></b> &mdash; {$config.currency_symbol}<%= plan.cost %>
					</label>
				
					<div class="b-plan__description"><%= plan.description %></div>

					<%
						var checked_list = "{if isset($smarty.post.visual_options)}{implode(',', $smarty.post.visual_options)}{elseif isset($listing.visual_options)}{$listing.visual_options}{/if}";

						checked_list = checked_list.split(",");

						if (plan.options && plan.options.length > 0)
						{
					%>
						<div id="planVisualOptions_<%= plan.id %>" class="b-plan__visual-options">
							<h5>{lang key='visual_options'}</h5>
							<div class="b-plan__visual-options__radios">
								<%
									_.each(plan.options, function(option)
									{
								%>
									<label class="checkbox">
										<input type="checkbox" name="visual_options[]" data-option-price="<%= option.price %>" value="<%= option.name %>" data-option-name="<%= option.name %>"

										<%
											if (intelli.inArray(option.name, checked_list) && plan.id == $('#old_plan_id').val())
											{
												print('checked disabled');
											}
										%>
										>
										<%= intelli.lang['listing_option_' + option.name] %> - {$config.currency_symbol}<%= option.price %>
									</label>
								<%
									});
								%>
							</div>
						</div>
					<%
						}
					%>
					<div class="b-plan__footer">
						<a href="#" class="b-plan__visual-options__preview js-options-preview" style="display: none;">{lang key='preview_listing'}</a>
						<div id="planVisualOptionsTotal_<%= plan.id %>" class="b-plan__total-sum">{lang key='total'}: <b>{$config.currency_symbol}<span><%= plan.cost %></span></b></div>
					</div>
				</div>
			<%
				});
			%>
		</script>

		{if $config.sponsored_listings && $plans_exist}
			<div class="fieldset collapsible" id="fieldPlans">
				<h4 class="title">{lang key='plans'}</h4>
				<div class="content collapsible-content" id="plans"></div>
			</div>
		{/if}

		{ia_hooker name='editListingForm'}

		<div id="fields" class="fields content"></div>
		<div class="fields-loader"></div>

		<div id="deepLinksDiv" style="display:none;">
			<div class="fieldset collapsible" id="fieldDeepLinks">
				<h4 class="title">{lang key='deep_links'}</h4>
				<div id="deepLinks" class="content collapsible-content">
					{if $listing.deep_links}
						{foreach from=$listing.deep_links key=key item=deep_link}	
							<input type="hidden" name="deep_links" value="{$deep_link}|{$key}" size="15" />
						{/foreach}
					{/if}
				</div>
			</div>
		</div>

		{ia_hooker name='afterSuggestListingFields'}

		{if $config.reciprocal_check}
			<div id="reciprocal" style="display: none;">
				<div class="fieldset">
					<h4 class="title">{lang key='reciprocal'}</h4>
					<div class="content">
						<label>{$config.reciprocal_label}</label>
						<textarea class="input-block-level" rows="3" readonly="readonly">{$config.reciprocal_text|escape:'html'}</textarea>
					</div>
				</div>
			</div>
		{/if}

		<div id="gateways" style="display: none;">
			<div class="fieldset">
				<h4 class="title">{lang key='payment_gateway'}</h4>
				<div class="content">
					{ia_hooker name='paymentButtons'}
				</div>
			</div>
		</div>

		{include file='captcha.tpl'}

		<div class="actions">
			<input type="hidden" id="category_id" name="category_id" value="{$category.id}" />
			<input type="hidden" id="listing_id" name="listing_id" value="{$listing.id}" />
			<input type="hidden" id="old_plan_id" name="old_plan_id" value="{if isset($listing.plan_id) && !empty($listing.plan_id)}{$listing.plan_id}{/if}" />
			<input type="hidden" name="multi_crossed" id="multi_crossed" value="{if isset($crossed) && !empty($crossed)}{','|implode:$crossed}{/if}" />
			<input type="hidden" id="parent_path" name="parent_path" value="{if isset($parent_path)}{$parent_path}{/if}" />
			<input type="hidden" id="plan_cost" name="plan_cost" value="">

			<input type="submit" name="save_changes" value="{lang key='submit'}" id="submit_btn" class="btn btn-primary btn-large" />
		</div>
	</form>

	{ia_print_js files='js/ckeditor/ckeditor, js/frontend/suggest-listing'}
{/if}

{ia_print_js files='js/intelli/intelli.tree,js/intelli/intelli.deeplinks, js/intelli/intelli.fields, js/intelli/intelli.plans, js/intelli/intelli.textcounter, js/utils/underscore-min'}

{ia_hooker name='suggestListingBeforeFooter'}