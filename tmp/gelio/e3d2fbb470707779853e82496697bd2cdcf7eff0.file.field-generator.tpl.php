<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:52
         compiled from "/home/wwwsyaqd/public_html/templates/common/field-generator.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1292095973550914d0385733-98666798%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e3d2fbb470707779853e82496697bd2cdcf7eff0' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/field-generator.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1292095973550914d0385733-98666798',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'field_groups' => 0,
    'group_name' => 0,
    'fields' => 0,
    'field' => 0,
    'key' => 0,
    'listing' => 0,
    'field_name' => 0,
    'field_val' => 0,
    'image_name' => 0,
    'image_path' => 0,
    'image_title' => 0,
    'image_title_key' => 0,
    'images' => 0,
    'indx' => 0,
    'image_titles' => 0,
    'image' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d04bdd66_39839971',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d04bdd66_39839971')) {function content_550914d04bdd66_39839971($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_block_ia_add_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_add_js.php';
?><?php if (!empty($_smarty_tpl->tpl_vars['field_groups']->value)){?>
	<?php echo smarty_function_ia_hooker(array('name'=>'viewListingBeforeFieldsDisplay'),$_smarty_tpl);?>


	<!-- Grouped fields -->
	<ul class="nav nav-tabs" id="fieldTabs">
		<?php  $_smarty_tpl->tpl_vars['fields'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fields']->_loop = false;
 $_smarty_tpl->tpl_vars['group_name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['field_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fields']->key => $_smarty_tpl->tpl_vars['fields']->value){
$_smarty_tpl->tpl_vars['fields']->_loop = true;
 $_smarty_tpl->tpl_vars['group_name']->value = $_smarty_tpl->tpl_vars['fields']->key;
?>
			<?php if ('non_group'!=$_smarty_tpl->tpl_vars['group_name']->value){?>
				<li class="tab_<?php echo $_smarty_tpl->tpl_vars['group_name']->value;?>
"><a href="#tab-pane_<?php echo $_smarty_tpl->tpl_vars['group_name']->value;?>
" data-toggle="tab"><?php echo smarty_function_lang(array('key'=>"field_group_title_".((string)$_smarty_tpl->tpl_vars['group_name']->value)),$_smarty_tpl);?>
</a></li>
			<?php }?>
		<?php } ?>
	</ul>

	<div class="tab-content" id="fieldTabsContent">
		<?php  $_smarty_tpl->tpl_vars['fields'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['fields']->_loop = false;
 $_smarty_tpl->tpl_vars['group_name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['field_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['fields']->key => $_smarty_tpl->tpl_vars['fields']->value){
$_smarty_tpl->tpl_vars['fields']->_loop = true;
 $_smarty_tpl->tpl_vars['group_name']->value = $_smarty_tpl->tpl_vars['fields']->key;
?>
			<?php if ('non_group'!=$_smarty_tpl->tpl_vars['group_name']->value){?>
				<div class="tab-pane" id="tab-pane_<?php echo $_smarty_tpl->tpl_vars['group_name']->value;?>
">
			<?php }else{ ?>
				<?php $_smarty_tpl->_capture_stack[0][] = array('nogroup', null, null); ob_start(); ?>
			<?php }?>

			<div class="ia-wrap">
				<?php if (!empty($_smarty_tpl->tpl_vars['fields']->value)&&!is_array($_smarty_tpl->tpl_vars['fields']->value)){?>
					<?php echo $_smarty_tpl->tpl_vars['fields']->value;?>

				<?php }else{ ?>
					<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['fields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
						<div class="ia-field clearfix plain">
							<?php if (isset($_smarty_tpl->tpl_vars['key'])) {$_smarty_tpl->tpl_vars['key'] = clone $_smarty_tpl->tpl_vars['key'];
$_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['field']->value['name']; $_smarty_tpl->tpl_vars['key']->nocache = null; $_smarty_tpl->tpl_vars['key']->scope = 0;
} else $_smarty_tpl->tpl_vars['key'] = new Smarty_variable($_smarty_tpl->tpl_vars['field']->value['name'], null, 0);?>
							<?php if (isset($_smarty_tpl->tpl_vars['field_name'])) {$_smarty_tpl->tpl_vars['field_name'] = clone $_smarty_tpl->tpl_vars['field_name'];
$_smarty_tpl->tpl_vars['field_name']->value = "field_".((string)$_smarty_tpl->tpl_vars['field']->value['name']); $_smarty_tpl->tpl_vars['field_name']->nocache = null; $_smarty_tpl->tpl_vars['field_name']->scope = 0;
} else $_smarty_tpl->tpl_vars['field_name'] = new Smarty_variable("field_".((string)$_smarty_tpl->tpl_vars['field']->value['name']), null, 0);?>

							<?php if ($_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value]||'0'==$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value]){?>
								<div class="title"><?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['field_name']->value),$_smarty_tpl);?>
</div>

								<div class="content">
									<?php if (in_array($_smarty_tpl->tpl_vars['field']->value['type'],array('text','textarea','number'))){?>
										<?php echo $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value];?>

									<?php }elseif(in_array($_smarty_tpl->tpl_vars['field']->value['type'],array('combo','radio'))){?>
										<?php echo smarty_function_lang(array('key'=>"field_".((string)$_smarty_tpl->tpl_vars['field']->value['name'])."_".((string)$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value])),$_smarty_tpl);?>

									<?php }elseif('checkbox'==$_smarty_tpl->tpl_vars['field']->value['type']){?>
										<?php  $_smarty_tpl->tpl_vars['field_val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field_val']->_loop = false;
 $_from = explode(',',$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value]); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['field_val']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['field_val']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['field_val']->key => $_smarty_tpl->tpl_vars['field_val']->value){
$_smarty_tpl->tpl_vars['field_val']->_loop = true;
 $_smarty_tpl->tpl_vars['field_val']->iteration++;
 $_smarty_tpl->tpl_vars['field_val']->last = $_smarty_tpl->tpl_vars['field_val']->iteration === $_smarty_tpl->tpl_vars['field_val']->total;
?>
											<?php echo smarty_function_lang(array('key'=>"field_".((string)$_smarty_tpl->tpl_vars['field']->value['name'])."_".((string)$_smarty_tpl->tpl_vars['field_val']->value)),$_smarty_tpl);?>
<?php if (!$_smarty_tpl->tpl_vars['field_val']->last){?>,&nbsp;<?php }?>
										<?php } ?>
									<?php }elseif('storage'==$_smarty_tpl->tpl_vars['field']->value['type']){?>
										<a href="<?php echo @constant('IA_URL');?>
uploads/<?php echo $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value];?>
"><?php echo smarty_function_lang(array('key'=>'download'),$_smarty_tpl);?>
</a>
									<?php }elseif('image'==$_smarty_tpl->tpl_vars['field']->value['type']){?>
										<?php if (isset($_smarty_tpl->tpl_vars['image_name'])) {$_smarty_tpl->tpl_vars['image_name'] = clone $_smarty_tpl->tpl_vars['image_name'];
$_smarty_tpl->tpl_vars['image_name']->value = "small_".((string)$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value]); $_smarty_tpl->tpl_vars['image_name']->nocache = null; $_smarty_tpl->tpl_vars['image_name']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_name'] = new Smarty_variable("small_".((string)$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value]), null, 0);?>
										<?php if (isset($_smarty_tpl->tpl_vars['image_path'])) {$_smarty_tpl->tpl_vars['image_path'] = clone $_smarty_tpl->tpl_vars['image_path'];
$_smarty_tpl->tpl_vars['image_path']->value = (@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['image_name']->value); $_smarty_tpl->tpl_vars['image_path']->nocache = null; $_smarty_tpl->tpl_vars['image_path']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_path'] = new Smarty_variable((@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['image_name']->value), null, 0);?>
										<?php if (isset($_smarty_tpl->tpl_vars['image_title'])) {$_smarty_tpl->tpl_vars['image_title'] = clone $_smarty_tpl->tpl_vars['image_title'];
$_smarty_tpl->tpl_vars['image_title']->value = $_smarty_tpl->tpl_vars['listing']->value[((string)$_smarty_tpl->tpl_vars['key']->value)."_title"]; $_smarty_tpl->tpl_vars['image_title']->nocache = null; $_smarty_tpl->tpl_vars['image_title']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_title'] = new Smarty_variable($_smarty_tpl->tpl_vars['listing']->value[((string)$_smarty_tpl->tpl_vars['key']->value)."_title"], null, 0);?>

										<?php if (file_exists($_smarty_tpl->tpl_vars['image_path']->value)){?>
											<a href="<?php echo @constant('IA_URL');?>
uploads/<?php echo $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value];?>
" target="_blank" rel="ia_lightbox[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]"><?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['image_name']->value,'title'=>$_smarty_tpl->tpl_vars['image_title']->value),$_smarty_tpl);?>
</a>
										<?php }else{ ?>
											<a href="<?php echo @constant('IA_URL');?>
uploads/<?php echo $_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value];?>
" target="_blank" rel="ia_lightbox[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]"><?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value],'title'=>$_smarty_tpl->tpl_vars['image_title']->value),$_smarty_tpl);?>
</a>
										<?php }?>
									<?php }elseif('pictures'==$_smarty_tpl->tpl_vars['field']->value['type']){?>
										<?php if (isset($_smarty_tpl->tpl_vars['images'])) {$_smarty_tpl->tpl_vars['images'] = clone $_smarty_tpl->tpl_vars['images'];
$_smarty_tpl->tpl_vars['images']->value = explode(",",$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value]); $_smarty_tpl->tpl_vars['images']->nocache = null; $_smarty_tpl->tpl_vars['images']->scope = 0;
} else $_smarty_tpl->tpl_vars['images'] = new Smarty_variable(explode(",",$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['key']->value]), null, 0);?>
										<?php if (isset($_smarty_tpl->tpl_vars['image_title_key'])) {$_smarty_tpl->tpl_vars['image_title_key'] = clone $_smarty_tpl->tpl_vars['image_title_key'];
$_smarty_tpl->tpl_vars['image_title_key']->value = ((string)$_smarty_tpl->tpl_vars['key']->value)."_titles"; $_smarty_tpl->tpl_vars['image_title_key']->nocache = null; $_smarty_tpl->tpl_vars['image_title_key']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_title_key'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['key']->value)."_titles", null, 0);?>
										<?php if (isset($_smarty_tpl->tpl_vars['image_titles'])) {$_smarty_tpl->tpl_vars['image_titles'] = clone $_smarty_tpl->tpl_vars['image_titles'];
$_smarty_tpl->tpl_vars['image_titles']->value = explode(",",$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['image_title_key']->value]); $_smarty_tpl->tpl_vars['image_titles']->nocache = null; $_smarty_tpl->tpl_vars['image_titles']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_titles'] = new Smarty_variable(explode(",",$_smarty_tpl->tpl_vars['listing']->value[$_smarty_tpl->tpl_vars['image_title_key']->value]), null, 0);?>

										<?php if ('gallery'==$_smarty_tpl->tpl_vars['field']->value['pic_type']){?>
											<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['ia_add_media'][0][0]->add_media(array('files'=>'css:js/jquery/plugins/flexslider/flexslider'),$_smarty_tpl);?>


											<div class="ia-gallery__wrapper">
												<div class="flexslider ia-gallery">
													<ul class="slides">
														<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_smarty_tpl->tpl_vars['indx'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['images']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
 $_smarty_tpl->tpl_vars['indx']->value = $_smarty_tpl->tpl_vars['image']->key;
?>
															<?php if (isset($_smarty_tpl->tpl_vars['image_name'])) {$_smarty_tpl->tpl_vars['image_name'] = clone $_smarty_tpl->tpl_vars['image_name'];
$_smarty_tpl->tpl_vars['image_name']->value = "small_".((string)$_smarty_tpl->tpl_vars['image']->value); $_smarty_tpl->tpl_vars['image_name']->nocache = null; $_smarty_tpl->tpl_vars['image_name']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_name'] = new Smarty_variable("small_".((string)$_smarty_tpl->tpl_vars['image']->value), null, 0);?>
															<?php if (isset($_smarty_tpl->tpl_vars['image_path'])) {$_smarty_tpl->tpl_vars['image_path'] = clone $_smarty_tpl->tpl_vars['image_path'];
$_smarty_tpl->tpl_vars['image_path']->value = (@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['image_name']->value); $_smarty_tpl->tpl_vars['image_path']->nocache = null; $_smarty_tpl->tpl_vars['image_path']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_path'] = new Smarty_variable((@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['image_name']->value), null, 0);?>
															<?php if (isset($_smarty_tpl->tpl_vars['image_titles']->value[$_smarty_tpl->tpl_vars['indx']->value])){?>
																<?php if (isset($_smarty_tpl->tpl_vars['image_title'])) {$_smarty_tpl->tpl_vars['image_title'] = clone $_smarty_tpl->tpl_vars['image_title'];
$_smarty_tpl->tpl_vars['image_title']->value = $_smarty_tpl->tpl_vars['image_titles']->value[$_smarty_tpl->tpl_vars['indx']->value]; $_smarty_tpl->tpl_vars['image_title']->nocache = null; $_smarty_tpl->tpl_vars['image_title']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_title'] = new Smarty_variable($_smarty_tpl->tpl_vars['image_titles']->value[$_smarty_tpl->tpl_vars['indx']->value], null, 0);?>
															<?php }else{ ?>
																<?php if (isset($_smarty_tpl->tpl_vars['image_title'])) {$_smarty_tpl->tpl_vars['image_title'] = clone $_smarty_tpl->tpl_vars['image_title'];
$_smarty_tpl->tpl_vars['image_title']->value = ''; $_smarty_tpl->tpl_vars['image_title']->nocache = null; $_smarty_tpl->tpl_vars['image_title']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_title'] = new Smarty_variable('', null, 0);?>
															<?php }?>
												
															<li>
																<?php if (file_exists($_smarty_tpl->tpl_vars['image_path']->value)){?>
																	<a href="<?php echo @constant('IA_URL');?>
uploads/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" rel="ia_lightbox[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" title="<?php echo $_smarty_tpl->tpl_vars['image_title']->value;?>
"><?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['image']->value,'title'=>$_smarty_tpl->tpl_vars['image_title']->value,'class'=>'ia-gallery__image'),$_smarty_tpl);?>
</a>
																<?php }else{ ?>
																	<a href="<?php echo @constant('IA_URL');?>
uploads/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" rel="ia_lightbox[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" title="<?php echo $_smarty_tpl->tpl_vars['image_title']->value;?>
"><?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['image']->value,'title'=>$_smarty_tpl->tpl_vars['image_title']->value,'class'=>'ia-gallery__image'),$_smarty_tpl);?>
</a>
																<?php }?>
															</li>
														<?php } ?>
													</ul>
												</div>
												
												<div class="flexslider ia-gallery-carousel">
													<ul class="slides">
														<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_smarty_tpl->tpl_vars['indx'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['images']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
 $_smarty_tpl->tpl_vars['indx']->value = $_smarty_tpl->tpl_vars['image']->key;
?>
															<?php if (isset($_smarty_tpl->tpl_vars['image_name'])) {$_smarty_tpl->tpl_vars['image_name'] = clone $_smarty_tpl->tpl_vars['image_name'];
$_smarty_tpl->tpl_vars['image_name']->value = "small_".((string)$_smarty_tpl->tpl_vars['image']->value); $_smarty_tpl->tpl_vars['image_name']->nocache = null; $_smarty_tpl->tpl_vars['image_name']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_name'] = new Smarty_variable("small_".((string)$_smarty_tpl->tpl_vars['image']->value), null, 0);?>
															<?php if (isset($_smarty_tpl->tpl_vars['image_path'])) {$_smarty_tpl->tpl_vars['image_path'] = clone $_smarty_tpl->tpl_vars['image_path'];
$_smarty_tpl->tpl_vars['image_path']->value = (@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['image_name']->value); $_smarty_tpl->tpl_vars['image_path']->nocache = null; $_smarty_tpl->tpl_vars['image_path']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_path'] = new Smarty_variable((@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['image_name']->value), null, 0);?>
												
															<li>
																<?php if (file_exists($_smarty_tpl->tpl_vars['image_path']->value)){?>
																	<a href="#"><?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['image_name']->value),$_smarty_tpl);?>
</a>
																<?php }else{ ?>
																	<a href="#"><?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['image']->value),$_smarty_tpl);?>
</a>
																<?php }?>
															</li>
														<?php } ?>
													</ul>
												</div>
											</div>

											<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_add_js', array()); $_block_repeat=true; echo smarty_block_ia_add_js(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

// The slider being synced must be initialized first
$('.ia-gallery-carousel').flexslider(
{
	animation: "slide",
	controlNav: false,
	animationLoop: false,
	slideshow: false,
	itemWidth: 80,
	itemMargin: 5,
	asNavFor: '.ia-gallery',
	prevText: '',
	nextText: ''
});

$('.ia-gallery').flexslider(
{
	animation: "slide",
	controlNav: false,
	directionNav: false,
	animationLoop: false,
	slideshow: false,
	sync: ".ia-gallery-carousel"
});
											<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_add_js(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>


										<?php }else{ ?>
											<ul class="thumbnails ia-gallery--simple">
												<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_smarty_tpl->tpl_vars['indx'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['images']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value){
$_smarty_tpl->tpl_vars['image']->_loop = true;
 $_smarty_tpl->tpl_vars['indx']->value = $_smarty_tpl->tpl_vars['image']->key;
?>
													<?php if (isset($_smarty_tpl->tpl_vars['image_name'])) {$_smarty_tpl->tpl_vars['image_name'] = clone $_smarty_tpl->tpl_vars['image_name'];
$_smarty_tpl->tpl_vars['image_name']->value = "small_".((string)$_smarty_tpl->tpl_vars['image']->value); $_smarty_tpl->tpl_vars['image_name']->nocache = null; $_smarty_tpl->tpl_vars['image_name']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_name'] = new Smarty_variable("small_".((string)$_smarty_tpl->tpl_vars['image']->value), null, 0);?>
													<?php if (isset($_smarty_tpl->tpl_vars['image_path'])) {$_smarty_tpl->tpl_vars['image_path'] = clone $_smarty_tpl->tpl_vars['image_path'];
$_smarty_tpl->tpl_vars['image_path']->value = (@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['image_name']->value); $_smarty_tpl->tpl_vars['image_path']->nocache = null; $_smarty_tpl->tpl_vars['image_path']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_path'] = new Smarty_variable((@constant('IA_UPLOADS')).($_smarty_tpl->tpl_vars['image_name']->value), null, 0);?>

													<?php if (isset($_smarty_tpl->tpl_vars['image_titles']->value[$_smarty_tpl->tpl_vars['indx']->value])){?>
														<?php if (isset($_smarty_tpl->tpl_vars['image_title'])) {$_smarty_tpl->tpl_vars['image_title'] = clone $_smarty_tpl->tpl_vars['image_title'];
$_smarty_tpl->tpl_vars['image_title']->value = $_smarty_tpl->tpl_vars['image_titles']->value[$_smarty_tpl->tpl_vars['indx']->value]; $_smarty_tpl->tpl_vars['image_title']->nocache = null; $_smarty_tpl->tpl_vars['image_title']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_title'] = new Smarty_variable($_smarty_tpl->tpl_vars['image_titles']->value[$_smarty_tpl->tpl_vars['indx']->value], null, 0);?>
													<?php }else{ ?>
														<?php if (isset($_smarty_tpl->tpl_vars['image_title'])) {$_smarty_tpl->tpl_vars['image_title'] = clone $_smarty_tpl->tpl_vars['image_title'];
$_smarty_tpl->tpl_vars['image_title']->value = ''; $_smarty_tpl->tpl_vars['image_title']->nocache = null; $_smarty_tpl->tpl_vars['image_title']->scope = 0;
} else $_smarty_tpl->tpl_vars['image_title'] = new Smarty_variable('', null, 0);?>
													<?php }?>

													<li class="span2">
														<?php if (file_exists($_smarty_tpl->tpl_vars['image_path']->value)){?>
															<a href="<?php echo @constant('IA_URL');?>
uploads/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" rel="ia_lightbox[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" title="<?php echo $_smarty_tpl->tpl_vars['image_title']->value;?>
" class="thumbnail"><?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['image_name']->value,'title'=>$_smarty_tpl->tpl_vars['image_title']->value),$_smarty_tpl);?>
</a>
														<?php }else{ ?>
															<a href="<?php echo @constant('IA_URL');?>
uploads/<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" rel="ia_lightbox[<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
]" title="<?php echo $_smarty_tpl->tpl_vars['image_title']->value;?>
" class="thumbnail"><?php echo smarty_function_print_img(array('ups'=>true,'full'=>true,'fl'=>$_smarty_tpl->tpl_vars['image']->value,'title'=>$_smarty_tpl->tpl_vars['image_title']->value),$_smarty_tpl);?>
</a>
														<?php }?>
													</li>
												<?php } ?>
											</ul>
										<?php }?>
									<?php }?>
								</div>
							<?php }?>
						</div>
					<?php } ?>
				<?php }?>
			</div>

			<?php if ($_smarty_tpl->tpl_vars['group_name']->value!='non_group'){?>
				</div>
			<?php }else{ ?>
				<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
			<?php }?>
		<?php } ?>
	</div>

	<!-- non grouped fields -->
	<div id="fieldNonGroup"><?php echo Smarty::$_smarty_vars['capture']['nogroup'];?>
</div>

	<?php echo smarty_function_ia_hooker(array('name'=>'viewListingAfterFieldsDisplay'),$_smarty_tpl);?>

<?php }?><?php }} ?>