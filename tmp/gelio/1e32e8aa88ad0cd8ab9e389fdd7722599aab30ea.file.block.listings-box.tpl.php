<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/plugins/listings_boxes/templates/block.listings-box.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1794832296550910497be590-86269700%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e32e8aa88ad0cd8ab9e389fdd7722599aab30ea' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/listings_boxes/templates/block.listings-box.tpl',
      1 => 1425025916,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1794832296550910497be590-86269700',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'box_listings' => 0,
    'top_listing' => 0,
    'config' => 0,
    'random_listing' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550910497f4d68_27128599',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550910497f4d68_27128599')) {function content_550910497f4d68_27128599($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_truncate')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.truncate.php';
?><?php if ($_smarty_tpl->tpl_vars['box_listings']->value['top']||$_smarty_tpl->tpl_vars['box_listings']->value['random']){?>
	<ul class="nav nav-tabs" id="listingsTabs">
		<li class="tab_top active"><a href="#tab-pane_listingsTop" data-toggle="tab"><?php echo smarty_function_lang(array('key'=>'listings_box_top'),$_smarty_tpl);?>
</a></li>
		<li class="tab_random"><a href="#tab-pane_listingsRandom" data-toggle="tab"><?php echo smarty_function_lang(array('key'=>'listings_box_random'),$_smarty_tpl);?>
</a></li>
	</ul>

	<div class="tab-content" id="listingsTabsContent">
		<div class="tab-pane active" id="tab-pane_listingsTop">
			<div class="ia-wrap">
				<?php  $_smarty_tpl->tpl_vars['top_listing'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['top_listing']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['box_listings']->value['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['top_listing']->key => $_smarty_tpl->tpl_vars['top_listing']->value){
$_smarty_tpl->tpl_vars['top_listing']->_loop = true;
?>
					<div class="ia-item list">
						<?php if ($_smarty_tpl->tpl_vars['top_listing']->value['rank']){?>
							<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['star'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['star']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['name'] = 'star';
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['top_listing']->value['rank']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<?php endfor; endif; ?>
						<?php }?>
						<a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['top_listing']->value),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['top_listing']->value['title'];?>
</a>
						<br>
						<span class="text-small"><i class="icon-time icon-gray"></i> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['top_listing']->value['date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
</span>
						<span class="text-small"><i class="icon-folder-open icon-gray"></i> <?php echo $_smarty_tpl->tpl_vars['top_listing']->value['category_title'];?>
</span>
						<p class="text-small muted"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['top_listing']->value['description'],100,'...');?>
</p>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="tab-pane" id="tab-pane_listingsRandom">
			<div class="ia-wrap">
				<?php  $_smarty_tpl->tpl_vars['random_listing'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['random_listing']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['box_listings']->value['random']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['random_listing']->key => $_smarty_tpl->tpl_vars['random_listing']->value){
$_smarty_tpl->tpl_vars['random_listing']->_loop = true;
?>
					<div class="ia-item list">
						<a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['random_listing']->value),$_smarty_tpl);?>
" <?php if ($_smarty_tpl->tpl_vars['config']->value['new_window']){?>target="_blank"<?php }?>><?php echo $_smarty_tpl->tpl_vars['random_listing']->value['title'];?>
</a> &mdash; <span class="muted"><?php echo $_smarty_tpl->tpl_vars['random_listing']->value['category_title'];?>
</span>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php }?><?php }} ?>