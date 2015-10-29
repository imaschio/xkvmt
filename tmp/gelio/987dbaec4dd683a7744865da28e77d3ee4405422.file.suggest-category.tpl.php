<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 08:14:30
         compiled from "/home/wwwsyaqd/public_html/templates/common/suggest-category.tpl" */ ?>
<?php /*%%SmartyHeaderCode:198265871555096c263e95e5-55707162%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '987dbaec4dd683a7744865da28e77d3ee4405422' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/suggest-category.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '198265871555096c263e95e5-55707162',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'category' => 0,
    'cat_title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55096c2645e2f7_14383056',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55096c2645e2f7_14383056')) {function content_55096c2645e2f7_14383056($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><div class="page-description"><?php echo smarty_function_lang(array('key'=>'suggest_category_top1'),$_smarty_tpl);?>
</div>

<form method="post" action="<?php echo @constant('IA_URL');?>
suggest-category.php?id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" class="ia-form bordered">

	<div class="fieldset" id="fieldCategories">
		<h4 id="categoryTitle" class="title"><?php echo smarty_function_lang(array('key'=>'category'),$_smarty_tpl);?>
: <span><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
</span></h4>
		<div id="treeContainer" class="content">
			<div id="tree" class="tree"></div>
			<hr>
			<label><?php echo smarty_function_lang(array('key'=>'category_title'),$_smarty_tpl);?>
</label>
			<input type="text" name="title" id="title" value="<?php if (isset($_smarty_tpl->tpl_vars['cat_title']->value)){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['cat_title']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" />
		</div>
	</div>
	
	<?php echo $_smarty_tpl->getSubTemplate ('captcha.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


	<div class="actions">
		<input type="hidden" id="category_id" name="category_id" value="<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" />
		<input type="hidden" id="category_title" name="category_title" value="<?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
" />
		<input type="submit" name="add_category" value="<?php echo smarty_function_lang(array('key'=>'suggest_category'),$_smarty_tpl);?>
" class="btn btn-primary btn-large" />
	</div>
</form>

<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['ia_add_media'][0][0]->add_media(array('files'=>'js:js/intelli/intelli.tree, js:js/frontend/suggest-category'),$_smarty_tpl);?>
<?php }} ?>