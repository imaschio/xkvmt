<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:52
         compiled from "/home/wwwsyaqd/public_html/plugins/youtube/templates/hook.tplFrontViewListingBeforeDeepLinks.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1606460392550914d04c52a8-96132877%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f30371b128cf3954cd6f35427b8f12f95c535500' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/youtube/templates/hook.tplFrontViewListingBeforeDeepLinks.tpl',
      1 => 1425025918,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1606460392550914d04c52a8-96132877',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listing' => 0,
    'lang' => 0,
    'video' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d04d89f6_36917352',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d04d89f6_36917352')) {function content_550914d04d89f6_36917352($_smarty_tpl) {?><?php if (!is_callable('smarty_block_ia_block')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_block.php';
?><?php if (isset($_smarty_tpl->tpl_vars['listing']->value['youtubevideo'])&&!empty($_smarty_tpl->tpl_vars['listing']->value['youtubevideo'])){?>
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_block', array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['field_youtubevideo'],'collapsible'=>'1','id'=>'youtube')); $_block_repeat=true; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['field_youtubevideo'],'collapsible'=>'1','id'=>'youtube'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


	<div class="text-center">
		<?php  $_smarty_tpl->tpl_vars['video'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['video']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listing']->value['youtubevideo']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['video']->key => $_smarty_tpl->tpl_vars['video']->value){
$_smarty_tpl->tpl_vars['video']->_loop = true;
?>
			<object width="425" height="355">
				<param name="movie" value="http://www.youtube.com/v/<?php echo $_smarty_tpl->tpl_vars['video']->value;?>
&rel=1"></param>
				<param name="wmode" value="transparent"></param>
				<embed src="http://www.youtube.com/v/<?php echo $_smarty_tpl->tpl_vars['video']->value;?>
&rel=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed>
			</object>
		<?php } ?>
	</div>

	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['field_youtubevideo'],'collapsible'=>'1','id'=>'youtube'), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }?><?php }} ?>