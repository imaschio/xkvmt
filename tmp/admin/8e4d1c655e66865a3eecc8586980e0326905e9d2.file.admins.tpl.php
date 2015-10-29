<?php /* Smarty version Smarty-3.1.13, created on 2015-03-19 02:05:49
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/admins.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1415485233550a673db505c2-83926135%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8e4d1c655e66865a3eecc8586980e0326905e9d2' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/admins.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1415485233550a673db505c2-83926135',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'admin' => 0,
    'gTitle' => 0,
    'esynI18N' => 0,
    'currentAdmin' => 0,
    'config' => 0,
    'admin_blocks' => 0,
    'admin_pages' => 0,
    'block' => 0,
    'post_key' => 0,
    'page' => 0,
    'key' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550a673defa855_16652061',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550a673defa855_16652061')) {function content_550a673defa855_16652061($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_modifier_capitalize')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.capitalize.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer, js/bootstrap/css/passfield"), 0);?>


<?php if ((isset($_GET['do'])&&$_GET['do']=='add')||(isset($_GET['do'])&&$_GET['do']=='edit'&&isset($_smarty_tpl->tpl_vars['admin']->value)&&!empty($_smarty_tpl->tpl_vars['admin']->value))){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	<form action="controller.php?file=admins<?php if ($_GET['do']=='add'){?>&amp;do=add<?php }else{ ?>&amp;do=edit&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['username'];?>
:</strong></td>
		<td><input type="text" name="username" class="common" size="22" value="<?php if (isset($_smarty_tpl->tpl_vars['admin']->value['username'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admin']->value['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['username'])){?><?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
	</tr>
	
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['fullname'];?>
:</strong></td>
		<td><input type="text" name="fullname" class="common" size="22" value="<?php if (isset($_smarty_tpl->tpl_vars['admin']->value['fullname'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admin']->value['fullname'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['fullname'])){?><?php echo htmlspecialchars($_POST['fullname'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
	</tr>
	
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['email'];?>
:</strong></td>
		<td><input type="text" name="email" class="common" size="22" value="<?php if (isset($_smarty_tpl->tpl_vars['admin']->value['email'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admin']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['email'])){?><?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
	</tr>
		
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['password'];?>
:</strong></td>
		<td><input type="password" name="new_pass" class="js-input-password common" size="22"></td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['password_confirm'];?>
:</strong></td>
		<td><input type="password" name="new_pass2" class="common" size="22"></td>
	</tr>

	<?php if (('add'==$_GET['do'])||(isset($_GET['id'])&&$_smarty_tpl->tpl_vars['currentAdmin']->value['id']!=$_GET['id'])){?>
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status'];?>
:</strong></td>
			<td>
				<select name="status">
					<option value="active" <?php if (isset($_smarty_tpl->tpl_vars['admin']->value['status'])&&$_smarty_tpl->tpl_vars['admin']->value['status']=='active'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='active'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</option>
					<option value="inactive" <?php if (isset($_smarty_tpl->tpl_vars['admin']->value['status'])&&$_smarty_tpl->tpl_vars['admin']->value['status']=='inactive'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='inactive'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['inactive'];?>
</option>
				</select>
			</td>
		</tr>
	<?php }?>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['submission_notif'];?>
:</strong></td>
		<td><?php echo smarty_function_html_radio_switcher(array('value'=>$_smarty_tpl->tpl_vars['admin']->value['submit_notif'],'name'=>"submit_notif"),$_smarty_tpl);?>
</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['account_registr_notif'];?>
:</strong></td>
		<td><?php echo smarty_function_html_radio_switcher(array('value'=>$_smarty_tpl->tpl_vars['admin']->value['account_registr_notif'],'name'=>"account_registr_notif"),$_smarty_tpl);?>
</td>
	</tr>

	<?php if ($_smarty_tpl->tpl_vars['config']->value['sponsored_listings']){?>
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['payment_notif'];?>
:</strong></td>
			<td><?php echo smarty_function_html_radio_switcher(array('value'=>$_smarty_tpl->tpl_vars['admin']->value['payment_notif'],'name'=>"payment_notif"),$_smarty_tpl);?>
</td>
		</tr>
	<?php }?>

	<?php if (('add'==$_GET['do'])||(isset($_GET['id'])&&$_smarty_tpl->tpl_vars['currentAdmin']->value['id']!=$_GET['id'])){?>
		<tr>
			<td class="caption" colspan="2"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['admin_permissions'];?>
</strong></td>
		</tr>

		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['super_admin'];?>
:&nbsp;</strong></td>
			<td>
				<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['admin']->value['super'])===null||$tmp==='' ? $_POST['super'] : $tmp))===null||$tmp==='' ? 1 : $tmp),'name'=>"super"),$_smarty_tpl);?>

			</td>
		</tr>
	<?php }?>

	</table>

	<div id="permissions" style="display: none;">
		<table cellspacing="0" width="100%" class="striped">
		<tr>
			<td>
				<?php if (isset($_smarty_tpl->tpl_vars['admin_blocks']->value)&&!empty($_smarty_tpl->tpl_vars['admin_blocks']->value)){?>
					<?php if (isset($_smarty_tpl->tpl_vars['admin_pages']->value)&&!empty($_smarty_tpl->tpl_vars['admin_pages']->value)){?>
						<input type="checkbox" value="1" name="select_all" id="select_all" <?php if (isset($_POST['select_all'])&&$_POST['select_all']=='1'){?>checked="checked"<?php }?> /><label for="select_all">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['select_all'];?>
</label>
							<div style="clear:both;"></div>
						<?php  $_smarty_tpl->tpl_vars['block'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['block']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['admin_blocks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['block']->key => $_smarty_tpl->tpl_vars['block']->value){
$_smarty_tpl->tpl_vars['block']->_loop = true;
?>
							<fieldset class="list" style="float:left;">
								<?php if (isset($_smarty_tpl->tpl_vars["post_key"])) {$_smarty_tpl->tpl_vars["post_key"] = clone $_smarty_tpl->tpl_vars["post_key"];
$_smarty_tpl->tpl_vars["post_key"]->value = ("select_all_").($_smarty_tpl->tpl_vars['block']->value); $_smarty_tpl->tpl_vars["post_key"]->nocache = null; $_smarty_tpl->tpl_vars["post_key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["post_key"] = new Smarty_variable(("select_all_").($_smarty_tpl->tpl_vars['block']->value), null, 0);?>
								<legend><input type="checkbox" value="1" class="<?php echo $_smarty_tpl->tpl_vars['block']->value;?>
" name="select_all_<?php echo $_smarty_tpl->tpl_vars['block']->value;?>
" id="select_all_<?php echo $_smarty_tpl->tpl_vars['block']->value;?>
" <?php if (isset($_POST[$_smarty_tpl->tpl_vars['post_key']->value])&&$_POST[$_smarty_tpl->tpl_vars['post_key']->value]=='1'){?>checked="checked"<?php }?> /><label for="select_all_<?php echo $_smarty_tpl->tpl_vars['block']->value;?>
">&nbsp;<strong><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['block']->value]);?>
</strong></label></legend>
								<?php  $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['page']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['admin_pages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value){
$_smarty_tpl->tpl_vars['page']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['page']->key;
?>
									<?php if ($_smarty_tpl->tpl_vars['page']->value['block_name']==$_smarty_tpl->tpl_vars['block']->value){?>
										<ul style="list-style-type: none; width:200px;">
											<li style="margin: 0 0 0 15px; padding-bottom: 3px; float: left; width: 200px;" >
												<input type="checkbox" name="permissions[]" class="<?php echo $_smarty_tpl->tpl_vars['block']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['aco'];?>
" id="page_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (isset($_smarty_tpl->tpl_vars['admin']->value['permissions'])&&in_array($_smarty_tpl->tpl_vars['page']->value['aco'],$_smarty_tpl->tpl_vars['admin']->value['permissions'])){?>checked="checked"<?php }?> /><label for="page_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">&nbsp;<?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
</label>
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
	
	<table cellspacing="0" width="100%" class="striped">
	<tr>
		<td style="padding: 0 0 0 11px; width: 0;">
			<input type="submit" name="save" class="common" value="<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
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
		</td>
	</tr>
	</table>
	<input type="hidden" name="id" value="<?php if (isset($_smarty_tpl->tpl_vars['admin']->value['id'])){?><?php echo $_smarty_tpl->tpl_vars['admin']->value['id'];?>
<?php }?>">
	<input type="hidden" name="do" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>">
	<input type="hidden" name="old_email" value="<?php if (isset($_smarty_tpl->tpl_vars['admin']->value['email'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['admin']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<div id="box_admins" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/bootstrap/js/passfield.min, js/admin/admins"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>