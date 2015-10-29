{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer" js="js/jquery/plugins/iphoneswitch/jquery.iphone-switch"}

{if (isset($smarty.get.do) && $smarty.get.do == 'add') || (isset($smarty.get.do) && $smarty.get.do == 'edit' && isset($page) && !empty($page))}
	{include file='box-header.tpl' title=$gTitle}
	<form action="controller.php?file=pages&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post" id="page_form">
	{preventCsrf}
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong>{lang key="name"}:</strong></td>
		<td>
			<input type="text" name="name" size="24" class="common" value="{if isset($page.name)}{$page.name}{elseif isset($smarty.post.name)}{$smarty.post.name}{/if}" {if isset($smarty.get.do) && $smarty.get.do == 'edit'}readonly="readonly"{/if} />
			<div class="option_tip">{lang key="unique_name"}</div>
		</td>
	</tr>
	{foreach from=$langs key=code item=lang}
	<tr>
		<td><strong>{$lang}&nbsp;{$esynI18N.title}:</strong></td>
		<td>
			<input type="text" name="titles[{$code}]" size="24" class="common" value="{if isset($page.titles)}{$page.titles.$code}{elseif isset($smarty.post.titles.$code)}{$smarty.post.titles.$code}{/if}">
		</td>
	</tr>
	{/foreach}
	<tr>
		<td><strong>{$esynI18N.show_menus}:</strong></td>
		<td>
			{foreach from=$menus key=key item=menu}
				<input type="checkbox" name="menus[]" value="{$key}" id="{$key}" {if (isset($smarty.post.menus) && in_array($key, $smarty.post.menus)) || !empty($page_menus) && in_array($key, $page_menus)}checked="checked"{/if} />
				<label for="{$key}">{if !empty($menu)}{$menu}{else}{$key}{/if}</label><br />
			{/foreach}
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.no_follow_url}:</strong></td>
		<td>
			{html_radio_switcher value=$page.nofollow|default:$smarty.post.nofollow|default:0 name="nofollow"}
		</td>
	</tr>
	<tr>
		<td><strong>{lang key="page_new_window"}:</strong></td>
		<td>
			{html_radio_switcher value=$page.new_window|default:$smarty.post.new_window|default:0 name="new_window"}
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($page.status) && $page.status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="approval" {if isset($page.status) && $page.status == 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.external_url}:</strong></td>
		<td>
			{if isset($page) && !empty($page.unique_url)}
				{assign var="default_external" value="1"}
			{else}
				{assign var="default_external" value="0"}
			{/if}

			{html_radio_switcher value=$default_external name="external_url"}
		</td>
	</tr>
	</table>
	<div id="url_field" style="display: none;">
		<table cellspacing="0" width="100%" class="striped">
		<tr>
			<td width="200"><strong>{$esynI18N.page_external_url}:</strong></td>
			<td>
				<input type="text" name="unique_url" size="44" id="unique_url" class="common" value="{if isset($page.unique_url)}{$page.unique_url}{elseif isset($smarty.post.unique_url)}{$smarty.post.unique_url}{/if}">
			</td>
		</tr>
		</table>
	</div>
	<div id="page_options" style="display: none;">
		<table cellspacing="0" width="100%" class="striped">
		<tr>
			<td width="200"><strong>{$esynI18N.custom_url}:</strong></td>
			<td>
				<input type="text" name="custom_url" size="24" class="common" style="float: left;" value="{if isset($page.custom_url)}{$page.custom_url}{elseif isset($smarty.post.custom_url)}{$smarty.post.custom_url}{/if}">
				<div style="float: left; display: none; margin-left: 3px; padding: 4px;" id="page_url_box"><span>{$esynI18N.page_url_will_be}:&nbsp;<span><span id="page_url" style="padding: 3px; margin: 0; background: #FFE269;"></span></div>
			</td>
		</tr>
		<tr>
			<td width="200"><strong>{$esynI18N.meta_description}:</strong></td>
			<td>
				<textarea name="meta_description" cols="43" rows="2" class="common">{if isset($page.meta_description)}{$page.meta_description}{elseif isset($smarty.post.meta_description)}{$smarty.post.meta_description|escape:'html'}{/if}</textarea>
			</td>
		</tr>
		<tr>
			<td width="200"><strong>{$esynI18N.meta_keywords}:</strong></td>
			<td>
				<input type="text" name="meta_keywords" class="common" value="{if isset($page.meta_keywords)}{$page.meta_keywords}{elseif isset($smarty.post.meta_keywords)}{$smarty.post.meta_keywords|escape:'html'}{/if}" size="42"/>
			</td>
		</tr>
		</table>
	</div>

	<div id="ckeditor" style="display: none; padding: 5px 0 10px 11px;">
		<div style="padding-bottom: 5px;"><b>{$esynI18N.page_content}:</b></div>
		<div id="editorToolbar"></div>
		<div id="languages_content"></div>
		{foreach from=$langs key=code item=pre_lang}
			<div id="div_content_{$code}" title="{$pre_lang}" class="pre_lang x-hide-display">
				<textarea id="contents[{$pre_lang}]" name="contents[{$code}]" class="ckeditor_textarea">{if isset($page.contents.$code)}{$page.contents.$code}{elseif isset($smarty.post.contents.$code)}{$smarty.post.contents.$code}{else}&nbsp;{/if}</textarea>
			</div>
		{/foreach}
	</div>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr class="all">
		<td style="padding: 0 0 0 11px; width: 0;">
			<input type="submit" name="save" class="common" value="{if $smarty.get.do == 'add'}{$esynI18N.add}{else}{$esynI18N.save_changes}{/if}">
		</td>
		<td style="padding: 0;">
			{if $smarty.get.do == 'add'}
				<strong>&nbsp;{$esynI18N.and_then}&nbsp;</strong>
				<select name="goto">
					<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>{$esynI18N.go_to_list}</option>
					<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto == 'add'}selected="selected"{/if}>{$esynI18N.add_another_one}</option>
				</select>
			{/if}
			
			&nbsp;<input type="submit" value="{$esynI18N.preview} {$esynI18N.page}" class="common" name="preview">
		</td>
	</tr>
	</table>
	<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}">
	<input type="hidden" name="old_name" value="{if isset($page.name)}{$page.name}{/if}">
	<input type="hidden" name="old_custom_url" value="{if isset($page.custom_url)}{$page.custom_url}{/if}">
	<input type="hidden" name="id" value="{if isset($page.id)}{$page.id}{/if}">
	</form>
	{include file='box-footer.tpl'}
{else}
	<div id="box_pages" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/pages"}

{include file='footer.tpl'}