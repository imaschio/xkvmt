<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/common/parse-blocks.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5510693055091049461446-04013291%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '27e860bf99547d470682068af2a8d6cfa7a69540' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/parse-blocks.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5510693055091049461446-04013291',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pos' => 0,
    'block' => 0,
    'manageMode' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910494a3fd5_98171050',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910494a3fd5_98171050')) {function content_550910494a3fd5_98171050($_smarty_tpl) {?><?php if (!is_callable('smarty_block_ia_block')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_block.php';
?><?php if (isset($_smarty_tpl->tpl_vars['pos']->value)&&!empty($_smarty_tpl->tpl_vars['pos']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value){
$_smarty_tpl->tpl_vars['block']->_loop = true;
?>

		<?php $_smarty_tpl->_capture_stack[0][] = array('contents', null, null); ob_start(); ?>
			<!--__b_c_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
-->
			<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['ia_parse_block'][0][0]->_ia_parse_block(array('block'=>$_smarty_tpl->tpl_vars['block']->value,'content'=>$_smarty_tpl->tpl_vars['block']->value['contents']),$_smarty_tpl);?>

			<!--__e_c_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
-->
		<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

		<?php if (Smarty::$_smarty_vars['capture']['contents']||$_smarty_tpl->tpl_vars['manageMode']->value){?>
			<!--__b_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
-->
			<?php if (!in_array($_smarty_tpl->tpl_vars['block']->value['position'],array('inventory','mainmenu','copyright'))){?>
				<?php if ($_smarty_tpl->tpl_vars['block']->value['show_header']){?>
					<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_block', array('caption'=>$_smarty_tpl->tpl_vars['block']->value['title'],'style'=>'movable','collapsible'=>$_smarty_tpl->tpl_vars['block']->value['collapsible'],'collapsed'=>$_smarty_tpl->tpl_vars['block']->value['collapsed'],'block'=>$_smarty_tpl->tpl_vars['block']->value)); $_block_repeat=true; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['block']->value['title'],'style'=>'movable','collapsible'=>$_smarty_tpl->tpl_vars['block']->value['collapsible'],'collapsed'=>$_smarty_tpl->tpl_vars['block']->value['collapsed'],'block'=>$_smarty_tpl->tpl_vars['block']->value), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

						<?php echo Smarty::$_smarty_vars['capture']['contents'];?>

					<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['block']->value['title'],'style'=>'movable','collapsible'=>$_smarty_tpl->tpl_vars['block']->value['collapsible'],'collapsed'=>$_smarty_tpl->tpl_vars['block']->value['collapsed'],'block'=>$_smarty_tpl->tpl_vars['block']->value), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

				<?php }else{ ?>
					<div class="box no-header <?php echo $_smarty_tpl->tpl_vars['block']->value['classname'];?>
" id="block_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
" >
						<div class="box-content" id="content_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
">
							<?php echo Smarty::$_smarty_vars['capture']['contents'];?>

						</div>
					</div>
				<?php }?>
			<?php }else{ ?>
				<?php echo Smarty::$_smarty_vars['capture']['contents'];?>

			<?php }?>
			<!--__e_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
-->
		<?php }?>
	<?php } ?>
<?php }?><?php }} ?>