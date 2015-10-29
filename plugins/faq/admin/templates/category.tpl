{include file="header.tpl"}

{if isset($smarty.get.do) && ($smarty.get.do eq 'add' || $smarty.get.do eq 'edit')}
	
	{include file="box-header.tpl" title=$gTitle}
	
	<form action="controller.php?plugin=faq&amp;file=categories&amp;do={$smarty.get.do}{if $smarty.get.do eq 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
	
	{preventCsrf}
	
	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	<tr>
		<td width="200"><strong>{$esynI18N.language}:</strong></td>
		<td>
			<select name="lang" {if $langs|@count eq 1}disabled="disabled"{/if}>
				{foreach from=$langs key=code item=lang}
					<option value="{$code}" {if (isset($faq_category.lang) && $faq_category.lang eq $code) || (isset($smarty.post.lang) && $smarty.post.lang eq $code)}selected="selected"{elseif $config.lang eq $code}selected="selected"{/if}>{$lang}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td width="150"><strong>{$esynI18N.title}</strong></td>
		<td><input type="text" class="common" name="title" size="32" maxlength="100" value="{if isset($faq_category.title)}{$faq_category.title}{elseif isset($smarty.post.title)}{$smarty.post.title}{/if}" /></td>
	</tr>

	<tr>
		<td><strong>{$esynI18N.description}</strong></td>
		<td>
			<textarea name="description" id="description" class="common">{if isset($faq_category.description)}{$faq_category.description}{elseif isset($smarty.post.description)}{$smarty.post.description}{/if}</textarea>
		</td>
	</tr>
	
	<tr>
		<td><strong>{$esynI18N.status}:</strong></td>
		<td>
			<select name="status">
				<option value="active" {if isset($faq_category.status) && $faq_category.status eq 'active'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status eq 'active'}selected="selected"{/if}>{$esynI18N.active}</option>
				<option value="inactive" {if isset($faq_category.status) && $faq_category.status eq 'inactive'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status eq 'inactive'}selected="selected"{/if}>{$esynI18N.inactive}</option>
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

{include_file js="js/ext/plugins/bettercombobox/betterComboBox"}

{include file='footer.tpl'}