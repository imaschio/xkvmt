<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 05:18:45
         compiled from "/home/wwwsyaqd/public_html/templates/common/listings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:188459429550942f569a709-47733046%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9bf9c3e4358866f3304e79ce993ae02ea83de328' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/listings.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '188459429550942f569a709-47733046',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listings' => 0,
    'view' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550942f56eb238_92318111',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550942f56eb238_92318111')) {function content_550942f56eb238_92318111($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><?php if ($_smarty_tpl->tpl_vars['listings']->value){?>
	<?php echo $_smarty_tpl->getSubTemplate ('ia-listings.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listings'=>$_smarty_tpl->tpl_vars['listings']->value), 0);?>

<?php }else{ ?>
	<div class="alert alert-info">
	<?php if ('favorites'==$_smarty_tpl->tpl_vars['view']->value){?>
		<?php echo smarty_function_lang(array('key'=>'no_favorites'),$_smarty_tpl);?>

	<?php }else{ ?>
		<?php echo smarty_function_lang(array('key'=>'no_listings'),$_smarty_tpl);?>

	<?php }?>
	</div>
<?php }?><?php }} ?>