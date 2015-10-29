<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 05:20:17
         compiled from "/home/wwwsyaqd/public_html/templates/common/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:204810669655094351b70b23-42875118%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c2567bc5e16f5a68fdab5f67836bb7a1dceefaa5' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/search.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '204810669655094351b70b23-42875118',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'showForm' => 0,
    'adv' => 0,
    'textFields' => 0,
    'f' => 0,
    'fname' => 0,
    'i' => 0,
    'name' => 0,
    'lang' => 0,
    'field_groups' => 0,
    'key' => 0,
    'group' => 0,
    'field' => 0,
    'page' => 0,
    'POST_json' => 0,
    'pages' => 0,
    'page_result' => 0,
    'categories' => 0,
    'total_categories' => 0,
    'category' => 0,
    'config' => 0,
    'filter' => 0,
    'listings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55094351d182c0_32752641',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55094351d182c0_32752641')) {function content_55094351d182c0_32752641($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
if (!is_callable('smarty_block_ia_block')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/block.ia_block.php';
if (!is_callable('smarty_modifier_replace')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/modifier.replace.php';
?><?php if ($_smarty_tpl->tpl_vars['showForm']->value){?>
	<?php if ($_smarty_tpl->tpl_vars['adv']->value){?>
		<form action="" method="post" id="advsearch" class="ia-form">
			<div class="text-center">
				<input type="text" class="input-medium" size="22" id="searchquery_domid" value="<?php if (isset($_GET['searchquery'])){?><?php echo htmlspecialchars($_GET['searchquery'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" name="searchquery" />
				<select name="match" class="span1-5">
					<option value="any"<?php if (!isset($_POST['match'])){?> selected="selected"<?php }?>><?php echo smarty_function_lang(array('key'=>'any_word'),$_smarty_tpl);?>
</option>
					<option value="exact"><?php echo smarty_function_lang(array('key'=>'exact_match'),$_smarty_tpl);?>
</option>
					<option value="all"><?php echo smarty_function_lang(array('key'=>'all_words'),$_smarty_tpl);?>
</option>
				</select>
				<select name="_settings[sort]" class="span1-5">
					<option value="relevance"<?php if (!isset($_POST['_settings'])){?> selected="selected"<?php }?>><?php echo smarty_function_lang(array('key'=>'relevance'),$_smarty_tpl);?>
</option>
					<option value="date"><?php echo smarty_function_lang(array('key'=>'date'),$_smarty_tpl);?>
</option>
				</select>
				<input type="submit" value="<?php echo smarty_function_lang(array('key'=>'start_search'),$_smarty_tpl);?>
" class="btn btn-primary" />
			</div>

			<hr>

			<div class="row-fluid">

				<?php echo smarty_function_ia_hooker(array('name'=>'tplFrontAdvSearchForm'),$_smarty_tpl);?>


				<div class="span6">
					<div class="fieldset">
						<h4 class="title"><?php echo smarty_function_lang(array('key'=>'listings'),$_smarty_tpl);?>
</h4>
						<div class="content collapsible-content">
							<?php  $_smarty_tpl->tpl_vars['f'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['f']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['textFields']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['f']->key => $_smarty_tpl->tpl_vars['f']->value){
$_smarty_tpl->tpl_vars['f']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['f']->key;
?>
								<?php if (isset($_smarty_tpl->tpl_vars["fname"])) {$_smarty_tpl->tpl_vars["fname"] = clone $_smarty_tpl->tpl_vars["fname"];
$_smarty_tpl->tpl_vars["fname"]->value = $_smarty_tpl->tpl_vars['f']->value['name']; $_smarty_tpl->tpl_vars["fname"]->nocache = null; $_smarty_tpl->tpl_vars["fname"]->scope = 0;
} else $_smarty_tpl->tpl_vars["fname"] = new Smarty_variable($_smarty_tpl->tpl_vars['f']->value['name'], null, 0);?>
								<?php if (isset($_smarty_tpl->tpl_vars["name"])) {$_smarty_tpl->tpl_vars["name"] = clone $_smarty_tpl->tpl_vars["name"];
$_smarty_tpl->tpl_vars["name"]->value = ("field_").($_smarty_tpl->tpl_vars['fname']->value); $_smarty_tpl->tpl_vars["name"]->nocache = null; $_smarty_tpl->tpl_vars["name"]->scope = 0;
} else $_smarty_tpl->tpl_vars["name"] = new Smarty_variable(("field_").($_smarty_tpl->tpl_vars['fname']->value), null, 0);?>

								<label for="queryFilter<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
_domid" class="checkbox">
									<input type="checkbox" id="queryFilter<?php echo $_smarty_tpl->tpl_vars['i']->value;?>
_domid" name="queryFilter[]" value="<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
"
									<?php if ((!isset($_POST['match'])&&($_smarty_tpl->tpl_vars['f']->value['name']=='title'||$_smarty_tpl->tpl_vars['f']->value['name']=='description'))){?>checked="checked"<?php }?>>
									<?php if (isset($_smarty_tpl->tpl_vars['lang']->value[$_smarty_tpl->tpl_vars['name']->value])){?><?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['name']->value),$_smarty_tpl);?>
<?php }else{ ?><?php echo smarty_function_lang(array('key'=>'unknown'),$_smarty_tpl);?>
<?php }?>
								</label>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="fieldset">
						<h4 class="title"><?php echo smarty_function_lang(array('key'=>'categories'),$_smarty_tpl);?>
</h4>
						<div class="content collapsible-content">
							<label for="FilterCat1" class="checkbox">
								<input type="checkbox" id="FilterCat1" name="queryFilterCat[]" value="title"
								<?php if (isset($_POST['queryFilterCat']['title'])||!isset($_POST['searchquery'])){?>checked="checked"<?php }?>>
								<?php echo smarty_function_lang(array('key'=>'title'),$_smarty_tpl);?>

							</label>
							<label for="FilterCat2" class="checkbox">
								<input type="checkbox" id="FilterCat2" name="queryFilterCat[]" value="description"
								<?php if (isset($_POST['queryFilterCat']['description'])||!isset($_POST['searchquery'])){?>checked="checked"<?php }?>>
								<?php echo smarty_function_lang(array('key'=>'description'),$_smarty_tpl);?>

							</label>
							<label for="FilterCat3" class="checkbox">
								<input type="checkbox" id="FilterCat3" name="queryFilterCat[]" value="meta_keywords"
								<?php if (isset($_POST['queryFilterCat']['meta_keywords'])){?>checked="checked"<?php }?>>
								<?php echo smarty_function_lang(array('key'=>'meta_keywords'),$_smarty_tpl);?>

							</label>
							<label for="FilterCat4" class="checkbox">
								<input type="checkbox" id="FilterCat4" name="queryFilterCat[]" value="meta_description"
								<?php if (isset($_POST['queryFilterCat']['meta_description'])){?>checked="checked"<?php }?>>
								<?php echo smarty_function_lang(array('key'=>'meta_description'),$_smarty_tpl);?>

							</label>
						</div>
					</div>
				</div>
			</div>

			<?php if (!empty($_smarty_tpl->tpl_vars['field_groups']->value)){?>
				<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['field_groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value){
$_smarty_tpl->tpl_vars['group']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['group']->key;
?>
					<div class="fieldset collapsible">
						<h4 class="title"><?php echo smarty_function_lang(array('key'=>"field_group_title_".((string)$_smarty_tpl->tpl_vars['key']->value)),$_smarty_tpl);?>
</h4>
						<div class="content collapsible-content">
							<?php  $_smarty_tpl->tpl_vars['field'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['field']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['group']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['field']->key => $_smarty_tpl->tpl_vars['field']->value){
$_smarty_tpl->tpl_vars['field']->_loop = true;
?>
								<?php echo $_smarty_tpl->getSubTemplate ('search-field-type.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('variable'=>$_smarty_tpl->tpl_vars['field']->value), 0);?>

							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<input type="hidden" name="field_groups" value="set" />
			<?php }?>

			<div class="actions">
				<input type="hidden" name="_settings[page]" id="pageNumber" value="<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
" />
				<input type="submit" value="<?php echo smarty_function_lang(array('key'=>'apply_filters'),$_smarty_tpl);?>
" class="btn btn-primary" />
			</div>
		</form>

		<script type="text/javascript">
			var POSTDATA = <?php echo $_smarty_tpl->tpl_vars['POST_json']->value;?>
;
		</script>

		<?php echo smarty_function_ia_print_js(array('files'=>'js/frontend/search_adv'),$_smarty_tpl);?>

	<?php }else{ ?>
		<form action="<?php echo @constant('IA_URL');?>
search.php" method="get" class="form-search">
			<input type="text" class="text" name="what" id="what" size="22" value="<?php if (isset($_GET['what'])){?><?php echo htmlspecialchars($_GET['what'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>" />
			<input type="submit" value="<?php echo smarty_function_lang(array('key'=>'search'),$_smarty_tpl);?>
" class="btn btn-primary" />

			<div class="search-options">
				<label for="any" class="radio"><input type="radio" name="type" value="1" id="any" <?php if (isset($_GET['type'])&&$_GET['type']=='1'){?>checked="checked"<?php }elseif(!isset($_GET['type'])){?>checked="checked"<?php }?>/> <?php echo smarty_function_lang(array('key'=>'any_word'),$_smarty_tpl);?>
</label>
				<label for="all" class="radio"><input type="radio" name="type" value="2" id="all" <?php if (isset($_GET['type'])&&$_GET['type']=='2'){?>checked="checked"<?php }?>/> <?php echo smarty_function_lang(array('key'=>'all_words'),$_smarty_tpl);?>
</label>
				<label for="exact" class="radio"><input type="radio" name="type" value="3" id="exact" <?php if (isset($_GET['type'])&&$_GET['type']=='3'){?>checked="checked"<?php }?>/> <?php echo smarty_function_lang(array('key'=>'exact_match'),$_smarty_tpl);?>
</label>
			</div>
		</form>
	<?php }?>
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['pages']->value)&&!empty($_smarty_tpl->tpl_vars['pages']->value)){?>
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_block', array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['pages'],'id'=>'pages_results','collapsible'=>true)); $_block_repeat=true; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['pages'],'id'=>'pages_results','collapsible'=>true), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

		<div class="pages-results media-body">
			<?php  $_smarty_tpl->tpl_vars['page_result'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['page_result']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['page_result']->key => $_smarty_tpl->tpl_vars['page_result']->value){
$_smarty_tpl->tpl_vars['page_result']->_loop = true;
?>
				<div class="page-wrap">
					<div class="description"><a href="<?php echo $_smarty_tpl->tpl_vars['page_result']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['page_result']->value['title'];?>
</a> - <?php echo $_smarty_tpl->tpl_vars['page_result']->value['body'];?>
</div>
				</div>
			<?php } ?>
		</div>
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_block(array('caption'=>$_smarty_tpl->tpl_vars['lang']->value['pages'],'id'=>'pages_results','collapsible'=>true), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['categories']->value)&&!empty($_smarty_tpl->tpl_vars['categories']->value)){?>
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('ia_block', array('caption'=>($_smarty_tpl->tpl_vars['lang']->value['categories_found']).($_smarty_tpl->tpl_vars['total_categories']->value))); $_block_repeat=true; echo smarty_block_ia_block(array('caption'=>($_smarty_tpl->tpl_vars['lang']->value['categories_found']).($_smarty_tpl->tpl_vars['total_categories']->value)), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


		<?php if (isset($_smarty_tpl->tpl_vars['num_columns'])) {$_smarty_tpl->tpl_vars['num_columns'] = clone $_smarty_tpl->tpl_vars['num_columns'];
$_smarty_tpl->tpl_vars['num_columns']->value = ($_smarty_tpl->tpl_vars['category']->value['num_cols']>0 ? $_smarty_tpl->tpl_vars['category']->value['num_cols'] : $_smarty_tpl->tpl_vars['config']->value['num_categories_cols']); $_smarty_tpl->tpl_vars['num_columns']->nocache = null; $_smarty_tpl->tpl_vars['num_columns']->scope = 0;
} else $_smarty_tpl->tpl_vars['num_columns'] = new Smarty_variable(($_smarty_tpl->tpl_vars['category']->value['num_cols']>0 ? $_smarty_tpl->tpl_vars['category']->value['num_cols'] : $_smarty_tpl->tpl_vars['config']->value['num_categories_cols']), null, 0);?>
		<?php echo $_smarty_tpl->getSubTemplate ('ia-categories.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('categories'=>$_smarty_tpl->tpl_vars['categories']->value), 0);?>


		<?php if ($_smarty_tpl->tpl_vars['total_categories']->value>$_smarty_tpl->tpl_vars['config']->value['num_cats_for_search']&&!isset($_GET['cats'])&&!isset($_POST['cats_only'])){?>
			<?php if (isset($_GET['adv'])){?>
				<form action="<?php echo @constant('IA_URL');?>
search.php?adv" method="post" id="adv_cat_search_form">
					<?php if (isset($_POST['queryFilterCat'])){?>
						<?php  $_smarty_tpl->tpl_vars['filter'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['filter']->_loop = false;
 $_from = $_POST['queryFilterCat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['filter']->key => $_smarty_tpl->tpl_vars['filter']->value){
$_smarty_tpl->tpl_vars['filter']->_loop = true;
?>
							<input type="hidden" name="queryFilterCat[]" value="<?php echo $_smarty_tpl->tpl_vars['filter']->value;?>
" />
						<?php } ?>
						<input type="hidden" name="cats_only" value="1" />
						<input type="hidden" name="searchquery" value="<?php echo $_POST['searchquery'];?>
"/>
						<input type="hidden" name="match" value="<?php echo $_POST['match'];?>
" />
						<input type="hidden" name="_settings[sort]" value="<?php echo $_POST['_settings']['sort'];?>
" />
					<?php }?>
				</form>

				<p class="text-right"><a href="#" id="adv_cat_search_submit" class="btn btn-mini btn-success"><?php echo smarty_function_lang(array('key'=>'more'),$_smarty_tpl);?>
</a></p>
			<?php }else{ ?>
				<p class="text-right"><a href="<?php echo @constant('IA_URL');?>
search.php?what=<?php echo htmlspecialchars($_GET['what'], ENT_QUOTES, 'UTF-8', true);?>
&cats=true" class="btn btn-mini btn-success"><?php echo smarty_function_lang(array('key'=>'more'),$_smarty_tpl);?>
</a></p>
			<?php }?>
		<?php }?>
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_ia_block(array('caption'=>($_smarty_tpl->tpl_vars['lang']->value['categories_found']).($_smarty_tpl->tpl_vars['total_categories']->value)), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }?>

<?php echo smarty_function_ia_hooker(array('name'=>'tplFrontSearchBeforeListings'),$_smarty_tpl);?>


<?php if ((isset($_smarty_tpl->tpl_vars['listings']->value)&&!empty($_smarty_tpl->tpl_vars['listings']->value)||isset($_smarty_tpl->tpl_vars['categories']->value)&&!empty($_smarty_tpl->tpl_vars['categories']->value))&&(isset($_GET['what'])||isset($_POST['searchquery']))){?>
	<script type="text/javascript">
		var pWhat = '<?php if (isset($_POST['searchquery'])){?><?php echo htmlspecialchars(smarty_modifier_replace($_POST['searchquery'],"'",''), ENT_QUOTES, 'UTF-8', true);?>
<?php }else{ ?><?php echo htmlspecialchars(smarty_modifier_replace($_GET['what'],"'",''), ENT_QUOTES, 'UTF-8', true);?>
<?php }?>';
	</script>
<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['listings']->value)&&!empty($_smarty_tpl->tpl_vars['listings']->value)){?>
	<?php echo $_smarty_tpl->getSubTemplate ('ia-listings.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('listings'=>$_smarty_tpl->tpl_vars['listings']->value), 0);?>

<?php }elseif(empty($_smarty_tpl->tpl_vars['listings']->value)&&($_smarty_tpl->tpl_vars['adv']->value&&!$_smarty_tpl->tpl_vars['showForm']->value)||isset($_GET['what'])&&!isset($_GET['cats'])){?>
	<div class="alert alert-info"><?php echo smarty_function_lang(array('key'=>'not_found_listings'),$_smarty_tpl);?>
</div>
<?php }?>

<?php echo smarty_function_ia_hooker(array('name'=>'searchBeforeFooter'),$_smarty_tpl);?>


<?php echo smarty_function_ia_print_js(array('files'=>'js/jquery/plugins/jquery.numeric, js/frontend/search_highlight'),$_smarty_tpl);?>
<?php }} ?>