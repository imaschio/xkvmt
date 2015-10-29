<?php /* Smarty version Smarty-3.1.13, created on 2015-10-08 00:51:29
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/pages.tpl" */ ?>
<?php /*%%SmartyHeaderCode:360927115615f65100f735-10634101%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ff7931642521031ea99b0bf2265722c7f0e127cf' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/pages.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '360927115615f65100f735-10634101',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'page' => 0,
    'gTitle' => 0,
    'langs' => 0,
    'lang' => 0,
    'esynI18N' => 0,
    'code' => 0,
    'menus' => 0,
    'key' => 0,
    'page_menus' => 0,
    'menu' => 0,
    'default_external' => 0,
    'pre_lang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5615f6512a0906_70300372',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5615f6512a0906_70300372')) {function content_5615f6512a0906_70300372($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer",'js'=>"js/jquery/plugins/iphoneswitch/jquery.iphone-switch"), 0);?>


<?php if ((isset($_GET['do'])&&$_GET['do']=='add')||(isset($_GET['do'])&&$_GET['do']=='edit'&&isset($_smarty_tpl->tpl_vars['page']->value)&&!empty($_smarty_tpl->tpl_vars['page']->value))){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	<form action="controller.php?file=pages&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post" id="page_form">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong><?php echo smarty_function_lang(array('key'=>"name"),$_smarty_tpl);?>
:</strong></td>
		<td>
			<input type="text" name="name" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['page']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['name'];?>
<?php }elseif(isset($_POST['name'])){?><?php echo $_POST['name'];?>
<?php }?>" <?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>readonly="readonly"<?php }?> />
			<div class="option_tip"><?php echo smarty_function_lang(array('key'=>"unique_name"),$_smarty_tpl);?>
</div>
		</td>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
:</strong></td>
		<td>
			<input type="text" name="titles[<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
]" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['page']->value['titles'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['titles'][$_smarty_tpl->tpl_vars['code']->value];?>
<?php }elseif(isset($_POST['titles'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo $_POST['titles'][$_smarty_tpl->tpl_vars['code']->value];?>
<?php }?>">
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['show_menus'];?>
:</strong></td>
		<td>
			<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['menu']->key;
?>
				<input type="checkbox" name="menus[]" value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if ((isset($_POST['menus'])&&in_array($_smarty_tpl->tpl_vars['key']->value,$_POST['menus']))||!empty($_smarty_tpl->tpl_vars['page_menus']->value)&&in_array($_smarty_tpl->tpl_vars['key']->value,$_smarty_tpl->tpl_vars['page_menus']->value)){?>checked="checked"<?php }?> />
				<label for="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php if (!empty($_smarty_tpl->tpl_vars['menu']->value)){?><?php echo $_smarty_tpl->tpl_vars['menu']->value;?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
<?php }?></label><br />
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['no_follow_url'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['page']->value['nofollow'])===null||$tmp==='' ? $_POST['nofollow'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"nofollow"),$_smarty_tpl);?>

		</td>
	</tr>
	<tr>
		<td><strong><?php echo smarty_function_lang(array('key'=>"page_new_window"),$_smarty_tpl);?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['page']->value['new_window'])===null||$tmp==='' ? $_POST['new_window'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"new_window"),$_smarty_tpl);?>

		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status'];?>
:</strong></td>
		<td>
			<select name="status">
				<option value="active" <?php if (isset($_smarty_tpl->tpl_vars['page']->value['status'])&&$_smarty_tpl->tpl_vars['page']->value['status']=='active'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</option>
				<option value="approval" <?php if (isset($_smarty_tpl->tpl_vars['page']->value['status'])&&$_smarty_tpl->tpl_vars['page']->value['status']=='inactive'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['inactive'];?>
</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['external_url'];?>
:</strong></td>
		<td>
			<?php if (isset($_smarty_tpl->tpl_vars['page']->value)&&!empty($_smarty_tpl->tpl_vars['page']->value['unique_url'])){?>
				<?php if (isset($_smarty_tpl->tpl_vars["default_external"])) {$_smarty_tpl->tpl_vars["default_external"] = clone $_smarty_tpl->tpl_vars["default_external"];
$_smarty_tpl->tpl_vars["default_external"]->value = "1"; $_smarty_tpl->tpl_vars["default_external"]->nocache = null; $_smarty_tpl->tpl_vars["default_external"]->scope = 0;
} else $_smarty_tpl->tpl_vars["default_external"] = new Smarty_variable("1", null, 0);?>
			<?php }else{ ?>
				<?php if (isset($_smarty_tpl->tpl_vars["default_external"])) {$_smarty_tpl->tpl_vars["default_external"] = clone $_smarty_tpl->tpl_vars["default_external"];
$_smarty_tpl->tpl_vars["default_external"]->value = "0"; $_smarty_tpl->tpl_vars["default_external"]->nocache = null; $_smarty_tpl->tpl_vars["default_external"]->scope = 0;
} else $_smarty_tpl->tpl_vars["default_external"] = new Smarty_variable("0", null, 0);?>
			<?php }?>

			<?php echo smarty_function_html_radio_switcher(array('value'=>$_smarty_tpl->tpl_vars['default_external']->value,'name'=>"external_url"),$_smarty_tpl);?>

		</td>
	</tr>
	</table>
	<div id="url_field" style="display: none;">
		<table cellspacing="0" width="100%" class="striped">
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['page_external_url'];?>
:</strong></td>
			<td>
				<input type="text" name="unique_url" size="44" id="unique_url" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['page']->value['unique_url'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['unique_url'];?>
<?php }elseif(isset($_POST['unique_url'])){?><?php echo $_POST['unique_url'];?>
<?php }?>">
			</td>
		</tr>
		</table>
	</div>
	<div id="page_options" style="display: none;">
		<table cellspacing="0" width="100%" class="striped">
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['custom_url'];?>
:</strong></td>
			<td>
				<input type="text" name="custom_url" size="24" class="common" style="float: left;" value="<?php if (isset($_smarty_tpl->tpl_vars['page']->value['custom_url'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['custom_url'];?>
<?php }elseif(isset($_POST['custom_url'])){?><?php echo $_POST['custom_url'];?>
<?php }?>">
				<div style="float: left; display: none; margin-left: 3px; padding: 4px;" id="page_url_box"><span><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['page_url_will_be'];?>
:&nbsp;<span><span id="page_url" style="padding: 3px; margin: 0; background: #FFE269;"></span></div>
			</td>
		</tr>
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['meta_description'];?>
:</strong></td>
			<td>
				<textarea name="meta_description" cols="43" rows="2" class="common"><?php if (isset($_smarty_tpl->tpl_vars['page']->value['meta_description'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['meta_description'];?>
<?php }elseif(isset($_POST['meta_description'])){?><?php echo htmlspecialchars($_POST['meta_description'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></textarea>
			</td>
		</tr>
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['meta_keywords'];?>
:</strong></td>
			<td>
				<input type="text" name="meta_keywords" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['page']->value['meta_keywords'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['meta_keywords'];?>
<?php }elseif(isset($_POST['meta_keywords'])){?><?php echo htmlspecialchars($_POST['meta_keywords'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" size="42"/>
			</td>
		</tr>
		</table>
	</div>

	<div id="ckeditor" style="display: none; padding: 5px 0 10px 11px;">
		<div style="padding-bottom: 5px;"><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['page_content'];?>
:</b></div>
		<div id="editorToolbar"></div>
		<div id="languages_content"></div>
		<?php  $_smarty_tpl->tpl_vars['pre_lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pre_lang']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pre_lang']->key => $_smarty_tpl->tpl_vars['pre_lang']->value){
$_smarty_tpl->tpl_vars['pre_lang']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['pre_lang']->key;
?>
			<div id="div_content_<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['pre_lang']->value;?>
" class="pre_lang x-hide-display">
				<textarea id="contents[<?php echo $_smarty_tpl->tpl_vars['pre_lang']->value;?>
]" name="contents[<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
]" class="ckeditor_textarea"><?php if (isset($_smarty_tpl->tpl_vars['page']->value['contents'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['contents'][$_smarty_tpl->tpl_vars['code']->value];?>
<?php }elseif(isset($_POST['contents'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo $_POST['contents'][$_smarty_tpl->tpl_vars['code']->value];?>
<?php }else{ ?>&nbsp;<?php }?></textarea>
			</div>
		<?php } ?>
	</div>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr class="all">
		<td style="padding: 0 0 0 11px; width: 0;">
			<input type="submit" name="save" class="common" value="<?php if ($_GET['do']=='add'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }?>">
		</td>
		<td style="padding: 0;">
			<?php if ($_GET['do']=='add'){?>
				<strong>&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['and_then'];?>
&nbsp;</strong>
				<select name="goto">
					<option value="list" <?php if (isset($_POST['goto'])&&$_POST['goto']=='list'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go_to_list'];?>
</option>
					<option value="add" <?php if (isset($_POST['goto'])&&$_POST['goto']=='add'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add_another_one'];?>
</option>
				</select>
			<?php }?>
			
			&nbsp;<input type="submit" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['preview'];?>
 <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['page'];?>
" class="common" name="preview">
		</td>
	</tr>
	</table>
	<input type="hidden" name="do" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>">
	<input type="hidden" name="old_name" value="<?php if (isset($_smarty_tpl->tpl_vars['page']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['name'];?>
<?php }?>">
	<input type="hidden" name="old_custom_url" value="<?php if (isset($_smarty_tpl->tpl_vars['page']->value['custom_url'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['custom_url'];?>
<?php }?>">
	<input type="hidden" name="id" value="<?php if (isset($_smarty_tpl->tpl_vars['page']->value['id'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
<?php }?>">
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<div id="box_pages" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/pages"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>