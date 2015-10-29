<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:49:57
         compiled from "/home/wwwsyaqd/public_html/templates/common/notification.tpl" */ ?>
<?php /*%%SmartyHeaderCode:66667397454f02fb5c24d09-62265052%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e6270e0aee349a2ed2d7eb013a82248102e5553' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/notification.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '66667397454f02fb5c24d09-62265052',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'msg' => 0,
    'error' => 0,
    'alert' => 0,
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f02fb5c39d08_89296721',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f02fb5c39d08_89296721')) {function content_54f02fb5c39d08_89296721($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['msg']->value)&&!empty($_smarty_tpl->tpl_vars['msg']->value)){?>
	<div id="notification">
		<div class="alert alert-block alert-<?php if ($_smarty_tpl->tpl_vars['error']->value){?>error<?php }elseif($_smarty_tpl->tpl_vars['alert']->value){?>warning<?php }else{ ?>success<?php }?>">
			<ul class="unstyled">
			<?php  $_smarty_tpl->tpl_vars['message'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['message']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['msg']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['message']->key => $_smarty_tpl->tpl_vars['message']->value){
$_smarty_tpl->tpl_vars['message']->_loop = true;
?>
				<li><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</li>
			<?php } ?>
			</ul>
		</div>
	</div>
<?php }?><?php }} ?>