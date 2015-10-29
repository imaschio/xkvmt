{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer" title=$gTitle}

{if isset($smarty.get.do) && ($smarty.get.do == 'add' || $smarty.get.do == 'edit')}
	
	{include file='box-header.tpl' title=$gTitle}
	
	<form action="controller.php?file=search-filters&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post" enctype="multipart/form-data">

	{preventCsrf}
	
	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.category}:</strong></td>
		<td>
			<span id="category_title_container">
				<strong>
					{if isset($category.title)}<a href="controller.php?file=browse&amp;id={$category.id}">{$category.title}</a>
					{else}<a href="controller.php?file=browse&amp;id=0">ROOT</a>{/if}
				</strong>
				&nbsp;|&nbsp;<a href="#" id="change_category">{$esynI18N.change}...</a>
			</span>
			<p><input type="checkbox" name="recursive" id="recursive" value="1" {if $filter.childs}checked="checked"{elseif isset($smarty.post.recursive) && $smarty.post.recursive == '1'}checked="checked"{elseif !isset($filter) && empty($smarty.post)}checked="checked"{/if}/><label for="recursive">&nbsp;{$esynI18N.include_subcats}</label></p>
			<input type="hidden" id="category_id" name="category_id" value="{$category.id}" />
		</td>
	</tr>

	<tr>
		<td width="100"><strong>{$esynI18N.title}</strong></td>
		<td><input type="text" class="common" name="title" size="32" value="{if isset($filter.title)}{$filter.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.filter}:</strong></td>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" class="striped">
			<tr>
				<td width="300"><div id="div_filter"></div></td>
				<td width="25">
					<div>
						<input type="button" value="&gt;&gt;" id="delete_field" class="common small"/><br /><br />
						<input type="button" value="&lt;&lt;" id="add_field" class="common small" />
					</div>
				</td>

				<td><div id="div_fields"></div></td>
			</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($filter.status) && $filter.status == 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="inactive" {if isset($filter.status) && $filter.status == 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
			</select>
		</td>
	</tr>

	{if isset($smarty.get.do) && 'add' == $smarty.get.do}
		<tr>
			<td><span>{$gTitle} <strong>{$esynI18N.and_then}</strong></span></td>
			<td>
				<select name="goto">
					<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>{$esynI18N.go_to_list}</option>
					<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto == 'add'}selected="selected"{/if}>{$esynI18N.add_another_one}</option>
				</select>
			</td>
		</tr>
	{/if}

	<tr class="all">
		<td colspan="2">
			<input class="common" type="submit" id="save" onclick="return false;" value="{if isset($smart.get.do) && $smarty.get.do == 'add'}{lang key='create'}{else}{lang key='save'}{/if}" />
			<input type="hidden" name="save" value="{if isset($smart.get.do) && $smarty.get.do == 'add'}{lang key='create'}{else}{lang key='save'}{/if}" />
		</td>
	</tr>
	</table>

	<input type="hidden" id="filter_content" value="" name="filter_content" />
	<input type="hidden" name="id" value="{if isset($filter.id)}{$filter.id}{/if}" />
	<input type="hidden" name="old_path" value="{if isset($filter.old_path)}{$filter.old_path}{/if}" />

	<script type="text/javascript">
		var fields = {$fields};
		var filter = {$filter_menu};
	</script>

	</form>

	{include file='box-footer.tpl'}

	{include_file js="js/ext/plugins/treeserializer/ext.tree.serialize, js/ext/plugins/panelresizer/PanelResizer, js/admin/search-filters-manage"}
{else}
	<div id="box_search_filters" style="margin-top: 15px;"></div>
	{include_file js="js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/search-filters"}
{/if}

{include file='footer.tpl'}