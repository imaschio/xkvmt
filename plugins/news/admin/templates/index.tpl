{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if isset($smarty.get.do) && ($smarty.get.do == 'add' || $smarty.get.do == 'edit')}
	
	{include file='box-header.tpl' title=$gTitle}
	
	<form action="controller.php?plugin=news&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post" enctype="multipart/form-data">
		
	{preventCsrf}
	
	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	
	<tr>
		<td width="150"><strong>{$esynI18N.language}:</strong></td>
		<td>
			<select name="lang" {if $langs|count == 1}disabled="disabled"{/if}>
				{foreach from=$langs key=code item=lang}
					<option value="{$code}" {if isset($news.lang) && $news.lang == $code}selected="selected"{/if}>{$lang}</option>
				{/foreach}
			</select>
		</td>
	</tr>

	<tr>
		<td width="100"><strong>{$esynI18N.title}</strong></td>
		<td><input type="text" class="common" name="title" size="32" value="{if isset($news.title)}{$news.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
	</tr>

    {if isset($news.image) && !empty($news.image)}
        <tr>
            <td colspan="2">
                <img src="{$smarty.const.IA_URL|cat:"uploads"|cat:$smarty.const.IA_DS|cat:$news.image}" alt="" />
                <input name="image_del" type="checkbox" id="image_del" /><label for="image_del">{$esynI18N.delete_image}</label>
                <input type="hidden" name="news_cur_image" value="{$news.image}" />
            </td>
        </tr>
    {/if}
    <tr>
        <td><strong>{$esynI18N.news_image}</strong></td>
        <td>
            <input type="file" name="news_image" class="common" size="35" id="news_image" />
        </td>
    </tr>

	<tr>
		<td><strong>{$esynI18N.body}</strong></td>
		<td>
			<textarea name="body" class="ckeditor_textarea" id="body">{if isset($news.body)}{$news.body}{elseif isset($smarty.post.body)}{$smarty.post.body}{/if}</textarea>
		</td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.date}</strong></td>
		<td><input type="text" class="common" id="date" name="date" size="32" value="{if isset($news.date)}{$news.date}{elseif isset($smarty.post.date)}{$smarty.post.date}{elseif !isset($news) && !$smarty.post}{$smarty.now|date_format:"%Y-%m-%d"}{/if}" /></td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($news.status) && $news.status == 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="inactive" {if isset($news.status) && $news.status == 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
			</select>
		</td>
	</tr>

	{if isset($smarty.get.do) && $smarty.get.do == 'add'}
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
			<input type="submit" name="save" class="common" value="{if $smarty.get.do == 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}" />
		</td>
	</tr>
	</table>
	</form>
    {include_file js="js/ckeditor/ckeditor, plugins/news/js/admin/manage"}
	{include file='box-footer.tpl'}
{else}
    {include_file js="js/intelli/intelli.grid,  js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/news/js/admin/news"}
	<div id="box_news" style="margin-top: 15px;"></div>
{/if}

{include file='footer.tpl'}