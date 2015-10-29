<?php /* Smarty version Smarty-3.1.13, created on 2015-03-19 12:13:01
         compiled from "/home/wwwsyaqd/public_html/plugins/comments/admin/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1229200306550af58ddd8fd3-36369526%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9eabbcf1ed26957900b04d8a977dec45a22e6ec8' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/comments/admin/templates/index.tpl',
      1 => 1425025904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1229200306550af58ddd8fd3-36369526',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'esynI18N' => 0,
    'comment' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550af58de5a0a4_69855321',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550af58de5a0a4_69855321')) {function content_550af58de5a0a4_69855321($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<?php if (isset($_GET['do'])&&$_GET['do']=='edit'){?>
	
	<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

	
	<form action="controller.php?plugin=comments&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table cellspacing="0" cellpadding="0" width="100%" class="striped">
	<tr>
		<td width="200"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['author'];?>
:</strong></td>
		<td><input type="text" size="40" name="author" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['comment']->value['author'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['comment']->value['author'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" /></td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['email'];?>
:</strong></td>
		<td><input type="text" size="40" name="email" class="common" value="<?php if (isset($_smarty_tpl->tpl_vars['comment']->value['email'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['comment']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" /></td>
	</tr>
	
	
	
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['body'];?>
:</strong></td>
		<td><textarea name="body" cols="53" rows="8" class="common" id="commentbody"><?php if (isset($_smarty_tpl->tpl_vars['comment']->value['body'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['comment']->value['body'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?></textarea></td>
	</tr>
	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status'];?>
:</strong></td>
		<td>
			<select name="status">
				<option value="inactive" <?php if (isset($_smarty_tpl->tpl_vars['comment']->value['status'])&&$_smarty_tpl->tpl_vars['comment']->value['status']=='inactive'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['inactive'];?>
</option>
				<option value="active" <?php if (isset($_smarty_tpl->tpl_vars['comment']->value['status'])&&$_smarty_tpl->tpl_vars['comment']->value['status']=='active'){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</option>
			</select>
		</td>
	</tr>
	<tr class="all">
		<td colspan="2">
			<input type="submit" name="edit_comments" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
" class="common" />
			<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['comment']->value['id'];?>
" />
            <input type="hidden" name="item_id" value="<?php echo $_smarty_tpl->tpl_vars['comment']->value['item_id'];?>
" />
            <input type="hidden" name="item" value="<?php echo $_smarty_tpl->tpl_vars['comment']->value['item'];?>
" />
		</td>
	</tr>
	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ("box-footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('class'=>"box"), 0);?>

<?php }else{ ?>
	<div id="box_comments" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli.grid, js/ckeditor/ckeditor, js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/comments/js/admin/comments"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>