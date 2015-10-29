<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:31:25
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:72093532355090dad0fca47-23163844%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '12512cd9a107fa142972b49e16beef575527a3f0' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/footer.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '72093532355090dad0fca47-23163844',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'esyn_tips' => 0,
    'tip' => 0,
    'esynI18N' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55090dad118465_69384057',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55090dad118465_69384057')) {function content_55090dad118465_69384057($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?>	</div>
	<!-- right column end -->

	<div style="clear: both;"></div>
</div>
<!-- content end -->

<!-- footer start -->
<div class="footer">
	<div>
		Powered by <a href="http://www.esyndicat.com/" target="_blank">eSyndiCat Pro v<?php echo $_smarty_tpl->tpl_vars['config']->value['version'];?>
</a><br />
		Copyright &copy; <?php echo smarty_modifier_date_format(time(),"%Y");?>
 <a href="http://www.intelliants.com/" target="_blank">Intelliants LLC</a>
	</div>
</div>
<!-- footer end -->

<?php if (isset($_smarty_tpl->tpl_vars['esyn_tips']->value)&&!empty($_smarty_tpl->tpl_vars['esyn_tips']->value)){?>
	<?php  $_smarty_tpl->tpl_vars["tip"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["tip"]->_loop = false;
 $_smarty_tpl->tpl_vars["key"] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['esyn_tips']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["tip"]->key => $_smarty_tpl->tpl_vars["tip"]->value){
$_smarty_tpl->tpl_vars["tip"]->_loop = true;
 $_smarty_tpl->tpl_vars["key"]->value = $_smarty_tpl->tpl_vars["tip"]->key;
?>
		<div style="display: none;"><div id="tip-content-<?php echo $_smarty_tpl->tpl_vars['tip']->value['key'];?>
"><?php echo $_smarty_tpl->tpl_vars['tip']->value['value'];?>
</div></div>
	<?php } ?>
<?php }?>

<div id="ajax-loader"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['loading'];?>
</div>

<?php echo smarty_function_include_file(array('js'=>"js/admin/footer"),$_smarty_tpl);?>


</body>
</html><?php }} ?>