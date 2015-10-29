<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:52
         compiled from "/home/wwwsyaqd/public_html/plugins/googlemap/templates/block.googlemap.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1200197181550914d0736fe5-13338595%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd4c3a50c3ad84b956a30d0b31a1d6d482dc160b3' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/googlemap/templates/block.googlemap.tpl',
      1 => 1425025904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1200197181550914d0736fe5-13338595',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d073fba5_29305667',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d073fba5_29305667')) {function content_550914d073fba5_29305667($_smarty_tpl) {?><?php if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><div id="google_map" style="width: <?php echo $_smarty_tpl->tpl_vars['config']->value['googlemap_map_width'];?>
; height: <?php echo $_smarty_tpl->tpl_vars['config']->value['googlemap_map_height'];?>
; margin: 0 auto; max-width: none;"></div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<?php echo smarty_function_include_file(array('js'=>'plugins/googlemap/js/jquery/plugins/jquery.gomap.min, plugins/googlemap/js/frontend/infobubble.min, plugins/googlemap/js/frontend/google_map'),$_smarty_tpl);?>


<style type="text/css" media="screen">
.gmap-marker {
	color: #333; font-size: 12px; line-height: 15px; padding: 5px; height:auto!important; width:auto!important;
}
a.gmap-title {
	font-size: 13px; font-weight: bold; text-decoration:underline;
}
div.gmap-description {
	margin: 5px 0;
}
div.gmap-address {
	font-style: italic;
}
</style><?php }} ?>