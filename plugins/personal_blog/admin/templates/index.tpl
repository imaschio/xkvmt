{include file="header.tpl" css="plugins/personal_blog/admin/templates/css/new.datetimepicker"}

{if isset($smarty.get.do) && ($smarty.get.do == 'add' || $smarty.get.do == 'edit')}
	<div>
		{include file='box-header.tpl' title=$gTitle}
		<form action="controller.php?plugin=personal_blog&amp;do={$smarty.get.do}{if $smarty.get.do == 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
		{preventCsrf}
			<table width="100%" cellpadding="0" cellspacing="0" class="striped">
				<tr>
					<td width="200"><strong>{$esynI18N.language}:</strong></td>
					<td>
						<select name="lang" class="common">
							{foreach from=$langs key=code item=lang}
								<option value="{$code}" {if isset($one_article.lang) and $one_article.lang == $code}selected="selected" {elseif isset($smarty.post.lang) and $smarty.post.lang == $code}selected="selected" {/if}>{$lang}</option>
							{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<td><strong>{$esynI18N.title}</strong></td>
					<td><input type="text" name="title" class="common" size="100" value="{if isset($one_article.title)}{$one_article.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
				</tr>
				<tr>
					<td><strong>{$esynI18N.article}:</strong></td>
					<td>
						<div class="ckeditor_textarea">
							<textarea class="ckeditor_textarea" id="body" name="body">
								{if isset($one_article.body)}{$one_article.body}{elseif isset($smarty.post.body)}{$smarty.post.body}{/if}
							</textarea>
					</td>
				</tr>
				<tr>
					<td><strong>{$esynI18N.meta_keywords}</strong></td>
					<td><input type="text" name="meta_keywords" class="common" size="132" value="{if isset($one_article.meta_keywords)}{$one_article.meta_keywords}{elseif isset($smarty.post.meta_keywords)}{$smarty.post.meta_keywords}{/if}" /></td>
				</tr>
				<tr>
					<td><strong>{$esynI18N.meta_description}:</strong></td>
					<td>
						<div >
							<textarea class="common" id="meta_description" name="meta_description" >{if isset($one_article.meta_description)}{$one_article.meta_description}{elseif isset($smarty.post.meta_description)}{$smarty.post.meta_description}{/if}</textarea>
						</div>
					</td>
				</tr>
				<tr>
					<td><strong>{$esynI18N.date}</strong></td>
					<td align="left">
						<div style="width:100px;float:left">
							<input type="text" id="date" name="date" class="common" size="9" value="{if isset($one_article.date)}{$one_article.date}{elseif isset($smarty.post.date)}{$smarty.post.date}{elseif !isset($one_article) && !$smarty.post}{$smarty.now|date_format:"%Y-%m-%d"}{/if}" />
						</div>
						<div style="width:70px;float:left">
							<input type="text" id="time" name="time" class="common" size="7" value="{if isset($one_article.time)}{$one_article.time}{elseif isset($smarty.post.time)}{$smarty.post.time}{elseif !isset($one_article) && !$smarty.post}{$smarty.now|date_format:"%H:%M:%S"}{/if}" />
						</div>
					</td>	
				</tr>
				{if isset($smarty.get.do) && $smarty.get.do == 'add'}
					<tr>
						<td colspan="2">
							<span>Add article <strong>and then</strong></span>
							<select name="goto">
								<option value="list" {if isset($smarty.post.goto) && $smarty.post.goto == 'list'}selected="selected"{/if}>Go to list</option>
								<option value="add" {if isset($smarty.post.goto) && $smarty.post.goto == 'add'}selected="selected"{/if}>Add another one</option>
							</select>
						</td>
					</tr>
				{else}
					<tr>
						<td><strong>{$esynI18N.status}:</strong></td>
						<td> 
							<select name="status">
								<option value="active" {if isset($one_article.status) && $one_article.status == 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
								<option value="inactive" {if isset($one_article.status) && $one_article.status == 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status == 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
							</select>
						</td>
					</tr>
				{/if}
			
				<tr class="all">
					<td colspan="2">
						<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}" />
						<input type="hidden" name="id" value="{if isset($one_article.id)}{$one_article.id}{/if}" />
						<input type="submit" name="save" class="common" value="{if $smarty.get.do == 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}" />
					</td>
				</tr>
			</table>
		</form>
		{include file="box-footer.tpl" class="box"}
	</div>
	<div id="box_articles_comments" style="margin-top: 15px;"></div>
	{include_file js="js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/personal_blog/js/admin/edit_article"}
{else}
	<div id="box_articles" style="margin-top: 15px;"></div>
	{include_file js="js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/personal_blog/js/admin/index"}
{/if}

{include file='footer.tpl'}