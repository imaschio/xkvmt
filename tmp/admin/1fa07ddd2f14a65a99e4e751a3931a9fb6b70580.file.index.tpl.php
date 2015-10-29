<?php /* Smarty version Smarty-3.1.13, created on 2015-03-25 03:12:50
         compiled from "/home/wwwsyaqd/public_html/plugins/yandexrank/admin/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:61736088555125ff2ae85e7-80217897%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1fa07ddd2f14a65a99e4e751a3931a9fb6b70580' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/yandexrank/admin/templates/index.tpl',
      1 => 1425025928,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '61736088555125ff2ae85e7-80217897',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'esynI18N' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55125ff2b1f817_44578834',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55125ff2b1f817_44578834')) {function content_55125ff2b1f817_44578834($_smarty_tpl) {?><div id="recount_yandex" <?php if (!isset($_GET['type'])||('recountyandex'!=$_GET['type'])){?>style="display: none;"<?php }?>>

<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['esynI18N']->value['recount_ya_rank']), 0);?>


<input type="text" size="6" value="0" id="start_num_yandex" class="common" />
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['recount'];?>
" id="start_yandex" class="common" />

<div style="margin-top: 30px; position:relative; border:1px solid #76A9DC; height:33px; width:100%; background-color:#ffffff;color:#335B92;text-align:center;">
	<div id="percent_yandex" style="position:absolute;left:50%;top:10px;z-index:2;font-size:13px;font-weight:bold;">0%</div>
	<div id="progress_bar_yandex" style="height:33px; width:0%; background: url(<?php echo @constant('IA_URL');?>
plugins/yandexrank/admin/templates/img/progress_bar.gif) left repeat-x;color:#335B92;"></div>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("box-footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


</div><?php }} ?>