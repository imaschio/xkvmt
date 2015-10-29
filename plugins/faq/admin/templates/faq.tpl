{include file="header.tpl"}

{if isset($smarty.get.do) && ($smarty.get.do eq 'add' || $smarty.get.do eq 'edit')}
	
	{include file="box-header.tpl" title=$gTitle}
	
	<form action="controller.php?plugin=faq&amp;do={$smarty.get.do}{if $smarty.get.do eq 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
	
	{preventCsrf}
	
	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	<tr>
		<td width="150"><strong>{$esynI18N.faq_categories}</strong></td>
		<td>
			<select id="faq_category" name="category">
				{foreach from=$faq_categories item=category}
					<option class="{$category.lang}" value="{$category.id}" {if isset($faq.category_id) && $faq.category_id eq $category.id}selected="selected"{elseif isset($smarty.post.category_id) && $smarty.post.category_id eq $category.id}selected="selected"{/if}>{$category.title}</option>
				{/foreach}
			</select>	
		</td>
	</tr>
	
	<tr>
		<td width="200"><strong>{$esynI18N.language}:</strong></td>
		<td>
			<select readonly="readonly" id="faq_lang" name="lang" {if $langs|@count eq 1}disabled="disabled"{/if}>
				{foreach from=$langs key=code item=lang}
					<option value="{$code}">{$lang}</option>
				{/foreach}
			</select>
			<input id="lang2" type="hidden" name="lang2" value="{$smarty.const.IA_LANGUAGE}" />
		</td>
	</tr>
	
	<tr>
		<td width="150"><strong>{$esynI18N.question}</strong></td>
		<td><input type="text" class="common" name="question" size="32" value="{if isset($faq.question)}{$faq.question}{elseif isset($smarty.post.question)}{$smarty.post.question}{/if}" /></td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.answer}</strong></td>
		<td>
			<textarea name="answer" id="answer">{if isset($faq.answer)}{$faq.answer}{elseif isset($smarty.post.answer)}{$smarty.post.answer}{/if}</textarea>
		</td>
	</tr>
	
	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($faq.status) && $faq.status eq 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status eq 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="inactive" {if isset($faq.status) && $faq.status eq 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status eq 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
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
	
	{include file="box-footer.tpl"}
	
{/if}

{include_file js="js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, plugins/faq/js/admin/cked"}

{include file='footer.tpl'}