<?php /* Smarty version Smarty-3.1.13, created on 2015-03-20 08:34:46
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/categories.tpl" */ ?>
<?php /*%%SmartyHeaderCode:999305597550c13e67d4715-91027378%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e6efba8cc25009338882d967f54b4500a2f9c467' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/categories.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '999305597550c13e67d4715-91027378',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550c13e6843db4_04973742',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550c13e6843db4_04973742')) {function content_550c13e6843db4_04973742($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<div id="box_categories" style="margin-top: 15px;"></div>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/categories"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>