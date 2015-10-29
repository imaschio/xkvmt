<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:31:25
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/notification.tpl" */ ?>
<?php /*%%SmartyHeaderCode:63861921955090dad0e9dd6-13392769%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '617b96c0bb012d8a0de1e196f93e6343d6fa9906' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/notification.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '63861921955090dad0e9dd6-13392769',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'id' => 0,
    'messages' => 0,
    'type' => 0,
    'messages_list' => 0,
    'message' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55090dad0f9e57_26608848',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55090dad0f9e57_26608848')) {function content_55090dad0f9e57_26608848($_smarty_tpl) {?><div id="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['id']->value)===null||$tmp==='' ? 'notification' : $tmp);?>
">
	<?php  $_smarty_tpl->tpl_vars['messages_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['messages_list']->_loop = false;
 $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['messages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['messages_list']->key => $_smarty_tpl->tpl_vars['messages_list']->value){
$_smarty_tpl->tpl_vars['messages_list']->_loop = true;
 $_smarty_tpl->tpl_vars['type']->value = $_smarty_tpl->tpl_vars['messages_list']->key;
?>
		<div class="message <?php echo $_smarty_tpl->tpl_vars['type']->value;?>
">
			<div class="icon"></div>
			<ul>
				<?php  $_smarty_tpl->tpl_vars['message'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['message']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['messages_list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['message']->key => $_smarty_tpl->tpl_vars['message']->value){
$_smarty_tpl->tpl_vars['message']->_loop = true;
?>
					<li><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
</div><?php }} ?>