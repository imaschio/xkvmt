{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if isset($smarty.get.do) && ($smarty.get.do eq 'add' || $smarty.get.do eq 'edit')}

	{include file="box-header.tpl" title=$gTitle}

	<form action="controller.php?plugin=polls&amp;do={$smarty.get.do}{if $smarty.get.do eq 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">

	{preventCsrf}

	<table width="100%" cellpadding="0" cellspacing="0" class="striped">

	<tr>
		<td width="150"><strong>{$esynI18N.language}:</strong></td>
		<td>
			<select name="lang" {if $langs|count eq 1}disabled="disabled"{/if}>
				{foreach from=$langs key=code item=lang}
					<option value="{$code}" {if isset($one_poll.lang) && $one_poll.lang eq $code}selected="selected"{/if}>{$lang}</option>
				{/foreach}
			</select>
		</td>
	</tr>

	<tr>
		<td width="150"><strong>{$esynI18N.categories}:</strong></td>
		<td>
			<div id="tree"></div>
			<label><input type="checkbox" name="recursive" value="1" {if isset($one_poll.recursive) && $one_poll.recursive eq '1'}checked="checked"{elseif isset($smarty.post.recursive) && $smarty.post.recursive eq '1'}checked="checked"{/if} />&nbsp;{$esynI18N.include_subcats}</label>

			<input type="hidden" name="categories" id="categories" value="{$categories_out}" />
		</td>
	</tr>

	<tr>
		<td width="100"><strong>{$esynI18N.title}</strong></td>
		<td><input type="text" class="common" name="title" size="32" value="{if isset($one_poll.title)}{$one_poll.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.expire_date}</strong></td>
		<td><input type="text" class="common" id="expires" name="expires" size="32" value="{if isset($one_poll.expires)}{$one_poll.expires}{elseif isset($smarty.post.expires)}{$smarty.post.expires}{elseif !isset($one_poll) && !$smarty.post}{{strtotime('+7 day')}|date_format:"%Y-%m-%d"}{/if}" /></td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.options}</strong></td>
		<td>
			{if !empty($one_poll.options)}
				{foreach from=$one_poll.options item=option}
					<div style="margin-bottom:5px">
						<span style="font-weight: bold;">{$esynI18N.title}</span>
						<input type="text" name="existant[{$option.id}]" size="25" value="{$option.title}" class="common" />
						(<b>{$option.votes}</b> votes)
					</div>
				{/foreach}
			{/if}
			{if !empty($smarty.post.options)}
				{foreach from=$smarty.post.options item=option}
					<div style="margin-bottom:5px">
						<span style="font-weight: bold;">{$esynI18N.title}</span>
						<input type="text" name="options[]" size="25" value="{$option}" class="common" />
					</div>
				{/foreach}
			{/if}

			<table cellspacing="0" width="100%" id="before">
				<tr>
					<td colspan="2">
						<a href="#" id="add_item"><strong>Add Option</strong></a>
					</td>
				</tr>
			</table>

			<div style="margin: 5px 0; display: none" id="value_item">
				<span style="font-weight: bold;">{$esynI18N.title}:</span>
				<input type="text" name="options[]" size="25" />
				<a href="#" id="remove_node">Remove</a>
			</div>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($one_poll.status) && $one_poll.status eq 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status eq 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="inactive" {if isset($one_poll.status) && $one_poll.status eq 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status eq 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
			</select>
		</td>
	</tr>

	{if isset($smarty.get.do) && $smarty.get.do eq 'add'}
		<tr>
			<td><span>{$gTitle} <strong>{$esynI18N.and_then}</strong></span></td>
			<td>
				<select name="goto">
					<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto eq 'list'}selected="selected"{/if}>{$esynI18N.go_to_list}</option>
					<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto eq 'add'}selected="selected"{/if}>{$esynI18N.add_another_one}</option>
				</select>
			</td>
		</tr>
	{/if}

	<tr class="all">
		<td colspan="2">
			<input type="submit" name="save" class="common" value="{if $smarty.get.do eq 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}" />
		</td>
	</tr>
	</table>
	</form>

	{include_file js=" js/ckeditor/ckeditor, plugins/polls/js/admin/edit"}

	{include file="box-footer.tpl"}
{else}
	<div id="box_polls" style="margin-top: 15px;"></div>
	{include_file js="js/intelli/intelli.grid,  js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/polls/js/admin/index"}
{/if}

{include file='footer.tpl'}