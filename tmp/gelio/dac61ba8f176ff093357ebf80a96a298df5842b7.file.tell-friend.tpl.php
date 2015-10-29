<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/plugins/tell_friend/templates/tell-friend.tpl" */ ?>
<?php /*%%SmartyHeaderCode:33140316555091049152870-65333911%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dac61ba8f176ff093357ebf80a96a298df5842b7' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/tell_friend/templates/tell-friend.tpl',
      1 => 1425025928,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '33140316555091049152870-65333911',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lang' => 0,
    'temp' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910491fdfa6_90515596',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910491fdfa6_90515596')) {function content_550910491fdfa6_90515596($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><form action="<?php if (@constant('IA_MODREWRITE')){?>mod/tell_friend/<?php }else{ ?>controller.php?plugin=tell_friend<?php }?>" method="post" class="ia-form">
	<label><?php echo $_smarty_tpl->tpl_vars['lang']->value['fullname'];?>
</label>
	<input type="text" name="fullname" value="<?php if (isset($_smarty_tpl->tpl_vars['temp']->value['fullname'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['temp']->value['fullname'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['fullname'])){?><?php echo $_POST['fullname'];?>
<?php }?>">

	<label><?php echo $_smarty_tpl->tpl_vars['lang']->value['your_email'];?>
</label>
	<input type="text" name="email" value="<?php if (isset($_smarty_tpl->tpl_vars['temp']->value['email'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['temp']->value['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['email'])){?><?php echo $_POST['email'];?>
<?php }?>">

	<label><?php echo $_smarty_tpl->tpl_vars['lang']->value['friend_fullname'];?>
</label>
	<input type="text" name="fullname2" value="<?php if (isset($_smarty_tpl->tpl_vars['temp']->value['fullname2'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['temp']->value['fullname2'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['fullname2'])){?><?php echo $_POST['fullname2'];?>
<?php }?>">

	<label><?php echo $_smarty_tpl->tpl_vars['lang']->value['email'];?>
</label>
	<input type="text" name="email2" value="<?php if (isset($_smarty_tpl->tpl_vars['temp']->value['email2'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['temp']->value['email2'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['email2'])){?><?php echo $_POST['email2'];?>
<?php }?>">

	<label><?php echo $_smarty_tpl->tpl_vars['lang']->value['your_message'];?>
</label>
	<textarea name="body" rows="8" class="input-block-level"><?php if (isset($_smarty_tpl->tpl_vars['temp']->value['body'])){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['temp']->value['body'], ENT_QUOTES, 'UTF-8', true);?>
<?php }elseif(isset($_POST['body'])){?><?php echo $_POST['body'];?>
<?php }?></textarea>

	<?php echo $_smarty_tpl->getSubTemplate ('captcha.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


	<div class="actions">
		<input type="submit" class="btn btn-primary btn-plain" name="tell" value="<?php echo smarty_function_lang(array('key'=>'submit'),$_smarty_tpl);?>
" />
	</div>
</form><?php }} ?>