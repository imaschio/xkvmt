<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 17:13:19
         compiled from "/home/wwwsyaqd/public_html/plugins/categories_quick_navigation/templates/block.categories-nav.tpl" */ ?>
<?php /*%%SmartyHeaderCode:124888005554f0ebffaf2de0-18181978%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f2da2e3e2c23ca41c89f5c9bcc3aba49051c6201' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/categories_quick_navigation/templates/block.categories-nav.tpl',
      1 => 1425025916,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '124888005554f0ebffaf2de0-18181978',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f0ebffaf8e30_46664915',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f0ebffaf8e30_46664915')) {function content_54f0ebffaf8e30_46664915($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><p class="help-block"><?php echo smarty_function_lang(array('key'=>'cat_nav_help'),$_smarty_tpl);?>
</p>

<input type="text" class="input-block-level" name="what" value="" id="cat_navigation" autocomplete="off">

<?php echo smarty_function_ia_print_js(array('files'=>'plugins/categories_quick_navigation/js/index'),$_smarty_tpl);?>
<?php }} ?>