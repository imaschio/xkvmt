<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/templates/gelio/layout.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1870906102550910492180d5-74020337%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a6c22410a1b61b7461e7bc2b1773bd33ac982dc' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/gelio/layout.tpl',
      1 => 1426326082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1870906102550910492180d5-74020337',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'config' => 0,
    'description' => 0,
    'keywords' => 0,
    'css_files' => 0,
    'img' => 0,
    'category' => 0,
    'view' => 0,
    'listings' => 0,
    'templates' => 0,
    'languages' => 0,
    'code' => 0,
    'language' => 0,
    'blocks' => 0,
    'search_alphas' => 0,
    'value' => 0,
    'breadcrumb' => 0,
    'header' => 0,
    '_content_' => 0,
    'phpVariables' => 0,
    'manageMode' => 0,
    'js_files' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55091049408638_39254164',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55091049408638_39254164')) {function content_55091049408638_39254164($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_print_css')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_css.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['config']->value['suffix'];?>
</title>
		<meta charset="<?php echo $_smarty_tpl->tpl_vars['config']->value['charset'];?>
">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php if (isset($_smarty_tpl->tpl_vars['description']->value)&&!empty($_smarty_tpl->tpl_vars['description']->value)){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['description']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" />
		<meta name="keywords" content="<?php if (isset($_smarty_tpl->tpl_vars['keywords']->value)&&!empty($_smarty_tpl->tpl_vars['keywords']->value)){?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['keywords']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" />
		<meta name="generator" content="eSyndiCat Business Directory Script <?php echo @constant('IA_VERSION');?>
" />
		<base href="<?php echo @constant('IA_URL');?>
" />

		<?php echo smarty_function_ia_print_css(array('files'=>$_smarty_tpl->tpl_vars['css_files']->value),$_smarty_tpl);?>


		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="js/bootstrap/js/shiv.js" type="text/javascript"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
ico/apple-touch-icon-57-precomposed.png">
		<link rel="shortcut icon" href="<?php echo @constant('IA_URL');?>
favicon.ico">
		<link rel="canonical" href="<?php echo @constant('IA_CANONICAL');?>
"/>

		<!-- RSS links inside category and view page -->
		<?php if (isset($_smarty_tpl->tpl_vars['category']->value)&&$_smarty_tpl->tpl_vars['category']->value['id']>=0){?>
			<link rel="alternate" type="application/rss+xml" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" href="<?php echo @constant('IA_URL');?>
feed.php?from=category&amp;id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" />
		<?php }?>
		
		<?php if (isset($_smarty_tpl->tpl_vars['view']->value)&&$_smarty_tpl->tpl_vars['view']->value!='random'&&isset($_smarty_tpl->tpl_vars['listings']->value)){?>
			<link rel="alternate" type="application/rss+xml" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
" href="<?php echo @constant('IA_URL');?>
feed.php?from=<?php echo $_smarty_tpl->tpl_vars['view']->value;?>
" />
		<?php }?>

		<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['ia_add_media'][0][0]->add_media(array('files'=>'jquery, bootstrap-awesome, intelli, js:js/jquery/plugins/flexslider/flexslider.min'),$_smarty_tpl);?>


		<?php echo smarty_function_ia_hooker(array('name'=>'headSection'),$_smarty_tpl);?>


		<!-- CUSTOM STYLES -->
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['templates']->value;?>
css/user-style.css"/>

		<!--[if lt IE 9]>
			<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['templates']->value;?>
css/ie.css"/>
		<![endif]-->
	</head>

	<body class="page-<?php echo @constant('IA_REALM');?>
">

		<section id="inventory" class="clearfix">
			<div class="container">
				<div class="brand pull-left">
					<a class="logo" href="<?php echo @constant('IA_URL');?>
">
						<?php if ($_smarty_tpl->tpl_vars['config']->value['site_logo']){?>
							<?php echo smarty_function_print_img(array('ups'=>true,'fl'=>$_smarty_tpl->tpl_vars['config']->value['site_logo'],'full'=>true,'title'=>$_smarty_tpl->tpl_vars['config']->value['site']),$_smarty_tpl);?>

						<?php }else{ ?>
							<?php echo smarty_function_print_img(array('fl'=>'logo.png','full'=>true,'alt'=>'eSyndiCat Web Directory Software'),$_smarty_tpl);?>

						<?php }?>
					</a>
				</div>

				<?php if ($_smarty_tpl->tpl_vars['config']->value['language_switch']&&count($_smarty_tpl->tpl_vars['languages']->value)>1){?>
					<ul class="nav nav-pills nav-langs pull-right">
						<?php  $_smarty_tpl->tpl_vars['language'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['language']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['languages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['language']->key => $_smarty_tpl->tpl_vars['language']->value){
$_smarty_tpl->tpl_vars['language']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['language']->key;
?>
							<li<?php if (@constant('IA_LANGUAGE')==$_smarty_tpl->tpl_vars['code']->value){?> class="active"<?php }?>><a href="<?php echo @constant('IA_CANONICAL');?>
?language=<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['language']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['language']->value;?>
</a></li>
						<?php } ?>
					</ul>
				<?php }?>

				<ul class="nav nav-pills nav-social pull-right">
					<li class="twitter"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['gelio_social_t'];?>
" title="Twitter"><i class="icon-twitter-sign"></i></a></li>
					<li class="facebook"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['gelio_social_f'];?>
" title="Facebook"><i class="icon-facebook-sign"></i></a></li>
					<li class="gplus"><a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['gelio_social_g'];?>
" title="Google+"><i class="icon-google-plus-sign"></i></a></li>
					<?php if (isset($_smarty_tpl->tpl_vars['listings']->value)){?>
						<li class="xml">
							<?php if (isset($_smarty_tpl->tpl_vars['category']->value['id'])){?>
								<a href="<?php echo @constant('IA_URL');?>
feed.php?from=category&amp;id=<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
"><i class="icon-rss-sign"></i></a>
							<?php }elseif(in_array($_smarty_tpl->tpl_vars['view']->value,array('popular','new','top'))){?>
								<a href="<?php echo @constant('IA_URL');?>
feed.php?from=<?php echo $_smarty_tpl->tpl_vars['view']->value;?>
"><i class="icon-rss-sign"></i></a>
							<?php }?>
						</li>
					<?php }?>

					<?php echo smarty_function_ia_hooker(array('name'=>'tplFrontHeaderAfterRSS'),$_smarty_tpl);?>

				</ul>

				<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['inventory'])===null||$tmp==='' ? null : $tmp)), 0);?>


				<?php echo smarty_function_ia_hooker(array('name'=>'tplFrontAfterInventory'),$_smarty_tpl);?>

			</div>
		</section>

		<header>
			<div class="container">
				<form action="<?php echo @constant('IA_URL');?>
search.php" method="get" class="form-inline pull-right" id="searchFormBar">
					<label>
						<input type="text" class="span2" name="what" value="<?php if (isset($_GET['what'])){?><?php echo htmlspecialchars($_GET['what'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" id="search_input" autocomplete="off" placeholder="<?php echo smarty_function_lang(array('key'=>'search_for'),$_smarty_tpl);?>
">
					</label>

					<?php echo smarty_function_ia_hooker(array('name'=>'tplFrontAfterSearchInputs'),$_smarty_tpl);?>


					<button type="submit" class="js-top-search-button btn btn-primary" title="<?php echo smarty_function_lang(array('key'=>'search'),$_smarty_tpl);?>
"><i class="icon-search"></i></button>
					<a href="<?php echo @constant('IA_URL');?>
search.php?adv=true" class="btn btn-success" title="<?php echo smarty_function_lang(array('key'=>'advanced_search'),$_smarty_tpl);?>
"><i class="icon-cog"></i></a>
				</form>
				<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['mainmenu'])===null||$tmp==='' ? null : $tmp)), 0);?>

			</div>
		</header>

		<div class="container"><?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['slider'])===null||$tmp==='' ? null : $tmp)), 0);?>
</div>

		<?php if ($_smarty_tpl->tpl_vars['config']->value['alphabetic_search']&&!empty($_smarty_tpl->tpl_vars['search_alphas']->value)){?>
			<section class="alpha-search">
				<div class="container">
					<div class="alphabetic-search text-center">
						<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['search_alphas']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
?>
							<?php if (!isset($_GET['alpha'])||$_GET['alpha']!=$_smarty_tpl->tpl_vars['value']->value){?>
								<a href="<?php echo @constant('IA_URL');?>
alpha/<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
/" class="btn btn-small"><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</a>
							<?php }else{ ?>
								<span><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</span>
							<?php }?>
						<?php } ?>
					</div>
				</div>
			</section>
		<?php }?>

		<div class="container">
			<div class="js-groupWrapper" data-position="verytop">
				<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['verytop'])===null||$tmp==='' ? null : $tmp)), 0);?>

			</div>
		</div>

		<section id="content">
			<div class="container">
				<div class="row">
					<?php if (isset($_smarty_tpl->tpl_vars['blocks']->value['left'])&&!empty($_smarty_tpl->tpl_vars['blocks']->value['left'])){?>
						<div class="js-groupWrapper span3" data-position="left">
							<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['left'])===null||$tmp==='' ? null : $tmp)), 0);?>

						</div>
					<?php }?>

					<?php if ((!isset($_smarty_tpl->tpl_vars['blocks']->value['left'])&&empty($_smarty_tpl->tpl_vars['blocks']->value['left']))||(!isset($_smarty_tpl->tpl_vars['blocks']->value['right'])&&empty($_smarty_tpl->tpl_vars['blocks']->value['right']))){?>
						<div class="span9">	
					<?php }else{ ?>
						<div class="span6">	
					<?php }?>
						<?php if (isset($_smarty_tpl->tpl_vars['breadcrumb']->value)&&'index'!=@constant('IA_REALM')){?>
							<div class="section breads">
								<?php echo $_smarty_tpl->getSubTemplate ('breadcrumb.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


								<?php echo smarty_function_ia_hooker(array('name'=>'afterBreadcrumb'),$_smarty_tpl);?>


							</div>
						<?php }?>

						<div class="js-groupWrapper top-blocks" data-position="top">
							<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['top'])===null||$tmp==='' ? null : $tmp)), 0);?>

						</div>

						<?php echo smarty_function_ia_hooker(array('name'=>'beforeMainContent'),$_smarty_tpl);?>


						<div class="content-wrap">
							<h1><?php echo (($tmp = @$_smarty_tpl->tpl_vars['header']->value)===null||$tmp==='' ? $_smarty_tpl->tpl_vars['title']->value : $tmp);?>
</h1>

							<?php echo $_smarty_tpl->getSubTemplate ('notification.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

							
							<?php echo $_smarty_tpl->tpl_vars['_content_']->value;?>


						</div>

						<?php echo smarty_function_ia_hooker(array('name'=>'afterMainContent'),$_smarty_tpl);?>


						<div class="content-bottom-blocks">
							<div class="row">
								<div class="js-groupWrapper span3" data-position="user1">
									<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['user1'])===null||$tmp==='' ? null : $tmp)), 0);?>

								</div>
								<div class="js-groupWrapper span3" data-position="user2">
									<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['user2'])===null||$tmp==='' ? null : $tmp)), 0);?>

								</div>
							</div>
						</div>

						<div class="js-groupWrapper bottom-blocks" data-position="bottom">
							<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['bottom'])===null||$tmp==='' ? null : $tmp)), 0);?>

						</div>
						
					</div><!-- /.span6 -->
					<?php if (isset($_smarty_tpl->tpl_vars['blocks']->value['right'])&&!empty($_smarty_tpl->tpl_vars['blocks']->value['right'])){?>
						<div class="js-groupWrapper span3" data-position="right">
							<?php echo $_smarty_tpl->getSubTemplate ("parse-blocks.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['right'])===null||$tmp==='' ? null : $tmp)), 0);?>

						</div>
					<?php }?>
				</div><!-- /.row -->
			</div><!-- /.container -->
		</section>

		<?php if (isset($_smarty_tpl->tpl_vars['blocks']->value['verybottom'])){?>
			<section class="section gray bordered">
				<div class="container">
					<div class="js-groupWrapper verybottom-blocks" data-position="verybottom">
						<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['verybottom'])===null||$tmp==='' ? null : $tmp)), 0);?>

					</div>
				</div>
			</section>
		<?php }?>

		<?php if (isset($_smarty_tpl->tpl_vars['blocks']->value['footer1'])||isset($_smarty_tpl->tpl_vars['blocks']->value['footer2'])||isset($_smarty_tpl->tpl_vars['blocks']->value['footer3'])||isset($_smarty_tpl->tpl_vars['blocks']->value['footer4'])){?>
			<section id="very-bottom" class="section dark bordered">
				<div class="container">
					<div class="row">
						<div class="js-groupWrapper span3" data-position="footer1">
							<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['footer1'])===null||$tmp==='' ? null : $tmp)), 0);?>

						</div>
						<div class="js-groupWrapper span3" data-position="footer2">
							<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['footer2'])===null||$tmp==='' ? null : $tmp)), 0);?>

						</div>
						<div class="js-groupWrapper span3" data-position="footer3">
							<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['footer3'])===null||$tmp==='' ? null : $tmp)), 0);?>

						</div>
						<div class="js-groupWrapper span3" data-position="footer4">
							<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['footer4'])===null||$tmp==='' ? null : $tmp)), 0);?>

						</div>
					</div>
				</div>
			</section>
		<?php }?>

		<footer class="section dark">
			<div class="container">
	
				<?php echo smarty_function_ia_hooker(array('name'=>'beforeFooterLinks'),$_smarty_tpl);?>

	
				<div class="row">
					<div class="span4">
						<p class="copyright">&copy; <?php echo smarty_modifier_date_format($_SERVER['REQUEST_TIME'],'%Y');?>
 <?php echo smarty_function_lang(array('key'=>'copyright'),$_smarty_tpl);?>
</p>
					</div>
					<div class="span8">
						<?php echo $_smarty_tpl->getSubTemplate ('parse-blocks.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('pos'=>(($tmp = @$_smarty_tpl->tpl_vars['blocks']->value['copyright'])===null||$tmp==='' ? null : $tmp)), 0);?>

					</div>
				</div>

				<?php echo smarty_function_ia_hooker(array('name'=>'afterFooterLinks'),$_smarty_tpl);?>

				
			</div>
		</footer>
		<div id="backToTop"><a href="#"><i class="icon-chevron-up"></i><br><?php echo smarty_function_lang(array('key'=>'top'),$_smarty_tpl);?>
</a></div>

		<noscript>
			<div class="js_notification"><?php echo smarty_function_lang(array('key'=>'error_javascript'),$_smarty_tpl);?>
</div>
		</noscript>

		<!-- thumbs preview start -->
		<div class="thumb">
			<div class="loading" style="display: none;"><?php echo smarty_function_print_img(array('fl'=>'ajax-loader.gif','full'=>true,'class'=>'spinner'),$_smarty_tpl);?>
</div>
		</div>
		<!-- thumbs preview end -->

		<?php echo smarty_function_ia_hooker(array('name'=>'footerBeforeIncludeJs'),$_smarty_tpl);?>


		<!-- include mandatory javascript files -->
		<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['ia_add_media'][0][0]->add_media(array('files'=>'footer'),$_smarty_tpl);?>


		<script type="text/javascript">
			<?php if (isset($_smarty_tpl->tpl_vars['phpVariables']->value)){?><?php echo $_smarty_tpl->tpl_vars['phpVariables']->value;?>
<?php }?>
			intelli.lang = intelli.lang['<?php echo $_smarty_tpl->tpl_vars['config']->value['lang'];?>
'];
		</script>

		<?php if ($_smarty_tpl->tpl_vars['manageMode']->value){?>
			<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['ia_add_media'][0][0]->add_media(array('files'=>'managemode'),$_smarty_tpl);?>


			<div id="mod_box" class="managemode">
				<p><?php echo smarty_function_lang(array('key'=>'youre_in_manage_mode'),$_smarty_tpl);?>
</p>
			</div>
		<?php }?>

		<?php if (isset($_GET['preview'])||isset($_SESSION['preview'])){?>
			<div id="mod_box" class="managemode">
				<p><?php echo smarty_function_lang(array('key'=>'youre_in_preview_mode'),$_smarty_tpl);?>
</p>
			</div>
		<?php }?>

		<?php echo smarty_function_ia_hooker(array('name'=>'beforeCloseTag'),$_smarty_tpl);?>


		<?php echo smarty_function_ia_print_js(array('files'=>$_smarty_tpl->tpl_vars['js_files']->value,'printcode'=>true),$_smarty_tpl);?>

	</body>
</html><?php }} ?>