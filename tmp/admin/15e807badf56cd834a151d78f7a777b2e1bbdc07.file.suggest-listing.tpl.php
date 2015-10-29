<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 06:37:57
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/suggest-listing.tpl" */ ?>
<?php /*%%SmartyHeaderCode:189304031355095585f196a1-31672447%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '15e807badf56cd834a151d78f7a777b2e1bbdc07' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/suggest-listing.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '189304031355095585f196a1-31672447',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'esynI18N' => 0,
    'category' => 0,
    'parent' => 0,
    'listing' => 0,
    'crossed_html' => 0,
    'fields' => 0,
    'group' => 0,
    'group_key' => 0,
    'g_fields' => 0,
    'value' => 0,
    'lang_key' => 0,
    'value_name' => 0,
    'values' => 0,
    'item' => 0,
    'temp' => 0,
    'key' => 0,
    'checkboxes' => 0,
    'index' => 0,
    'default' => 0,
    'file_path' => 0,
    'images' => 0,
    'image' => 0,
    'images_titles' => 0,
    'config' => 0,
    'plans' => 0,
    'plan' => 0,
    'deep_links' => 0,
    'deep_title' => 0,
    'deep_url' => 0,
    'deep_id' => 0,
    'account' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550955865af026_60330287',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550955865af026_60330287')) {function content_550955865af026_60330287($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>(@constant('IA_URL')).("js/jquery/plugins/lightbox/css/jquery.lightbox")), 0);?>


<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>


<form name="suggest_listing" action="controller.php?file=suggest-listing<?php if (isset($_GET['do'])){?>&amp;do=<?php echo $_GET['do'];?>
<?php }?><?php if (isset($_GET['status'])){?>&amp;status=<?php echo $_GET['status'];?>
<?php }?><?php if (isset($_GET['id'])){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post" enctype="multipart/form-data">
<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

<table cellspacing="0" cellpadding="0" width="100%" class="striped">
<tr>
	<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listing_category'];?>
:</strong></td>
	<td>
		<span id="parent_category_title_container">
			<strong><?php if (isset($_smarty_tpl->tpl_vars['category']->value['title'])){?><a href="controller.php?file=browse&amp;id=<?php echo $_smarty_tpl->tpl_vars['parent']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
</a><?php }else{ ?>ROOT<?php }?></strong>
		</span>&nbsp;|&nbsp;
		<a href="#" id="change_category"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['change'];?>
...</a>&nbsp;|&nbsp;
		<a href="#" id="add_crossed"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add_as_crossed_to_other_categories'];?>
</a>

		<input type="hidden" id="category_id" name="category_id" value="<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
">
		<input type="hidden" id="category_parents" name="category_parents" value="<?php if (isset($_smarty_tpl->tpl_vars['category']->value['parents'])){?><?php echo $_smarty_tpl->tpl_vars['category']->value['parents'];?>
<?php }?>">
		<input type="hidden" name="multi_crossed" id="multi_crossed" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['crossed'])&&!empty($_smarty_tpl->tpl_vars['listing']->value['crossed'])){?><?php echo implode('|',$_smarty_tpl->tpl_vars['listing']->value['crossed']);?>
<?php }?>">
		<input type="hidden" name="crossed_expand_path" id="crossed_expand_path" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['crossed_expand_path'])&&!empty($_smarty_tpl->tpl_vars['listing']->value['crossed_expand_path'])){?><?php echo implode(',',$_smarty_tpl->tpl_vars['listing']->value['crossed_expand_path']);?>
<?php }?>">

		<div id="crossed"><?php if (isset($_smarty_tpl->tpl_vars['crossed_html']->value)&&!empty($_smarty_tpl->tpl_vars['crossed_html']->value)){?><?php echo $_smarty_tpl->tpl_vars['crossed_html']->value;?>
<?php }?></div>
	</td>
</tr>
</table>

<?php echo smarty_function_ia_hooker(array('name'=>"tplAdminSuggestListingForm"),$_smarty_tpl);?>


<?php if (isset($_smarty_tpl->tpl_vars['fields']->value)){?>
<?php  $_smarty_tpl->tpl_vars['g_fields'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['g_fields']->_loop = false;
 $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['g_fields']->key => $_smarty_tpl->tpl_vars['g_fields']->value){
$_smarty_tpl->tpl_vars['g_fields']->_loop = true;
 $_smarty_tpl->tpl_vars['group']->value = $_smarty_tpl->tpl_vars['g_fields']->key;
?>

	<?php if ($_smarty_tpl->tpl_vars['group']->value!='non_group'){?>
		<?php if (isset($_smarty_tpl->tpl_vars["group_key"])) {$_smarty_tpl->tpl_vars["group_key"] = clone $_smarty_tpl->tpl_vars["group_key"];
$_smarty_tpl->tpl_vars["group_key"]->value = ("field_group_title_").($_smarty_tpl->tpl_vars['group']->value); $_smarty_tpl->tpl_vars["group_key"]->nocache = null; $_smarty_tpl->tpl_vars["group_key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["group_key"] = new Smarty_variable(("field_group_title_").($_smarty_tpl->tpl_vars['group']->value), null, 0);?>
		<fieldset>
		<legend><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['group_key']->value];?>
</strong></legend>
	<?php }?>
	
	<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['g_fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
		<tr>
			<?php if (isset($_smarty_tpl->tpl_vars["lang_key"])) {$_smarty_tpl->tpl_vars["lang_key"] = clone $_smarty_tpl->tpl_vars["lang_key"];
$_smarty_tpl->tpl_vars["lang_key"]->value = ("field_").($_smarty_tpl->tpl_vars['value']->value['name']); $_smarty_tpl->tpl_vars["lang_key"]->nocache = null; $_smarty_tpl->tpl_vars["lang_key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["lang_key"] = new Smarty_variable(("field_").($_smarty_tpl->tpl_vars['value']->value['name']), null, 0);?>
			<?php if (isset($_smarty_tpl->tpl_vars["value_name"])) {$_smarty_tpl->tpl_vars["value_name"] = clone $_smarty_tpl->tpl_vars["value_name"];
$_smarty_tpl->tpl_vars["value_name"]->value = $_smarty_tpl->tpl_vars['value']->value['name']; $_smarty_tpl->tpl_vars["value_name"]->nocache = null; $_smarty_tpl->tpl_vars["value_name"]->scope = 0;
} else $_smarty_tpl->tpl_vars["value_name"] = new Smarty_variable($_smarty_tpl->tpl_vars['value']->value['name'], null, 0);?>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['lang_key']->value];?>
:</strong></td>
			<td>
			<?php if ($_smarty_tpl->tpl_vars['value']->value['type']=='text'||$_smarty_tpl->tpl_vars['value']->value['type']=='number'){?>
				<input <?php if ($_smarty_tpl->tpl_vars['value']->value['length']!=''){?>maxlength="<?php echo $_smarty_tpl->tpl_vars['value']->value['length'];?>
"<?php }?> type="text" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" id="f_<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST[$_smarty_tpl->tpl_vars['value_name']->value])){?><?php echo $_POST[$_smarty_tpl->tpl_vars['value_name']->value];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['value']->value['default'];?>
<?php }?>" class="common<?php if ($_smarty_tpl->tpl_vars['value']->value['type']=='number'){?> numeric<?php }?>" size="45">

				<?php if ('title'==$_smarty_tpl->tpl_vars['value_name']->value){?>
					</td></tr>
					<tr>
						<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['path'];?>
:</strong></td>

						<td>
							<input type="text" name="title_alias" size="45" maxlength="150" class="common" style="float: left;" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['title_alias'])&&isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['title_alias'];?>
<?php }elseif(isset($_POST['title_alias'])){?><?php echo htmlspecialchars($_POST['title_alias'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">&nbsp;
							<div style="float: left; display: none; margin-left: 3px; padding: 4px;" id="listing_url_box"><span><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listing_url_will_be'];?>
:&nbsp;<span><span id="listing_url" style="padding: 3px; margin: 0; background: #FFE269;"></span></div>
						</td>
					</tr>
				<?php }?>

			<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=='textarea'){?>
				<?php if ($_smarty_tpl->tpl_vars['value']->value['editor']=='1'){?>
					<textarea class="ckeditor_textarea" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" id="f_<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" ><?php if (isset($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value];?>
<?php }elseif(isset($_POST[$_smarty_tpl->tpl_vars['value_name']->value])){?><?php echo $_POST[$_smarty_tpl->tpl_vars['value_name']->value];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['value']->value['default'];?>
<?php }?></textarea>
				<?php }else{ ?>
					<textarea name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" id="f_<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" cols="53" rows="8" class="common"><?php if (isset($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value];?>
<?php }elseif(isset($_POST[$_smarty_tpl->tpl_vars['value_name']->value])){?><?php echo $_POST[$_smarty_tpl->tpl_vars['value_name']->value];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['value']->value['default'];?>
<?php }?></textarea><br />
				<?php }?>
			<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=='combo'){?>
				<?php if (isset($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value])){?>
					<?php if (isset($_smarty_tpl->tpl_vars["temp"])) {$_smarty_tpl->tpl_vars["temp"] = clone $_smarty_tpl->tpl_vars["temp"];
$_smarty_tpl->tpl_vars["temp"]->value = $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value]; $_smarty_tpl->tpl_vars["temp"]->nocache = null; $_smarty_tpl->tpl_vars["temp"]->scope = 0;
} else $_smarty_tpl->tpl_vars["temp"] = new Smarty_variable($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value], null, 0);?>
				<?php }elseif(isset($_POST[$_smarty_tpl->tpl_vars['value_name']->value])){?>
					<?php if (isset($_smarty_tpl->tpl_vars["temp"])) {$_smarty_tpl->tpl_vars["temp"] = clone $_smarty_tpl->tpl_vars["temp"];
$_smarty_tpl->tpl_vars["temp"]->value = $_POST[$_smarty_tpl->tpl_vars['value_name']->value]; $_smarty_tpl->tpl_vars["temp"]->nocache = null; $_smarty_tpl->tpl_vars["temp"]->scope = 0;
} else $_smarty_tpl->tpl_vars["temp"] = new Smarty_variable($_POST[$_smarty_tpl->tpl_vars['value_name']->value], null, 0);?>
				<?php }else{ ?>
					<?php if (isset($_smarty_tpl->tpl_vars["temp"])) {$_smarty_tpl->tpl_vars["temp"] = clone $_smarty_tpl->tpl_vars["temp"];
$_smarty_tpl->tpl_vars["temp"]->value = $_smarty_tpl->tpl_vars['value']->value['default']; $_smarty_tpl->tpl_vars["temp"]->nocache = null; $_smarty_tpl->tpl_vars["temp"]->scope = 0;
} else $_smarty_tpl->tpl_vars["temp"] = new Smarty_variable($_smarty_tpl->tpl_vars['value']->value['default'], null, 0);?>
				<?php }?>

				<?php if (isset($_smarty_tpl->tpl_vars["values"])) {$_smarty_tpl->tpl_vars["values"] = clone $_smarty_tpl->tpl_vars["values"];
$_smarty_tpl->tpl_vars["values"]->value = explode(',',$_smarty_tpl->tpl_vars['value']->value['values']); $_smarty_tpl->tpl_vars["values"]->nocache = null; $_smarty_tpl->tpl_vars["values"]->scope = 0;
} else $_smarty_tpl->tpl_vars["values"] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['value']->value['values']), null, 0);?>

				<?php if ($_smarty_tpl->tpl_vars['values']->value){?>
					<select name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
">
					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
						<?php if (isset($_smarty_tpl->tpl_vars["key"])) {$_smarty_tpl->tpl_vars["key"] = clone $_smarty_tpl->tpl_vars["key"];
$_smarty_tpl->tpl_vars["key"]->value = ((("field_").($_smarty_tpl->tpl_vars['value']->value['name'])).('_')).($_smarty_tpl->tpl_vars['item']->value); $_smarty_tpl->tpl_vars["key"]->nocache = null; $_smarty_tpl->tpl_vars["key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["key"] = new Smarty_variable(((("field_").($_smarty_tpl->tpl_vars['value']->value['name'])).('_')).($_smarty_tpl->tpl_vars['item']->value), null, 0);?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['item']->value==$_smarty_tpl->tpl_vars['temp']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['key']->value];?>
</option>
					<?php } ?>
					</select>
				<?php }?>
			<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=='radio'){?>
				<?php if (isset($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value])){?>
					<?php if (isset($_smarty_tpl->tpl_vars["temp"])) {$_smarty_tpl->tpl_vars["temp"] = clone $_smarty_tpl->tpl_vars["temp"];
$_smarty_tpl->tpl_vars["temp"]->value = $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value]; $_smarty_tpl->tpl_vars["temp"]->nocache = null; $_smarty_tpl->tpl_vars["temp"]->scope = 0;
} else $_smarty_tpl->tpl_vars["temp"] = new Smarty_variable($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value], null, 0);?>
				<?php }elseif(isset($_POST[$_smarty_tpl->tpl_vars['value_name']->value])){?>
					<?php if (isset($_smarty_tpl->tpl_vars["temp"])) {$_smarty_tpl->tpl_vars["temp"] = clone $_smarty_tpl->tpl_vars["temp"];
$_smarty_tpl->tpl_vars["temp"]->value = $_POST[$_smarty_tpl->tpl_vars['value_name']->value]; $_smarty_tpl->tpl_vars["temp"]->nocache = null; $_smarty_tpl->tpl_vars["temp"]->scope = 0;
} else $_smarty_tpl->tpl_vars["temp"] = new Smarty_variable($_POST[$_smarty_tpl->tpl_vars['value_name']->value], null, 0);?>
				<?php }else{ ?>
					<?php if (isset($_smarty_tpl->tpl_vars["temp"])) {$_smarty_tpl->tpl_vars["temp"] = clone $_smarty_tpl->tpl_vars["temp"];
$_smarty_tpl->tpl_vars["temp"]->value = $_smarty_tpl->tpl_vars['value']->value['default']; $_smarty_tpl->tpl_vars["temp"]->nocache = null; $_smarty_tpl->tpl_vars["temp"]->scope = 0;
} else $_smarty_tpl->tpl_vars["temp"] = new Smarty_variable($_smarty_tpl->tpl_vars['value']->value['default'], null, 0);?>
				<?php }?>

				<?php if (isset($_smarty_tpl->tpl_vars["values"])) {$_smarty_tpl->tpl_vars["values"] = clone $_smarty_tpl->tpl_vars["values"];
$_smarty_tpl->tpl_vars["values"]->value = explode(',',$_smarty_tpl->tpl_vars['value']->value['values']); $_smarty_tpl->tpl_vars["values"]->nocache = null; $_smarty_tpl->tpl_vars["values"]->scope = 0;
} else $_smarty_tpl->tpl_vars["values"] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['value']->value['values']), null, 0);?>

				<?php if ($_smarty_tpl->tpl_vars['values']->value){?>
					<div class="fields-pages">
					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
						<?php if (isset($_smarty_tpl->tpl_vars["key"])) {$_smarty_tpl->tpl_vars["key"] = clone $_smarty_tpl->tpl_vars["key"];
$_smarty_tpl->tpl_vars["key"]->value = "field_".((string)$_smarty_tpl->tpl_vars['value']->value['name'])."_".((string)$_smarty_tpl->tpl_vars['item']->value); $_smarty_tpl->tpl_vars["key"]->nocache = null; $_smarty_tpl->tpl_vars["key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["key"] = new Smarty_variable("field_".((string)$_smarty_tpl->tpl_vars['value']->value['name'])."_".((string)$_smarty_tpl->tpl_vars['item']->value), null, 0);?>
						<label for="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
">
							<input type="radio" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['item']->value==$_smarty_tpl->tpl_vars['temp']->value){?>checked="checked"<?php }?> />
							<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['key']->value];?>

						</label>
					<?php } ?>
					</div>
				<?php }?>
			<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=='checkbox'){?>
				<?php if (isset($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value])){?>
					<?php if (isset($_smarty_tpl->tpl_vars["default"])) {$_smarty_tpl->tpl_vars["default"] = clone $_smarty_tpl->tpl_vars["default"];
$_smarty_tpl->tpl_vars["default"]->value = explode(',',$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value]); $_smarty_tpl->tpl_vars["default"]->nocache = null; $_smarty_tpl->tpl_vars["default"]->scope = 0;
} else $_smarty_tpl->tpl_vars["default"] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value]), null, 0);?>
				<?php }elseif(isset($_POST[$_smarty_tpl->tpl_vars['value_name']->value])){?>
					<?php if (isset($_smarty_tpl->tpl_vars["default"])) {$_smarty_tpl->tpl_vars["default"] = clone $_smarty_tpl->tpl_vars["default"];
$_smarty_tpl->tpl_vars["default"]->value = $_POST[$_smarty_tpl->tpl_vars['value_name']->value]; $_smarty_tpl->tpl_vars["default"]->nocache = null; $_smarty_tpl->tpl_vars["default"]->scope = 0;
} else $_smarty_tpl->tpl_vars["default"] = new Smarty_variable($_POST[$_smarty_tpl->tpl_vars['value_name']->value], null, 0);?>
				<?php }else{ ?>
					<?php if (isset($_smarty_tpl->tpl_vars["default"])) {$_smarty_tpl->tpl_vars["default"] = clone $_smarty_tpl->tpl_vars["default"];
$_smarty_tpl->tpl_vars["default"]->value = explode(',',$_smarty_tpl->tpl_vars['value']->value['default']); $_smarty_tpl->tpl_vars["default"]->nocache = null; $_smarty_tpl->tpl_vars["default"]->scope = 0;
} else $_smarty_tpl->tpl_vars["default"] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['value']->value['default']), null, 0);?>
				<?php }?>

				<?php if (isset($_smarty_tpl->tpl_vars["checkboxes"])) {$_smarty_tpl->tpl_vars["checkboxes"] = clone $_smarty_tpl->tpl_vars["checkboxes"];
$_smarty_tpl->tpl_vars["checkboxes"]->value = explode(',',$_smarty_tpl->tpl_vars['value']->value['values']); $_smarty_tpl->tpl_vars["checkboxes"]->nocache = null; $_smarty_tpl->tpl_vars["checkboxes"]->scope = 0;
} else $_smarty_tpl->tpl_vars["checkboxes"] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['value']->value['values']), null, 0);?>

				<?php if ($_smarty_tpl->tpl_vars['checkboxes']->value){?>
					<div class="fields-pages">
					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['checkboxes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
						<?php if (isset($_smarty_tpl->tpl_vars["key"])) {$_smarty_tpl->tpl_vars["key"] = clone $_smarty_tpl->tpl_vars["key"];
$_smarty_tpl->tpl_vars["key"]->value = ((("field_").($_smarty_tpl->tpl_vars['value']->value['name'])).('_')).($_smarty_tpl->tpl_vars['index']->value); $_smarty_tpl->tpl_vars["key"]->nocache = null; $_smarty_tpl->tpl_vars["key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["key"] = new Smarty_variable(((("field_").($_smarty_tpl->tpl_vars['value']->value['name'])).('_')).($_smarty_tpl->tpl_vars['index']->value), null, 0);?>
						<label for="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
">
							<input type="checkbox" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
[]" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['item']->value,$_smarty_tpl->tpl_vars['default']->value)){?>checked="checked"<?php }?> />
							<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['key']->value];?>

						</label>
					<?php } ?>
					</div>
				<?php }?>
			<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=='image'||$_smarty_tpl->tpl_vars['value']->value['type']=='storage'){?>
				<?php if (!is_writeable(@constant('IA_UPLOADS'))){?>
					<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['upload_writable_permission'];?>
</i></div>
				<?php }else{ ?>
					<?php if ($_smarty_tpl->tpl_vars['value']->value['type']=='image'){?>
						<div class="gallery">
							<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_title'];?>
:<br />
							<input class="common" type="text" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value[((string)$_smarty_tpl->tpl_vars['value_name']->value)."_title"])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value[((string)$_smarty_tpl->tpl_vars['value_name']->value)."_title"];?>
<?php }?>" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_title" style="width: 220px;"/>
						</div>
					<?php }?>

					<input type="file" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
" size="40" style="float:left;">
					<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>
						<?php if (isset($_smarty_tpl->tpl_vars["file_path"])) {$_smarty_tpl->tpl_vars["file_path"] = clone $_smarty_tpl->tpl_vars["file_path"];
$_smarty_tpl->tpl_vars["file_path"]->value = (@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value]); $_smarty_tpl->tpl_vars["file_path"]->nocache = null; $_smarty_tpl->tpl_vars["file_path"]->scope = 0;
} else $_smarty_tpl->tpl_vars["file_path"] = new Smarty_variable((@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value]), null, 0);?>

						<?php if (is_file($_smarty_tpl->tpl_vars['file_path']->value)&&file_exists($_smarty_tpl->tpl_vars['file_path']->value)){?>
							<div id="file_manage" style="float:left;padding-left:10px;">
								<a href="../uploads/<?php echo $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value];?>
" <?php if ($_smarty_tpl->tpl_vars['value']->value['type']=='image'){?>class="lightbox"<?php }else{ ?>target="_blank"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['view'];?>
</a>&nbsp;|&nbsp;
								<a href="<?php echo $_smarty_tpl->tpl_vars['value_name']->value;?>
/<?php echo $_GET['id'];?>
/<?php echo $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value];?>
/" class="clear"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['delete'];?>
</a>
							</div>
						<?php }?>
					<?php }?>
				<?php }?>
			<?php }elseif($_smarty_tpl->tpl_vars['value']->value['type']=='pictures'){?>
				<?php if (!is_writeable(@constant('IA_UPLOADS'))){?>
					<div style="width: 430px; padding: 3px; margin: 0; background: #FFE269 none repeat scroll 0 0;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['upload_writable_permission'];?>
</i></div>
				<?php }else{ ?>
					<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>
						<?php if (!empty($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value])){?>
							<?php if (isset($_smarty_tpl->tpl_vars["images"])) {$_smarty_tpl->tpl_vars["images"] = clone $_smarty_tpl->tpl_vars["images"];
$_smarty_tpl->tpl_vars["images"]->value = explode(',',$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value]); $_smarty_tpl->tpl_vars["images"]->nocache = null; $_smarty_tpl->tpl_vars["images"]->scope = 0;
} else $_smarty_tpl->tpl_vars["images"] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['value_name']->value]), null, 0);?>
							<?php if (isset($_smarty_tpl->tpl_vars["images_titles"])) {$_smarty_tpl->tpl_vars["images_titles"] = clone $_smarty_tpl->tpl_vars["images_titles"];
$_smarty_tpl->tpl_vars["images_titles"]->value = explode(',',$_smarty_tpl->tpl_vars['listing']->value[((string)$_smarty_tpl->tpl_vars['value_name']->value)."_titles"]); $_smarty_tpl->tpl_vars["images_titles"]->nocache = null; $_smarty_tpl->tpl_vars["images_titles"]->scope = 0;
} else $_smarty_tpl->tpl_vars["images_titles"] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['listing']->value[((string)$_smarty_tpl->tpl_vars['value_name']->value)."_titles"]), null, 0);?>

							<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['images']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['image']->key;
?>
								<div class="image_gallery_wrap">
									<?php if (isset($_smarty_tpl->tpl_vars["file_path"])) {$_smarty_tpl->tpl_vars["file_path"] = clone $_smarty_tpl->tpl_vars["file_path"];
$_smarty_tpl->tpl_vars["file_path"]->value = ((@constant('IA_UPLOADS')).('small_')).($_smarty_tpl->tpl_vars['image']->value); $_smarty_tpl->tpl_vars["file_path"]->nocache = null; $_smarty_tpl->tpl_vars["file_path"]->scope = 0;
} else $_smarty_tpl->tpl_vars["file_path"] = new Smarty_variable(((@constant('IA_UPLOADS')).('small_')).($_smarty_tpl->tpl_vars['image']->value), null, 0);?>

									<div class="gallery">
										<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_title'];?>
: <br />
										<input class="common" type="text" value="<?php echo $_smarty_tpl->tpl_vars['images_titles']->value[$_smarty_tpl->tpl_vars['key']->value];?>
" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_titles[]" style="width: 220px;"/>
									</div>

									<div class="image_box">
										<?php if (is_file($_smarty_tpl->tpl_vars['file_path']->value)&&file_exists($_smarty_tpl->tpl_vars['file_path']->value)){?>
											<a href="../uploads/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" target="_blank" class="lightbox"><img src="../uploads/small_<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
"></a>
											<div class="delete"><a href="<?php echo $_smarty_tpl->tpl_vars['value_name']->value;?>
/<?php echo $_GET['id'];?>
/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" class="clear"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['delete'];?>
</a></div>
										<?php }else{ ?>
											<a href="../uploads/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" target="_blank" class="lightbox"><img src="../uploads/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
"></a>
											<div class="delete"><a href="<?php echo $_smarty_tpl->tpl_vars['value_name']->value;?>
/<?php echo $_GET['id'];?>
/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" class="clear"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['delete'];?>
</a></div>
										<?php }?>
									</div>
								</div>
							<?php } ?>
						<?php }?>
					<?php }?>

					<div class="gallery" style="clear: both;">
						<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image'];?>
:<br />
						<input type="file" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
[]" size="40" style="float:left;">
						<input type="button" value="+" class="add_img common small">
						<input type="button" value="-" class="remove_img common small"><br />
						
						<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_title'];?>
: <br />
						<input class="common" type="text" value="" name="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_titles[]" style="width: 220px;"/>
					</div>
					<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['value']->value['length'];?>
" name="num_images" id="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
_num_img">
				<?php }?>
			<?php }?>

			<?php if ($_smarty_tpl->tpl_vars['config']->value['autometafetch']&&'url'==$_smarty_tpl->tpl_vars['value']->value['name']){?>
				<a href="#get_meta" id="fetch_meta"><?php echo smarty_function_lang(array('key'=>'fetch_meta'),$_smarty_tpl);?>
</a>
			<?php }?>
		</td>
		</tr>
	</table>
	<?php } ?>
	
	<?php if ('non_group'!=$_smarty_tpl->tpl_vars['group']->value){?>
		</fieldset>
	<?php }?>

	<div style="height: 15px;"></div>
<?php } ?>
	
	<fieldset>
	<legend><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['fields_for_admin'];?>
</strong></legend>
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['date'];?>
:</strong></td>
			<td>
				<input type="text" name="date" id="date" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['date'])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['date'];?>
<?php }elseif(isset($_POST['date'])){?><?php echo $_POST['date'];?>
<?php }?>">
			</td>
		</tr>
		
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['time'];?>
:</strong></td>
			<td>
				<input type="text" name="time" id="time" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['time'])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['time'];?>
<?php }elseif(isset($_POST['time'])){?><?php echo $_POST['time'];?>
<?php }?>">
			</td>
		</tr>
	<?php }?>

	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['featured'];?>
:</strong></td>
		<td><?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @$_smarty_tpl->tpl_vars['listing']->value['featured'])===null||$tmp==='' ? 0 : $tmp),'name'=>"featured"),$_smarty_tpl);?>
</td>
	</tr>

	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['partner'];?>
:</strong></td>
		<td><?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @$_smarty_tpl->tpl_vars['listing']->value['partner'])===null||$tmp==='' ? 0 : $tmp),'name'=>"partner"),$_smarty_tpl);?>
</td>
	</tr>

	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['sponsored'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @$_smarty_tpl->tpl_vars['listing']->value['sponsored'])===null||$tmp==='' ? 0 : $tmp),'name'=>"sponsored"),$_smarty_tpl);?>

		</td>
	</tr>

	<?php if (isset($_smarty_tpl->tpl_vars['plans']->value)&&!empty($_smarty_tpl->tpl_vars['plans']->value)){?>
		<tr>
			<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['plans'];?>
:</strong></td>
			<td>
				<?php  $_smarty_tpl->tpl_vars['plan'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plan']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['plans']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['plan']->key => $_smarty_tpl->tpl_vars['plan']->value){
$_smarty_tpl->tpl_vars['plan']->_loop = true;
?>
					<p class="field">
						<input type="radio" name="assign_plan" data-period="<?php echo $_smarty_tpl->tpl_vars['plan']->value['period'];?>
" data-expire_notif="<?php echo $_smarty_tpl->tpl_vars['plan']->value['expire_notif'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" id="plan_<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['plan_id'])&&$_smarty_tpl->tpl_vars['listing']->value['plan_id']==$_smarty_tpl->tpl_vars['plan']->value['id']){?>checked="checked"<?php }elseif(isset($_POST['assign_plan'])&&$_POST['assign_plan']==$_smarty_tpl->tpl_vars['plan']->value['id']){?>checked="checked"<?php }?> />
						<label for="plan_<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
"><strong><?php echo $_smarty_tpl->tpl_vars['plan']->value['title'];?>
&nbsp;-&nbsp;<?php echo $_smarty_tpl->tpl_vars['config']->value['currency_symbol'];?>
<?php echo $_smarty_tpl->tpl_vars['plan']->value['cost'];?>
</strong></label>

						<input type="hidden" id="option_ids_<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['plan']->value['visual_options'];?>
">
					</p>
				<?php } ?>
				<p class="field">
					<input type="radio" name="assign_plan" id="plan_reset" value="-1" <?php if (isset($_POST['assign_plan'])&&$_POST['assign_plan']=='-1'){?>checked="checked"<?php }?> />
					<label for="plan_reset"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['reset_plan'];?>
</strong></label>
				</p>
			</td>
		</tr>
	<?php }?>
	</table>

	<?php if (isset($_smarty_tpl->tpl_vars['plans']->value)&&!empty($_smarty_tpl->tpl_vars['plans']->value)){?>
		<?php if (isset($_smarty_tpl->tpl_vars['listing']->value)&&!empty($_smarty_tpl->tpl_vars['listing']->value['visual_options'])){?>
			<input type="hidden" id="old_plan_id" name="old_plan_id" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['plan_id'])&&!empty($_smarty_tpl->tpl_vars['listing']->value['plan_id'])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['plan_id'];?>
<?php }?>">
		<?php }?>

		<script type="text/html" id="optionsList">
			<table cellspacing="0" cellpadding="0" width="100%" class="striped">
				<tr>
					<td width="200"><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['visual_options'];?>
:</b></td>
					<td>
						<<?php ?>%
						var checked_plan_id = $("input[name='assign_plan']:checked").attr("id").replace("plan_", "");
						var current_plan_id = $('#old_plan_id').val();

						var checked_list = "<?php if (isset($_POST['visual_options'])){?><?php echo implode(',',$_POST['visual_options']);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['listing']->value['visual_options'])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['visual_options'];?>
<?php }?>";

						checked_list = checked_list.split(",");

						_.each(options, function(option) {
						%<?php ?>>

						<div class="option even">
							<div class="cfg">
								<input id="visual_option_<<?php ?>%= option.name %<?php ?>>" type="checkbox" name="visual_options[]" value="<<?php ?>%= option.name %<?php ?>>" style="margin:0 10px 0 0;"
								<<?php ?>%
									if (intelli.inArray(option.name, checked_list) && checked_plan_id == current_plan_id)
									{
										print('checked');
									}
								%<?php ?>>
								/>

								<span style="display:inline-block;width:200px;">
									<label for="visual_option_<<?php ?>%= option.name %<?php ?>>"><<?php ?>%= _t('listing_option_' + option.name) %<?php ?>></label>
								</span>

								<b><?php echo $_smarty_tpl->tpl_vars['config']->value['currency_symbol'];?>
</b><span id="price_<<?php ?>%= option.name %<?php ?>>"><<?php ?>%= option.price %<?php ?>></span>
							</div>
						</div>

						<<?php ?>%
						});
						%<?php ?>>
					</td>
				</tr>
			</table>
		</script>

		<div id="visual_options" class="visual_options" style="display: none;"></div>

		<?php if (isset($_smarty_tpl->tpl_vars['listing']->value)&&isset($_smarty_tpl->tpl_vars['listing']->value['deep_links'])){?>
			<?php if (isset($_smarty_tpl->tpl_vars["deep_links"])) {$_smarty_tpl->tpl_vars["deep_links"] = clone $_smarty_tpl->tpl_vars["deep_links"];
$_smarty_tpl->tpl_vars["deep_links"]->value = $_smarty_tpl->tpl_vars['listing']->value['deep_links']; $_smarty_tpl->tpl_vars["deep_links"]->nocache = null; $_smarty_tpl->tpl_vars["deep_links"]->scope = 0;
} else $_smarty_tpl->tpl_vars["deep_links"] = new Smarty_variable($_smarty_tpl->tpl_vars['listing']->value['deep_links'], null, 0);?>
		<?php }?>

		<?php  $_smarty_tpl->tpl_vars["plan"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["plan"]->_loop = false;
 $_smarty_tpl->tpl_vars["key"] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['plans']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["plan"]->key => $_smarty_tpl->tpl_vars["plan"]->value){
$_smarty_tpl->tpl_vars["plan"]->_loop = true;
 $_smarty_tpl->tpl_vars["key"]->value = $_smarty_tpl->tpl_vars["plan"]->key;
?>
			<?php if ($_smarty_tpl->tpl_vars['plan']->value['deep_links']>0){?>
				<div id="deep_links_<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" class="deep_links" style="display: none;">
				<table cellspacing="0" cellpadding="0" width="100%" class="striped">
				<tr>
					<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['deep_links'];?>
</strong></td>
					<td>
<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']["deep"])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]);
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['name'] = "deep";
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['plan']->value['deep_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']["deep"]['total']);
?>
	<?php if (isset($_smarty_tpl->tpl_vars["index"])) {$_smarty_tpl->tpl_vars["index"] = clone $_smarty_tpl->tpl_vars["index"];
$_smarty_tpl->tpl_vars["index"]->value = $_smarty_tpl->getVariable('smarty')->value['section']['deep']['index']; $_smarty_tpl->tpl_vars["index"]->nocache = null; $_smarty_tpl->tpl_vars["index"]->scope = 0;
} else $_smarty_tpl->tpl_vars["index"] = new Smarty_variable($_smarty_tpl->getVariable('smarty')->value['section']['deep']['index'], null, 0);?>

	<?php if (isset($_smarty_tpl->tpl_vars['deep_links']->value)&&isset($_smarty_tpl->tpl_vars['deep_links']->value[$_smarty_tpl->tpl_vars['index']->value])){?>
		<?php if (isset($_smarty_tpl->tpl_vars["deep_title"])) {$_smarty_tpl->tpl_vars["deep_title"] = clone $_smarty_tpl->tpl_vars["deep_title"];
$_smarty_tpl->tpl_vars["deep_title"]->value = $_smarty_tpl->tpl_vars['deep_links']->value[$_smarty_tpl->tpl_vars['index']->value]['title']; $_smarty_tpl->tpl_vars["deep_title"]->nocache = null; $_smarty_tpl->tpl_vars["deep_title"]->scope = 0;
} else $_smarty_tpl->tpl_vars["deep_title"] = new Smarty_variable($_smarty_tpl->tpl_vars['deep_links']->value[$_smarty_tpl->tpl_vars['index']->value]['title'], null, 0);?>
		<?php if (isset($_smarty_tpl->tpl_vars["deep_url"])) {$_smarty_tpl->tpl_vars["deep_url"] = clone $_smarty_tpl->tpl_vars["deep_url"];
$_smarty_tpl->tpl_vars["deep_url"]->value = $_smarty_tpl->tpl_vars['deep_links']->value[$_smarty_tpl->tpl_vars['index']->value]['url']; $_smarty_tpl->tpl_vars["deep_url"]->nocache = null; $_smarty_tpl->tpl_vars["deep_url"]->scope = 0;
} else $_smarty_tpl->tpl_vars["deep_url"] = new Smarty_variable($_smarty_tpl->tpl_vars['deep_links']->value[$_smarty_tpl->tpl_vars['index']->value]['url'], null, 0);?>
		<?php if (isset($_smarty_tpl->tpl_vars["deep_id"])) {$_smarty_tpl->tpl_vars["deep_id"] = clone $_smarty_tpl->tpl_vars["deep_id"];
$_smarty_tpl->tpl_vars["deep_id"]->value = $_smarty_tpl->tpl_vars['deep_links']->value[$_smarty_tpl->tpl_vars['index']->value]['id']; $_smarty_tpl->tpl_vars["deep_id"]->nocache = null; $_smarty_tpl->tpl_vars["deep_id"]->scope = 0;
} else $_smarty_tpl->tpl_vars["deep_id"] = new Smarty_variable($_smarty_tpl->tpl_vars['deep_links']->value[$_smarty_tpl->tpl_vars['index']->value]['id'], null, 0);?>
	<?php }else{ ?>
		<?php if (isset($_smarty_tpl->tpl_vars["deep_title"])) {$_smarty_tpl->tpl_vars["deep_title"] = clone $_smarty_tpl->tpl_vars["deep_title"];
$_smarty_tpl->tpl_vars["deep_title"]->value = ''; $_smarty_tpl->tpl_vars["deep_title"]->nocache = null; $_smarty_tpl->tpl_vars["deep_title"]->scope = 0;
} else $_smarty_tpl->tpl_vars["deep_title"] = new Smarty_variable('', null, 0);?>
		<?php if (isset($_smarty_tpl->tpl_vars["deep_url"])) {$_smarty_tpl->tpl_vars["deep_url"] = clone $_smarty_tpl->tpl_vars["deep_url"];
$_smarty_tpl->tpl_vars["deep_url"]->value = ''; $_smarty_tpl->tpl_vars["deep_url"]->nocache = null; $_smarty_tpl->tpl_vars["deep_url"]->scope = 0;
} else $_smarty_tpl->tpl_vars["deep_url"] = new Smarty_variable('', null, 0);?>
		<?php if (isset($_smarty_tpl->tpl_vars["deep_id"])) {$_smarty_tpl->tpl_vars["deep_id"] = clone $_smarty_tpl->tpl_vars["deep_id"];
$_smarty_tpl->tpl_vars["deep_id"]->value = ''; $_smarty_tpl->tpl_vars["deep_id"]->nocache = null; $_smarty_tpl->tpl_vars["deep_id"]->scope = 0;
} else $_smarty_tpl->tpl_vars["deep_id"] = new Smarty_variable('', null, 0);?>
	<?php }?>

	<div class="deep_link_box">
		<label><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
:&nbsp;<input size="25" type="text" class="common" name="deep_links[<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
][<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
][title]" value="<?php if (isset($_smarty_tpl->tpl_vars['deep_title']->value)){?><?php echo $_smarty_tpl->tpl_vars['deep_title']->value;?>
<?php }?>"></label>&nbsp;
		<label><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['url'];?>
:&nbsp;<input size="35" type="text" class="common" name="deep_links[<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
][<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
][url]" value="<?php if (isset($_smarty_tpl->tpl_vars['deep_url']->value)){?><?php echo $_smarty_tpl->tpl_vars['deep_url']->value;?>
<?php }?>"></label>

		<?php if (isset($_smarty_tpl->tpl_vars['deep_id']->value)&&!empty($_smarty_tpl->tpl_vars['deep_id']->value)){?>
			<input type="button" class="remove_deep common" name="remove_deep" id="deep_<?php echo $_smarty_tpl->tpl_vars['deep_id']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['delete'];?>
">
		<?php }?>
	</div>
<?php endfor; endif; ?>
					</td>
				</tr>
				</table>
				</div>
			<?php }?>
		<?php } ?>
	<?php }?>

	<table cellspacing="0" width="100%" class="striped" id="expire_table">
	<tr>
		<td class="tip-header" id="tip-header-expire_period" width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['expiration_period'];?>
:</strong></td>
		<td>
			<input type="text" name="expire" id="expire" class="common" value="<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['listing']->value['expire_date'];?>
<?php $_tmp1=ob_get_clean();?><?php if (isset($_tmp1)){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['expire_date'];?>
<?php }elseif(isset($_POST['expire'])){?><?php echo $_POST['expire'];?>
<?php }?>">
		</td>
	</tr>
	<tr>
		<td class="tip-header" id="tip-header-expire_notification"><strong><?php echo smarty_function_lang(array('key'=>"expire_notif"),$_smarty_tpl);?>
:</strong></td>
		<td><input type="text" name="expire_notif" id="expire_notif" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_notif'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_notif']>0){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['expire_notif'];?>
<?php }elseif(isset($_POST['expire_notif'])){?><?php echo $_POST['expire_notif'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['config']->value['expire_notif'];?>
<?php }?>">&nbsp;<?php if (isset($_smarty_tpl->tpl_vars['listing']->value)&&!empty($_smarty_tpl->tpl_vars['listing']->value)&&$_smarty_tpl->tpl_vars['listing']->value['expire_notif']>0&&'0000-00-00 00:00:00'!=$_smarty_tpl->tpl_vars['listing']->value['expire_date']){?><?php echo smarty_function_lang(array('key'=>"listing_notif_will_send"),$_smarty_tpl);?>
&nbsp;<strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['listing']->value['expire_notif_date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
</strong><?php }?></td>
	</tr>
	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['cron_for_expiration'];?>
:</strong></td>
		<td>
			<select name="expire_action">
				<option value="" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_action'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_action']==''){?>selected="selected"<?php }elseif(isset($_POST['expire_action'])&&$_POST['expire_action']==''){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['expire_action']==''){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['nothing'];?>
</option>
				<option value="remove" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_action'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_action']=='remove'){?>selected="selected"<?php }elseif(isset($_POST['expire_action'])&&$_POST['expire_action']=='remove'){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['expire_action']=='remove'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
</option>
				<optgroup label="Status">
					<option value="approval" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_action'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_action']=='approval'){?>selected="selected"<?php }elseif(isset($_POST['expire_action'])&&$_POST['expire_action']=='approval'){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['expire_action']=='approval'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</option>
					<option value="banned" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_action'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_action']=='banned'){?>selected="selected"<?php }elseif(isset($_POST['expire_action'])&&$_POST['expire_action']=='banned'){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['expire_action']=='banned'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banned'];?>
</option>
					<option value="suspended" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_action'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_action']=='suspended'){?>selected="selected"<?php }elseif(isset($_POST['expire_action'])&&$_POST['expire_action']=='suspended'){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['expire_action']=='suspended'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['suspended'];?>
</option>
				</optgroup>
				<optgroup label="Type">
					<option value="regular" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_action'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_action']=='regular'){?>selected="selected"<?php }elseif(isset($_POST['expire_action'])&&$_POST['expire_action']=='regular'){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['expire_action']=='regular'){?><?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['regular'];?>
</option>
					<option value="featured" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_action'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_action']=='featured'){?>selected="selected"<?php }elseif(isset($_POST['expire_action'])&&$_POST['expire_action']=='featured'){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['expire_action']=='featured'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['featured'];?>
</option>
					<option value="partner" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['expire_action'])&&$_smarty_tpl->tpl_vars['listing']->value['expire_action']=='partner'){?>selected="selected"<?php }elseif(isset($_POST['expire_action'])&&$_POST['expire_action']=='partner'){?>selected="selected"<?php }elseif($_smarty_tpl->tpl_vars['config']->value['expire_action']=='partner'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['partner'];?>
</option>
				</optgroup>
			</select>
		</td>
	</tr>
	</table>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['assign_account'];?>
:</strong></td>
		<td>
			<input type="radio" name="assign_account" value="1" id="a1" <?php if (isset($_POST['assign_account'])&&$_POST['assign_account']=='1'){?>checked="checked"<?php }?> /><label for="a1">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['new_account'];?>
</label>
			<input type="radio" name="assign_account" value="2" id="a2" <?php if (isset($_POST['assign_account'])&&$_POST['assign_account']=='2'){?>checked="checked"<?php }elseif(isset($_GET['do'])&&$_GET['do']=='edit'&&isset($_smarty_tpl->tpl_vars['account']->value)&&!empty($_smarty_tpl->tpl_vars['account']->value)){?>checked="checked"<?php }?> /><label for="a2">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['existing_account'];?>
</label>
			<input type="radio" name="assign_account" value="3" id="a3" <?php if (isset($_POST['assign_account'])&&$_POST['assign_account']=='3'){?>checked="checked"<?php }?> /><label for="a3">&nbsp;<?php echo smarty_function_lang(array('key'=>"reset_account"),$_smarty_tpl);?>
</label>
			<input type="radio" name="assign_account" value="0" id="a0" <?php if (isset($_POST['assign_account'])&&$_POST['assign_account']=='0'){?>checked="checked"<?php }elseif(!$_POST&&!isset($_smarty_tpl->tpl_vars['account']->value)){?>checked="checked"<?php }?> /><label for="a0">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['dont_assign'];?>
</label>

			<div id="exist_account" style="display:none;">
				<div id="accounts_list"><?php if (isset($_smarty_tpl->tpl_vars['account']->value)&&!empty($_smarty_tpl->tpl_vars['account']->value)){?><?php echo $_smarty_tpl->tpl_vars['account']->value['id'];?>
|<?php echo $_smarty_tpl->tpl_vars['account']->value['username'];?>
<?php }?></div>
			</div>
			<div id="new_account" style="display:none;">
				<table border="0">
				<tr>
					<td><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['username'];?>
:</td>
					<td><input type="text" name="new_account" size="45" class="common" value="<?php if (isset($_POST['new_account'])){?><?php echo $_POST['new_account'];?>
<?php }?>"></td>
				</tr>
				<tr>
					<td><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['email'];?>
:</td>
					<td><input type="text" name="new_account_email" size="45" class="common" value="<?php if (isset($_POST['new_account_email'])){?><?php echo $_POST['new_account_email'];?>
<?php }?>"></td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	</table>
	</fieldset>

	<div style="height: 15px;"></div>

	<fieldset>
	<legend><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['additional_fields'];?>
</strong></legend>	
	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listing_status'];?>
:</strong></td>
		<td>
			<select name="status">
				<option value="active" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['status'])&&$_smarty_tpl->tpl_vars['listing']->value['status']=='active'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='active'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</option>
				<option value="approval" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['status'])&&$_smarty_tpl->tpl_vars['listing']->value['status']=='approval'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='approval'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</option>
				<option value="banned" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['status'])&&$_smarty_tpl->tpl_vars['listing']->value['status']=='banned'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='banned'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banned'];?>
</option>
				<option value="suspended" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['status'])&&$_smarty_tpl->tpl_vars['listing']->value['status']=='suspended'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='suspended'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['suspended'];?>
</option>
				<option value="deleted" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['deleted'])&&$_smarty_tpl->tpl_vars['listing']->value['status']=='deleted'){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']=='deleted'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['deleted'];?>
</option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="tip-header first" id="tip-header-rank"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['rank'];?>
:</strong></td>
		<td>
			<select name="rank">
				<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]);
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['name'] = "listing_rank";
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['loop'] = is_array($_loop="6") ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_rank"]['total']);
?>
					<option value="<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['listing_rank']['index'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['rank'])&&$_smarty_tpl->tpl_vars['listing']->value['rank']==$_smarty_tpl->getVariable('smarty')->value['section']['listing_rank']['index']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->getVariable('smarty')->value['section']['listing_rank']['index'];?>
</option>
				<?php endfor; endif; ?>
			</select>
		</td>
	</tr>

	<tr>
		<td class="tip-header first"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['pagerank'];?>
:</strong></td>
		<td>
			<select name="pagerank" id="pagerank">
				<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]);
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['name'] = "listing_pagerank";
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['loop'] = is_array($_loop="11") ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']["listing_pagerank"]['total']);
?>
					<option value="<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['listing_pagerank']['index'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['listing']->value['pagerank'])&&$_smarty_tpl->tpl_vars['listing']->value['pagerank']==$_smarty_tpl->getVariable('smarty')->value['section']['listing_pagerank']['index']){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->getVariable('smarty')->value['section']['listing_pagerank']['index'];?>
</option>
				<?php endfor; endif; ?>
			</select>

			<a href="#" id="get_current_pagerank"><?php echo smarty_function_lang(array('key'=>'get_pagerank'),$_smarty_tpl);?>
</a>
		</td>
	</tr>

	</table>
	</fieldset>

	</table>

	<div style="height: 20px;"></div>
	<table cellspacing="0" width="100%" class="striped">
	<tr>
		<td style="padding: 0 0 0 11px; width: 1%">
			<input type="checkbox" name="send_email" id="send_email" checked="checked">&nbsp;<label for="send_email"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['email_notif'];?>
?</label>&nbsp;|&nbsp;
			<input type="submit" name="save" class="common" value="<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['create_listing'];?>
<?php }?>">

			<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>

				<input type="submit" name="delete" id="delete" class="common" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove'];?>
">

				<?php if (isset($_SERVER['HTTP_REFERER'])&&stristr($_SERVER['HTTP_REFERER'],'browse')){?>
					<input type="hidden" name="goto" value="browse">
				<?php }else{ ?>
					<input type="hidden" name="goto" value="list">
				<?php }?>
			<?php }else{ ?>
				<span><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['and_then'];?>
</strong></span>
				<select name="goto">
					<option value="list" <?php if (isset($_POST['goto'])&&$_POST['goto']=='list'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go_to_list'];?>
</option>
					<option value="add" <?php if (isset($_POST['goto'])&&$_POST['goto']=='add'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add_another_one'];?>
</option>
					<option value="addtosame" <?php if (isset($_POST['goto'])&&$_POST['goto']=='addtosame'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add_another_one_to_same'];?>
</option>
				</select>
			<?php }?>
		</td>
	</tr>

	</table>
	<input type="hidden" name="do" value="<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?><?php echo $_GET['do'];?>
<?php }?>">
	<input type="hidden" name="old_alias" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['old_alias'])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['old_alias'];?>
<?php }?>">
	</form>
<?php }?>

<div style="display: none;">
	<div id="tip-content-rank"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['rank_listing_option'];?>
</div>
	<div id="tip-content-expire_notification"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['tooltip_expire_notification'];?>
</div>
	<div id="tip-content-expire_period"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['tooltip_expire_period'];?>
</div>
</div>

<?php echo smarty_function_ia_hooker(array('name'=>"tplAdminSuggestListingBeforeIncludeJs"),$_smarty_tpl);?>


<?php echo smarty_function_include_file(array('js'=>"js/jquery/plugins/iphoneswitch/jquery.iphone-switch, js/jquery/plugins/lightbox/jquery.lightbox, js/ckeditor/ckeditor, js/jquery/plugins/jquery.charCount, js/admin/suggest-listing, js/utils/underscore-min"),$_smarty_tpl);?>


<?php echo smarty_function_ia_hooker(array('name'=>"tplAdminSuggestListingAfterIncludeJs"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>