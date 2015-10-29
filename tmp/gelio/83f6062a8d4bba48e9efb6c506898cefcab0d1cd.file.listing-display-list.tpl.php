<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:54
         compiled from "/home/wwwsyaqd/public_html/templates/common/listing-display-list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1805617371550914d23f4d76-05374719%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '83f6062a8d4bba48e9efb6c506898cefcab0d1cd' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/listing-display-list.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1805617371550914d23f4d76-05374719',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'visual_options' => 0,
    'listing' => 0,
    'options' => 0,
    'config' => 0,
    'esynAccountInfo' => 0,
    'category_id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d251ad74_72594147',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d251ad74_72594147')) {function content_550914d251ad74_72594147($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_modifier_truncate')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
?><?php echo smarty_function_ia_hooker(array('name'=>'beforeListingDisplay'),$_smarty_tpl);?>


<?php if (isset($_smarty_tpl->tpl_vars['options'])) {$_smarty_tpl->tpl_vars['options'] = clone $_smarty_tpl->tpl_vars['options'];
$_smarty_tpl->tpl_vars['options']->value = array_intersect(array_keys($_smarty_tpl->tpl_vars['visual_options']->value),explode(',',$_smarty_tpl->tpl_vars['listing']->value['visual_options'])); $_smarty_tpl->tpl_vars['options']->nocache = null; $_smarty_tpl->tpl_vars['options']->scope = 0;
} else $_smarty_tpl->tpl_vars['options'] = new Smarty_variable(array_intersect(array_keys($_smarty_tpl->tpl_vars['visual_options']->value),explode(',',$_smarty_tpl->tpl_vars['listing']->value['visual_options'])), null, 0);?>

<div class="media ia-item bordered <?php if ($_smarty_tpl->tpl_vars['listing']->value['featured']){?> featured<?php }?> <?php echo $_smarty_tpl->tpl_vars['listing']->value['status'];?>
<?php if (in_array('add_border',$_smarty_tpl->tpl_vars['options']->value)){?> ia-visual-option--border<?php }?>" id="listing-<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
">
	<?php if ($_smarty_tpl->tpl_vars['listing']->value['featured']||$_smarty_tpl->tpl_vars['listing']->value['sponsored']||$_smarty_tpl->tpl_vars['listing']->value['partner']){?>
		<?php echo $_smarty_tpl->getSubTemplate ('thumbshots.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listing'=>$_smarty_tpl->tpl_vars['listing']->value), 0);?>

	<?php }?>

	<?php if (in_array('add_badge',$_smarty_tpl->tpl_vars['options']->value)){?>
		<div class="pull-right ia-visual-option--badge">
			<img src="<?php echo $_smarty_tpl->tpl_vars['visual_options']->value['add_badge'];?>
" alt="Badge">
		</div>
	<?php }?>

	<div class="media-body">
		<h4 class="media-heading">
			<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['crossed'])&&$_smarty_tpl->tpl_vars['listing']->value['crossed']=='1'){?>
				<i class="icon-random icon-gray"></i> 
			<?php }?>

			<?php if (in_array('add_star',$_smarty_tpl->tpl_vars['options']->value)){?>
				<img src="<?php echo $_smarty_tpl->tpl_vars['visual_options']->value['add_star'];?>
" alt="Star" class="ia-visual-option--star">
			<?php }?>

			<a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['listing']->value),$_smarty_tpl);?>
" id="l_<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['config']->value['new_window']){?>target="_blank"<?php }?><?php if ($_smarty_tpl->tpl_vars['config']->value['external_no_follow']){?> rel="nofollow"<?php }?>data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-item="listings" style="
				<?php if (in_array('highlight',$_smarty_tpl->tpl_vars['options']->value)){?>background:<?php echo $_smarty_tpl->tpl_vars['visual_options']->value['highlight'];?>
;<?php }?>
				<?php if (in_array('color_link',$_smarty_tpl->tpl_vars['options']->value)){?>color:<?php echo $_smarty_tpl->tpl_vars['visual_options']->value['color_link'];?>
;<?php }?>
				<?php if (in_array('link_big',$_smarty_tpl->tpl_vars['options']->value)){?>font-size:<?php echo $_smarty_tpl->tpl_vars['visual_options']->value['link_big'];?>
px;<?php }?>
			" class="js-count<?php if (in_array('itali_link',$_smarty_tpl->tpl_vars['options']->value)){?> ia-visual-option--italic-link<?php }?>"><?php echo $_smarty_tpl->tpl_vars['listing']->value['title'];?>
</a>

			<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['interval'])&&(1==$_smarty_tpl->tpl_vars['listing']->value['interval'])){?>
				<span class="label label-important"><?php echo smarty_function_lang(array('key'=>'new'),$_smarty_tpl);?>
</span>
			<?php }?>
		</h4>

		<?php if ($_smarty_tpl->tpl_vars['listing']->value['partner']){?>
			<span class="ia-badge partner"><?php echo smarty_function_lang(array('key'=>'partner'),$_smarty_tpl);?>
</span>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['listing']->value['featured']){?>
			<span class="ia-badge featured"><?php echo smarty_function_lang(array('key'=>'featured'),$_smarty_tpl);?>
</span>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['listing']->value['sponsored']){?>
			<span class="ia-badge sponsored"><?php echo smarty_function_lang(array('key'=>'sponsored'),$_smarty_tpl);?>
</span>
		<?php }?>
		<?php if ('approval'==$_smarty_tpl->tpl_vars['listing']->value['status']){?>
			<span class="ia-badge approval"></span>
		<?php }?>
		<?php if ('deleted'==$_smarty_tpl->tpl_vars['listing']->value['status']){?>
			<span class="ia-badge deleted"></span>
		<?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['rank'])&&!empty($_smarty_tpl->tpl_vars['listing']->value['rank'])){?>
			<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['star'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['star']);
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
<?php endfor; endif; ?>
		<?php }?>

		<div class="description
			<?php if (in_array('desc_bold',$_smarty_tpl->tpl_vars['options']->value)){?> ia-visual-option--bold-desc<?php }?>
			<?php if (in_array('desc_ital',$_smarty_tpl->tpl_vars['options']->value)){?> ia-visual-option--italic-desc<?php }?>
		">
			<?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['listing']->value['description']),200,'...');?>

		</div>

		<?php echo smarty_function_ia_hooker(array('name'=>'listingDisplayFieldsArea'),$_smarty_tpl);?>

	</div>

	<?php echo smarty_function_ia_hooker(array('name'=>'listingDisplayBeforeStats'),$_smarty_tpl);?>


	<div class="panel clearfix">
		<?php if ($_smarty_tpl->tpl_vars['listing']->value['account_username']){?>
			<span class="account" title="<?php echo smarty_function_lang(array('key'=>'account'),$_smarty_tpl);?>
"><i class="icon-user icon-blue"></i> <a href="<?php echo @constant('IA_URL');?>
accounts/<?php echo $_smarty_tpl->tpl_vars['listing']->value['account_username'];?>
.html"><?php echo $_smarty_tpl->tpl_vars['listing']->value['account_username'];?>
</a></span>
		<?php }?>

		<span class="date" title="<?php echo smarty_function_lang(array('key'=>'listing_added'),$_smarty_tpl);?>
"><i class="icon-calendar icon-blue"></i> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['listing']->value['date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
</span>

		<?php if ('index_browse'!=@constant('IA_REALM')){?>
			<span class="category" title="<?php echo smarty_function_lang(array('key'=>'category'),$_smarty_tpl);?>
"><i class="icon-folder-open icon-blue"></i> <a href="<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['print_category_url'][0][0]->printCategoryUrl(array('cat'=>$_smarty_tpl->tpl_vars['listing']->value,'fprefix'=>'category'),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['listing']->value['category_title'];?>
</a></span>
		<?php }?>

		<span class="clicks"><i class="icon-hand-right icon-blue"></i> <?php echo smarty_function_lang(array('key'=>'clicks'),$_smarty_tpl);?>
: <?php echo $_smarty_tpl->tpl_vars['listing']->value['clicks'];?>
</span>

		<?php if ($_smarty_tpl->tpl_vars['config']->value['pagerank']){?>
			<span class="rank"><i class="icon-signal icon-blue"></i> <?php echo smarty_function_lang(array('key'=>'pagerank'),$_smarty_tpl);?>
:&nbsp;<?php if ($_smarty_tpl->tpl_vars['listing']->value['pagerank']!='-1'){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['pagerank'];?>
<?php }else{ ?><?php echo smarty_function_lang(array('key'=>'no_pagerank'),$_smarty_tpl);?>
<?php }?></span>
		<?php }?>

		<?php echo smarty_function_ia_hooker(array('name'=>'listingDisplayPanelLinks'),$_smarty_tpl);?>


		<div class="toolbar pull-right">

			<?php echo smarty_function_ia_hooker(array('name'=>'listingDisplayToolbarLinks'),$_smarty_tpl);?>


			<a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['listing']->value,'details'=>true),$_smarty_tpl);?>
" class="js-count" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-item="listings" title="<?php echo smarty_function_lang(array('key'=>'view_listing'),$_smarty_tpl);?>
"><i class="icon-info-sign icon-blue"></i></a>
			<a href="#" class="toolbar-toggle"><i class="icon-cog icon-blue"></i></a>

			<div class="toolbar-actions">

				<?php echo smarty_function_ia_hooker(array('name'=>'listingDisplayToolbarActions'),$_smarty_tpl);?>


				<?php if ($_smarty_tpl->tpl_vars['esynAccountInfo']->value['id']==$_smarty_tpl->tpl_vars['listing']->value['account_id']){?>
					<a href="<?php echo @constant('IA_URL');?>
suggest-listing.php?edit=<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" class="js-tooltip" title="<?php echo smarty_function_lang(array('key'=>'edit_listing'),$_smarty_tpl);?>
">
						<i class="icon-edit icon-white"></i>
					</a>
				<?php }?>

				<?php if ($_smarty_tpl->tpl_vars['config']->value['allow_listings_deletion']&&'deleted'!=$_smarty_tpl->tpl_vars['listing']->value['status']&&$_smarty_tpl->tpl_vars['esynAccountInfo']->value['id']==$_smarty_tpl->tpl_vars['listing']->value['account_id']){?>
					<a href="#" class="js-delete js-tooltip" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" title="<?php echo smarty_function_lang(array('key'=>'remove_listing'),$_smarty_tpl);?>
">
						<i class="icon-remove icon-white"></i>
					</a>
				<?php }?>

				<?php if ($_smarty_tpl->tpl_vars['config']->value['broken_listings_report']&&!($_smarty_tpl->tpl_vars['esynAccountInfo']->value['id']==$_smarty_tpl->tpl_vars['listing']->value['account_id'])){?>
					<a href="#" class="js-report js-tooltip" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" title="<?php echo smarty_function_lang(array('key'=>'report_broken_listing'),$_smarty_tpl);?>
" rel="nofollow">
						<i class="icon-warning-sign icon-white"></i>
					</a>
				<?php }?>

				<?php if ($_smarty_tpl->tpl_vars['esynAccountInfo']->value&&$_smarty_tpl->tpl_vars['esynAccountInfo']->value['id']!=$_smarty_tpl->tpl_vars['listing']->value['account_id']){?>
					<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['favorite'])&&!$_smarty_tpl->tpl_vars['listing']->value['favorite']){?>
						<a href="#" class="js-favorites js-tooltip" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-account="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['id'];?>
" data-action="add" rel="nofollow" title="<?php echo smarty_function_lang(array('key'=>'add_to_favorites'),$_smarty_tpl);?>
">
							<i class="icon-star-empty icon-white"></i>
						</a>
					<?php }else{ ?>
						<a href="#" class="js-favorites js-tooltip" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-account="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['id'];?>
" data-action="remove" rel="nofollow" title="<?php echo smarty_function_lang(array('key'=>'remove_from_favorites'),$_smarty_tpl);?>
">
							<i class="icon-star icon-white"></i>
						</a>
					<?php }?>
				<?php }?>

				<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['crossed'])&&$_smarty_tpl->tpl_vars['listing']->value['crossed']=='0'&&$_smarty_tpl->tpl_vars['esynAccountInfo']->value['id']==$_smarty_tpl->tpl_vars['listing']->value['account_id']){?>
					<a href="#" class="js-move js-tooltip" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-category="<?php echo $_smarty_tpl->tpl_vars['category_id']->value['id'];?>
" title="<?php echo smarty_function_lang(array('key'=>'move_listing'),$_smarty_tpl);?>
"><i class="icon-move icon-white"></i></a><br />
					<?php if ('account_listings'==@constant('IA_REALM')){?><?php echo smarty_function_lang(array('key'=>'category'),$_smarty_tpl);?>
: <a href="<?php echo @constant('IA_URL');?>
<?php if ($_smarty_tpl->tpl_vars['config']->value['use_html_path']){?><?php echo ($_smarty_tpl->tpl_vars['listing']->value['path']).(".html");?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['listing']->value['path'];?>
<?php }?>"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['listing']->value['category_title'], ENT_QUOTES, 'UTF-8', true);?>
</a><?php }?>
				<?php }?>
			</div>
		</div>
	</div>
</div><?php }} ?>