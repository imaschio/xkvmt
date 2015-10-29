<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/common/block.partner.tpl" */ ?>
<?php /*%%SmartyHeaderCode:738209741550910498413a5-96570392%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7a6b1626ae22949b399b3f88d60e06995ef46f31' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/block.partner.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '738209741550910498413a5-96570392',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'partner_listings' => 0,
    'partner_listing' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910498550e1_78427911',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910498550e1_78427911')) {function content_550910498550e1_78427911($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
?><?php if (isset($_smarty_tpl->tpl_vars['partner_listings']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['partner_listing'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['partner_listing']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['partner_listings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['partner_listing']->key => $_smarty_tpl->tpl_vars['partner_listing']->value){
$_smarty_tpl->tpl_vars['partner_listing']->_loop = true;
?>
		<div class="partner-listing">
			<a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['partner_listing']->value),$_smarty_tpl);?>
" class="js-count title" id="l_<?php echo $_smarty_tpl->tpl_vars['partner_listing']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['partner_listing']->value['id'];?>
" data-item="listings" <?php if ($_smarty_tpl->tpl_vars['config']->value['new_window']){?>target="_blank"<?php }?>><?php echo $_smarty_tpl->tpl_vars['partner_listing']->value['title'];?>
</a>
		</div>
	<?php } ?>
<?php }?><?php }} ?>