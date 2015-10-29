{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if isset($smarty.get.do) && ($smarty.get.do eq 'add' || $smarty.get.do eq 'edit')}
	
	{include file="box-header.tpl" title=$gTitle}
	
	<form action="{$smarty.const.IA_CURRENT_PLUGIN_URL}&amp;do={$smarty.get.do}{if $smarty.get.do eq 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
		
	{preventCsrf}
	
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.message_author}:</strong></td>
		<td><input type="text" class="common" size="40" name="author_name" value="{$message.author_name}"/></td>
	</tr>
	<tr>
		<td width="200"><strong>{$esynI18N.author_email}:</strong></td>
		<td><input type="text" class="common" size="40" name="email" value="{$message.email}"/></td>
	</tr>
	<tr>
		<td width="200"><strong>{$esynI18N.author_url}:</strong></td>
		<td><input type="text" class="common" size="40" name="author_url" value="{$message.author_url}"/></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.body}:</strong></td>
		<td><textarea name="body" cols="53" class="common" rows="8">{$message.body}</textarea></td>
	</tr>
	
	<tr>
		<td><strong>{$esynI18N.date}</strong></td>
		<td><input type="text" class="common" id="date" name="date" size="32" value="{$message.date}" /></td>
	</tr>
	
	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if $message.status eq 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="inactive" {if $message.status eq 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
			</select>
		</td>
	</tr>
	
	<tr class="all">
		<td colspan="2">
			<input type="submit" name="save" class="common" value="{if $smarty.get.do eq 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}" />
		</td>
	</tr>
	
	</table>
	</form>
	
	{include file="box-footer.tpl"}
{else}
	<div id="box_guestbook" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/"|cat:$smarty.const.IA_CURRENT_PLUGIN|cat:"/js/admin/index"}

{include file='footer.tpl'}
