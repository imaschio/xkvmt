{include file="header.tpl" js="js/jquery/plugins/iphoneswitch/jquery.iphone-switch"}

{if isset($smarty.get.do) && ($smarty.get.do == 'add' || $smarty.get.do == 'edit')}
	{include file='box-header.tpl' title=$gTitle}
	
	<form action="controller.php?file=menus&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" id="menu_form" method="post">
	{preventCsrf}
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td colspan="2" class="caption">{lang key="menus"}</td>
	</tr>
	<tr>
		<td width="150"><strong>{lang key="name"}:</strong></td>
		<td>
			<input type="text" name="name" id="name" size="24" class="common" value="{if isset($menu.name)}{$menu.name}{elseif isset($smarty.post.name)}{$smarty.post.name}{/if}" {if isset($smarty.get.do) && $smarty.get.do == 'edit'}readonly="readonly"{/if} />
			<div class="option_tip">{lang key="unique_name"}</div>
		</td>
	</tr>
	<tr>
		<td width="150"><label for="title"><strong>{lang key="title"}:</strong></label></td>
		<td><input class="common" type="text" value="{if isset($menu) && isset($menu.title)}{$menu.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" id="title" name="title"></td>
	</tr>
	<tr>
		<td><label for="position"><strong>{lang key="position"}:</strong></label></td>
		<td>
			<select class="common" id="position" name="position">
			{foreach item="place" from=$positions}
				<option value="{$place}" {if isset($menu) && isset($menu.position) && $menu.position == $place}selected{elseif isset($smarty.post.place) && $smarty.post.place == $place}selected{/if}>{$place}</option>
			{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.classname}:</strong></td>
		<td>
			<input type="text" name="classname" size="40" class="common" value="{if isset($menu.classname)}{$menu.classname}{elseif isset($smarty.post.classname)}{$smarty.post.classname}{/if}"/>
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.show_header}:</strong></td>
		<td>
			{html_radio_switcher value=$menu.show_header|default:$smarty.post.show_header|default:1 name="show_header"}
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.collapsible}:</strong></td>
		<td>
			{html_radio_switcher value=$menu.collapsible|default:$smarty.post.collapsible|default:1 name="collapsible"}
		</td>
	</tr>
	<tr>
		<td><strong>{lang key="sticky"}:</strong></td>
		<td>
			{html_radio_switcher value=$menu.sticky|default:$smarty.post.sticky|default:1 name="sticky"}
		</td>
	</tr>
	</table>
		
	<div id="acos" style="display: none;">
	<table class="striped">
	<tr>
		<td width="150"><strong>{$esynI18N.visible_on_pages}:</strong></td>
		<td>
			{if isset($pages_group) && !empty($pages_group)}
				{if isset($pages) && !empty($pages)}
					<input type="checkbox" value="1" name="select_all" id="select_all" {if isset($smarty.post.select_all) && $smarty.post.select_all == '1'}checked="checked"{/if} /><label for="select_all">&nbsp;{$esynI18N.select_all}</label>
						<div style="clear:both;"></div>
					{foreach from=$pages_group item=group}
						<fieldset class="list" style="float:left;">
							{assign var="post_key" value="select_all_"|cat:$group}
							<legend><input type="checkbox" value="1" class="{$group}" name="select_all_{$group}" id="select_all_{$group}" {if isset($smarty.post.$post_key) && $smarty.post.$post_key == '1'}checked="checked"{/if} /><label for="select_all_{$group}">&nbsp;<strong>{$esynI18N.$group}</strong></label></legend>
							{foreach from=$all_pages key=key item=page}
								{if $page.group == $group}
									<ul style="list-style-type: none;">
										<li style="margin: 0 0 0 15px; padding-bottom: 3px; float: left; width: 200px;" >
											<input type="checkbox" name="visible_on_pages[]" class="{$group}" value="{$page.name}" id="page_{$key}" {if in_array($page.name, $visibleOn, true)}checked="checked"{/if} /><label for="page_{$key}"> {if empty($page.title)}{$page.name}{else}{$page.title}{/if}</label>
											{if $page.name == 'index_browse'}
												<a style="float:right;padding-right:10px;" href="#" id="add_categories">
													<img src="{$smarty.const.IA_URL}js/ext/resources/images/default/tree/leaf.gif" alt="">
												</a>
											{/if}
										</li>
									</ul>
								{/if}
							{/foreach}
						</fieldset>
					{/foreach}
				{/if}
			{/if}
		</td>
	</tr>
	</table>
	</div>
		
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="300"><div id="div_menus"></div></td>
		<td width="25">
			<div>
				<input type="button" value="&gt;&gt;" id="delete_menu" class="common small"/><br /><br />
				<input type="button" value="&lt;&lt;" id="add_menu" class="common small">
			</div>
		</td>
		<td><div id="box_pages"></div></td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="hidden" id="action" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}" name="do">
			<input type="hidden" id="menu" value="" name="menu">
			<input type="hidden" name="cat_crossed" id="cat_crossed" value="{if isset($menu.categories) && !empty($menu.categories)}{','|implode:$menu.categories}{/if}">
			<input class="common" type="submit" name="save" id="save" onclick="return false;" value="{if isset($smart.get.do) && $smarty.get.do == 'add'}{lang key='create'}{else}{lang key='save'}{/if}">
			<span><b>&nbsp;{lang key="and_then"}&nbsp;</b></span>
			<select id="menus_goto" name="goto">
				<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>{lang key="go_to_list"}</option>
				<option value="new" {if isset($smarty.post.goto) && $smarty.post.goto == 'new'}selected="selected"{/if}>{lang key="add_another_one"}</option>
			</select>
		</td>
	</tr>
	</table>
	</form>
	
	{include file='box-footer.tpl'}

	<script type="text/javascript">
		var pages = {$tree_pages};
		var menus = {$tree_menus};
	</script>
{else}
	<div id="box_menus" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/ext/plugins/treeserializer/ext.tree.serialize, js/ckeditor/ckeditor, js/admin/menus"}

{include file='footer.tpl'}