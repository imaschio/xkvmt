{include file="header.tpl" css=$smarty.const.IA_URL|cat:"js/jquery/plugins/lightbox/css/jquery.lightbox"}

{include file='box-header.tpl' title=$gTitle}

<form name="suggest_listing" action="controller.php?file=suggest-listing{if isset($smarty.get.do)}&amp;do={$smarty.get.do}{/if}{if isset($smarty.get.status)}&amp;status={$smarty.get.status}{/if}{if isset($smarty.get.id)}&amp;id={$smarty.get.id}{/if}" method="post" enctype="multipart/form-data">
{preventCsrf}
<table cellspacing="0" cellpadding="0" width="100%" class="striped">
<tr>
	<td width="200"><strong>{$esynI18N.listing_category}:</strong></td>
	<td>
		<span id="parent_category_title_container">
			<strong>{if isset($category.title)}<a href="controller.php?file=browse&amp;id={$parent.id}">{$category.title}</a>{else}ROOT{/if}</strong>
		</span>&nbsp;|&nbsp;
		<a href="#" id="change_category">{$esynI18N.change}...</a>&nbsp;|&nbsp;
		<a href="#" id="add_crossed">{$esynI18N.add_as_crossed_to_other_categories}</a>

		<input type="hidden" id="category_id" name="category_id" value="{$category.id}">
		<input type="hidden" id="category_parents" name="category_parents" value="{if isset($category.parents)}{$category.parents}{/if}">
		<input type="hidden" name="multi_crossed" id="multi_crossed" value="{if isset($listing.crossed) && !empty($listing.crossed)}{'|'|implode:$listing.crossed}{/if}">
		<input type="hidden" name="crossed_expand_path" id="crossed_expand_path" value="{if isset($listing.crossed_expand_path) && !empty($listing.crossed_expand_path)}{','|implode:$listing.crossed_expand_path}{/if}">

		<div id="crossed">{if isset($crossed_html) && !empty($crossed_html)}{$crossed_html}{/if}</div>
	</td>
</tr>
</table>

{ia_hooker name="tplAdminSuggestListingForm"}

{if isset($fields)}
{foreach from=$fields key=group item=g_fields}

	{if $group != 'non_group'}
		{assign var="group_key" value="field_group_title_"|cat:$group}
		<fieldset>
		<legend><strong>{$esynI18N.$group_key}</strong></legend>
	{/if}
	
	{foreach from=$g_fields key=key item=value}
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr>
			{assign var="lang_key" value="field_"|cat:$value.name}
			{assign var="value_name" value=$value.name}
			<td width="200"><strong>{$esynI18N.$lang_key}:</strong></td>
			<td>
			{if $value.type == 'text' || $value.type == 'number'}
				<input {if $value.length != ''}maxlength="{$value.length}"{/if} type="text" name="{$value.name}" id="f_{$value.name}" value="{if isset($listing.$value_name)}{$listing.$value_name|escape:'html'}{elseif isset($smarty.post.$value_name)}{$smarty.post.$value_name}{else}{$value.default}{/if}" class="common{if $value.type == 'number'} numeric{/if}" size="45">

				{if 'title' == $value_name}
					</td></tr>
					<tr>
						<td><strong>{$esynI18N.path}:</strong></td>

						<td>
							<input type="text" name="title_alias" size="45" maxlength="150" class="common" style="float: left;" value="{if isset($listing.title_alias) && isset($smarty.get.do) && $smarty.get.do == 'edit'}{$listing.title_alias}{elseif isset($smarty.post.title_alias)}{$smarty.post.title_alias|escape:'html'}{/if}">&nbsp;
							<div style="float: left; display: none; margin-left: 3px; padding: 4px;" id="listing_url_box"><span>{$esynI18N.listing_url_will_be}:&nbsp;<span><span id="listing_url" style="padding: 3px; margin: 0; background: #FFE269;"></span></div>
						</td>
					</tr>
				{/if}

			{elseif $value.type == 'textarea'}
				{if $value.editor == '1'}
					<textarea class="ckeditor_textarea" id="{$value.name}" name="{$value.name}" id="f_{$value.name}" >{if isset($listing.$value_name)}{$listing.$value_name}{elseif isset($smarty.post.$value_name)}{$smarty.post.$value_name}{else}{$value.default}{/if}</textarea>
				{else}
					<textarea name="{$value.name}" id="f_{$value.name}" cols="53" rows="8" class="common">{if isset($listing.$value_name)}{$listing.$value_name}{elseif isset($smarty.post.$value_name)}{$smarty.post.$value_name}{else}{$value.default}{/if}</textarea><br />
				{/if}
			{elseif $value.type == 'combo'}
				{if isset($listing.$value_name)}
					{assign var="temp" value=$listing.$value_name}
				{elseif isset($smarty.post.$value_name)}
					{assign var="temp" value=$smarty.post.$value_name}
				{else}
					{assign var="temp" value=$value.default}
				{/if}

				{assign var="values" value=','|explode:$value.values}

				{if $values}
					<select name="{$value.name}">
					{foreach from=$values item=item}
						{assign var="key" value="field_"|cat:$value.name|cat:'_'|cat:$item}
						<option value="{$item}" {if $item == $temp}selected="selected"{/if}>{$esynI18N.$key}</option>
					{/foreach}
					</select>
				{/if}
			{elseif $value.type == 'radio'}
				{if isset($listing.$value_name)}
					{assign var="temp" value=$listing.$value_name}
				{elseif isset($smarty.post.$value_name)}
					{assign var="temp" value=$smarty.post.$value_name}
				{else}
					{assign var="temp" value=$value.default}
				{/if}

				{assign var="values" value=','|explode:$value.values}

				{if $values}
					<div class="fields-pages">
					{foreach from=$values item=item}
						{assign var="key" value="field_{$value.name}_{$item}"}
						<label for="{$value.name}_{$item}">
							<input type="radio" name="{$value.name}" id="{$value.name}_{$item}" value="{$item}" {if $item == $temp}checked="checked"{/if} />
							{$esynI18N.$key}
						</label>
					{/foreach}
					</div>
				{/if}
			{elseif $value.type == 'checkbox'}
				{if isset($listing.$value_name)}
					{assign var="default" value=','|explode:$listing.$value_name}
				{elseif isset($smarty.post.$value_name)}
					{assign var="default" value=$smarty.post.$value_name}
				{else}
					{assign var="default" value=','|explode:$value.default}
				{/if}

				{assign var="checkboxes" value=','|explode:$value.values}

				{if $checkboxes}
					<div class="fields-pages">
					{foreach from=$checkboxes key=index item=item}
						{assign var="key" value="field_"|cat:$value.name|cat:'_'|cat:$index}
						<label for="{$value.name}_{$item}">
							<input type="checkbox" name="{$value.name}[]" id="{$value.name}_{$item}" value="{$item}" {if in_array($item, $default)}checked="checked"{/if} />
							{$esynI18N.$key}
						</label>
					{/foreach}
					</div>
				{/if}
			{elseif $value.type == 'image' || $value.type == 'storage'}
				{if !is_writeable($smarty.const.IA_UPLOADS)}
					<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i>{$esynI18N.upload_writable_permission}</i></div>
				{else}
					{if $value.type == 'image'}
						<div class="gallery">
							{$esynI18N.image_title}:<br />
							<input class="common" type="text" value="{if isset($listing["{$value_name}_title"])}{$listing["{$value_name}_title"]}{/if}" name="{$value.name}_title" style="width: 220px;"/>
						</div>
					{/if}

					<input type="file" name="{$value.name}" id="{$value.name}" size="40" style="float:left;">
					{if isset($smarty.get.do) && $smarty.get.do == 'edit'}
						{assign var="file_path" value=$smarty.const.IA_UPLOADS|cat:$listing.$value_name}

						{if $file_path|is_file && $file_path|file_exists}
							<div id="file_manage" style="float:left;padding-left:10px;">
								<a href="../uploads/{$listing.$value_name}" {if $value.type == 'image'}class="lightbox"{else}target="_blank"{/if}>{$esynI18N.view}</a>&nbsp;|&nbsp;
								<a href="{$value_name}/{$smarty.get.id}/{$listing.$value_name}/" class="clear">{$esynI18N.delete}</a>
							</div>
						{/if}
					{/if}
				{/if}
			{elseif $value.type == 'pictures'}
				{if !is_writeable($smarty.const.IA_UPLOADS)}
					<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i>{$esynI18N.upload_writable_permission}</i></div>
				{else}
					{if isset($smarty.get.do) && $smarty.get.do == 'edit'}
						{if !empty($listing.$value_name)}
							{assign var="images" value=','|explode:$listing.$value_name}
							{assign var="images_titles" value=','|explode:$listing["{$value_name}_titles"]}

							{foreach from=$images key=key item=image}
								<div class="image_gallery_wrap">
									{assign var="file_path" value=$smarty.const.IA_UPLOADS|cat:'small_'|cat:$image}

									<div class="gallery">
										{$esynI18N.image_title}: <br />
										<input class="common" type="text" value="{$images_titles.$key}" name="{$value.name}_titles[]" style="width: 220px;"/>
									</div>

									<div class="image_box">
										{if $file_path|is_file && $file_path|file_exists}
											<a href="../uploads/{$image}" target="_blank" class="lightbox"><img src="../uploads/small_{$image}"></a>
											<div class="delete"><a href="{$value_name}/{$smarty.get.id}/{$image}" class="clear">{$esynI18N.delete}</a></div>
										{else}
											<a href="../uploads/{$image}" target="_blank" class="lightbox"><img src="../uploads/{$image}"></a>
											<div class="delete"><a href="{$value_name}/{$smarty.get.id}/{$image}" class="clear">{$esynI18N.delete}</a></div>
										{/if}
									</div>
								</div>
							{/foreach}
						{/if}
					{/if}

					<div class="gallery" style="clear: both;">
						{$esynI18N.image}:<br />
						<input type="file" name="{$value.name}[]" size="40" style="float:left;">
						<input type="button" value="+" class="add_img common small">
						<input type="button" value="-" class="remove_img common small"><br />
						
						{$esynI18N.image_title}: <br />
						<input class="common" type="text" value="" name="{$value.name}_titles[]" style="width: 220px;"/>
					</div>
					<input type="hidden" value="{$value.length}" name="num_images" id="{$value.name}_num_img">
				{/if}
			{/if}

			{if $config.autometafetch && 'url' == $value.name}
				<a href="#get_meta" id="fetch_meta">{lang key='fetch_meta'}</a>
			{/if}
		</td>
		</tr>
	</table>
	{/foreach}
	
	{if 'non_group' != $group}
		</fieldset>
	{/if}

	<div style="height: 15px;"></div>
{/foreach}
	
	<fieldset>
	<legend><strong>{$esynI18N.fields_for_admin}</strong></legend>
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	{if isset($smarty.get.do) && $smarty.get.do == 'edit'}
		<tr>
			<td width="200"><strong>{$esynI18N.date}:</strong></td>
			<td>
				<input type="text" name="date" id="date" class="common" value="{if isset($listing.date)}{$listing.date}{elseif isset($smarty.post.date)}{$smarty.post.date}{/if}">
			</td>
		</tr>
		
		<tr>
			<td width="200"><strong>{$esynI18N.time}:</strong></td>
			<td>
				<input type="text" name="time" id="time" class="common" value="{if isset($listing.time)}{$listing.time}{elseif isset($smarty.post.time)}{$smarty.post.time}{/if}">
			</td>
		</tr>
	{/if}

	<tr>
		<td width="200"><strong>{$esynI18N.featured}:</strong></td>
		<td>{html_radio_switcher value=$listing.featured|default:0 name="featured"}</td>
	</tr>

	<tr>
		<td width="200"><strong>{$esynI18N.partner}:</strong></td>
		<td>{html_radio_switcher value=$listing.partner|default:0 name="partner"}</td>
	</tr>

	<tr>
		<td width="200"><strong>{$esynI18N.sponsored}:</strong></td>
		<td>
			{html_radio_switcher value=$listing.sponsored|default:0 name="sponsored"}
		</td>
	</tr>

	{if isset($plans) && !empty($plans)}
		<tr>
			<td width="200"><strong>{$esynI18N.plans}:</strong></td>
			<td>
				{foreach from=$plans item=plan}
					<p class="field">
						<input type="radio" name="assign_plan" data-period="{$plan.period}" data-expire_notif="{$plan.expire_notif}" value="{$plan.id}" id="plan_{$plan.id}" {if isset($listing.plan_id) && $listing.plan_id == $plan.id}checked="checked"{elseif isset($smarty.post.assign_plan) && $smarty.post.assign_plan == $plan.id}checked="checked"{/if} />
						<label for="plan_{$plan.id}"><strong>{$plan.title}&nbsp;-&nbsp;{$config.currency_symbol}{$plan.cost}</strong></label>

						<input type="hidden" id="option_ids_{$plan.id}" value="{$plan.visual_options}">
					</p>
				{/foreach}
				<p class="field">
					<input type="radio" name="assign_plan" id="plan_reset" value="-1" {if isset($smarty.post.assign_plan) && $smarty.post.assign_plan == '-1'}checked="checked"{/if} />
					<label for="plan_reset"><strong>{$esynI18N.reset_plan}</strong></label>
				</p>
			</td>
		</tr>
	{/if}
	</table>

	{if isset($plans) && !empty($plans)}
		{if isset($listing) && !empty($listing.visual_options)}
			<input type="hidden" id="old_plan_id" name="old_plan_id" value="{if isset($listing.plan_id) && !empty($listing.plan_id)}{$listing.plan_id}{/if}">
		{/if}

		<script type="text/html" id="optionsList">
			<table cellspacing="0" cellpadding="0" width="100%" class="striped">
				<tr>
					<td width="200"><b>{$esynI18N['visual_options']}:</b></td>
					<td>
						<%
						var checked_plan_id = $("input[name='assign_plan']:checked").attr("id").replace("plan_", "");
						var current_plan_id = $('#old_plan_id').val();

						var checked_list = "{if isset($smarty.post.visual_options)}{implode(',', $smarty.post.visual_options)}{elseif isset($listing.visual_options)}{$listing.visual_options}{/if}";

						checked_list = checked_list.split(",");

						_.each(options, function(option) {
						%>

						<div class="option even">
							<div class="cfg">
								<input id="visual_option_<%= option.name %>" type="checkbox" name="visual_options[]" value="<%= option.name %>" style="margin:0 10px 0 0;"
								<%
									if (intelli.inArray(option.name, checked_list) && checked_plan_id == current_plan_id)
									{
										print('checked');
									}
								%>
								/>

								<span style="display:inline-block;width:200px;">
									<label for="visual_option_<%= option.name %>"><%= _t('listing_option_' + option.name) %></label>
								</span>

								<b>{$config.currency_symbol}</b><span id="price_<%= option.name %>"><%= option.price %></span>
							</div>
						</div>

						<%
						});
						%>
					</td>
				</tr>
			</table>
		</script>

		<div id="visual_options" class="visual_options" style="display: none;"></div>

		{if isset($listing) && isset($listing.deep_links)}
			{assign var="deep_links" value=$listing.deep_links}
		{/if}

		{foreach from=$plans key="key" item="plan"}
			{if $plan.deep_links > 0}
				<div id="deep_links_{$plan.id}" class="deep_links" style="display: none;">
				<table cellspacing="0" cellpadding="0" width="100%" class="striped">
				<tr>
					<td width="200"><strong>{$esynI18N.deep_links}</strong></td>
					<td>
{section name="deep" loop=$plan.deep_links}
	{assign var="index" value=$smarty.section.deep.index}

	{if isset($deep_links) && isset($deep_links.$index)}
		{assign var="deep_title" value=$deep_links.$index.title}
		{assign var="deep_url" value=$deep_links.$index.url}
		{assign var="deep_id" value=$deep_links.$index.id}
	{else}
		{assign var="deep_title" value=""}
		{assign var="deep_url" value=""}
		{assign var="deep_id" value=""}
	{/if}

	<div class="deep_link_box">
		<label>{$esynI18N.title}:&nbsp;<input size="25" type="text" class="common" name="deep_links[{$plan.id}][{$index}][title]" value="{if isset($deep_title)}{$deep_title}{/if}"></label>&nbsp;
		<label>{$esynI18N.url}:&nbsp;<input size="35" type="text" class="common" name="deep_links[{$plan.id}][{$index}][url]" value="{if isset($deep_url)}{$deep_url}{/if}"></label>

		{if isset($deep_id) && !empty($deep_id)}
			<input type="button" class="remove_deep common" name="remove_deep" id="deep_{$deep_id}" value="{$esynI18N.delete}">
		{/if}
	</div>
{/section}
					</td>
				</tr>
				</table>
				</div>
			{/if}
		{/foreach}
	{/if}

	<table cellspacing="0" width="100%" class="striped" id="expire_table">
	<tr>
		<td class="tip-header" id="tip-header-expire_period" width="200"><strong>{$esynI18N.expiration_period}:</strong></td>
		<td>
			<input type="text" name="expire" id="expire" class="common" value="{if isset({$listing.expire_date})}{$listing.expire_date}{elseif isset($smarty.post.expire)}{$smarty.post.expire}{/if}">
		</td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-expire_notification"><strong>{lang key="expire_notif"}:</strong></td>
		<td><input type="text" name="expire_notif" id="expire_notif" class="common" value="{if isset($listing.expire_notif) && $listing.expire_notif > 0}{$listing.expire_notif}{elseif isset($smarty.post.expire_notif)}{$smarty.post.expire_notif}{else}{$config.expire_notif}{/if}">&nbsp;{if isset($listing) && !empty($listing) && $listing.expire_notif > 0 && '0000-00-00 00:00:00' != $listing.expire_date}{lang key="listing_notif_will_send"}&nbsp;<strong>{$listing.expire_notif_date|date_format:$config.date_format}</strong>{/if}</td>
	</tr>
	<tr>
		<td width="200"><strong>{$esynI18N.cron_for_expiration}:</strong></td>
		<td>
			<select name="expire_action">
				<option value="" {if isset($listing.expire_action) && $listing.expire_action == ''}selected="selected"{elseif isset($smarty.post.expire_action) && $smarty.post.expire_action == ''}selected="selected"{elseif $config.expire_action == ''}selected="selected"{/if}>{$esynI18N.nothing}</option>
				<option value="remove" {if isset($listing.expire_action) && $listing.expire_action == 'remove'}selected="selected"{elseif isset($smarty.post.expire_action) && $smarty.post.expire_action == 'remove'}selected="selected"{elseif $config.expire_action == 'remove'}selected="selected"{/if}>{$esynI18N.remove}</option>
				<optgroup label="Status">
					<option value="approval" {if isset($listing.expire_action) && $listing.expire_action == 'approval'}selected="selected"{elseif isset($smarty.post.expire_action) && $smarty.post.expire_action == 'approval'}selected="selected"{elseif $config.expire_action == 'approval'}selected="selected"{/if}>{$esynI18N.approval}</option>
					<option value="banned" {if isset($listing.expire_action) && $listing.expire_action == 'banned'}selected="selected"{elseif isset($smarty.post.expire_action) && $smarty.post.expire_action == 'banned'}selected="selected"{elseif $config.expire_action == 'banned'}selected="selected"{/if}>{$esynI18N.banned}</option>
					<option value="suspended" {if isset($listing.expire_action) && $listing.expire_action == 'suspended'}selected="selected"{elseif isset($smarty.post.expire_action) && $smarty.post.expire_action == 'suspended'}selected="selected"{elseif $config.expire_action == 'suspended'}selected="selected"{/if}>{$esynI18N.suspended}</option>
				</optgroup>
				<optgroup label="Type">
					<option value="regular" {if isset($listing.expire_action) && $listing.expire_action == 'regular'}selected="selected"{elseif isset($smarty.post.expire_action) && $smarty.post.expire_action == 'regular'}selected="selected"{elseif $config.expire_action == 'regular'}{/if}>{$esynI18N.regular}</option>
					<option value="featured" {if isset($listing.expire_action) && $listing.expire_action == 'featured'}selected="selected"{elseif isset($smarty.post.expire_action) && $smarty.post.expire_action == 'featured'}selected="selected"{elseif $config.expire_action == 'featured'}selected="selected"{/if}>{$esynI18N.featured}</option>
					<option value="partner" {if isset($listing.expire_action) && $listing.expire_action == 'partner'}selected="selected"{elseif isset($smarty.post.expire_action) && $smarty.post.expire_action == 'partner'}selected="selected"{elseif $config.expire_action == 'partner'}selected="selected"{/if}>{$esynI18N.partner}</option>
				</optgroup>
			</select>
		</td>
	</tr>
	</table>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.assign_account}:</strong></td>
		<td>
			<input type="radio" name="assign_account" value="1" id="a1" {if isset($smarty.post.assign_account) && $smarty.post.assign_account == '1'}checked="checked"{/if} /><label for="a1">&nbsp;{$esynI18N.new_account}</label>
			<input type="radio" name="assign_account" value="2" id="a2" {if isset($smarty.post.assign_account) && $smarty.post.assign_account == '2'}checked="checked"{elseif isset($smarty.get.do) && $smarty.get.do == 'edit' && isset($account) && !empty($account)}checked="checked"{/if} /><label for="a2">&nbsp;{$esynI18N.existing_account}</label>
			<input type="radio" name="assign_account" value="3" id="a3" {if isset($smarty.post.assign_account) && $smarty.post.assign_account == '3'}checked="checked"{/if} /><label for="a3">&nbsp;{lang key="reset_account"}</label>
			<input type="radio" name="assign_account" value="0" id="a0" {if isset($smarty.post.assign_account) && $smarty.post.assign_account == '0'}checked="checked"{elseif !$smarty.post && !isset($account)}checked="checked"{/if} /><label for="a0">&nbsp;{$esynI18N.dont_assign}</label>

			<div id="exist_account" style="display:none;">
				<div id="accounts_list">{if isset($account) && !empty($account)}{$account.id}|{$account.username}{/if}</div>
			</div>
			<div id="new_account" style="display:none;">
				<table border="0">
				<tr>
					<td>{$esynI18N.username}:</td>
					<td><input type="text" name="new_account" size="45" class="common" value="{if isset($smarty.post.new_account)}{$smarty.post.new_account}{/if}"></td>
				</tr>
				<tr>
					<td>{$esynI18N.email}:</td>
					<td><input type="text" name="new_account_email" size="45" class="common" value="{if isset($smarty.post.new_account_email)}{$smarty.post.new_account_email}{/if}"></td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	</table>
	</fieldset>

	<div style="height: 15px;"></div>

	<fieldset>
	<legend><strong>{$esynI18N.additional_fields}</strong></legend>	
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.listing_status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($listing.status) && $listing.status == 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="approval" {if isset($listing.status) && $listing.status == 'approval'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'approval'}selected="selected"{/if}>{$esynI18N.approval}</option>
				<option value="banned" {if isset($listing.status) && $listing.status == 'banned'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'banned'}selected="selected"{/if}>{$esynI18N.banned}</option>
				<option value="suspended" {if isset($listing.status) && $listing.status == 'suspended'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'suspended'}selected="selected"{/if}>{$esynI18N.suspended}</option>
				<option value="deleted" {if isset($listing.deleted) && $listing.status == 'deleted'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'deleted'}selected="selected"{/if}>{$esynI18N.deleted}</option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="tip-header first" id="tip-header-rank"><strong>{$esynI18N.rank}:</strong></td>
		<td>
			<select name="rank">
				{section name="listing_rank" loop="6"}
					<option value="{$smarty.section.listing_rank.index}" {if isset($listing.rank) && $listing.rank == $smarty.section.listing_rank.index}selected="selected"{/if}>{$smarty.section.listing_rank.index}</option>
				{/section}
			</select>
		</td>
	</tr>

	<tr>
		<td class="tip-header first"><strong>{$esynI18N.pagerank}:</strong></td>
		<td>
			<select name="pagerank" id="pagerank">
				{section name="listing_pagerank" loop="11"}
					<option value="{$smarty.section.listing_pagerank.index}" {if isset($listing.pagerank) && $listing.pagerank == $smarty.section.listing_pagerank.index}selected="selected"{/if}>{$smarty.section.listing_pagerank.index}</option>
				{/section}
			</select>

			<a href="#" id="get_current_pagerank">{lang key='get_pagerank'}</a>
		</td>
	</tr>

	</table>
	</fieldset>

	</table>

	<div style="height: 20px;"></div>
	<table cellspacing="0" width="100%" class="striped">
	<tr>
		<td style="padding: 0 0 0 11px; width: 1%">
			<input type="checkbox" name="send_email" id="send_email" checked="checked">&nbsp;<label for="send_email">{$esynI18N.email_notif}?</label>&nbsp;|&nbsp;
			<input type="submit" name="save" class="common" value="{if isset($smarty.get.do) && $smarty.get.do == 'edit'}{$esynI18N.save}{else}{$esynI18N.create_listing}{/if}">

			{if isset($smarty.get.do) && $smarty.get.do == 'edit'}

				<input type="submit" name="delete" id="delete" class="common" value="{$esynI18N.remove}">

				{if isset($smarty.server.HTTP_REFERER) && stristr($smarty.server.HTTP_REFERER, 'browse')}
					<input type="hidden" name="goto" value="browse">
				{else}
					<input type="hidden" name="goto" value="list">
				{/if}
			{else}
				<span><strong>{$esynI18N.and_then}</strong></span>
				<select name="goto">
					<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>{$esynI18N.go_to_list}</option>
					<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto == 'add'}selected="selected"{/if}>{$esynI18N.add_another_one}</option>
					<option value="addtosame" {if isset($smarty.post.goto) && $smarty.post.goto == 'addtosame'}selected="selected"{/if}>{$esynI18N.add_another_one_to_same}</option>
				</select>
			{/if}
		</td>
	</tr>

	</table>
	<input type="hidden" name="do" value="{if isset($smarty.get.do) && $smarty.get.do == 'edit'}{$smarty.get.do}{/if}">
	<input type="hidden" name="old_alias" value="{if isset($listing.old_alias)}{$listing.old_alias}{/if}">
	</form>
{/if}

<div style="display: none;">
	<div id="tip-content-rank">{$esynI18N.rank_listing_option}</div>
	<div id="tip-content-expire_notification">{$esynI18N.tooltip_expire_notification}</div>
	<div id="tip-content-expire_period">{$esynI18N.tooltip_expire_period}</div>
</div>

{ia_hooker name="tplAdminSuggestListingBeforeIncludeJs"}

{include_file js="js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/jquery/plugins/lightbox/jquery.lightbox, js/ckeditor/ckeditor, js/jquery/plugins/jquery.charCount, js/admin/suggest-listing, js/utils/underscore-min"}

{ia_hooker name="tplAdminSuggestListingAfterIncludeJs"}

{include file='box-footer.tpl'}

{include file='footer.tpl'}