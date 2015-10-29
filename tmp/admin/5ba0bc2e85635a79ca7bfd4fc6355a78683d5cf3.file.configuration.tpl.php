<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:43:21
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/configuration.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16258798195509107901b964-43551568%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5ba0bc2e85635a79ca7bfd4fc6355a78683d5cf3' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/configuration.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16258798195509107901b964-43551568',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'esynI18N' => 0,
    'htaccess_code' => 0,
    'key' => 0,
    'code' => 0,
    'groups' => 0,
    'group' => 0,
    'group_item' => 0,
    'params' => 0,
    'value' => 0,
    'field_show' => 0,
    'config' => 0,
    'captcha_preview' => 0,
    'value2' => 0,
    'templates' => 0,
    'admin_templates' => 0,
    'langs' => 0,
    'array_res' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910791f61d0_01593684',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910791f61d0_01593684')) {function content_550910791f61d0_01593684($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/fileuploadfield/css/file-upload"), 0);?>


<a name="top"></a>

<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['htaccess_file'],'id'=>"htaccess",'hidden'=>"true"), 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['htaccess_code']->value)&&!empty($_smarty_tpl->tpl_vars['htaccess_code']->value)){?>
	<form action="controller.php?file=configuration" enctype="multipart/form-data" id="htaccess_form" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>


		<a class="button save" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save'];?>
</a>&nbsp;
		<a class="button close" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['close'];?>
</a>&nbsp;
		<a class="button rebuild" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['rebuild_htaccess'];?>
</a>&nbsp;
		<a class="button copy" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['copy_htaccess'];?>
</a>&nbsp;

		<div id="htaccess_code" style="border: 1px solid rgb(157, 157, 161); margin: 10px 0 10px 0; padding-left: 10px;">
			<?php  $_smarty_tpl->tpl_vars["code"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["code"]->_loop = false;
 $_smarty_tpl->tpl_vars["key"] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['htaccess_code']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["code"]->key => $_smarty_tpl->tpl_vars["code"]->value){
$_smarty_tpl->tpl_vars["code"]->_loop = true;
 $_smarty_tpl->tpl_vars["key"]->value = $_smarty_tpl->tpl_vars["code"]->key;
?>
				<p style="margin-top: 10px;">
					<label for="section_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><strong>SECTION #<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
:</strong></label>
					<textarea id="section_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="common" name="sections[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" rows="6" cols="45"><?php echo $_smarty_tpl->tpl_vars['code']->value;?>
</textarea><br />
				</p>
			<?php } ?>
		</div>

		<a class="button save" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save'];?>
</a>&nbsp;
		<a class="button close" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['close'];?>
</a>&nbsp;
		<a class="button rebuild" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['rebuild_htaccess'];?>
</a>&nbsp;
		<a class="button copy" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['copy_htaccess'];?>
</a>&nbsp;

		<input type="hidden" name="do" value="save_htaccess">

	</form>
<?php }?>
<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['config_groups'],'id'=>"options"), 0);?>


<div class="config-col-left">
	<ul class="groups">
	<?php  $_smarty_tpl->tpl_vars['group_item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group_item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group_item']->key => $_smarty_tpl->tpl_vars['group_item']->value){
$_smarty_tpl->tpl_vars['group_item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['group_item']->key;
?>
		<?php if (isset($_smarty_tpl->tpl_vars['group']->value)&&$_smarty_tpl->tpl_vars['group']->value==$_smarty_tpl->tpl_vars['key']->value){?>
			<li><div><?php echo $_smarty_tpl->tpl_vars['group_item']->value;?>
</div></li>
		<?php }else{ ?>
			<li><a href="controller.php?file=configuration&amp;group=<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['group_item']->value;?>
</a></li>
		<?php }?>
	<?php } ?>
	</ul>
</div>

<div class="config-col-right">
<?php if (isset($_smarty_tpl->tpl_vars['params']->value)){?>
	<form action="controller.php?file=configuration&amp;group=<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" enctype="multipart/form-data" method="post">
		<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

		<table cellspacing="0" class="striped">

		<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['params']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
			<?php if (!empty($_smarty_tpl->tpl_vars['value']->value['show'])){?>
				<?php if (isset($_smarty_tpl->tpl_vars['field_show'])) {$_smarty_tpl->tpl_vars['field_show'] = clone $_smarty_tpl->tpl_vars['field_show'];
$_smarty_tpl->tpl_vars['field_show']->value = explode('|',$_smarty_tpl->tpl_vars['value']->value['show']); $_smarty_tpl->tpl_vars['field_show']->nocache = null; $_smarty_tpl->tpl_vars['field_show']->scope = 0;
} else $_smarty_tpl->tpl_vars['field_show'] = new Smarty_variable(explode('|',$_smarty_tpl->tpl_vars['value']->value['show']), null, 0);?>
			<?php }else{ ?>
				<?php if (isset($_smarty_tpl->tpl_vars['field_show'])) {$_smarty_tpl->tpl_vars['field_show'] = clone $_smarty_tpl->tpl_vars['field_show'];
$_smarty_tpl->tpl_vars['field_show']->value = null; $_smarty_tpl->tpl_vars['field_show']->nocache = null; $_smarty_tpl->tpl_vars['field_show']->scope = 0;
} else $_smarty_tpl->tpl_vars['field_show'] = new Smarty_variable(null, null, 0);?>
			<?php }?>

			<?php if (empty($_smarty_tpl->tpl_vars['field_show']->value[0])||(!empty($_smarty_tpl->tpl_vars['field_show']->value[0])&&$_smarty_tpl->tpl_vars['config']->value[$_smarty_tpl->tpl_vars['field_show']->value[0]]==$_smarty_tpl->tpl_vars['field_show']->value[1])){?>
				<?php if ($_smarty_tpl->tpl_vars['value']->value['type']=="password"){?>
					<tr>
						<td class="tip-header" id="tip-header-<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
</td>
						<td><input type="password" class="common" size="45" name="param[<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
]" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
"></td>
					</tr>
				<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=="text"){?>
					<tr>
						<td class="tip-header" id="tip-header-<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" width="25%"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
</td>
						<?php if ($_smarty_tpl->tpl_vars['value']->value['name']=='expire_action'){?>
							<td>
								<select name="param[expire_action]" class="common">
									<option value="" <?php if ($_smarty_tpl->tpl_vars['value']->value['value']==''){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['nothing'];?>
</option>
									<option value="remove" <?php if ($_smarty_tpl->tpl_vars['value']->value['value']=='remove'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
</option>
									<optgroup label="Status">
										<option value="approval" <?php if ($_smarty_tpl->tpl_vars['value']->value['value']=='approval'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</option>
										<option value="banned" <?php if ($_smarty_tpl->tpl_vars['value']->value['value']=='banned'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banned'];?>
</option>
										<option value="suspended" <?php if ($_smarty_tpl->tpl_vars['value']->value['value']=='suspended'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['suspended'];?>
</option>
									</optgroup>
									<optgroup label="Type">
										<option value="regular" <?php if ($_smarty_tpl->tpl_vars['value']->value['value']=='regular'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['regular'];?>
</option>
										<option value="featured" <?php if ($_smarty_tpl->tpl_vars['value']->value['value']=='featured'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['featured'];?>
</option>
										<option value="partner" <?php if ($_smarty_tpl->tpl_vars['value']->value['value']=='partner'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['partner'];?>
</option>
									</optgroup>
								</select>
							</td>
						<?php }elseif($_smarty_tpl->tpl_vars['value']->value['name']=='captcha_preview'){?>
							<?php if (isset($_smarty_tpl->tpl_vars['captcha_preview']->value)&&!empty($_smarty_tpl->tpl_vars['captcha_preview']->value)){?>
								<td><?php echo $_smarty_tpl->tpl_vars['captcha_preview']->value;?>
</td>
							<?php }else{ ?>
								<td><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['no_captcha_preview'];?>
</td>
							<?php }?>
						<?php }else{ ?>
							<td><input type="text" size="45" name="param[<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
]" class="common" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
"></td>
						<?php }?>
					</tr>
				<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=="textarea"){?>
					<tr>
						<td class="tip-header" id="tip-header-<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
</td>
						<td><textarea name="param[<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
]" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" class="<?php if ($_smarty_tpl->tpl_vars['value']->value['editor']=='1'){?>cked <?php }?>common" cols="45" rows="7"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</textarea></td>
					</tr>
				<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=="image"){?>
					<tr>
						<td class="tip-header" id="tip-header-<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
</td>
						<td>
							<?php if (!is_writeable(((@constant('IA_HOME')).(@constant('IA_DS'))).('uploads'))){?>
								<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['upload_writable_permission'];?>
</i></div>
							<?php }else{ ?>
								<input type="hidden" name="param[<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
]">
								<input type="file" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" id="conf_<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" class="common" size="42">
							<?php }?>

							<?php if ($_smarty_tpl->tpl_vars['value']->value['value']!=''){?>
								<a href="#" class="view_image"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['view_image'];?>
</a>&nbsp;
								<a href="#" class="remove_image"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
 <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image'];?>
</a>
							<?php }?>
						</td>
					</tr>
				<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=="checkbox"){?>
					<tr>
						<td class="tip-header" id="tip-header-<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
</td>
						<td class="fields-pages">
							<?php  $_smarty_tpl->tpl_vars['value2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value2']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = explode(",",$_smarty_tpl->tpl_vars['value']->value['multiple_values']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value2']->key => $_smarty_tpl->tpl_vars['value2']->value){
$_smarty_tpl->tpl_vars['value2']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value2']->key;
?>
								<label for="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><input type="checkbox" name="param[<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
][]" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['key']->value,explode(",",$_smarty_tpl->tpl_vars['value']->value['value']))){?>checked="checked"<?php }?> /> <?php echo trim($_smarty_tpl->tpl_vars['value2']->value,"'");?>
</label>
							<?php } ?>
						</td>
					</tr>
				<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=="radio"){?>
					<tr>
						<td class="tip-header" id="tip-header-<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" width="250"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
</td>
						<td><?php echo smarty_function_html_radio_switcher(array('value'=>$_smarty_tpl->tpl_vars['value']->value['value'],'name'=>$_smarty_tpl->tpl_vars['value']->value['name'],'conf'=>true),$_smarty_tpl);?>
</td>
					</tr>
				<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=="select"){?>
					<tr>
						<td class="tip-header" id="tip-header-<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
</td>

						<?php if ($_smarty_tpl->tpl_vars['value']->value['name']=='tmpl'){?>
							<?php if (isset($_smarty_tpl->tpl_vars["array_res"])) {$_smarty_tpl->tpl_vars["array_res"] = clone $_smarty_tpl->tpl_vars["array_res"];
$_smarty_tpl->tpl_vars["array_res"]->value = $_smarty_tpl->tpl_vars['templates']->value; $_smarty_tpl->tpl_vars["array_res"]->nocache = null; $_smarty_tpl->tpl_vars["array_res"]->scope = 0;
} else $_smarty_tpl->tpl_vars["array_res"] = new Smarty_variable($_smarty_tpl->tpl_vars['templates']->value, null, 0);?>
						<?php }elseif($_smarty_tpl->tpl_vars['value']->value['name']=='admin_tmpl'){?>
							<?php if (isset($_smarty_tpl->tpl_vars["array_res"])) {$_smarty_tpl->tpl_vars["array_res"] = clone $_smarty_tpl->tpl_vars["array_res"];
$_smarty_tpl->tpl_vars["array_res"]->value = $_smarty_tpl->tpl_vars['admin_templates']->value; $_smarty_tpl->tpl_vars["array_res"]->nocache = null; $_smarty_tpl->tpl_vars["array_res"]->scope = 0;
} else $_smarty_tpl->tpl_vars["array_res"] = new Smarty_variable($_smarty_tpl->tpl_vars['admin_templates']->value, null, 0);?>
						<?php }elseif($_smarty_tpl->tpl_vars['value']->value['name']=='lang'){?>
							<?php if (isset($_smarty_tpl->tpl_vars["array_res"])) {$_smarty_tpl->tpl_vars["array_res"] = clone $_smarty_tpl->tpl_vars["array_res"];
$_smarty_tpl->tpl_vars["array_res"]->value = $_smarty_tpl->tpl_vars['langs']->value; $_smarty_tpl->tpl_vars["array_res"]->nocache = null; $_smarty_tpl->tpl_vars["array_res"]->scope = 0;
} else $_smarty_tpl->tpl_vars["array_res"] = new Smarty_variable($_smarty_tpl->tpl_vars['langs']->value, null, 0);?>
						<?php }else{ ?>
							<?php if (isset($_smarty_tpl->tpl_vars["array_res"])) {$_smarty_tpl->tpl_vars["array_res"] = clone $_smarty_tpl->tpl_vars["array_res"];
$_smarty_tpl->tpl_vars["array_res"]->value = explode(",",$_smarty_tpl->tpl_vars['value']->value['multiple_values']); $_smarty_tpl->tpl_vars["array_res"]->nocache = null; $_smarty_tpl->tpl_vars["array_res"]->scope = 0;
} else $_smarty_tpl->tpl_vars["array_res"] = new Smarty_variable(explode(",",$_smarty_tpl->tpl_vars['value']->value['multiple_values']), null, 0);?>
						<?php }?>

						<td>
							<select name="param[<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
]" class="common" <?php if (count($_smarty_tpl->tpl_vars['array_res']->value)==1){?>disabled="disabled"<?php }?>>
								<?php  $_smarty_tpl->tpl_vars['value2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value2']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['array_res']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value2']->key => $_smarty_tpl->tpl_vars['value2']->value){
$_smarty_tpl->tpl_vars['value2']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value2']->key;
?>
									<option value="<?php if ($_smarty_tpl->tpl_vars['value']->value['name']=='lang'){?><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
<?php }else{ ?><?php echo trim($_smarty_tpl->tpl_vars['value2']->value,"'");?>
<?php }?>" <?php if (($_smarty_tpl->tpl_vars['value']->value['name']=='lang'&&$_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['value']->value['value'])||trim($_smarty_tpl->tpl_vars['value2']->value,"'")==$_smarty_tpl->tpl_vars['value']->value['value']){?>selected="selected"<?php }?>><?php echo trim($_smarty_tpl->tpl_vars['value2']->value,"'");?>
</option>
								<?php } ?>
							</select>
						</td>
					</tr>
				<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=="divider"){?>
					<tr>
						<td colspan="2" class="caption"><strong><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</strong><?php if (!empty($_smarty_tpl->tpl_vars['value']->value['name'])){?><a name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
"></a><?php }?></td>
					</tr>
				<?php }?>
			<?php }?>
		<?php } ?>

		<tr class="all">
			<td colspan="2"><input type="submit" name="save" id="save" class="common" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
"></td>
		</tr>
		</table>
	</form>
<?php }?>
</div>

<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<a name="bottom"></a>

<?php echo smarty_function_include_file(array('js'=>"js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/ext/plugins/fileuploadfield/FileUploadField, js/ckeditor/ckeditor, js/utils/zeroclipboard/ZeroClipboard.min, js/admin/configuration"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>