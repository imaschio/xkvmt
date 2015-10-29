<?php /* Smarty version Smarty-3.1.13, created on 2015-03-19 06:40:45
         compiled from "/home/wwwsyaqd/public_html/templates/common/pay.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1314181322550aa7adbac5a8-96440170%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '099fef8e2ecc193344d7bfad5652852cf5ce860b' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/pay.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1314181322550aa7adbac5a8-96440170',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'form_custom' => 0,
    'form_action' => 0,
    'form_values' => 0,
    'key' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550aa7adbf6696_19060497',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550aa7adbf6696_19060497')) {function content_550aa7adbf6696_19060497($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<?php echo smarty_function_ia_print_js(array('files'=>'js/utils/sessvars'),$_smarty_tpl);?>

</head>
<body>

<?php if (isset($_smarty_tpl->tpl_vars['form_custom']->value)&&!empty($_smarty_tpl->tpl_vars['form_custom']->value)){?>
	<?php echo $_smarty_tpl->getSubTemplate ($_smarty_tpl->tpl_vars['form_custom']->value, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<form action="<?php echo $_smarty_tpl->tpl_vars['form_action']->value;?>
" method="post" id="payment_form">
		<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['form_values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['val']->key;
?>
		<input type="hidden" name="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['val']->value;?>
" />
		<?php } ?>
	</form>
<?php }?>

<script type="text/javascript">
	sessvars.$.clearMem();
	if (document.getElementById("payment_form"))
	{
		document.getElementById("payment_form").submit();
	}
</script>

</body>
</html><?php }} ?>