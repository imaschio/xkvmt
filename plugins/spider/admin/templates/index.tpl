{include file="header.tpl"}

{if !$error}
	{include file='box-header.tpl' title=$gTitle}
	<form method="post" action="controller.php?plugin=spider&amp;action=get" id="search_form">
	{preventCsrf}
		<table class="striped" width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td width="200"><strong>{$esynI18N.search_in}:</strong></td>
				<td>
					<select name="engine" id="engine">
						<option value="google" {if isset($smarty.post.engine) and $smarty.post.engine == 'google'}selected="selected"{/if}>{$esynI18N.google}</option>
						{*
						<option value="yahoo" {if isset($smarty.post.engine) and $smarty.post.engine == 'yahoo'}selected="selected"{/if}>{$esynI18N.yahoo}</option>
						<option value="bing" {if isset($smarty.post.engine) and $smarty.post.engine == 'bing'}selected="selected"{/if}>{$esynI18N.bing}</option>
						*}
					</select>
				</td>
			</tr>
			<tr>
				<td width="200"><strong>{$esynI18N.keywords}:</strong></td>
				<td><input type="text" name="keywords" size="50" class="common" value="{if isset($smarty.post.keywords)}{$smarty.post.keywords}{/if}" /></td>
			</tr>
			<tr class="number_of_results">
				<td width="200"><strong>{$esynI18N.number_of_links}:</strong></td>
				<td>
					<select name="count" id="search_numbers">
						{section name="count" start=10 loop=60 step=10}
							<option value="{$smarty.section.count.index}" {if isset($smarty.post.count) and $smarty.section.count.index == $smarty.post.count}selected="selected" {/if}>{$smarty.section.count.index}</option>
						{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="start" value="{if isset($result.start)}{$result.start}{else}0{/if}" id="search_start" />
					<input type="submit" name="continue" value="{$esynI18N.continue}" class="common" id="continue" />
				</td>
			</tr>
		</table>
	</form>
	{include file="box-footer.tpl" class="box"}
{/if}

{if isset($result) && !empty($result)}
	{include file='box-header.tpl' title=$gTitle}

	<div class="num_results">
		<div style="float:left;font-weight:bold;line-height:30px;">
			{$esynI18N.about|cat:"&nbsp;"|cat:$result.num_results|cat:"&nbsp;"|cat:$esynI18N.found}
		</div>
		<div style="float:left;position:absolute;left:39%;">
			{if $result.start > 0}
				<div style="float:left;padding-right:5px;">
					<input type="button" name="prev" value="{$esynI18N.prev}" class="common" id="search_prev" />
				</div>
			{/if}
			<div style="float:left;text-align:center;font-weight:bold;line-height:30px;">
				{assign var="curr_page" value=$result.start+1}
				{$esynI18N.page|cat:":&nbsp;"}{$curr_page}
			</div>
			{if $result.all_pages != $result.start + 1}
				<div style="float:left;padding-left:5px;">
					<input type="button" name="next" value="{$esynI18N.next}" class="common" id="search_next" />
				</div>
			{/if}
		</div>
		<div style="clear:both;font-size:0;">&nbsp;</div>
	</div>

	<div style="height: 600px; overflow: scroll; margin-top:20px;">
		<form method="post" action="controller.php?plugin=spider&amp;action=add" id="result">
			{preventCsrf}
			<table class="striped" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<th>&nbsp;</th>
					<th><center><b>{$esynI18N.url}</b></center></th>
					<th><center><b>{$esynI18N.title}</b></center></th>
					<th><center><b>{$esynI18N.description}</b></center></th>
					<th><center><b>{$esynI18N.category}</b></center></th>
				</tr>
				{foreach from=$result key=key item=data}
					{if is_array($data)}
						<tr>
							<td width="1%" style="vertical-align: top;"><input type="checkbox" name="index[{$key}]" checked="checked" class="common" /></td>
							<td width="19%" style="vertical-align: top;"><input type="text" name="urls[]" value="{$data.url|escape:'html'}" class="common" size="35" /><a href="{$data.url|escape:'html'}" target="_blank"/>{$esynI18N.visit_this_site}</a></td>
							<td width="20%"><textarea name="titles[]" rows="3" cols="5" class="common">{$data.title}</textarea></td>
							<td width="40%"><textarea name="descriptions[]" rows="3" cols="5" class="common">{$data.description}</textarea></td>
							<td width="20%" style="vertical-align: top;"><select name="categories[]" class="common">{$categories}</select></td>
						</tr>
					{/if}
				{/foreach}
				<tr>
					<td>{print_img fl='arrow_ltr.png' pl='spider' admin='true' alt='$esynI18N.arrow' full='true'}</td>
					<td style="vertical-align: center;">
						<a id="check_all" href="#">{$esynI18N.check_all}</a>&nbsp;/&nbsp;
						<a id="uncheck_all" href="#">{$esynI18N.uncheck_all}</a>
					</td>
				</tr>
				<tr>
					<td colspan="5" style="vertical-align: center;">
						<input type="checkbox" name="one_import" />&nbsp;
						{$esynI18N.import_to_one_cat}:&nbsp;
						<select name="one_category" class="common">{$categories}</select>&nbsp;
						{$esynI18N.with_status}&nbsp;
						<select name="status" class="common">
						{foreach from=$statuses key=key item=status}
							<option value="{$key}">{$status}</option>
						{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="4"><input type="submit" class="common" name="import" value="{$esynI18N.import_selected_links}" /></td>
				</tr>
			</table>
		</form>
	</div>
	{include file="box-footer.tpl" class="box"}
{/if}

{if !$error}
	{include_file js="plugins/spider/js/admin/spider"}
{/if}

{include file='footer.tpl'}