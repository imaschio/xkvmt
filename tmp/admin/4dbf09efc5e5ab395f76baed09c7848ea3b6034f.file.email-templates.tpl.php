<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:35:44
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/email-templates.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7465279755090eb067e4d8-15384277%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4dbf09efc5e5ab395f76baed09c7848ea3b6034f' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/email-templates.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7465279755090eb067e4d8-15384277',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'langs' => 0,
    'key' => 0,
    'value' => 0,
    'esynI18N' => 0,
    'email_groups' => 0,
    'group' => 0,
    'tmpls' => 0,
    'email_group_key' => 0,
    'i' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55090eb0768fa0_79366237',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55090eb0768fa0_79366237')) {function content_55090eb0768fa0_79366237($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>(($tmp = @$_smarty_tpl->tpl_vars['gTitle']->value)===null||$tmp==='' ? "test" : $tmp)), 0);?>

<form method="post" action="controller.php?file=email-templates" id="tpl_form">
	<table class="striped">
		<tr>
			<td width="200"><strong><?php echo smarty_function_lang(array('key'=>"language"),$_smarty_tpl);?>
:</strong></td>
			<td>
				<select name="lang" id="lang" class="common" <?php if (count($_smarty_tpl->tpl_vars['langs']->value)==1){?>disabled="disabled"<?php }?>>
					<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if ((isset($_POST['lang'])&&$_smarty_tpl->tpl_vars['key']->value==$_POST['lang'])||(isset($_POST['lang'])&&trim($_smarty_tpl->tpl_vars['value']->value,"'")==$_POST['lang'])){?>selected="selected"<?php }?>><?php echo trim($_smarty_tpl->tpl_vars['value']->value,"'");?>
</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['email'];?>
:</strong></td>
			<td>
				<span style="float: left; margin-right: 5px";>
					<select id="tpl" name="tpl">
						<option value=""><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['_select_'];?>
</option>

						<?php  $_smarty_tpl->tpl_vars["group"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["group"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['email_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["group"]->key => $_smarty_tpl->tpl_vars["group"]->value){
$_smarty_tpl->tpl_vars["group"]->_loop = true;
?>
							<?php if (isset($_smarty_tpl->tpl_vars["email_group_key"])) {$_smarty_tpl->tpl_vars["email_group_key"] = clone $_smarty_tpl->tpl_vars["email_group_key"];
$_smarty_tpl->tpl_vars["email_group_key"]->value = ("email_group_").($_smarty_tpl->tpl_vars['group']->value); $_smarty_tpl->tpl_vars["email_group_key"]->nocache = null; $_smarty_tpl->tpl_vars["email_group_key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["email_group_key"] = new Smarty_variable(("email_group_").($_smarty_tpl->tpl_vars['group']->value), null, 0);?>
							<?php if (!empty($_smarty_tpl->tpl_vars['tmpls']->value[$_smarty_tpl->tpl_vars['group']->value])){?>
								<optgroup label="<?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['email_group_key']->value),$_smarty_tpl);?>
">
									<?php  $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['i']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['tmpls']->value[$_smarty_tpl->tpl_vars['group']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['i']->key => $_smarty_tpl->tpl_vars['i']->value){
$_smarty_tpl->tpl_vars['i']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['i']->key;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['i']->value, ENT_QUOTES, 'UTF-8', true);?>
</option>
									<?php } ?>
								</optgroup>
							<?php }?>
						<?php } ?>
					</select>
				</span>

				<span id="switcher" style="display: none;">
					<?php echo smarty_function_html_radio_switcher(array('value'=>1,'name'=>'template'),$_smarty_tpl);?>

				</span>

			</td>
		</tr>
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['subject'];?>
:</strong></td>
			<td><input type="text" name="subject" id="subject" size="56" class="common"></td>
		</tr>
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['body'];?>
:</strong></td>
			<td><textarea name="body" id="body" cols="70" rows="20" class="common" style="resize:both"></textarea></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" class="common" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save'];?>
">
				<input type="submit" class="common" id="save_as_new" value="<?php echo smarty_function_lang(array('key'=>'save_as_new'),$_smarty_tpl);?>
">
			</td>
		</tr>
	</table>
</form>

<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div class="x-hidden template-tags" id="template_tags">
	<div class="option_tip" style="margin: 5px 5px 0 5px;"><?php echo smarty_function_lang(array('key'=>"email_templates_tags_info"),$_smarty_tpl);?>
</div>
	<ul>
		<li><h4><?php echo smarty_function_lang(array('key'=>"common"),$_smarty_tpl);?>
</h4></li>
		<li><a href="#" class="email_tags">{dir_title}</a> - <span><?php echo $_smarty_tpl->tpl_vars['config']->value['site'];?>
</span></li>
		<li><a href="#" class="email_tags">{dir_url}</a> - <span><?php echo @constant('IA_URL');?>
</span></li>
		<li><a href="#" class="email_tags">{dir_email}</a> - <span><?php echo $_smarty_tpl->tpl_vars['config']->value['site_email'];?>
</span></li>
	</ul>
	<ul>
		<li><h4><?php echo smarty_function_lang(array('key'=>"account"),$_smarty_tpl);?>
</h4></li>
		<li><a href="#" class="email_tags">{account_name}</a></li>
		<li><a href="#" class="email_tags">{account_key}</a></li>
		<li><a href="#" class="email_tags">{account_email}</a></li>
		<li><a href="#" class="email_tags">{account_status}</a></li>
	</ul>
	<ul>
		<li><h4><?php echo smarty_function_lang(array('key'=>"listing"),$_smarty_tpl);?>
</h4></li>
		<li><a href="#" class="email_tags">{listing_id}</a></li>
		<li><a href="#" class="email_tags">{listing_title}</a></li>
		<li><a href="#" class="email_tags">{listing_url}</a></li>
		<li><a href="#" class="email_tags">{listing_email}</a></li>
		<li><a href="#" class="email_tags">{listing_status}</a></li>
		<li><a href="#" class="email_tags">{listing_rank}</a></li>
		<li><a href="#" class="email_tags">{listing_description}</a></li>
		<li><a href="#" class="email_tags">{listing_path}</a></li>
	</ul>
	<ul>
		<li><h4><?php echo smarty_function_lang(array('key'=>"category"),$_smarty_tpl);?>
</h4></li>
		<li><a href="#" class="email_tags">{category_title}</a></li>
		<li><a href="#" class="email_tags">{category_path}</a></li>
	</ul>
</div>

<div class="x-hidden template-name" id="template_name">
	<strong><?php echo smarty_function_lang(array('key'=>"email_template_name"),$_smarty_tpl);?>
</strong>: custom_ <input type="text" size="56" class="common" value="" id="tpl_name" style="padding:0; margin:0;">
</div>

<?php echo smarty_function_include_file(array('js'=>"js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/jquery/plugins/jquery.form.ajaxLoader, js/ckeditor/ckeditor, js/admin/email-templates"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>