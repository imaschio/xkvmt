<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:52
         compiled from "/home/wwwsyaqd/public_html/templates/common/block.actions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2102528820550914d06e4365-58279179%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '50c275b491f17abc4b25e4bc762bfe4f11d2b1e4' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/block.actions.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2102528820550914d06e4365-58279179',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'esyndicat_actions' => 0,
    'name' => 0,
    'esynAccountInfo' => 0,
    'listing' => 0,
    'fav_array' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d0732920_69757003',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d0732920_69757003')) {function content_550914d0732920_69757003($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><?php if (isset($_smarty_tpl->tpl_vars['esyndicat_actions']->value)&&!empty($_smarty_tpl->tpl_vars['esyndicat_actions']->value)){?>
	<ul class="nav nav-actions">
		<?php  $_smarty_tpl->tpl_vars['action'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['action']->_loop = false;
 $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['esyndicat_actions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['action']->key => $_smarty_tpl->tpl_vars['action']->value){
$_smarty_tpl->tpl_vars['action']->_loop = true;
 $_smarty_tpl->tpl_vars['name']->value = $_smarty_tpl->tpl_vars['action']->key;
?>
			<?php if ('favorites'==$_smarty_tpl->tpl_vars['name']->value){?>
				<?php if ($_smarty_tpl->tpl_vars['esynAccountInfo']->value&&$_smarty_tpl->tpl_vars['esynAccountInfo']->value['id']!=$_smarty_tpl->tpl_vars['listing']->value['account_id']){?>
					<li>
					<?php if (isset($_smarty_tpl->tpl_vars['fav_array'])) {$_smarty_tpl->tpl_vars['fav_array'] = clone $_smarty_tpl->tpl_vars['fav_array'];
$_smarty_tpl->tpl_vars['fav_array']->value = explode(',',$_smarty_tpl->tpl_vars['listing']->value['fav_accounts_set']); $_smarty_tpl->tpl_vars['fav_array']->nocache = null; $_smarty_tpl->tpl_vars['fav_array']->scope = 0;
} else $_smarty_tpl->tpl_vars['fav_array'] = new Smarty_variable(explode(',',$_smarty_tpl->tpl_vars['listing']->value['fav_accounts_set']), null, 0);?>
					<?php if (!empty($_smarty_tpl->tpl_vars['fav_array']->value)&&in_array($_smarty_tpl->tpl_vars['esynAccountInfo']->value['id'],$_smarty_tpl->tpl_vars['fav_array']->value)){?>
						<a href="#" class="js-favorites" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-account="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['id'];?>
" data-action="remove"><?php echo smarty_function_lang(array('key'=>'remove_from_favorites'),$_smarty_tpl);?>
</a>
					<?php }else{ ?>
						<a href="#" class="js-favorites" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-account="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['id'];?>
" data-action="add"><?php echo smarty_function_lang(array('key'=>'add_to_favorites'),$_smarty_tpl);?>
</a>
					<?php }?>
					</li>
				<?php }elseif($_smarty_tpl->tpl_vars['esynAccountInfo']->value&&$_smarty_tpl->tpl_vars['esynAccountInfo']->value['id']==$_smarty_tpl->tpl_vars['listing']->value['account_id']){?>
					<li><a href="<?php echo @constant('IA_URL');?>
suggest-listing.php?edit=<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" class="js-edit-listing" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-account="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['id'];?>
" data-action="edit"><?php echo smarty_function_lang(array('key'=>'edit_listing'),$_smarty_tpl);?>
</a></li>
				<?php }?>
			<?php }else{ ?>
				<li><a href="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['action']->value['url'])===null||$tmp==='' ? '#' : $tmp);?>
" class="js-<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" data-listing-account="<?php echo $_smarty_tpl->tpl_vars['listing']->value['account_id'];?>
" data-account="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['id'];?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['listing']->value['url'];?>
"><?php echo smarty_function_lang(array('key'=>"esyndicat_action_".((string)$_smarty_tpl->tpl_vars['name']->value)),$_smarty_tpl);?>
</a></li>
			<?php }?>
		<?php } ?>
	</ul>
<?php }?><?php }} ?>