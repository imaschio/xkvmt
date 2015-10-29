<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 09:35:09
         compiled from "/home/wwwsyaqd/public_html/templates/common/view-account.tpl" */ ?>
<?php /*%%SmartyHeaderCode:153560730655097f0d462076-87553645%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0bf37909a2e9799660a1f7467883b31ba324abd4' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/view-account.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '153560730655097f0d462076-87553645',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'account' => 0,
    'config' => 0,
    'listings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55097f0d4c3458_36067689',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55097f0d4c3458_36067689')) {function content_55097f0d4c3458_36067689($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
?><div class="media ia-item clearfix">
	<div class="avatar pull-left">
		<?php if (isset($_smarty_tpl->tpl_vars['account']->value['avatar'])&&!empty($_smarty_tpl->tpl_vars['account']->value['avatar'])){?>
			<?php echo smarty_function_print_img(array('ups'=>true,'fl'=>$_smarty_tpl->tpl_vars['account']->value['avatar'],'full'=>true,'title'=>$_smarty_tpl->tpl_vars['account']->value['username'],'class'=>'avatar'),$_smarty_tpl);?>

		<?php }else{ ?>
			<?php echo smarty_function_print_img(array('fl'=>'no-avatar.png','full'=>true,'title'=>$_smarty_tpl->tpl_vars['account']->value['username'],'class'=>'avatar'),$_smarty_tpl);?>

		<?php }?>
	</div>
	<div class="media-body">
		<p><?php echo smarty_function_lang(array('key'=>'username'),$_smarty_tpl);?>
: <?php echo $_smarty_tpl->tpl_vars['account']->value['username'];?>
</p>
		<p><?php echo smarty_function_lang(array('key'=>'date_registration'),$_smarty_tpl);?>
: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['account']->value['date_reg'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
</p>
	</div>
</div>
<hr>
<?php if (isset($_smarty_tpl->tpl_vars['listings']->value)&&!empty($_smarty_tpl->tpl_vars['listings']->value)){?>
	<?php echo $_smarty_tpl->getSubTemplate ('ia-listings.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listings'=>$_smarty_tpl->tpl_vars['listings']->value), 0);?>

<?php }else{ ?>
	<div class="alert alert-info"><?php echo smarty_function_lang(array('key'=>'no_account_listings'),$_smarty_tpl);?>
</div>
<?php }?><?php }} ?>