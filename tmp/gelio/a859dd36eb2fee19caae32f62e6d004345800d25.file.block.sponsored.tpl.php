<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/common/block.sponsored.tpl" */ ?>
<?php /*%%SmartyHeaderCode:31689647055091049642b42-97167572%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a859dd36eb2fee19caae32f62e6d004345800d25' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/block.sponsored.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31689647055091049642b42-97167572',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sponsored_listings' => 0,
    'sponsored_listing' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910496676c6_63304069',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910496676c6_63304069')) {function content_550910496676c6_63304069($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_truncate')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.truncate.php';
?><?php if (isset($_smarty_tpl->tpl_vars['sponsored_listings']->value)&&$_smarty_tpl->tpl_vars['sponsored_listings']->value){?>
	<div class="slider-box flexslider">
		<ul class="slides">
			<?php  $_smarty_tpl->tpl_vars['sponsored_listing'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sponsored_listing']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sponsored_listings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sponsored_listing']->key => $_smarty_tpl->tpl_vars['sponsored_listing']->value){
$_smarty_tpl->tpl_vars['sponsored_listing']->_loop = true;
?>
				<li>
					<div class="media ia-item">
						<?php echo $_smarty_tpl->getSubTemplate ('thumbshots.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listing'=>$_smarty_tpl->tpl_vars['sponsored_listing']->value), 0);?>


						<div class="media-body">
							<h5 class="media-heading"><a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['sponsored_listing']->value),$_smarty_tpl);?>
" class="js-count title" id="sponsored-l_<?php echo $_smarty_tpl->tpl_vars['sponsored_listing']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['sponsored_listing']->value['id'];?>
" data-item="listings" <?php if ($_smarty_tpl->tpl_vars['config']->value['new_window']){?>target="_blank"<?php }?>><?php echo $_smarty_tpl->tpl_vars['sponsored_listing']->value['title'];?>
</a></h5>
							<p class="date">
								<i class="icon-calendar icon-gray"></i> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['sponsored_listing']->value['date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>

							</p>
						</div>

						<div class="description">
							<?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['sponsored_listing']->value['description']),150,'...');?>

						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
	</div>
<?php }?><?php }} ?>