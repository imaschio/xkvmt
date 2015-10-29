{include file="header.tpl"}

{include file="box-header.tpl" class="box" title=$gTitle style="margin-bottom: 10px;"}

<form method="post" action="controller.php?plugin=crawl" name="nFCrawl">
{preventCsrf}
<table class="striped" border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="10%">
			<b>{$esynI18N.list_of_urls}:</b>
		</td>
		<td width="30%">
			<textarea name="urls" cols="45" rows="6" class="common">{if isset($smarty.post.urls) && !empty($smarty.post.urls)}{$smarty.post.urls}{/if}</textarea>
		</td>
	</tr>
	<tr>
		<td><b>Force set encoding for convert:</b></td>
		<td><input type="checkbox" name="force_encode" id="force_encode" class="common" /></td>
	</tr>
</table>
<div style="display:none;" id="force_encode_div">
	<table class="striped" border="0" width="100%" cellpadding="4" cellspacing="0">
		<tr>
			<td width="10%">
				<b>Site encoding: </b>
			</td>
			<td width="30%">
				<input type="text" name="encoding" value="" class="common" />
				Look <a href="http://en.wikipedia.org/wiki/Character_encoding" target="_blank">HERE</a> about ancoding 
			</td>
		</tr>
	</table>
</div>
<div align="middle">
	<table class="striped" border="0" width="100%" cellpadding="4" cellspacing="0">
		<tr>
			<td width="10%">
				<input type="submit" class="common" name="parse" value="{$esynI18N.parse}" />
			</td>
		</tr>
	</table>	
</div>
</form>

{include file="box-footer.tpl" class="box"}

{if isset($result)}
	{include file="box-header.tpl" class="box" title=$gTitle style="margin-bottom: 10px;"}
		<form method="post" action="controller.php?plugin=crawl&amp;action=add" id="iFresult">
		{preventCsrf}
		<table class="striped" width="95%" cellpadding="4" cellspacing="0">
		<tr>
			<th>&nbsp;</th>
			<th>URL</th>
			<th>Title</th>
			<th>Description</th>
			<th>Keywords</th>
		</tr>

		{foreach from=$result key=key item=res}
			<tr>
				<td width="1%" style="vertical-align: top;"><input type="checkbox" name="index[{$key}]" class="cb" checked="checked" /></td>
				<td width="19%" style="vertical-align: top;"><input type="text" class="common" name="urls[]" value="{$res.url|escape:'html'}" size="35" /></td>
				<td width="20%"><textarea name="titles[]" class="common">{$res.title|escape:'html'}</textarea></td>
				<td width="40%"><textarea name="descriptions[]" class="common">{$res.description|escape:'html'}</textarea></td>
				<td width="20%"><textarea name="keywords[]" class="common">{$res.keywords|escape:'html'}</textarea></td>
			</tr>
		{/foreach}

		<tr>
			<td colspan="5" style="vertical-align: center;">
				<a onclick="javascript:$('input.cb').attr('checked','checked'); return false;" href="#">Check All</a>&nbsp;/&nbsp;<a onclick="javascript:$('input.cb').attr('checked',''); return false;" href="#">Uncheck All</a>
			</td>
		</tr>
		<tr>
			<td colspan="5" style="vertical-align: center;">
				<input type="hidden" id="category_id" name="category_id" value="" />
				<div style="float:left;padding-right: 5px;">Import selected links to</div><a href="#" id="category_tree"><b>ROOT</b></a></div>
				<div style="float:left;">with status&nbsp;</div>
				
				<select name="status">
					{foreach from=$statuses key=key item=status}
						echo "<option value="{$key}">{$status}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4"><input type="submit" class="common" name="import" value="Import selected links" /></td>
		</tr>
		</table>
		</form>
	{include file="box-footer.tpl" class="box"}
{/if}

{include_file js="plugins/crawl/js/admin/crawl"}

{include file='footer.tpl'}