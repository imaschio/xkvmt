<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 07:28:25
         compiled from "/home/wwwsyaqd/public_html/templates/common/register.tpl" */ ?>
<?php /*%%SmartyHeaderCode:67873711555096159a2f5e0-17562345%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '92e8e72160d7f7d3da4a744255b4b2c4b6239073' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/register.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '67873711555096159a2f5e0-17562345',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'account' => 0,
    'config' => 0,
    'plans' => 0,
    'plan' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55096159b47093_07387346',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55096159b47093_07387346')) {function content_55096159b47093_07387346($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><?php echo smarty_function_ia_hooker(array('name'=>'tplFrontRegisterBeforeRegister'),$_smarty_tpl);?>


<form method="post" action="<?php echo @constant('IA_URL');?>
register.php" class="ia-form">
	<label for="username"><?php echo smarty_function_lang(array('key'=>'your_username'),$_smarty_tpl);?>
</label>
	<input type="text" class="span3" name="username" id="username" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['username'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['username'])){?><?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">

	<label for="email"><?php echo smarty_function_lang(array('key'=>'your_email'),$_smarty_tpl);?>
</label>
	<input type="email" class="span3" name="email" id="email" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['email'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['email'])){?><?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">

	<label for="auto_generate" class="checkbox">
		<input type="checkbox" id="auto_generate" name="auto_generate" value="1" <?php if (isset($_POST['auto_generate'])&&$_POST['auto_generate']=='1'){?>checked="checked"<?php }elseif(!isset($_smarty_tpl->tpl_vars['account']->value)&&!$_POST){?>checked="checked"<?php }?>> <?php echo smarty_function_lang(array('key'=>'auto_generate_password'),$_smarty_tpl);?>

	</label>

	<div id="passwords" style="display: none;">
		<label for="password"><?php echo smarty_function_lang(array('key'=>'your_password'),$_smarty_tpl);?>
</label>
		<input type="password" name="password" class="span2" id="pass1" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['password'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value['password'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['password'])){?><?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
		<label for="password2"><?php echo smarty_function_lang(array('key'=>'your_password_confirm'),$_smarty_tpl);?>
</label>
		<input type="password" name="password2" class="span2" id="pass2" value="<?php if (isset($_smarty_tpl->tpl_vars['account']->value['password2'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['account']->value['password2'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['password2'])){?><?php echo htmlspecialchars($_POST['password2'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
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
" <?php if (isset($_POST['plan'])&&$_POST['plan']==$_smarty_tpl->tpl_vars['plan']->value['id']){?> checked="checked"<?php }?>>
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

	<?php echo $_smarty_tpl->getSubTemplate ('captcha.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


	<div class="actions">
		<input type="submit" name="register" value="<?php echo smarty_function_lang(array('key'=>'register'),$_smarty_tpl);?>
" class="btn btn-primary" />
	</div>
</form>

<?php echo smarty_function_ia_print_js(array('files'=>'js/frontend/register'),$_smarty_tpl);?>


<?php echo smarty_function_ia_hooker(array('name'=>'registerBeforeFooter'),$_smarty_tpl);?>
<?php }} ?>