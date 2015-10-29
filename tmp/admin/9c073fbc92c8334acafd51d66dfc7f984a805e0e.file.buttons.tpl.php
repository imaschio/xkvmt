<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:31:25
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/buttons.tpl" */ ?>
<?php /*%%SmartyHeaderCode:98579154055090dad0a36b1-23865277%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c073fbc92c8334acafd51d66dfc7f984a805e0e' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/buttons.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '98579154055090dad0a36b1-23865277',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'actions' => 0,
    'action' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55090dad0e6ff6_34498672',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55090dad0e6ff6_34498672')) {function content_55090dad0e6ff6_34498672($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['actions']->value)){?>
	<div class="buttons">
	<?php  $_smarty_tpl->tpl_vars['action'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['action']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['actions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['action']->key => $_smarty_tpl->tpl_vars['action']->value){
$_smarty_tpl->tpl_vars['action']->_loop = true;
?>
		<a href="<?php if (isset($_smarty_tpl->tpl_vars['action']->value['url'])&&$_smarty_tpl->tpl_vars['action']->value['url']!=''){?><?php echo $_smarty_tpl->tpl_vars['action']->value['url'];?>
<?php }else{ ?>#<?php }?>" <?php if (isset($_smarty_tpl->tpl_vars['action']->value['attributes'])&&$_smarty_tpl->tpl_vars['action']->value['attributes']!=''){?><?php echo $_smarty_tpl->tpl_vars['action']->value['attributes'];?>
<?php }?>><img src="<?php if (@constant('IA_CURRENT_PLUGIN')&&file_exists(((@constant('IA_PLUGIN_TEMPLATE')).('/img/')).($_smarty_tpl->tpl_vars['action']->value['icon']))){?><?php echo @constant('IA_URL');?>
plugins/<?php echo @constant('IA_CURRENT_PLUGIN');?>
/admin/templates/img/<?php }else{ ?>templates/<?php echo $_smarty_tpl->tpl_vars['config']->value['admin_tmpl'];?>
/img/icons/<?php }?><?php if (isset($_smarty_tpl->tpl_vars['action']->value['icon'])&&$_smarty_tpl->tpl_vars['action']->value['icon']!=''){?><?php echo $_smarty_tpl->tpl_vars['action']->value['icon'];?>
<?php }else{ ?>default-ico.png<?php }?>" title="<?php if (isset($_smarty_tpl->tpl_vars['action']->value['label'])&&$_smarty_tpl->tpl_vars['action']->value['label']!=''){?><?php echo $_smarty_tpl->tpl_vars['action']->value['label'];?>
<?php }?>" alt="<?php if (isset($_smarty_tpl->tpl_vars['action']->value['label'])&&$_smarty_tpl->tpl_vars['action']->value['label']!=''){?><?php echo $_smarty_tpl->tpl_vars['action']->value['label'];?>
<?php }?>"></a>
	<?php } ?>
	</div>

	<div style="clear:right; overflow:hidden;"></div>
<?php }?>
<?php }} ?>