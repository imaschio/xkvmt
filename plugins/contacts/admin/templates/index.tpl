{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if isset($smarty.get.do) && ($smarty.get.do == 'compose' || $smarty.get.do == 'reply' || $smarty.get.do == 'contact')}
	{include file="box-header.tpl" class="box" title=$gTitle style="margin-bottom: 10px;"}
	<form action="controller.php?plugin=contacts&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
	{preventCsrf}
	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	
    <tr>
		<td width="100"><strong>{$esynI18N.subject}</strong></td>
		<td><input type="text" class="common" name="subject" size="32" value="{if isset($contact.subject)}Re: {$contact.subject|truncate:"75"|escape:'html'}{elseif isset($smarty.post.subject)}{$smarty.post.subject|escape:'html'}{/if}" /></td>
	</tr>
    
	<tr>
		<td width="100"><strong>{$esynI18N.from}</strong></td>
		<td><input type="text" class="common" name="from" size="32" value="{if isset($currentAdmin.email)}{$currentAdmin.email}{elseif isset($smarty.post.from)}{$smarty.post.from}{/if}" /></td>
	</tr>

	<tr>
		<td width="100"><strong>{$esynI18N.to}</strong></td>
		<td><input type="text" class="common" name="to" size="32" value="{if isset($contact.email)}{$contact.email}{elseif isset($smarty.get.to)}{$smarty.get.to}{elseif isset($smarty.post.to)}{$smarty.post.to}{/if}" /></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.body}</strong></td>
		<td>
			<textarea name="body" id="body">{if isset($body)}&gt;&gt; {$body}{elseif isset($smarty.post.body)}{$smarty.post.body}{/if}</textarea>
		</td>
	</tr>
	<input type="hidden" name="go2accounts" value="{if isset($smarty.get.do) && $smarty.get.do == 'contact'}true{else}false{/if}" />
	<tr class="all">
		<td colspan="2">
			<input type="submit" name="send" class="common" value="{if $smarty.get.do == 'reply'}{$esynI18N.reply}{else}{$esynI18N.send}{/if}" />
		</td>
	</tr>
	</table>
	</form>
	{include file='box-footer.tpl'}
{else}
	<div id="box_contacts" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/contacts/js/admin/contacts"}

{include file='footer.tpl'}
