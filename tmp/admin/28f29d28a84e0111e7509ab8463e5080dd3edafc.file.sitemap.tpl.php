<?php /* Smarty version Smarty-3.1.13, created on 2015-03-19 05:25:40
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/sitemap.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2101916829550a9614b21888-78931180%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '28f29d28a84e0111e7509ab8463e5080dd3edafc' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/sitemap.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2101916829550a9614b21888-78931180',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'esynI18N' => 0,
    'disabled' => 0,
    'config' => 0,
    'items' => 0,
    'item' => 0,
    'count' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550a9614b69ad5_33793163',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550a9614b69ad5_33793163')) {function content_550a9614b69ad5_33793163($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo $_smarty_tpl->getSubTemplate ('box-header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>

<div style="margin: 10px 0 20px 0;">
	<label for="google_type" style="margin-right: 20px;"><input class="common" type="radio" id="google_type" name="type_sitemap" value="google" checked="checked"> Google</label>
	<label for="yahoo_type"><input type="radio" class="common" id="yahoo_type" name="type_sitemap" value="yahoo"> Yahoo</label>
</div>
<div>
	<span><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['start_from'];?>
</span>
	<input type="text" size="6" value="0" id="start_num" class="common">
	<span><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['total_items'];?>
</span>
	<input type="text" size="6" value="0" id="all" class="common">
	<input type="button" value="Create" id="start" class="common" <?php echo $_smarty_tpl->tpl_vars['disabled']->value;?>
 />
</div>

<div id="msg" style="margin: 15px 0;"></div>

<div style="position:relative;border:1px solid #76A9DC; height:33px; width:100%; background-color:#ffffff;color:#335B92;text-align:center;">
	<div id="percent" style="position:absolute;left:50%;top:10px;z-index:2;font-size:13px;font-weight:bold;">0%</div>
	<div id="progress_bar" style="height:33px; width:0%; background:url(templates/<?php echo $_smarty_tpl->tpl_vars['config']->value['admin_tmpl'];?>
/img/bgs/progress_bar.gif) left repeat-x;color:#335B92;"></div>
</div>

<script type="text/javascript">
<!--
intelli.sitemap = {};
intelli.sitemap.items = [];
intelli.sitemap.limit = 10000;
intelli.sitemap.start = 0;
intelli.sitemap.current = 0;
intelli.sitemap.stage = 1;
intelli.sitemap.pause = 1;
intelli.sitemap.url = 'controller.php?file=sitemap';
intelli.sitemap.type_sitemap = 'google';
intelli.sitemap.total = 0;
intelli.sitemap.stage_all = 0;
intelli.sitemap.percent = 0;

<?php  $_smarty_tpl->tpl_vars['count'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['count']->_loop = false;
 $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['count']->key => $_smarty_tpl->tpl_vars['count']->value){
$_smarty_tpl->tpl_vars['count']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->value = $_smarty_tpl->tpl_vars['count']->key;
?>
	intelli.sitemap.items.push(['<?php echo $_smarty_tpl->tpl_vars['item']->value;?>
','<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
']);
	intelli.sitemap.total = intelli.sitemap.total + <?php echo $_smarty_tpl->tpl_vars['count']->value;?>
;

	intelli.sitemap.stage_all = intelli.sitemap.stage_all + Math.ceil(<?php echo $_smarty_tpl->tpl_vars['count']->value;?>
 / intelli.sitemap.limit);
<?php } ?>

$('#all').val(intelli.sitemap.total);
//-->
</script>


<?php echo $_smarty_tpl->getSubTemplate ('box-footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<?php echo smarty_function_include_file(array('js'=>"js/admin/sitemap"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>