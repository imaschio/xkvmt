<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 07:30:05
         compiled from "/home/wwwsyaqd/public_html/templates/common/edit-account.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1992746802550961bd9ef6d0-52635915%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ab6147badf90f6df61bf012ae6c5baf279e29561' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/edit-account.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1992746802550961bd9ef6d0-52635915',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
    'esynAccountInfo' => 0,
    'file_path' => 0,
    'plans' => 0,
    'plan' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550961bdac2038_93439043',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550961bdac2038_93439043')) {function content_550961bdac2038_93439043($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
?><ul class="nav nav-tabs">
	<li class="tab_edit active"><a href="#tab-pane_edit" data-toggle="tab"><?php echo smarty_function_lang(array('key'=>'edit_account'),$_smarty_tpl);?>
</a></li>
	<li class="tab_password"><a href="#tab-pane_password" data-toggle="tab"><?php echo smarty_function_lang(array('key'=>'change_password'),$_smarty_tpl);?>
</a></li>
	<?php if ($_smarty_tpl->tpl_vars['config']->value['allow_delete_accounts']){?>
		<li class="tab_delete"><a href="#tab-pane_delete" data-toggle="tab"><?php echo smarty_function_lang(array('key'=>'delete_account'),$_smarty_tpl);?>
</a></li>
	<?php }?>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="tab-pane_edit">
		<div class="ia-wrap">
			<form action="<?php echo @constant('IA_URL');?>
edit-account.php" method="post" enctype="multipart/form-data" class="ia-form">
				<label for="email"><?php echo smarty_function_lang(array('key'=>'email'),$_smarty_tpl);?>
</label>
				<input type="text" class="span3" name="email" id="email" value="<?php echo (($tmp = @$_POST['email'])===null||$tmp==='' ? $_smarty_tpl->tpl_vars['esynAccountInfo']->value['email'] : $tmp);?>
">

				<label><?php echo smarty_function_lang(array('key'=>'avatar'),$_smarty_tpl);?>
</label>
				<?php if (isset($_smarty_tpl->tpl_vars['file_path'])) {$_smarty_tpl->tpl_vars['file_path'] = clone $_smarty_tpl->tpl_vars['file_path'];
$_smarty_tpl->tpl_vars['file_path']->value = ((string)@constant('IA_UPLOADS')).((string)$_smarty_tpl->tpl_vars['esynAccountInfo']->value['avatar']); $_smarty_tpl->tpl_vars['file_path']->nocache = null; $_smarty_tpl->tpl_vars['file_path']->scope = 0;
} else $_smarty_tpl->tpl_vars['file_path'] = new Smarty_variable(((string)@constant('IA_UPLOADS')).((string)$_smarty_tpl->tpl_vars['esynAccountInfo']->value['avatar']), null, 0);?>

				<?php if (is_file($_smarty_tpl->tpl_vars['file_path']->value)&&file_exists($_smarty_tpl->tpl_vars['file_path']->value)){?>
					<div id="file_manage" class="clearfix">
						<p>
							<?php echo smarty_function_print_img(array('ups'=>'true','fl'=>$_smarty_tpl->tpl_vars['esynAccountInfo']->value['avatar'],'full'=>'true','title'=>$_smarty_tpl->tpl_vars['esynAccountInfo']->value['username'],'class'=>'avatar'),$_smarty_tpl);?>

							<a class="btn btn-danger btn-small" href="<?php echo @constant('IA_URL');?>
edit-account.php?delete=photo" title="<?php echo smarty_function_lang(array('key'=>'delete'),$_smarty_tpl);?>
"><i class="icon-remove-sign icon-white"></i></a>
						</p>
					</div>
				<?php }?>
				<div class="clearfix">
					<div class="upload-wrap pull-left">
						<div class="input-append">
							<span class="span2 uneditable-input"><?php echo smarty_function_lang(array('key'=>'click_to_upload'),$_smarty_tpl);?>
</span>
							<span class="add-on"><?php echo smarty_function_lang(array('key'=>'browse'),$_smarty_tpl);?>
</span>
						</div>
						<input type="file" class="upload-hidden" name="avatar" id="avatar">
					</div>
				</div>

				<?php if ($_smarty_tpl->tpl_vars['config']->value['sponsored_accounts']&&$_smarty_tpl->tpl_vars['plans']->value){?>
					<div style="margin-top: 20px;">
						<div class="fieldset">
							<h4 class="title"><?php echo smarty_function_lang(array('key'=>'plans'),$_smarty_tpl);?>
</h4>
							<div class="content">
								<?php  $_smarty_tpl->tpl_vars['plan'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plan']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['plans']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['plan']->key => $_smarty_tpl->tpl_vars['plan']->value){
$_smarty_tpl->tpl_vars['plan']->_loop = true;
?>
									<div class="b-plan">
										<label for="p<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" class="radio b-plan__title">
											<input type="radio" name="plan" value="<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" id="p<?php echo $_smarty_tpl->tpl_vars['plan']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['esynAccountInfo']->value['plan_id']==$_smarty_tpl->tpl_vars['plan']->value['id']){?>checked="checked"<?php }?>>
											<b><?php echo $_smarty_tpl->tpl_vars['plan']->value['title'];?>
 &mdash; <?php echo $_smarty_tpl->tpl_vars['config']->value['currency_symbol'];?>
<?php echo $_smarty_tpl->tpl_vars['plan']->value['cost'];?>
</b>
										</label>

										<div class="b-plan__description"><?php echo $_smarty_tpl->tpl_vars['plan']->value['description'];?>
</div>
									</div>
								<?php } ?>
							</div>
						</div>

						<div id="gateways" class="fieldset" style="display: none;">
							<h4 class="title"><?php echo smarty_function_lang(array('key'=>'payment_gateway'),$_smarty_tpl);?>
</h4>
							<div class="content collapsible-content">
								<?php echo smarty_function_ia_hooker(array('name'=>'paymentButtons'),$_smarty_tpl);?>

							</div>
						</div>
					</div>
				<?php }?>

				<div class="actions">
					<input type="hidden" name="old_email" value="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['email'];?>
">
					<input type="submit" name="change_email" value="<?php echo smarty_function_lang(array('key'=>'save_changes'),$_smarty_tpl);?>
" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>

	<div class="tab-pane" id="tab-pane_password">
		<div class="ia-wrap">
			<form action="<?php echo @constant('IA_URL');?>
edit-account.php" method="post" class="ia-form">

				<label for="current"><?php echo smarty_function_lang(array('key'=>'current_password'),$_smarty_tpl);?>
</label>
				<input type="password" class="span3" name="current" id="current-password">

				<label for="new"><?php echo smarty_function_lang(array('key'=>'new_password'),$_smarty_tpl);?>
</label>
				<input type="password" class="span3" name="new" id="new-password">

				<label for="confirm"><?php echo smarty_function_lang(array('key'=>'new_password2'),$_smarty_tpl);?>
</label>
				<input type="password" class="span3" name="confirm" id="new-password-confirm">

				<div class="actions">
					<input type="submit" name="change_pass" value="<?php echo smarty_function_lang(array('key'=>'change_password'),$_smarty_tpl);?>
" class="btn btn-primary" />
				</div>
			</form>
		</div>
	</div>

	<?php if ($_smarty_tpl->tpl_vars['config']->value['allow_delete_accounts']){?>
		<div class="tab-pane" id="tab-pane_delete">
			<div class="ia-wrap">
				<form action="<?php echo @constant('IA_URL');?>
edit-account.php" method="post" class="ia-form">
					<label for="delete_accept" class="checkbox"><input type="checkbox" name="delete_accept" id="delete_accept" /> <?php echo smarty_function_lang(array('key'=>'delete_account_label'),$_smarty_tpl);?>
</label>

					<div class="actions">
						<input name="delete_account" class="btn btn-danger" type="submit" value="<?php echo smarty_function_lang(array('key'=>'delete_account'),$_smarty_tpl);?>
">
					</div>
				</form>
			</div>
		</div>
	<?php }?>
</div><?php }} ?>