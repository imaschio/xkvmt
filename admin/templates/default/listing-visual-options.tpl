{include file="header.tpl" css="js/ext/plugins/fileuploadfield/css/file-upload, js/ext/plugins/colorpicker/css/colorpicker, js/ext/plugins/spinner/css/Spinner"}

{include file='box-header.tpl' title=$gTitle}

<form action="controller.php?file=listing-visual-options" method="post" enctype="multipart/form-data">
{preventCsrf}
<table cellspacing="0" cellpadding="10" width="100%" class="common vo-options">
	<tbody>
		<tr>
			<th width="60px" class="first"><b>{$esynI18N.enabled}</b></th>
			<th width="240px"><b>{$esynI18N.description}</b></th>
			<th width="60px" class="text-center"><b>{$esynI18N.price}</b></th>
			<th><b>{$esynI18N.example}</b></th>
			<th width="100px"><b>{$esynI18N.actions}</b></th>
		</tr>

		{foreach $options as $option}
			<tr class="vo-option-row {if $option@index is even}even{else}odd{/if}">
				<td class="first vo-enabled">
					<input type="hidden" name="options[{$option.name}]" value="1">
					<input type="checkbox" name="show[{$option.name}]" {if $option.show}checked="checked"{/if}>
				</td>
				<td class="vo-name">{lang key="listing_option_{$option.name}"}</td>
				<td class="text-center vo-price">{$config.currency_symbol} <input type="text" name="price[{$option.name}]" size="3" value="{if $option.price}{$option.price}{/if}" class="common"></td>
				<td class="vo-example">
					<input type="hidden" value="{$option.value}" name="{$option.name}">
					{if 'add_star' == $option.name}
						<div id="preview" class="preview">
							<img id="preview-add_star" src="{$smarty.const.IA_URL}{$option.value}" alt="{$option.name}">
						</div>
					{elseif 'add_badge' == $option.name}
						<div id="preview_badge" class="preview">
							<img id="preview-add_badge" src="{$smarty.const.IA_URL}{$option.value}" alt="{$option.name}">
						</div>
					{elseif 'highlight' == $option.name}
						<div id="highlight"></div>
					{elseif 'link_big' == $option.name}
						<div class="preview">
							<div id="link_size"></div>
							<a id="link_big" href="#" style="font-size: {$option.value}px">{$esynI18N.link_title}</a>
						</div>
					{elseif 'color_link' == $option.name}
						<div id="color"></div>
					{/if}
				</td>
				<td>
					{if 'add_star' == $option.name}
						<a href="#" id="add_star" class="vo-option-change" name="{$option.name}">{$esynI18N.change}</a>
					{elseif 'add_badge' == $option.name}
						<a href="#" id="add_badge" class="vo-option-change" name="{$option.name}">{$esynI18N.change}</a>
					{else}
						&nbsp;
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>

<div style="margin: 20px 0 10px;">
	<input type="hidden" id="upload_type" value="">
	<input type="submit" class="common" name="save" value="{$esynI18N.save}">
</div>
</form>

{include file='box-footer.tpl'}

{include_file js="js/admin/listing-visual-options, /js/ext/plugins/fileuploadfield/FileUploadField, /js/ext/plugins/colorpicker/ColorPicker, /js/ext/plugins/colorpicker/ColorPickerField, /js/ext/plugins/colorpicker/ColorMenu, /js/ext/plugins/spinner/Spinner, /js/ext/plugins/spinner/SpinnerField"}
{include file='footer.tpl'}