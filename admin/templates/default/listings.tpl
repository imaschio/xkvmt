{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

<div id="box_listings" style="margin-top: 15px;"></div>

<div id="remove_reason" style="display: none;">
	{$esynI18N.listing_remove_reason}<br />
	<textarea cols="40" rows="5" name="body" id="remove_reason_text" class="common" style="width: 99%;"></textarea>
</div>

<div class="x-hidden email-templates" id="email_templates">
	<div class="buttons" style="margin: 0 10px 0 0;">
		<a href="#" id="tags"><img src="templates/default/img/icons/list.png" title="Email Templates Tags" alt="Email Templates Tags"></a>
	</div>
	<p>
		<strong style="width:100px; display:inline-block;">{lang key="language"}:</strong>
		<select name="lang" id="lang" class="common" {if $langs|@count == 1}disabled="disabled"{/if}>
			{foreach from=$langs key=key item=value}
				<option value="{$key}" {if (isset($smarty.post.lang) && $key == $smarty.post.lang) || (isset($smarty.post.lang) && $value|trim:"'" == $smarty.post.lang)}selected="selected"{/if}>{$value|trim:"'"}</option>
			{/foreach}
		</select>
	</p>
	<p>
		<strong style="width:100px; display:inline-block;">Email to:</strong>
 		<label style="padding-right:5px;"><input type="radio" name="email" value="listing" checked="checked"> {lang key="listing"}</label>
 		<label style="padding-right:5px;"><input type="radio" name="email" value="account"> {lang key="account"}</label>
	</p>
	<p>
		<strong style="width:100px; display:inline-block;">{$esynI18N.email}:</strong>
		<select id="tpl" name="tpl" style="width:350px; margin-right: 5px;">
			<option value="">{$esynI18N._select_}</option>

			{foreach from=$email_groups item="group"}
				{assign var="email_group_key" value="email_group_"|cat:$group}
				{if !empty($tmpls.$group)}
					<optgroup class="{$group}" label="{lang key=$email_group_key}">
						{foreach from=$tmpls.$group key=key item=i}
							<option value="{$key}">{$i|escape:'html'}</option>
						{/foreach}
					</optgroup>
				{/if}
			{/foreach}
		</select>
	</p>
	<p>
		<strong style="width:100px; display:inline-block;">{$esynI18N.subject}:</strong>
		<input type="text" name="subject" id="subject" style="width:350px;" class="common">
	</p>
	<p>
		<strong style="width:100px; display:inline-block;">{$esynI18N.body}:</strong>
		<textarea name="body" id="body" cols="107" rows="18" class="common" style="resize:both"></textarea>
	</p>
</div>
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

{include_file js="js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/ext/plugins/datetime/datetime, js/ckeditor/ckeditor, js/jquery/plugins/jquery.form.ajaxLoader, js/admin/listings"}

{include file='footer.tpl'}