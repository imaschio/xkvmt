{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if (isset($smarty.get.do) && $smarty.get.do == 'add') || (isset($smarty.get.do) && $smarty.get.do == 'edit' && isset($plan) && !empty($plan))}
	{include file='box-header.tpl' title=$gTitle}

	<form action="controller.php?file=plans&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
	{preventCsrf}
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	{if $langs|@count > 1}
		<tr>
			<td width="200"><strong>{$esynI18N.language}:</strong></td>
			<td>
				<select name="lang">
					{foreach from=$langs key=code item=lang}
						<option value="{$code}" {if (isset($smarty.post.lang) && $smarty.post.lang == $code) || (isset($plan.lang) && $plan.lang == $code)}selected="selected"{elseif $config.lang == $code}selected="selected"{/if}>{$lang}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	{/if}
	<tr>
		<td class="tip-header" id="tip-header-plan_type" width="200"><strong>{lang key="plan_type"}:</strong></td>
		<td>
			<select name="item">
				{foreach from=$valid_items item=item}
					<option value="{$item}" {if isset($smarty.post.item) && $smarty.post.item == $item}selected="selected"{elseif isset($plan.item) && $plan.item == $item}selected="selected"{elseif isset($smarty.get.item) && $smarty.get.item == $item}selected="selected"{/if}>{$item|capitalize}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-show_on_pages" valign="top"><strong>{$esynI18N.show_on_pages}:</strong></td>
		<td>
			<div id="account_pages" class="plan_pages fields-pages" style="display: none;">
				<label for="ap1"><input type="checkbox" name="pages[account][]" value="suggest" id="ap1" {if isset($smarty.post.pages.account) && in_array('suggest', $smarty.post.pages.account)}checked="checked"{elseif isset($plan_pages) && in_array("suggest", $plan_pages)}checked="checked"{elseif !isset($plan) && empty($smarty.post)}checked="checked"{/if} />
					{lang key="register_account"}</label>
				<label for="ap2"><input type="checkbox" name="pages[account][]" value="edit" id="ap2" {if isset($smarty.post.pages.account) && in_array('edit', $smarty.post.pages.account)}checked="checked"{elseif isset($plan_pages) && in_array("edit", $plan_pages)}checked="checked"{elseif !isset($plan) && empty($smarty.post)}checked="checked"{/if} />
					{lang key="edit_account"}</label>
			</div>

			<div id="listing_pages" class="plan_pages fields-pages" style="display: none;">
				<label for="lp1"><input type="checkbox" name="pages[listing][]" value="suggest" id="lp1" {if isset($smarty.post.pages.listing) && in_array('suggest', $smarty.post.pages.listing)}checked="checked"{elseif isset($plan_pages) && in_array("suggest", $plan_pages)}checked="checked"{elseif !isset($plan) && empty($smarty.post)}checked="checked"{/if} />
					{lang key="suggest_listing"}</label>
				<label for="lp2"><input type="checkbox" name="pages[listing][]" value="edit" id="lp2" {if isset($smarty.post.pages.listing) && in_array('edit', $smarty.post.pages.listing)}checked="checked"{elseif isset($plan_pages) && in_array("edit", $plan_pages)}checked="checked"{elseif !isset($plan) && empty($smarty.post)}checked="checked"{/if} />
					{lang key="edit_listing"}</label>
				<label for="lp3"><input type="checkbox" name="pages[listing][]" value="upgrade" id="lp3" {if isset($smarty.post.pages.listing) && in_array('upgrade', $smarty.post.pages.listing)}checked="checked"{elseif isset($plan_pages) && in_array("upgrade", $plan_pages)}checked="checked"{elseif !isset($plan) && empty($smarty.post)}checked="checked"{/if} />
					{lang key="upgrade_listing"}</label>
			</div>
		</td>
	</tr>
	</table>
	{if $fields}
		<div id="listing_fields" style="display: none;">
			<table cellspacing="0" cellpadding="0" width="100%" class="striped">
			<tr>
				<td class="tip-header" id="tip-header-fields_for_plan" width="200" valign="top"><strong>{lang key="fields_for_plan"}:</strong></td>
				<td>
					<fieldset>
						<legend><input type="checkbox" value="Check all" id="check_all_fields">&nbsp;<label for="check_all_fields">{lang key="all_fields"}</label></legend>
						<ul>
							{foreach from=$fields item=field}
								{assign var="field_label" value="field_"|cat:$field.name}
								<li>
									<input type="checkbox" name="fields[]" value="{$field.id}" id="field_{$field.id}" {if isset($smarty.post.fields)}{if in_array($field.id, $smarty.post.fields)}checked="checked"{/if}{elseif isset($plan) && $plan.fields}{if in_array($field.id, $plan.fields)}checked="checked"{/if}{/if} />&nbsp;<label for="field_{$field.id}">{lang key=$field_label}</label>
								</li>
							{/foreach}
						</ul>
					</fieldset>
				</td>
			</tr>
			</table>
		</div>
	{/if}

	{if $visual_options}
		<div id="visual_options" style="display: none;">
			<table cellspacing="0" cellpadding="0" width="100%" class="striped">
			<tr>
				<td class="tip-header" id="tip-header-fields_for_plan" width="200" valign="top"><strong>{lang key='visual_options'}:</strong></td>
				<td>
					<fieldset>
						<legend><input type="checkbox" value="Check all" id="check_all_options">&nbsp;<label for="check_all_options">{lang key='check_all'}</label></legend>
						<ul>
							{foreach $visual_options as $option}
								{assign var="option_label" value="listing_option_{$option.name}"}
								<li>
									<label for="{$option.name}"><input type="checkbox" name="visual_options[]" value="{$option.name}" id="{$option.name}" {if isset($smarty.post.visual_options)}{if in_array($option.name, $smarty.post.visual_options)}checked="checked"{/if}{elseif isset($plan) && $plan.visual_options}{if in_array($option.name, $plan.visual_options)}checked="checked"{/if}{/if} />&nbsp;{lang key=$option_label}</label>
								</li>
							{/foreach}
						</ul>
					</fieldset>
				</td>
			</tr>
			</table>
		</div>
	{/if}

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td class="tip-header" id="tip-header-plan_title" width="200"><strong>{$esynI18N.title}:</strong></td>
		<td><input type="text" name="title" size="30" class="common" value="{if isset($smarty.post.title)}{$smarty.post.title|escape:'html'}{elseif isset($plan.title)}{$plan.title|escape:'html'}{/if}"></td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-plan_description"><strong>{$esynI18N.description}:</strong></td>
		<td><textarea name="description" cols="5" rows="4" class="common">{if isset($smarty.post.description)}{$smarty.post.description|escape:'html'}{elseif isset($plan.description)}{$plan.description|escape:'html'}{/if}</textarea></td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-plan_cost"><strong>{$esynI18N.cost}:</strong></td>
		<td><input type="text" class="common numeric" name="cost" size="30" value="{if isset($smarty.post.cost)}{$smarty.post.cost|escape:'html'}{elseif isset($plan.cost)}{$plan.cost|escape:'html'}{elseif isset($smarty.post.cost)}{$smarty.post.cost|escape:'html'}{/if}"></td>
	</tr>
	</table>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_deep_links"><strong>{$esynI18N.deep_links}:</strong></td>
			<td><input type="text" class="common numeric" name="deep_links" size="30" value="{if isset($smarty.post.deep_links)}{$smarty.post.deep_links|escape:'html'}{elseif isset($plan.deep_links)}{$plan.deep_links|escape:'html'}{/if}"></td>
		</tr>
		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_multicross"><strong>{$esynI18N.multicross}:</strong></td>
			<td><input type="text" {if !$config.mcross_functionality}disabled="disabled"{/if} class="common numeric" name="multicross" size="30" value="{if isset($smarty.post.multicross)}{$smarty.post.multicross|escape:'html'}{elseif isset($plan.multicross)}{$plan.multicross|escape:'html'}{/if}"></td>
		</tr>
		<tr>
			<td class="tip-header" id="tip-header-plan_days"><strong>{$esynI18N.days}:</strong></td>
			<td><input type="text" class="common numeric" name="period" size="30" value="{if isset($smarty.post.period)}{$smarty.post.period|escape:'html'}{elseif isset($plan.period)}{$plan.period|escape:'html'}{/if}"></td>
		</tr>
		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_expire_notif" width="200"><strong>{$esynI18N.send_expiration_email}:</strong></td>
			<td><input type="text" name="expire_notif" size="30" class="common" value="{if isset($smarty.post.expire_notif)}{$smarty.post.expire_notif|escape:'html'}{elseif isset($plan.expire_notif)}{$plan.expire_notif|escape:'html'}{/if}"></td>
		</tr>
		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_status_after_submit"><strong>{$esynI18N.status_after_submit}:</strong></td>
			<td>
				<select name="set_status">
					<option value="active" {if isset($smarty.post.set_status) && $smarty.post.set_status == 'active'}selected="selected"{elseif isset($plan.set_status) && $plan.set_status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
					<option value="approval" {if isset($smarty.post.set_status) && $smarty.post.set_status == 'approval'}selected="selected"{elseif isset($plan.set_status) && $plan.set_status == 'approval'}selected="selected"{/if}>{$esynI18N.approval}</option>
					<option value="suspended" {if isset($smarty.post.set_status) && $smarty.post.set_status == 'suspended'}selected="selected"{elseif isset($plan.set_status) && $plan.set_status == 'suspended'}selected="selected"{/if}>{$esynI18N.suspended}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tip-header" id="tip-header-plan_mark_after_submit" width="200"><strong>{$esynI18N.mark_after_submit}:</strong></td>
			<td>
				<select name="markas">
					<option value="sponsored" {if isset($smarty.post.markas) && $smarty.post.markas == 'sponsored'}selected="selected"{elseif isset($plan.mark_as) && $plan.mark_as == 'sponsored'}selected="selected"{/if}>{$esynI18N.sponsored}</option>
					<option value="featured" {if isset($smarty.post.markas) && $smarty.post.markas == 'featured'}selected="selected"{elseif isset($plan.mark_as) && $plan.mark_as == 'featured'}selected="selected"{/if}>{$esynI18N.featured}</option>
					<option value="partner" {if isset($smarty.post.markas) && $smarty.post.markas == 'partner'}selected="selected"{elseif isset($plan.mark_as) && $plan.mark_as == 'partner'}selected="selected"{/if}>{$esynI18N.partner}</option>
					<option value="regular" {if isset($smarty.post.markas) && $smarty.post.markas == 'regular'}selected="selected"{elseif isset($plan.mark_as) && $plan.mark_as == 'regular'}selected="selected"{/if}>{$esynI18N.regular}</option>
				</select>
			</td>
		</tr>
		<tr class="listing_options" style="display:none;">
			<td width="200"><strong>{$esynI18N.cron_for_expiration}:</strong></td>
			<td>
				<select name="expire_action">
					<option value="" {if isset($smarty.post.expire_action) && $smarty.post.expire_action == ''}selected="selected"{elseif isset($plan.expire_action) && $plan.expire_action == ''}selected="selected"{/if}>{$esynI18N.nothing}</option>
					<option value="remove" {if isset($smarty.post.expire_action) && $smarty.post.expire_action == 'remove'}selected="selected"{elseif isset($plan.expire_action) && $plan.expire_action == 'remove'}selected="selected"{/if}>{$esynI18N.remove}</option>
					<optgroup label="Status">
						<option value="approval" {if isset($smarty.post.expire_action) && $smarty.post.expire_action == 'approval'}selected="selected"{elseif isset($plan.expire_action) && $plan.expire_action == 'approval'}selected="selected"{/if}>{$esynI18N.approval}</option>
						<option value="banned" {if isset($smarty.post.expire_action) && $smarty.post.expire_action == 'banned'}selected="selected"{elseif isset($plan.expire_action) && $plan.expire_action == 'banned'}selected="selected"{/if}>{$esynI18N.banned}</option>
						<option value="suspended" {if isset($smarty.post.expire_action) && $smarty.post.expire_action == 'suspended'}selected="selected"{elseif isset($plan.expire_action) && $plan.expire_action == 'suspended'}selected="selected"{/if}>{$esynI18N.suspended}</option>
					</optgroup>
					<optgroup label="Type">
						<option value="regular" {if isset($smarty.post.expire_action) && $smarty.post.expire_action == 'regular'}selected="selected"{elseif isset($plan.expire_action) && $plan.expire_action == 'regular'}selected="selected"{/if}>{$esynI18N.regular}</option>
						<option value="featured" {if isset($smarty.post.expire_action) && $smarty.post.expire_action == 'featured'}selected="selected"{elseif isset($plan.expire_action) && $plan.expire_action == 'featured'}selected="selected"{/if}>{$esynI18N.featured}</option>
						<option value="partner" {if isset($smarty.post.expire_action) && $smarty.post.expire_action == 'partner'}selected="selected"{elseif isset($plan.expire_action) && $plan.expire_action == 'partner'}selected="selected"{/if}>{$esynI18N.partner}</option>
					</optgroup>
				</select>
			</td>
		</tr>

		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_category"><strong>{$esynI18N.category}:</strong></td>
			<td>
				<div id="tree"></div>
				<label><input type="checkbox" name="recursive" value="1" {if isset($smarty.post.recursive) && $smarty.post.recursive == '1'}checked="checked"{elseif isset($plan.recursive) && $plan.recursive == '1'}checked="checked"{elseif !isset($plan) && !$smarty.post}checked="checked"{/if} />&nbsp;{$esynI18N.include_subcats}</label>
			</td>
		</tr>
	</table>

	<div id="addit_account_options" style="display:none;">
		<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr>
			<td class="tip-header" width="200"><strong>{$esynI18N.num_allowed_listing}:</strong></td>
			<td>
				<span style="float: left;">
					<input type="radio" name="num_allowed_listings_type" value="-1" {if isset($plan.num_allowed_listings) && isset($smarty.get.do) && $smarty.get.do == 'edit' && $plan.num_allowed_listings == '-1'}checked="checked"{elseif isset($smarty.post.num_allowed_listings) && $smarty.post.num_allowed_listings == '-1'}checked="checked"{elseif !$smarty.post}checked="checked"{/if} id="nal0"><label for="nal0">&nbsp;{$esynI18N.do_not_limit}</label>
					<input type="radio" name="num_allowed_listings_type" value="0" {if isset($plan.num_allowed_listings) && isset($smarty.get.do) && $smarty.get.do == 'edit' && $category.num_allowed_listings == '0'}checked="checked"{elseif isset($smarty.post.num_allowed_listings_type) && $smarty.post.num_allowed_listings_type == '0'}checked="checked"{/if} id="nal1"><label for="nal1">&nbsp;{$esynI18N.use_global}</label>
					<input type="radio" name="num_allowed_listings_type" value="1" {if isset($plan.num_allowed_listings) && isset($smarty.get.do) && $smarty.get.do == 'edit' && $plan.num_allowed_listings gt 0}checked="checked"{elseif isset($smarty.post.num_allowed_listings) && $smarty.post.num_allowed_listings == '1'}checked="checked"{/if} id="nal2"/><label for="nal2">&nbsp;{$esynI18N.custom}</label>&nbsp;&nbsp;&nbsp;
				</span>
				<span id="nal" style="display:none;">
					<input class="common numeric" type="text" name="num_allowed_listings" size="5" value="{if isset($plan.num_allowed_listings)}{$plan.num_allowed_listings}{elseif isset($smarty.post.num_allowed_listings)}{$smarty.post.num_allowed_listings}{/if}">
				</span>
			</td>
		</tr>
		</table>
	</div>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">

	{ia_hooker name="plansBeforeSubmitButton"}

	<tr class="all">
		<td colspan="2">
			<input type="submit" name="save" class="common" value="{if $smarty.get.do == 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}">
		</td>
	</tr>
	</table>

	<input type="hidden" name="id" value="{if isset($plan.id)}{$plan.id}{/if}">
	<input type="hidden" name="old_name" value="{if isset($plan.name)}{$plan.name}{/if}">
	<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}">
	<input type="hidden" name="categories_parents" id="categories_parents" value="{if isset($smarty.post.categories_parents)}{$smarty.post.categories_parents}{elseif isset($plan_categories_parents)}{$plan_categories_parents}{/if}">
	<input type="hidden" name="categories" id="categories" value="{if isset($smarty.post.categories)}{$smarty.post.categories}{elseif isset($plan_categories)}{$plan_categories}{/if}">
	</form>
	{include file='box-footer.tpl'}
{else}
	<div id="box_plans" style="margin-top: 15px;"></div>
{/if}

<div style="display:none;">
	<div id="tip-content-plan_type">{lang key="tooltip_plan_type"}</div>
	<div id="tip-content-show_on_pages">{lang key="tooltip_plan_show_on_pages"}</div>
	<div id="tip-content-fields_for_plan">{lang key="tooltip_fields_for_plan"}</div>
	<div id="tip-content-plan_title">{lang key="tooltip_plan_title"}</div>
	<div id="tip-content-plan_description">{lang key="tooltip_plan_description"}</div>
	<div id="tip-content-plan_cost">{lang key="tooltip_plan_cost"}</div>
	<div id="tip-content-plan_deep_links">{lang key="tooltip_plan_deep_links"}</div>
	<div id="tip-content-plan_multicross">{lang key="tooltip_plan_multicross"}</div>
	<div id="tip-content-plan_days">{lang key="tooltip_plan_days"}</div>
	<div id="tip-content-plan_expire_notif">{lang key="tooltip_plan_expire_notif"}</div>
	<div id="tip-content-plan_mark_after_submit">{lang key="tooltip_plan_mark_after_submit"}</div>
	<div id="tip-content-plan_category">{lang key="tooltip_plan_category"}</div>
</div>

{include_file js="js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/plans"}

{ia_hooker name="plansAfterJsInclude"}

{include file='footer.tpl'}