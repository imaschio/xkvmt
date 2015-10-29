<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:36:19
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/accounts.tpl" */ ?>
<?php /*%%SmartyHeaderCode:36558805155090ed3971147-30494386%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5e7606c324c91a6b715b8e50bd7877c23c8c0c07' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/accounts.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '36558805155090ed3971147-30494386',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'account' => 0,
    'gTitle' => 0,
    'esynI18N' => 0,
    'file_path' => 0,
    'plans' => 0,
    'plan' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55090ed3adfcf1_51379282',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55090ed3adfcf1_51379282')) {function content_55090ed3adfcf1_51379282($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer, js/bootstrap/css/passfield"), 0);?>


<?php if ((isset($_GET['do'])&&$_GET['do']=='add')||(isset($_GET['do'])&&$_GET['do']=='edit'&&isset($_smarty_tpl->tpl_vars['account']->value)&&!empty($_smarty_tpl->tpl_vars['account']->value))){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	<form action="controller.php?file=accounts&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post" enctype="multipart/form-data">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellspacing="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['username'];?>
:</strong></td>
		<td><input type="text" name="username" size="26" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['username'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['username'])){?><?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['password'];?>
:</strong></td>
		<td><input type="password" name="password" size="26" class="js-input-password common" value="<?php if (isset($_POST['password'])){?><?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" autocomplete="off"/></td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['password_confirm'];?>
:</strong></td>
		<td><input type="password" name="password2" size="26" class="common" value="<?php if (isset($_POST['password2'])){?><?php echo htmlspecialchars($_POST['password2'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['email'];?>
:</strong></td>
		<td><input type="text" name="email" size="26" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['email'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['email'])){?><?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['avatar'];?>
:</strong></td>
		<td>
			<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>
				<?php if (isset($_smarty_tpl->tpl_vars['file_path'])) {$_smarty_tpl->tpl_vars['file_path'] = clone $_smarty_tpl->tpl_vars['file_path'];
$_smarty_tpl->tpl_vars['file_path']->value = ((string)@constant('IA_HOME'))."uploads".((string)@constant('IA_DS')).((string)$_smarty_tpl->tpl_vars['account']->value['avatar']); $_smarty_tpl->tpl_vars['file_path']->nocache = null; $_smarty_tpl->tpl_vars['file_path']->scope = 0;
} else $_smarty_tpl->tpl_vars['file_path'] = new Smarty_variable(((string)@constant('IA_HOME'))."uploads".((string)@constant('IA_DS')).((string)$_smarty_tpl->tpl_vars['account']->value['avatar']), null, 0);?>

				<?php if (is_file($_smarty_tpl->tpl_vars['file_path']->value)&&file_exists($_smarty_tpl->tpl_vars['file_path']->value)){?>
					<div id="file_manage">
						<div class="image_box">
							<img src="../uploads<?php echo @constant('IA_DS');?>
<?php echo $_smarty_tpl->tpl_vars['account']->value['avatar'];?>
" alt="" width="100" height="100"/>
							<div class="delete">
								<a href="controller.php?file=accounts&do=edit&id=<?php echo $_smarty_tpl->tpl_vars['account']->value['id'];?>
&delete=photo"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['delete'];?>
</a>
							</div>
						</div>
					</div>
				<?php }?>
			<?php }?>

			<input type="file" name="avatar" id="avatar" size="40" style="float: left;">
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status'];?>
:</strong></td>
		<td>
			<select name="status">
				<option value="active" <?php if (isset($_smarty_tpl->tpl_vars['account']->value['status'])&&$_smarty_tpl->tpl_vars['account']->value['status']=='active'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='active'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</option>
				<option value="approval" <?php if (isset($_smarty_tpl->tpl_vars['account']->value['status'])&&$_smarty_tpl->tpl_vars['account']->value['status']=='approval'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='approval'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</option>
				<option value="banned" <?php if (isset($_smarty_tpl->tpl_vars['account']->value['status'])&&$_smarty_tpl->tpl_vars['account']->value['status']=='banned'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='banned'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banned'];?>
</option>
			</select>
		</td>
	</tr>
	
	<?php if (isset($_smarty_tpl->tpl_vars['plans']->value)&&!empty($_smarty_tpl->tpl_vars['plans']->value)){?>
		<tr>
			<td valign="top"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['plans'];?>
:</strong></td>
			<td>
				<p class="field">
					<input type="radio" name="plan" id="plan_reset" value="-1" <?php if (isset($_POST['plan'])&&$_POST['plan']=='-1'){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['account']->value)&&empty($_POST)){?>checked="checked"<?php }?> />
					<label for="plan_reset"><strong><?php echo smarty_function_lang(array('key'=>'not_assign_plan'),$_smarty_tpl);?>
</strong></label>
				</p>

				<?php  $_smarty_tpl->tpl_vars['plan'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plan']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['plans']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['plan']->key => $_smarty_tpl->tpl_vars['plan']->value){
$_smarty_tpl->tpl_vars['plan']->_loop = true;
?>
					<p class="field">
						<input type="radio" name="plan" value="<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" id="plan_<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['account']->value['plan_id'])&&$_smarty_tpl->tpl_vars['account']->value['plan_id']==$_smarty_tpl->tpl_vars['plan']->value['id']){?>checked="checked"<?php }elseif(isset($_POST['plan'])&&$_POST['plan']==$_smarty_tpl->tpl_vars['plan']->value['id']){?>checked="checked"<?php }?> />
						<label for="plan_<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
"><strong><?php echo $_smarty_tpl->tpl_vars['plan']->value['title'];?>
&nbsp;-&nbsp;<?php echo $_smarty_tpl->tpl_vars['config']->value['currency_symbol'];?>
<?php echo $_smarty_tpl->tpl_vars['plan']->value['cost'];?>
</strong></label><br>
						<i><?php echo $_smarty_tpl->tpl_vars['plan']->value['description'];?>
</i>
					</p>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['expire_date'];?>
:</strong></td>
			<td>
				<input type="text" name="sponsored_expire_date" id="sponsored_expire_date" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['sponsored_expire_date'])){?><?php echo $_smarty_tpl->tpl_vars['account']->value['sponsored_expire_date'];?>
<?php }elseif(isset($_POST['sponsored_expire_date'])){?><?php echo $_POST['sponsored_expire_date'];?>
<?php }?>">
			</td>
		</tr>
	<?php }?>
	</table>

	<table>
	<tr class="all">
		<td style="padding: 0 0 0 11px;">
			<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>
				<input type="checkbox" name="send_email" id="send_email">&nbsp;<label for="send_email"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['email_notif'];?>
?</label>&nbsp;|&nbsp;
			<?php }?>
			<input type="submit" name="save" class="common" value="<?php if ($_GET['do']=='add'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }?>">
		</td>
		<td style="padding: 0;">
		<?php if (isset($_GET['do'])&&$_GET['do']=='add'){?>
			<span><strong>&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['and_then'];?>
&nbsp;</strong></span>
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
	<input type="hidden" name="do" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>">
	<input type="hidden" name="old_name" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['username'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
	<input type="hidden" name="old_email" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['email'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
	<input type="hidden" name="id" value="<?php if (isset($_GET['id'])){?><?php echo $_GET['id'];?>
<?php }?>">
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<div id="box_accounts" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>'js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/bootstrap/js/passfield.min, js/admin/accounts'),$_smarty_tpl);?>


<?php echo smarty_function_ia_hooker(array('name'=>"smartyAdminAccountsAfterJSInclude"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>