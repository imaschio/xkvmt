<?php /* Smarty version Smarty-3.1.13, created on 2015-09-27 11:26:37
         compiled from "/home/wwwsyaqd/public_html/plugins/slider/admin/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:143105561456080aada4b616-70428275%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c8af5f140b75da96c11d1ac2660aaa743af69cc2' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/slider/admin/templates/index.tpl',
      1 => 1425025906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '143105561456080aada4b616-70428275',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'esynI18N' => 0,
    'category' => 0,
    'slide' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56080aadbe4054_53724065',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56080aadbe4054_53724065')) {function content_56080aadbe4054_53724065($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php if (isset($_GET['do'])&&($_GET['do']=='add'||$_GET['do']=='edit')){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>


	<form action="controller.php?plugin=slider&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post" enctype="multipart/form-data">

	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>


	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['slider_category'];?>
</strong></td>
		<td>
			<span id="parent_category_title_container">
				<strong><?php if (isset($_smarty_tpl->tpl_vars['category']->value['title'])){?><a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
</a><?php }else{ ?>ROOT<?php }?></strong>
			</span>&nbsp;
			<a href="#" id="change_category"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['change'];?>
...</a>&nbsp;

			<input type="hidden" id="category_id" name="category_id" value="<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" />
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
</strong></td>
		<td><input type="text" name="title" size="32" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['slide']->value['title'])){?><?php echo $_smarty_tpl->tpl_vars['slide']->value['title'];?>
<?php }elseif(isset($_POST['title'])){?><?php echo $_POST['title'];?>
<?php }?>" /></td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['classname'];?>
</strong></td>
		<td><input type="text" name="classname" size="32" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['slide']->value['classname'])){?><?php echo $_smarty_tpl->tpl_vars['slide']->value['classname'];?>
<?php }elseif(isset($_POST['classname'])){?><?php echo $_POST['classname'];?>
<?php }?>" /></td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['description'];?>
</strong></td>
		<td>
			<div class="ckeditor_textarea">
				<textarea class="ckeditor_textarea" id="description" name="description">
					<?php if (isset($_smarty_tpl->tpl_vars['slide']->value['description'])){?><?php echo $_smarty_tpl->tpl_vars['slide']->value['description'];?>
<?php }elseif(isset($_POST['description'])){?><?php echo $_POST['description'];?>
<?php }?>
				</textarea>
			</div>
		</td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-sliderimage">
			<strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image'];?>
</strong>
			<div style="display:none;"><div id="tip-content-sliderimage"><?php echo smarty_function_lang(array('key'=>'slider_image_desc'),$_smarty_tpl);?>
</div></div>
		</td>
		<td>
			<input type="file" name="image" size="35" id="image" />
			<?php if ($_smarty_tpl->tpl_vars['slide']->value['image']!=''){?><a href="<?php echo @constant('ESYN_URL');?>
uploads/<?php echo $_smarty_tpl->tpl_vars['slide']->value['image'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['slide']->value['image'];?>
</a><?php }?>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['order'];?>
</strong></td>
		<td><input type="text" name="order" size="4" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['slide']->value['order'])){?><?php echo $_smarty_tpl->tpl_vars['slide']->value['order'];?>
<?php }elseif(isset($_POST['order'])){?><?php echo $_POST['order'];?>
<?php }?>" /></td>
	</tr>
	<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status'];?>
:</strong></td>
		<td> 
			<select name="status">
				<option value="active" <?php if (isset($_smarty_tpl->tpl_vars['slide']->value['status'])&&$_smarty_tpl->tpl_vars['slide']->value['status']=='active'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='active'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</option>
				<option value="inactive" <?php if (isset($_smarty_tpl->tpl_vars['slide']->value['status'])&&$_smarty_tpl->tpl_vars['slide']->value['status']=='inactive'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='inactive'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['inactive'];?>
</option>
			</select>
		</td>
	</tr>
	<?php }?>

	<tr class="all">
		<td colspan="2">
			<input type="hidden" name="do" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>" />
			<input type="hidden" name="id" value="<?php if (isset($_smarty_tpl->tpl_vars['slide']->value['id'])){?><?php echo $_smarty_tpl->tpl_vars['slide']->value['id'];?>
<?php }?>" />
			<input type="submit" name="save" class="common" value="<?php if ($_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
<?php }?>" />

			<span><?php echo $_smarty_tpl->tpl_vars['gTitle']->value;?>
 <strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['and_then'];?>
</strong></span>
			<select name="goto">
				<option value="list" <?php if (isset($_POST['goto'])&&$_POST['goto']=='list'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go_to_list'];?>
</option>
				<option value="add" <?php if (isset($_POST['goto'])&&$_POST['goto']=='add'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add_another_one'];?>
</option>
			</select>
		</td>
	</tr>
	</table>
	</form>
	
	<?php echo $_smarty_tpl->getSubTemplate ("box-footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('class'=>"box"), 0);?>

<?php }else{ ?>
	<div id="box_slider" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/slider/js/admin/slider"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>