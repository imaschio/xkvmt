<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:45:41
         compiled from "/home/wwwsyaqd/public_html/templates/common/listing-preview.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173294795455091105c023c8-00720548%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3c2ca4395adffbe2b896eb914c51e3f3a9ce1bb0' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/listing-preview.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173294795455091105c023c8-00720548',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55091105c389b4_59885962',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55091105c389b4_59885962')) {function content_55091105c389b4_59885962($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
?><div class="media ia-item bordered<<?php ?>% if (options['add_border']) { %<?php ?>> ia-visual-option--border<<?php ?>% } %<?php ?>>">
	<<?php ?>% if (options['add_badge']) { %<?php ?>>
		<div class="pull-right ia-visual-option--badge">
			<img src="<<?php ?>%= options['add_badge']['value'] %<?php ?>>" alt="Badge">
		</div>
	<<?php ?>% } %<?php ?>>

	<div class="media-body">
		<h4 class="media-heading">
			<<?php ?>% if (options['add_star']) { %<?php ?>>
				<img src="<<?php ?>%= options['add_star']['value'] %<?php ?>>" alt="Star" class="ia-visual-option--star">
			<<?php ?>% } %<?php ?>>

			<a href="#" style="
				<<?php ?>% if (options['highlight']) { %<?php ?>>
					background: <<?php ?>%= options['highlight']['value'] %<?php ?>>;
				<<?php ?>% } %<?php ?>>
				<<?php ?>% if (options['color_link']) { %<?php ?>>
					color: <<?php ?>%= options['color_link']['value'] %<?php ?>>;
				<<?php ?>% } %<?php ?>>
				<<?php ?>% if (options['link_big']) { %<?php ?>>
					font-size: <<?php ?>%= options['link_big']['value'] %<?php ?>>px;
				<<?php ?>% } %<?php ?>>
			"<<?php ?>% if (options['itali_link']) { %<?php ?>> class="ia-visual-option--italic-link"<<?php ?>% } %<?php ?>>><?php echo smarty_function_lang(array('key'=>'preview_listing_title'),$_smarty_tpl);?>
</a>
			<span class="label label-important"><?php echo smarty_function_lang(array('key'=>'new'),$_smarty_tpl);?>
</span>
		</h4>

		<div class="description<<?php ?>% if (options['desc_bold']) { %<?php ?>> ia-visual-option--bold-desc<<?php ?>% } %<?php ?>><<?php ?>% if (options['desc_ital']) { %<?php ?>> ia-visual-option--italic-desc<<?php ?>% } %<?php ?>>">
			<p>
				<?php echo smarty_function_lang(array('key'=>'preview_listing_description'),$_smarty_tpl);?>

			</p>
		</div>
	</div>

	<div class="panel clearfix">
		<span class="account" title="<?php echo smarty_function_lang(array('key'=>'account'),$_smarty_tpl);?>
"><i class="icon-user icon-blue"></i> <a href="#"><?php echo smarty_function_lang(array('key'=>'username'),$_smarty_tpl);?>
</a></span>

		<span class="date" title="<?php echo smarty_function_lang(array('key'=>'listing_added'),$_smarty_tpl);?>
"><i class="icon-calendar icon-blue"></i> <?php echo smarty_modifier_date_format(time(),$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
</span>

		<span class="category" title="<?php echo smarty_function_lang(array('key'=>'category'),$_smarty_tpl);?>
"><i class="icon-folder-open icon-blue"></i> <a href="#"><?php echo smarty_function_lang(array('key'=>'category'),$_smarty_tpl);?>
</a></span>

		<span class="clicks"><i class="icon-hand-right icon-blue"></i> <?php echo smarty_function_lang(array('key'=>'clicks'),$_smarty_tpl);?>
: 0</span>

		<?php if ($_smarty_tpl->tpl_vars['config']->value['pagerank']){?>
			<span class="rank"><i class="icon-signal icon-blue"></i> <?php echo smarty_function_lang(array('key'=>'pagerank'),$_smarty_tpl);?>
: <?php echo smarty_function_lang(array('key'=>'no_pagerank'),$_smarty_tpl);?>
</span>
		<?php }?>
	</div>
</div><?php }} ?>