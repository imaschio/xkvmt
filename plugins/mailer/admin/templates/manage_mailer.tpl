{include file='header.tpl'}

{if isset($smarty.get.do) && $smarty.get.do eq 'send'}
	{include file="box-header.tpl" title=$gTitle}
	<form action="" method="post" enctype="multipart/form-data">
		{preventCsrf}
		<table class="striped"  width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td width="150"><label for="subject" style="font-weight: bold;">{$esynI18N.subject}:</label></td>
				<td><input type="text" name="subject" id="subject" class="common" style="width:250px;" value="{$state.subject}" /></td>
			</tr>
			<tr>
				<td width="150"><label for="from" style="font-weight: bold;">{$esynI18N.from}:</label></td>
				<td><input type="text" name="from" id="from" class="common" style="width:250px;" value="{$config.site_email}" /></td>
			</tr>
			<tr>
				<td width="150"><label for="reply" style="font-weight: bold;">{$esynI18N.reply}:</label></td>
				<td><input type="text" name="reply" id="reply" class="common" style="width:250px;" value="{$state.reply}" /></td>
			</tr>
			<tr>
				<td><label for="usergroup" style="font-weight: bold;">{$esynI18N.recipients}:</label></td>
				<td>
					<div style="float:left;width:110px;">
						<select name="usergroup[]" id="usergroup" style="width:105px;height:125px" multiple="multiple">
							{foreach from=$usergroups item='v' key='k'}
								<option value="{$k}">{$v}</option>
							{/foreach}	
						</select>
					</div>
					<div style="float:left;">
						<!--{$esynI18N.individ_receps}-->
						<textarea name="individual" id="individual" rows="5" cols="15" style="width:200px;height:100px;">{$state.individual}</textarea>
					</div>
					<div style="float:left; padding-left:20px;">
						<strong>{$esynI18N.get_rec_from_cat}</strong><br />
						<div id="tree"></div>			
					</div>
					<div style="width: 110px;"><p>{$esynI18N.select_accounts}</p>				
						{if $accounts}
							<select name="accunt[]" id="accunt" style="width:105px;height:125px" multiple="multiple">
								<option value="0">-{$esynI18N.all_accounts}-</option>
								{foreach from=$accounts item='acc'}
									<option value="{$acc.id}">{$acc.username}</option>
								{/foreach}
							</select>
						{else}
							{$esynI18N.no_accounts}
						{/if}
					</div>
				</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">{$esynI18N.status}:</td>
				<td>
					{foreach from=$statuses key='i' item='st'}
						<input type="checkbox" name="statuses[]" id="st_{$i}" value="{$st}" {if $st eq 'active'}checked="checked"{/if} />		
						<label for="st_{$i}">{$st}</label><br />
					{/foreach}
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
					{$esynI18N.email_tags_explan}<br />
					<textarea readonly="readonly" name="tags_explan" id="tags_explan" cols="50" rows="8">
						%dir_title%
						%dir_url%
						%dir_email%
						%account_name%
					</textarea>
				</td>
			</tr>
		
			<tr>
				<td colspan="2"><label for="message"><b>{$esynI18N.body}: </b></label><br />
					<textarea {if $mimetype eq 'html'}class="ckeditor_textarea"{/if} name="message" id="message" cols="50" rows="8">{$state.message}</textarea>
				</td>
			</tr>
			<tr class="all">
				<td colspan="2">
					<input type="submit" name="add" class="common" value=" {$esynI18N.send} " />
				</td>
			</tr>
		</table>
	</form>
	{include file="box-footer.tpl"}
{else}
	
	<div id="box_queue" style="margin-top: 15px;"></div>
	
	
{/if}
{include_file js="js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/mailer/js/admin/manage-mailer"}
{include file='footer.tpl'}