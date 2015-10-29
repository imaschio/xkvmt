<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 02:01:52
         compiled from "/home/wwwsyaqd/public_html/plugins/comments/templates/comments.tpl" */ ?>
<?php /*%%SmartyHeaderCode:747193690550914d062e2c2-07542288%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '02ce71c2212dbea7eb49c30928cec3add54d25ae' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/comments/templates/comments.tpl',
      1 => 1425025904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '747193690550914d062e2c2-07542288',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'comments' => 0,
    'comment' => 0,
    'config' => 0,
    'item' => 0,
    'comments_allow_submission' => 0,
    '_comments_submission_disabled' => 0,
    'lang' => 0,
    'comments_accounts' => 0,
    'esynAccountInfo' => 0,
    'error_comment_logged' => 0,
    'msg' => 0,
    'error' => 0,
    'body' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550914d06ab979_02199434',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550914d06ab979_02199434')) {function content_550914d06ab979_02199434($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
?><div id="comments_container" class="ia-comments">
	<div class="comments">
		<?php if ($_smarty_tpl->tpl_vars['comments']->value){?>
			<h3><?php echo smarty_function_lang(array('key'=>'comments'),$_smarty_tpl);?>
</h3>
			<?php  $_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['comment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['comments']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['comment']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['comment']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->key => $_smarty_tpl->tpl_vars['comment']->value){
$_smarty_tpl->tpl_vars['comment']->_loop = true;
 $_smarty_tpl->tpl_vars['comment']->iteration++;
 $_smarty_tpl->tpl_vars['comment']->last = $_smarty_tpl->tpl_vars['comment']->iteration === $_smarty_tpl->tpl_vars['comment']->total;
?>
				<div class="ia-item">
					<p class="date"><i class="icon-calendar"></i> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['comment']->value['date'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>
 <i class="icon-user"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['comment']->value['author'], ENT_QUOTES, 'UTF-8', true);?>
</p>
					<?php if ($_smarty_tpl->tpl_vars['config']->value['comments_rating']){?>
						<p><?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['star'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['star']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['name'] = 'star';
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['comment']->value['rating']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['star']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['star']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['star']['total']);
?><img src="plugins/comments/templates/img/gold.png" alt="" /><?php endfor; endif; ?></p>
					<?php }?>
					<div class="description"><?php echo $_smarty_tpl->tpl_vars['comment']->value['body'];?>
</div>
				</div>
				<?php if (!$_smarty_tpl->tpl_vars['comment']->last){?><hr /><?php }?>
			<?php } ?>
		<?php }?>
	</div>

	<div id="error" class="alert alert-danger" style="display:none;"></div>

	<h3><?php echo smarty_function_lang(array('key'=>'leave_comment'),$_smarty_tpl);?>
</h3>
	<div class="comments-form">
		<?php if (isset($_smarty_tpl->tpl_vars['comments_allow_submission'])) {$_smarty_tpl->tpl_vars['comments_allow_submission'] = clone $_smarty_tpl->tpl_vars['comments_allow_submission'];
$_smarty_tpl->tpl_vars['comments_allow_submission']->value = (("comments_allow_").($_smarty_tpl->tpl_vars['item']->value['item'])).("_submission"); $_smarty_tpl->tpl_vars['comments_allow_submission']->nocache = null; $_smarty_tpl->tpl_vars['comments_allow_submission']->scope = 0;
} else $_smarty_tpl->tpl_vars['comments_allow_submission'] = new Smarty_variable((("comments_allow_").($_smarty_tpl->tpl_vars['item']->value['item'])).("_submission"), null, 0);?>
		<?php if (isset($_smarty_tpl->tpl_vars['comments_accounts'])) {$_smarty_tpl->tpl_vars['comments_accounts'] = clone $_smarty_tpl->tpl_vars['comments_accounts'];
$_smarty_tpl->tpl_vars['comments_accounts']->value = (("comments_").($_smarty_tpl->tpl_vars['item']->value['item'])).("_accounts"); $_smarty_tpl->tpl_vars['comments_accounts']->nocache = null; $_smarty_tpl->tpl_vars['comments_accounts']->scope = 0;
} else $_smarty_tpl->tpl_vars['comments_accounts'] = new Smarty_variable((("comments_").($_smarty_tpl->tpl_vars['item']->value['item'])).("_accounts"), null, 0);?>
		<?php if (isset($_smarty_tpl->tpl_vars['_comments_submission_disabled'])) {$_smarty_tpl->tpl_vars['_comments_submission_disabled'] = clone $_smarty_tpl->tpl_vars['_comments_submission_disabled'];
$_smarty_tpl->tpl_vars['_comments_submission_disabled']->value = ($_smarty_tpl->tpl_vars['item']->value['item']).("_comments_submission_disabled"); $_smarty_tpl->tpl_vars['_comments_submission_disabled']->nocache = null; $_smarty_tpl->tpl_vars['_comments_submission_disabled']->scope = 0;
} else $_smarty_tpl->tpl_vars['_comments_submission_disabled'] = new Smarty_variable(($_smarty_tpl->tpl_vars['item']->value['item']).("_comments_submission_disabled"), null, 0);?>
		<?php if (isset($_smarty_tpl->tpl_vars['error_comment_logged'])) {$_smarty_tpl->tpl_vars['error_comment_logged'] = clone $_smarty_tpl->tpl_vars['error_comment_logged'];
$_smarty_tpl->tpl_vars['error_comment_logged']->value = ($_smarty_tpl->tpl_vars['item']->value['item']).("_error_comment_logged"); $_smarty_tpl->tpl_vars['error_comment_logged']->nocache = null; $_smarty_tpl->tpl_vars['error_comment_logged']->scope = 0;
} else $_smarty_tpl->tpl_vars['error_comment_logged'] = new Smarty_variable(($_smarty_tpl->tpl_vars['item']->value['item']).("_error_comment_logged"), null, 0);?>

		<?php if (!$_smarty_tpl->tpl_vars['config']->value[$_smarty_tpl->tpl_vars['comments_allow_submission']->value]){?>
			<div class="alert alert-info"><?php echo $_smarty_tpl->tpl_vars['lang']->value[$_smarty_tpl->tpl_vars['_comments_submission_disabled']->value];?>
</div>
		<?php }elseif(!$_smarty_tpl->tpl_vars['config']->value[$_smarty_tpl->tpl_vars['comments_accounts']->value]&&!$_smarty_tpl->tpl_vars['esynAccountInfo']->value){?>
			<div class="alert alert-info"><?php echo $_smarty_tpl->tpl_vars['lang']->value[$_smarty_tpl->tpl_vars['error_comment_logged']->value];?>
</div>
		<?php }else{ ?>
			<?php if (isset($_smarty_tpl->tpl_vars['msg']->value)){?>
				<?php if (!$_smarty_tpl->tpl_vars['error']->value){?>
					<script type="text/javascript">
						sessvars.$.clearMem();
					</script>
				<?php }?>
			<?php }?>
			<form action="" method="post" id="comment" class="ia-form">
				<?php if ($_smarty_tpl->tpl_vars['esynAccountInfo']->value){?>
					<input type="hidden" name="author" value="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['username'];?>
" />
					<input type="hidden" name="email" value="<?php echo $_smarty_tpl->tpl_vars['esynAccountInfo']->value['email'];?>
" />
				<?php }else{ ?>
					<div class="row-fluid">
						<div class="span6">
							<input type="text" class="input-block-level" value="<?php if (isset($_POST['author'])){?><?php echo htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" name="author" size="25" placeholder="<?php echo $_smarty_tpl->tpl_vars['lang']->value['comment_author'];?>
" />
						</div>
						<div class="span6">
							<input type="text" class="input-block-level" value="<?php if (isset($_POST['email'])){?><?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" name="email" size="25" placeholder="<?php echo $_smarty_tpl->tpl_vars['lang']->value['author_email'];?>
" />
						</div>
					</div>
				<?php }?>

				<?php if ($_smarty_tpl->tpl_vars['config']->value['comments_rating']){?>
					<div id="comment-rating" class="clearfix" style="margin-bottom: 12px;"></div>
				<?php }?>

				<label for="comment"><?php echo smarty_function_lang(array('key'=>'comment'),$_smarty_tpl);?>
</label>
				<textarea name="comment" class="input-block-level ckeditor_textarea" rows="6" cols="40" id="comment_form"><?php if (isset($_smarty_tpl->tpl_vars['body']->value)&&!empty($_smarty_tpl->tpl_vars['body']->value)){?><?php echo $_smarty_tpl->tpl_vars['body']->value;?>
<?php }?></textarea>
				<p class="help-block text-right"><?php echo $_smarty_tpl->tpl_vars['lang']->value['characters_left'];?>
: <input type="text" class="char-counter" id="comment_counter" /></p>

				<?php echo $_smarty_tpl->getSubTemplate ('captcha.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('style'=>'fixed'), 0);?>


				<div class="actions">
					<input type="hidden" name="item_id" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" />
					<input type="hidden" name="item_name" value="<?php echo $_smarty_tpl->tpl_vars['item']->value['item'];?>
" />
					<input type="submit" id="add_comment" name="add_comment" value="<?php echo $_smarty_tpl->tpl_vars['lang']->value['leave_comment'];?>
" class="btn btn-info"/>
				</div>
			</form>
			<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['ia_add_media'][0][0]->add_media(array('files'=>'js:js/ckeditor/ckeditor, js:js/intelli/intelli.textcounter, js:js/jquery/plugins/jquery.validate, js:plugins/comments/js/frontend/comment-rating, js:plugins/comments/js/frontend/comments, css:plugins/comments/templates/css/style'),$_smarty_tpl);?>

		<?php }?>
	</div>
</div><?php }} ?>