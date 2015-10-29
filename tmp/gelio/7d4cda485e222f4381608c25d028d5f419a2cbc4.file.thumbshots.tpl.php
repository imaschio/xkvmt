<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/common/thumbshots.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12796240925509104966a228-90291483%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7d4cda485e222f4381608c25d028d5f419a2cbc4' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/thumbshots.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12796240925509104966a228-90291483',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'instead_thumbnail' => 0,
    'listing' => 0,
    'tile' => 0,
    'instead_thumbnail_info' => 0,
    'lightbox' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910496bbb61_61725228',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910496bbb61_61725228')) {function content_550910496bbb61_61725228($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
?><?php if (isset($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['instead_thumbnail']->value])&&$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['instead_thumbnail']->value]){?>
	<?php if (isset($_smarty_tpl->tpl_vars['tile']->value)){?>
		<a class="pull-left image" href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['listing']->value),$_smarty_tpl);?>
">
			<?php echo smarty_function_print_img(array('ups'=>true,'fl'=>"small_".((string)$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['instead_thumbnail']->value]),'full'=>true,'style'=>"margin: -".((string)($_smarty_tpl->tpl_vars['instead_thumbnail_info']->value['thumb_height']/2))."px 0 0 -".((string)($_smarty_tpl->tpl_vars['instead_thumbnail_info']->value['thumb_width']/2))."px",'title'=>$_smarty_tpl->tpl_vars['listing']->value['title']),$_smarty_tpl);?>

		</a>
	<?php }else{ ?>
		<a class="pull-left" href="<?php ob_start();?><?php echo smarty_function_print_img(array('ups'=>true,'fl'=>$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['instead_thumbnail']->value]),$_smarty_tpl);?>
<?php $_tmp1=ob_get_clean();?><?php ob_start();?><?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['listing']->value),$_smarty_tpl);?>
<?php $_tmp2=ob_get_clean();?><?php echo isset($_smarty_tpl->tpl_vars['lightbox']->value) ? $_tmp1 : $_tmp2;?>
" <?php if (isset($_smarty_tpl->tpl_vars['lightbox']->value)){?>rel="ia_lightbox-<?php echo $_smarty_tpl->tpl_vars['instead_thumbnail']->value;?>
"<?php }?>>
			<?php echo smarty_function_print_img(array('ups'=>true,'fl'=>"small_".((string)$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['instead_thumbnail']->value]),'full'=>true,'title'=>$_smarty_tpl->tpl_vars['listing']->value['title'],'class'=>'media-object'),$_smarty_tpl);?>

		</a>
	<?php }?>
<?php }else{ ?>
	<div class="pull-left image">
		<?php if ($_smarty_tpl->tpl_vars['config']->value['thumbshot']&&$_smarty_tpl->tpl_vars['config']->value['thumbshots_name']){?>
			<?php echo $_smarty_tpl->getSubTemplate ("plugins/".((string)$_smarty_tpl->tpl_vars['config']->value['thumbshots_name'])."/templates/thumbshot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<?php }?>
	</div>
<?php }?><?php }} ?>