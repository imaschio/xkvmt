{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if isset($smarty.get.do) && ($smarty.get.do == 'add' || $smarty.get.do == 'edit')}
	
	{include file='box-header.tpl' title=$gTitle}
	
	<form action="controller.php?plugin=rss&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
	
	{preventCsrf}
	
	<table width="100%" cellpadding="0" cellspacing="0" class="striped">

	<tr>
		<td width="150"><strong>{lang key='title'}</strong></td>
		<td><input type="text" class="common" name="title" size="32" value="{if isset($rss.title)}{$rss.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
	</tr>

	<tr>
		<td><strong>{lang key='rss_url'}</strong></td>
		<td><input type="text" class="common" name="url" size="32" value="{if isset($rss.url)}{$rss.url}{elseif isset($smarty.post.url)}{$smarty.post.url}{/if}" /></td>
	</tr>
	
	<tr>
		<td><strong>{lang key='rss_num_items'}</strong></td>
		<td><input type="text" class="common" name="num" size="32" value="{if isset($rss.num)}{$rss.num}{elseif isset($smarty.post.num)}{$smarty.post.num}{/if}" /></td>
	</tr>

	<tr>
		<td><strong>{lang key='rss_refresh_time'}</strong></td>
		<td><input type="text" class="common" name="refresh" size="32" value="{if isset($rss.refresh)}{$rss.refresh}{elseif isset($smarty.post.refresh)}{$smarty.post.refresh}{else}600{/if}" /></td>
	</tr>
	
	<tr>
		<td width="150"><strong>{lang key='category'}:</strong></td>
		<td>
			<div id="tree"></div>
			<label><input type="checkbox" name="recursive" value="1" {if isset($rss.recursive) && $rss.recursive == '1'}checked="checked"{elseif isset($smarty.post.recursive) && $smarty.post.recursive == '1'}checked="checked"{/if} />&nbsp;{lang key='include_subcats'}</label>
		</td>
	</tr>

	<tr>
		<td><strong>{lang key='status'}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($rss.status) && $rss.status == 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'active'}selected="selected"{/if}>{lang key='active'}</option>
				<option value="inactive" {if isset($rss.status) && $rss.status == 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'inactive'}selected="selected"{/if}>{lang key='inactive'}</option>
			</select>
		</td>
	</tr>

	{if isset($smarty.get.do) && $smarty.get.do == 'add'}
		<tr>
			<td><span>{$gTitle} <strong>{lang key='and_then'}</strong></span></td>
			<td>
				<select name="goto">
					<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>{lang key='go_to_list'}</option>
					<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto == 'add'}selected="selected"{/if}>{lang key='add_another_one'}</option>
				</select>
			</td>
		</tr>
	{/if}

	<tr class="all">
		<td colspan="2">
			<input type="hidden" name="categories" id="categories" value="{$selected_categories}" />
			{if $smarty.get.do == 'edit'}<input type="hidden" name="block_id" value="{$rss.id_block}" />{/if}
			<input type="submit" name="save" class="common" value="{if $smarty.get.do == 'edit'}{lang key='save_changes'}{else}{lang key='add'}{/if}" />
		</td>
	</tr>
	</table>
	</form>
	
	{include file='box-footer.tpl'}
{else}
	<div id="box_rss" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/rss/js/admin/rss"}

{include file='footer.tpl'}