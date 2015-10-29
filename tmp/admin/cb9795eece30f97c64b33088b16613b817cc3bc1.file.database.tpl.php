<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 03:10:03
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/database.tpl" */ ?>
<?php /*%%SmartyHeaderCode:528511939550924cbdf8da3-17447698%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cb9795eece30f97c64b33088b16613b817cc3bc1' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/database.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '528511939550924cbdf8da3-17447698',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'backup_is_not_writeable' => 0,
    'out_sql' => 0,
    'esynI18N' => 0,
    'tables' => 0,
    'table' => 0,
    'upgrades' => 0,
    'value' => 0,
    'gTitle' => 0,
    'sql_query' => 0,
    'queryOut' => 0,
    'stats_items' => 0,
    'reset_options' => 0,
    'key' => 0,
    'option' => 0,
    'hooks' => 0,
    'hook' => 0,
    'code' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550924cc0b4799_40456036',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550924cc0b4799_40456036')) {function content_550924cc0b4799_40456036($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php if ($_GET['page']=='export'){?>
	<?php if (isset($_smarty_tpl->tpl_vars['backup_is_not_writeable']->value)){?>
		<div class="message alert" id="backup_message">
			<div class="inner">
				<div class="icon"></div>
				<ul>
					<li><?php echo $_smarty_tpl->tpl_vars['backup_is_not_writeable']->value;?>
</li>
				</ul>
			</div>
		</div>
	<?php }?>

	<?php if (isset($_smarty_tpl->tpl_vars['out_sql']->value)&&!empty($_smarty_tpl->tpl_vars['out_sql']->value)){?>
		<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['export']), 0);?>

			<textarea class="common" style="margin-top: 10px;" rows="24" cols="15" readonly="readonly">
				<?php echo $_smarty_tpl->tpl_vars['out_sql']->value;?>

			</textarea>
		<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<?php }?>

	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['export']), 0);?>


	<form action="controller.php?file=database&amp;page=export" method="post" name="dump" id="dump">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table width="100%" cellspacing="0" cellpadding="0" class="striped">
	<tr class="tr">
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['export'];?>
:</strong></td>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['mysql_options'];?>
:</strong></td>
	</tr>
	<tr>
		<td valign="top">
			<select name="tbl[]" id="tbl" size="7" multiple="multiple" style="font-size: 12px; font-family: Verdana;">

			<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['table']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value;?>
</option>
			<?php } ?>

			</select>

			<div style="margin-top: 5px; text-align: center;" class="selecting">
				<a href="#" class="select"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['select_all'];?>
</a>&nbsp;/&nbsp;
				<a href="#" class="deselect"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['select_none'];?>
</a>
			</div>
		</td>
		<td align="left" width="100%">
			<table cellspacing="1" width="100%" class="striped">
			<tr>
				<td style="background-color: #E5E5E5;">
					<input type="checkbox" name="sql_structure" value="structure" id="sql_structure" <?php if (isset($_POST['sql_structure'])||!$_POST){?>checked="checked"<?php }?> style="vertical-align: middle">
					<label for="sql_structure"><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['structure'];?>
:</b></label><br />&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="drop" value="1" <?php if (isset($_POST['drop'])&&$_POST['drop']=='1'){?>checked="checked"<?php }?> id="dump_drop" style="vertical-align: middle">
					<label for="dump_drop"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add_drop_table'];?>
</label>
				</td>
			</tr>
			<tr>
				<td style="background-color: #E5E5E5;">
					<input type="checkbox" name="sql_data" value="data" id="sql_data" <?php if (isset($_POST['sql_data'])||!$_POST){?>checked="checked"<?php }?> style="vertical-align: middle">
					<label for="sql_data"><b>Data:</b></label><br />&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" name="showcolumns" value="1" <?php if (isset($_POST['showcolumns'])&&$_POST['showcolumns']=='1'){?>checked="checked"<?php }?> id="dump_showcolumns" style="vertical-align: middle">
					<label for="dump_showcolumns"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['complete_inserts'];?>
</label>
				</td>
			</tr>
			<tr>
				<td style="background-color: #E5E5E5;">
					<input type="checkbox" name="real_prefix" id="real_prefix" <?php if (isset($_POST['real_prefix'])||!$_POST){?>checked="checked"<?php }?> style="vertical-align: middle">
					<label for="real_prefix"><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['use_real_prefix'];?>
</b></label><br />
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="tr">
			<input type="checkbox" name="save_file" id="save_file" style="vertical-align: middle">
			<label for="save_file"><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_as_file'];?>
</b></label><br />
		</td>
	</tr>
	</table>

	<div id="save_to" style="display: none;">
		<table width="100%" cellpadding="0" cellspacing="0" class="striped">
		<tr class="tr">
			<td width="50%" style="padding-left: 10px;" class="fields-pages">
				<label for="server"><input type="radio" name="savetype" value="server" id="server">&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_to_server'];?>
</label>
				<label for="client"><input type="radio" name="savetype" value="client" id="client" <?php if (isset($_POST['savetype'])&&$_POST['savetype']=='client'||!$_POST){?>checked="checked"<?php }?> />&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_to_pc'];?>
</label>
			</td>
			<td style="padding-right: 20px; text-align: right;">
				<input type="checkbox" name="gzip_compress" id="gzip_compress" <?php if (isset($_POST['gzip_compress'])||!$_POST){?>checked="checked"<?php }?> style="vertical-align: middle">
				<label for="gzip_compress"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['gzip_compress'];?>
</label>
			</td>
		</tr>
		</table>
	</div>

	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
		<tr class="all">
			<td colspan="2" align="right">
				<input type="button" id="exportAction" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go'];?>
" class="common">
				<input type="hidden" name="export" id="export">
			</td>
		</tr>
	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }elseif($_GET['page']=='import'){?>
	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['import']), 0);?>


	<form action="controller.php?file=database&amp;page=import" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table width="100%" cellspacing="0" class="striped">

	<?php if ($_smarty_tpl->tpl_vars['upgrades']->value){?>
		<tr class="tr">
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['choose_import_file'];?>
:</strong></td>
		</tr>
		<tr>
			<td width="50%">
				<select name="sqlfile">
					<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['upgrades']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" <?php if (isset($_POST['sqlfile'])&&$_POST['sqlfile']==$_smarty_tpl->tpl_vars['value']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr class="all tr">
			<td align="right"><input type="submit" name="run_update" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go'];?>
" class="common"></td>
		</tr>
	<?php }else{ ?>
		<tr class="tr">
			<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['no_upgrades'];?>
</strong></td>
		</tr>
	<?php }?>

	</table>
	</form>

	<form enctype="multipart/form-data" action="controller.php?file=database&amp;page=import" method="post" name="update" id="update">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellpadding="0" cellspacing="0" width="100%" class="striped">
	<tr class="tr">
		<td class="caption"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['choose_import_file'];?>
</strong></td>
	</tr>
	<tr class="tr">
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['location_sql_file'];?>
:</strong></td>
	</tr>
	<tr>
		<td>
			<input type="file" name="sql_file" id="sql_file" class="textfield">&nbsp;(Max: 2,048KB)<br />
			<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
		</td>
	</tr>
	<tr class="all tr">
		<td align="right">
			<input type="button" id="importAction" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go'];?>
" class="common">
			<input type="hidden" name="run_update" id="run_update">
		</td>
	</tr>
	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }elseif($_GET['page']=='sql'){?>
	<?php echo smarty_function_include_file(array('js'=>"js/ext/plugins/tablegrid/TableGrid"),$_smarty_tpl);?>


	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>


	<form action="controller.php?file=database&amp;page=sql" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table width="100%" cellspacing="0" cellpadding="0" class="striped">
	<tr style="font-weight: bold;" class="tr">
		<td><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['run_sql_queries'];?>
:</td>
		<td><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['tables_fields'];?>
:</td>
	</tr>
	<tr>
		<td width="99%" valign="top">
			<textarea class="noresize" rows="4" cols="4" name="query" id="query" style="height: 300px; width: 100%; font-size: 12px; font-family: Verdana;"><?php if (isset($_POST['show_query'])&&$_POST['show_query']=='1'&&isset($_smarty_tpl->tpl_vars['sql_query']->value)&&$_smarty_tpl->tpl_vars['sql_query']->value!=''){?><?php echo $_smarty_tpl->tpl_vars['sql_query']->value;?>
<?php }else{ ?>SELECT * FROM <?php }?></textarea>
		</td>
		<td width="30" valign="top">
			<select name="table" id="table" size="10" style="font-size: 12px; font-family: Verdana; height: 158px; width: 200px;">
			<?php  $_smarty_tpl->tpl_vars['table'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['table']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['table']->key => $_smarty_tpl->tpl_vars['table']->value){
$_smarty_tpl->tpl_vars['table']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['table']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value;?>
</option>
			<?php } ?>
			</select>
			<p style="text-align: left; margin: 10px 0;">
				<input type="button" value="&#171;" id="addTableButton" class="common small">
			</p>

			<select name="field" id="field" size="5" style="font-size: 12px; font-family: Verdana; display: none; width: 200px;">
				<option>&nbsp;</option>
			</select>
			<p style="text-align: left; margin: 10px 0 0 0;">
				<input type="button" value="&#171;" id="addFieldButton" style="display: none;" class="common small"/>
			</p>
		</td>
	</tr>
	<tr class="all tr">
	<td>
		<input type="checkbox" name="show_query" value="1" id="sh1" style="vertical-align: middle" <?php if (isset($_POST['show_query'])&&$_POST['show_query']=='1'||!$_POST){?>checked="checked"<?php }?> />
		<label for="sh1"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['show_query_again'];?>
</label>
	</td>
	<td colspan="2" align="right">
		<input type="submit" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['go'];?>
" name="exec_query" class="common small">
		<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['clear'];?>
" id="clearButton" class="common small">
	</td>
	</tr>
	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


	<?php if (isset($_smarty_tpl->tpl_vars['queryOut']->value)&&$_smarty_tpl->tpl_vars['queryOut']->value!=''){?>
		<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['display'],'style'=>"height: 450px; overflow: auto;"), 0);?>

		<?php echo $_smarty_tpl->tpl_vars['queryOut']->value;?>

		<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<?php }?>
<?php }elseif($_GET['page']=='consistency'){?>

	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>


	<ul class="consistency-list">
		<li>
			<div class="consistency-item-actions">
				<h5><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['categories_level'];?>
:</h5>
				<a class="ajax" data-type="categories_level" data-num="<?php echo $_smarty_tpl->tpl_vars['stats_items']->value['categories'];?>
" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['repair'];?>
</a>
			</div>
			<div class="consistency-item-annotation">
				<?php echo smarty_function_lang(array('key'=>'consistency_categories_level'),$_smarty_tpl);?>

			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['categories_paths_repair'];?>
:</h5>
				<a class="ajax" data-type="categories_paths" data-num="<?php echo $_smarty_tpl->tpl_vars['stats_items']->value['categories'];?>
" data-prelaunch="1" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['repair'];?>
</a>
			</div>
			<div class="consistency-item-annotation">
				<?php echo smarty_function_lang(array('key'=>'consistency_categories_paths'),$_smarty_tpl);?>

			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['categories_relation'];?>
:</h5>
				<a class="ajax" data-type="categories_relation" data-num="<?php echo $_smarty_tpl->tpl_vars['stats_items']->value['categories'];?>
" data-prelaunch="1" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['repair'];?>
</a>
			</div>
			<div class="consistency-item-annotation">
				<?php echo smarty_function_lang(array('key'=>'consistency_categories_relation'),$_smarty_tpl);?>

			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listing_aliases'];?>
:</h5>
				<a class="ajax" data-type="listing_alias" data-num="<?php echo $_smarty_tpl->tpl_vars['stats_items']->value['listings'];?>
" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['repair'];?>
</a>
			</div>
			<div class="consistency-item-annotation">
				<?php echo smarty_function_lang(array('key'=>'consistency_listing_aliases'),$_smarty_tpl);?>

				<div style="margin-top:10px;">
					<input type="checkbox" name="all_listings" id="all_listings" style="width:auto;">
					<label for="all_listings"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['remove_all_aliases'];?>
</label>
				</div>
			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active_listings_count'];?>
:</h5>
				<a class="ajax" data-type="num_all_listings" data-num="<?php echo $_smarty_tpl->tpl_vars['stats_items']->value['categories'];?>
" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['recount'];?>
</a>
			</div>
			<div class="consistency-item-annotation">
				<?php echo smarty_function_lang(array('key'=>'consistency_num_all_listings'),$_smarty_tpl);?>

			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listings_and_categories'];?>
:</h5>
				<a class="ajax" data-type="listing_categories" data-num="<?php echo $_smarty_tpl->tpl_vars['stats_items']->value['categories'];?>
" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['find_and_delete'];?>
</a>
			</div>
			<div class="consistency-item-annotation">
				<?php echo smarty_function_lang(array('key'=>'consistency_listing_categories'),$_smarty_tpl);?>

			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['repair_tables'];?>
:</h5>
				<a href="controller.php?file=database&amp;page=consistency&amp;type=repair_tables"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['repair'];?>
</a>
			</div>
			<div class="consistency-item-annotation">
				<?php echo smarty_function_lang(array('key'=>'consistency_repair_tables'),$_smarty_tpl);?>

			</div>
		</li>
		<li>
			<div class="consistency-item-actions">
				<h5><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['optimize_tables'];?>
:</h5>
				<a href="controller.php?file=database&amp;page=consistency&amp;type=optimize_tables"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['optimize_tables'];?>
</a>
			</div>
			<div class="consistency-item-annotation">
				<?php echo smarty_function_lang(array('key'=>'consistency_optimize_tables'),$_smarty_tpl);?>

			</div>
		</li>

		<?php echo smarty_function_ia_hooker(array('name'=>"adminDatabaseConsistency"),$_smarty_tpl);?>


	</ul>

	<div class="consistency-progress">
		<div class="progressbar">
			<div class="progressbar-info">
				<span class="current-info">0</span> / 
				<span class="total-info">100%</span>
			</div>
			<div class="bar"></div>
		</div>
		<div class="consistency-progress-actions">
			<?php echo smarty_function_lang(array('key'=>"limit"),$_smarty_tpl);?>
: <input class="limit" type="text" value="100"> 
			<a href="#" class="run"><?php echo smarty_function_lang(array('key'=>"run"),$_smarty_tpl);?>
</a>
			<a href="#" class="stop hide"><?php echo smarty_function_lang(array('key'=>"stop"),$_smarty_tpl);?>
</a>
		</div>
	</div>

	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php }elseif($_GET['page']=='reset'&&!empty($_smarty_tpl->tpl_vars['reset_options']->value)){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

		<form action="controller.php?file=database&amp;page=reset" method="post">
		<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

		<table width="100%" cellspacing="0" cellpadding="0" class="striped">
		<tr>
			<td width="150"><label for="all_options"><strong><?php echo smarty_function_lang(array('key'=>'reset_all'),$_smarty_tpl);?>
</strong></label></td>
			<td><input type="checkbox" value="all" name="all_options" id="all_options"></td>
		</tr>

		<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['option']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['reset_options']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value){
$_smarty_tpl->tpl_vars['option']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['option']->key;
?>
			<tr>
				<td><label for="option_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['option']->value;?>
<label></td>
				<td><input type="checkbox" id="option_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" name="options[]" value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"></td>
			</tr>
		<?php } ?>

		<tr>
			<td rowspan="2">
				<input type="submit" name="reset" class="common" value="<?php echo smarty_function_lang(array('key'=>'reset'),$_smarty_tpl);?>
">
			</td>
		</tr>
		</table>
		</form>
	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }elseif($_GET['page']=='hook_editor'){?>
	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['hook_editor']), 0);?>


	<table class="striped" width="98%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="200">
			<select id="hook">
				<?php  $_smarty_tpl->tpl_vars['hook'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['hook']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['hooks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['hook']->key => $_smarty_tpl->tpl_vars['hook']->value){
$_smarty_tpl->tpl_vars['hook']->_loop = true;
?>
					<optgroup label="<?php echo $_smarty_tpl->tpl_vars['hook']->value['title'];?>
">
						<?php  $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['code']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['hook']->value['code']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['code']->key => $_smarty_tpl->tpl_vars['code']->value){
$_smarty_tpl->tpl_vars['code']->_loop = true;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['code']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['code']->value['name'];?>
</option>
						<?php } ?>
					</optgroup>
				<?php } ?>
			</select>
		</td>

		<td>
			<input type="button" class="common" id="show" value="Show Code">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<textarea name="code" id="codeContainer" class="common codepress php" cols="10" rows="20"></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" class="common" name="save" id="save" value="Save">
			<input type="submit" class="common" id="close_all" value="Close All">
		</td>
	</tr>
	</table>

	<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


	<?php echo smarty_function_include_file(array('js'=>"js/utils/edit_area/edit_area_full, js/admin/hook_editor"),$_smarty_tpl);?>

<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/admin/database"),$_smarty_tpl);?>


<?php echo smarty_function_ia_hooker(array('name'=>"tplAdminDatabaseBeforeFooter"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>