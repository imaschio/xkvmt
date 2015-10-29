<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:49:57
         compiled from "/home/wwwsyaqd/public_html/templates/common/block.search_filter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:59875279654f02fb5b672f5-24014908%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd010a77fafe2cef813885bfcd50653ba586de002' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/block.search_filter.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '59875279654f02fb5b672f5-24014908',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'field_groups' => 0,
    'showFilters' => 0,
    'group' => 0,
    'field' => 0,
    'maxFilterNum' => 0,
    'fileFields' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f02fb5bd5029_44018287',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f02fb5bd5029_44018287')) {function content_54f02fb5bd5029_44018287($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_html_checkboxes')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_html_checkboxes.php';
if (!is_callable('smarty_block_ia_add_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_add_js.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><?php if (isset($_smarty_tpl->tpl_vars['field_groups']->value)&&$_smarty_tpl->tpl_vars['field_groups']->value&&isset($_smarty_tpl->tpl_vars['showFilters']->value)){?>
	<?php if (isset($_smarty_tpl->tpl_vars['maxFilterNum'])) {$_smarty_tpl->tpl_vars['maxFilterNum'] = clone $_smarty_tpl->tpl_vars['maxFilterNum'];
$_smarty_tpl->tpl_vars['maxFilterNum']->value = 5; $_smarty_tpl->tpl_vars['maxFilterNum']->nocache = null; $_smarty_tpl->tpl_vars['maxFilterNum']->scope = 0;
} else $_smarty_tpl->tpl_vars['maxFilterNum'] = new Smarty_variable(5, null, 0);?>

	<form action="" method="post" id="advsearch" class="ia-form filter">
		<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['field_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['group']->key;
?>
			<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['group']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
				<div class="filter__group">
					<?php if (in_array($_smarty_tpl->tpl_vars['field']->value['type'],array('checkbox','combo','radio'))&&'checkbox'==$_smarty_tpl->tpl_vars['field']->value['show_as']){?>
						<h5 class="filter__group__title"><?php echo smarty_function_lang(array('key'=>"field_".((string)$_smarty_tpl->tpl_vars['field']->value['name'])),$_smarty_tpl);?>
 <span class="text-right"><a class="filter__group__reset" href="#" data-field="<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
">x</a></span></h5>
						<div class="filter__group__content">
							<?php echo smarty_function_ia_html_checkboxes(array('name'=>$_smarty_tpl->tpl_vars['field']->value['name'],'options'=>$_smarty_tpl->tpl_vars['field']->value['values'],'checked'=>$_smarty_tpl->tpl_vars['field']->value['checked'],'grouping'=>5),$_smarty_tpl);?>

							<div class="filter__group__actions text-right">
								<?php if (count($_smarty_tpl->tpl_vars['field']->value['values'])>$_smarty_tpl->tpl_vars['maxFilterNum']->value){?>
									<a class="filter__group__all" href="#" style="float: none;"><?php echo smarty_function_lang(array('key'=>'show_more'),$_smarty_tpl);?>
</a>
								<?php }?>
							</div>
						</div>
					<?php }elseif(in_array($_smarty_tpl->tpl_vars['field']->value['type'],array('storage','image'))){?>
						<h5 class="filter__group__title"><?php echo smarty_function_lang(array('key'=>'contains'),$_smarty_tpl);?>
 "<?php echo smarty_function_lang(array('key'=>"field_".((string)$_smarty_tpl->tpl_vars['field']->value['name'])),$_smarty_tpl);?>
" <span class="text-right"><a class="filter__group__reset" href="#" data-field="<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
">x</a></span></h5>
						<div class="filter__group__content">
							<label class="radio inline">
								<input class="storage" type="radio" id="hasFile<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
[has]" value="y" <?php if ($_smarty_tpl->tpl_vars['fileFields']->value[$_smarty_tpl->tpl_vars['field']->value['name']]=='y'){?>checked<?php }?>> <?php echo smarty_function_lang(array('key'=>'yes'),$_smarty_tpl);?>

							</label>
							<label class="radio inline">
								<input class="storage" type="radio" id="doesntHaveFile<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['field']->value['name'];?>
[has]" value="n" <?php if ($_smarty_tpl->tpl_vars['fileFields']->value[$_smarty_tpl->tpl_vars['field']->value['name']]=='n'){?>checked<?php }?>> <?php echo smarty_function_lang(array('key'=>'no'),$_smarty_tpl);?>

							</label>
						</div>
					<?php }?>
				</div>
			<?php } ?>
		<?php } ?>

		<div class="filter__actions">
			<button type="reset" class="btn btn-small pull-right"><?php echo smarty_function_lang(array('key'=>'reset_filters'),$_smarty_tpl);?>
</button>
			<button type="submit" class="btn btn-primary btn-small pull-left"><?php echo smarty_function_lang(array('key'=>'apply_filters'),$_smarty_tpl);?>
</button>
		</div>
	</form>

	<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_add_js', array()); $_block_repeat=true; echo smarty_block_ia_add_js(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

var maxFilterNum = <?php echo $_smarty_tpl->tpl_vars['maxFilterNum']->value;?>
;
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_add_js(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>


	<?php echo smarty_function_ia_print_js(array('files'=>'js/frontend/search_filters'),$_smarty_tpl);?>

<?php }?><?php }} ?>