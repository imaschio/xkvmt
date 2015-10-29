<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:51:12
         compiled from "/home/wwwsyaqd/public_html/templates/common/accounts.tpl" */ ?>
<?php /*%%SmartyHeaderCode:186992541454f03000d62714-17330311%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3cf4609aae6614dfe46d1999aac685ec8c02d55c' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/accounts.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '186992541454f03000d62714-17330311',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'search_alphas' => 0,
    'onealpha' => 0,
    'alpha' => 0,
    'accounts' => 0,
    'total_accounts' => 0,
    'url' => 0,
    'config' => 0,
    'account' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f03000dda482_73312460',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f03000dda482_73312460')) {function content_54f03000dda482_73312460($_smarty_tpl) {?><?php if (!is_callable('smarty_function_navigation')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.navigation.php';
if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
?><?php if ($_smarty_tpl->tpl_vars['search_alphas']->value){?>
	<div class="text-center">
		<div class="alphabetic-search btn-group">
			<?php  $_smarty_tpl->tpl_vars['onealpha'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['onealpha']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['search_alphas']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['onealpha']->key => $_smarty_tpl->tpl_vars['onealpha']->value){
$_smarty_tpl->tpl_vars['onealpha']->_loop = true;
?>
				<?php if ($_smarty_tpl->tpl_vars['onealpha']->value==$_smarty_tpl->tpl_vars['alpha']->value){?>
					<a class="btn btn-mini disabled"><?php echo $_smarty_tpl->tpl_vars['onealpha']->value;?>
</a>
				<?php }else{ ?>
					<a href="<?php echo @constant('IA_URL');?>
accounts/<?php echo $_smarty_tpl->tpl_vars['onealpha']->value;?>
/" class="btn btn-mini"><?php echo $_smarty_tpl->tpl_vars['onealpha']->value;?>
</a>
				<?php }?>
			<?php } ?>
		</div>
	</div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['accounts']->value){?>
	<?php echo smarty_function_navigation(array('aTotal'=>$_smarty_tpl->tpl_vars['total_accounts']->value,'aTemplate'=>$_smarty_tpl->tpl_vars['url']->value,'aItemsPerPage'=>$_smarty_tpl->tpl_vars['config']->value['num_get_accounts'],'aNumPageItems'=>5,'aTruncateParam'=>0,'notiles'=>true),$_smarty_tpl);?>


	<ul class="unstyled">
		<?php  $_smarty_tpl->tpl_vars['account'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['account']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['accounts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['account']->key => $_smarty_tpl->tpl_vars['account']->value){
$_smarty_tpl->tpl_vars['account']->_loop = true;
?>
			<li>
				<div class="media ia-item bordered">
					<a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_account_url'][0][0]->printAccountUrl(array('account'=>$_smarty_tpl->tpl_vars['account']->value),$_smarty_tpl);?>
" class="media-object pull-left">
						<?php if (isset($_smarty_tpl->tpl_vars['account']->value['avatar'])&&!empty($_smarty_tpl->tpl_vars['account']->value['avatar'])){?>
							<?php echo smarty_function_print_img(array('ups'=>true,'fl'=>$_smarty_tpl->tpl_vars['account']->value['avatar'],'full'=>true,'title'=>$_smarty_tpl->tpl_vars['account']->value['username'],'class'=>'avatar'),$_smarty_tpl);?>

						<?php }else{ ?>
							<?php echo smarty_function_print_img(array('fl'=>'no-avatar.png','full'=>true,'class'=>'avatar'),$_smarty_tpl);?>

						<?php }?>
					</a>
					<div class="media-body">
						<p><?php echo smarty_function_lang(array('key'=>'username'),$_smarty_tpl);?>
: <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_account_url'][0][0]->printAccountUrl(array('account'=>$_smarty_tpl->tpl_vars['account']->value),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['account']->value['username'];?>
</a></p>
						<p><?php echo smarty_function_lang(array('key'=>'date_registration'),$_smarty_tpl);?>
: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['account']->value['date_reg'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
</p>
					</div>
				</div>
			</li>
		<?php } ?>
	</ul>

	<?php echo smarty_function_navigation(array('aTotal'=>$_smarty_tpl->tpl_vars['total_accounts']->value,'aTemplate'=>$_smarty_tpl->tpl_vars['url']->value,'aItemsPerPage'=>$_smarty_tpl->tpl_vars['config']->value['num_get_accounts'],'aNumPageItems'=>5,'aTruncateParam'=>0,'notiles'=>true),$_smarty_tpl);?>

<?php }else{ ?>
	<div class="alert alert-info"><?php echo smarty_function_lang(array('key'=>'no_accounts'),$_smarty_tpl);?>
</div>
<?php }?><?php }} ?>