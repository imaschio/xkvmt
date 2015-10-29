<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 07:28:53
         compiled from "/home/wwwsyaqd/public_html/templates/common/thank.tpl" */ ?>
<?php /*%%SmartyHeaderCode:118602468955096175360bb6-72526397%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f60a7f2ee150e19cd37e1c85301f4695a4a7c1ff' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/thank.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '118602468955096175360bb6-72526397',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'email' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55096175394d82_07426156',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55096175394d82_07426156')) {function content_55096175394d82_07426156($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><div class="page-description"><?php echo smarty_function_lang(array('key'=>'thankyou_head'),$_smarty_tpl);?>
</div>
<h2><?php echo $_smarty_tpl->tpl_vars['email']->value;?>
</h2>
<div><?php echo smarty_function_lang(array('key'=>'thankyou_tail'),$_smarty_tpl);?>
</div>

<?php if ($_smarty_tpl->tpl_vars['config']->value['accounts_autoapproval']){?>
	<div>
		<input type="button" value=" <?php echo smarty_function_lang(array('key'=>'next'),$_smarty_tpl);?>
 " onclick="document.location.href = 'login.php';" class="btn btn-primary">
	</div>
<?php }?><?php }} ?>