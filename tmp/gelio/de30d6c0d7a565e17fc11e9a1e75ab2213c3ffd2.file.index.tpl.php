<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 06:42:31
         compiled from "/home/wwwsyaqd/public_html/plugins/tagcloud/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:184457484955095697dc0f10-56414566%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'de30d6c0d7a565e17fc11e9a1e75ab2213c3ffd2' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/tagcloud/templates/index.tpl',
      1 => 1425025916,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '184457484955095697dc0f10-56414566',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tag' => 0,
    'tag_listings' => 0,
    'all_tags' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55095697e46e89_20770158',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55095697e46e89_20770158')) {function content_55095697e46e89_20770158($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
?><?php if (isset($_smarty_tpl->tpl_vars['tag']->value)){?>
	<?php if ($_smarty_tpl->tpl_vars['tag_listings']->value){?>
		<?php echo $_smarty_tpl->getSubTemplate ('ia-listings.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listings'=>$_smarty_tpl->tpl_vars['tag_listings']->value), 0);?>

	<?php }else{ ?>
		<div class="alert alert-info"><?php echo smarty_function_lang(array('key'=>'tags_no_listings'),$_smarty_tpl);?>
</div>
	<?php }?>
<?php }elseif($_smarty_tpl->tpl_vars['all_tags']->value){?>
	<?php  $_smarty_tpl->tpl_vars['tag'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tag']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['all_tags']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['tag']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['tag']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->key => $_smarty_tpl->tpl_vars['tag']->value){
$_smarty_tpl->tpl_vars['tag']->_loop = true;
 $_smarty_tpl->tpl_vars['tag']->iteration++;
 $_smarty_tpl->tpl_vars['tag']->last = $_smarty_tpl->tpl_vars['tag']->iteration === $_smarty_tpl->tpl_vars['tag']->total;
?>
		<a href="mod/tagcloud/<?php echo urlencode(htmlspecialchars($_smarty_tpl->tpl_vars['tag']->value['tag'], ENT_QUOTES, 'UTF-8', true));?>
" style="font-size: <?php echo $_smarty_tpl->tpl_vars['tag']->value['size'];?>
%"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tag']->value['tag'], ENT_QUOTES, 'UTF-8', true);?>
</a><?php if (!$_smarty_tpl->tpl_vars['tag']->last){?>, <?php }?>
	<?php } ?>
<?php }else{ ?>
	<div class="alert alert-info"><?php echo smarty_function_lang(array('key'=>'tags_no_listings'),$_smarty_tpl);?>
</div>
<?php }?><?php }} ?>