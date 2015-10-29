<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:52
         compiled from "/home/wwwsyaqd/public_html/plugins/comments/templates/block.rating.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2073049975550914d06b3463-24907551%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '486eec174282d1f2c4643770d15c1f3627d69a2f' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/comments/templates/block.rating.tpl',
      1 => 1425025904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2073049975550914d06b3463-24907551',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'comment_rating' => 0,
    'item' => 0,
    'lang' => 0,
    'comments_rating_block_max' => 0,
    'config' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d06df409_90845667',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d06df409_90845667')) {function content_550914d06df409_90845667($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><?php if (isset($_smarty_tpl->tpl_vars['comment_rating']->value)&&!empty($_smarty_tpl->tpl_vars['comment_rating']->value)){?>

	<div id="comments_ratings"></div><br />

	<?php if (isset($_smarty_tpl->tpl_vars['comments_rating_block_max'])) {$_smarty_tpl->tpl_vars['comments_rating_block_max'] = clone $_smarty_tpl->tpl_vars['comments_rating_block_max'];
$_smarty_tpl->tpl_vars['comments_rating_block_max']->value = (("comments_").($_smarty_tpl->tpl_vars['item']->value['item'])).("_rating_block_max"); $_smarty_tpl->tpl_vars['comments_rating_block_max']->nocache = null; $_smarty_tpl->tpl_vars['comments_rating_block_max']->scope = 0;
} else $_smarty_tpl->tpl_vars['comments_rating_block_max'] = new Smarty_variable((("comments_").($_smarty_tpl->tpl_vars['item']->value['item'])).("_rating_block_max"), null, 0);?>
	<?php echo $_smarty_tpl->tpl_vars['lang']->value['comment_rating'];?>
:&nbsp;<span id="ratings_text"><?php echo sprintf("%.2f",$_smarty_tpl->tpl_vars['comment_rating']->value['rating']);?>
&nbsp;/&nbsp;<?php echo $_smarty_tpl->tpl_vars['config']->value[$_smarty_tpl->tpl_vars['comments_rating_block_max']->value];?>
&nbsp;(<?php echo $_smarty_tpl->tpl_vars['comment_rating']->value['num_votes'];?>
&nbsp;<?php if ($_smarty_tpl->tpl_vars['comment_rating']->value['num_votes']>1){?><?php echo $_smarty_tpl->tpl_vars['lang']->value['votes_cast'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['lang']->value['vote_cast'];?>
<?php }?>)</span>

	<input type="hidden" id="item_id" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" />
	<input type="hidden" id="item_name" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['item'];?>
" />
	<input type="hidden" id="current_comment_rating" value="<?php echo $_smarty_tpl->tpl_vars['comment_rating']->value['rating'];?>
" />
	<input type="hidden" id="comment_voted" value="<?php if ($_smarty_tpl->tpl_vars['comment_rating']->value['voted']){?>1<?php }else{ ?>0<?php }?>" />

	<?php echo smarty_function_ia_print_js(array('files'=>'plugins/comments/js/frontend/exstars, plugins/comments/js/frontend/ratings'),$_smarty_tpl);?>

<?php }?><?php }} ?>