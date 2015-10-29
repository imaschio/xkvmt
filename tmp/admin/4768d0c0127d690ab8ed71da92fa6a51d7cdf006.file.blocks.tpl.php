<?php /* Smarty version Smarty-3.1.13, created on 2015-03-30 12:30:28
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/blocks.tpl" */ ?>
<?php /*%%SmartyHeaderCode:167680614355197a2451f0a8-88096589%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4768d0c0127d690ab8ed71da92fa6a51d7cdf006' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/blocks.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '167680614355197a2451f0a8-88096589',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'block' => 0,
    'gTitle' => 0,
    'esynI18N' => 0,
    'types' => 0,
    'type' => 0,
    'positions' => 0,
    'position' => 0,
    'block_status' => 0,
    'b_status' => 0,
    'langs' => 0,
    'code' => 0,
    'lang' => 0,
    'pages_group' => 0,
    'pages' => 0,
    'group' => 0,
    'post_key' => 0,
    'page' => 0,
    'key' => 0,
    'visibleOn' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55197a24766ad5_95131999',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55197a24766ad5_95131999')) {function content_55197a24766ad5_95131999($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_html_radio_switcher')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.html_radio_switcher.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer",'js'=>"js/utils/edit_area/edit_area_full, js/jquery/plugins/iphoneswitch/jquery.iphone-switch"), 0);?>


<?php if ((isset($_GET['do'])&&$_GET['do']=='add')||(isset($_GET['do'])&&$_GET['do']=='edit'&&isset($_smarty_tpl->tpl_vars['block']->value)&&!empty($_smarty_tpl->tpl_vars['block']->value))){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	<form action="controller.php?file=blocks&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table class="striped" cellspacing="0" width="100%">
	<tr>
		<td class="caption" colspan="2"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['block_options'];?>
</strong></td>
	</tr>
	<tr>
		<td width="150"><strong><?php echo smarty_function_lang(array('key'=>"name"),$_smarty_tpl);?>
:</strong></td>
		<td>
			<input type="text" name="name" size="24" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['block']->value['name'])){?><?php echo $_smarty_tpl->tpl_vars['block']->value['name'];?>
<?php }elseif(isset($_POST['name'])){?><?php echo $_POST['name'];?>
<?php }?>" <?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>readonly="readonly"<?php }?> />
			<div class="option_tip"><?php echo smarty_function_lang(array('key'=>"unique_name"),$_smarty_tpl);?>
</div>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['type'];?>
:</strong></td>
		<td>
			<select name="type" id="block_type">
				<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['types']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value){
$_smarty_tpl->tpl_vars['type']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['type']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
" <?php if (isset($_smarty_tpl->tpl_vars['block']->value['type'])&&$_smarty_tpl->tpl_vars['block']->value['type']==$_smarty_tpl->tpl_vars['type']->value){?>selected="selected"<?php }elseif(isset($_POST['type'])&&$_POST['type']==$_smarty_tpl->tpl_vars['type']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['type']->value;?>
</option>
				<?php } ?>
			</select>
			<br />
			<div class="option_tip" id="type_tip_plain" style="display: none;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['block_type_tip_plain'];?>
</i></div>
			<div class="option_tip" id="type_tip_html" style="display: none;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['block_type_tip_html'];?>
</i></div>
			<div class="option_tip" id="type_tip_smarty" style="display: none;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['block_type_tip_smarty'];?>
</i></div>
			<div class="option_tip" id="type_tip_php" style="display: none;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['block_type_tip_php'];?>
</i></div>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['position'];?>
:</strong></td>
		<td>
			<select name="position">
				<?php  $_smarty_tpl->tpl_vars['position'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['position']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['positions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['position']->key => $_smarty_tpl->tpl_vars['position']->value){
$_smarty_tpl->tpl_vars['position']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['position']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['position']->value;?>
" <?php if (isset($_smarty_tpl->tpl_vars['block']->value['position'])&&$_smarty_tpl->tpl_vars['block']->value['position']==$_smarty_tpl->tpl_vars['position']->value){?>selected="selected"<?php }elseif(isset($_POST['position'])&&$_POST['position']==$_smarty_tpl->tpl_vars['position']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['position']->value;?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['classname'];?>
:</strong></td>
		<td>
			<input type="text" name="classname" size="40" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['block']->value['classname'])){?><?php echo $_smarty_tpl->tpl_vars['block']->value['classname'];?>
<?php }elseif(isset($_POST['classname'])){?><?php echo $_POST['classname'];?>
<?php }?>"/>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status'];?>
:</strong></td>
		<td>
			<select name="status">
				<?php  $_smarty_tpl->tpl_vars['b_status'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['b_status']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['block_status']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['b_status']->key => $_smarty_tpl->tpl_vars['b_status']->value){
$_smarty_tpl->tpl_vars['b_status']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['b_status']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['b_status']->value;?>
" <?php if (isset($_smarty_tpl->tpl_vars['block']->value['status'])&&$_smarty_tpl->tpl_vars['block']->value['status']==$_smarty_tpl->tpl_vars['b_status']->value){?>selected="selected"<?php }elseif(isset($_POST['status'])&&$_POST['status']==$_smarty_tpl->tpl_vars['b_status']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['b_status']->value;?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['show_header'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['block']->value['show_header'])===null||$tmp==='' ? $_POST['show_header'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"show_header"),$_smarty_tpl);?>

		</td>
	</tr>
	<tr style="display: none;">
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['collapsible'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['block']->value['collapsible'])===null||$tmp==='' ? $_POST['collapsible'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"collapsible"),$_smarty_tpl);?>

		</td>
	</tr>
	<tr style="display: none;">
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['collapsed'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['block']->value['collapsed'])===null||$tmp==='' ? $_POST['collapsed'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>"collapsed"),$_smarty_tpl);?>

		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['multi_language'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['block']->value['multi_language'])===null||$tmp==='' ? $_POST['multi_language'] : $tmp))===null||$tmp==='' ? 1 : $tmp),'name'=>"multi_language"),$_smarty_tpl);?>

		</td>
	</tr>
	<tr id="languages" style="display: none;">
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['language'];?>
:</strong></td>
		<td>
			<label><input type="checkbox" id="select_all_languages" name="select_all_languages" value="1" <?php if (isset($_POST['select_all'])&&$_POST['select_all']=='1'){?>checked="checked"<?php }?> />&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['select_all'];?>
</label>
			
			<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
				<br /><label><input type="checkbox" class="block_languages" name="block_languages[]" value="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" <?php if (isset($_smarty_tpl->tpl_vars['block']->value['block_languages'])&&!empty($_smarty_tpl->tpl_vars['block']->value['block_languages'])&&in_array($_smarty_tpl->tpl_vars['code']->value,$_smarty_tpl->tpl_vars['block']->value['block_languages'])){?>checked="checked"<?php }elseif(isset($_POST['block_languages'])&&in_array($_smarty_tpl->tpl_vars['code']->value,$_POST['block_languges'])){?>checked="checked"<?php }?> />&nbsp;<?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
</label>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['sticky'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['block']->value['sticky'])===null||$tmp==='' ? $_POST['sticky'] : $tmp))===null||$tmp==='' ? 1 : $tmp),'name'=>"sticky"),$_smarty_tpl);?>

		</td>
	</tr>
	</table>
	
	<div id="acos" style="display: none;">
	<table class="striped">
	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['visible_on_pages'];?>
:</strong></td>
		<td>
			<?php if (isset($_smarty_tpl->tpl_vars['pages_group']->value)&&!empty($_smarty_tpl->tpl_vars['pages_group']->value)){?>
				<?php if (isset($_smarty_tpl->tpl_vars['pages']->value)&&!empty($_smarty_tpl->tpl_vars['pages']->value)){?>
					<input type="checkbox" value="1" name="select_all" id="select_all" <?php if (isset($_POST['select_all'])&&$_POST['select_all']=='1'){?>checked="checked"<?php }?> /><label for="select_all">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['select_all'];?>
</label>
						<div style="clear:both;"></div>
					<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pages_group']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
?>
						<fieldset class="list" style="float:left;">
							<?php if (isset($_smarty_tpl->tpl_vars["post_key"])) {$_smarty_tpl->tpl_vars["post_key"] = clone $_smarty_tpl->tpl_vars["post_key"];
$_smarty_tpl->tpl_vars["post_key"]->value = ("select_all_").($_smarty_tpl->tpl_vars['group']->value); $_smarty_tpl->tpl_vars["post_key"]->nocache = null; $_smarty_tpl->tpl_vars["post_key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["post_key"] = new Smarty_variable(("select_all_").($_smarty_tpl->tpl_vars['group']->value), null, 0);?>
							<legend><input type="checkbox" value="1" class="<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" name="select_all_<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" id="select_all_<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" <?php if (isset($_POST[$_smarty_tpl->tpl_vars['post_key']->value])&&$_POST[$_smarty_tpl->tpl_vars['post_key']->value]=='1'){?>checked="checked"<?php }?> /><label for="select_all_<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
">&nbsp;<strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['group']->value];?>
</strong></label></legend>
							<?php  $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['page']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value){
$_smarty_tpl->tpl_vars['page']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['page']->key;
?>
								<?php if ($_smarty_tpl->tpl_vars['page']->value['group']==$_smarty_tpl->tpl_vars['group']->value){?>
									<ul style="list-style-type: none;">
										<li style="margin: 0 0 0 15px; padding-bottom: 3px; float: left; width: 200px;" >
											<input type="checkbox" name="visible_on_pages[]" class="<?php echo $_smarty_tpl->tpl_vars['group']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['page']->value['name'];?>
" id="page_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" <?php if (in_array($_smarty_tpl->tpl_vars['page']->value['name'],$_smarty_tpl->tpl_vars['visibleOn']->value,true)){?>checked="checked"<?php }?> /><label for="page_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"> <?php if (empty($_smarty_tpl->tpl_vars['page']->value['title'])){?><?php echo $_smarty_tpl->tpl_vars['page']->value['name'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
<?php }?></label>
											<?php if ($_smarty_tpl->tpl_vars['page']->value['name']=='index_browse'){?>
												<a style="float:right;padding-right:10px;" href="#" id="add_categories">
													<img src="<?php echo @constant('IA_URL');?>
js/ext/resources/images/default/tree/leaf.gif" alt="">
												</a>
											<?php }?>
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

	<table class="striped" cellspacing="0" width="100%" id="external_switcher" style="<?php if (isset($_smarty_tpl->tpl_vars['block']->value['type'])&&in_array($_smarty_tpl->tpl_vars['block']->value['type'],array('php','smarty'))){?>display: block;<?php }else{ ?>display: none;<?php }?>">
	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['external_file'];?>
:</strong></td>
		<td>
			<?php echo smarty_function_html_radio_switcher(array('value'=>(($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['block']->value['external'])===null||$tmp==='' ? $_POST['external'] : $tmp))===null||$tmp==='' ? 0 : $tmp),'name'=>'external'),$_smarty_tpl);?>

		</td>
	</tr>
	</table>

	<table class="striped" cellspacing="0" width="100%">
	<tr>
		<td width="150" class="caption" colspan="2"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['block_contents'];?>
</strong></td>
	</tr>
	</table>

	<div id="blocks_contents" style="display: none;">
		<table class="striped" cellspacing="0" width="100%">
		<tr>
			<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
:</strong></td>
			<td><input type="text" name="multi_title" size="30" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['block']->value['title'])&&!is_array($_smarty_tpl->tpl_vars['block']->value['title'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['block']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['multi_title'])){?><?php echo htmlspecialchars($_POST['multi_title'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>"></td>
		</tr>
		<tr>
			<td colspan="2" style="padding: 0; margin: 0;">
				<div id="non_external_content" style="padding: 0; margin: 0;">
					<table cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['contents'];?>
:</strong></td>
						<td>
							<div class="option_tip" id="php_tags_tooltip" style="display: none; margin-bottom: 10px;"><i><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['php_tags_tooltip'];?>
</i></div>
							<textarea name="multi_contents" id="multi_contents" cols="50" rows="8" class="cked common"><?php if (isset($_smarty_tpl->tpl_vars['block']->value['contents'])&&!is_array($_smarty_tpl->tpl_vars['block']->value['contents'])){?><?php echo $_smarty_tpl->tpl_vars['block']->value['contents'];?>
<?php }elseif(isset($_POST['multi_contents'])){?><?php echo $_POST['multi_contents'];?>
<?php }?></textarea>
						</td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
	</div>

	<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
		<div id="blocks_contents_<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" style="display: none;">
			<table class="striped" cellspacing="0" width="100%">
			<tr>
				<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
&nbsp;[<?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
]:</strong></td>
				<td><input type="text" name="title[<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
]" size="30" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['block']->value)&&is_array($_smarty_tpl->tpl_vars['block']->value['title'])&&isset($_smarty_tpl->tpl_vars['block']->value['title'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['block']->value['title'][$_smarty_tpl->tpl_vars['code']->value], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['title'])&&is_array($_POST['title'])&&isset($_smarty_tpl->tpl_vars['block']->value['post']['title'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo htmlspecialchars($_POST['title'][$_smarty_tpl->tpl_vars['code']->value], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['block']->value['title'])&&!empty($_smarty_tpl->tpl_vars['block']->value['title'])){?><?php echo $_smarty_tpl->tpl_vars['block']->value['title'];?>
<?php }?>"></td>
			</tr>
			<tr>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['contents'];?>
&nbsp;[<?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
]:</strong></td>
				<td><textarea name="contents[<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
]" id="contents_<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" cols="50" rows="8" class="common" width="100%"><?php if (isset($_smarty_tpl->tpl_vars['block']->value)&&is_array($_smarty_tpl->tpl_vars['block']->value['contents'])&&isset($_smarty_tpl->tpl_vars['block']->value['contents'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['block']->value['contents'][$_smarty_tpl->tpl_vars['code']->value], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['contents'])&&is_array($_POST['contents'])&&isset($_POST['contents'][$_smarty_tpl->tpl_vars['code']->value])){?><?php echo htmlspecialchars($_POST['contents'][$_smarty_tpl->tpl_vars['code']->value], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['block']->value['contents'])&&!empty($_smarty_tpl->tpl_vars['block']->value['contents'])){?><?php echo $_smarty_tpl->tpl_vars['block']->value['contents'];?>
<?php }?></textarea></td>
			</tr>
			</table>
		</div>
	<?php } ?>

	<table class="striped">
	<tr class="all">
		<td colspan="2">
			<input type="hidden" name="do" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>">
			<input type="hidden" name="id" value="<?php if (isset($_smarty_tpl->tpl_vars['block']->value['id'])){?><?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
<?php }?>">
			<input type="hidden" name="cat_crossed" id="cat_crossed" value="<?php if (isset($_smarty_tpl->tpl_vars['block']->value['categories'])&&!empty($_smarty_tpl->tpl_vars['block']->value['categories'])){?><?php echo implode(',',$_smarty_tpl->tpl_vars['block']->value['categories']);?>
<?php }?>">
			<input type="submit" name="save" class="common" value="<?php if ($_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
<?php }?>">
		</td>
	</tr>
	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }else{ ?>
	<div id="box_blocks" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, js/ckeditor/ckeditor, js/admin/blocks"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>