<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 17:13:19
         compiled from "/home/wwwsyaqd/public_html/plugins/categories_boxes/templates/block.categories-box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:50233631854f0ebffbc3c13-90322698%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e2ee4928f06ed942537557491b008b799ba16245' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/categories_boxes/templates/block.categories-box.tpl',
      1 => 1425025912,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '50233631854f0ebffbc3c13-90322698',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'boxes_categories' => 0,
    'config' => 0,
    'top_categories' => 0,
    'popular_category' => 0,
    'lang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f0ebffc2d159_84490441',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f0ebffc2d159_84490441')) {function content_54f0ebffc2d159_84490441($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><?php if (!empty($_smarty_tpl->tpl_vars['boxes_categories']->value['top'])||!empty($_smarty_tpl->tpl_vars['boxes_categories']->value['popular'])){?>
<ul class="nav nav-tabs" id="categoriesTabs">
	<?php if ($_smarty_tpl->tpl_vars['config']->value['num_top_cat']>'0'){?>
		<li class="tab_top<?php if ($_smarty_tpl->tpl_vars['config']->value['num_popular_cat']=='0'){?> active<?php }else{ ?> active<?php }?>"><a href="#tab-pane_catTop" data-toggle="tab"><?php echo smarty_function_lang(array('key'=>'categories_box_top'),$_smarty_tpl);?>
</a></li>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['config']->value['num_popular_cat']>'0'){?>
		<li class="tab_popular<?php if ($_smarty_tpl->tpl_vars['config']->value['num_top_cat']=='0'){?> active<?php }?>"><a href="#tab-pane_catPopular" data-toggle="tab"><?php echo smarty_function_lang(array('key'=>'categories_box_popular'),$_smarty_tpl);?>
</a></li>
	<?php }?>
</ul>

<div class="tab-content" id="categoriesTabsContent">
	<?php if ($_smarty_tpl->tpl_vars['config']->value['num_top_cat']>'0'){?>
		<div class="tab-pane<?php if ($_smarty_tpl->tpl_vars['config']->value['num_popular_cat']=='0'){?> active<?php }else{ ?> active<?php }?>" id="tab-pane_catTop">
			<div class="ia-wrap">
				<?php if (!empty($_smarty_tpl->tpl_vars['boxes_categories']->value['top'])){?>
					<ul class="nav nav-actions">
						<?php  $_smarty_tpl->tpl_vars['top_categories'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['top_categories']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['boxes_categories']->value['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['top_categories']->key => $_smarty_tpl->tpl_vars['top_categories']->value){
$_smarty_tpl->tpl_vars['top_categories']->_loop = true;
?>
							<li><a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['top_categories']->value),$_smarty_tpl);?>
"<?php if ($_smarty_tpl->tpl_vars['top_categories']->value['no_follow']){?> rel="nofollow"<?php }?>><?php echo $_smarty_tpl->tpl_vars['top_categories']->value['title'];?>
</a></li>
						<?php } ?>
					</ul>
				<?php }else{ ?>
					<div class="alert alert-info"><?php echo smarty_function_lang(array('key'=>'no_categories'),$_smarty_tpl);?>
</div>
				<?php }?>
			</div>
		</div>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['config']->value['num_popular_cat']>'0'){?>
		<div class="tab-pane<?php if ($_smarty_tpl->tpl_vars['config']->value['num_top_cat']=='0'){?> active<?php }?>" id="tab-pane_catPopular">
			<div class="ia-wrap">
				<?php if (!empty($_smarty_tpl->tpl_vars['boxes_categories']->value['popular'])){?>
					<ul class="nav nav-actions">
						<?php  $_smarty_tpl->tpl_vars['popular_category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['popular_category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['boxes_categories']->value['popular']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['popular_category']->key => $_smarty_tpl->tpl_vars['popular_category']->value){
$_smarty_tpl->tpl_vars['popular_category']->_loop = true;
?>
							<li><a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['popular_category']->value),$_smarty_tpl);?>
"<?php if ($_smarty_tpl->tpl_vars['popular_category']->value['no_follow']){?> rel="nofollow"<?php }?>><?php echo $_smarty_tpl->tpl_vars['popular_category']->value['title'];?>
 <sup title="<?php echo $_smarty_tpl->tpl_vars['lang']->value['clicks'];?>
"><?php echo $_smarty_tpl->tpl_vars['popular_category']->value['clicks'];?>
</sup></a></li>
						<?php } ?>
					</ul>
				<?php }else{ ?>
					<div class="alert alert-info"><?php echo smarty_function_lang(array('key'=>'no_categories'),$_smarty_tpl);?>
</div>
				<?php }?>
			</div>
		</div>
	<?php }?>
</div>
<?php }?><?php }} ?>