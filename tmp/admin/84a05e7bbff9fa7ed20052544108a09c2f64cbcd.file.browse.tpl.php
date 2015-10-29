<?php /* Smarty version Smarty-3.1.13, created on 2015-05-10 08:16:44
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/browse.tpl" */ ?>
<?php /*%%SmartyHeaderCode:370180829554f4c2c5ae058-90318956%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '84a05e7bbff9fa7ed20052544108a09c2f64cbcd' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/browse.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '370180829554f4c2c5ae058-90318956',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'esynI18N' => 0,
    'categories' => 0,
    'cnt' => 0,
    'config' => 0,
    'value' => 0,
    'value2' => 0,
    'cnt2' => 0,
    'min' => 0,
    'row' => 0,
    'category_id' => 0,
    'related_categories' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_554f4c2c8cdd55_37779275',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_554f4c2c8cdd55_37779275')) {function content_554f4c2c8cdd55_37779275($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_modifier_replace')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.replace.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['browse_categories']), 0);?>

<?php if ($_smarty_tpl->tpl_vars['categories']->value){?>
	<div class="categories">
	<?php if (isset($_smarty_tpl->tpl_vars["cnt"])) {$_smarty_tpl->tpl_vars["cnt"] = clone $_smarty_tpl->tpl_vars["cnt"];
$_smarty_tpl->tpl_vars["cnt"]->value = "0"; $_smarty_tpl->tpl_vars["cnt"]->nocache = null; $_smarty_tpl->tpl_vars["cnt"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable("0", null, 0);?>
	<?php if (isset($_smarty_tpl->tpl_vars["row"])) {$_smarty_tpl->tpl_vars["row"] = clone $_smarty_tpl->tpl_vars["row"];
$_smarty_tpl->tpl_vars["row"]->value = "1"; $_smarty_tpl->tpl_vars["row"]->nocache = null; $_smarty_tpl->tpl_vars["row"]->scope = 0;
} else $_smarty_tpl->tpl_vars["row"] = new Smarty_variable("1", null, 0);?>

	<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
		<?php if (isset($_smarty_tpl->tpl_vars["cnt"])) {$_smarty_tpl->tpl_vars["cnt"] = clone $_smarty_tpl->tpl_vars["cnt"];
$_smarty_tpl->tpl_vars["cnt"]->value = $_smarty_tpl->tpl_vars['cnt']->value+1; $_smarty_tpl->tpl_vars["cnt"]->nocache = null; $_smarty_tpl->tpl_vars["cnt"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?>
		<?php if (!($_smarty_tpl->tpl_vars['cnt']->value%3)||$_smarty_tpl->tpl_vars['cnt']->value==count($_smarty_tpl->tpl_vars['categories']->value)){?>
			<div class="last">
				<div class="category">
					<?php if ($_smarty_tpl->tpl_vars['config']->value['categories_icon_display']){?>
						<?php if ($_smarty_tpl->tpl_vars['value']->value['icon']){?>
							<img src="<?php echo @constant('IA_URL');?>
<?php echo $_smarty_tpl->tpl_vars['value']->value['icon'];?>
" alt="" width="32" height="32" align="left" style="margin-right: 10px;"/>
						<?php }elseif($_smarty_tpl->tpl_vars['config']->value['default_categories_icon']){?>
							<img src="<?php echo @constant('IA_URL');?>
<?php echo $_smarty_tpl->tpl_vars['config']->value['default_categories_icon'];?>
" alt="" width="32" height="32" align="left" style="margin-right: 10px;"/>
						<?php }else{ ?>
							<img src="<?php echo @constant('IA_URL');?>
templates/common/img/category_icons/category_icon.gif" alt="" width="32" height="32" align="left" style="margin-right: 10px;"/>
						<?php }?>
					<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['value']->value['crossed']){?>@&nbsp;<?php }?><a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['value']->value['status'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a>&nbsp;<?php if ($_smarty_tpl->tpl_vars['config']->value['num_listings_display']){?>(<?php echo $_smarty_tpl->tpl_vars['value']->value['num_all_listings'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['value']->value['crossed']){?>&nbsp;<a href="#" class="actions_edt-crossed_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
"><?php echo smarty_function_print_img(array('full'=>true,'fl'=>"icons/edit-grid-ico.png",'admin'=>true,'style'=>"vertical-align: middle;"),$_smarty_tpl);?>
</a>&nbsp;<a href="#" class="actions_rmv-crossed_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
"><img style="vertical-align: middle;" src="<?php echo smarty_function_print_img(array('fl'=>"remove-grid-ico.png",'folder'=>"icons/",'admin'=>"true"),$_smarty_tpl);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
"></a><?php }?>
				<?php if ($_smarty_tpl->tpl_vars['config']->value['subcats_display']){?>
					<?php if (isset($_smarty_tpl->tpl_vars['value']->value['subcategories'])&&!empty($_smarty_tpl->tpl_vars['value']->value['subcategories'])){?>
						<div class="subcategories">
						<?php if (isset($_smarty_tpl->tpl_vars["cnt2"])) {$_smarty_tpl->tpl_vars["cnt2"] = clone $_smarty_tpl->tpl_vars["cnt2"];
$_smarty_tpl->tpl_vars["cnt2"]->value = "1"; $_smarty_tpl->tpl_vars["cnt2"]->nocache = null; $_smarty_tpl->tpl_vars["cnt2"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt2"] = new Smarty_variable("1", null, 0);?>
						<?php  $_smarty_tpl->tpl_vars['value2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value2']->_loop = false;
 $_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['value']->value['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value2']->key => $_smarty_tpl->tpl_vars['value2']->value){
$_smarty_tpl->tpl_vars['value2']->_loop = true;
 $_smarty_tpl->tpl_vars['key2']->value = $_smarty_tpl->tpl_vars['value2']->key;
?>
							<?php if (count($_smarty_tpl->tpl_vars['value']->value['subcategories'])<$_smarty_tpl->tpl_vars['config']->value['subcats_display']){?>
								<?php if (isset($_smarty_tpl->tpl_vars["min"])) {$_smarty_tpl->tpl_vars["min"] = clone $_smarty_tpl->tpl_vars["min"];
$_smarty_tpl->tpl_vars["min"]->value = count($_smarty_tpl->tpl_vars['value']->value['subcategories']); $_smarty_tpl->tpl_vars["min"]->nocache = null; $_smarty_tpl->tpl_vars["min"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min"] = new Smarty_variable(count($_smarty_tpl->tpl_vars['value']->value['subcategories']), null, 0);?>
							<?php }else{ ?>
								<?php if (isset($_smarty_tpl->tpl_vars["min"])) {$_smarty_tpl->tpl_vars["min"] = clone $_smarty_tpl->tpl_vars["min"];
$_smarty_tpl->tpl_vars["min"]->value = $_smarty_tpl->tpl_vars['config']->value['subcats_display']; $_smarty_tpl->tpl_vars["min"]->nocache = null; $_smarty_tpl->tpl_vars["min"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min"] = new Smarty_variable($_smarty_tpl->tpl_vars['config']->value['subcats_display'], null, 0);?>
							<?php }?>

							<a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['value2']->value['id'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['value2']->value['status'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value2']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a><?php if ($_smarty_tpl->tpl_vars['cnt2']->value<$_smarty_tpl->tpl_vars['min']->value){?>,<?php }?>
							<?php if (isset($_smarty_tpl->tpl_vars["cnt2"])) {$_smarty_tpl->tpl_vars["cnt2"] = clone $_smarty_tpl->tpl_vars["cnt2"];
$_smarty_tpl->tpl_vars["cnt2"]->value = $_smarty_tpl->tpl_vars['cnt2']->value+1; $_smarty_tpl->tpl_vars["cnt2"]->nocache = null; $_smarty_tpl->tpl_vars["cnt2"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt2"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt2']->value+1, null, 0);?>
						<?php } ?>
						</div>
					<?php }?>
				<?php }?>
				</div>
			</div>

			<?php if ($_smarty_tpl->tpl_vars['row']->value<count($_smarty_tpl->tpl_vars['categories']->value)/3){?>
				<div class="divider clearfix" style="clear: left;"></div>
			<?php }?>
			<?php if (isset($_smarty_tpl->tpl_vars["row"])) {$_smarty_tpl->tpl_vars["row"] = clone $_smarty_tpl->tpl_vars["row"];
$_smarty_tpl->tpl_vars["row"]->value = $_smarty_tpl->tpl_vars['row']->value+1; $_smarty_tpl->tpl_vars["row"]->nocache = null; $_smarty_tpl->tpl_vars["row"]->scope = 0;
} else $_smarty_tpl->tpl_vars["row"] = new Smarty_variable($_smarty_tpl->tpl_vars['row']->value+1, null, 0);?>
		<?php }else{ ?>
			<div class="col">
				<div class="category">
					<?php if ($_smarty_tpl->tpl_vars['config']->value['categories_icon_display']){?>
						<?php if ($_smarty_tpl->tpl_vars['value']->value['icon']){?>
							<img src="<?php echo @constant('IA_URL');?>
<?php echo $_smarty_tpl->tpl_vars['value']->value['icon'];?>
" alt="" width="32" height="32" align="left" style="margin-right: 10px;"/>
						<?php }elseif($_smarty_tpl->tpl_vars['config']->value['default_categories_icon']){?>
							<img src="<?php echo @constant('IA_URL');?>
<?php echo $_smarty_tpl->tpl_vars['config']->value['default_categories_icon'];?>
" alt="" width="32" height="32" align="left" style="margin-right: 10px;"/>
						<?php }else{ ?>
							<img src="<?php echo @constant('IA_URL');?>
templates/common/img/category_icons/category_icon.gif" alt="" width="32" height="32" align="left" style="margin-right: 10px;"/>
						<?php }?>
					<?php }?>

					<?php if ($_smarty_tpl->tpl_vars['value']->value['crossed']){?>@&nbsp;<?php }?><a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['value']->value['status'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a>&nbsp;<?php if ($_smarty_tpl->tpl_vars['config']->value['num_listings_display']){?>(<?php echo $_smarty_tpl->tpl_vars['value']->value['num_all_listings'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['value']->value['crossed']){?>&nbsp;<a href="#" class="actions_edt-crossed_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
"><?php echo smarty_function_print_img(array('full'=>true,'fl'=>"icons/edit-grid-ico.png",'admin'=>true,'style'=>"vertical-align: middle;"),$_smarty_tpl);?>
</a>&nbsp;<a href="#" class="actions_rmv-crossed_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
"><img style="vertical-align: middle;" src="<?php echo smarty_function_print_img(array('fl'=>"remove-grid-ico.png",'folder'=>"icons/",'admin'=>"true"),$_smarty_tpl);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
"></a><?php }?>
					<?php if ($_smarty_tpl->tpl_vars['config']->value['subcats_display']){?>
						<?php if (isset($_smarty_tpl->tpl_vars['value']->value['subcategories'])&&!empty($_smarty_tpl->tpl_vars['value']->value['subcategories'])){?>
							<div class="subcategories">
							<?php if (isset($_smarty_tpl->tpl_vars["cnt2"])) {$_smarty_tpl->tpl_vars["cnt2"] = clone $_smarty_tpl->tpl_vars["cnt2"];
$_smarty_tpl->tpl_vars["cnt2"]->value = "1"; $_smarty_tpl->tpl_vars["cnt2"]->nocache = null; $_smarty_tpl->tpl_vars["cnt2"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt2"] = new Smarty_variable("1", null, 0);?>
							<?php  $_smarty_tpl->tpl_vars['value2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value2']->_loop = false;
 $_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['value']->value['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value2']->key => $_smarty_tpl->tpl_vars['value2']->value){
$_smarty_tpl->tpl_vars['value2']->_loop = true;
 $_smarty_tpl->tpl_vars['key2']->value = $_smarty_tpl->tpl_vars['value2']->key;
?>
								<?php if (count($_smarty_tpl->tpl_vars['value']->value['subcategories'])<$_smarty_tpl->tpl_vars['config']->value['subcats_display']){?>
									<?php if (isset($_smarty_tpl->tpl_vars["min"])) {$_smarty_tpl->tpl_vars["min"] = clone $_smarty_tpl->tpl_vars["min"];
$_smarty_tpl->tpl_vars["min"]->value = count($_smarty_tpl->tpl_vars['value']->value['subcategories']); $_smarty_tpl->tpl_vars["min"]->nocache = null; $_smarty_tpl->tpl_vars["min"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min"] = new Smarty_variable(count($_smarty_tpl->tpl_vars['value']->value['subcategories']), null, 0);?>
								<?php }else{ ?>
									<?php if (isset($_smarty_tpl->tpl_vars["min"])) {$_smarty_tpl->tpl_vars["min"] = clone $_smarty_tpl->tpl_vars["min"];
$_smarty_tpl->tpl_vars["min"]->value = $_smarty_tpl->tpl_vars['config']->value['subcats_display']; $_smarty_tpl->tpl_vars["min"]->nocache = null; $_smarty_tpl->tpl_vars["min"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min"] = new Smarty_variable($_smarty_tpl->tpl_vars['config']->value['subcats_display'], null, 0);?>
								<?php }?>

								<a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['value2']->value['id'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['value2']->value['status'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value2']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a><?php if ($_smarty_tpl->tpl_vars['cnt2']->value<$_smarty_tpl->tpl_vars['min']->value){?>,<?php }?>
								<?php if (isset($_smarty_tpl->tpl_vars["cnt2"])) {$_smarty_tpl->tpl_vars["cnt2"] = clone $_smarty_tpl->tpl_vars["cnt2"];
$_smarty_tpl->tpl_vars["cnt2"]->value = $_smarty_tpl->tpl_vars['cnt2']->value+1; $_smarty_tpl->tpl_vars["cnt2"]->nocache = null; $_smarty_tpl->tpl_vars["cnt2"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt2"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt2']->value+1, null, 0);?>
							<?php } ?>
							</div>
						<?php }?>
					<?php }?>
				</div>
			</div>
		<?php }?>
	<?php } ?>
	</div>

	<div style="clear:both;">&nbsp;</div>
<?php }else{ ?>
	<?php if (isset($_GET['id'])){?>
		<?php if (isset($_smarty_tpl->tpl_vars["category_id"])) {$_smarty_tpl->tpl_vars["category_id"] = clone $_smarty_tpl->tpl_vars["category_id"];
$_smarty_tpl->tpl_vars["category_id"]->value = $_GET['id']; $_smarty_tpl->tpl_vars["category_id"]->nocache = null; $_smarty_tpl->tpl_vars["category_id"]->scope = 0;
} else $_smarty_tpl->tpl_vars["category_id"] = new Smarty_variable($_GET['id'], null, 0);?>
	<?php }else{ ?>
		<?php if (isset($_smarty_tpl->tpl_vars["category_id"])) {$_smarty_tpl->tpl_vars["category_id"] = clone $_smarty_tpl->tpl_vars["category_id"];
$_smarty_tpl->tpl_vars["category_id"]->value = 0; $_smarty_tpl->tpl_vars["category_id"]->nocache = null; $_smarty_tpl->tpl_vars["category_id"]->scope = 0;
} else $_smarty_tpl->tpl_vars["category_id"] = new Smarty_variable(0, null, 0);?>
	<?php }?>

	<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['esynI18N']->value['no_categories'],"[category_id]",$_smarty_tpl->tpl_vars['category_id']->value);?>

<?php }?>
<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>



<?php if (isset($_smarty_tpl->tpl_vars['related_categories']->value)&&!empty($_smarty_tpl->tpl_vars['related_categories']->value)){?>

	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['related_categories']), 0);?>


	<div class="categories">

		<?php if (isset($_smarty_tpl->tpl_vars["cnt"])) {$_smarty_tpl->tpl_vars["cnt"] = clone $_smarty_tpl->tpl_vars["cnt"];
$_smarty_tpl->tpl_vars["cnt"]->value = "0"; $_smarty_tpl->tpl_vars["cnt"]->nocache = null; $_smarty_tpl->tpl_vars["cnt"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable("0", null, 0);?>
		<?php if (isset($_smarty_tpl->tpl_vars["row"])) {$_smarty_tpl->tpl_vars["row"] = clone $_smarty_tpl->tpl_vars["row"];
$_smarty_tpl->tpl_vars["row"]->value = "1"; $_smarty_tpl->tpl_vars["row"]->nocache = null; $_smarty_tpl->tpl_vars["row"]->scope = 0;
} else $_smarty_tpl->tpl_vars["row"] = new Smarty_variable("1", null, 0);?>
		
		<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['related_categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
			<?php if (isset($_smarty_tpl->tpl_vars["cnt"])) {$_smarty_tpl->tpl_vars["cnt"] = clone $_smarty_tpl->tpl_vars["cnt"];
$_smarty_tpl->tpl_vars["cnt"]->value = $_smarty_tpl->tpl_vars['cnt']->value+1; $_smarty_tpl->tpl_vars["cnt"]->nocache = null; $_smarty_tpl->tpl_vars["cnt"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?>
			<?php if (!($_smarty_tpl->tpl_vars['cnt']->value%3)||$_smarty_tpl->tpl_vars['cnt']->value==count($_smarty_tpl->tpl_vars['related_categories']->value)){?>
				<div class="last">
					<div class="category">
						<a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['value']->value['status'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a>
							&nbsp;<?php if ($_smarty_tpl->tpl_vars['config']->value['num_listings_display']){?>(<?php echo $_smarty_tpl->tpl_vars['value']->value['num_all_listings'];?>
)<?php }?>
							&nbsp;<a href="#" class="actions_edt-related_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
"><?php echo smarty_function_print_img(array('full'=>true,'fl'=>"icons/edit-grid-ico.png",'admin'=>true,'style'=>"vertical-align: middle;"),$_smarty_tpl);?>
</a>
							&nbsp;<a href="#" class="actions_rmv-related_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
"><img style="vertical-align: middle;" src="<?php echo smarty_function_print_img(array('fl'=>"remove-grid-ico.png",'folder'=>"icons/",'admin'=>"true"),$_smarty_tpl);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
">
						</a>
					<?php if ($_smarty_tpl->tpl_vars['config']->value['subcats_display']){?>
						<?php if (isset($_smarty_tpl->tpl_vars['value']->value['subcategories'])&&!empty($_smarty_tpl->tpl_vars['value']->value['subcategories'])){?>
							<div class="subcategories">
							<?php if (isset($_smarty_tpl->tpl_vars["cnt2"])) {$_smarty_tpl->tpl_vars["cnt2"] = clone $_smarty_tpl->tpl_vars["cnt2"];
$_smarty_tpl->tpl_vars["cnt2"]->value = "1"; $_smarty_tpl->tpl_vars["cnt2"]->nocache = null; $_smarty_tpl->tpl_vars["cnt2"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt2"] = new Smarty_variable("1", null, 0);?>
							<?php  $_smarty_tpl->tpl_vars['value2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value2']->_loop = false;
 $_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['value']->value['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value2']->key => $_smarty_tpl->tpl_vars['value2']->value){
$_smarty_tpl->tpl_vars['value2']->_loop = true;
 $_smarty_tpl->tpl_vars['key2']->value = $_smarty_tpl->tpl_vars['value2']->key;
?>
								<?php if (count($_smarty_tpl->tpl_vars['value']->value['subcategories'])<$_smarty_tpl->tpl_vars['config']->value['subcats_display']){?>
									<?php if (isset($_smarty_tpl->tpl_vars["min"])) {$_smarty_tpl->tpl_vars["min"] = clone $_smarty_tpl->tpl_vars["min"];
$_smarty_tpl->tpl_vars["min"]->value = count($_smarty_tpl->tpl_vars['value']->value['subcategories']); $_smarty_tpl->tpl_vars["min"]->nocache = null; $_smarty_tpl->tpl_vars["min"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min"] = new Smarty_variable(count($_smarty_tpl->tpl_vars['value']->value['subcategories']), null, 0);?>
								<?php }else{ ?>
									<?php if (isset($_smarty_tpl->tpl_vars["min"])) {$_smarty_tpl->tpl_vars["min"] = clone $_smarty_tpl->tpl_vars["min"];
$_smarty_tpl->tpl_vars["min"]->value = $_smarty_tpl->tpl_vars['config']->value['subcats_display']; $_smarty_tpl->tpl_vars["min"]->nocache = null; $_smarty_tpl->tpl_vars["min"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min"] = new Smarty_variable($_smarty_tpl->tpl_vars['config']->value['subcats_display'], null, 0);?>
								<?php }?>
								
								<a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['value2']->value['id'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['value2']->value['status'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value2']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a><?php if ($_smarty_tpl->tpl_vars['cnt2']->value<$_smarty_tpl->tpl_vars['min']->value){?>,<?php }?>
								<?php if (isset($_smarty_tpl->tpl_vars["cnt2"])) {$_smarty_tpl->tpl_vars["cnt2"] = clone $_smarty_tpl->tpl_vars["cnt2"];
$_smarty_tpl->tpl_vars["cnt2"]->value = $_smarty_tpl->tpl_vars['cnt2']->value+1; $_smarty_tpl->tpl_vars["cnt2"]->nocache = null; $_smarty_tpl->tpl_vars["cnt2"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt2"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt2']->value+1, null, 0);?>
							<?php } ?>
							</div>
						<?php }?>
					<?php }?>	
				</div></div>
				<?php if ($_smarty_tpl->tpl_vars['row']->value<count($_smarty_tpl->tpl_vars['related_categories']->value)/3){?>
					<div class="divider clearfix" style="clear: left;"></div>
				<?php }?>
				<?php if (isset($_smarty_tpl->tpl_vars["row"])) {$_smarty_tpl->tpl_vars["row"] = clone $_smarty_tpl->tpl_vars["row"];
$_smarty_tpl->tpl_vars["row"]->value = $_smarty_tpl->tpl_vars['row']->value+1; $_smarty_tpl->tpl_vars["row"]->nocache = null; $_smarty_tpl->tpl_vars["row"]->scope = 0;
} else $_smarty_tpl->tpl_vars["row"] = new Smarty_variable($_smarty_tpl->tpl_vars['row']->value+1, null, 0);?>
			<?php }else{ ?>
				<div class="col">
					<div class="category">
						<a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['value']->value['status'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a>
							&nbsp;<?php if ($_smarty_tpl->tpl_vars['config']->value['num_listings_display']){?>(<?php echo $_smarty_tpl->tpl_vars['value']->value['num_all_listings'];?>
)<?php }?>
							&nbsp;<a href="#" class="actions_edt-related_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
"><?php echo smarty_function_print_img(array('full'=>true,'fl'=>"icons/edit-grid-ico.png",'admin'=>true,'style'=>"vertical-align: middle;"),$_smarty_tpl);?>
</a>
							&nbsp;<a href="#" class="actions_rmv-related_<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
"><img style="vertical-align: middle;" src="<?php echo smarty_function_print_img(array('fl'=>"remove-grid-ico.png",'folder'=>"icons/",'admin'=>"true"),$_smarty_tpl);?>
" alt="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
">
						</a>
					<?php if ($_smarty_tpl->tpl_vars['config']->value['subcats_display']){?>
						<?php if (isset($_smarty_tpl->tpl_vars['value']->value['subcategories'])&&!empty($_smarty_tpl->tpl_vars['value']->value['subcategories'])){?>
							<div class="subcategories">
							<?php if (isset($_smarty_tpl->tpl_vars["cnt2"])) {$_smarty_tpl->tpl_vars["cnt2"] = clone $_smarty_tpl->tpl_vars["cnt2"];
$_smarty_tpl->tpl_vars["cnt2"]->value = "1"; $_smarty_tpl->tpl_vars["cnt2"]->nocache = null; $_smarty_tpl->tpl_vars["cnt2"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt2"] = new Smarty_variable("1", null, 0);?>
							<?php  $_smarty_tpl->tpl_vars['value2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value2']->_loop = false;
 $_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['value']->value['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value2']->key => $_smarty_tpl->tpl_vars['value2']->value){
$_smarty_tpl->tpl_vars['value2']->_loop = true;
 $_smarty_tpl->tpl_vars['key2']->value = $_smarty_tpl->tpl_vars['value2']->key;
?>
								<?php if (count($_smarty_tpl->tpl_vars['value']->value['subcategories'])<$_smarty_tpl->tpl_vars['config']->value['subcats_display']){?>
									<?php if (isset($_smarty_tpl->tpl_vars["min"])) {$_smarty_tpl->tpl_vars["min"] = clone $_smarty_tpl->tpl_vars["min"];
$_smarty_tpl->tpl_vars["min"]->value = count($_smarty_tpl->tpl_vars['value']->value['subcategories']); $_smarty_tpl->tpl_vars["min"]->nocache = null; $_smarty_tpl->tpl_vars["min"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min"] = new Smarty_variable(count($_smarty_tpl->tpl_vars['value']->value['subcategories']), null, 0);?>
								<?php }else{ ?>
									<?php if (isset($_smarty_tpl->tpl_vars["min"])) {$_smarty_tpl->tpl_vars["min"] = clone $_smarty_tpl->tpl_vars["min"];
$_smarty_tpl->tpl_vars["min"]->value = $_smarty_tpl->tpl_vars['config']->value['subcats_display']; $_smarty_tpl->tpl_vars["min"]->nocache = null; $_smarty_tpl->tpl_vars["min"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min"] = new Smarty_variable($_smarty_tpl->tpl_vars['config']->value['subcats_display'], null, 0);?>
								<?php }?>
								
								<a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['value2']->value['id'];?>
" class="<?php echo $_smarty_tpl->tpl_vars['value2']->value['status'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value2']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
</a><?php if ($_smarty_tpl->tpl_vars['cnt2']->value<$_smarty_tpl->tpl_vars['min']->value){?>,<?php }?>
								<?php if (isset($_smarty_tpl->tpl_vars["cnt2"])) {$_smarty_tpl->tpl_vars["cnt2"] = clone $_smarty_tpl->tpl_vars["cnt2"];
$_smarty_tpl->tpl_vars["cnt2"]->value = $_smarty_tpl->tpl_vars['cnt2']->value+1; $_smarty_tpl->tpl_vars["cnt2"]->nocache = null; $_smarty_tpl->tpl_vars["cnt2"]->scope = 0;
} else $_smarty_tpl->tpl_vars["cnt2"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt2']->value+1, null, 0);?>
							<?php } ?>
							</div>
						<?php }?>
					<?php }?>
				</div></div>
			<?php }?>
		<?php } ?>
	</div>

	<div style="clear:both;">&nbsp;</div>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?>

<div id="box_listings" style="margin-top: 15px;"></div>

<div id="remove_reason" style="display: none;">
	<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listing_remove_reason'];?>
<br />
	<textarea cols="40" rows="5" name="body" id="remove_reason_text" class="common" style="width: 99%;"></textarea>
</div>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/browse, js/utils/dutil"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>