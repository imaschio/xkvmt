<?php /* Smarty version Smarty-3.1.13, created on 2015-10-08 01:06:19
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/menus.tpl" */ ?>
<?php /*%%SmartyHeaderCode:883600515615f9cb695546-20907996%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0648c95a36dc98226e22e899ff4e06fbb3a79a58' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/menus.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '883600515615f9cb695546-20907996',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'menu' => 0,
    'positions' => 0,
    'place' => 0,
    'esynI18N' => 0,
    'pages_group' => 0,
    'pages' => 0,
    'group' => 0,
    'post_key' => 0,
    'all_pages' => 0,
    'page' => 0,
    'key' => 0,
    'visibleOn' => 0,
    'smart' => 0,
    'tree_pages' => 0,
    'tree_menus' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5615f9cb9042f1_01840455',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5615f9cb9042f1_01840455')) {function content_5615f9cb9042f1_01840455($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('js'=>"js/jquery/plugins/iphoneswitch/jquery.iphone-switch"), 0);?>


<?php if (isset($_GET['do'])&&($_GET['do']=='add'||$_GET['do']=='edit')){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	
	<form action="controller.php?file=menus&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" id="menu_form" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td colspan="2" class="caption"><?php echo smarty_function_lang(array('key'=>"menus"),$_smarty_tpl);?>
</td>
	</tr>
	<tr>
		<td width="150"><strong><?php echo smarty_function_lang(array('key'=>"name"),$_smarty_tpl);?>
:</strong></td>
		<td>
			<input type="text" name="name" id="name" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
<?php }elseif(isset($_POST['name'])){?><?php echo $_POST['name'];?>
<?php }?>" <?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>readonly="readonly"<?php }?> />
			<div class="option_tip"><?php echo smarty_function_lang(array('key'=>"unique_name"),$_smarty_tpl);?>
</div>
		</td>
	</tr>
	<tr>
		<td width="150"><label for="title"><strong><?php echo smarty_function_lang(array('key'=>"title"),$_smarty_tpl);?>
:</strong></label></td>
		<td><input class="common" type="text" value="<?php if (isset($_smarty_tpl->tpl_vars['menu']->value)&&isset($_smarty_tpl->tpl_vars['menu']->value['title'])){?><?php echo $_smarty_tpl->tpl_vars['menu']->value['title'];?>
<?php }elseif(isset($_POST['title'])){?><?php echo $_POST['title'];?>
<?php }?>" id="title" name="title"></td>
	</tr>
	<tr>
		<td><label for="position"><strong><?php echo smarty_function_lang(array('key'=>"position"),$_smarty_tpl);?>
:</strong></label></td>
		<td>
			<select class="common" id="position" name="position">
			<?php  $_smarty_tpl->tpl_vars["place"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["place"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['positions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["place"]->key => $_smarty_tpl->tpl_vars["place"]->value){
$_smarty_tpl->tpl_vars["place"]->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['place']->value;?>
" <?php if (isset($_smarty_tpl->tpl_vars['menu']->value)&&isset($_smarty_tpl->tpl_vars['menu']->value['position'])&&$_smarty_tpl->tpl_vars['menu']->value['position']==$_smarty_tpl->tpl_vars['place']->value){?>selected<?php }elseif(isset($_POST['place'])&&$_POST['place']==$_smarty_tpl->tpl_vars['place']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['place']->value;?>
</option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['classname'];?>
:</strong></td>
		<td>
			<input type="text" name="classname" size="40" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['classname'])){?><?php echo $_smarty_tpl->tpl_vars['menu']->value['classname'];?>
<?php }elseif(isset($_POST['classname'])){?><?php echo $_POST['classname'];?>
<?php }?>"/>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['show_header'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['menu']->value['show_header'])===null||$tmp==='' ? $_POST['show_header'] : $tmp))===null||$tmp==='' ? 1 : $tmp),'name'=>"show_header"),$_smarty_tpl);?>

		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['collapsible'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['menu']->value['collapsible'])===null||$tmp==='' ? $_POST['collapsible'] : $tmp))===null||$tmp==='' ? 1 : $tmp),'name'=>"collapsible"),$_smarty_tpl);?>

		</td>
	</tr>
	<tr>
		<td><strong><?php echo smarty_function_lang(array('key'=>"sticky"),$_smarty_tpl);?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['menu']->value['sticky'])===null||$tmp==='' ? $_POST['sticky'] : $tmp))===null||$tmp==='' ? 1 : $tmp),'name'=>"sticky"),$_smarty_tpl);?>

		</td>
	</tr>
	</table>
		
	<div id="acos" style="display: none;">
	<table class="striped">
	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['visible_on_pages'];?>
:</strong></td>
		<td>
			<?php if (isset($_smarty_tpl->tpl_vars['pages_group']->value)&&!empty($_smarty_tpl->tpl_vars['pages_group']->value)){?>
				<?php if (isset($_smarty_tpl->tpl_vars['pages']->value)&&!empty($_smarty_tpl->tpl_vars['pages']->value)){?>
					<input type="checkbox" value="1" name="select_all" id="select_all" <?php if (isset($_POST['select_all'])&&$_POST['select_all']=='1'){?>checked="checked"<?php }?> /><label for="select_all">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['select_all'];?>
</label>
						<div style="clear:both;"></div>
					<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pages_group']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
?>
						<fieldset class="list" style="float:left;">
							<?php if (isset($_smarty_tpl->tpl_vars["post_key"])) {$_smarty_tpl->tpl_vars["post_key"] = clone $_smarty_tpl->tpl_vars["post_key"];
$_smarty_tpl->tpl_vars["post_key"]->value = ("select_all_").($_smarty_tpl->tpl_vars['group']->value); $_smarty_tpl->tpl_vars["post_key"]->nocache = null; $_smarty_tpl->tpl_vars["post_key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["post_key"] = new Smarty_variable(("select_all_").($_smarty_tpl->tpl_vars['group']->value), null, 0);?>
							<legend><input type="checkbox" value="1" class="<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" name="select_all_<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" id="select_all_<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" <?php if (isset($_POST[$_smarty_tpl->tpl_vars['post_key']->value])&&$_POST[$_smarty_tpl->tpl_vars['post_key']->value]=='1'){?>checked="checked"<?php }?> /><label for="select_all_<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
">&nbsp;<strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['group']->value];?>
</strong></label></legend>
							<?php  $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['page']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['all_pages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value){
$_smarty_tpl->tpl_vars['page']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['page']->key;
?>
								<?php if ($_smarty_tpl->tpl_vars['page']->value['group']==$_smarty_tpl->tpl_vars['group']->value){?>
									<ul style="list-style-type: none;">
										<li style="margin: 0 0 0 15px; padding-bottom: 3px; float: left; width: 200px;" >
											<input type="checkbox" name="visible_on_pages[]" class="<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['name'];?>
" id="page_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['page']->value['name'],$_smarty_tpl->tpl_vars['visibleOn']->value,true)){?>checked="checked"<?php }?> /><label for="page_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"> <?php if (empty($_smarty_tpl->tpl_vars['page']->value['title'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['name'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
<?php }?></label>
											<?php if ($_smarty_tpl->tpl_vars['page']->value['name']=='index_browse'){?>
												<a style="float:right;padding-right:10px;" href="#" id="add_categories">
													<img src="<?php echo @constant('IA_URL');?>
js/ext/resources/images/default/tree/leaf.gif" alt="">
												</a>
											<?php }?>
										</li>
									</ul>
								<?php }?>
							<?php } ?>
						</fieldset>
					<?php } ?>
				<?php }?>
			<?php }?>
		</td>
	</tr>
	</table>
	</div>
		
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="300"><div id="div_menus"></div></td>
		<td width="25">
			<div>
				<input type="button" value="&gt;&gt;" id="delete_menu" class="common small"/><br /><br />
				<input type="button" value="&lt;&lt;" id="add_menu" class="common small">
			</div>
		</td>
		<td><div id="box_pages"></div></td>
	</tr>
	<tr>
		<td colspan="3">
			<input type="hidden" id="action" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>" name="do">
			<input type="hidden" id="menu" value="" name="menu">
			<input type="hidden" name="cat_crossed" id="cat_crossed" value="<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['categories'])&&!empty($_smarty_tpl->tpl_vars['menu']->value['categories'])){?><?php echo implode(',',$_smarty_tpl->tpl_vars['menu']->value['categories']);?>
<?php }?>">
			<input class="common" type="submit" name="save" id="save" onclick="return false;" value="<?php if (isset($_smarty_tpl->tpl_vars['smart']->value['get']['do'])&&$_GET['do']=='add'){?><?php echo smarty_function_lang(array('key'=>'create'),$_smarty_tpl);?>
<?php }else{ ?><?php echo smarty_function_lang(array('key'=>'save'),$_smarty_tpl);?>
<?php }?>">
			<span><b>&nbsp;<?php echo smarty_function_lang(array('key'=>"and_then"),$_smarty_tpl);?>
&nbsp;</b></span>
			<select id="menus_goto" name="goto">
				<option value="list" <?php if (isset($_POST['goto'])&&$_POST['goto']=='list'){?>selected="selected"<?php }?>><?php echo smarty_function_lang(array('key'=>"go_to_list"),$_smarty_tpl);?>
</option>
				<option value="new" <?php if (isset($_POST['goto'])&&$_POST['goto']=='new'){?>selected="selected"<?php }?>><?php echo smarty_function_lang(array('key'=>"add_another_one"),$_smarty_tpl);?>
</option>
			</select>
		</td>
	</tr>
	</table>
	</form>
	
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


	<script type="text/javascript">
		var pages = <?php echo $_smarty_tpl->tpl_vars['tree_pages']->value;?>
;
		var menus = <?php echo $_smarty_tpl->tpl_vars['tree_menus']->value;?>
;
	</script>
<?php }else{ ?>
	<div id="box_menus" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/ext/plugins/treeserializer/ext.tree.serialize, js/ckeditor/ckeditor, js/admin/menus"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>