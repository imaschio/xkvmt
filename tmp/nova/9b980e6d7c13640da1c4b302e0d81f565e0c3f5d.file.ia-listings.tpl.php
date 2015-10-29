<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 04:29:21
         compiled from "/home/wwwsyaqd/public_html/templates/common/ia-listings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:161874028154f038f1379883-77018837%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9b980e6d7c13640da1c4b302e0d81f565e0c3f5d' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/ia-listings.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '161874028154f038f1379883-77018837',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'num_display_listings' => 0,
    'config' => 0,
    'total_listings' => 0,
    'url' => 0,
    'num_listings_per_page' => 0,
    'sorting' => 0,
    'type' => 0,
    'listings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f038f142e471_58708055',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f038f142e471_58708055')) {function content_54f038f142e471_58708055($_smarty_tpl) {?><?php if (!is_callable('smarty_function_navigation')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.navigation.php';
?><!-- listings box start -->
<div class="ia-listings">
	<?php if (isset($_smarty_tpl->tpl_vars['num_display_listings']->value)){?>
		<?php if (isset($_smarty_tpl->tpl_vars['num_listings_per_page'])) {$_smarty_tpl->tpl_vars['num_listings_per_page'] = clone $_smarty_tpl->tpl_vars['num_listings_per_page'];
$_smarty_tpl->tpl_vars['num_listings_per_page']->value = ((string)$_smarty_tpl->tpl_vars['num_display_listings']->value); $_smarty_tpl->tpl_vars['num_listings_per_page']->nocache = null; $_smarty_tpl->tpl_vars['num_listings_per_page']->scope = 0;
} else $_smarty_tpl->tpl_vars['num_listings_per_page'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['num_display_listings']->value), null, 0);?>
	<?php }else{ ?>
		<?php if (isset($_smarty_tpl->tpl_vars['num_listings_per_page'])) {$_smarty_tpl->tpl_vars['num_listings_per_page'] = clone $_smarty_tpl->tpl_vars['num_listings_per_page'];
$_smarty_tpl->tpl_vars['num_listings_per_page']->value = ((string)$_smarty_tpl->tpl_vars['config']->value['num_index_listings']); $_smarty_tpl->tpl_vars['num_listings_per_page']->nocache = null; $_smarty_tpl->tpl_vars['num_listings_per_page']->scope = 0;
} else $_smarty_tpl->tpl_vars['num_listings_per_page'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['config']->value['num_index_listings']), null, 0);?>
	<?php }?>

	<?php echo smarty_function_navigation(array('aTotal'=>$_smarty_tpl->tpl_vars['total_listings']->value,'aTemplate'=>$_smarty_tpl->tpl_vars['url']->value,'aItemsPerPage'=>$_smarty_tpl->tpl_vars['num_listings_per_page']->value,'aNumPageItems'=>5,'aTruncateParam'=>1,'sorting'=>(($tmp = @$_smarty_tpl->tpl_vars['sorting']->value)===null||$tmp==='' ? false : $tmp)),$_smarty_tpl);?>


	<?php if (isset($_smarty_tpl->tpl_vars['type'])) {$_smarty_tpl->tpl_vars['type'] = clone $_smarty_tpl->tpl_vars['type'];
$_smarty_tpl->tpl_vars['type']->value = (($tmp = @$_COOKIE['listing_display_type'])===null||$tmp==='' ? 'list' : $tmp); $_smarty_tpl->tpl_vars['type']->nocache = null; $_smarty_tpl->tpl_vars['type']->scope = 0;
} else $_smarty_tpl->tpl_vars['type'] = new Smarty_variable((($tmp = @$_COOKIE['listing_display_type'])===null||$tmp==='' ? 'list' : $tmp), null, 0);?>
	<?php if ($_smarty_tpl->tpl_vars['type']->value=='tile'){?><ul class="thumbnails"><?php }?>
	<?php  $_smarty_tpl->tpl_vars['listing'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['listing']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listings']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['listing']->key => $_smarty_tpl->tpl_vars['listing']->value){
$_smarty_tpl->tpl_vars['listing']->_loop = true;
?>
		<?php echo $_smarty_tpl->getSubTemplate ("listing-display-".((string)$_smarty_tpl->tpl_vars['type']->value).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<?php } ?>
	<?php if ($_smarty_tpl->tpl_vars['type']->value=='tile'){?></ul><?php }?>

	<?php echo smarty_function_navigation(array('aTotal'=>$_smarty_tpl->tpl_vars['total_listings']->value,'aTemplate'=>$_smarty_tpl->tpl_vars['url']->value,'aItemsPerPage'=>$_smarty_tpl->tpl_vars['num_listings_per_page']->value,'aNumPageItems'=>5,'aTruncateParam'=>1,'sorting'=>(($tmp = @$_smarty_tpl->tpl_vars['sorting']->value)===null||$tmp==='' ? false : $tmp)),$_smarty_tpl);?>

</div>
<!-- listings box end --><?php }} ?>