{include file='header.tpl'}

{if isset($smarty.get.do) && ('add' == $smarty.get.do)}
	{include file="box-header.tpl" title=$gTitle}
	<div id="subscribe_form">	
		<form action="" method="post">
			{preventCsrf}
			<table width="100%" cellpadding="0" cellspacing="0" class="striped">
				<tr>
					<td width="100"><strong>{$esynI18N.name}</strong></td>
					<td><input type="text" name="realname" class="common" size="52" value="{if isset($smarty.post.realname)}{$smarty.post.realname}{/if}" /></td>
				</tr>
				<tr>
					<td><strong>{$esynI18N.email}</strong></td>
					<td><input type="text" name="email" class="common" size="52" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" /></td>
				</tr>
				<tr>
					<td><strong>{$esynI18N.status}</strong></td>
					<td>
						<select name="status">
							<option value="active">{$esynI18N.active}</option>
							<option value="approval">{$esynI18N.approval}</option>
							<option value="unconfirmed">{$esynI18N.unconfirmed}</option>
						</select>
					</td>
				</tr>
				<tr class="all">
					<td colspan="2"><input class="common" type="submit" value="{$esynI18N.add}" name="save" /></td>
				</tr>
			</table>
		</form>
	</div>	
	{include file="box-footer.tpl"}
{else}

	<div id="box_subscribe" style="margin-top: 15px;"></div>
	{include_file js="js/intelli/intelli.grid,  js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/mailer/js/admin/index"}

{/if}



{include file='footer.tpl'}