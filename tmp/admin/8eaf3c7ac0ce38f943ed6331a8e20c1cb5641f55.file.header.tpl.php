<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:31:24
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:200574522555090dacf01650-34651890%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8eaf3c7ac0ce38f943ed6331a8e20c1cb5641f55' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/header.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '200574522555090dacf01650-34651890',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'config' => 0,
    'css' => 0,
    'js' => 0,
    'esynI18N' => 0,
    'currentAdmin' => 0,
    'adminHeaderMenu' => 0,
    'item' => 0,
    'update_msg' => 0,
    'adminMenu' => 0,
    'menu' => 0,
    'esyn_tabs' => 0,
    'esyn_tab' => 0,
    'esyn_tab_content_key' => 0,
    'esyn_tab_title_key' => 0,
    'breadcrumb' => 0,
    'notifications' => 0,
    'messages' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55090dad09cfd2_25334015',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55090dad09cfd2_25334015')) {function content_55090dad09cfd2_25334015($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_print_icon_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_icon_url.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
	<title><?php if (isset($_smarty_tpl->tpl_vars['gTitle']->value)){?><?php echo $_smarty_tpl->tpl_vars['gTitle']->value;?>
<?php }?>&nbsp;<?php echo $_smarty_tpl->tpl_vars['config']->value['suffix'];?>
</title>
	<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $_smarty_tpl->tpl_vars['config']->value['charset'];?>
">
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7;">
	<base href="<?php echo @constant('IA_ADMIN_URL');?>
">
	<link rel="shortcut icon" href="<?php echo @constant('IA_URL');?>
favicon.ico">

	<?php echo smarty_function_include_file(array('css'=>"js/ext/resources/css/ext-all, ".((string)@constant('IA_ADMIN_DIR'))."/templates/".((string)$_smarty_tpl->tpl_vars['config']->value['admin_tmpl'])."/css/style, js/jquery/plugins/jreject/css/jquery.reject"),$_smarty_tpl);?>


	<?php if (isset($_smarty_tpl->tpl_vars['css']->value)){?>
		<?php echo smarty_function_include_file(array('css'=>$_smarty_tpl->tpl_vars['css']->value),$_smarty_tpl);?>

	<?php }?>

	<?php echo smarty_function_include_file(array('js'=>'js/ext/ext-base, js/ext/ext-all, js/jquery/jquery, js/jquery/plugins/jquery.interface, js/jquery/plugins/jreject/js/jquery.reject'),$_smarty_tpl);?>

	<?php echo smarty_function_include_file(array('js'=>"js/intelli/intelli, js/intelli/intelli.admin, ".((string)@constant('IA_TMP_NAME'))."/cache/intelli.config, ".((string)@constant('IA_TMP_NAME'))."/cache/intelli.admin.lang.".((string)$_smarty_tpl->tpl_vars['config']->value['lang'])),$_smarty_tpl);?>


	<?php if (isset($_smarty_tpl->tpl_vars['js']->value)){?>
		<?php echo smarty_function_include_file(array('js'=>$_smarty_tpl->tpl_vars['js']->value),$_smarty_tpl);?>

	<?php }?>

	<?php echo smarty_function_ia_hooker(array('name'=>'adminHeadSection'),$_smarty_tpl);?>


	<script type="text/javascript">
		intelli.admin.lang = intelli.admin.lang['<?php echo $_smarty_tpl->tpl_vars['config']->value['lang'];?>
'];
		$(function($)
		{
			$.reject({
				reject: {  
					firefox1: true, 
					firefox2: true,
					konqueror: true,
					chrome1: true, 
					chrome2: true,
					opera7: true,
					opera8: true,
					opera9: true,
					opera10: true,
					msie5: true, 
					msie6: true, 
					msie7: true,
					msie8: true
				},
				imagePath: '<?php echo @constant('IA_URL');?>
js/jquery/plugins/jreject/images/',
				display: ['firefox','chrome','opera','safari']
			});
		});
	</script>
</head>

<body>

<noscript>
	<div class="js_notification"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['error_javascript'];?>
</div>
</noscript>

<!-- header start -->
<div class="header">

	<div class="logo"><a href="http://www.esyndicat.com/"><img src="templates/<?php echo $_smarty_tpl->tpl_vars['config']->value['admin_tmpl'];?>
/img/logos/logo.png" alt="eSyndiCat Directory Software" title="eSyndiCat Directory Script"/></a></div>

	<div class="header-buttons">
		<ul>
			<li><a class="inner" href="<?php echo @constant('IA_URL');?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['directory_home'];?>
</a></li>
		</ul>
	</div>

	<div class="login-info">
		<ul>
			<li><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['howdy'];?>
, <a href="controller.php?file=admins&amp;do=edit&amp;id=<?php echo $_smarty_tpl->tpl_vars['currentAdmin']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['currentAdmin']->value['username'];?>
</a></li>
			<li><a class="logout" href="logout.php" id="admin_logout"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['logout'];?>
</a></li>
		</ul>
	</div>

	<!-- header menu start -->
	<div class="header-menus">
		<div class="h-menu">
			<div class="h-menu-inner">
				<div class="jump-to">
					<span style="float:left;"><a><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['quick_jump_to'];?>
</a></span>
					<span class="h-arrow">&nbsp;</span>
					<div style="clear:both;"></div>
				</div>
				<div class="h-submenu">
				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['adminHeaderMenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
					<?php if ('divider'==$_smarty_tpl->tpl_vars['item']->value['block_name']){?>
						<div class="h-divider"></div>
					<?php }else{ ?>
						<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['href'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['item']->value['attr'])&&!empty($_smarty_tpl->tpl_vars['item']->value['attr'])){?><?php echo $_smarty_tpl->tpl_vars['item']->value['attr'];?>
<?php }?>><?php echo $_smarty_tpl->tpl_vars['item']->value['text'];?>
</a>
					<?php }?>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- header menu end -->
</div>
<!-- header end -->

<div class="top-menu" id="top_menu"><?php if (isset($_smarty_tpl->tpl_vars['update_msg']->value)&&!empty($_smarty_tpl->tpl_vars['update_msg']->value)){?><?php echo $_smarty_tpl->tpl_vars['update_msg']->value;?>
<?php }?></div>

<!-- content start -->
<div class="content" id="mainCon">

	<!-- left column start -->
	<div class="left-column" id="left-column">
		<?php  $_smarty_tpl->tpl_vars["menu"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["menu"]->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['adminMenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["menu"]->key => $_smarty_tpl->tpl_vars["menu"]->value){
$_smarty_tpl->tpl_vars["menu"]->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars["menu"]->key;
?>
			<!-- menu start -->
			<div class="menu dragGroup" id="menu_box_<?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
" <?php if (!isset($_smarty_tpl->tpl_vars['menu']->value['items'])){?>style="display:none;"<?php }?>>
				<div class="inner">
					<div class="menu-caption"><?php echo $_smarty_tpl->tpl_vars['menu']->value['text'];?>
</div>
					<div class="minmax white-<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['open'])){?><?php echo $_smarty_tpl->tpl_vars['menu']->value['open'];?>
<?php }else{ ?>open<?php }?>" id="amenu_<?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
"></div>
					<div class="box-content" style="padding: 0; <?php if (isset($_smarty_tpl->tpl_vars['menu']->value['open'])&&$_smarty_tpl->tpl_vars['menu']->value['open']=='close'){?>display:none;<?php }?>" >
						<ul class="menu" id="menu_<?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
">
						<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['items'])&&!empty($_smarty_tpl->tpl_vars['menu']->value['items'])){?>
							<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu']->value['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
								<li<?php if (@constant('IA_REALM')==$_smarty_tpl->tpl_vars['item']->value['aco']){?> class="active"<?php }?>><a <?php echo smarty_function_print_icon_url(array('realm'=>$_smarty_tpl->tpl_vars['item']->value['aco'],'path'=>'menu'),$_smarty_tpl);?>
 href="<?php echo $_smarty_tpl->tpl_vars['item']->value['href'];?>
" <?php if (isset($_smarty_tpl->tpl_vars['item']->value['attr'])&&!empty($_smarty_tpl->tpl_vars['item']->value['attr'])){?><?php echo $_smarty_tpl->tpl_vars['item']->value['attr'];?>
<?php }?> class="submenu"><?php echo $_smarty_tpl->tpl_vars['item']->value['text'];?>
</a></li>
							<?php } ?>
						<?php }?>
						</ul>
					</div>
				</div>
			</div>
			<!-- menu end -->
		<?php } ?>
	</div>
	<!-- left column end -->

	<!-- right column start -->
	<div class="right-column">

		<?php if (isset($_smarty_tpl->tpl_vars['esyn_tabs']->value)&&!empty($_smarty_tpl->tpl_vars['esyn_tabs']->value)){?>
			<div class="empty-div"></div>
			<?php  $_smarty_tpl->tpl_vars['esyn_tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['esyn_tab']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['esyn_tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['esyn_tab']->key => $_smarty_tpl->tpl_vars['esyn_tab']->value){
$_smarty_tpl->tpl_vars['esyn_tab']->_loop = true;
?>
				<div class="tab-content <?php echo $_smarty_tpl->tpl_vars['esyn_tab']->value['name'];?>
" id="esyntab-content-<?php echo $_smarty_tpl->tpl_vars['esyn_tab']->value['name'];?>
">
					<div class="tab-content-inner">
						<?php if (isset($_smarty_tpl->tpl_vars["esyn_tab_content_key"])) {$_smarty_tpl->tpl_vars["esyn_tab_content_key"] = clone $_smarty_tpl->tpl_vars["esyn_tab_content_key"];
$_smarty_tpl->tpl_vars["esyn_tab_content_key"]->value = "tab_content_".((string)$_smarty_tpl->tpl_vars['esyn_tab']->value['name']); $_smarty_tpl->tpl_vars["esyn_tab_content_key"]->nocache = null; $_smarty_tpl->tpl_vars["esyn_tab_content_key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["esyn_tab_content_key"] = new Smarty_variable("tab_content_".((string)$_smarty_tpl->tpl_vars['esyn_tab']->value['name']), null, 0);?>
						<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['esyn_tab_content_key']->value];?>

					</div>
				</div>
			<?php } ?>

			<?php  $_smarty_tpl->tpl_vars['esyn_tab'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['esyn_tab']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['esyn_tabs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['esyn_tab']->key => $_smarty_tpl->tpl_vars['esyn_tab']->value){
$_smarty_tpl->tpl_vars['esyn_tab']->_loop = true;
?>
				<div class="tab-shortcut" id="esyntab-shortcut-<?php echo $_smarty_tpl->tpl_vars['esyn_tab']->value['name'];?>
">
					<div class="tab-shortcut-inner">
						<?php if (isset($_smarty_tpl->tpl_vars["esyn_tab_title_key"])) {$_smarty_tpl->tpl_vars["esyn_tab_title_key"] = clone $_smarty_tpl->tpl_vars["esyn_tab_title_key"];
$_smarty_tpl->tpl_vars["esyn_tab_title_key"]->value = "tab_title_".((string)$_smarty_tpl->tpl_vars['esyn_tab']->value['name']); $_smarty_tpl->tpl_vars["esyn_tab_title_key"]->nocache = null; $_smarty_tpl->tpl_vars["esyn_tab_title_key"]->scope = 0;
} else $_smarty_tpl->tpl_vars["esyn_tab_title_key"] = new Smarty_variable("tab_title_".((string)$_smarty_tpl->tpl_vars['esyn_tab']->value['name']), null, 0);?>
						<div class="tab-icon tab-icon-<?php echo $_smarty_tpl->tpl_vars['esyn_tab']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value[$_smarty_tpl->tpl_vars['esyn_tab_title_key']->value];?>
</div>
					</div>
				</div>
			<?php } ?>
		<?php }?>

		<?php echo $_smarty_tpl->tpl_vars['breadcrumb']->value;?>


		<?php if (isset($_smarty_tpl->tpl_vars['gTitle']->value)){?><h1 class="common"<?php echo smarty_function_print_icon_url(array('realm'=>@constant('IA_REALM'),'header'=>true),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['gTitle']->value;?>
</h1><?php }?>

		<?php echo $_smarty_tpl->getSubTemplate ('buttons.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


		<?php if (isset($_smarty_tpl->tpl_vars['notifications']->value)&&!empty($_smarty_tpl->tpl_vars['notifications']->value)){?>
			<?php echo $_smarty_tpl->getSubTemplate ('notification.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('msg'=>$_smarty_tpl->tpl_vars['notifications']->value,'id'=>'notif'), 0);?>

		<?php }?>

		<?php echo $_smarty_tpl->getSubTemplate ('notification.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('messages'=>$_smarty_tpl->tpl_vars['messages']->value), 0);?>
<?php }} ?>