<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 17:13:19
         compiled from "/home/wwwsyaqd/public_html/plugins/searches/templates/block.last_searches.tpl" */ ?>
<?php /*%%SmartyHeaderCode:183953085754f0ebffb398c6-90379175%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5f87dd274ee856a34f18dd749bf72125753055c5' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/searches/templates/block.last_searches.tpl',
      1 => 1425025906,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183953085754f0ebffb398c6-90379175',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'last_search' => 0,
    'last' => 0,
    'lang' => 0,
    'key' => 0,
    'result' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f0ebffb6aae6_73968112',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f0ebffb6aae6_73968112')) {function content_54f0ebffb6aae6_73968112($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.truncate.php';
?><?php if (!empty($_smarty_tpl->tpl_vars['last_search']->value)){?>
<div class="search-log">
	<?php  $_smarty_tpl->tpl_vars['last'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['last']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['last_search']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['last']->key => $_smarty_tpl->tpl_vars['last']->value){
$_smarty_tpl->tpl_vars['last']->_loop = true;
?>
		<div class="item">
			<p class="count">
				<a href="<?php echo @constant('IA_URL');?>
search.php?what=<?php echo $_smarty_tpl->tpl_vars['last']->value['search_word'];?>
&type=1"><b><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['last']->value['search_word'],20,'...');?>
</b></a>
				<span> - <?php echo $_smarty_tpl->tpl_vars['lang']->value['searched'];?>
 <?php if ($_smarty_tpl->tpl_vars['last']->value['search_count']=='1'){?><?php echo $_smarty_tpl->tpl_vars['lang']->value['once'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['last']->value['search_count'];?>
 <?php echo $_smarty_tpl->tpl_vars['lang']->value['times'];?>
<?php }?></span>
			</p>
			<div class="results">
			<?php  $_smarty_tpl->tpl_vars['result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['result']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = unserialize($_smarty_tpl->tpl_vars['last']->value['search_result']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['result']->key => $_smarty_tpl->tpl_vars['result']->value){
$_smarty_tpl->tpl_vars['result']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['result']->key;
?>
				<p><?php echo $_smarty_tpl->tpl_vars['lang']->value[$_smarty_tpl->tpl_vars['key']->value];?>
: <b><?php echo $_smarty_tpl->tpl_vars['result']->value;?>
</b></p>
			<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
<?php }?><?php }} ?>