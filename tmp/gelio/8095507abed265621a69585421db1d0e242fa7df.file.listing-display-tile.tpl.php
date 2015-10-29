<?php /* Smarty version Smarty-3.1.13, created on 2015-04-07 11:40:06
         compiled from "/home/wwwsyaqd/public_html/templates/common/listing-display-tile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9553961515523fa566fcb59-28202934%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8095507abed265621a69585421db1d0e242fa7df' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/listing-display-tile.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9553961515523fa566fcb59-28202934',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'visual_options' => 0,
    'listing' => 0,
    'options' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5523fa567ddfa8_24785855',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5523fa567ddfa8_24785855')) {function content_5523fa567ddfa8_24785855($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
if (!is_callable('smarty_modifier_truncate')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
?><?php echo smarty_function_ia_hooker(array('name'=>'beforeListingDisplay'),$_smarty_tpl);?>


<?php if (isset($_smarty_tpl->tpl_vars['options'])) {$_smarty_tpl->tpl_vars['options'] = clone $_smarty_tpl->tpl_vars['options'];
$_smarty_tpl->tpl_vars['options']->value = array_intersect(array_keys($_smarty_tpl->tpl_vars['visual_options']->value),explode(',',$_smarty_tpl->tpl_vars['listing']->value['visual_options'])); $_smarty_tpl->tpl_vars['options']->nocache = null; $_smarty_tpl->tpl_vars['options']->scope = 0;
} else $_smarty_tpl->tpl_vars['options'] = new Smarty_variable(array_intersect(array_keys($_smarty_tpl->tpl_vars['visual_options']->value),explode(',',$_smarty_tpl->tpl_vars['listing']->value['visual_options'])), null, 0);?>

<li class="span2">
	<div class="ia-item tile<?php if ($_smarty_tpl->tpl_vars['listing']->value['featured']){?> featured<?php }?> <?php echo $_smarty_tpl->tpl_vars['listing']->value['status'];?>
<?php if (in_array('add_border',$_smarty_tpl->tpl_vars['options']->value)){?> ia-visual-option--border<?php }?>" id="listing-<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
">
		<?php if ($_smarty_tpl->tpl_vars['listing']->value['partner']){?>
			<span class="ia-badge partner"><?php echo smarty_function_lang(array('key'=>'partner'),$_smarty_tpl);?>
</span>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['listing']->value['featured']){?>
			<span class="ia-badge featured"><?php echo smarty_function_lang(array('key'=>'featured'),$_smarty_tpl);?>
</span>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['listing']->value['sponsored']){?>
			<span class="ia-badge sponsored"><?php echo smarty_function_lang(array('key'=>'sponsored'),$_smarty_tpl);?>
</span>
		<?php }?>
		<?php if ('approval'==$_smarty_tpl->tpl_vars['listing']->value['status']){?>
			<span class="ia-badge approval"></span>
		<?php }?>

		<?php if (in_array('add_badge',$_smarty_tpl->tpl_vars['options']->value)){?>
			<div class="pull-right ia-visual-option--badge">
				<img src="<?php echo $_smarty_tpl->tpl_vars['visual_options']->value['add_badge'];?>
" alt="Badge">
			</div>
		<?php }?>

		<div class="tile-body">
			<?php if ($_smarty_tpl->tpl_vars['listing']->value['featured']||$_smarty_tpl->tpl_vars['listing']->value['sponsored']||$_smarty_tpl->tpl_vars['listing']->value['partner']){?>
				<?php echo $_smarty_tpl->getSubTemplate ('thumbshots.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listing'=>$_smarty_tpl->tpl_vars['listing']->value,'tile'=>true), 0);?>

			<?php }?>

			<div class="description<?php if (!$_smarty_tpl->tpl_vars['listing']->value['featured']&&!$_smarty_tpl->tpl_vars['listing']->value['sponsored']&&!$_smarty_tpl->tpl_vars['listing']->value['partner']){?> regular<?php }?>">
				<h4>
					<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['crossed'])&&$_smarty_tpl->tpl_vars['listing']->value['crossed']=='1'){?>
						<i class="icon-random icon-gray"></i> 
					<?php }?>
					<a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['listing']->value),$_smarty_tpl);?>
" <?php if (!$_smarty_tpl->tpl_vars['config']->value['forward_to_listing_details']&&$_smarty_tpl->tpl_vars['config']->value['new_window']){?>target="_blank"<?php }?> <?php if ($_smarty_tpl->tpl_vars['config']->value['external_no_follow']){?> rel="nofollow"<?php }?>class="js-count" id="l_<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-item="listings"><?php echo $_smarty_tpl->tpl_vars['listing']->value['title'];?>
</a>
				</h4>
				<?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['listing']->value['description']),100,'...');?>

				<div class="tile-info">
					<?php if ('index_browse'!=@constant('IA_REALM')){?>
						<span class="category" title="<?php echo $_smarty_tpl->tpl_vars['listing']->value['category_title'];?>
"><i class="icon-folder-open icon-white"></i> <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['listing']->value,'fprefix'=>'category'),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['listing']->value['category_title'];?>
</a></span>
					<?php }else{ ?>
						<span class="date" title="<?php echo smarty_function_lang(array('key'=>'listing_added'),$_smarty_tpl);?>
"><i class="icon-calendar icon-white"></i> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['listing']->value['date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
</span>
					<?php }?>
				</div>

				<?php echo smarty_function_ia_hooker(array('name'=>'listingDisplayPanelLinks'),$_smarty_tpl);?>

			</div>	
			<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['interval'])&&(1==$_smarty_tpl->tpl_vars['listing']->value['interval'])){?>
				<span class="label label-important"><?php echo smarty_function_lang(array('key'=>'new'),$_smarty_tpl);?>
</span>
			<?php }?>

			<?php echo smarty_function_ia_hooker(array('name'=>'listingDisplayFieldsArea'),$_smarty_tpl);?>

		</div>

		<?php echo smarty_function_ia_hooker(array('name'=>'listingDisplayBeforeStats'),$_smarty_tpl);?>

	</div>
</li><?php }} ?>