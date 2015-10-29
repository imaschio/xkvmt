<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/common/notification.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1314664671550910498823c9-66786797%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
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
  'nocache_hash' => '1314664671550910498823c9-66786797',
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
  'unifunc' => 'content_55091049893aa3_93805411',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55091049893aa3_93805411')) {function content_55091049893aa3_93805411($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['msg']->value)&&!empty($_smarty_tpl->tpl_vars['msg']->value)){?>
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