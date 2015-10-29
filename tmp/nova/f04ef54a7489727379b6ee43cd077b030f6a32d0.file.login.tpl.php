<?php /* Smarty version Smarty-3.1.13, created on 2015-03-10 18:35:14
         compiled from "/home/wwwsyaqd/public_html/templates/common/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:101793955754ff71a2d457f2-39731018%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f04ef54a7489727379b6ee43cd077b030f6a32d0' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/login.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '101793955754ff71a2d457f2-39731018',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54ff71a2db4439_01621903',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54ff71a2db4439_01621903')) {function content_54ff71a2db4439_01621903($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><?php echo smarty_function_ia_hooker(array('name'=>'tplFrontLoginAfterHeader'),$_smarty_tpl);?>


<form action="<?php echo @constant('IA_URL');?>
login.php" method="post" class="ia-form">
	<label for="username"><?php echo smarty_function_lang(array('key'=>'username'),$_smarty_tpl);?>
</label>
	<input type="text" class="span3" tabindex="4" name="username" value="<?php if (isset($_POST['username'])&&!empty($_POST['username'])){?><?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>">

	<label for="password"><?php echo smarty_function_lang(array('key'=>'password'),$_smarty_tpl);?>
</label>
	<input type="password" class="span3" tabindex="5" name="password" value="">

	<label for="rememberme" class="checkbox">
		<input type="checkbox" tabindex="3" name="rememberme" value="1" id="rememberme" <?php if (isset($_POST['rememberme'])&&$_POST['rememberme']=='1'){?>checked="checked"<?php }?>> <?php echo smarty_function_lang(array('key'=>'rememberme'),$_smarty_tpl);?>

	</label>

	<div class="actions">
		<input type="submit" tabindex="6" name="login" value="<?php echo smarty_function_lang(array('key'=>'login'),$_smarty_tpl);?>
" class="btn btn-primary">
		<a href="<?php echo @constant('IA_URL');?>
forgot.php"><?php echo smarty_function_lang(array('key'=>'forgot'),$_smarty_tpl);?>
</a>
	</div>
</form>

<p><?php echo smarty_function_lang(array('key'=>'register_account'),$_smarty_tpl);?>
 <a href="<?php echo @constant('IA_URL');?>
register.php" rel="nofollow"><?php echo smarty_function_lang(array('key'=>'register'),$_smarty_tpl);?>
</a></p>

<?php echo smarty_function_ia_hooker(array('name'=>'loginBeforeFooter'),$_smarty_tpl);?>
<?php }} ?>