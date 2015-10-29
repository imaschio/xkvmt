<?php /* Smarty version Smarty-3.1.13, created on 2015-03-25 03:14:59
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/importer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2780460055126073b3d078-22942037%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e16f995f0e0602ad4dbfbd60771da21d6e750b2' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/importer.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2780460055126073b3d078-22942037',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'report' => 0,
    'esynI18N' => 0,
    'rep' => 0,
    'importers' => 0,
    'success' => 0,
    'gTitle' => 0,
    'importer' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55126073c7f6e9_71195061',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55126073c7f6e9_71195061')) {function content_55126073c7f6e9_71195061($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php if (isset($_smarty_tpl->tpl_vars['report']->value)&&!empty($_smarty_tpl->tpl_vars['report']->value)){?>
	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['import_report']), 0);?>


	<table cellspacing="0" width="100%" class="striped">
	
	<?php  $_smarty_tpl->tpl_vars['rep'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['rep']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['report']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['rep']->key => $_smarty_tpl->tpl_vars['rep']->value){
$_smarty_tpl->tpl_vars['rep']->_loop = true;
?>
		<tr>
			<td width="250"><?php echo $_smarty_tpl->tpl_vars['rep']->value['msg'];?>
</td>
			<td>
				<strong>
					<?php if ($_smarty_tpl->tpl_vars['rep']->value['success']){?>
						<span style="color: green;">OK</span>
					<?php }else{ ?>
						<span style="color: red;">FAIL</span>
					<?php }?>
				</strong>
			</td>
		</tr>
	<?php } ?>

	</table>

	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['importers']->value)&&!empty($_smarty_tpl->tpl_vars['importers']->value)&&!isset($_smarty_tpl->tpl_vars['success']->value)){?>
	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value,'id'=>'database_panel'), 0);?>

	
	<form action="controller.php?file=importer" method="post" id="import_form">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>


	<table cellspacing="0" width="100%" class="striped">
	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['choose_importer'];?>
:</strong></td>
		<td>
			<select name="importer" id="importer" class="common" style="float: left;">
			<?php  $_smarty_tpl->tpl_vars["importer"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["importer"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['importers']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["importer"]->key => $_smarty_tpl->tpl_vars["importer"]->value){
$_smarty_tpl->tpl_vars["importer"]->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['importer']->value;?>
" <?php if (isset($_POST['importer'])&&$_POST['importer']==$_smarty_tpl->tpl_vars['importer']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['importer']->value;?>
</option>
			<?php } ?>
			</select>

			<div class="option_tip" style="float: left; margin: 2px 0 0 10px;"><i>Please use the details of your source database that you want to migrate the data from.</i></div>
		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['database_host'];?>
:</strong></td>
		<td>
			<input type="text" name="host" class="common" value="<?php if (isset($_POST['host'])&&!empty($_POST['host'])){?><?php echo htmlspecialchars($_POST['host'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" id="host" placeholder="<?php echo @constant('IA_DBHOST');?>
"/>
		</td>
	</tr>

	<tr>
		<td style="vertical-align:top;"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['database_name'];?>
:</strong></td>
		<td>
			<input type="text" name="database" class="common" value="<?php if (isset($_POST['database'])&&!empty($_POST['database'])){?><?php echo htmlspecialchars($_POST['database'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" id="database" placeholder="<?php echo @constant('IA_DBNAME');?>
">
		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['database_username'];?>
:</strong></td>
		<td>
			<input type="text" name="username" class="common" value="<?php if (isset($_POST['username'])&&!empty($_POST['username'])){?><?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" id="username" placeholder="<?php echo @constant('IA_DBUSER');?>
" autocomplete="off"/>
		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['database_password'];?>
:</strong></td>
		<td>
			<input type="password" name="password" id="password" class="common" placeholder="<?php echo @constant('IA_DBPASS');?>
" autocomplete="off"/>
		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['database_prefix'];?>
:</strong></td>
		<td>
			<input type="text" name="prefix" id="prefix" class="common" value="<?php if (isset($_POST['prefix'])&&!empty($_POST['prefix'])){?><?php echo htmlspecialchars($_POST['prefix'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" placeholder="<?php echo @constant('IA_DBPREFIX');?>
">
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<input type="button" name="connect" id="connect" value="Connect" class="common" style="float: left; margin-right: 10px;"/>
			<input type="button" id="placehold" value="Use same DB" class="common" style="float: left; margin-right: 10px;">
			<input type="button" name="resetdb" id="resetdb" value="Reset" class="common">
		</td>
	</tr>
	</table>

	</form>

	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?>

<div id="tables_panel" style="display: none;">
	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value,'id'=>'tables_panel'), 0);?>


	<div id="migrate_items" style="text-transform: capitalize; margin: 10px 0 10px 10px;"></div>

	<p>
		<input type="submit" name="start" id="start" value="Import" class="common">
	</p>

	<div class="progress" style="margin: 20px 0 10px 0;">
		<div id="percents">0%</div>
		<div id="progress_bars" style="background: url(templates/<?php echo $_smarty_tpl->tpl_vars['config']->value['admin_tmpl'];?>
/img/bgs/progress_bar.gif) left repeat-x;"></div>
	</div>

	<div class="import_result" id="import_result"></div>

	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</div>

<?php echo smarty_function_include_file(array('js'=>"js/admin/importer"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>