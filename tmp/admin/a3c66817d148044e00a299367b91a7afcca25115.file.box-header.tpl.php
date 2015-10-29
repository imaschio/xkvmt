<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:35:44
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/box-header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:203490838855090eb077bfa6-51054677%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a3c66817d148044e00a299367b91a7afcca25115' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/box-header.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203490838855090eb077bfa6-51054677',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'id' => 0,
    'hidden' => 0,
    'title' => 0,
    'collapsed' => 0,
    'style' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55090eb07a1d90_87625365',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55090eb07a1d90_87625365')) {function content_55090eb07a1d90_87625365($_smarty_tpl) {?><!-- simple box start -->
<div class="box" <?php if (isset($_smarty_tpl->tpl_vars['id']->value)){?>id="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"<?php }?> <?php if (isset($_smarty_tpl->tpl_vars['hidden']->value)){?>style="display: none;"<?php }?>>
	<div class="inner">
		<div class="box-caption"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</div>
		<div class="minmax <?php if (isset($_smarty_tpl->tpl_vars['collapsed']->value)){?>white-close<?php }else{ ?>white-open<?php }?>"></div>
		<div class="box-content" <?php if (isset($_smarty_tpl->tpl_vars['collapsed']->value)){?>style="display: none;"<?php }?><?php if (isset($_smarty_tpl->tpl_vars['style']->value)&&!empty($_smarty_tpl->tpl_vars['style']->value)){?>style="<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
"<?php }?>>
<?php }} ?>