<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 09:41:30
         compiled from "/home/wwwsyaqd/public_html/plugins/categoriesmap/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9181566735509808a0f8309-57286801%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9ee60d807fead62a7ac6f90cb8f0bedc37c083a6' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/categoriesmap/templates/index.tpl',
      1 => 1425025914,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9181566735509808a0f8309-57286801',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tree' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5509808a1445f9_00844327',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5509808a1445f9_00844327')) {function content_5509808a1445f9_00844327($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><?php echo smarty_function_include_file(array('css'=>"plugins/categoriesmap/templates/css/jquery.treeview"),$_smarty_tpl);?>


<div class="page-description"><?php echo smarty_function_lang(array('key'=>'categories_map_description'),$_smarty_tpl);?>
</div>

<ul id="navigation">
	<?php echo $_smarty_tpl->tpl_vars['tree']->value;?>

</ul>

<?php echo smarty_function_include_file(array('js'=>"plugins/categoriesmap/js/jquery.treeview, plugins/categoriesmap/js/index"),$_smarty_tpl);?>
<?php }} ?>