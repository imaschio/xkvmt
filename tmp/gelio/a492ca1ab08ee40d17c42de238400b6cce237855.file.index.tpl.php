<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 09:41:42
         compiled from "/home/wwwsyaqd/public_html/plugins/listingstatus/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:187617927155098096263574-25314964%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a492ca1ab08ee40d17c42de238400b6cce237855' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/listingstatus/templates/index.tpl',
      1 => 1425025928,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '187617927155098096263574-25314964',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550980962a2026_56780094',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550980962a2026_56780094')) {function content_550980962a2026_56780094($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><div class="page-description"><?php echo smarty_function_lang(array('key'=>'listing_status_description'),$_smarty_tpl);?>
</div>

<form action="" method="post" class="form-inline">

	<?php echo smarty_function_lang(array('key'=>'check_link_status_url'),$_smarty_tpl);?>
:
	<input type="text" class="text" name="link_url" size="35" value="<?php if (isset($_POST['link_url'])){?><?php echo htmlspecialchars($_POST['link_url'], ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?>http://<?php }?>" />

	<input class="btn btn-primary" type="submit" name="contact" value="<?php echo smarty_function_lang(array('key'=>'submit'),$_smarty_tpl);?>
" />
</form><?php }} ?>