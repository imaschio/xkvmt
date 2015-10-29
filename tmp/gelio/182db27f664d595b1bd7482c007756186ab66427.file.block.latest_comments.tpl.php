<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 01:42:33
         compiled from "/home/wwwsyaqd/public_html/plugins/comments/templates/block.latest_comments.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15640649455509104992d127-60886214%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '182db27f664d595b1bd7482c007756186ab66427' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/comments/templates/block.latest_comments.tpl',
      1 => 1425025904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15640649455509104992d127-60886214',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'latest_comments' => 0,
    'comment' => 0,
    'config' => 0,
    'lang' => 0,
    'oarticle' => 0,
    'onews' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55091049972242_07827536',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55091049972242_07827536')) {function content_55091049972242_07827536($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_modifier_truncate')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.truncate.php';
if (!is_callable('smarty_function_print_listing_url')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_listing_url.php';
?><?php if (!empty($_smarty_tpl->tpl_vars['latest_comments']->value)){?>
	<?php  $_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['comment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['latest_comments']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['comment']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['comment']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->key => $_smarty_tpl->tpl_vars['comment']->value){
$_smarty_tpl->tpl_vars['comment']->_loop = true;
 $_smarty_tpl->tpl_vars['comment']->iteration++;
 $_smarty_tpl->tpl_vars['comment']->last = $_smarty_tpl->tpl_vars['comment']->iteration === $_smarty_tpl->tpl_vars['comment']->total;
?>
		<div class="media ia-item one-latest-comment-<?php echo $_smarty_tpl->tpl_vars['comment']->value['id'];?>
">
			<div class="media-body">
				<div class="date text-small"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['comment']->value['author'], ENT_QUOTES, 'UTF-8', true);?>
 / <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
</div>
				<div>
					<?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['comment']->value['body']),100);?>

				</div>
				<div class="text-small">
					<?php if ($_smarty_tpl->tpl_vars['comment']->value['item']=='listings'){?>
						<a href="<?php echo smarty_function_print_listing_url(array('listing'=>$_smarty_tpl->tpl_vars['comment']->value['_item'],'details'=>true),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['lang']->value['check_listing'];?>
</a>
					<?php }elseif($_smarty_tpl->tpl_vars['comment']->value['item']=='articles'){?>
						<?php if (isset($_smarty_tpl->tpl_vars["oarticle"])) {$_smarty_tpl->tpl_vars["oarticle"] = clone $_smarty_tpl->tpl_vars["oarticle"];
$_smarty_tpl->tpl_vars["oarticle"]->value = $_smarty_tpl->tpl_vars['comment']->value['_item']; $_smarty_tpl->tpl_vars["oarticle"]->nocache = null; $_smarty_tpl->tpl_vars["oarticle"]->scope = 0;
} else $_smarty_tpl->tpl_vars["oarticle"] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value['_item'], null, 0);?>
						<a href="<?php echo @constant('IA_URL');?>
articles/<?php echo $_smarty_tpl->tpl_vars['oarticle']->value['id'];?>
-<?php echo $_smarty_tpl->tpl_vars['oarticle']->value['alias'];?>
.html"><?php echo $_smarty_tpl->tpl_vars['lang']->value['check_article'];?>
</a>
					<?php }elseif($_smarty_tpl->tpl_vars['comment']->value['item']=='news'){?>
						<?php if (isset($_smarty_tpl->tpl_vars["onews"])) {$_smarty_tpl->tpl_vars["onews"] = clone $_smarty_tpl->tpl_vars["onews"];
$_smarty_tpl->tpl_vars["onews"]->value = $_smarty_tpl->tpl_vars['comment']->value['_item']; $_smarty_tpl->tpl_vars["onews"]->nocache = null; $_smarty_tpl->tpl_vars["onews"]->scope = 0;
} else $_smarty_tpl->tpl_vars["onews"] = new Smarty_variable($_smarty_tpl->tpl_vars['comment']->value['_item'], null, 0);?>
						<a href="<?php echo $_smarty_tpl->tpl_vars['config']->value['esyn_url'];?>
news/<?php echo $_smarty_tpl->tpl_vars['onews']->value['id'];?>
-<?php echo $_smarty_tpl->tpl_vars['onews']->value['alias'];?>
.html"><?php echo $_smarty_tpl->tpl_vars['lang']->value['check_news'];?>
</a>
					<?php }else{ ?>
						<script type="text/javascript">$("one-latest-comment-<?php echo $_smarty_tpl->tpl_vars['comment']->value['id'];?>
").remove();</script>
					<?php }?>
				</div>
			</div>
		</div>
		<?php if (!$_smarty_tpl->tpl_vars['comment']->last){?><hr /><?php }?>
	<?php } ?>
<?php }?><?php }} ?>