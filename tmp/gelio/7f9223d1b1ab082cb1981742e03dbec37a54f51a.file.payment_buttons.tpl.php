<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:45:41
         compiled from "/home/wwwsyaqd/public_html/plugins/paypal/templates/payment_buttons.tpl" */ ?>
<?php /*%%SmartyHeaderCode:92012956555091105c45b89-33844241%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7f9223d1b1ab082cb1981742e03dbec37a54f51a' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/paypal/templates/payment_buttons.tpl',
      1 => 1425025916,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '92012956555091105c45b89-33844241',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55091105c49e62_44574850',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55091105c49e62_44574850')) {function content_55091105c49e62_44574850($_smarty_tpl) {?><label for="paypal" class="radio">
	<input type="radio" name="payment_gateway" value="paypal" id="paypal" checked="checked" />
	<img src="<?php echo @constant('IA_URL');?>
plugins/paypal/templates/img/paypal.png" alt="Paypal" />
</label><?php }} ?>