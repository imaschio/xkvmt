{include file="header.tpl"}

{include file="box-header.tpl" title=$gTitle|default:"test"}
<form method="post" action="controller.php?file=email-templates" id="tpl_form">
	<table class="striped">
		<tr>
			<td width="200"><strong>{lang key="language"}:</strong></td>
			<td>
				<select name="lang" id="lang" class="common" {if $langs|@count == 1}disabled="disabled"{/if}>
					{foreach from=$langs key=key item=value}
						<option value="{$key}" {if (isset($smarty.post.lang) && $key == $smarty.post.lang) || (isset($smarty.post.lang) && $value|trim:"'" == $smarty.post.lang)}selected="selected"{/if}>{$value|trim:"'"}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td><strong>{$esynI18N.email}:</strong></td>
			<td>
				<span style="float: left; margin-right: 5px";>
					<select id="tpl" name="tpl">
						<option value="">{$esynI18N._select_}</option>

						{foreach from=$email_groups item="group"}
							{assign var="email_group_key" value="email_group_"|cat:$group}
							{if !empty($tmpls.$group)}
								<optgroup label="{lang key=$email_group_key}">
									{foreach from=$tmpls.$group key=key item=i}
										<option value="{$key}">{$i|escape:'html'}</option>
									{/foreach}
								</optgroup>
							{/if}
						{/foreach}
					</select>
				</span>

				<span id="switcher" style="display: none;">
					{html_radio_switcher value=1 name='template'}
				</span>

			</td>
		</tr>
		<tr>
			<td><strong>{$esynI18N.subject}:</strong></td>
			<td><input type="text" name="subject" id="subject" size="56" class="common"></td>
		</tr>
		<tr>
			<td><strong>{$esynI18N.body}:</strong></td>
			<td><textarea name="body" id="body" cols="70" rows="20" class="common" style="resize:both"></textarea></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" class="common" value="{$esynI18N.save}">
				<input type="submit" class="common" id="save_as_new" value="{lang key='save_as_new'}">
			</td>
		</tr>
	</table>
</form>

{include file='box-footer.tpl'}

<div class="x-hidden template-tags" id="template_tags">
	<div class="option_tip" style="margin: 5px 5px 0 5px;">{lang key="email_templates_tags_info"}</div>
	<ul>
		<li><h4>{lang key="common"}</h4></li>
		<li><a href="#" class="email_tags">{literal}{dir_title}{/literal}</a> - <span>{$config.site}</span></li>
		<li><a href="#" class="email_tags">{literal}{dir_url}{/literal}</a> - <span>{$smarty.const.IA_URL}</span></li>
		<li><a href="#" class="email_tags">{literal}{dir_email}{/literal}</a> - <span>{$config.site_email}</span></li>
	</ul>
	<ul>
		<li><h4>{lang key="account"}</h4></li>
		<li><a href="#" class="email_tags">{literal}{account_name}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{account_key}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{account_email}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{account_status}{/literal}</a></li>
	</ul>
	<ul>
		<li><h4>{lang key="listing"}</h4></li>
		<li><a href="#" class="email_tags">{literal}{listing_id}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{listing_title}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{listing_url}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{listing_email}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{listing_status}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{listing_rank}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{listing_description}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{listing_path}{/literal}</a></li>
	</ul>
	<ul>
		<li><h4>{lang key="category"}</h4></li>
		<li><a href="#" class="email_tags">{literal}{category_title}{/literal}</a></li>
		<li><a href="#" class="email_tags">{literal}{category_path}{/literal}</a></li>
	</ul>
</div>

<div class="x-hidden template-name" id="template_name">
	<strong>{lang key="email_template_name"}</strong>: custom_ <input type="text" size="56" class="common" value="" id="tpl_name" style="padding:0; margin:0;">
</div>

{include_file js="js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/jquery/plugins/jquery.form.ajaxLoader, js/ckeditor/ckeditor, js/admin/email-templates"}

{include file='footer.tpl'}
