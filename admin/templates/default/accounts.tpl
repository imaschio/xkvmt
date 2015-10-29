{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer, js/bootstrap/css/passfield"}

{if (isset($smarty.get.do) && $smarty.get.do == 'add') || (isset($smarty.get.do) && $smarty.get.do == 'edit' && isset($account) && !empty($account))}
	{include file='box-header.tpl' title=$gTitle}
	<form action="controller.php?file=accounts&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post" enctype="multipart/form-data">
	{preventCsrf}
	<table cellspacing="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.username}:</strong></td>
		<td><input type="text" name="username" size="26" class="common" value="{if isset($account.username)}{$account.username|escape:'html'}{elseif isset($smarty.post.username)}{$smarty.post.username|escape:'html'}{/if}"></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.password}:</strong></td>
		<td><input type="password" name="password" size="26" class="js-input-password common" value="{if isset($smarty.post.password)}{$smarty.post.password|escape:'html'}{/if}" autocomplete="off"/></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.password_confirm}:</strong></td>
		<td><input type="password" name="password2" size="26" class="common" value="{if isset($smarty.post.password2)}{$smarty.post.password2|escape:'html'}{/if}"></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.email}:</strong></td>
		<td><input type="text" name="email" size="26" class="common" value="{if isset($account.email)}{$account.email|escape:'html'}{elseif isset($smarty.post.email)}{$smarty.post.email|escape:'html'}{/if}"></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.avatar}:</strong></td>
		<td>
			{if isset($smarty.get.do) && $smarty.get.do == 'edit'}
				{assign var='file_path' value="{$smarty.const.IA_HOME}uploads{$smarty.const.IA_DS}{$account.avatar}"}

				{if $file_path|is_file && $file_path|file_exists}
					<div id="file_manage">
						<div class="image_box">
							<img src="../uploads{$smarty.const.IA_DS}{$account.avatar}" alt="" width="100" height="100"/>
							<div class="delete">
								<a href="controller.php?file=accounts&do=edit&id={$account.id}&delete=photo">{$esynI18N.delete}</a>
							</div>
						</div>
					</div>
				{/if}
			{/if}

			<input type="file" name="avatar" id="avatar" size="40" style="float: left;">
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($account.status) && $account.status == 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="approval" {if isset($account.status) && $account.status == 'approval'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'approval'}selected="selected"{/if}>{$esynI18N.approval}</option>
				<option value="banned" {if isset($account.status) && $account.status == 'banned'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'banned'}selected="selected"{/if}>{$esynI18N.banned}</option>
			</select>
		</td>
	</tr>
	
	{if isset($plans) && !empty($plans)}
		<tr>
			<td valign="top"><strong>{$esynI18N.plans}:</strong></td>
			<td>
				<p class="field">
					<input type="radio" name="plan" id="plan_reset" value="-1" {if isset($smarty.post.plan) && $smarty.post.plan == '-1'}checked="checked"{elseif !isset($account) && empty($smarty.post)}checked="checked"{/if} />
					<label for="plan_reset"><strong>{lang key='not_assign_plan'}</strong></label>
				</p>

				{foreach from=$plans item=plan}
					<p class="field">
						<input type="radio" name="plan" value="{$plan.id}" id="plan_{$plan.id}" {if isset($account.plan_id) && $account.plan_id == $plan.id}checked="checked"{elseif isset($smarty.post.plan) && $smarty.post.plan == $plan.id}checked="checked"{/if} />
						<label for="plan_{$plan.id}"><strong>{$plan.title}&nbsp;-&nbsp;{$config.currency_symbol}{$plan.cost}</strong></label><br>
						<i>{$plan.description}</i>
					</p>
				{/foreach}
			</td>
		</tr>
		<tr>
			<td width="200"><strong>{$esynI18N.expire_date}:</strong></td>
			<td>
				<input type="text" name="sponsored_expire_date" id="sponsored_expire_date" class="common" value="{if isset($account.sponsored_expire_date)}{$account.sponsored_expire_date}{elseif isset($smarty.post.sponsored_expire_date)}{$smarty.post.sponsored_expire_date}{/if}">
			</td>
		</tr>
	{/if}
	</table>

	<table>
	<tr class="all">
		<td style="padding: 0 0 0 11px;">
			{if isset($smarty.get.do) && $smarty.get.do == 'edit'}
				<input type="checkbox" name="send_email" id="send_email">&nbsp;<label for="send_email">{$esynI18N.email_notif}?</label>&nbsp;|&nbsp;
			{/if}
			<input type="submit" name="save" class="common" value="{if $smarty.get.do == 'add'}{$esynI18N.add}{else}{$esynI18N.save_changes}{/if}">
		</td>
		<td style="padding: 0;">
		{if isset($smarty.get.do) && $smarty.get.do == 'add'}
			<span><strong>&nbsp;{$esynI18N.and_then}&nbsp;</strong></span>
			<select name="goto">
				<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>{$esynI18N.go_to_list}</option>
				<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto == 'add'}selected="selected"{/if}>{$esynI18N.add_another_one}</option>
			</select>
		{/if}
		</td>
	</tr>
	</table>
	<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}">
	<input type="hidden" name="old_name" value="{if isset($account.username)}{$account.username|escape:'html'}{/if}">
	<input type="hidden" name="old_email" value="{if isset($account.email)}{$account.email|escape:'html'}{/if}">
	<input type="hidden" name="id" value="{if isset($smarty.get.id)}{$smarty.get.id}{/if}">
	</form>
	{include file='box-footer.tpl'}
{else}
	<div id="box_accounts" style="margin-top: 15px;"></div>
{/if}

{include_file js='js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/bootstrap/js/passfield.min, js/admin/accounts'}

{ia_hooker name="smartyAdminAccountsAfterJSInclude"}

{include file='footer.tpl'}