<div class="page-description">{lang key='suggest_category_top1'}</div>

<form method="post" action="{$smarty.const.IA_URL}suggest-category.php?id={$category.id}" class="ia-form bordered">

	<div class="fieldset" id="fieldCategories">
		<h4 id="categoryTitle" class="title">{lang key='category'}: <span>{$category.title}</span></h4>
		<div id="treeContainer" class="content">
			<div id="tree" class="tree"></div>
			<hr>
			<label>{lang key='category_title'}</label>
			<input type="text" name="title" id="title" value="{if isset($cat_title)}{$cat_title|escape:'html'}{/if}" />
		</div>
	</div>
	
	{include file='captcha.tpl'}

	<div class="actions">
		<input type="hidden" id="category_id" name="category_id" value="{$category.id}" />
		<input type="hidden" id="category_title" name="category_title" value="{$category.title}" />
		<input type="submit" name="add_category" value="{lang key='suggest_category'}" class="btn btn-primary btn-large" />
	</div>
</form>

{ia_add_media files='js:js/intelli/intelli.tree, js:js/frontend/suggest-category'}