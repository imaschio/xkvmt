<?php /* Smarty version Smarty-3.1.13, created on 2015-03-22 07:57:57
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/listing-fields.tpl" */ ?>
<?php /*%%SmartyHeaderCode:203077508550eae45e7bc03-93615476%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '443bd759a0d4de383e242620826bfdd3cb98d6e8' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/listing-fields.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '203077508550eae45e7bc03-93615476',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'field' => 0,
    'gTitle' => 0,
    'esynI18N' => 0,
    'langs' => 0,
    'lang' => 0,
    'code' => 0,
    'field_titles' => 0,
    'page_title' => 0,
    'pages' => 0,
    'field_groups' => 0,
    'group' => 0,
    'plans' => 0,
    'plan' => 0,
    'field_plans' => 0,
    'field_types' => 0,
    'key' => 0,
    'type' => 0,
    'lengths' => 0,
    'min_length' => 0,
    'max_length' => 0,
    'field_values' => 0,
    'value' => 0,
    'config' => 0,
    'field_categories' => 0,
    'field_categories_parents' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550eae465d7a67_78231816',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550eae465d7a67_78231816')) {function content_550eae465d7a67_78231816($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<?php if ((isset($_GET['do'])&&$_GET['do']=='add')||(isset($_GET['do'])&&$_GET['do']=='edit'&&isset($_smarty_tpl->tpl_vars['field']->value)&&!empty($_smarty_tpl->tpl_vars['field']->value))){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	<form action="controller.php?file=listing-fields&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['name'];?>
:</strong></td>
		<td>
			<input type="text" name="name" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
<?php }elseif(isset($_POST['name'])){?><?php echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" <?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>readonly="readonly"<?php }?> />
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
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
:</strong></td>
		<td>
			<?php if (isset($_smarty_tpl->tpl_vars['field']->value['name'])){?>
				<?php if (isset($_smarty_tpl->tpl_vars["page_title"])) {$_smarty_tpl->tpl_vars["page_title"] = clone $_smarty_tpl->tpl_vars["page_title"];
$_smarty_tpl->tpl_vars["page_title"]->value = $_smarty_tpl->tpl_vars['field_titles']->value[$_smarty_tpl->tpl_vars['code']->value]; $_smarty_tpl->tpl_vars["page_title"]->nocache = null; $_smarty_tpl->tpl_vars["page_title"]->scope = 0;
} else $_smarty_tpl->tpl_vars["page_title"] = new Smarty_variable($_smarty_tpl->tpl_vars['field_titles']->value[$_smarty_tpl->tpl_vars['code']->value], null, 0);?>
			<?php }?>
			<input type="text" name="title[<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
]" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['page_title']->value)){?><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
<?php }elseif(isset($_POST['title'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo $_POST['title'][$_smarty_tpl->tpl_vars['code']->value];?>
<?php }?>">
		</td>
	</tr>
	<?php } ?>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['show_on_pages'];?>
:</strong></td>
		<td class="fields-pages">
			<label for="p1"><input type="checkbox" name="pages[]" value="suggest" id="p1" <?php if (in_array("suggest",$_smarty_tpl->tpl_vars['pages']->value)){?>checked="checked"<?php }?> /><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['suggest_listing'];?>
</label>
			<label for="p2"><input type="checkbox" name="pages[]" value="edit" id="p2" <?php if (in_array("edit",$_smarty_tpl->tpl_vars['pages']->value)){?>checked="checked"<?php }?> /><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['edit_listing'];?>
</label>
			<label for="p3"><input type="checkbox" name="pages[]" value="view" id="p3" <?php if (in_array("view",$_smarty_tpl->tpl_vars['pages']->value)){?>checked="checked"<?php }?> /><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listing_details'];?>
</label>
		</td>
	</tr>

	<?php if (!empty($_smarty_tpl->tpl_vars['field_groups']->value)){?>
	<tr>
		<td><strong><?php echo smarty_function_lang(array('key'=>'show_in_group'),$_smarty_tpl);?>
:</strong></td>
		<td>
			<select name="group">
				<option value="">--&nbsp;<?php echo smarty_function_lang(array('key'=>"select_field_group"),$_smarty_tpl);?>
&nbsp;--</option>
				<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['field_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['group']->value['name'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['group'])&&$_smarty_tpl->tpl_vars['field']->value['group']==$_smarty_tpl->tpl_vars['group']->value['name']){?>selected="selected"<?php }?>><?php echo smarty_function_lang(array('key'=>("field_group_title_").($_smarty_tpl->tpl_vars['group']->value['name'])),$_smarty_tpl);?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<?php }?>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['tooltip'];?>
:</strong></td>
		<td>
			<textarea name="tooltip" rows="3" cols="20"><?php if (isset($_smarty_tpl->tpl_vars['field']->value['tooltip'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['tooltip'];?>
<?php }elseif(isset($_POST['tooltip'])){?><?php echo htmlspecialchars($_POST['tooltip'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></textarea>
		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['required_field'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['field']->value['required'])===null||$tmp==='' ? $_POST['required'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"required"),$_smarty_tpl);?>

		</td>
	</tr>
	
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['visible_for_admin'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['field']->value['adminonly'])===null||$tmp==='' ? $_POST['adminonly'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"adminonly"),$_smarty_tpl);?>

		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['searchable'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['field']->value['searchable'])===null||$tmp==='' ? $_POST['searchable'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"searchable"),$_smarty_tpl);?>

		</td>
	</tr>

	<tr id="fulltext_search_zone" style="display: none;">
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['fulltext_search'];?>
</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['field']->value['make_fulltext'])===null||$tmp==='' ? $_POST['make_fulltext'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"make_fulltext"),$_smarty_tpl);?>

		</td>
	</tr>
	
	<?php if ($_smarty_tpl->tpl_vars['plans']->value){?>
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['bind_to_plans'];?>
:</strong></td>
			<td>
				<?php  $_smarty_tpl->tpl_vars['plan'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plan']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['plans']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['plan']->key => $_smarty_tpl->tpl_vars['plan']->value){
$_smarty_tpl->tpl_vars['plan']->_loop = true;
?>
					<input name="plans[]" type="checkbox" id="plan_<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['field_plans']->value)&&in_array($_smarty_tpl->tpl_vars['plan']->value['id'],$_smarty_tpl->tpl_vars['field_plans']->value)){?>checked="checked"<?php }elseif(isset($_POST['plans'])&&in_array($_smarty_tpl->tpl_vars['plan']->value['id'],$_POST['plans'])){?>checked="checked"<?php }?>/>
					<?php if (isset($_smarty_tpl->tpl_vars["lang"])) {$_smarty_tpl->tpl_vars["lang"] = clone $_smarty_tpl->tpl_vars["lang"];
$_smarty_tpl->tpl_vars["lang"]->value = $_smarty_tpl->tpl_vars['plan']->value['lang']; $_smarty_tpl->tpl_vars["lang"]->nocache = null; $_smarty_tpl->tpl_vars["lang"]->scope = 0;
} else $_smarty_tpl->tpl_vars["lang"] = new Smarty_variable($_smarty_tpl->tpl_vars['plan']->value['lang'], null, 0);?>
					<label for="plan_<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['plan']->value['title'];?>
&nbsp;(<?php echo $_smarty_tpl->tpl_vars['langs']->value[$_smarty_tpl->tpl_vars['lang']->value];?>
)</label><br />
				<?php } ?>
			</td>
		</tr>
	<?php }?>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['bind_to_categories'];?>
:</strong></td>
		<td>
			<div id="tree"></div>
			<input type="checkbox" name="recursive" id="recursive" value="1" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['recursive'])&&$_smarty_tpl->tpl_vars['field']->value['recursive']=='1'){?>checked="checked"<?php }elseif(isset($_POST['recursive'])&&$_POST['recursive']=='1'){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['field']->value)&&empty($_POST)){?>checked="checked"<?php }?>/><label for="recursive">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['include_subcats'];?>
</label>
		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['field_type'];?>
:</strong></td>
		<td>
			<select name="field_type" id="type" <?php if ('edit'==$_GET['do']){?>disabled="disabled" <?php }?>>
				<option value=""><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['_select_'];?>
</option>
				<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['field_types']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value){
$_smarty_tpl->tpl_vars['type']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['type']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"<?php if ((isset($_smarty_tpl->tpl_vars['field']->value['type'])&&$_smarty_tpl->tpl_vars['field']->value['type']==$_smarty_tpl->tpl_vars['key']->value)||(isset($_POST['field_type'])&&$_POST['field_type']==$_smarty_tpl->tpl_vars['key']->value)){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['type']->value;?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>

	</table>

	<div id="text" style="display: none;">
		<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr>
			<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['field_length'];?>
:</strong></td>
			<td><input type="text" name="length" size="24" class="common numeric" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['length'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['length'];?>
<?php }elseif(isset($_POST['length'])){?><?php echo $_POST['length'];?>
<?php }?>"> <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['digit_only'];?>
</td>
		</tr>
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['field_default'];?>
:</strong></td>
			<td><input type="text" name="text_default" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['default'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['default'];?>
<?php }elseif(isset($_POST['text_default'])){?><?php echo $_POST['text_default'];?>
<?php }?>"></td>
		</tr>
		</table>
	</div>

	
	
	<div id="textarea" style="display: none;">
		<?php if (isset($_smarty_tpl->tpl_vars['field']->value['length'])&&$_smarty_tpl->tpl_vars['field']->value['type']=='textarea'&&!empty($_smarty_tpl->tpl_vars['field']->value['length'])){?>
			<?php if (isset($_smarty_tpl->tpl_vars["lengths"])) {$_smarty_tpl->tpl_vars["lengths"] = clone $_smarty_tpl->tpl_vars["lengths"];
$_smarty_tpl->tpl_vars["lengths"]->value = explode(',',$_smarty_tpl->tpl_vars['field']->value['length']); $_smarty_tpl->tpl_vars["lengths"]->nocache = null; $_smarty_tpl->tpl_vars["lengths"]->scope = 0;
} else $_smarty_tpl->tpl_vars["lengths"] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['field']->value['length']), null, 0);?> 
			<?php if (isset($_smarty_tpl->tpl_vars["min_length"])) {$_smarty_tpl->tpl_vars["min_length"] = clone $_smarty_tpl->tpl_vars["min_length"];
$_smarty_tpl->tpl_vars["min_length"]->value = $_smarty_tpl->tpl_vars['lengths']->value[0]; $_smarty_tpl->tpl_vars["min_length"]->nocache = null; $_smarty_tpl->tpl_vars["min_length"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min_length"] = new Smarty_variable($_smarty_tpl->tpl_vars['lengths']->value[0], null, 0);?>
			<?php if (isset($_smarty_tpl->tpl_vars["max_length"])) {$_smarty_tpl->tpl_vars["max_length"] = clone $_smarty_tpl->tpl_vars["max_length"];
$_smarty_tpl->tpl_vars["max_length"]->value = $_smarty_tpl->tpl_vars['lengths']->value[1]; $_smarty_tpl->tpl_vars["max_length"]->nocache = null; $_smarty_tpl->tpl_vars["max_length"]->scope = 0;
} else $_smarty_tpl->tpl_vars["max_length"] = new Smarty_variable($_smarty_tpl->tpl_vars['lengths']->value[1], null, 0);?>
		<?php }elseif(isset($_POST)){?>
			<?php if (isset($_POST['min_length'])){?>
				<?php if (isset($_smarty_tpl->tpl_vars["min_length"])) {$_smarty_tpl->tpl_vars["min_length"] = clone $_smarty_tpl->tpl_vars["min_length"];
$_smarty_tpl->tpl_vars["min_length"]->value = $_POST['min_length']; $_smarty_tpl->tpl_vars["min_length"]->nocache = null; $_smarty_tpl->tpl_vars["min_length"]->scope = 0;
} else $_smarty_tpl->tpl_vars["min_length"] = new Smarty_variable($_POST['min_length'], null, 0);?>
			<?php }?>
			<?php if (isset($_POST['max_length'])){?>
				<?php if (isset($_smarty_tpl->tpl_vars["max_length"])) {$_smarty_tpl->tpl_vars["max_length"] = clone $_smarty_tpl->tpl_vars["max_length"];
$_smarty_tpl->tpl_vars["max_length"]->value = $_POST['max_length']; $_smarty_tpl->tpl_vars["max_length"]->nocache = null; $_smarty_tpl->tpl_vars["max_length"]->scope = 0;
} else $_smarty_tpl->tpl_vars["max_length"] = new Smarty_variable($_POST['max_length'], null, 0);?>
			<?php }?>
		<?php }?>
		<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['wysiwyg_editor'];?>
:</strong></td>
			<td>
				<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['field']->value['editor'])===null||$tmp==='' ? $_POST['editor'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"editor"),$_smarty_tpl);?>

			</td>
		</tr>
		<tr>
			<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['field_min_length'];?>
:</strong></td>
			<td><input type="text" class="common numeric" name="min_length" size="24" value="<?php if (isset($_smarty_tpl->tpl_vars['min_length']->value)){?><?php echo $_smarty_tpl->tpl_vars['min_length']->value;?>
<?php }?>"></td>
		</tr>
		<tr>
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['field_max_length'];?>
:</strong></td>
			<td><input type="text" class="common numeric" name="max_length" size="24" value="<?php if (isset($_smarty_tpl->tpl_vars['max_length']->value)){?><?php echo $_smarty_tpl->tpl_vars['max_length']->value;?>
<?php }?>"></td>
		</tr>
		</table>
	</div>

	<div id="storage" style="display: none;">
		<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr>
			<td>
				<?php if (!is_writeable(((@constant('IA_HOME')).(@constant('IA_DS'))).('uploads'))){?>
					<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['upload_writable_permission'];?>
</i></div>
				<?php }else{ ?>
					<label for="file_types"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['file_types'];?>
</label><br />
					<textarea rows="3" cols="20" class="common" id="file_types" name="file_types" style="width:500px;height:40px"><?php if (isset($_smarty_tpl->tpl_vars['field']->value['file_types'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['file_types'];?>
<?php }elseif(isset($_POST['file_types'])){?><?php echo $_POST['file_types'];?>
<?php }elseif(!isset($_smarty_tpl->tpl_vars['field']->value)&&empty($_POST)){?>doc,docx,xls,xlsx,zip,pdf<?php }?></textarea>
				<?php }?>
			</td>
		</tr>
		</table>
	</div>
	
	<div id="image" style="display: none;">
		<?php if (!is_writeable(((@constant('IA_HOME')).(@constant('IA_DS'))).('uploads'))){?>
			<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['upload_writable_permission'];?>
</i></div>							
		<?php }else{ ?>
			<table cellspacing="0" cellpadding="0" width="100%" class="striped">
			<tr>
				<td class="tip-header"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['instead_thumbnail'];?>
:</strong></td>
				<?php if (isset($_smarty_tpl->tpl_vars['field']->value['instead_thumbnail'])){?>
					<?php if (isset($_smarty_tpl->tpl_vars["instead_thumbnail_value"])) {$_smarty_tpl->tpl_vars["instead_thumbnail_value"] = clone $_smarty_tpl->tpl_vars["instead_thumbnail_value"];
$_smarty_tpl->tpl_vars["instead_thumbnail_value"]->value = $_smarty_tpl->tpl_vars['field']->value['instead_thumbnail']; $_smarty_tpl->tpl_vars["instead_thumbnail_value"]->nocache = null; $_smarty_tpl->tpl_vars["instead_thumbnail_value"]->scope = 0;
} else $_smarty_tpl->tpl_vars["instead_thumbnail_value"] = new Smarty_variable($_smarty_tpl->tpl_vars['field']->value['instead_thumbnail'], null, 0);?>
				<?php }elseif(isset($_POST['instead_thumbnail'])){?>
					<?php if (isset($_smarty_tpl->tpl_vars["instead_thumbnail_value"])) {$_smarty_tpl->tpl_vars["instead_thumbnail_value"] = clone $_smarty_tpl->tpl_vars["instead_thumbnail_value"];
$_smarty_tpl->tpl_vars["instead_thumbnail_value"]->value = $_POST['instead_thumbnail']; $_smarty_tpl->tpl_vars["instead_thumbnail_value"]->nocache = null; $_smarty_tpl->tpl_vars["instead_thumbnail_value"]->scope = 0;
} else $_smarty_tpl->tpl_vars["instead_thumbnail_value"] = new Smarty_variable($_POST['instead_thumbnail'], null, 0);?>
				<?php }?>
				<td>
					<input type="radio" name="instead_thumbnail" id="instead_thumbnail_yes" value="1" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['instead_thumbnail'])&&$_smarty_tpl->tpl_vars['field']->value['instead_thumbnail']=='1'){?>checked="checked"<?php }elseif(isset($_POST['instead_thumbnail'])&&$_POST['instead_thumbnail']=='1'){?>checked="checked"<?php }?> /><label for="instead_thumbnail_yes"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['yes'];?>
</label>
					<input type="radio" name="instead_thumbnail" id="instead_thumbnail_no" value="0" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['instead_thumbnail'])&&$_smarty_tpl->tpl_vars['field']->value['instead_thumbnail']=='0'){?>checked="checked"<?php }elseif(isset($_POST['instead_thumbnail'])&&$_POST['instead_thumbnail']=='0'){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['field']->value)&&!$_POST){?>checked="checked"<?php }?> /><label for="instead_thumbnail_no"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['no'];?>
</label>
				</td>
			</tr>
			<tr>
				<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['file_prefix'];?>
:</strong></td>
				<td><input type="text" name="file_prefix" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['file_prefix'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['file_prefix'];?>
<?php }elseif(isset($_POST['file_prefix'])){?><?php echo $_POST['file_prefix'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo smarty_function_lang(array('key'=>"image_title_length"),$_smarty_tpl);?>
:</strong></td>
				<td><input type="text" name="image_title_length" size="24" class="common numeric" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['image_title_length'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['image_title_length'];?>
<?php }elseif(isset($_POST['image_title_length'])){?><?php echo $_POST['image_title_length'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_width'];?>
:</strong></td>
				<td><input type="text" name="image_width" size="24" class="common numeric" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['image_width'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['image_width'];?>
<?php }elseif(isset($_POST['image_width'])){?><?php echo $_POST['image_width'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_height'];?>
:</strong></td>
				<td><input type="text" name="image_height" size="24" class="common numeric" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['image_height'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['image_height'];?>
<?php }elseif(isset($_POST['image_height'])){?><?php echo $_POST['image_height'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['thumb_width'];?>
:</strong></td>
				<td><input type="text" name="thumb_width" size="24" class="common numeric" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['thumb_width'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['thumb_width'];?>
<?php }elseif(isset($_POST['thumb_width'])){?><?php echo $_POST['thumb_width'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['thumb_height'];?>
:</strong></td>
				<td><input type="text" name="thumb_height" size="24" class="common numeric" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['thumb_height'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['thumb_height'];?>
<?php }elseif(isset($_POST['thumb_height'])){?><?php echo $_POST['thumb_height'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['resize_mode'];?>
:</strong></td>
				<td>
					<select name="resize_mode">
						<option value="crop" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['resize_mode'])&&$_smarty_tpl->tpl_vars['field']->value['resize_mode']=='crop'){?>selected="selected"<?php }elseif(isset($_POST['resize_mode'])&&$_POST['resize_mode']=='crop'){?>selected="selected"<?php }?>> <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['crop'];?>
 </option>
						<option value="fit" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['resize_mode'])&&$_smarty_tpl->tpl_vars['field']->value['resize_mode']=='fit'){?>selected="selected"<?php }elseif(isset($_POST['resize_mode'])&&$_POST['resize_mode']=='fit'){?>selected="selected"<?php }?>> <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['fit'];?>
 </option>
					</select>
					<span id="resize_mode_tip_crop" class="option_tip" style="display: none;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['crop_tip'];?>
</span>
					<span id="resize_mode_tip_fit" class="option_tip" style="display: none;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['fit_tip'];?>
</span>
				</td>
			</tr>

			</table>
		<?php }?>
	</div>

	<div id="multiple" style="display: none;">
		<div id="textany_meta_container" style="display: none;">
			<table cellspacing="0" width="100%" class="striped">
				<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
				<tr>
					<td width="150">
						<label for="anyMeta<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"><strong><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
:&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title_for_any'];?>
:</strong></label>
					</td>
					<td>
						<input type="text" name="any_meta[<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
]" id="anyMeta<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['any_meta'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['any_meta'][$_smarty_tpl->tpl_vars['code']->value];?>
<?php }elseif(isset($_POST['any_meta'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo $_POST['any_meta'][$_smarty_tpl->tpl_vars['code']->value];?>
<?php }?>" disabled="disabled">
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>
		
		<table cellspacing="0" width="100%" class="striped" style="display: none;" id="check_all_option">
			<tr>
				<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['check_all'];?>
</strong></td>
				<td>
					<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['field']->value['check_all'])===null||$tmp==='' ? $_POST['check_all'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"check_all"),$_smarty_tpl);?>

				</td>
			</tr>
		</table>
	
		<table cellspacing="0" width="100%" class="striped">
		<tr>
			<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['show_in_search_as'];?>
:</strong></td>
			<td>
				<select name="show_as" id="showAs" disabled="disabled">
					<option value="checkbox" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['show_as'])&&$_smarty_tpl->tpl_vars['field']->value['show_as']=='checkbox'){?>selected="selected"<?php }elseif(isset($_POST['show_as'])&&$_POST['show_as']=='checkbox'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['checkboxes'];?>
</option>
					<option value="radio" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['show_as'])&&$_smarty_tpl->tpl_vars['field']->value['show_as']=='radio'){?>selected="selected"<?php }elseif(isset($_POST['show_as'])&&$_POST['show_as']=='radio'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['radios'];?>
</option>
					<option value="combo" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['show_as'])&&$_smarty_tpl->tpl_vars['field']->value['show_as']=='combo'){?>selected="selected"<?php }elseif(isset($_POST['show_as'])&&$_POST['show_as']=='combo'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['dropdown'];?>
</option>
				</select>
			</td>
		</tr>
		</table>
	
		<table cellspacing="0" width="100%" class="striped">
		<tr>
			<td colspan="2" class="td_items">
				<?php if (isset($_smarty_tpl->tpl_vars['field_values']->value)&&!empty($_smarty_tpl->tpl_vars['field_values']->value)){?>
					<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['field_values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
						<div style="margin: 10px 0 10px 0;">
							<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
								<br />
								
								<a href="#" style="visibility: hidden;" class="arrow_up"><img src="templates/default/img/arrow_up.png" alt="" title="" style="float: left; margin-right: 2px;"></a>
								<a href="#" style="visibility: hidden;" class="arrow_down"><img src="templates/default/img/arrow_down.png" alt="" title="" style="float: left; margin-right: 2px;"></a>
								
								<input type="text" class="common" name="lang_values[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][]" size="25" value="<?php echo $_smarty_tpl->tpl_vars['value']->value[$_smarty_tpl->tpl_vars['key']->value];?>
">
								<span style="margin-left: 30px;"><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['item_value'];?>
</span>
								
								<?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['config']->value['lang']){?>
									&nbsp;|&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['field_default'];?>
&nbsp;(&nbsp;
									<a href="set_default" class="actions_setDefault"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['set_default'];?>
</a>&nbsp;|&nbsp;
									<a href="remove_default" class="actions_removeDefault"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
</a>&nbsp;)&nbsp;|&nbsp;
									<a href="remove_item" class="actions_removeItem"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
</a>
								<?php }?>
							<?php } ?>
						</div>
					<?php } ?>
				<?php }?>
				<a href="add_item" id="add_item" onclick="return false;"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add_item_value'];?>
</strong></a>
			</td>
		</tr>
		<tr>
			<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['field_default'];?>
:</strong></td>
			<td><input type="text" readonly="readonly" name="multiple_default" id="multiple_default" size="45" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['default'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['default'];?>
<?php }elseif(isset($_POST['multiple_default'])){?><?php echo $_POST['multiple_default'];?>
<?php }?>"></td>
		</tr>
		</table>
	</div>

	<div id="pictures" style="display: none;">
		<?php if (!is_writeable(@constant('IA_UPLOADS'))){?>
			<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['upload_writable_permission'];?>
</i></div>							
		<?php }else{ ?>
			<table cellspacing="0" cellpadding="0" width="100%" class="striped">
			<tr>
				<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['max_num_images'];?>
:</strong></td>
				<td><input type="text" name="pic_max_images" size="24" class="numeric common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['length'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['length'];?>
<?php }elseif(isset($_POST['pic_max_images'])){?><?php echo $_POST['pic_max_images'];?>
<?php }elseif(!isset($_smarty_tpl->tpl_vars['field']->value)&&empty($_POST)){?>5<?php }?>"></td>
			</tr>
			<tr>
				<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['file_prefix'];?>
:</strong></td>
				<td><input type="text" name="pic_file_prefix" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['file_prefix'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['file_prefix'];?>
<?php }elseif(isset($_POST['pic_file_prefix'])){?><?php echo $_POST['pic_file_prefix'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo smarty_function_lang(array('key'=>"image_title_length"),$_smarty_tpl);?>
:</strong></td>
				<td><input type="text" name="pic_title_length" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['image_title_length'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['image_title_length'];?>
<?php }elseif(isset($_POST['pic_title_length'])){?><?php echo $_POST['pic_title_length'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_width'];?>
:</strong></td>
				<td><input type="text" name="pic_image_width" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['image_width'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['image_width'];?>
<?php }elseif(isset($_POST['pic_image_width'])){?><?php echo $_POST['pic_image_width'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_height'];?>
:</strong></td>
				<td><input type="text" name="pic_image_height" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['image_height'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['image_height'];?>
<?php }elseif(isset($_POST['pic_image_height'])){?><?php echo $_POST['pic_image_height'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['thumb_width'];?>
:</strong></td>
				<td><input type="text" name="pic_thumb_width" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['thumb_width'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['thumb_width'];?>
<?php }elseif(isset($_POST['pic_thumb_width'])){?><?php echo $_POST['pic_thumb_width'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['thumb_height'];?>
:</strong></td>
				<td><input type="text" name="pic_thumb_height" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['thumb_height'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['thumb_height'];?>
<?php }elseif(isset($_POST['pic_thumb_height'])){?><?php echo $_POST['pic_thumb_height'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['resize_mode'];?>
:</strong></td>
				<td>
					<select name="pic_resize_mode">
						<option value="crop" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['resize_mode'])&&$_smarty_tpl->tpl_vars['field']->value['resize_mode']=='crop'){?>selected="selected"<?php }elseif(isset($_POST['pic_resize_mode'])&&$_POST['pic_resize_mode']=='crop'){?>selected="selected"<?php }?>> <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['crop'];?>
 </option>
						<option value="fit" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['resize_mode'])&&$_smarty_tpl->tpl_vars['field']->value['resize_mode']=='fit'){?>selected="selected"<?php }elseif(isset($_POST['pic_resize_mode'])&&$_POST['pic_resize_mode']=='fit'){?>selected="selected"<?php }?>> <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['fit'];?>
 </option>
					</select>
					<span id="pic_resize_mode_tip_crop" class="option_tip" style="display: none;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['crop_tip'];?>
</span>
					<span id="pic_resize_mode_tip_fit" class="option_tip" style="display: none;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['fit_tip'];?>
</span>
				</td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['pic_gallery_type'];?>
:</strong></td>
				<td>
					<select name="pic_type">
						<option value="gallery" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['pic_type'])&&'gallery'==$_smarty_tpl->tpl_vars['field']->value['pic_type']){?>selected="selected"<?php }elseif(isset($_POST['pic_type'])&&'gallery'==$_POST['pic_type']){?>selected="selected"<?php }?>> <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['pic_gallery'];?>
 </option>
						<option value="separate" <?php if (isset($_smarty_tpl->tpl_vars['field']->value['pic_type'])&&'separate'==$_smarty_tpl->tpl_vars['field']->value['pic_type']){?>selected="selected"<?php }elseif(isset($_POST['pic_type'])&&'separate'==$_POST['pic_type']){?>selected="selected"<?php }?>> <?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['pic_separate'];?>
 </option>
					</select>
				</td>
			</tr>

			</table>
		<?php }?>
	</div>

	<?php echo smarty_function_ia_hooker(array('name'=>"tplAdminFieldTypesForm"),$_smarty_tpl);?>


	<table cellspacing="0" width="100%" class="striped">
	<tr class="all">
		<td colspan="2">
			<input type="submit" name="save" class="common" value="<?php if ($_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
<?php }?>">
		</td>
	</tr>
	</table>
	<input type="hidden" name="old_field_type" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['type'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['type'];?>
<?php }?>">
	<input type="hidden" name="old_name" value="<?php if (isset($_smarty_tpl->tpl_vars['field']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
<?php }?>">
	<input type="hidden" name="do" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>">
	<input type="hidden" name="categories" id="categories" value="<?php if (isset($_smarty_tpl->tpl_vars['field_categories']->value)){?><?php echo $_smarty_tpl->tpl_vars['field_categories']->value;?>
<?php }elseif(isset($_POST['categories'])){?><?php echo $_POST['categories'];?>
<?php }?>">
	<input type="hidden" name="categories_parents" id="categories_parents" value="<?php if (isset($_smarty_tpl->tpl_vars['field_categories_parents']->value)){?><?php echo $_smarty_tpl->tpl_vars['field_categories_parents']->value;?>
<?php }elseif(isset($_POST['categories_parents'])){?><?php echo $_POST['categories_parents'];?>
<?php }?>">
	</form>

	<div style="margin: 10px 0 10px 0; display: none;" id="value_item">
		<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
			<br />
			
			<a href="#" style="visibility: hidden;" class="arrow_up"><img src="templates/default/img/arrow_up.png" alt="" title="" style="float: left; margin-right: 2px;"></a>
			<a href="#" style="visibility: hidden;" class="arrow_down"><img src="templates/default/img/arrow_down.png" alt="" title="" style="float: left; margin-right: 2px;"></a>

			<input type="text" class="common" name="lang_values[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
][]" size="25" value="">
			<span style="margin-left: 30px;"><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['item_value'];?>
</span>
			
			<?php if ($_smarty_tpl->tpl_vars['key']->value==$_smarty_tpl->tpl_vars['config']->value['lang']){?>
				&nbsp;|&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['field_default'];?>
&nbsp;(&nbsp;
				<a href="set_default" class="actions_setDefault"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['set_default'];?>
</a>&nbsp;|&nbsp;
				<a href="remove_default" class="actions_removeDefault"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
</a>&nbsp;)&nbsp;|&nbsp;
				<a href="remove_item" class="actions_removeItem" onclick="return false;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
</a>
			<?php }?>
		<?php } ?>
	</div>

	<div style="margin: 5px 0; display: none" id="value_two_items">
		<span style="font-weight:bold; margin-right: 68px;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['item_value'];?>
:</span>
		<input class="common numeric" type="text" name="_values[]" size="10">
		<span style="font-weight:bold; margin-left: 38px;"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
:</span>
		<input type="text" class="common" name="_titles[]" size="10">
		<a href="remove_item" class="actions_removeItem"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
</a>
	</div>

	<?php echo $_smarty_tpl->getSubTemplate ("box-footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('class'=>"box"), 0);?>

<?php }else{ ?>
	<div id="box_fields" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/jquery/plugins/jquery.numeric, js/admin/listing-fields"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>