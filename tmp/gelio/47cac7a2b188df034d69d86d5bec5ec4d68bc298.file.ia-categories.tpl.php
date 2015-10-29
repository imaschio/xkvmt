<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:54
         compiled from "/home/wwwsyaqd/public_html/templates/common/ia-categories.tpl" */ ?>
<?php /*%%SmartyHeaderCode:406410478550914d21e3db1-82143622%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '47cac7a2b188df034d69d86d5bec5ec4d68bc298' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/ia-categories.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '406410478550914d21e3db1-82143622',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'categories' => 0,
    'num_columns' => 0,
    'class_names' => 0,
    'config' => 0,
    'category' => 0,
    'subcategory' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d223ecd3_71449250',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d223ecd3_71449250')) {function content_550914d223ecd3_71449250($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['categories']->value){?>
	<div class="row-fluid cats">
		<?php  $_smarty_tpl->tpl_vars['category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['category']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['category']->key => $_smarty_tpl->tpl_vars['category']->value){
$_smarty_tpl->tpl_vars['category']->_loop = true;
 $_smarty_tpl->tpl_vars['category']->iteration++;
?>
			<?php if (isset($_smarty_tpl->tpl_vars['class_names'])) {$_smarty_tpl->tpl_vars['class_names'] = clone $_smarty_tpl->tpl_vars['class_names'];
$_smarty_tpl->tpl_vars['class_names']->value = array('span12','span6','span4','span3'); $_smarty_tpl->tpl_vars['class_names']->nocache = null; $_smarty_tpl->tpl_vars['class_names']->scope = 0;
} else $_smarty_tpl->tpl_vars['class_names'] = new Smarty_variable(array('span12','span6','span4','span3'), null, 0);?>

			<div class="<?php echo $_smarty_tpl->tpl_vars['class_names']->value[$_smarty_tpl->tpl_vars['num_columns']->value-1];?>
">
				<div class="cat-wrap">
					<?php if ($_smarty_tpl->tpl_vars['config']->value['categories_icon_display']){?>
						<?php if ($_smarty_tpl->tpl_vars['category']->value['icon']){?>
							<img src="<?php echo $_smarty_tpl->tpl_vars['category']->value['icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
" style="height: <?php echo $_smarty_tpl->tpl_vars['config']->value['categories_icon_height'];?>
px; width: <?php echo $_smarty_tpl->tpl_vars['config']->value['categories_icon_width'];?>
px;">
						<?php }elseif($_smarty_tpl->tpl_vars['config']->value['default_categories_icon']){?>
							<img src="<?php echo $_smarty_tpl->tpl_vars['config']->value['default_categories_icon'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
" style="height: <?php echo $_smarty_tpl->tpl_vars['config']->value['categories_icon_height'];?>
px; width: <?php echo $_smarty_tpl->tpl_vars['config']->value['categories_icon_width'];?>
px;">
						<?php }else{ ?>
							<i class="icon-folder-open icon-blue"></i>
						<?php }?>
					<?php }?>

					<?php if ($_smarty_tpl->tpl_vars['category']->value['crossed']){?>@&nbsp;<?php }?><a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['category']->value),$_smarty_tpl);?>
" <?php if (isset($_smarty_tpl->tpl_vars['category']->value['no_follow'])&&$_smarty_tpl->tpl_vars['category']->value['no_follow']){?>rel="nofollow"<?php }?>><?php echo $_smarty_tpl->tpl_vars['category']->value['category_title'];?>
</a>
					<?php if ($_smarty_tpl->tpl_vars['config']->value['num_listings_display']){?>
						&mdash; <?php echo $_smarty_tpl->tpl_vars['category']->value['num_all_listings'];?>

					<?php }?>

					<?php if ($_smarty_tpl->tpl_vars['config']->value['subcats_display']&&$_smarty_tpl->tpl_vars['category']->value['subcategories']){?>
						<div class="subcat-wrap">
							<?php  $_smarty_tpl->tpl_vars['subcategory'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subcategory']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['category']->value['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['subcategory']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['subcategory']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['subcategory']->key => $_smarty_tpl->tpl_vars['subcategory']->value){
$_smarty_tpl->tpl_vars['subcategory']->_loop = true;
 $_smarty_tpl->tpl_vars['subcategory']->iteration++;
 $_smarty_tpl->tpl_vars['subcategory']->last = $_smarty_tpl->tpl_vars['subcategory']->iteration === $_smarty_tpl->tpl_vars['subcategory']->total;
?>
								<a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['subcategory']->value),$_smarty_tpl);?>
" <?php if (isset($_smarty_tpl->tpl_vars['category']->value['no_follow'])&&$_smarty_tpl->tpl_vars['category']->value['no_follow']){?>rel="nofollow"<?php }?>><?php echo $_smarty_tpl->tpl_vars['subcategory']->value['title'];?>
</a><?php if (!$_smarty_tpl->tpl_vars['subcategory']->last){?>, <?php }?>
							<?php } ?>
						</div>
					<?php }?>
				</div>
			</div>

			<?php if ($_smarty_tpl->tpl_vars['category']->iteration%$_smarty_tpl->tpl_vars['num_columns']->value==0){?>
				</div>
				<div class="row-fluid cats">
			<?php }?>
		<?php } ?>
	</div>
<?php }?><?php }} ?>