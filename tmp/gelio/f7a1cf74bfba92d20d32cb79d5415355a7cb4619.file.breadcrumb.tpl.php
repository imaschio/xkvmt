<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/common/breadcrumb.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6024861355091049817202-11411860%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f7a1cf74bfba92d20d32cb79d5415355a7cb4619' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/breadcrumb.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6024861355091049817202-11411860',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'breadcrumb' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5509104983e073_00596657',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5509104983e073_00596657')) {function content_5509104983e073_00596657($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
?><?php if (isset($_smarty_tpl->tpl_vars['breadcrumb']->value)&&count($_smarty_tpl->tpl_vars['breadcrumb']->value)){?>
<div class="breadcrumb-wrapper noprint clearfix">
	<div xmlns:v="http://rdf.data-vocabulary.org/#">
		<ul class="ia-breadcrumb pull-left">
			<li><a href="<?php echo @constant('IA_URL');?>
" rel="v:url" property="v:title"><?php echo smarty_function_lang(array('key'=>'home'),$_smarty_tpl);?>
</a> <span class="divider">/</span></li>
			<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['breadcrumb']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['item']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['item']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->iteration++;
 $_smarty_tpl->tpl_vars['item']->last = $_smarty_tpl->tpl_vars['item']->iteration === $_smarty_tpl->tpl_vars['item']->total;
?>
				<?php if ($_smarty_tpl->tpl_vars['item']->value['url']&&!$_smarty_tpl->tpl_vars['item']->last){?>
					<li typeof="v:Breadcrumb">
						<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"<?php if (isset($_smarty_tpl->tpl_vars['item']->value['no_follow'])&&$_smarty_tpl->tpl_vars['item']->value['no_follow']){?> rel="nofollow"<?php }?> rel="v:url" property="v:title"><?php echo $_smarty_tpl->tpl_vars['item']->value['caption'];?>
</a>
						<span class="divider">/</span>
					</li>
				<?php }else{ ?>
					<li class="active"><?php echo $_smarty_tpl->tpl_vars['item']->value['caption'];?>
</li>
				<?php }?>
			<?php } ?>
		</ul>

		<ul class="unstyled special-icons pull-right">
			<?php echo smarty_function_ia_hooker(array('name'=>'smartyFrontBreadcrumbIcons'),$_smarty_tpl);?>

		</ul>
	</div>
</div>
<?php }?><?php }} ?>