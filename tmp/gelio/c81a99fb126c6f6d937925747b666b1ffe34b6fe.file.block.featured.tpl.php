<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/common/block.featured.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10940283965509104978b378-92340235%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c81a99fb126c6f6d937925747b666b1ffe34b6fe' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/block.featured.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10940283965509104978b378-92340235',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'featured_listings' => 0,
    'featured_listing' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910497af1c4_87004322',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910497af1c4_87004322')) {function content_550910497af1c4_87004322($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_truncate')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.truncate.php';
?><?php if (isset($_smarty_tpl->tpl_vars['featured_listings']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['featured_listing'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['featured_listing']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['featured_listings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['featured_listing']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['featured_listing']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['featured_listing']->key => $_smarty_tpl->tpl_vars['featured_listing']->value){
$_smarty_tpl->tpl_vars['featured_listing']->_loop = true;
 $_smarty_tpl->tpl_vars['featured_listing']->iteration++;
 $_smarty_tpl->tpl_vars['featured_listing']->last = $_smarty_tpl->tpl_vars['featured_listing']->iteration === $_smarty_tpl->tpl_vars['featured_listing']->total;
?>
		<div class="media ia-item">

			<?php echo $_smarty_tpl->getSubTemplate ('thumbshots.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listing'=>$_smarty_tpl->tpl_vars['featured_listing']->value), 0);?>


			<div class="media-body">
				<h5 class="media-heading"><a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['featured_listing']->value),$_smarty_tpl);?>
" class="js-count title" data-item="listings" id="l_<?php echo $_smarty_tpl->tpl_vars['featured_listing']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['featured_listing']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['config']->value['new_window']){?>target="_blank"<?php }?>><?php echo $_smarty_tpl->tpl_vars['featured_listing']->value['title'];?>
</a></h5>
				<p class="date">
					<i class="icon-calendar icon-gray"></i> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['featured_listing']->value['date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>

				</p>
			</div>

			<div class="description">
				<?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['featured_listing']->value['description']),150,'...');?>

			</div>
		</div>
		<?php if (!$_smarty_tpl->tpl_vars['featured_listing']->last){?><hr /><?php }?>
	<?php } ?>
<?php }?><?php }} ?>