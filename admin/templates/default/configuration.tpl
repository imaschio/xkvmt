{include file="header.tpl" css="js/ext/plugins/fileuploadfield/css/file-upload"}

<a name="top"></a>

{include file="box-header.tpl" title=$esynI18N.htaccess_file id="htaccess" hidden="true"}
{if isset($htaccess_code) && !empty($htaccess_code)}
	<form action="controller.php?file=configuration" enctype="multipart/form-data" id="htaccess_form" method="post">
	{preventCsrf}

		<a class="button save" href="#">{$esynI18N.save}</a>&nbsp;
		<a class="button close" href="#">{$esynI18N.close}</a>&nbsp;
		<a class="button rebuild" href="#">{$esynI18N.rebuild_htaccess}</a>&nbsp;
		<a class="button copy" href="#">{$esynI18N.copy_htaccess}</a>&nbsp;

		<div id="htaccess_code" style="border: 1px solid rgb(157, 157, 161); margin: 10px 0 10px 0; padding-left: 10px;">
			{foreach from=$htaccess_code key="key" item="code"}
				<p style="margin-top: 10px;">
					<label for="section_{$key}"><strong>SECTION #{$key}:</strong></label>
					<textarea id="section_{$key}" class="common" name="sections[{$key}]" rows="6" cols="45">{$code}</textarea><br />
				</p>
			{/foreach}
		</div>

		<a class="button save" href="#">{$esynI18N.save}</a>&nbsp;
		<a class="button close" href="#">{$esynI18N.close}</a>&nbsp;
		<a class="button rebuild" href="#">{$esynI18N.rebuild_htaccess}</a>&nbsp;
		<a class="button copy" href="#">{$esynI18N.copy_htaccess}</a>&nbsp;

		<input type="hidden" name="do" value="save_htaccess">

	</form>
{/if}
{include file='box-footer.tpl'}

{include file="box-header.tpl" title=$esynI18N.config_groups id="options"}

<div class="config-col-left">
	<ul class="groups">
	{foreach from=$groups key=key item=group_item name=groups}
		{if isset($group) && $group == $key}
			<li><div>{$group_item}</div></li>
		{else}
			<li><a href="controller.php?file=configuration&amp;group={$key}">{$group_item}</a></li>
		{/if}
	{/foreach}
	</ul>
</div>

<div class="config-col-right">
{if isset($params)}
	<form action="controller.php?file=configuration&amp;group={$group}" enctype="multipart/form-data" method="post">
		{preventCsrf}
		<table cellspacing="0" class="striped">

		{foreach from=$params key=key item=value}
			{if !empty($value.show)}
				{assign var='field_show' value=explode('|', $value.show)}
			{else}
				{assign var='field_show' value=null}
			{/if}

			{if empty($field_show[0]) || (!empty($field_show[0]) && $config.{$field_show[0]} == $field_show[1])}
				{if $value.type == "password"}
					<tr>
						<td class="tip-header" id="tip-header-{$value.name}">{$value.description|escape:'html'}</td>
						<td><input type="password" class="common" size="45" name="param[{$value.name}]" id="{$value.name}" value="{$value.value|escape:'html'}"></td>
					</tr>
				{elseif $value.type == "text"}
					<tr>
						<td class="tip-header" id="tip-header-{$value.name}" width="25%">{$value.description|escape:'html'}</td>
						{if $value.name == 'expire_action'}
							<td>
								<select name="param[expire_action]" class="common">
									<option value="" {if $value.value == ''}selected="selected"{/if}>{$esynI18N.nothing}</option>
									<option value="remove" {if $value.value == 'remove'}selected="selected"{/if}>{$esynI18N.remove}</option>
									<optgroup label="Status">
										<option value="approval" {if $value.value == 'approval'}selected="selected"{/if}>{$esynI18N.approval}</option>
										<option value="banned" {if $value.value == 'banned'}selected="selected"{/if}>{$esynI18N.banned}</option>
										<option value="suspended" {if $value.value == 'suspended'}selected="selected"{/if}>{$esynI18N.suspended}</option>
									</optgroup>
									<optgroup label="Type">
										<option value="regular" {if $value.value == 'regular'}selected="selected"{/if}>{$esynI18N.regular}</option>
										<option value="featured" {if $value.value == 'featured'}selected="selected"{/if}>{$esynI18N.featured}</option>
										<option value="partner" {if $value.value == 'partner'}selected="selected"{/if}>{$esynI18N.partner}</option>
									</optgroup>
								</select>
							</td>
						{elseif $value.name == 'captcha_preview'}
							{if isset($captcha_preview) && !empty($captcha_preview)}
								<td>{$captcha_preview}</td>
							{else}
								<td>{$esynI18N.no_captcha_preview}</td>
							{/if}
						{else}
							<td><input type="text" size="45" name="param[{$value.name}]" class="common" id="{$value.name}" value="{$value.value|escape:'html'}"></td>
						{/if}
					</tr>
				{elseif $value.type == "textarea"}
					<tr>
						<td class="tip-header" id="tip-header-{$value.name}">{$value.description|escape:'html'}</td>
						<td><textarea name="param[{$value.name}]" id="{$value.name}" class="{if $value.editor == '1'}cked {/if}common" cols="45" rows="7">{$value.value|escape:'html'}</textarea></td>
					</tr>
				{elseif $value.type == "image"}
					<tr>
						<td class="tip-header" id="tip-header-{$value.name}">{$value.description|escape:'html'}</td>
						<td>
							{if !is_writeable($smarty.const.IA_HOME|cat:$smarty.const.IA_DS|cat:'uploads')}
								<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i>{$esynI18N.upload_writable_permission}</i></div>
							{else}
								<input type="hidden" name="param[{$value.name}]">
								<input type="file" name="{$value.name}" id="conf_{$value.name}" class="common" size="42">
							{/if}

							{if $value.value != ''}
								<a href="#" class="view_image">{$esynI18N.view_image}</a>&nbsp;
								<a href="#" class="remove_image">{$esynI18N.remove} {$esynI18N.image}</a>
							{/if}
						</td>
					</tr>
				{elseif $value.type == "checkbox"}
					<tr>
						<td class="tip-header" id="tip-header-{$value.name}">{$value.description|escape:'html'}</td>
						<td class="fields-pages">
							{foreach from=","|explode:$value.multiple_values key=key item=value2}
								<label for="{$value.name}_{$key}"><input type="checkbox" name="param[{$value.name}][]" id="{$value.name}_{$key}" value="{$key}" {if in_array($key, ","|explode:$value.value)}checked="checked"{/if} /> {$value2|trim:"'"}</label>
							{/foreach}
						</td>
					</tr>
				{elseif $value.type == "radio"}
					<tr>
						<td class="tip-header" id="tip-header-{$value.name}" width="250">{$value.description|escape:'html'}</td>
						<td>{html_radio_switcher value=$value.value name=$value.name conf=true}</td>
					</tr>
				{elseif $value.type == "select"}
					<tr>
						<td class="tip-header" id="tip-header-{$value.name}">{$value.description|escape:'html'}</td>

						{if $value.name == 'tmpl'}
							{assign var="array_res" value=$templates}
						{elseif $value.name == 'admin_tmpl'}
							{assign var="array_res" value=$admin_templates}
						{elseif $value.name == 'lang'}
							{assign var="array_res" value=$langs}
						{else}
							{assign var="array_res" value=","|explode:$value.multiple_values}
						{/if}

						<td>
							<select name="param[{$value.name}]" class="common" {if $array_res|@count == 1}disabled="disabled"{/if}>
								{foreach from=$array_res key=key item=value2}
									<option value="{if $value.name == 'lang'}{$key}{else}{$value2|trim:"'"}{/if}" {if ($value.name == 'lang' && $key == $value.value) || $value2|trim:"'" == $value.value}selected="selected"{/if}>{$value2|trim:"'"}</option>
								{/foreach}
							</select>
						</td>
					</tr>
				{elseif $value.type == "divider"}
					<tr>
						<td colspan="2" class="caption"><strong>{$value.value|escape:'html'}</strong>{if !empty($value.name)}<a name="{$value.name}"></a>{/if}</td>
					</tr>
				{/if}
			{/if}
		{/foreach}

		<tr class="all">
			<td colspan="2"><input type="submit" name="save" id="save" class="common" value="{$esynI18N.save_changes}"></td>
		</tr>
		</table>
	</form>
{/if}
</div>

{include file='box-footer.tpl'}

<a name="bottom"></a>

{include_file js="js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/ext/plugins/fileuploadfield/FileUploadField, js/ckeditor/ckeditor, js/utils/zeroclipboard/ZeroClipboard.min, js/admin/configuration"}

{include file='footer.tpl'}