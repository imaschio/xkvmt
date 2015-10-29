{include file="header.tpl"}

{include file="box-header.tpl" title=$gTitle}

<form method="post" action="controller.php?plugin=import_csv" enctype="multipart/form-data">
{preventCsrf}
<table class="striped" width="100%" cellpadding="4" cellspacing="0">
<tr>
	<td width="15%"><strong>{$esynI18N.csv_file}:</strong></td>
	<td width="85%"><input name="csvfile" type="file" class="common"/></td>
</tr>
<tr>
	<td><strong>{$esynI18N.delimiter}:</strong></td>
	<td><input type="text" name="delimeter" size="5" class="common" />&nbsp;({$esynI18N.for_tab_delimiter})</td>
</tr>
<tr>
	<td colspan="2"><input type="submit" name="parse" class="common" value="{$esynI18N.parse}" /></td>
</tr>
</table>
</form>
{include file="box-footer.tpl" class="box"}

{if isset($csvData) && !empty($csvData)}
{include file="box-header.tpl" title=$gTitle}
<div style="height: 600px; overflow: scroll;">
	<form method="post" action="controller.php?plugin=import_csv&amp;do=add" id="result">
	{preventCsrf}
		<table class="striped" width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td>&nbsp;</td>
					{$csvFieldsMenus}
				</tr>
				{foreach from=$csvData key=key item=csv}
					<tr>
					<td><input type="checkbox" name="index[{$key}]" value="1" checked="checked" /></td>
					{foreach from=$csv item=row}
						<td><input type="hidden" value="{$row}" name="csv[{$key}][]" />{$row}</td>
					{/foreach}
					</tr>
				{/foreach}
			<tr>
				<td>{print_img fl='arrow_ltr.png' pl='import_csv' admin='true' full='true' alt='$esynI18N.arrow'}</td>
				<td colspan="{$csvNumRows}" style="vertical-align: center;">
					<a href="javascript:checkAll();">{$esynI18N.check_all}</a>&nbsp;&nbsp;
					<a href="javascript:uncheckAll();">{$esynI18N.uncheck_all}</a>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="{$csvNumRows}" style="vertical-align: center;">
					<input type="hidden" id="category_id" name="category_id" value="0" />
					<div id="category_title">{$esynI18N.imported_links_to}&nbsp;<a href="#" id="show_tree_cats" onclick="showTree(); return false;"><b>{$esynI18N.root}</b></a></div>
					{$esynI18N.with_status}&nbsp;
					<select name="status">
						{foreach from=$statuses key=key item=status}
							<option value="{$key}">{$status}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="{$csvNumRows}"><input class="common" style="width:155px;" type="submit" name="import" value="{$esynI18N.import}" /></td>
			</tr>
		</table>
	</form>
</div>
{include file="box-footer.tpl" class="box"}
{/if}

{include_file js="plugins/import_csv/js/admin/csv"}

{include file='footer.tpl'}