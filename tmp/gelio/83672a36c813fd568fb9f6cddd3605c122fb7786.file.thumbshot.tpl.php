<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "plugins/pagepeeker/templates/thumbshot.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1801358767550910496bea86-13349063%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '83672a36c813fd568fb9f6cddd3605c122fb7786' => 
    array (
      0 => 'plugins/pagepeeker/templates/thumbshot.tpl',
      1 => 1425025904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1801358767550910496bea86-13349063',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lightbox' => 0,
    'listing' => 0,
    'config' => 0,
    'tile' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910496d9e18_17432583',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910496d9e18_17432583')) {function content_550910496d9e18_17432583($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
?><a href="<?php ob_start();?><?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['listing']->value),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php echo isset($_smarty_tpl->tpl_vars['lightbox']->value) ? $_smarty_tpl->tpl_vars['listing']->value['url'] : $_tmp1;?>
" id="l_<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['config']->value['external_no_follow']){?>rel="nofollow"<?php }?> data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-item="listings"  class="js-count">
	<img <?php if (isset($_smarty_tpl->tpl_vars['tile']->value)){?>style="margin: -90px 0 0 -90px;"<?php }else{ ?>class="media-object"<?php }?> src="<?php echo @constant('IA_URL');?>
uploads/thumbnails/<?php echo $_smarty_tpl->tpl_vars['listing']->value['domain'];?>
.<?php echo $_smarty_tpl->tpl_vars['config']->value['pagepeeker_format'];?>
" alt="">
</a><?php }} ?>