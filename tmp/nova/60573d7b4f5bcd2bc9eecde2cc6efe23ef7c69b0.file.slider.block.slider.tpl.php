<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:49:57
         compiled from "/home/wwwsyaqd/public_html/templates/nova/plugins/slider.block.slider.tpl" */ ?>
<?php /*%%SmartyHeaderCode:74302499054f02fb5abbc79-75523303%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '60573d7b4f5bcd2bc9eecde2cc6efe23ef7c69b0' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/nova/plugins/slider.block.slider.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '74302499054f02fb5abbc79-75523303',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'slides' => 0,
    'slide' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f02fb5ae6c33_02509892',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f02fb5ae6c33_02509892')) {function content_54f02fb5ae6c33_02509892($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_modifier_truncate')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.truncate.php';
if (!is_callable('smarty_block_ia_add_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_add_js.php';
?><?php if ($_smarty_tpl->tpl_vars['slides']->value){?>
	<div class="flexslider flexslider-nova">
		<ul class="slides">
			<?php  $_smarty_tpl->tpl_vars['slide'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slide']->_loop = false;
 $_smarty_tpl->tpl_vars['slide_index_key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['slides']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slide']->key => $_smarty_tpl->tpl_vars['slide']->value){
$_smarty_tpl->tpl_vars['slide']->_loop = true;
 $_smarty_tpl->tpl_vars['slide_index_key']->value = $_smarty_tpl->tpl_vars['slide']->key;
?>
				<li class="<?php echo $_smarty_tpl->tpl_vars['slide']->value['classname'];?>
">
					<?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['slide']->value['image'],'alt'=>$_smarty_tpl->tpl_vars['slide']->value['title']),$_smarty_tpl);?>

					<div class="container">
						<div class="caption">
							<h2><?php echo $_smarty_tpl->tpl_vars['slide']->value['title'];?>
</h2>
							<p><?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['slide']->value['description']),300,'...');?>
</p>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
	</div>

	<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_add_js', array()); $_block_repeat=true; echo smarty_block_ia_add_js(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

		$('.flexslider-nova').flexslider(
		{
			pauseOnHover: true,
			slideshowSpeed: <?php echo $_smarty_tpl->tpl_vars['config']->value['slideshow_speed'];?>
,
			animationSpeed: <?php echo $_smarty_tpl->tpl_vars['config']->value['animation_speed'];?>
,
			animation: '<?php echo $_smarty_tpl->tpl_vars['config']->value['slider_animation'];?>
',
			direction: '<?php echo $_smarty_tpl->tpl_vars['config']->value['slider_direction'];?>
'
		});
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_add_js(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }?><?php }} ?>