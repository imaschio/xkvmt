{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer, js/bootstrap/css/passfield"}

{if (isset($smarty.get.do) && $smarty.get.do == 'add') || (isset($smarty.get.do) && $smarty.get.do == 'edit' && isset($admin) && !empty($admin))}
	{include file='box-header.tpl' title=$gTitle}
	<form action="controller.php?file=admins{if $smarty.get.do == 'add'}&amp;do=add{else $smarty.get.do == 'edit'}&amp;do=edit&amp;id={$smarty.get.id}{/if}" method="post">
	{preventCsrf}
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.username}:</strong></td>
		<td><input type="text" name="username" class="common" size="22" value="{if isset($admin.username)}{$admin.username|escape:'html'}{elseif isset($smarty.post.username)}{$smarty.post.username|escape:'html'}{/if}"></td>
	</tr>
	
	<tr>
		<td><strong>{$esynI18N.fullname}:</strong></td>
		<td><input type="text" name="fullname" class="common" size="22" value="{if isset($admin.fullname)}{$admin.fullname|escape:'html'}{elseif isset($smarty.post.fullname)}{$smarty.post.fullname|escape:'html'}{/if}"></td>
	</tr>
	
	<tr>
		<td><strong>{$esynI18N.email}:</strong></td>
		<td><input type="text" name="email" class="common" size="22" value="{if isset($admin.email)}{$admin.email|escape:'html'}{elseif isset($smarty.post.email)}{$smarty.post.email|escape:'html'}{/if}"></td>
	</tr>
		
	<tr>
		<td><strong>{$esynI18N.password}:</strong></td>
		<td><input type="password" name="new_pass" class="js-input-password common" size="22"></td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.password_confirm}:</strong></td>
		<td><input type="password" name="new_pass2" class="common" size="22"></td>
	</tr>

	{if ('add' == $smarty.get.do) || (isset($smarty.get.id) && $currentAdmin.id != $smarty.get.id)}
		<tr>
			<td><strong>{$esynI18N.status}:</strong></td>
			<td>
				<select name="status">
					<option value="active" {if isset($admin.status) && $admin.status == 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
					<option value="inactive" {if isset($admin.status) && $admin.status == 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
				</select>
			</td>
		</tr>
	{/if}

	<tr>
		<td><strong>{$esynI18N.submission_notif}:</strong></td>
		<td>{html_radio_switcher value=$admin.submit_notif name="submit_notif"}</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.account_registr_notif}:</strong></td>
		<td>{html_radio_switcher value=$admin.account_registr_notif name="account_registr_notif"}</td>
	</tr>

	{if $config.sponsored_listings}
		<tr>
			<td><strong>{$esynI18N.payment_notif}:</strong></td>
			<td>{html_radio_switcher value=$admin.payment_notif name="payment_notif"}</td>
		</tr>
	{/if}

	{if ('add' == $smarty.get.do) || (isset($smarty.get.id) && $currentAdmin.id != $smarty.get.id)}
		<tr>
			<td class="caption" colspan="2"><strong>{$esynI18N.admin_permissions}</strong></td>
		</tr>

		<tr>
			<td><strong>{$esynI18N.super_admin}:&nbsp;</strong></td>
			<td>
				{html_radio_switcher value=$admin.super|default:$smarty.post.super|default:1 name="super"}
			</td>
		</tr>
	{/if}

	</table>

	<div id="permissions" style="display: none;">
		<table cellspacing="0" width="100%" class="striped">
		<tr>
			<td>
				{if isset($admin_blocks) && !empty($admin_blocks)}
					{if isset($admin_pages) && !empty($admin_pages)}
						<input type="checkbox" value="1" name="select_all" id="select_all" {if isset($smarty.post.select_all) && $smarty.post.select_all == '1'}checked="checked"{/if} /><label for="select_all">&nbsp;{$esynI18N.select_all}</label>
							<div style="clear:both;"></div>
						{foreach from=$admin_blocks item=block}
							<fieldset class="list" style="float:left;">
								{assign var="post_key" value="select_all_"|cat:$block}
								<legend><input type="checkbox" value="1" class="{$block}" name="select_all_{$block}" id="select_all_{$block}" {if isset($smarty.post.$post_key) && $smarty.post.$post_key == '1'}checked="checked"{/if} /><label for="select_all_{$block}">&nbsp;<strong>{$esynI18N.$block|capitalize}</strong></label></legend>
								{foreach from=$admin_pages key=key item=page}
									{if $page.block_name == $block}
										<ul style="list-style-type: none; width:200px;">
											<li style="margin: 0 0 0 15px; padding-bottom: 3px; float: left; width: 200px;" >
												<input type="checkbox" name="permissions[]" class="{$block}" value="{$page.aco}" id="page_{$key}" {if isset($admin.permissions) && in_array($page.aco, $admin.permissions)}checked="checked"{/if} /><label for="page_{$key}">&nbsp;{$page.title}</label>
											</li>
										</ul>
									{/if}
								{/foreach}
							</fieldset>
						{/foreach}
					{/if}
				{/if}
			</td>
		</tr>
		</table>
	</div>
	
	<table cellspacing="0" width="100%" class="striped">
	<tr>
		<td style="padding: 0 0 0 11px; width: 0;">
			<input type="submit" name="save" class="common" value="{if isset($smarty.get.do) && $smarty.get.do == 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}">
		</td>
		<td style="padding: 0;">
			{if $smarty.get.do == 'add'}
				<strong>&nbsp;{$esynI18N.and_then}&nbsp;</strong>
				<select name="goto">
					<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>{$esynI18N.go_to_list}</option>
					<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto == 'add'}selected="selected"{/if}>{$esynI18N.add_another_one}</option>
				</select>
			{/if}
		</td>
	</tr>
	</table>
	<input type="hidden" name="id" value="{if isset($admin.id)}{$admin.id}{/if}">
	<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}">
	<input type="hidden" name="old_email" value="{if isset($admin.email)}{$admin.email|escape:'html'}{/if}">
	</form>
	{include file='box-footer.tpl'}
{else}
	<div id="box_admins" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/bootstrap/js/passfield.min, js/admin/admins"}

{include file='footer.tpl'}
