{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if isset($smarty.get.do) && ($smarty.get.do eq 'add' || $smarty.get.do eq 'edit')}
	{include file="box-header.tpl" title=$gTitle}
	
	<form action="controller.php?plugin=banners&amp;file=banner_plans&amp;do={$smarty.get.do}{if $smarty.get.do eq 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
	{preventCsrf}
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.language}:</strong></td>
		<td>
			<select name="lang" {if $langs|@count eq 1}disabled="disabled"{/if}>
				{foreach from=$langs key=code item=lang}
					<option value="{$code}" {if (isset($banner_plan.lang) && $banner_plan.lang eq $code) || (isset($smarty.post.lang) && $smarty.post.lang eq $code)}selected="selected"{elseif $config.lang eq $code}selected="selected"{/if}>{$lang}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.title}:</strong></td>
		<td><input type="text" name="title" size="30" class="common" value="{if isset($banner_plan.title)}{$banner_plan.title|escape:"html"}{elseif isset($smarty.post.title)}{$smarty.post.title|escape:"html"}{/if}" /></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.description}:</strong></td>
		<td><textarea name="description" cols="5" rows="4" class="common">{if isset($banner_plan.description)}{$banner_plan.description|escape:"html"}{elseif isset($smarty.post.description)}{$smarty.post.description|escape:"html"}{/if}</textarea></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.cost}:</strong></td>
		<td><input type="text" class="common numeric" name="cost" size="30" value="{if isset($banner_plan.cost)}{$banner_plan.cost|escape:"html"}{elseif isset($smarty.post.cost)}{$smarty.post.cost|escape:"html"}{/if}" /></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.days}:</strong></td>
		<td><input type="text" class="common numeric" name="period" size="30" value="{if isset($banner_plan.period)}{$banner_plan.period|escape:"html"}{elseif isset($smarty.post.period)}{$smarty.post.period|escape:"html"}{/if}" /></td>
	</tr>
	<tr>
		<td width="200"><strong>{$esynI18N.send_expiration_email}:</strong></td>
		<td><input type="text" name="email_expire" size="30" class="common" value="{if isset($banner_plan.email_expire)}{$banner_plan.email_expire|escape:"html"}{elseif isset($smarty.post.email_expire)}{$smarty.post.email_expire|escape:"html"}{/if}" /></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.set_status_after_submit}:</strong></td>
		<td>
			<select name="markas">
				<option value="active" {if isset($banner_plan.mark_as) && $banner_plan.mark_as eq 'active'}selected="selected"{elseif isset($smarty.post.markas) && $smarty.post.markas eq 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="inactive" {if isset($banner_plan.mark_as) && $banner_plan.mark_as eq 'inactive'}selected="selected"{elseif isset($smarty.post.markas) && $smarty.post.markas eq 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="200"><strong>{$esynI18N.cron_for_expiration_b}:</strong></td>
		<td>
			<select name="action_expire">
				<option value="" {if isset($banner_plan.action_expire) && $banner_plan.action_expire eq ''}selected="selected"{elseif isset($smarty.post.action_expire) && $smarty.post.action_expire eq ''}selected="selected"{/if}>{$esynI18N.nothing}</option>
				<option value="remove" {if isset($banner_plan.action_expire) && $banner_plan.action_expire eq 'remove'}selected="selected"{elseif isset($smarty.post.action_expire) && $smarty.post.action_expire eq 'remove'}selected="selected"{/if}>{$esynI18N.remove}</option>
				<optgroup label="Status">
					<option value="active" {if isset($banner_plan.action_expire) && $banner_plan.action_expire eq 'active'}selected="selected"{elseif isset($smarty.post.action_expire) && $smarty.post.action_expire eq 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
					<option value="inactive" {if isset($banner_plan.action_expire) && $banner_plan.action_expire eq 'inactive'}selected="selected"{elseif isset($smarty.post.action_expire) && $smarty.post.action_expire eq 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
				</optgroup>
			</select>
		</td>
	</tr>

	{if $blocks}
		<tr>
			<td><strong>{$esynI18N.blocks}:</strong></td>
			<td>
				<input type="checkbox" value="Check all" id="check_all_fields" />&nbsp;<label for="check_all_fields">{$esynI18N.select_all}</label><br />
				{foreach from=$blocks key=pos item=title}
					<input type="checkbox" name="blocks[]" value="{$pos}" id="blocks_{$pos}" 
						{if isset($banner_plan.blocks) && !empty($banner_plan.blocks)}
							{if in_array($pos, $banner_plan.blocks)}checked="checked"{/if}
						{/if} />&nbsp;
					<label for="blocks_{$pos}">{$title}</label><br />
				{/foreach}
			</td>
		</tr>
	{/if}

	{ia_hooker name="plansBeforeSubmitButton"}

	<tr class="all">
		<td colspan="2">
			<input type="submit" name="save" class="common" value="{if $smarty.get.do eq 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}" />
		</td>
	</tr>
	</table>
	<input type="hidden" name="id" value="{if isset($banner_plan.id)}{$banner_plan.id}{/if}" />
	<input type="hidden" name="old_name" value="{if isset($banner_plan.name)}{$banner_plan.name}{/if}" />
	<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}" />
	<input type="hidden" name="categories" id="categories" value="0" />
	</form>
	{include file="box-footer.tpl"}
{else}
	<div id="box_banner_plans" style="margin-top: 15px;">
		{preventCsrf}
	</div>
{/if}

{include_file js="js/ckeditor/ckeditor, js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/banners/js/admin/banner_plans"}

{ia_hooker name="plansAfterJsInclude"}

{include file='footer.tpl'}