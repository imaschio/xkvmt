<?php /* Smarty version Smarty-3.1.13, created on 2015-06-27 01:44:18
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/plans.tpl" */ ?>
<?php /*%%SmartyHeaderCode:213007262558e3832507a55-16728097%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c944cf96f10b4d582ba2b7d1a52bd79c94a44d9a' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/plans.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '213007262558e3832507a55-16728097',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'plan' => 0,
    'gTitle' => 0,
    'langs' => 0,
    'esynI18N' => 0,
    'code' => 0,
    'config' => 0,
    'lang' => 0,
    'valid_items' => 0,
    'item' => 0,
    'plan_pages' => 0,
    'fields' => 0,
    'field' => 0,
    'field_label' => 0,
    'visual_options' => 0,
    'option' => 0,
    'option_label' => 0,
    'category' => 0,
    'plan_categories_parents' => 0,
    'plan_categories' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_558e3832b8eae7_45996460',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_558e3832b8eae7_45996460')) {function content_558e3832b8eae7_45996460($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_modifier_capitalize')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.capitalize.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<?php if ((isset($_GET['do'])&&$_GET['do']=='add')||(isset($_GET['do'])&&$_GET['do']=='edit'&&isset($_smarty_tpl->tpl_vars['plan']->value)&&!empty($_smarty_tpl->tpl_vars['plan']->value))){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>


	<form action="controller.php?file=plans&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<?php if (count($_smarty_tpl->tpl_vars['langs']->value)>1){?>
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['language'];?>
:</strong></td>
			<td>
				<select name="lang">
					<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" <?php if ((isset($_POST['lang'])&&$_POST['lang']==$_smarty_tpl->tpl_vars['code']->value)||(isset($_smarty_tpl->tpl_vars['plan']->value['lang'])&&$_smarty_tpl->tpl_vars['plan']->value['lang']==$_smarty_tpl->tpl_vars['code']->value)){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['lang']==$_smarty_tpl->tpl_vars['code']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
</option>
					<?php } ?>
				</select>
			</td>
		</tr>
	<?php }?>
	<tr>
		<td class="tip-header" id="tip-header-plan_type" width="200"><strong><?php echo smarty_function_lang(array('key'=>"plan_type"),$_smarty_tpl);?>
:</strong></td>
		<td>
			<select name="item">
				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['valid_items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" <?php if (isset($_POST['item'])&&$_POST['item']==$_smarty_tpl->tpl_vars['item']->value){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['item'])&&$_smarty_tpl->tpl_vars['plan']->value['item']==$_smarty_tpl->tpl_vars['item']->value){?>selected="selected"<?php }elseif(isset($_GET['item'])&&$_GET['item']==$_smarty_tpl->tpl_vars['item']->value){?>selected="selected"<?php }?>><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['item']->value);?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-show_on_pages" valign="top"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['show_on_pages'];?>
:</strong></td>
		<td>
			<div id="account_pages" class="plan_pages fields-pages" style="display: none;">
				<label for="ap1"><input type="checkbox" name="pages[account][]" value="suggest" id="ap1" <?php if (isset($_POST['pages']['account'])&&in_array('suggest',$_POST['pages']['account'])){?>checked="checked"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan_pages']->value)&&in_array("suggest",$_smarty_tpl->tpl_vars['plan_pages']->value)){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['plan']->value)&&empty($_POST)){?>checked="checked"<?php }?> />
					<?php echo smarty_function_lang(array('key'=>"register_account"),$_smarty_tpl);?>
</label>
				<label for="ap2"><input type="checkbox" name="pages[account][]" value="edit" id="ap2" <?php if (isset($_POST['pages']['account'])&&in_array('edit',$_POST['pages']['account'])){?>checked="checked"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan_pages']->value)&&in_array("edit",$_smarty_tpl->tpl_vars['plan_pages']->value)){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['plan']->value)&&empty($_POST)){?>checked="checked"<?php }?> />
					<?php echo smarty_function_lang(array('key'=>"edit_account"),$_smarty_tpl);?>
</label>
			</div>

			<div id="listing_pages" class="plan_pages fields-pages" style="display: none;">
				<label for="lp1"><input type="checkbox" name="pages[listing][]" value="suggest" id="lp1" <?php if (isset($_POST['pages']['listing'])&&in_array('suggest',$_POST['pages']['listing'])){?>checked="checked"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan_pages']->value)&&in_array("suggest",$_smarty_tpl->tpl_vars['plan_pages']->value)){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['plan']->value)&&empty($_POST)){?>checked="checked"<?php }?> />
					<?php echo smarty_function_lang(array('key'=>"suggest_listing"),$_smarty_tpl);?>
</label>
				<label for="lp2"><input type="checkbox" name="pages[listing][]" value="edit" id="lp2" <?php if (isset($_POST['pages']['listing'])&&in_array('edit',$_POST['pages']['listing'])){?>checked="checked"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan_pages']->value)&&in_array("edit",$_smarty_tpl->tpl_vars['plan_pages']->value)){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['plan']->value)&&empty($_POST)){?>checked="checked"<?php }?> />
					<?php echo smarty_function_lang(array('key'=>"edit_listing"),$_smarty_tpl);?>
</label>
				<label for="lp3"><input type="checkbox" name="pages[listing][]" value="upgrade" id="lp3" <?php if (isset($_POST['pages']['listing'])&&in_array('upgrade',$_POST['pages']['listing'])){?>checked="checked"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan_pages']->value)&&in_array("upgrade",$_smarty_tpl->tpl_vars['plan_pages']->value)){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['plan']->value)&&empty($_POST)){?>checked="checked"<?php }?> />
					<?php echo smarty_function_lang(array('key'=>"upgrade_listing"),$_smarty_tpl);?>
</label>
			</div>
		</td>
	</tr>
	</table>
	<?php if ($_smarty_tpl->tpl_vars['fields']->value){?>
		<div id="listing_fields" style="display: none;">
			<table cellspacing="0" cellpadding="0" width="100%" class="striped">
			<tr>
				<td class="tip-header" id="tip-header-fields_for_plan" width="200" valign="top"><strong><?php echo smarty_function_lang(array('key'=>"fields_for_plan"),$_smarty_tpl);?>
:</strong></td>
				<td>
					<fieldset>
						<legend><input type="checkbox" value="Check all" id="check_all_fields">&nbsp;<label for="check_all_fields"><?php echo smarty_function_lang(array('key'=>"all_fields"),$_smarty_tpl);?>
</label></legend>
						<ul>
							<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
								<?php if (isset($_smarty_tpl->tpl_vars["field_label"])) {$_smarty_tpl->tpl_vars["field_label"] = clone $_smarty_tpl->tpl_vars["field_label"];
$_smarty_tpl->tpl_vars["field_label"]->value = ("field_").($_smarty_tpl->tpl_vars['field']->value['name']); $_smarty_tpl->tpl_vars["field_label"]->nocache = null; $_smarty_tpl->tpl_vars["field_label"]->scope = 0;
} else $_smarty_tpl->tpl_vars["field_label"] = new Smarty_variable(("field_").($_smarty_tpl->tpl_vars['field']->value['name']), null, 0);?>
								<li>
									<input type="checkbox" name="fields[]" value="<?php echo $_smarty_tpl->tpl_vars['field']->value['id'];?>
" id="field_<?php echo $_smarty_tpl->tpl_vars['field']->value['id'];?>
" <?php if (isset($_POST['fields'])){?><?php if (in_array($_smarty_tpl->tpl_vars['field']->value['id'],$_POST['fields'])){?>checked="checked"<?php }?><?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value)&&$_smarty_tpl->tpl_vars['plan']->value['fields']){?><?php if (in_array($_smarty_tpl->tpl_vars['field']->value['id'],$_smarty_tpl->tpl_vars['plan']->value['fields'])){?>checked="checked"<?php }?><?php }?> />&nbsp;<label for="field_<?php echo $_smarty_tpl->tpl_vars['field']->value['id'];?>
"><?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['field_label']->value),$_smarty_tpl);?>
</label>
								</li>
							<?php } ?>
						</ul>
					</fieldset>
				</td>
			</tr>
			</table>
		</div>
	<?php }?>

	<?php if ($_smarty_tpl->tpl_vars['visual_options']->value){?>
		<div id="visual_options" style="display: none;">
			<table cellspacing="0" cellpadding="0" width="100%" class="striped">
			<tr>
				<td class="tip-header" id="tip-header-fields_for_plan" width="200" valign="top"><strong><?php echo smarty_function_lang(array('key'=>'visual_options'),$_smarty_tpl);?>
:</strong></td>
				<td>
					<fieldset>
						<legend><input type="checkbox" value="Check all" id="check_all_options">&nbsp;<label for="check_all_options"><?php echo smarty_function_lang(array('key'=>'check_all'),$_smarty_tpl);?>
</label></legend>
						<ul>
							<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['visual_options']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value){
$_smarty_tpl->tpl_vars['option']->_loop = true;
?>
								<?php if (isset($_smarty_tpl->tpl_vars["option_label"])) {$_smarty_tpl->tpl_vars["option_label"] = clone $_smarty_tpl->tpl_vars["option_label"];
$_smarty_tpl->tpl_vars["option_label"]->value = "listing_option_".((string)$_smarty_tpl->tpl_vars['option']->value['name']); $_smarty_tpl->tpl_vars["option_label"]->nocache = null; $_smarty_tpl->tpl_vars["option_label"]->scope = 0;
} else $_smarty_tpl->tpl_vars["option_label"] = new Smarty_variable("listing_option_".((string)$_smarty_tpl->tpl_vars['option']->value['name']), null, 0);?>
								<li>
									<label for="<?php echo $_smarty_tpl->tpl_vars['option']->value['name'];?>
"><input type="checkbox" name="visual_options[]" value="<?php echo $_smarty_tpl->tpl_vars['option']->value['name'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['option']->value['name'];?>
" <?php if (isset($_POST['visual_options'])){?><?php if (in_array($_smarty_tpl->tpl_vars['option']->value['name'],$_POST['visual_options'])){?>checked="checked"<?php }?><?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value)&&$_smarty_tpl->tpl_vars['plan']->value['visual_options']){?><?php if (in_array($_smarty_tpl->tpl_vars['option']->value['name'],$_smarty_tpl->tpl_vars['plan']->value['visual_options'])){?>checked="checked"<?php }?><?php }?> />&nbsp;<?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['option_label']->value),$_smarty_tpl);?>
</label>
								</li>
							<?php } ?>
						</ul>
					</fieldset>
				</td>
			</tr>
			</table>
		</div>
	<?php }?>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td class="tip-header" id="tip-header-plan_title" width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
:</strong></td>
		<td><input type="text" name="title" size="30" class="common" value="<?php if (isset($_POST['title'])){?><?php echo htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['title'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['plan']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-plan_description"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['description'];?>
:</strong></td>
		<td><textarea name="description" cols="5" rows="4" class="common"><?php if (isset($_POST['description'])){?><?php echo htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['description'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['plan']->value['description'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></textarea></td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-plan_cost"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['cost'];?>
:</strong></td>
		<td><input type="text" class="common numeric" name="cost" size="30" value="<?php if (isset($_POST['cost'])){?><?php echo htmlspecialchars($_POST['cost'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['cost'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['plan']->value['cost'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['cost'])){?><?php echo htmlspecialchars($_POST['cost'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
	</tr>
	</table>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_deep_links"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['deep_links'];?>
:</strong></td>
			<td><input type="text" class="common numeric" name="deep_links" size="30" value="<?php if (isset($_POST['deep_links'])){?><?php echo htmlspecialchars($_POST['deep_links'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['deep_links'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['plan']->value['deep_links'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
		</tr>
		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_multicross"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['multicross'];?>
:</strong></td>
			<td><input type="text" <?php if (!$_smarty_tpl->tpl_vars['config']->value['mcross_functionality']){?>disabled="disabled"<?php }?> class="common numeric" name="multicross" size="30" value="<?php if (isset($_POST['multicross'])){?><?php echo htmlspecialchars($_POST['multicross'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['multicross'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['plan']->value['multicross'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
		</tr>
		<tr>
			<td class="tip-header" id="tip-header-plan_days"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['days'];?>
:</strong></td>
			<td><input type="text" class="common numeric" name="period" size="30" value="<?php if (isset($_POST['period'])){?><?php echo htmlspecialchars($_POST['period'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['period'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['plan']->value['period'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
		</tr>
		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_expire_notif" width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['send_expiration_email'];?>
:</strong></td>
			<td><input type="text" name="expire_notif" size="30" class="common" value="<?php if (isset($_POST['expire_notif'])){?><?php echo htmlspecialchars($_POST['expire_notif'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_notif'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['plan']->value['expire_notif'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
		</tr>
		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_status_after_submit"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status_after_submit'];?>
:</strong></td>
			<td>
				<select name="set_status">
					<option value="active" <?php if (isset($_POST['set_status'])&&$_POST['set_status']=='active'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['set_status'])&&$_smarty_tpl->tpl_vars['plan']->value['set_status']=='active'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</option>
					<option value="approval" <?php if (isset($_POST['set_status'])&&$_POST['set_status']=='approval'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['set_status'])&&$_smarty_tpl->tpl_vars['plan']->value['set_status']=='approval'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</option>
					<option value="suspended" <?php if (isset($_POST['set_status'])&&$_POST['set_status']=='suspended'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['set_status'])&&$_smarty_tpl->tpl_vars['plan']->value['set_status']=='suspended'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['suspended'];?>
</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tip-header" id="tip-header-plan_mark_after_submit" width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['mark_after_submit'];?>
:</strong></td>
			<td>
				<select name="markas">
					<option value="sponsored" <?php if (isset($_POST['markas'])&&$_POST['markas']=='sponsored'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['mark_as'])&&$_smarty_tpl->tpl_vars['plan']->value['mark_as']=='sponsored'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['sponsored'];?>
</option>
					<option value="featured" <?php if (isset($_POST['markas'])&&$_POST['markas']=='featured'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['mark_as'])&&$_smarty_tpl->tpl_vars['plan']->value['mark_as']=='featured'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['featured'];?>
</option>
					<option value="partner" <?php if (isset($_POST['markas'])&&$_POST['markas']=='partner'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['mark_as'])&&$_smarty_tpl->tpl_vars['plan']->value['mark_as']=='partner'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['partner'];?>
</option>
					<option value="regular" <?php if (isset($_POST['markas'])&&$_POST['markas']=='regular'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['mark_as'])&&$_smarty_tpl->tpl_vars['plan']->value['mark_as']=='regular'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['regular'];?>
</option>
				</select>
			</td>
		</tr>
		<tr class="listing_options" style="display:none;">
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['cron_for_expiration'];?>
:</strong></td>
			<td>
				<select name="expire_action">
					<option value="" <?php if (isset($_POST['expire_action'])&&$_POST['expire_action']==''){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_action'])&&$_smarty_tpl->tpl_vars['plan']->value['expire_action']==''){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['nothing'];?>
</option>
					<option value="remove" <?php if (isset($_POST['expire_action'])&&$_POST['expire_action']=='remove'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_action'])&&$_smarty_tpl->tpl_vars['plan']->value['expire_action']=='remove'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
</option>
					<optgroup label="Status">
						<option value="approval" <?php if (isset($_POST['expire_action'])&&$_POST['expire_action']=='approval'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_action'])&&$_smarty_tpl->tpl_vars['plan']->value['expire_action']=='approval'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</option>
						<option value="banned" <?php if (isset($_POST['expire_action'])&&$_POST['expire_action']=='banned'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_action'])&&$_smarty_tpl->tpl_vars['plan']->value['expire_action']=='banned'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banned'];?>
</option>
						<option value="suspended" <?php if (isset($_POST['expire_action'])&&$_POST['expire_action']=='suspended'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_action'])&&$_smarty_tpl->tpl_vars['plan']->value['expire_action']=='suspended'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['suspended'];?>
</option>
					</optgroup>
					<optgroup label="Type">
						<option value="regular" <?php if (isset($_POST['expire_action'])&&$_POST['expire_action']=='regular'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_action'])&&$_smarty_tpl->tpl_vars['plan']->value['expire_action']=='regular'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['regular'];?>
</option>
						<option value="featured" <?php if (isset($_POST['expire_action'])&&$_POST['expire_action']=='featured'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_action'])&&$_smarty_tpl->tpl_vars['plan']->value['expire_action']=='featured'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['featured'];?>
</option>
						<option value="partner" <?php if (isset($_POST['expire_action'])&&$_POST['expire_action']=='partner'){?>selected="selected"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['expire_action'])&&$_smarty_tpl->tpl_vars['plan']->value['expire_action']=='partner'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['partner'];?>
</option>
					</optgroup>
				</select>
			</td>
		</tr>

		<tr class="listing_options" style="display:none;">
			<td class="tip-header" id="tip-header-plan_category"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['category'];?>
:</strong></td>
			<td>
				<div id="tree"></div>
				<label><input type="checkbox" name="recursive" value="1" <?php if (isset($_POST['recursive'])&&$_POST['recursive']=='1'){?>checked="checked"<?php }elseif(isset($_smarty_tpl->tpl_vars['plan']->value['recursive'])&&$_smarty_tpl->tpl_vars['plan']->value['recursive']=='1'){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['plan']->value)&&!$_POST){?>checked="checked"<?php }?> />&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['include_subcats'];?>
</label>
			</td>
		</tr>
	</table>

	<div id="addit_account_options" style="display:none;">
		<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr>
			<td class="tip-header" width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['num_allowed_listing'];?>
:</strong></td>
			<td>
				<span style="float: left;">
					<input type="radio" name="num_allowed_listings_type" value="-1" <?php if (isset($_smarty_tpl->tpl_vars['plan']->value['num_allowed_listings'])&&isset($_GET['do'])&&$_GET['do']=='edit'&&$_smarty_tpl->tpl_vars['plan']->value['num_allowed_listings']=='-1'){?>checked="checked"<?php }elseif(isset($_POST['num_allowed_listings'])&&$_POST['num_allowed_listings']=='-1'){?>checked="checked"<?php }elseif(!$_POST){?>checked="checked"<?php }?> id="nal0"><label for="nal0">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['do_not_limit'];?>
</label>
					<input type="radio" name="num_allowed_listings_type" value="0" <?php if (isset($_smarty_tpl->tpl_vars['plan']->value['num_allowed_listings'])&&isset($_GET['do'])&&$_GET['do']=='edit'&&$_smarty_tpl->tpl_vars['category']->value['num_allowed_listings']=='0'){?>checked="checked"<?php }elseif(isset($_POST['num_allowed_listings_type'])&&$_POST['num_allowed_listings_type']=='0'){?>checked="checked"<?php }?> id="nal1"><label for="nal1">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['use_global'];?>
</label>
					<input type="radio" name="num_allowed_listings_type" value="1" <?php if (isset($_smarty_tpl->tpl_vars['plan']->value['num_allowed_listings'])&&isset($_GET['do'])&&$_GET['do']=='edit'&&$_smarty_tpl->tpl_vars['plan']->value['num_allowed_listings']>0){?>checked="checked"<?php }elseif(isset($_POST['num_allowed_listings'])&&$_POST['num_allowed_listings']=='1'){?>checked="checked"<?php }?> id="nal2"/><label for="nal2">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['custom'];?>
</label>&nbsp;&nbsp;&nbsp;
				</span>
				<span id="nal" style="display:none;">
					<input class="common numeric" type="text" name="num_allowed_listings" size="5" value="<?php if (isset($_smarty_tpl->tpl_vars['plan']->value['num_allowed_listings'])){?><?php echo $_smarty_tpl->tpl_vars['plan']->value['num_allowed_listings'];?>
<?php }elseif(isset($_POST['num_allowed_listings'])){?><?php echo $_POST['num_allowed_listings'];?>
<?php }?>">
				</span>
			</td>
		</tr>
		</table>
	</div>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">

	<?php echo smarty_function_ia_hooker(array('name'=>"plansBeforeSubmitButton"),$_smarty_tpl);?>


	<tr class="all">
		<td colspan="2">
			<input type="submit" name="save" class="common" value="<?php if ($_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
<?php }?>">
		</td>
	</tr>
	</table>

	<input type="hidden" name="id" value="<?php if (isset($_smarty_tpl->tpl_vars['plan']->value['id'])){?><?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
<?php }?>">
	<input type="hidden" name="old_name" value="<?php if (isset($_smarty_tpl->tpl_vars['plan']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['plan']->value['name'];?>
<?php }?>">
	<input type="hidden" name="do" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>">
	<input type="hidden" name="categories_parents" id="categories_parents" value="<?php if (isset($_POST['categories_parents'])){?><?php echo $_POST['categories_parents'];?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan_categories_parents']->value)){?><?php echo $_smarty_tpl->tpl_vars['plan_categories_parents']->value;?>
<?php }?>">
	<input type="hidden" name="categories" id="categories" value="<?php if (isset($_POST['categories'])){?><?php echo $_POST['categories'];?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['plan_categories']->value)){?><?php echo $_smarty_tpl->tpl_vars['plan_categories']->value;?>
<?php }?>">
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<div id="box_plans" style="margin-top: 15px;"></div>
<?php }?>

<div style="display:none;">
	<div id="tip-content-plan_type"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_type"),$_smarty_tpl);?>
</div>
	<div id="tip-content-show_on_pages"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_show_on_pages"),$_smarty_tpl);?>
</div>
	<div id="tip-content-fields_for_plan"><?php echo smarty_function_lang(array('key'=>"tooltip_fields_for_plan"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_title"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_title"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_description"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_description"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_cost"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_cost"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_deep_links"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_deep_links"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_multicross"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_multicross"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_days"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_days"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_expire_notif"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_expire_notif"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_mark_after_submit"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_mark_after_submit"),$_smarty_tpl);?>
</div>
	<div id="tip-content-plan_category"><?php echo smarty_function_lang(array('key'=>"tooltip_plan_category"),$_smarty_tpl);?>
</div>
</div>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/plans"),$_smarty_tpl);?>


<?php echo smarty_function_ia_hooker(array('name'=>"plansAfterJsInclude"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>