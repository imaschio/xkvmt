<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:49:57
         compiled from "/home/wwwsyaqd/public_html/templates/common/block.tpl" */ ?>
<?php /*%%SmartyHeaderCode:102240909054f02fb5b17c64-18580002%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '700fb5f1ee5bc79aef08361be751a33cb7896cc0' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/block.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '102240909054f02fb5b17c64-18580002',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'block' => 0,
    'collapsible' => 0,
    'collapsed' => 0,
    'style' => 0,
    'id' => 0,
    'caption' => 0,
    '_block_content_' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f02fb5b64011_23211249',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f02fb5b64011_23211249')) {function content_54f02fb5b64011_23211249($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
?><div class="box <?php echo $_smarty_tpl->tpl_vars['block']->value['classname'];?>
<?php if (isset($_smarty_tpl->tpl_vars['collapsible']->value)&&$_smarty_tpl->tpl_vars['collapsible']->value=='1'){?> collapsible<?php }?><?php if (isset($_smarty_tpl->tpl_vars['collapsed']->value)&&$_smarty_tpl->tpl_vars['collapsed']->value=='1'){?> collapsed<?php }?>" <?php if (isset($_smarty_tpl->tpl_vars['block']->value['id'])){?>id="block_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
"<?php }?>>
	<h4 class="box-caption-<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
" <?php if (isset($_smarty_tpl->tpl_vars['id']->value)){?>id="caption_<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"<?php }elseif($_smarty_tpl->tpl_vars['block']->value['id']){?>id="caption_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
"<?php }?>>
		<?php if (!empty($_smarty_tpl->tpl_vars['block']->value['rss'])){?><a href="<?php echo $_smarty_tpl->tpl_vars['block']->value['rss'];?>
" ><?php echo smarty_function_print_img(array('fl'=>'xml.gif','full'=>true),$_smarty_tpl);?>
</a><?php }?>
		<?php echo $_smarty_tpl->tpl_vars['caption']->value;?>
 <?php echo smarty_function_ia_hooker(array('name'=>'blockHeader'),$_smarty_tpl);?>

	</h4>

	<div class="box-content box-content-<?php echo $_smarty_tpl->tpl_vars['style']->value;?>
<?php if (isset($_smarty_tpl->tpl_vars['collapsible']->value)&&'1'==$_smarty_tpl->tpl_vars['collapsible']->value){?> collapsible-content<?php }?>" <?php if (isset($_smarty_tpl->tpl_vars['id']->value)){?>id="content_<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"<?php }elseif($_smarty_tpl->tpl_vars['block']->value['id']){?>id="content_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
"<?php }?>>
		<?php echo $_smarty_tpl->tpl_vars['_block_content_']->value;?>

	</div>
</div><?php }} ?>