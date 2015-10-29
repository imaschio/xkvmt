{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer" js="js/jquery/plugins/iphoneswitch/jquery.iphone-switch"}

{if isset($smarty.get.do) && ($smarty.get.do == 'add' || $smarty.get.do == 'edit')}
	{include file='box-header.tpl' title=$gTitle}
	<form action="controller.php?file=field-groups&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
	{preventCsrf}

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	{foreach from=$langs key=code item=lang}
	<tr>
		<td><strong>{$lang}&nbsp;{$esynI18N.title}:</strong></td>
		<td>
			<input type="text" name="title[{$code}]" size="24" class="common" value="{if isset($group.title.$code)}{$group.title.$code}{elseif isset($smarty.post.title[$code])}{$smarty.post.title[$code]}{/if}">
		</td>
	</tr>
	{/foreach}

	<tr>
		<td class="tip-header" id="tip-header-show_on_pages" valign="top" width="150"><strong>{$esynI18N.show_on_pages}:</strong></td>
		<td class="fields-pages">
			<label for="lp1"><input type="checkbox" name="pages[]" value="suggest" id="lp1" {if isset($group.pages) && in_array('suggest', $group.pages)}checked="checked"{elseif isset($smarty.post.pages) && in_array('suggest', $smarty.post.pages)}checked="checked"{elseif !isset($group) && empty($smarty.post)}checked="checked"{/if} />{lang key="suggest_listing"}</label>
			<label for="lp2"><input type="checkbox" name="pages[]" value="edit" id="lp2" {if isset($group.pages) && in_array('edit', $group.pages)}checked="checked"{elseif isset($smarty.post.pages) && in_array('edit', $smarty.post.pages)}checked="checked"{elseif !isset($group) && empty($smarty.post)}checked="checked"{/if} />{lang key="edit_listing"}</label>
			<label for="lp3"><input type="checkbox" name="pages[]" value="view" id="lp3" {if isset($group.pages) && in_array('view', $group.pages)}checked="checked"{elseif isset($smarty.post.pages) && in_array('view', $smarty.post.pages)}checked="checked"{elseif !isset($group) && empty($smarty.post)}checked="checked"{/if} />{lang key="view_listing"}</label>
			<label for="lp4"><input type="checkbox" name="pages[]" value="search" id="lp4" {if isset($group.pages) && in_array('search', $group.pages)}checked="checked"{elseif isset($smarty.post.pages) && in_array('search', $smarty.post.pages)}checked="checked"{elseif !isset($group) && empty($smarty.post)}checked="checked"{/if} />{lang key="search_page"}</label>
		</td>
	</tr>

	<tr>
		<td valign="top"><strong>{lang key='fields_for_group'}:</strong></td>
		<td>
			<fieldset style="width: 200px;">
				<legend><input type="checkbox" value="Check all" id="check_all_fields">&nbsp;<label for="check_all_fields">{lang key='all_fields'}</label></legend>
				<ul>
					{foreach $fields as $field}
						<li>
							<input type="checkbox" name="fields[]" value="{$field.name}" id="field_{$field.id}" {if isset($group) && $field.group == $group.name}checked="checked"{/if} />&nbsp;<label for="field_{$field.id}">{lang key="field_{$field.name}"}</label>
						</li>
					{/foreach}
				</ul>
			</fieldset>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.collapsible}:</strong></td>
		<td>
			{html_radio_switcher value=$group.collapsible|default:$smarty.post.collapsible|default:0 name='collapsible'}
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.collapsed}:</strong></td>
		<td>
			{html_radio_switcher value=$group.collapsed|default:$smarty.post.collapsed|default:0 name='collapsed'}
		</td>
	</tr>

	<tr class="all">
		<td colspan="2">
			<input type="hidden" name="name" value="{if isset($group.name)}{$group.name}{/if}">
			<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}">
			<input type="submit" name="save" class="common" value="{if $smarty.get.do == 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}">
		</td>
	</tr>

	</table>
	</form>
	{include file='box-footer.tpl'}
{else}
	<div id="box_sections" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/field-groups"}

{include file='footer.tpl'}