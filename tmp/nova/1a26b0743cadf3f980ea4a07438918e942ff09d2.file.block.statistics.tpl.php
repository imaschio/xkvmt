<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:49:57
         compiled from "/home/wwwsyaqd/public_html/templates/common/block.statistics.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173450710554f02fb5c3d060-75304298%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a26b0743cadf3f980ea4a07438918e942ff09d2' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/block.statistics.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173450710554f02fb5c3d060-75304298',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'num_listings' => 0,
    'num_categories' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f02fb5c54985_91983456',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f02fb5c54985_91983456')) {function content_54f02fb5c54985_91983456($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
?><table style="width: 100%">
	<tbody>
		<?php if (isset($_smarty_tpl->tpl_vars['num_listings']->value)){?>
			<tr>
				<td><?php echo smarty_function_lang(array('key'=>'total_num_listings'),$_smarty_tpl);?>
</td>
				<td class="text-right"><?php echo sprintf('%d',$_smarty_tpl->tpl_vars['num_listings']->value);?>
</td>
			</tr>
		<?php }?>

		<?php if (isset($_smarty_tpl->tpl_vars['num_categories']->value)){?>
			<tr>
				<td><?php echo smarty_function_lang(array('key'=>'total_num_categories'),$_smarty_tpl);?>
</td>
				<td class="text-right"><?php echo sprintf('%d',$_smarty_tpl->tpl_vars['num_categories']->value);?>
</td>
			</tr>
		<?php }?>

		<?php echo smarty_function_ia_hooker(array('name'=>'statisticsBlock'),$_smarty_tpl);?>

	</tbody>
</table><?php }} ?>