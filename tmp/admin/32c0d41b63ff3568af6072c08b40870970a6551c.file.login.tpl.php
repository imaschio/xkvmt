<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 03:05:25
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:890629650550923b509be77-71201185%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '32c0d41b63ff3568af6072c08b40870970a6551c' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/login.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '890629650550923b509be77-71201185',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'esynI18N' => 0,
    'config' => 0,
    'langs' => 0,
    'iso' => 0,
    'lang' => 0,
    'error' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550923b517d430_20617133',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550923b517d430_20617133')) {function content_550923b517d430_20617133($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['login_to'];?>
</title>
	<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $_smarty_tpl->tpl_vars['config']->value['charset'];?>
">
	<base href="<?php echo @constant('IA_URL');?>
<?php echo @constant('IA_ADMIN_DIR');?>
/">

	<?php echo smarty_function_include_file(array('js'=>"js/ext/ext-base, js/ext/ext-all, js/jquery/jquery, js/utils/md5, js/intelli/intelli, js/intelli/intelli.admin"),$_smarty_tpl);?>


	<?php echo smarty_function_include_file(array('js'=>((string)@constant('IA_TMP_NAME'))."/cache/intelli.config"),$_smarty_tpl);?>

	<?php echo smarty_function_include_file(array('js'=>((string)@constant('IA_TMP_NAME'))."/cache/intelli.admin.lang.".((string)$_smarty_tpl->tpl_vars['config']->value['lang'])),$_smarty_tpl);?>


	<?php echo smarty_function_include_file(array('css'=>"js/ext/resources/css/ext-all"),$_smarty_tpl);?>

	<link rel="stylesheet" type="text/css" href="templates/<?php echo $_smarty_tpl->tpl_vars['config']->value['admin_tmpl'];?>
/css/login.css">
</head>

<body>

	<!-- login start -->
	<div class="login">

		<!-- logo start -->
		<div class="logo"><a href="http://www.esyndicat.com/"><img src="templates/<?php echo $_smarty_tpl->tpl_vars['config']->value['admin_tmpl'];?>
/img/logos/logo.png" alt="eSyndiCat"></a></div>
		<!-- logo end -->

		<!-- text start -->
		<div class="text">
			<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['login_to_text'];?>

		</div>
		<!-- text end -->

		<!-- login form start -->
		<div class="form">
			<form action="login.php" method="post" name="login_form" id="login_form" onsubmit="formSubmit();">
			<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

			<ul>
				<li><label for="username"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['login'];?>
</strong></label></li>
				<li style="width:200px;">
					<input type="text" id="username" name="username" tabindex="1" value="<?php if (isset($_POST['username'])){?><?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">
				</li>

				<li style="clear:both;"><label for="dummy_password"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['password'];?>
</strong></label></li>
				<li>
					<input type="password" id="dummy_password" name="dummy_password" value="" tabindex="2">
					<input type="hidden" id="password" name="password" size="25" tabindex="2">
				</li>

				<?php if (count($_smarty_tpl->tpl_vars['langs']->value)>1){?>
				<li style="clear:both;"><label for="lang"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['language'];?>
</strong></label></li>
				<li style="width: 200px;">
					<select name="lang" class="common" autocomplete="off">
						<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['iso'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['iso']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['iso']->value;?>
" <?php if (@constant('IA_LANGUAGE')==$_smarty_tpl->tpl_vars['iso']->value){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
</option>
						<?php } ?>
					</select>
				</li>
				<?php }?>

				<li style="clear:both; padding-left:80px;">
					<input type="hidden" name="action" id="action" value="">
					<input type="hidden" name="md5Salt" id="md5Salt" value="<?php echo $_SESSION['md5Salt'];?>
">
					<input type="submit" id="login" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['login'];?>
" tabindex="3">
				</li>
			</ul>
			</form>

			<div style="clear:both;"></div>

			<?php if ($_smarty_tpl->tpl_vars['error']->value){?>
				<div class="tip">
					<div class="inner">
						<div class="tip-arrow"></div>
						<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['error_login'];?>

					</div>
				</div>
			<?php }?>

		</div>
		<!-- login form end -->

		<!-- copyrights start -->
		<div class="copy">
			Powered by <a href="http://www.esyndicat.com/">eSyndiCat Pro v<?php echo $_smarty_tpl->tpl_vars['config']->value['version'];?>
</a><br />
			Copyright &copy; <?php echo smarty_modifier_date_format(time(),"%Y");?>
 <a href="http://www.intelliants.com/">Intelliants LLC</a>
		</div>
		<!-- copyrights end -->

		<div class="forgot">
			<br /><a href="#" id="forgot_password"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['forgot_password'];?>
</a>
		</div>

	</div>
	<!-- login end -->

<?php echo smarty_function_include_file(array('js'=>"js/admin/login"),$_smarty_tpl);?>


</body>
</html>
<?php }} ?>