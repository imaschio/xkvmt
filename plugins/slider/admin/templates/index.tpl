{include file="header.tpl"}

{if isset($smarty.get.do) && ($smarty.get.do == 'add' || $smarty.get.do == 'edit')}
	{include file='box-header.tpl' title=$gTitle}

	<form action="controller.php?plugin=slider&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post" enctype="multipart/form-data">

	{preventCsrf}

	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.slider_category}</strong></td>
		<td>
			<span id="parent_category_title_container">
				<strong>{if isset($category.title)}<a href="controller.php?file=browse&amp;id={$category.id}">{$category.title}</a>{else}ROOT{/if}</strong>
			</span>&nbsp;
			<a href="#" id="change_category">{$esynI18N.change}...</a>&nbsp;

			<input type="hidden" id="category_id" name="category_id" value="{$category.id}" />
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.title}</strong></td>
		<td><input type="text" name="title" size="32" class="common" value="{if isset($slide.title)}{$slide.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.classname}</strong></td>
		<td><input type="text" name="classname" size="32" class="common" value="{if isset($slide.classname)}{$slide.classname}{elseif isset($smarty.post.classname)}{$smarty.post.classname}{/if}" /></td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.description}</strong></td>
		<td>
			<div class="ckeditor_textarea">
				<textarea class="ckeditor_textarea" id="description" name="description">
					{if isset($slide.description)}{$slide.description}{elseif isset($smarty.post.description)}{$smarty.post.description}{/if}
				</textarea>
			</div>
		</td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-sliderimage">
			<strong>{$esynI18N.image}</strong>
			<div style="display:none;"><div id="tip-content-sliderimage">{lang key='slider_image_desc'}</div></div>
		</td>
		<td>
			<input type="file" name="image" size="35" id="image" />
			{if $slide.image != ''}<a href="{$smarty.const.ESYN_URL}uploads/{$slide.image}" target="_blank">{$slide.image}</a>{/if}
		</td>
	</tr>
	<tr>
		<td><strong>{$esynI18N.order}</strong></td>
		<td><input type="text" name="order" size="4" class="common" value="{if isset($slide.order)}{$slide.order}{elseif isset($smarty.post.order)}{$smarty.post.order}{/if}" /></td>
	</tr>
	{if isset($smarty.get.do) && $smarty.get.do == 'edit'}
	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td> 
			<select name="status">
				<option value="active" {if isset($slide.status) && $slide.status == 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="inactive" {if isset($slide.status) && $slide.status == 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
			</select>
		</td>
	</tr>
	{/if}

	<tr class="all">
		<td colspan="2">
			<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}" />
			<input type="hidden" name="id" value="{if isset($slide.id)}{$slide.id}{/if}" />
			<input type="submit" name="save" class="common" value="{if $smarty.get.do == 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}" />

			<span>{$gTitle} <strong>{$esynI18N.and_then}</strong></span>
			<select name="goto">
				<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>{$esynI18N.go_to_list}</option>
				<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto == 'add'}selected="selected"{/if}>{$esynI18N.add_another_one}</option>
			</select>
		</td>
	</tr>
	</table>
	</form>
	
	{include file="box-footer.tpl" class="box"}
{else}
	<div id="box_slider" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/slider/js/admin/slider"}

{include file="footer.tpl"}