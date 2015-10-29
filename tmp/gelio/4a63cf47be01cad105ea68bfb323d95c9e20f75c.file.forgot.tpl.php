<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 09:22:04
         compiled from "/home/wwwsyaqd/public_html/templates/common/forgot.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3163657655097bfc86cb55-63041729%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a63cf47be01cad105ea68bfb323d95c9e20f75c' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/forgot.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3163657655097bfc86cb55-63041729',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'form' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55097bfc8af0e9_93354642',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55097bfc8af0e9_93354642')) {function content_55097bfc8af0e9_93354642($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><?php if ($_smarty_tpl->tpl_vars['form']->value){?>
	<form action="<?php echo @constant('IA_URL');?>
forgot.php" method="post" class="ia-form form-inline">
		<?php echo smarty_function_lang(array('key'=>'email'),$_smarty_tpl);?>
:
		<input type="text" class="input-large" name="email" value="<?php if (isset($_POST['email'])){?><?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" size="35" />
		<input type="submit" name="restore" value="<?php echo smarty_function_lang(array('key'=>'submit'),$_smarty_tpl);?>
" class="btn btn-primary" />
	</form>
<?php }?>

<div><?php echo smarty_function_lang(array('key'=>'resend_confirm_email_text'),$_smarty_tpl);?>
</div><?php }} ?>