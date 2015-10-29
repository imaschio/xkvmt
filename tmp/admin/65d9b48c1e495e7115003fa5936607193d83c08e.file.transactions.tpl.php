<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 09:59:19
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/transactions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1581996357550984b7d1e686-70573701%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '65d9b48c1e495e7115003fa5936607193d83c08e' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/transactions.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1581996357550984b7d1e686-70573701',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550984b7d50023_45047585',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550984b7d50023_45047585')) {function content_550984b7d50023_45047585($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<div id="box_transactions_add" style="margin-top: 15px;"></div>
<div id="box_transactions" style="margin-top: 15px;"></div>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/transactions"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>