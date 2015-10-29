<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:49:57
         compiled from "/home/wwwsyaqd/public_html/templates/common/menu-ul.tpl" */ ?>
<?php /*%%SmartyHeaderCode:164362431654f02fb58bf141-58164710%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce4a9e895c2eaa998fb3952d31bf97e7b0a27b2b' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/menu-ul.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '164362431654f02fb58bf141-58164710',
  'function' => 
  array (
    'menu' => 
    array (
      'parameter' => 
      array (
        'level' => 0,
      ),
      'compiled' => '',
    ),
  ),
  'variables' => 
  array (
    'category' => 0,
    'menus' => 0,
    'level' => 0,
    'class' => 0,
    'data' => 0,
    'config' => 0,
    'place' => 0,
    'menu' => 0,
    'page_name' => 0,
    'dropdown' => 0,
    'esynAccountInfo' => 0,
    'block' => 0,
  ),
  'has_nocache_code' => 0,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f02fb5a4c3b6_88978717',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f02fb5a4c3b6_88978717')) {function content_54f02fb5a4c3b6_88978717($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><?php if (!function_exists('smarty_template_function_menu')) {
    function smarty_template_function_menu($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['menu']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
<?php if ($_smarty_tpl->tpl_vars['category']->value){?>
	<?php if (isset($_smarty_tpl->tpl_vars["page_name"])) {$_smarty_tpl->tpl_vars["page_name"] = clone $_smarty_tpl->tpl_vars["page_name"];
$_smarty_tpl->tpl_vars["page_name"]->value = ("index_browse|").($_smarty_tpl->tpl_vars['category']->value['id']); $_smarty_tpl->tpl_vars["page_name"]->nocache = null; $_smarty_tpl->tpl_vars["page_name"]->scope = 0;
} else $_smarty_tpl->tpl_vars["page_name"] = new Smarty_variable(("index_browse|").($_smarty_tpl->tpl_vars['category']->value['id']), null, 0);?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['menus']->value)){?>
	<ul class="level_<?php echo $_smarty_tpl->tpl_vars['level']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['class']->value;?>
">
		<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['menu']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menu']->iteration++;
?>
			<?php if ($_smarty_tpl->tpl_vars['menu']->iteration>(($tmp = @$_smarty_tpl->tpl_vars['config']->value['max_top_menu_items'])===null||$tmp==='' ? 5 : $tmp)&&$_smarty_tpl->tpl_vars['level']->value<1&&'mainmenu'==$_smarty_tpl->tpl_vars['place']->value){?><?php $_smarty_tpl->_capture_stack[0][] = array('default', null, 'dropdown'); ob_start(); ?><?php }?>
			<li class="m_<?php echo $_smarty_tpl->tpl_vars['menu']->value['name'];?>
 
				<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['children'])&&$_smarty_tpl->tpl_vars['menu']->iteration<=(($tmp = @$_smarty_tpl->tpl_vars['config']->value['max_top_menu_items'])===null||$tmp==='' ? 5 : $tmp)){?> dropdown<?php }?>
				<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['children'])&&$_smarty_tpl->tpl_vars['menu']->iteration>(($tmp = @$_smarty_tpl->tpl_vars['config']->value['max_top_menu_items'])===null||$tmp==='' ? 5 : $tmp)){?> dropdown-submenu<?php }?>
				<?php if ($_smarty_tpl->tpl_vars['level']->value>=1&&isset($_smarty_tpl->tpl_vars['menu']->value['children'])){?> dropdown-submenu<?php }?>
				<?php if (@constant('IA_REALM')==$_smarty_tpl->tpl_vars['menu']->value['name']||$_smarty_tpl->tpl_vars['page_name']->value==$_smarty_tpl->tpl_vars['menu']->value['name']){?> active<?php }?>
				">
				<a href="<?php if ($_smarty_tpl->tpl_vars['menu']->value['url']){?><?php echo $_smarty_tpl->tpl_vars['menu']->value['url'];?>
<?php }else{ ?><?php echo @constant('IA_SELF');?>
#<?php }?>" 
					<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['children'])){?>class="dropdown-toggle" data-toggle="dropdown" data-target="#"<?php }?> 
					<?php if ($_smarty_tpl->tpl_vars['menu']->value['nofollow']=='1'){?>rel="nofollow"<?php }?> 
					<?php if ($_smarty_tpl->tpl_vars['menu']->value['new_window']=='1'){?>target="_blank"<?php }?>
					>
					<?php echo $_smarty_tpl->tpl_vars['menu']->value['title'];?>
<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['children'])&&$_smarty_tpl->tpl_vars['level']->value<1&&$_smarty_tpl->tpl_vars['menu']->iteration<=(($tmp = @$_smarty_tpl->tpl_vars['config']->value['max_top_menu_items'])===null||$tmp==='' ? 5 : $tmp)){?> <b class="caret"></b><?php }?>
				</a>
				<?php if (isset($_smarty_tpl->tpl_vars['menu']->value['children'])){?>
					<?php smarty_template_function_menu($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['menu']->value['children'],'level'=>$_smarty_tpl->tpl_vars['level']->value+1,'class'=>"dropdown-menu"));?>

				<?php }?>
			</li>
			<?php if ($_smarty_tpl->tpl_vars['menu']->iteration>(($tmp = @$_smarty_tpl->tpl_vars['config']->value['max_top_menu_items'])===null||$tmp==='' ? 5 : $tmp)&&$_smarty_tpl->tpl_vars['level']->value<1&&'mainmenu'==$_smarty_tpl->tpl_vars['place']->value){?><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php }?>
		<?php } ?>
		<?php if ($_smarty_tpl->tpl_vars['dropdown']->value&&$_smarty_tpl->tpl_vars['level']->value<1){?>
			<li class="m_more dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<?php echo smarty_function_lang(array('key'=>'main_menu_more'),$_smarty_tpl);?>

					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu pull-right" role="menu">
					<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dropdown']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['menu']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menu']->iteration++;
?>
						<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>

					<?php } ?>
				</ul>
			</li>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['config']->value['accounts']&&'inventory'==$_smarty_tpl->tpl_vars['place']->value&&$_smarty_tpl->tpl_vars['level']->value==0){?>
			<li class="nav-login dropdown<?php if ('account_login'==@constant('IA_REALM')){?> active<?php }?>">
				<?php if ($_smarty_tpl->tpl_vars['esynAccountInfo']->value){?>
					<a href="<?php echo @constant('IA_URL');?>
logout.php?action=logout"><?php echo smarty_function_lang(array('key'=>'logout'),$_smarty_tpl);?>
&nbsp;[<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['username'];?>
]</a>
				<?php }else{ ?>
					<?php if ('account_login'==@constant('IA_REALM')){?>
						<a href="<?php echo @constant('IA_URL');?>
login.php"><?php echo smarty_function_lang(array('key'=>'login'),$_smarty_tpl);?>
</a>
					<?php }else{ ?>
						<a href="<?php echo @constant('IA_URL');?>
login.php" data-toggle="dropdown" class="dropdown-toggle" data-target="#"><?php echo smarty_function_lang(array('key'=>'login'),$_smarty_tpl);?>
</a>
						<ul class="dropdown-menu pull-right">
							<li>
								<a class="close" href="#">&times;</a>
								<form action="<?php echo @constant('IA_URL');?>
login.php" method="post" class="form">
									<label><?php echo smarty_function_lang(array('key'=>'username'),$_smarty_tpl);?>
:
										<input type="text" tabindex="1" class="input-block-level" name="username" size="18" value="" />
									</label>
									<label><?php echo smarty_function_lang(array('key'=>'password'),$_smarty_tpl);?>
:
										<input type="password" tabindex="2" class="input-block-level" name="password" size="18" value="" />
									</label>
									<button type="submit" class="btn btn-block btn-warning" tabindex="6" name="login" value="login"><?php echo smarty_function_lang(array('key'=>'login'),$_smarty_tpl);?>
</button>

									<div class="actions">
										<a href="<?php echo @constant('IA_URL');?>
register.php" rel="nofollow"><?php echo smarty_function_lang(array('key'=>'register'),$_smarty_tpl);?>
</a>
										<a href="<?php echo @constant('IA_URL');?>
forgot.php"><?php echo smarty_function_lang(array('key'=>'forgot'),$_smarty_tpl);?>
</a>
									</div>
								</form>
							</li>
						</ul>
					<?php }?>
				<?php }?>
			</li>
		<?php }?>

		<?php if ($_smarty_tpl->tpl_vars['config']->value['accounts']&&'account'==$_smarty_tpl->tpl_vars['block']->value['name']&&$_smarty_tpl->tpl_vars['esynAccountInfo']->value){?>
			<li class="m_logout"><a href="<?php echo @constant('IA_URL');?>
logout.php?action=logout"><?php echo smarty_function_lang(array('key'=>'logout'),$_smarty_tpl);?>
&nbsp;[<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['username'];?>
]</a></li>
		<?php }?>
	</ul>
<?php }?>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php smarty_template_function_menu($_smarty_tpl,array('data'=>$_smarty_tpl->tpl_vars['menus']->value,'class'=>$_smarty_tpl->tpl_vars['class']->value));?>
<?php }} ?>