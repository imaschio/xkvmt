<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:52
         compiled from "/home/wwwsyaqd/public_html/plugins/listings_boxes/templates/block.related-listings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:731375536550914d076e177-85888305%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '79e49f51bdb38eae6df05394f9f5306cd3d30c9d' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/listings_boxes/templates/block.related-listings.tpl',
      1 => 1425025916,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '731375536550914d076e177-85888305',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'box_listings' => 0,
    'related_listing' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d077efe2_52860109',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d077efe2_52860109')) {function content_550914d077efe2_52860109($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
?><?php if (isset($_smarty_tpl->tpl_vars['box_listings']->value['related'])&&!empty($_smarty_tpl->tpl_vars['box_listings']->value['related'])){?>
	<?php  $_smarty_tpl->tpl_vars['related_listing'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['related_listing']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['box_listings']->value['related']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['related_listing']->key => $_smarty_tpl->tpl_vars['related_listing']->value){
$_smarty_tpl->tpl_vars['related_listing']->_loop = true;
?>
		<div class="ia-item list">
			<a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['related_listing']->value),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['related_listing']->value['title'];?>
</a>
		</div>
	<?php } ?>
<?php }?><?php }} ?>