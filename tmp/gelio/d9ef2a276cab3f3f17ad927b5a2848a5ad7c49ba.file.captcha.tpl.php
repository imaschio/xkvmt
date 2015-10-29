<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/common/captcha.tpl" */ ?>
<?php /*%%SmartyHeaderCode:78545875555091049203403-72398255%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd9ef2a276cab3f3f17ad927b5a2848a5ad7c49ba' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/captcha.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '78545875555091049203403-72398255',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'esynAccountInfo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910492136c1_42194021',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910492136c1_42194021')) {function content_550910492136c1_42194021($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_captcha')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_captcha.php';
?><?php if ($_smarty_tpl->tpl_vars['config']->value['captcha']&&$_smarty_tpl->tpl_vars['config']->value['captcha_name']&&empty($_smarty_tpl->tpl_vars['esynAccountInfo']->value)){?>
	<div class="captcha-simple" id="captcha">
		<div class="fieldset">
			<div class="content">
				<?php echo smarty_function_include_captcha(array('name'=>$_smarty_tpl->tpl_vars['config']->value['captcha_name']),$_smarty_tpl);?>

			</div>
		</div>
	</div>
<?php }?><?php }} ?>