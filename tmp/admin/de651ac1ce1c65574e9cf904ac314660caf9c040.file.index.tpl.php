<?php /* Smarty version Smarty-3.1.13, created on 2015-05-28 08:21:23
         compiled from "/home/wwwsyaqd/public_html/plugins/spider/admin/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:176398978755670843a0a026-05890997%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'de651ac1ce1c65574e9cf904ac314660caf9c040' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/spider/admin/templates/index.tpl',
      1 => 1425025908,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '176398978755670843a0a026-05890997',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error' => 0,
    'gTitle' => 0,
    'esynI18N' => 0,
    'result' => 0,
    'curr_page' => 0,
    'data' => 0,
    'key' => 0,
    'categories' => 0,
    'statuses' => 0,
    'status' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55670843b67266_50139879',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55670843b67266_50139879')) {function content_55670843b67266_50139879($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php if (!$_smarty_tpl->tpl_vars['error']->value){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	<form method="post" action="controller.php?plugin=spider&amp;action=get" id="search_form">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

		<table class="striped" width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['search_in'];?>
:</strong></td>
				<td>
					<select name="engine" id="engine">
						<option value="google" <?php if (isset($_POST['engine'])&&$_POST['engine']=='google'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['google'];?>
</option>
						
					</select>
				</td>
			</tr>
			<tr>
				<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['keywords'];?>
:</strong></td>
				<td><input type="text" name="keywords" size="50" class="common" value="<?php if (isset($_POST['keywords'])){?><?php echo $_POST['keywords'];?>
<?php }?>" /></td>
			</tr>
			<tr class="number_of_results">
				<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['number_of_links'];?>
:</strong></td>
				<td>
					<select name="count" id="search_numbers">
						<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']["count"])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]);
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['name'] = "count";
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start'] = (int)10;
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['loop'] = is_array($_loop=60) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['step'] = ((int)10) == 0 ? 1 : (int)10;
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']["count"]['total']);
?>
							<option value="<?php echo $_smarty_tpl->getVariable('smarty')->value['section']['count']['index'];?>
" <?php if (isset($_POST['count'])&&$_smarty_tpl->getVariable('smarty')->value['section']['count']['index']==$_POST['count']){?>selected="selected" <?php }?>><?php echo $_smarty_tpl->getVariable('smarty')->value['section']['count']['index'];?>
</option>
						<?php endfor; endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="hidden" name="start" value="<?php if (isset($_smarty_tpl->tpl_vars['result']->value['start'])){?><?php echo $_smarty_tpl->tpl_vars['result']->value['start'];?>
<?php }else{ ?>0<?php }?>" id="search_start" />
					<input type="submit" name="continue" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['continue'];?>
" class="common" id="continue" />
				</td>
			</tr>
		</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ("box-footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('class'=>"box"), 0);?>

<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['result']->value)&&!empty($_smarty_tpl->tpl_vars['result']->value)){?>
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>


	<div class="num_results">
		<div style="float:left;font-weight:bold;line-height:30px;">
			<?php echo (((($_smarty_tpl->tpl_vars['esynI18N']->value['about']).("&nbsp;")).($_smarty_tpl->tpl_vars['result']->value['num_results'])).("&nbsp;")).($_smarty_tpl->tpl_vars['esynI18N']->value['found']);?>

		</div>
		<div style="float:left;position:absolute;left:39%;">
			<?php if ($_smarty_tpl->tpl_vars['result']->value['start']>0){?>
				<div style="float:left;padding-right:5px;">
					<input type="button" name="prev" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['prev'];?>
" class="common" id="search_prev" />
				</div>
			<?php }?>
			<div style="float:left;text-align:center;font-weight:bold;line-height:30px;">
				<?php if (isset($_smarty_tpl->tpl_vars["curr_page"])) {$_smarty_tpl->tpl_vars["curr_page"] = clone $_smarty_tpl->tpl_vars["curr_page"];
$_smarty_tpl->tpl_vars["curr_page"]->value = $_smarty_tpl->tpl_vars['result']->value['start']+1; $_smarty_tpl->tpl_vars["curr_page"]->nocache = null; $_smarty_tpl->tpl_vars["curr_page"]->scope = 0;
} else $_smarty_tpl->tpl_vars["curr_page"] = new Smarty_variable($_smarty_tpl->tpl_vars['result']->value['start']+1, null, 0);?>
				<?php echo ($_smarty_tpl->tpl_vars['esynI18N']->value['page']).(":&nbsp;");?>
<?php echo $_smarty_tpl->tpl_vars['curr_page']->value;?>

			</div>
			<?php if ($_smarty_tpl->tpl_vars['result']->value['all_pages']!=$_smarty_tpl->tpl_vars['result']->value['start']+1){?>
				<div style="float:left;padding-left:5px;">
					<input type="button" name="next" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['next'];?>
" class="common" id="search_next" />
				</div>
			<?php }?>
		</div>
		<div style="clear:both;font-size:0;">&nbsp;</div>
	</div>

	<div style="height: 600px; overflow: scroll; margin-top:20px;">
		<form method="post" action="controller.php?plugin=spider&amp;action=add" id="result">
			<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

			<table class="striped" width="100%" cellpadding="4" cellspacing="0">
				<tr>
					<th>&nbsp;</th>
					<th><center><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['url'];?>
</b></center></th>
					<th><center><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['title'];?>
</b></center></th>
					<th><center><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['description'];?>
</b></center></th>
					<th><center><b><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['category'];?>
</b></center></th>
				</tr>
				<?php  $_smarty_tpl->tpl_vars['data'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['data']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['result']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['data']->key => $_smarty_tpl->tpl_vars['data']->value){
$_smarty_tpl->tpl_vars['data']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['data']->key;
?>
					<?php if (is_array($_smarty_tpl->tpl_vars['data']->value)){?>
						<tr>
							<td width="1%" style="vertical-align: top;"><input type="checkbox" name="index[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" checked="checked" class="common" /></td>
							<td width="19%" style="vertical-align: top;"><input type="text" name="urls[]" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
" class="common" size="35" /><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['data']->value['url'], ENT_QUOTES, 'UTF-8', true);?>
" target="_blank"/><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['visit_this_site'];?>
</a></td>
							<td width="20%"><textarea name="titles[]" rows="3" cols="5" class="common"><?php echo $_smarty_tpl->tpl_vars['data']->value['title'];?>
</textarea></td>
							<td width="40%"><textarea name="descriptions[]" rows="3" cols="5" class="common"><?php echo $_smarty_tpl->tpl_vars['data']->value['description'];?>
</textarea></td>
							<td width="20%" style="vertical-align: top;"><select name="categories[]" class="common"><?php echo $_smarty_tpl->tpl_vars['categories']->value;?>
</select></td>
						</tr>
					<?php }?>
				<?php } ?>
				<tr>
					<td><?php echo smarty_function_print_img(array('fl'=>'arrow_ltr.png','pl'=>'spider','admin'=>'true','alt'=>'$esynI18N.arrow','full'=>'true'),$_smarty_tpl);?>
</td>
					<td style="vertical-align: center;">
						<a id="check_all" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['check_all'];?>
</a>&nbsp;/&nbsp;
						<a id="uncheck_all" href="#"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['uncheck_all'];?>
</a>
					</td>
				</tr>
				<tr>
					<td colspan="5" style="vertical-align: center;">
						<input type="checkbox" name="one_import" />&nbsp;
						<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['import_to_one_cat'];?>
:&nbsp;
						<select name="one_category" class="common"><?php echo $_smarty_tpl->tpl_vars['categories']->value;?>
</select>&nbsp;
						<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['with_status'];?>
&nbsp;
						<select name="status" class="common">
						<?php  $_smarty_tpl->tpl_vars['status'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['status']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['statuses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['status']->key => $_smarty_tpl->tpl_vars['status']->value){
$_smarty_tpl->tpl_vars['status']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['status']->key;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['status']->value;?>
</option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="4"><input type="submit" class="common" name="import" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['import_selected_links'];?>
" /></td>
				</tr>
			</table>
		</form>
	</div>
	<?php echo $_smarty_tpl->getSubTemplate ("box-footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('class'=>"box"), 0);?>

<?php }?>

<?php if (!$_smarty_tpl->tpl_vars['error']->value){?>
	<?php echo smarty_function_include_file(array('js'=>"plugins/spider/js/admin/spider"),$_smarty_tpl);?>

<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>