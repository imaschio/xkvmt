<?php /* Smarty version Smarty-3.1.13, created on 2015-03-30 11:46:58
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/suggest-category.tpl" */ ?>
<?php /*%%SmartyHeaderCode:31427364255196ff20a5d77-92873235%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0c73d9f3eea95fd8505d9e4ac342d61e77d9bc6e' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/suggest-category.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31427364255196ff20a5d77-92873235',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'parent' => 0,
    'esynI18N' => 0,
    'category' => 0,
    'columns_num_array' => 0,
    'column' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55196ff23c9dd3_56375561',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55196ff23c9dd3_56375561')) {function content_55196ff23c9dd3_56375561($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/chooser/css/chooser"), 0);?>


<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

<form action="controller.php?file=suggest-category<?php if (isset($_GET['id'])){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?><?php if (isset($_GET['do'])){?>&amp;do=<?php echo $_GET['do'];?>
<?php }?>" method="post">
<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

<table cellspacing="0" cellpadding="0" width="100%" class="striped">

<?php if (isset($_smarty_tpl->tpl_vars['parent']->value)&&!empty($_smarty_tpl->tpl_vars['parent']->value)){?>
	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['parent_category'];?>
:</strong></td>
		<td>
			<span id="parent_category_title_container"><strong><?php if (isset($_smarty_tpl->tpl_vars['parent']->value)&&!empty($_smarty_tpl->tpl_vars['parent']->value)){?><a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['parent']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['parent']->value['title'];?>
</a><?php }else{ ?><a href="controller.php?file=browse"><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
</a><?php }?></strong>&nbsp;|&nbsp;<a href="#" id="change_category"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['change'];?>
...</a></span>
			<input type="hidden" id="parent_id" name="parent_id" value="<?php if (isset($_smarty_tpl->tpl_vars['parent']->value)&&!empty($_smarty_tpl->tpl_vars['parent']->value)){?><?php echo $_smarty_tpl->tpl_vars['parent']->value['id'];?>
<?php }?>">
		</td>
	</tr>
<?php }?>

<tr>
	<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
:</strong></td>
	<td><input type="text" name="title" size="30" maxlength="150" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['title'])&&isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
<?php }elseif(isset($_POST['title'])){?><?php echo htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
</tr>

<tr>
	<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['page_title'];?>
:</strong></td>
	<td><input type="text" name="page_title" size="30" maxlength="150" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['page_title'])&&isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['category']->value['page_title'];?>
<?php }elseif(isset($_POST['page_title'])){?><?php echo htmlspecialchars($_POST['page_title'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
</tr>

<?php if (isset($_smarty_tpl->tpl_vars['parent']->value)&&!empty($_smarty_tpl->tpl_vars['parent']->value)){?>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['path'];?>
:</strong></td>
		<td>
			<input type="text" name="path" size="30" maxlength="150" class="common" style="float: left;" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['path'])&&isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['category']->value['path'];?>
<?php }elseif(isset($_POST['path'])){?><?php echo htmlspecialchars($_POST['path'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">&nbsp;
			<div style="float: left; display: none; margin-left: 3px; padding: 4px;" id="category_url_box"><span><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['category_url_will_be'];?>
:&nbsp;<span><span id="category_url" style="padding: 3px; margin: 0; background: #FFE269;"></span></div>
		</td>
	</tr>
<?php }?>

<tr>
	<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['description'];?>
:</strong></td>
	<td>
		<textarea name="description" id="description"><?php if (isset($_smarty_tpl->tpl_vars['category']->value['description'])&&isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['category']->value['description'];?>
<?php }elseif(isset($_POST['description'])){?><?php echo $_POST['description'];?>
<?php }?></textarea>
	</td>
</tr>

<tr>
	<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['meta_description'];?>
:</strong></td>
	<td>
		<textarea name="meta_description" cols="43" rows="8" class="common"><?php if (isset($_smarty_tpl->tpl_vars['category']->value['meta_description'])&&isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['category']->value['meta_description'];?>
<?php }elseif(isset($_POST['meta_description'])){?><?php echo htmlspecialchars($_POST['meta_description'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></textarea>
	</td>
</tr>

<tr>
	<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['meta_keywords'];?>
:</strong></td>
	<td><input type="text" name="meta_keywords" size="60" maxlength="150" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['meta_keywords'])&&isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['category']->value['meta_keywords'];?>
<?php }elseif(isset($_POST['meta_keywords'])){?><?php echo htmlspecialchars($_POST['meta_keywords'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
</tr>

<tr>
	<td class="first"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['enable_no_follow'];?>
:</strong></td>
	<td><?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @$_smarty_tpl->tpl_vars['category']->value['no_follow'])===null||$tmp==='' ? 0 : $tmp),'name'=>"no_follow"),$_smarty_tpl);?>
</td>
</tr>

<tr>
	<td class="first"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['lock_category'];?>
:</strong></td>
	<td>
		<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @$_smarty_tpl->tpl_vars['category']->value['locked'])===null||$tmp==='' ? 0 : $tmp),'name'=>"locked"),$_smarty_tpl);?>

		<div style="padding: 5px 0 0 100px;"><label><input type="checkbox" name="subcategories">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['include_subcats'];?>
</label></div>
	</td>
</tr>
<?php if (!isset($_smarty_tpl->tpl_vars['category']->value['id'])||(isset($_smarty_tpl->tpl_vars['category']->value['id'])&&$_smarty_tpl->tpl_vars['category']->value['id']>0)){?>
	<tr>
		<td class="tip-header first" id="tip-header-hide_category"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['hide_category'];?>
:</strong></td>
		<td><?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @$_smarty_tpl->tpl_vars['category']->value['hidden'])===null||$tmp==='' ? 0 : $tmp),'name'=>"hidden"),$_smarty_tpl);?>
</td>
	</tr>
<?php }?>

<tr>
	<td class="first"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['unique_category_template'];?>
:</strong></td>
	<td><?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @$_smarty_tpl->tpl_vars['category']->value['unique_tpl'])===null||$tmp==='' ? 0 : $tmp),'name'=>"unique_tpl"),$_smarty_tpl);?>
</td>
</tr>

<tr>
	<td class="first"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['number_of_columns'];?>
:</strong></td>
	<td>
		<select name="num_cols">
			<?php  $_smarty_tpl->tpl_vars['column'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['column']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['columns_num_array']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['column']->key => $_smarty_tpl->tpl_vars['column']->value){
$_smarty_tpl->tpl_vars['column']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['column']->key;?>
" <?php if (isset($_smarty_tpl->tpl_vars['category']->value['num_cols'])&&isset($_GET['do'])&&$_GET['do']=='edit'&&$_smarty_tpl->tpl_vars['category']->value['num_cols']==$_smarty_tpl->tpl_vars['column']->key){?>selected="selected"<?php }elseif(isset($_POST['num_cols_type'])&&$_POST['num_cols_type']==$_smarty_tpl->tpl_vars['column']->key){?>selected="selected"<?php }elseif(!$_POST){?>checked="checked"<?php }?>><?php echo $_smarty_tpl->tpl_vars['column']->value;?>
</option>
			<?php } ?>
		</select>
	</td>
</tr>

<?php if ($_smarty_tpl->tpl_vars['config']->value['neighbour']){?>
<tr>
	<td class="first"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['number_of_neighbours'];?>
:</strong></td>
	<td>
		<span style="float: left;">
			<input type="radio" name="num_neighbours_type" value="-1" <?php if (isset($_smarty_tpl->tpl_vars['category']->value['num_neighbours'])&&isset($_GET['do'])&&$_GET['do']=='edit'&&$_smarty_tpl->tpl_vars['category']->value['num_neighbours']=='0'){?>checked="checked"<?php }elseif(isset($_POST['num_neighbours_type'])&&$_POST['num_neighbours_type']=='-1'){?>checked="checked"<?php }elseif(!$_POST){?>checked="checked"<?php }?> id="nnc0"><label for="nnc0">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['do_not_display_neighbours'];?>
</label>
			<input type="radio" name="num_neighbours_type" value="0" <?php if (isset($_smarty_tpl->tpl_vars['category']->value['num_neighbours'])&&isset($_GET['do'])&&$_GET['do']=='edit'&&$_smarty_tpl->tpl_vars['category']->value['num_neighbours']=='-1'){?>checked="checked"<?php }elseif(isset($_POST['num_neighbours_type'])&&$_POST['num_neighbours_type']=='0'){?>checked="checked"<?php }?> id="nnc1"><label for="nnc1">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['all_neighbours'];?>
</label>
			<input type="radio" name="num_neighbours_type" value="1" <?php if (isset($_smarty_tpl->tpl_vars['category']->value['num_neighbours'])&&isset($_GET['do'])&&$_GET['do']=='edit'&&$_smarty_tpl->tpl_vars['category']->value['num_neighbours']>0){?>checked="checked"<?php }elseif(isset($_POST['num_neighbours_type'])&&$_POST['num_neighbours_type']=='1'){?>checked="checked"<?php }?> id="nnc2"/><label for="nnc2">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['custom'];?>
</label>&nbsp;&nbsp;&nbsp;
		</span>
		<span id="nnc" style="display: none;">
			<input class="common numeric" type="text" name="num_neighbours" size="5" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['num_neighbours'])){?><?php echo $_smarty_tpl->tpl_vars['category']->value['num_neighbours'];?>
<?php }elseif(isset($_POST['num_neighbours'])){?><?php echo $_POST['num_neighbours'];?>
<?php }?>" style="text-align: right;">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['number_of_neigh_tip'];?>
</span>
	</td>
</tr>
<?php }?>

<tr>
	<td class="tip-header first" id="tip-header-confirmation" valign="top"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['confirmation'];?>
:</strong></td>
	<td>
		<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @$_smarty_tpl->tpl_vars['category']->value['confirmation'])===null||$tmp==='' ? 0 : $tmp),'name'=>"confirmation"),$_smarty_tpl);?>


		<div id="confirmation_text" style="padding-top: 30px; display: none;">
			<textarea name="confirmation_text" cols="43" rows="8" class="common"><?php if (isset($_smarty_tpl->tpl_vars['category']->value['confirmation_text'])){?><?php echo $_smarty_tpl->tpl_vars['category']->value['confirmation_text'];?>
<?php }elseif(isset($_POST['confirmation_text'])){?><?php echo $_POST['confirmation_text'];?>
<?php }?></textarea>
		</div>
	</td>
</tr>

<tr>
	<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status'];?>
:</strong></td>
	<td>
		<select name="status">
			<option value="active" <?php if (isset($_smarty_tpl->tpl_vars['category']->value['status'])&&$_smarty_tpl->tpl_vars['category']->value['status']=='active'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='active'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</option>
			<option value="approval" <?php if (isset($_smarty_tpl->tpl_vars['category']->value['status'])&&$_smarty_tpl->tpl_vars['category']->value['status']=='approval'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='approval'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</option>
		</select>
	</td>
</tr>

<tr>
	<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['icon'];?>
:</strong></td>
	<td>
		<div id="icons">
			<?php if (isset($_smarty_tpl->tpl_vars['category']->value['icon'])&&!empty($_smarty_tpl->tpl_vars['category']->value['icon'])){?>
				<img style="margin: 10px; visibility: visible; opacity: 1;" src="<?php echo @constant('IA_URL');?>
<?php echo $_smarty_tpl->tpl_vars['category']->value['icon'];?>
">
			<?php }elseif(isset($_POST['icon'])&&!empty($_POST['icon'])){?>
				<img style="margin: 10px; visibility: visible; opacity: 1;" src="<?php echo @constant('IA_URL');?>
<?php echo $_POST['icon'];?>
">
			<?php }?>
		</div>

		<input type="button" id="choose_icon" name="choose" class="common" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['choose_icon'];?>
">
		<input type="button" id="remove_icon" name="remove" class="common" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove_icon'];?>
">

		<span style="margin-left: 20px;">
			Click <a href="controller.php?file=category-icons">Manage Icons</a> to add more icons to the gallery.
		</span>
		<input type="hidden" id="icon_name" name="icon" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['icon'])){?><?php echo $_smarty_tpl->tpl_vars['category']->value['icon'];?>
<?php }elseif(isset($_POST['icon'])){?><?php echo $_POST['icon'];?>
<?php }?>">
	</td>
</tr>
<?php if (!file_exists(@constant('IA_CATEGORY_ICONS_DIR'))){?>
<tr>
	<td>&nbsp;</td>
	<td>
		<span class="option_tip">
			<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['categories_icon_notif'];?>

		</span>
	</td>
</tr>
<?php }?>
</table>

<table cellspacing="0" width="100%" class="striped">
<tr>
	<td style="padding: 0 0 0 11px; width: 0;">
		<input type="submit" name="save" class="common" value="<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
<?php }?>">
	</td>
	<td style="padding: 0; width:99%;">
		<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>
			<?php if (isset($_SERVER['HTTP_REFERER'])&&stristr($_SERVER['HTTP_REFERER'],'browse')){?>
				<input type="hidden" name="goto" value="browse_new">
			<?php }else{ ?>
				<input type="hidden" name="goto" value="list">
			<?php }?>
		<?php }else{ ?>
			<span><strong>&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['and_then'];?>
&nbsp;</strong></span>
			<select name="goto">
				<option value="list" <?php if (isset($_POST['goto'])&&$_POST['goto']=='list'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go_to_list'];?>
</option>
				<option value="browse_add" <?php if (isset($_POST['goto'])&&$_POST['goto']=='browse_add'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go_to_browse'];?>
 <?php echo $_smarty_tpl->tpl_vars['parent']->value['title'];?>
</option>
				<option value="browse_new" <?php if (isset($_POST['goto'])&&$_POST['goto']=='browse_new'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go_to_browse_new_category'];?>
</option>
				<option value="add" <?php if (isset($_POST['goto'])&&$_POST['goto']=='add'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add_another_one'];?>
</option>
			</select>
		<?php }?>
	</td>
</tr>

</table>
<input type="hidden" name="id" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['id'])){?><?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
<?php }?>">
<input type="hidden" name="old_path" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['old_path'])){?><?php echo $_smarty_tpl->tpl_vars['category']->value['old_path'];?>
<?php }?>">
</form>

<div style="display: none;">
	<div id="tip-content-hide_category" ><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['hide_category_option'];?>
</div>
	<div id="tip-content-confirmation" ><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['confirmation_category_option'];?>
</div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo smarty_function_include_file(array('js'=>"js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/ckeditor/ckeditor, js/ext/plugins/chooser/chooser, js/admin/suggest-category"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>