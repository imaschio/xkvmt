<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:31:24
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/language.tpl" */ ?>
<?php /*%%SmartyHeaderCode:124284066855090dacdf3aa0-17197205%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ecf3635af071ee0dfd773cd29e9215094ce9c074' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/language.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '124284066855090dacdf3aa0-17197205',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'esynI18N' => 0,
    'langs' => 0,
    'language' => 0,
    'code' => 0,
    'config' => 0,
    'lang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55090dacefc881_56923726',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55090dacefc881_56923726')) {function content_55090dacefc881_56923726($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.replace.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<div id="box_add_phrase" style="margin-top: 15px;">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

</div>

<?php if ($_GET['view']=='language'){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	
	<form action="controller.php?file=language&amp;view=language" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellspacing="0" cellpadding="10" width="100%" class="common">
	<tr>
		<th class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['language'];?>
</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['default'];?>
</th>
	</tr>
		
	<?php  $_smarty_tpl->tpl_vars['language'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['language']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['language']->key => $_smarty_tpl->tpl_vars['language']->value){
$_smarty_tpl->tpl_vars['language']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['language']->key;
?>
	<tr>
		<td class="first"><?php echo $_smarty_tpl->tpl_vars['language']->value;?>
</td>
		<td><a href="controller.php?file=language&amp;view=phrase&amp;language=<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['esynI18N']->value['edit_translate'],"language",$_smarty_tpl->tpl_vars['language']->value);?>
</a></td>
		<td>
		<?php if (count($_smarty_tpl->tpl_vars['langs']->value)!=1&&$_smarty_tpl->tpl_vars['code']->value!=$_smarty_tpl->tpl_vars['config']->value['lang']){?>
			<a class="delete_language" href="controller.php?file=language&amp;view=language&amp;do=delete&amp;language=<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['delete'];?>
</a>&nbsp;|&nbsp;
		<?php }?>
		<a href="controller.php?file=language&amp;view=language&amp;do=download&amp;language=<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['download'];?>
</a></td>
		<td width="100">
			<?php if ($_smarty_tpl->tpl_vars['code']->value!=$_smarty_tpl->tpl_vars['config']->value['lang']){?>
				<a href="controller.php?file=language&amp;view=language&amp;do=default&amp;language=<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['set_default'];?>
</a>
			<?php }else{ ?>
				&nbsp;
			<?php }?>
		</td>
	</tr>
	<?php } ?>
	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }elseif($_GET['view']=='phrase'){?>
	<div id="box_phrases" style="margin-top: 15px;"></div>
<?php }elseif($_GET['view']=='download'){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	
	<form action="controller.php?file=language&amp;view=download" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<input type="hidden" name="do" value="download">
	<table cellspacing="0" cellpadding="10" width="100%" class="common">
	<tr>
		<th colspan="2" class="first caption"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['download'];?>
</td>
	</tr>
	<tr>
		<td class="first" width="200"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['language'];?>
</td>
		<td>
			<select name="lang" <?php if (count($_smarty_tpl->tpl_vars['langs']->value)==1){?>disabled="disabled"<?php }?>>
				<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['file_format'];?>
</td>
		<td>
			<select name="file_format">
				<option value="csv" <?php if (isset($_POST['file_format'])&&$_POST['file_format']=='csv'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['csv_format'];?>
</option>
				<option value="sql" <?php if (isset($_POST['file_format'])&&$_POST['file_format']=='sql'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['sql_format'];?>
</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['filename'];?>
</td>
		<td><input type="text" size="40" name="filename" class="common" value="<?php if (isset($_POST['filename'])&&!empty($_POST['filename'])){?><?php echo htmlspecialchars($_POST['filename'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?>esc_language<?php }?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="first">
			<input type="submit" class="common" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['download'];?>
">
		</td>
	</tr>
	</table>
	</form>
	
	<form action="controller.php?file=language&amp;view=download" method="post" enctype="multipart/form-data">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<input type="hidden" name="do" value="import">
	<table cellspacing="0" cellpadding="10" width="100%" class="common">
	<tr>
		<th colspan="2" class="first caption"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['import'];?>
</td>
	</tr>
	<tr>
		<td class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['file_format'];?>
</td>
		<td>
			<select name="file_format">
				<option value="csv" <?php if (isset($_POST['file_format'])&&$_POST['file_format']=='csv'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['csv_format'];?>
</option>
				<option value="sql" <?php if (isset($_POST['file_format'])&&$_POST['file_format']=='sql'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['sql_format'];?>
</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="first" width="200"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['import_from_pc'];?>
</td>
		<td><input type="file" name="language_file" size="40"></td>
	</tr>
	<tr>
		<td class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['import_from_server'];?>
</td>
		<td><input type="text" size="40" name="language_file2" class="common" value="../updates/"></td>
	</tr>
	<tr>
		<td colspan="2" align="center" class="first">
			<input type="submit" class="common" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['import'];?>
">
		</td>
	</tr>
	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }elseif($_GET['view']=='add_lang'){?>
	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['copy_language']), 0);?>

	
	<form action="controller.php?file=language&amp;view=add_lang" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<input type="hidden" name="do" value="add_lang">
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="250"><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['esynI18N']->value['copy_default_language_to'],"[lang]",$_smarty_tpl->tpl_vars['langs']->value[$_smarty_tpl->tpl_vars['config']->value['lang']]);?>
</td>
		<td>
			<label for="new_code"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['iso_code'];?>
</label>
			<input id="new_code" size="2" maxlength="2" type="text" name="new_code" class="common" value="<?php if (isset($_POST['new_code'])){?><?php echo htmlspecialchars($_POST['new_code'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
			<label for="new_lang"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
</label>
			<input id="new_lang" size="10" maxlength="40" type="text" name="new_lang" class="common" value="<?php if (isset($_POST['new_lang'])){?><?php echo htmlspecialchars($_POST['new_lang'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
			<input type="submit" class="common" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['copy_language'];?>
">
		</td>
	</tr>
	
	<tr>
		<td width="200"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['all_languages'];?>
</td>
		<td>
		<?php  $_smarty_tpl->tpl_vars['language'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['language']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['language']->key => $_smarty_tpl->tpl_vars['language']->value){
$_smarty_tpl->tpl_vars['language']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['language']->key;
?>
			<b><?php echo $_smarty_tpl->tpl_vars['language']->value;?>
</b>&nbsp;<?php if ($_smarty_tpl->tpl_vars['code']->value==$_smarty_tpl->tpl_vars['config']->value['lang']){?>[ <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['default'];?>
 ]<?php }?><br />
		<?php } ?>
		</td>
	</tr>

	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }elseif($_GET['view']=='compare'){?>
	<div id="box_compare" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/admin/language"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>