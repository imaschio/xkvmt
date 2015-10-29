<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 09:47:48
         compiled from "/home/wwwsyaqd/public_html/templates/common/resend-confirm.tpl" */ ?>
<?php /*%%SmartyHeaderCode:191759816555098204538ad7-61307495%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f4123b68f122543a5a5202bd43fdee860b27b783' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/resend-confirm.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '191759816555098204538ad7-61307495',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5509820457aff5_93414019',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5509820457aff5_93414019')) {function content_5509820457aff5_93414019($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><form action="<?php echo @constant('IA_URL');?>
resend-confirm.php" method="post" class="ia-form">
	<label for="username"><?php echo smarty_function_lang(array('key'=>'username_or_email'),$_smarty_tpl);?>
</label>
	<input type="text" class="span3" name="username" value="<?php if (isset($_GET['username'])){?><?php echo htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
	<div class="actions">
		<input type="submit" name="resend" value="<?php echo smarty_function_lang(array('key'=>'submit'),$_smarty_tpl);?>
" class="btn btn-primary" />
	</div>
</form><?php }} ?>