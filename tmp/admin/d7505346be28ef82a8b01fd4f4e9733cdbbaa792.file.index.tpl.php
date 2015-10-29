<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 03:09:57
         compiled from "/home/wwwsyaqd/public_html/admin/templates/default/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17052960550924c55621e3-19991827%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd7505346be28ef82a8b01fd4f4e9733cdbbaa792' => 
    array (
      0 => '/home/wwwsyaqd/public_html/admin/templates/default/index.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17052960550924c55621e3-19991827',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'esynI18N' => 0,
    'listings' => 0,
    'broken_listings' => 0,
    'no_reciprocal_listings' => 0,
    'reciprocal_listings' => 0,
    'config' => 0,
    'sponsored_listings' => 0,
    'featured_listings' => 0,
    'partner_listings' => 0,
    'all_listings' => 0,
    'approval' => 0,
    'active' => 0,
    'summary' => 0,
    'currentAdmin' => 0,
    'approval_accounts' => 0,
    'active_accounts' => 0,
    'unconfirmed_accounts' => 0,
    'all_accounts' => 0,
    'esyndicat_new_plugins' => 0,
    'plugin' => 0,
    'timeline' => 0,
    'tweet' => 0,
    'changelog' => 0,
    'changelog_titles' => 0,
    'index' => 0,
    'item' => 0,
    'items' => 0,
    'list' => 0,
    'class' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_550924c56a9a93_28513316',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_550924c56a9a93_28513316')) {function content_550924c56a9a93_28513316($_smarty_tpl) {?><?php if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_modifier_date_format')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.date_format.php';
if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<div id="box_panels_content" style="margin-top: 15px;"></div>

<div id="box_statistics" style="display: none;">
	<table width="99%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="49%" valign="top">
			<table cellspacing="0" class="striped common">
			<tr>
				<th width="90%" class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['listings'];?>
</th>
				<th width="50">&nbsp;</th>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=suspended"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['suspended'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['listings']->value[2]['total'];?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=banned"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banned'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['listings']->value[1]['total'];?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=approval"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['listings']->value[0]['total'];?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=active"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['listings']->value[3]['total'];?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;status=deleted"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['deleted'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['listings']->value[4]['total'];?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;state=destbroken"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['broken'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['broken_listings']->value;?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;state=recipbroken"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['nonrecip'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['no_reciprocal_listings']->value;?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;state=recipvalid"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['reciprocal'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['reciprocal_listings']->value;?>
</strong>
				</td>
			</tr>
		
			<?php if ($_smarty_tpl->tpl_vars['config']->value['sponsored_listings']){?>
				<tr>
					<td class="first">
						<a href="controller.php?file=listings&amp;type=sponsored"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['sponsored'];?>
</a>:
					</td>
					<td>
						<strong><?php echo $_smarty_tpl->tpl_vars['sponsored_listings']->value;?>
</strong>
					</td>
				</tr>
			<?php }?>
		
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;type=featured"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['featured'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['featured_listings']->value;?>
</strong>
				</td>
			</tr>
		
			<tr>
				<td class="first">
					<a href="controller.php?file=listings&amp;type=partner"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['partner'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['partner_listings']->value;?>
</strong>
				</td>
			</tr>
		
			<tr class="last">
				<td class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['total'];?>
:</td>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['all_listings']->value;?>
</strong></td>
			</tr>
			</table>
			
			<?php echo smarty_function_ia_hooker(array('name'=>"adminIndexStats1"),$_smarty_tpl);?>

			
		</td>
		<td style="padding-left: 15px; vertical-align: top;">
			<table cellspacing="0" class="common striped" width="99%">
			<tr>
				<th width="90%" class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['categories'];?>
</th>
				<th width="50">&nbsp;</th>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=categories&amp;status=approval"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['approval']->value;?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=categories&amp;status=active"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['active']->value;?>
</strong>
				</td>
			</tr>
			<tr class="last">
				<td class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['total'];?>
:</td>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['summary']->value;?>
</strong></td>
			</tr>
			</table>
		
			<?php if ($_smarty_tpl->tpl_vars['config']->value['accounts']&&$_smarty_tpl->tpl_vars['currentAdmin']->value['super']){?>
			<table cellspacing="0" class="common striped" width="99%">
			<tr>
				<th width="90%" class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['accounts'];?>
</th>
				<th width="50">&nbsp;</th>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=accounts&amp;status=approval"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['approval'];?>
</a>:
				</td>
		
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['approval_accounts']->value;?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=accounts&amp;status=active"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['active'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['active_accounts']->value;?>
</strong>
				</td>
			</tr>
			<tr>
				<td class="first">
					<a href="controller.php?file=accounts&amp;status=unconfirmed"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['unconfirmed'];?>
</a>:
				</td>
				<td>
					<strong><?php echo $_smarty_tpl->tpl_vars['unconfirmed_accounts']->value;?>
</strong>
				</td>
			</tr>
			<tr class="last">
				<td class="first"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['total'];?>
:</td>
				<td><strong><?php echo $_smarty_tpl->tpl_vars['all_accounts']->value;?>
</strong></td>
			</tr>
			</table>
			<?php }?>
			
			<?php echo smarty_function_ia_hooker(array('name'=>"adminIndexStats2"),$_smarty_tpl);?>

			
		</td>
	</tr>
	</table>
</div>

<?php echo smarty_function_ia_hooker(array('name'=>'adminIndexPage'),$_smarty_tpl);?>


<?php if ($_smarty_tpl->tpl_vars['config']->value['display_new_plugins']&&isset($_smarty_tpl->tpl_vars['esyndicat_new_plugins']->value['items'])&&!empty($_smarty_tpl->tpl_vars['esyndicat_new_plugins']->value['items'])){?>
	<div id="box_new_plugins" style="display: none;">
		<table cellspacing="0" class="striped">
		<?php  $_smarty_tpl->tpl_vars['plugin'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['plugin']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['esyndicat_new_plugins']->value['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['plugin']->key => $_smarty_tpl->tpl_vars['plugin']->value){
$_smarty_tpl->tpl_vars['plugin']->_loop = true;
?>
			<tr>
				<td>
					<a href="<?php echo $_smarty_tpl->tpl_vars['plugin']->value['link'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['plugin']->value['title'];?>
</a><br />
					&nbsp;<?php echo $_smarty_tpl->tpl_vars['plugin']->value['description'];?>

				</td>
			</tr>
		<?php } ?>
		</table>
	</div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['config']->value['display_twitter']&&!empty($_smarty_tpl->tpl_vars['timeline']->value)){?>
	<div id="box_twitter" class="twitter" style="display: none;">
		<ul>
		<?php  $_smarty_tpl->tpl_vars['tweet'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tweet']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['timeline']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tweet']->key => $_smarty_tpl->tpl_vars['tweet']->value){
$_smarty_tpl->tpl_vars['tweet']->_loop = true;
?>
			<li>
				<div class="date">
					<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['tweet']->value['created_at'],$_smarty_tpl->tpl_vars['config']->value['date_format']);?>

				</div>
				<div class="avatar">
					<a href="<?php echo $_smarty_tpl->tpl_vars['tweet']->value['url'];?>
"><img src="http://static.intelliants.com/media/logotypes/intelliants-logo-symbol-80-inverse.png" alt=""></a>
				</div>
				<div class="tweet">
					<h5><a href="<?php echo $_smarty_tpl->tpl_vars['tweet']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['tweet']->value['name'];?>
</a> <span><a href="<?php echo $_smarty_tpl->tpl_vars['tweet']->value['url'];?>
">@<?php echo $_smarty_tpl->tpl_vars['tweet']->value['screen_name'];?>
</a></span></h5>
					<?php echo $_smarty_tpl->tpl_vars['tweet']->value['text'];?>

				</div>
			</li>
		<?php } ?>
		</ul>
	</div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['config']->value['display_feedbacks']){?>
	<div id="box_fdb" style="display: none;">
		<div id="feedbackbox" style="margin: 0 10px 10px 10px; display: none;"></div>
		<div class="feedback" id="feedback">
			<form action="" method="post" id="feedbackform">
				<div><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['feedback_terms'];?>
</div>

				<div><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['subject'];?>
: <select name="subject" id="subject" style="width: 50%">
					<option><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['_select_'];?>
</option>
					<option><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['bug_report'];?>
</option>
					<option><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['feature_request'];?>
</option>
					<option><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['custom_quote'];?>
</option>
				</select></div>
				<div><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['message_body'];?>
:<br />
					<textarea cols="40" rows="5" name="body" id="body" class="common" style="width: 99%;"></textarea>
				</div>
				<div><input type="button" id="submitButton" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['submit'];?>
" class="common"/> <input type="button" id="resetButton" value="<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['clear'];?>
" class="common"/></div>
			</form>
		</div>
	</div>
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['changelog']->value)){?>
	<div id="box_changelog" style="display: none;">
		<select id="changelog_item" class="common">
			<?php  $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['index']->_loop = false;
 $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['changelog_titles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['index']->key => $_smarty_tpl->tpl_vars['index']->value){
$_smarty_tpl->tpl_vars['index']->_loop = true;
 $_smarty_tpl->tpl_vars['item']->value = $_smarty_tpl->tpl_vars['index']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</option>
			<?php } ?>
		</select>
		<?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_smarty_tpl->tpl_vars['index'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['changelog']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
 $_smarty_tpl->tpl_vars['index']->value = $_smarty_tpl->tpl_vars['items']->key;
?>
			<div class="changelog_item" id="changelog_<?php echo $_smarty_tpl->tpl_vars['index']->value;?>
" style="display: none;">
				<?php  $_smarty_tpl->tpl_vars['list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['list']->_loop = false;
 $_smarty_tpl->tpl_vars['class'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['items']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['list']->key => $_smarty_tpl->tpl_vars['list']->value){
$_smarty_tpl->tpl_vars['list']->_loop = true;
 $_smarty_tpl->tpl_vars['class']->value = $_smarty_tpl->tpl_vars['list']->key;
?>
					<?php if (!empty($_smarty_tpl->tpl_vars['list']->value)&&$_smarty_tpl->tpl_vars['class']->value!='title'){?>
						<div class="info change_<?php echo $_smarty_tpl->tpl_vars['class']->value;?>
"><?php echo smarty_function_lang(array('key'=>('changelog_').($_smarty_tpl->tpl_vars['class']->value)),$_smarty_tpl);?>
</div>
						<ul><?php echo $_smarty_tpl->tpl_vars['list']->value;?>
</ul>
					<?php }?>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="changelog_footer"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['changelog_roadmap'];?>
</div>
	</div>
<?php }?>

<script type="text/javascript" src="?action=get-state"></script>

<?php echo smarty_function_include_file(array('js'=>"js/ext/plugins/portal/Portal, js/ext/plugins/portal/PortalColumn, js/ext/plugins/portal/Portlet, js/ext/plugins/portal/overrides, js/ext/plugins/httpprovider/httpprovider, js/admin/index"),$_smarty_tpl);?>


<?php echo smarty_function_ia_hooker(array('name'=>"adminIndexAfterIncludeJs"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>