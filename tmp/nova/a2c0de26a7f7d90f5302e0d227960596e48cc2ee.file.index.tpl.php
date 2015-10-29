<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:49:57
         compiled from "/home/wwwsyaqd/public_html/templates/common/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:165746515954f02fb5336180-22198943%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a2c0de26a7f7d90f5302e0d227960596e48cc2ee' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/index.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '165746515954f02fb5336180-22198943',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'category' => 0,
    'config' => 0,
    'categories' => 0,
    'blocks' => 0,
    'listings' => 0,
    'related_categories' => 0,
    'lang' => 0,
    'neighbour_categories' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f02fb54375b2_93652892',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f02fb54375b2_93652892')) {function content_54f02fb54375b2_93652892($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
if (!is_callable('smarty_block_ia_block')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_block.php';
if (!is_callable('smarty_block_ia_add_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_add_js.php';
?><?php if ($_smarty_tpl->tpl_vars['category']->value['confirmation']&&!isset($_COOKIE[('confirm_').($_smarty_tpl->tpl_vars['category']->value['id'])])){?>
	<div class="page-desc"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value['confirmation_text'], ENT_QUOTES, 'UTF-8', true);?>
</div>
	<div>
		<input type="button" class="btn btn-primary" name="confirm_answer" id="continue" value="<?php echo smarty_function_lang(array('key'=>'yes'),$_smarty_tpl);?>
" data-category="<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
"/>
		<input type="button" class="btn" name="confirm_answer" id="back" value="<?php echo smarty_function_lang(array('key'=>'no'),$_smarty_tpl);?>
" />
	</div>
<?php }else{ ?>
	<?php if ($_smarty_tpl->tpl_vars['category']->value['description']){?>
		<div class="page-description"><?php echo $_smarty_tpl->tpl_vars['category']->value['description'];?>
</div>
	<?php }?>

	<?php if (isset($_smarty_tpl->tpl_vars['num_columns'])) {$_smarty_tpl->tpl_vars['num_columns'] = clone $_smarty_tpl->tpl_vars['num_columns'];
$_smarty_tpl->tpl_vars['num_columns']->value = ($_smarty_tpl->tpl_vars['category']->value['num_cols']>0 ? $_smarty_tpl->tpl_vars['category']->value['num_cols'] : $_smarty_tpl->tpl_vars['config']->value['num_categories_cols']); $_smarty_tpl->tpl_vars['num_columns']->nocache = null; $_smarty_tpl->tpl_vars['num_columns']->scope = 0;
} else $_smarty_tpl->tpl_vars['num_columns'] = new Smarty_variable(($_smarty_tpl->tpl_vars['category']->value['num_cols']>0 ? $_smarty_tpl->tpl_vars['category']->value['num_cols'] : $_smarty_tpl->tpl_vars['config']->value['num_categories_cols']), null, 0);?>
	<?php if ($_smarty_tpl->tpl_vars['categories']->value){?>
		<div class="ia-categories">
			<?php echo $_smarty_tpl->getSubTemplate ('ia-categories.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('categories'=>$_smarty_tpl->tpl_vars['categories']->value), 0);?>

		</div>
	<?php }?>

	<div class="js-groupWrapper" data-position="center">
		<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['center'])===null||$tmp==='' ? null : $tmp)), 0);?>

	</div>
	
	<?php echo smarty_function_ia_hooker(array('name'=>'indexBeforeListings'),$_smarty_tpl);?>


	<?php if ($_smarty_tpl->tpl_vars['listings']->value){?>

		<?php echo $_smarty_tpl->getSubTemplate ('ia-listings.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listings'=>$_smarty_tpl->tpl_vars['listings']->value,'sorting'=>true), 0);?>


		<?php echo smarty_function_ia_print_js(array('files'=>'js/intelli/intelli.tree'),$_smarty_tpl);?>

	<?php }?>

	<?php if (isset($_smarty_tpl->tpl_vars['related_categories']->value)&&!empty($_smarty_tpl->tpl_vars['related_categories']->value)){?>
		<!-- related categories box start -->
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_block', array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['related_categories'])); $_block_repeat=true; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['related_categories']), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

			<div class="ia-categories">
				<?php echo $_smarty_tpl->getSubTemplate ('ia-categories.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('categories'=>$_smarty_tpl->tpl_vars['related_categories']->value), 0);?>

			</div>
		<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['related_categories']), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

	<?php }?>

	<?php if (isset($_smarty_tpl->tpl_vars['neighbour_categories']->value)&&!empty($_smarty_tpl->tpl_vars['neighbour_categories']->value)){?>
		<!-- neighbour categories box start -->
		<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_block', array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['neighbour_categories'])); $_block_repeat=true; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['neighbour_categories']), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

			<div class="ia-categories">
				<?php echo $_smarty_tpl->getSubTemplate ('ia-categories.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('categories'=>$_smarty_tpl->tpl_vars['neighbour_categories']->value), 0);?>

			</div>
		<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['neighbour_categories']), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

		<!-- neighbour categories box end -->
	<?php }?>
<?php }?>

<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_add_js', array()); $_block_repeat=true; echo smarty_block_ia_add_js(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

$(function()
{
	$('#back').click(function()
	{
		history.back(1);
		return false;
	});

	$('input[name="confirm_answer"]').click(function()
	{
		intelli.createCookie('confirm_' + $(this).data('category'), '1');
		window.location = window.location.href;
	});
});
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_add_js(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<?php echo smarty_function_ia_hooker(array('name'=>'indexBeforeFooter'),$_smarty_tpl);?>
<?php }} ?>