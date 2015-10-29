<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:54
         compiled from "/home/wwwsyaqd/public_html/plugins/listings_boxes/templates/block.new-listings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1912277089550914d23b4c71-85475047%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ba2f5d22106bdff556da6ddcdc1c423a69aadb8c' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/listings_boxes/templates/block.new-listings.tpl',
      1 => 1425025916,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1912277089550914d23b4c71-85475047',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'box_listings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d23bef61_98217360',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d23bef61_98217360')) {function content_550914d23bef61_98217360($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><?php if ($_smarty_tpl->tpl_vars['box_listings']->value['new']){?>
	<h2><?php echo smarty_function_lang(array('key'=>'new_listings'),$_smarty_tpl);?>
</h2>

	<?php echo $_smarty_tpl->getSubTemplate ('ia-listings.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listings'=>$_smarty_tpl->tpl_vars['box_listings']->value['new']), 0);?>

	<?php echo smarty_function_ia_print_js(array('files'=>'js/intelli/intelli.tree'),$_smarty_tpl);?>

<?php }?><?php }} ?>