<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:52
         compiled from "/home/wwwsyaqd/public_html/templates/gelio/view-listing.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1257901531550914d02424b5-66677659%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ea06ec280c73457c7676d6d4381f0847cef79163' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/gelio/view-listing.tpl',
      1 => 1398959464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1257901531550914d02424b5-66677659',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'listing' => 0,
    'config' => 0,
    'view_fields' => 0,
    'cats_chain' => 0,
    'cat' => 0,
    'category' => 0,
    'crossed_categories' => 0,
    'crossed_category' => 0,
    'lang' => 0,
    'u' => 0,
    't' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d030d6e9_46951933',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d030d6e9_46951933')) {function content_550914d030d6e9_46951933($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_block_ia_block')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_block.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><?php echo smarty_function_ia_hooker(array('name'=>'viewListingAfterHeading'),$_smarty_tpl);?>


<div class="media ia-item ia-item-view-listing" id="listing-<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
">
	<div class="pull-right">
		<?php echo $_smarty_tpl->getSubTemplate ('thumbshots.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listing'=>$_smarty_tpl->tpl_vars['listing']->value,'lightbox'=>true), 0);?>


		<?php if ($_smarty_tpl->tpl_vars['listing']->value['rank']){?>
			<p class="text-center"><?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['star'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['star']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['name'] = 'star';
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['listing']->value['rank']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total']);
?><?php echo smarty_function_print_img(array('fl'=>'star.png','full'=>true),$_smarty_tpl);?>
<?php endfor; endif; ?></p>
		<?php }?>

		<?php echo smarty_function_ia_hooker(array('name'=>'tplFrontViewListingAfterThumbshot'),$_smarty_tpl);?>


	</div>

	<div class="media-body">
		<dl class="dl-horizontal">
			<?php if (isset($_smarty_tpl->tpl_vars['view_fields'])) {$_smarty_tpl->tpl_vars['view_fields'] = clone $_smarty_tpl->tpl_vars['view_fields'];
$_smarty_tpl->tpl_vars['view_fields']->value = explode(",",$_smarty_tpl->tpl_vars['config']->value['viewlisting_fields']); $_smarty_tpl->tpl_vars['view_fields']->nocache = null; $_smarty_tpl->tpl_vars['view_fields']->scope = 0;
} else $_smarty_tpl->tpl_vars['view_fields'] = new Smarty_variable(explode(",",$_smarty_tpl->tpl_vars['config']->value['viewlisting_fields']), null, 0);?>
			<?php if (in_array(0,$_smarty_tpl->tpl_vars['view_fields']->value)){?>
				<dt><?php echo smarty_function_lang(array('key'=>'title'),$_smarty_tpl);?>
</dt>
				<dd><a href="<?php echo $_smarty_tpl->tpl_vars['listing']->value['url'];?>
" id="l_<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['config']->value['new_window']){?>target="_blank"<?php }?> class="js-count" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-item="listings"><?php echo $_smarty_tpl->tpl_vars['listing']->value['title'];?>
</a></dd>
			<?php }?>
			<?php if (in_array(1,$_smarty_tpl->tpl_vars['view_fields']->value)){?>
				<dt><?php echo smarty_function_lang(array('key'=>'category'),$_smarty_tpl);?>
</dt>
				<dd>
					<?php if (isset($_smarty_tpl->tpl_vars['cats_chain']->value)&&$_smarty_tpl->tpl_vars['cats_chain']->value){?>
						<?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cats_chain']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['cat']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['cat']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value){
$_smarty_tpl->tpl_vars['cat']->_loop = true;
 $_smarty_tpl->tpl_vars['cat']->iteration++;
 $_smarty_tpl->tpl_vars['cat']->last = $_smarty_tpl->tpl_vars['cat']->iteration === $_smarty_tpl->tpl_vars['cat']->total;
?>
							<a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['cat']->value),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['cat']->value['title'];?>
</a>
							<?php if (!$_smarty_tpl->tpl_vars['cat']->last){?> / <?php }?>
						<?php } ?>
					<?php }else{ ?>
						<a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['category']->value),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
</a>
					<?php }?>
				</dd>
				<?php if (isset($_smarty_tpl->tpl_vars['crossed_categories']->value)&&!empty($_smarty_tpl->tpl_vars['crossed_categories']->value)){?>
					<dt><?php echo smarty_function_lang(array('key'=>'crossed_to'),$_smarty_tpl);?>
</dt>
					<dd>
						<?php  $_smarty_tpl->tpl_vars['crossed_category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['crossed_category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['crossed_categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['crossed_category']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['crossed_category']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['crossed_category']->key => $_smarty_tpl->tpl_vars['crossed_category']->value){
$_smarty_tpl->tpl_vars['crossed_category']->_loop = true;
 $_smarty_tpl->tpl_vars['crossed_category']->iteration++;
 $_smarty_tpl->tpl_vars['crossed_category']->last = $_smarty_tpl->tpl_vars['crossed_category']->iteration === $_smarty_tpl->tpl_vars['crossed_category']->total;
?>
							<a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['crossed_category']->value),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['crossed_category']->value['title'];?>
</a>
							<?php if (!$_smarty_tpl->tpl_vars['crossed_category']->last){?>,<?php }?>
						<?php } ?>
					</dd>
				<?php }?>
			<?php }?>

			<?php if (in_array(2,$_smarty_tpl->tpl_vars['view_fields']->value)){?>
				<dt><?php echo smarty_function_lang(array('key'=>'clicks'),$_smarty_tpl);?>
</dt>
				<dd><?php echo $_smarty_tpl->tpl_vars['listing']->value['clicks'];?>
</dd>
			<?php }?>
			<?php if (in_array(3,$_smarty_tpl->tpl_vars['view_fields']->value)){?>
				<dt><?php echo smarty_function_lang(array('key'=>'listing_added'),$_smarty_tpl);?>
</dt>
				<dd><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['listing']->value['date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
&nbsp;</dd>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['config']->value['pagerank']&&in_array(4,$_smarty_tpl->tpl_vars['view_fields']->value)){?>
				<dt><?php echo smarty_function_lang(array('key'=>'pagerank'),$_smarty_tpl);?>
</dt>
				<dd><?php if ($_smarty_tpl->tpl_vars['listing']->value['pagerank']!='-1'){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['pagerank'];?>
<?php }else{ ?><?php echo smarty_function_lang(array('key'=>'no_pagerank'),$_smarty_tpl);?>
<?php }?></dd>
			<?php }?>

			<?php echo smarty_function_ia_hooker(array('name'=>'viewListingAfterMainFieldsDisplay'),$_smarty_tpl);?>

		</dl>
	</div>

	<?php echo smarty_function_ia_hooker(array('name'=>'viewListingBeforeDescription'),$_smarty_tpl);?>


	<div class="description">
		<?php echo $_smarty_tpl->tpl_vars['listing']->value['description'];?>

	</div>

	<div class="info-panel">
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_preferred_2"></a>
			<a class="addthis_button_preferred_3"></a>
			<a class="addthis_button_preferred_4"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-511dfb4f5bdf57f0"></script>
		<!-- AddThis Button END -->
	</div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ('field-generator.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo smarty_function_ia_hooker(array('name'=>'tplFrontViewListingBeforeDeepLinks'),$_smarty_tpl);?>


<?php if ($_smarty_tpl->tpl_vars['listing']->value['_deep_links']){?>
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_block', array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['quick_links'])); $_block_repeat=true; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['quick_links']), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	<ul class="nav nav-actions">
		<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['t']->_loop = false;
 $_smarty_tpl->tpl_vars['u'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listing']->value['_deep_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
$_smarty_tpl->tpl_vars['t']->_loop = true;
 $_smarty_tpl->tpl_vars['u']->value = $_smarty_tpl->tpl_vars['t']->key;
?>
			<li><a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['u']->value, ENT_QUOTES, 'UTF-8', true);?>
"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['t']->value, ENT_QUOTES, 'UTF-8', true);?>
</a></li>
		<?php } ?>
	</ul>
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['quick_links']), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }?>

<?php echo smarty_function_ia_print_js(array('files'=>'js/frontend/view-listing'),$_smarty_tpl);?>


<?php echo smarty_function_ia_hooker(array('name'=>'viewListingBeforeFooter'),$_smarty_tpl);?>
<?php }} ?>