<?php /* Smarty version Smarty-3.1.13, created on 2015-10-24 01:57:15
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/templates.tpl" */ ?>
<?php /*%%SmartyHeaderCode:271903276562b1dbba11329-98919890%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c769eecfb5b3f4449dd0bbb997d3e4b40198b51b' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/templates.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '271903276562b1dbba11329-98919890',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'templates' => 0,
    'template' => 0,
    'tmpl' => 0,
    'screenshot' => 0,
    'esynI18N' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_562b1dbbac1055_22219159',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_562b1dbbac1055_22219159')) {function content_562b1dbbac1055_22219159($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>((string)@constant('IA_URL'))."js/jquery/plugins/lightbox/css/jquery.lightbox"), 0);?>


<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	<?php  $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['template']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['templates']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['template']->key => $_smarty_tpl->tpl_vars['template']->value){
$_smarty_tpl->tpl_vars['template']->_loop = true;
?>
		<div class="tpls-plate<?php if ($_smarty_tpl->tpl_vars['template']->value['name']==$_smarty_tpl->tpl_vars['tmpl']->value){?> tpls-plate--active<?php }?>">
			<div class="tpls-plate__image">
				<?php if (isset($_smarty_tpl->tpl_vars['template']->value['local'])){?>
					<a href="#" class="screenshots"><img src="<?php echo @constant('IA_URL');?>
templates/<?php echo $_smarty_tpl->tpl_vars['template']->value['name'];?>
/info/preview.jpg" title="<?php echo $_smarty_tpl->tpl_vars['template']->value['title'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['template']->value['title'];?>
"></a>
					<?php if (isset($_smarty_tpl->tpl_vars['template']->value['screenshots'])&&!empty($_smarty_tpl->tpl_vars['template']->value['screenshots'])){?>
						<?php  $_smarty_tpl->tpl_vars['screenshot'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['screenshot']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['template']->value['screenshots']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['screenshot']->key => $_smarty_tpl->tpl_vars['screenshot']->value){
$_smarty_tpl->tpl_vars['screenshot']->_loop = true;
?>
							<a class="lb" href="<?php echo @constant('IA_URL');?>
templates/<?php echo $_smarty_tpl->tpl_vars['template']->value['name'];?>
/info/screenshots/<?php echo $_smarty_tpl->tpl_vars['screenshot']->value;?>
" style="display: none;"><img src="<?php echo @constant('IA_URL');?>
templates/<?php echo $_smarty_tpl->tpl_vars['template']->value['name'];?>
/info/screenshots/<?php echo $_smarty_tpl->tpl_vars['screenshot']->value;?>
"></a>
						<?php } ?>
					<?php }?>
				<?php }else{ ?>
					<img src="<?php echo $_smarty_tpl->tpl_vars['template']->value['logo'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['template']->value['title'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['template']->value['title'];?>
">
				<?php }?>
			</div>
			<div class="tpls-plate__info">
				<p>
					<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['name'];?>
:&nbsp;<strong><?php echo $_smarty_tpl->tpl_vars['template']->value['title'];?>
</strong><br />
					<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['author'];?>
:&nbsp;<strong><?php echo $_smarty_tpl->tpl_vars['template']->value['author'];?>
</strong><br />
					<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['contributor'];?>
:&nbsp;<strong><?php echo $_smarty_tpl->tpl_vars['template']->value['contributor'];?>
</strong><br />
					<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['release_date'];?>
:&nbsp;<strong><?php echo $_smarty_tpl->tpl_vars['template']->value['date'];?>
</strong><br />
					<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['esyndicat_version'];?>
:&nbsp;<strong><?php echo $_smarty_tpl->tpl_vars['template']->value['compatibility'];?>
</strong>
				</p>
			</div>
			<div class="tpls-plate__actions">
				<form method="post">
					<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

					<input type="hidden" name="template" value="<?php echo $_smarty_tpl->tpl_vars['template']->value['name'];?>
">
					<?php if (isset($_smarty_tpl->tpl_vars['template']->value['local'])){?>
						<?php if ($_smarty_tpl->tpl_vars['template']->value['name']!=$_smarty_tpl->tpl_vars['tmpl']->value){?>
							<input type="submit" name="set_template" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['set_default'];?>
" class="common">
						<?php }?>
						<a href="<?php echo @constant('IA_URL');?>
<?php if ($_smarty_tpl->tpl_vars['template']->value['name']!=$_smarty_tpl->tpl_vars['tmpl']->value){?>?preview=<?php echo $_smarty_tpl->tpl_vars['template']->value['name'];?>
<?php }?>" target="_blank"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['preview'];?>
</a>
					<?php }else{ ?>
						<input type="submit" name="download_template" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['download'];?>
" class="common">
						<a href="<?php echo $_smarty_tpl->tpl_vars['template']->value['url'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['details'];?>
</a>
					<?php }?>
				</form>
			</div>
		</div>
	<?php } ?>
<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo smarty_function_include_file(array('js'=>'js/jquery/plugins/lightbox/jquery.lightbox, js/admin/templates'),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>