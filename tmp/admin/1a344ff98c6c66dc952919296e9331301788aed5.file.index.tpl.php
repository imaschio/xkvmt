<?php /* Smarty version Smarty-3.1.13, created on 2015-09-27 11:26:18
         compiled from "/home/wwwsyaqd/public_html/plugins/banners/admin/templates/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:54591070556080a9ac3f1b1-63682040%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a344ff98c6c66dc952919296e9331301788aed5' => 
    array (
      0 => '/home/wwwsyaqd/public_html/plugins/banners/admin/templates/index.tpl',
      1 => 1425025914,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '54591070556080a9ac3f1b1-63682040',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'gTitle' => 0,
    'one_banner' => 0,
    'esynI18N' => 0,
    'langs' => 0,
    'code' => 0,
    'block' => 0,
    'lang' => 0,
    'positions' => 0,
    'position' => 0,
    'types' => 0,
    'type' => 0,
    'targets' => 0,
    'target' => 0,
    'statuses' => 0,
    'status' => 0,
    'selected_categories' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_56080a9ae7e259_21448075',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56080a9ae7e259_21448075')) {function content_56080a9ae7e259_21448075($_smarty_tpl) {?><?php if (!is_callable('smarty_function_print_img')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.print_img.php';
if (!is_callable('smarty_function_include_file')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.include_file.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('css'=>"js/ext/plugins/panelresizer/css/PanelResizer"), 0);?>


<?php if (isset($_GET['do'])&&($_GET['do']=='add'||$_GET['do']=='edit')){?>
	<?php echo $_smarty_tpl->getSubTemplate ("box-header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['gTitle']->value), 0);?>


		<div id="bannerThumbnail" style="display:none;">
			<?php if ($_smarty_tpl->tpl_vars['one_banner']->value['type']=='local'){?>
				<?php echo smarty_function_print_img(array('fl'=>$_smarty_tpl->tpl_vars['one_banner']->value['image'],'full'=>"true",'ups'=>"true"),$_smarty_tpl);?>

			<?php }elseif($_smarty_tpl->tpl_vars['one_banner']->value['type']=='flash'&&substr($_smarty_tpl->tpl_vars['one_banner']->value['image'],-3)=='swf'){?>
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="<?php echo $_smarty_tpl->tpl_vars['one_banner']->value['width'];?>
" height="<?php echo $_smarty_tpl->tpl_vars['one_banner']->value['height'];?>
">
					<param name="movie" value="<?php echo smarty_function_print_img(array('fl'=>$_smarty_tpl->tpl_vars['one_banner']->value['image'],'ups'=>'true'),$_smarty_tpl);?>
">
					<param name="quality" value="high">
					<embed src="<?php echo smarty_function_print_img(array('fl'=>$_smarty_tpl->tpl_vars['one_banner']->value['image'],'ups'=>'true'),$_smarty_tpl);?>
" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="<?php echo $_smarty_tpl->tpl_vars['one_banner']->value['width'];?>
" height="<?php echo $_smarty_tpl->tpl_vars['one_banner']->value['height'];?>
"></embed>
				</object>
			<?php }?>
		</div>

	<form action="controller.php?plugin=banners&amp;do=<?php echo $_GET['do'];?>
<?php if ($_GET['do']=='edit'){?>&amp;id=<?php echo $_GET['id'];?>
<?php }?>" method="post" enctype="multipart/form-data">
	<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['preventCsrf'][0][0]->preventCsrf(array(),$_smarty_tpl);?>

	<table width="100%" cellpadding="0" cellspacing="0" class="striped">
	<!--<tr>
		<td width="100"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['language'];?>
:</strong></td>
		<td>
			<select name="lang" <?php if (count($_smarty_tpl->tpl_vars['langs']->value)==1){?>disabled="disabled"<?php }?>>
				<?php  $_smarty_tpl->tpl_vars['lang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lang']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['langs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lang']->key => $_smarty_tpl->tpl_vars['lang']->value){
$_smarty_tpl->tpl_vars['lang']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['lang']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['block']->value['lang']==$_smarty_tpl->tpl_vars['code']->value||$_POST['lang']==$_smarty_tpl->tpl_vars['code']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['lang']->value;?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>-->


	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['position'];?>
: </strong></td>
		<td>
			<select name="position">
				<?php  $_smarty_tpl->tpl_vars['position'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['position']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['positions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['position']->key => $_smarty_tpl->tpl_vars['position']->value){
$_smarty_tpl->tpl_vars['position']->_loop = true;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['position']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['position']->value==$_smarty_tpl->tpl_vars['one_banner']->value['position']||($_POST&&$_POST['position']==$_smarty_tpl->tpl_vars['position']->value)){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['position']->value;?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banner_type'];?>
: </strong></td>
		<td>
			<select id="typeSelecter" name="type" onchange="changeType()">
				<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['types']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value){
$_smarty_tpl->tpl_vars['type']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['type']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['code']->value==$_smarty_tpl->tpl_vars['one_banner']->value['type']||($_POST&&$_POST['type']==$_smarty_tpl->tpl_vars['code']->value)){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['type']->value;?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>
</table>

	<div id="imageUrl" style="display:none;">
		<table class="striped" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="150">
					<label for="bannerImageUrl"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banner_img_url'];?>
: </strong></label>
				</td>
				<td>
					<input type="text" class="common" name="image" id="bannerImageUrl" size="32" value="<?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['image'])){?><?php if ($_smarty_tpl->tpl_vars['one_banner']->value['type']=='remote'){?>http://<?php }?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['image'];?>
<?php }elseif(isset($_POST['image'])){?><?php echo $_POST['image'];?>
<?php }?>" />
				</td>
			</tr>
		</table>
	</div>

	<div id="uploadcontainer" style="display:none">
		<table class="striped" width="100%" cellpadding="4" cellspacing="0">
			<tr>
				<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['choose_file_upload'];?>
: </strong></td>
				<td><input type="file" class="common" name="uploadfile" id="file"/></td>
			</tr>
		</table>
	</div>

<table class="striped" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banner_title'];?>
: </strong></td>
		<td><input type="text" class="common" name="title" size="32" value="<?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['title'])){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['title'];?>
<?php }elseif(isset($_POST['title'])){?><?php echo $_POST['title'];?>
<?php }?>" /></td>
	</tr>
</table>

<div id="imageTitle" style="display:none">
<table class="striped" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banner_alt'];?>
: </strong></td>
		<td><input type="text" class="common" name="alt" size="32" value="<?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['alt'])){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['alt'];?>
<?php }elseif(isset($_POST['alt'])){?><?php echo $_POST['alt'];?>
<?php }?>" /></td>
	</tr>
</table>
</div>

<div id="textcontainer" style="display:none">
<table class="striped" width="100%" cellpadding="4" cellspacing="0">
<tr>
	<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['content'];?>
:</strong></td>
	<td>
		<textarea id="content" name="content" class="content" style="width:50%;height:150px;" cols="1" rows="1"><?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['content'])){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['content'];?>
<?php }elseif(isset($_POST['content'])){?><?php echo $_POST['content'];?>
<?php }?></textarea>

	</td>
</tr>
</table>
</div>
<div id="planetextcontainer" style="display:none">
<table class="striped" width="100%" cellpadding="4" cellspacing="0">
<tr>
	<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['content'];?>
:</strong></td>
	<td>
		<textarea name="planetext_content" class="common" style="width:50%;height:150px;" cols="1" rows="1"><?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['planetext_content'])){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['planetext_content'];?>
<?php }elseif(isset($_POST['planetext_content'])){?><?php echo $_POST['planetext_content'];?>
<?php }?></textarea>

	</td>
</tr>
</table>
</div>

<table class="striped" width="100%" cellpadding="4" cellspacing="0">
<tr>
	<td width="150">
		<strong>No follow: </strong>
	</td>
	<td>
		<label for="nofollow1"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['yes'];?>
</label> <input type="radio" name="no_follow" id="nofollow1" value="1" <?php if ($_smarty_tpl->tpl_vars['one_banner']->value['no_follow']=='1'){?>checked="checked" <?php }?> />
		<label for="nofollow2"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['no'];?>
</label> <input type="radio" name="no_follow" id="nofollow2" value="0" <?php if ($_smarty_tpl->tpl_vars['one_banner']->value['no_follow']=='0'){?>checked="checked" <?php }?> />
	</td>
</tr>
</table>

<div id="mediasize" style="display:none">
<table class="striped" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="150">
			<strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['use_orig_params'];?>
: </strong>
		</td>
		<td>
			<label for="orig"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['yes'];?>
</label> <input type="radio" name="params" id="orig" value="1" onclick="$('#imageParams').hide()" <?php if (empty($_smarty_tpl->tpl_vars['one_banner']->value['width'])){?>checked="checked"<?php }?> />
			<label for="setown"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['no'];?>
</label> <input type="radio" name="params" id="setown" value="0" onclick="$('#imageParams').show()" <?php if (!empty($_smarty_tpl->tpl_vars['one_banner']->value['width'])){?>checked="checked"<?php }?> />
		</td>
	</tr>
</table>
</div>

<div id="imageParams" style="display:none">
	<table class="striped" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="150" style="padding-left:40px;"><label for="imageWidth"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_width'];?>
:</label></td>
			<td><input type="text" class="common" size="5" maxlength="3" name="width" id="imageWidth" value="<?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['width'])){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['width'];?>
<?php }elseif(isset($_POST['width'])){?><?php echo $_POST['width'];?>
<?php }?>" /></td>
		</tr>
		<tr>
			<td width="150" style="padding-left:40px;"><label for="imageHeight"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_height'];?>
:</label></td>
			<td><input type="text" class="common" size="5" maxlength="3" name="height" id="imageHeight" value="<?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['height'])){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['height'];?>
<?php }elseif(isset($_POST['height'])){?><?php echo $_POST['height'];?>
<?php }?>" /></td>
		</tr>
		<tr>
			<td width="150" style="padding-left:40px;"><label for="imageKeepRatio"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_keepratio'];?>
:</label></td>
			<td><input type="checkbox" name="keep_ratio" id="imageKeepRatio" onclick="imageFit()"/></td>
		</tr>
	</table>
</div>
<div id="imageFit" style="display:none">
    <table class="striped" width="100%" cellpadding="0" cellspacing="0">
   		<tr>
    		<td width="150" style="padding-left:40px;"><label for="fit"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['image_fit'];?>
:</label></td>
    		<td><input type="checkbox" name="fit" id="fit"/></td>
    	</tr>
    </table>
</div>

<div id="bannerurl" style="display:none;">
<table class="striped" width="100%" cellpadding="4" cellspacing="0">
	<tr>
	<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['banner_url'];?>
: </strong></td>

	<td><input type="text" class="common" name="url" size="32" value="<?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['url'])){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['url'];?>
<?php }elseif(isset($_POST['url'])){?><?php echo $_POST['url'];?>
<?php }?>" /></td>
	</tr>

	<tr>
	<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['target'];?>
: </strong></td>
	<td>

	<select name="target" onchange="getTarget(this)">
		<?php  $_smarty_tpl->tpl_vars['target'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['target']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['targets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['target']->key => $_smarty_tpl->tpl_vars['target']->value){
$_smarty_tpl->tpl_vars['target']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['target']->key;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['code']->value==$_smarty_tpl->tpl_vars['one_banner']->value['target']){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['target']->value;?>
</option>
		<?php } ?>
		<option value="other"><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['other'];?>
...</option>
	</select>
		<span id="settarget" style="display:none;">
			<input type="text" name="targetframe" value="<?php if ($_smarty_tpl->tpl_vars['one_banner']->value['target']){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['target'];?>
<?php }else{ ?>_blank<?php }?>" />
		</span>
	</td>
	</tr>
</table>
</div>

<table class="striped" width="100%" cellpadding="0" cellspacing="0">

	<tr>
		<td width="150"><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['category'];?>
:</strong></td>
		<td>
			<div id="tree"></div>
			<label><input type="checkbox" name="recursive" value="1" <?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['recursive'])&&$_smarty_tpl->tpl_vars['one_banner']->value['recursive']=='1'){?>checked="checked"<?php }elseif(isset($_POST['recursive'])&&$_POST['recursive']=='1'){?>checked="checked"<?php }?> />&nbsp;<?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['include_subcats'];?>
</label>
		</td>
	</tr>

	<tr>
		<td><strong><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['status'];?>
:</strong></td>
		<td>
			<select name="status">
				<?php  $_smarty_tpl->tpl_vars['status'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['status']->_loop = false;
 $_smarty_tpl->tpl_vars['code'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['statuses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['status']->key => $_smarty_tpl->tpl_vars['status']->value){
$_smarty_tpl->tpl_vars['status']->_loop = true;
 $_smarty_tpl->tpl_vars['code']->value = $_smarty_tpl->tpl_vars['status']->key;
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['code']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['code']->value==$_smarty_tpl->tpl_vars['one_banner']->value['status']||($_POST&&$_POST['status']==$_smarty_tpl->tpl_vars['code']->value)){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['status']->value;?>
</option>
				<?php } ?>
			</select>
		</td>
	</tr>

	<?php if (isset($_GET['do'])&&$_GET['do']=='add'){?>
		<tr>
			<td colspan="2">
				<span>Add banner <strong>and then</strong></span>
				<select name="goto">
					<option value="list" <?php if (isset($_POST['goto'])&&$_POST['goto']=='list'){?>selected="selected"<?php }?>>Go to list</option>
					<option value="add" <?php if (isset($_POST['goto'])&&$_POST['goto']=='add'){?>selected="selected"<?php }?>>Add another one</option>
				</select>
			</td>
		</tr>
<?php }?>



	<tr class="all">
		<td colspan="2">
			<input type="hidden" name="do" value="<?php if (isset($_GET['do'])){?><?php echo $_GET['do'];?>
<?php }?>" />
			<input type="hidden" name="id" value="<?php if (isset($_smarty_tpl->tpl_vars['one_banner']->value['id'])){?><?php echo $_smarty_tpl->tpl_vars['one_banner']->value['id'];?>
<?php }?>" />
			<input type="hidden" name="categories" id="categories" value="<?php echo $_smarty_tpl->tpl_vars['selected_categories']->value;?>
" />
			<input type="submit" name="save" value="<?php if ($_GET['do']=='edit'){?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['save_changes'];?>
<?php }else{ ?><?php echo $_smarty_tpl->tpl_vars['esynI18N']->value['add'];?>
<?php }?>" class="common" />
		</td>
	</tr>
	</table>
	</form>
	<?php echo $_smarty_tpl->getSubTemplate ("box-footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('class'=>"box"), 0);?>

<?php }else{ ?>
	<div id="box_banners" style="margin-top: 15px;"></div>
<?php }?>

<?php echo smarty_function_include_file(array('js'=>"js/ckeditor/ckeditor, js/intelli/intelli.grid,  js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/banners/js/admin/banners"),$_smarty_tpl);?>


<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>