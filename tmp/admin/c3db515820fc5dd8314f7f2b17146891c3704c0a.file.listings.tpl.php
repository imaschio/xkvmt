<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 05:19:44
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/listings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:126380217955094330d68665-26580632%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c3db515820fc5dd8314f7f2b17146891c3704c0a' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/listings.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '126380217955094330d68665-26580632',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'esynI18N' => 0,
    'langs' => 0,
    'key' => 0,
    'value' => 0,
    'email_groups' => 0,
    'group' => 0,
    'tmpls' => 0,
    'email_group_key' => 0,
    'i' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55094330e0c850_69024256',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55094330e0c850_69024256')) {function content_55094330e0c850_69024256($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<div id="box_listings" style="margin-top: 15px;"></div>

<div id="remove_reason" style="display: none;">
	<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listing_remove_reason'];?>
<br />
	<textarea cols="40" rows="5" name="body" id="remove_reason_text" class="common" style="width: 99%;"></textarea>
</div>

<div class="x-hidden email-templates" id="email_templates">
	<div class="buttons" style="margin: 0 10px 0 0;">
		<a href="#" id="tags"><img src="templates/default/img/icons/list.png" title="Email Templates Tags" alt="Email Templates Tags"></a>
	</div>
	<p>
		<strong style="width:100px; display:inline-block;"><?php echo smarty_function_lang(array('key'=>"language"),$_smarty_tpl);?>
:</strong>
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
	</p>
	<p>
		<strong style="width:100px; display:inline-block;">Email to:</strong>
 		<label style="padding-right:5px;"><input type="radio" name="email" value="listing" checked="checked"> <?php echo smarty_function_lang(array('key'=>"listing"),$_smarty_tpl);?>
</label>
 		<label style="padding-right:5px;"><input type="radio" name="email" value="account"> <?php echo smarty_function_lang(array('key'=>"account"),$_smarty_tpl);?>
</label>
	</p>
	<p>
		<strong style="width:100px; display:inline-block;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['email'];?>
:</strong>
		<select id="tpl" name="tpl" style="width:350px; margin-right: 5px;">
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
					<optgroup class="<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" label="<?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['email_group_key']->value),$_smarty_tpl);?>
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
	</p>
	<p>
		<strong style="width:100px; display:inline-block;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['subject'];?>
:</strong>
		<input type="text" name="subject" id="subject" style="width:350px;" class="common">
	</p>
	<p>
		<strong style="width:100px; display:inline-block;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['body'];?>
:</strong>
		<textarea name="body" id="body" cols="107" rows="18" class="common" style="resize:both"></textarea>
	</p>
</div>
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

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/rowexpander/rowExpander, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/ext/plugins/datetime/datetime, js/ckeditor/ckeditor, js/jquery/plugins/jquery.form.ajaxLoader, js/admin/listings"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>