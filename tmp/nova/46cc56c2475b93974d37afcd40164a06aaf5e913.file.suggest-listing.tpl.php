<?php /* Smarty version Smarty-3.1.13, created on 2015-02-27 03:51:18
         compiled from "/home/wwwsyaqd/public_html/templates/common/suggest-listing.tpl" */ ?>
<?php /*%%SmartyHeaderCode:208637878654f03006ecc123-22589602%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '46cc56c2475b93974d37afcd40164a06aaf5e913' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/suggest-listing.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '208637878654f03006ecc123-22589602',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'msg' => 0,
    'clearData' => 0,
    'hide_form' => 0,
    'listing' => 0,
    'categories_exist' => 0,
    'category' => 0,
    'config' => 0,
    'crossed_categories' => 0,
    'crossed_category' => 0,
    'plans_exist' => 0,
    'deep_link' => 0,
    'key' => 0,
    'crossed' => 0,
    'parent_path' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_54f03007122474_33159254',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54f03007122474_33159254')) {function content_54f03007122474_33159254($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_hooker')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_hooker.php';
if (!is_callable('smarty_function_ia_print_js')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_print_js.php';
?><div id="msg"></div>

<?php if (empty($_smarty_tpl->tpl_vars['msg']->value)||$_smarty_tpl->tpl_vars['clearData']->value){?>
	<script type="text/javascript">
		$(function()
		{
			sessvars.$.clearMem();
		});
	</script>
<?php }?>

<?php if (!$_smarty_tpl->tpl_vars['hide_form']->value){?>
	<form action="<?php echo @constant('IA_URL');?>
suggest-listing.php<?php if ($_smarty_tpl->tpl_vars['listing']->value){?>?edit=<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
<?php }?>" method="post" id="form_listing" class="ia-form bordered" enctype="multipart/form-data">
		<?php if (!empty($_smarty_tpl->tpl_vars['categories_exist']->value)){?>
			<div class="fieldset collapsible" id="fieldCategories">
				<h4 id="categoryTitle" class="title"><?php echo smarty_function_lang(array('key'=>'category'),$_smarty_tpl);?>
: <span><?php echo $_smarty_tpl->tpl_vars['category']->value['title'];?>
<?php if ($_smarty_tpl->tpl_vars['category']->value['locked']){?> <i class="icon-lock icon-red"></i><?php }?></span></h4>
				<div id="treeContainer" class="content collapsible-content">
					<div id="tree" class="tree"></div>
				</div>
			</div>
		<?php }?>

		<?php if ($_smarty_tpl->tpl_vars['config']->value['mcross_functionality']){?>
			<div class="fieldset collapsible" id="fieldCrossed">
				<h4 class="title"><?php echo smarty_function_lang(array('key'=>'crossed_categories'),$_smarty_tpl);?>
</h4>
				<div id="mCrossContainer" class="content collapsible-content">
					<div class="alert alert-info">
						<?php echo smarty_function_lang(array('key'=>'add_as_crossed_to_other_categories'),$_smarty_tpl);?>

					</div>
					<div id="mCrossTree"></div>
					<div id="crossedCategories">
						<?php if (!empty($_smarty_tpl->tpl_vars['crossed_categories']->value)){?>
							<?php  $_smarty_tpl->tpl_vars['crossed_category'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['crossed_category']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['crossed_categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['crossed_category']->key => $_smarty_tpl->tpl_vars['crossed_category']->value){
$_smarty_tpl->tpl_vars['crossed_category']->_loop = true;
?>
								<span class="label" id="mcrossedCat_<?php echo $_smarty_tpl->tpl_vars['crossed_category']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['crossed_category']->value['title'];?>
</span>
							<?php } ?>
						<?php }?>
					</div>
				</div>
			</div>
		<?php }?>

		<div id="previeListingModal" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3><?php echo smarty_function_lang(array('key'=>'preview_listing'),$_smarty_tpl);?>
</h3>
			</div>
			<div class="modal-body">
				<script type="text/html" id="OpList">
					<?php echo $_smarty_tpl->getSubTemplate ('listing-preview.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

				</script>
				<div id="optionsPreview"></div>
			</div>
		</div>

		<script type="text/html" id="plansList">
			<<?php ?>%
				_.each(plans,function(plan)
				{
					plan.description = plan.description.replace(/\r\n/g, '<br />');
					plan.description = plan.description.replace(/\n/g, '<br />');
			%<?php ?>>
				<div class="b-plan">
					<input type="hidden" id="planCrossedMax_<<?php ?>%= plan.id %<?php ?>>" value="<<?php ?>%= plan.multicross %<?php ?>>">
					<input type="hidden" id="planDeepLinks_<<?php ?>%= plan.id %<?php ?>>" value="<<?php ?>%= plan.deep_links %<?php ?>>">
					<input type="hidden" id="planCost_<<?php ?>%= plan.id %<?php ?>>" value="<<?php ?>%= plan.cost %<?php ?>>">
					
					<label for="p<<?php ?>%= plan.id %<?php ?>>" class="radio b-plan__title">
						<input type="radio" name="plan" value="<<?php ?>%= plan.id %<?php ?>>" id="p<<?php ?>%= plan.id %<?php ?>>">
						<b><<?php ?>%= plan.title %<?php ?>></b> &mdash; <?php echo $_smarty_tpl->tpl_vars['config']->value['currency_symbol'];?>
<<?php ?>%= plan.cost %<?php ?>>
					</label>
				
					<div class="b-plan__description"><<?php ?>%= plan.description %<?php ?>></div>

					<<?php ?>%
						var checked_list = "<?php if (isset($_POST['visual_options'])){?><?php echo implode(',',$_POST['visual_options']);?>
<?php }elseif(isset($_smarty_tpl->tpl_vars['listing']->value['visual_options'])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['visual_options'];?>
<?php }?>";

						checked_list = checked_list.split(",");

						if (plan.options && plan.options.length > 0)
						{
					%<?php ?>>
						<div id="planVisualOptions_<<?php ?>%= plan.id %<?php ?>>" class="b-plan__visual-options">
							<h5><?php echo smarty_function_lang(array('key'=>'visual_options'),$_smarty_tpl);?>
</h5>
							<div class="b-plan__visual-options__radios">
								<<?php ?>%
									_.each(plan.options, function(option)
									{
								%<?php ?>>
									<label class="checkbox">
										<input type="checkbox" name="visual_options[]" data-option-price="<<?php ?>%= option.price %<?php ?>>" value="<<?php ?>%= option.name %<?php ?>>" data-option-name="<<?php ?>%= option.name %<?php ?>>"

										<<?php ?>%
											if (intelli.inArray(option.name, checked_list) && plan.id == $('#old_plan_id').val())
											{
												print('checked disabled');
											}
										%<?php ?>>
										>
										<<?php ?>%= intelli.lang['listing_option_' + option.name] %<?php ?>> - <?php echo $_smarty_tpl->tpl_vars['config']->value['currency_symbol'];?>
<<?php ?>%= option.price %<?php ?>>
									</label>
								<<?php ?>%
									});
								%<?php ?>>
							</div>
						</div>
					<<?php ?>%
						}
					%<?php ?>>
					<div class="b-plan__footer">
						<a href="#" class="b-plan__visual-options__preview js-options-preview" style="display: none;"><?php echo smarty_function_lang(array('key'=>'preview_listing'),$_smarty_tpl);?>
</a>
						<div id="planVisualOptionsTotal_<<?php ?>%= plan.id %<?php ?>>" class="b-plan__total-sum"><?php echo smarty_function_lang(array('key'=>'total'),$_smarty_tpl);?>
: <b><?php echo $_smarty_tpl->tpl_vars['config']->value['currency_symbol'];?>
<span><<?php ?>%= plan.cost %<?php ?>></span></b></div>
					</div>
				</div>
			<<?php ?>%
				});
			%<?php ?>>
		</script>

		<?php if ($_smarty_tpl->tpl_vars['config']->value['sponsored_listings']&&$_smarty_tpl->tpl_vars['plans_exist']->value){?>
			<div class="fieldset collapsible" id="fieldPlans">
				<h4 class="title"><?php echo smarty_function_lang(array('key'=>'plans'),$_smarty_tpl);?>
</h4>
				<div class="content collapsible-content" id="plans"></div>
			</div>
		<?php }?>

		<?php echo smarty_function_ia_hooker(array('name'=>'editListingForm'),$_smarty_tpl);?>


		<div id="fields" class="fields content"></div>
		<div class="fields-loader"></div>

		<div id="deepLinksDiv" style="display:none;">
			<div class="fieldset collapsible" id="fieldDeepLinks">
				<h4 class="title"><?php echo smarty_function_lang(array('key'=>'deep_links'),$_smarty_tpl);?>
</h4>
				<div id="deepLinks" class="content collapsible-content">
					<?php if ($_smarty_tpl->tpl_vars['listing']->value['deep_links']){?>
						<?php  $_smarty_tpl->tpl_vars['deep_link'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['deep_link']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['listing']->value['deep_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['deep_link']->key => $_smarty_tpl->tpl_vars['deep_link']->value){
$_smarty_tpl->tpl_vars['deep_link']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['deep_link']->key;
?>	
							<input type="hidden" name="deep_links" value="<?php echo $_smarty_tpl->tpl_vars['deep_link']->value;?>
|<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" size="15" />
						<?php } ?>
					<?php }?>
				</div>
			</div>
		</div>

		<?php echo smarty_function_ia_hooker(array('name'=>'afterSuggestListingFields'),$_smarty_tpl);?>


		<?php if ($_smarty_tpl->tpl_vars['config']->value['reciprocal_check']){?>
			<div id="reciprocal" style="display: none;">
				<div class="fieldset">
					<h4 class="title"><?php echo smarty_function_lang(array('key'=>'reciprocal'),$_smarty_tpl);?>
</h4>
					<div class="content">
						<label><?php echo $_smarty_tpl->tpl_vars['config']->value['reciprocal_label'];?>
</label>
						<textarea class="input-block-level" rows="3" readonly="readonly"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['config']->value['reciprocal_text'], ENT_QUOTES, 'UTF-8', true);?>
</textarea>
					</div>
				</div>
			</div>
		<?php }?>

		<div id="gateways" style="display: none;">
			<div class="fieldset">
				<h4 class="title"><?php echo smarty_function_lang(array('key'=>'payment_gateway'),$_smarty_tpl);?>
</h4>
				<div class="content">
					<?php echo smarty_function_ia_hooker(array('name'=>'paymentButtons'),$_smarty_tpl);?>

				</div>
			</div>
		</div>

		<?php echo $_smarty_tpl->getSubTemplate ('captcha.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


		<div class="actions">
			<input type="hidden" id="category_id" name="category_id" value="<?php echo $_smarty_tpl->tpl_vars['category']->value['id'];?>
" />
			<input type="hidden" id="listing_id" name="listing_id" value="<?php echo $_smarty_tpl->tpl_vars['listing']->value['id'];?>
" />
			<input type="hidden" id="old_plan_id" name="old_plan_id" value="<?php if (isset($_smarty_tpl->tpl_vars['listing']->value['plan_id'])&&!empty($_smarty_tpl->tpl_vars['listing']->value['plan_id'])){?><?php echo $_smarty_tpl->tpl_vars['listing']->value['plan_id'];?>
<?php }?>" />
			<input type="hidden" name="multi_crossed" id="multi_crossed" value="<?php if (isset($_smarty_tpl->tpl_vars['crossed']->value)&&!empty($_smarty_tpl->tpl_vars['crossed']->value)){?><?php echo implode(',',$_smarty_tpl->tpl_vars['crossed']->value);?>
<?php }?>" />
			<input type="hidden" id="parent_path" name="parent_path" value="<?php if (isset($_smarty_tpl->tpl_vars['parent_path']->value)){?><?php echo $_smarty_tpl->tpl_vars['parent_path']->value;?>
<?php }?>" />
			<input type="hidden" id="plan_cost" name="plan_cost" value="">

			<input type="submit" name="save_changes" value="<?php echo smarty_function_lang(array('key'=>'submit'),$_smarty_tpl);?>
" id="submit_btn" class="btn btn-primary btn-large" />
		</div>
	</form>

	<?php echo smarty_function_ia_print_js(array('files'=>'js/ckeditor/ckeditor, js/frontend/suggest-listing'),$_smarty_tpl);?>

<?php }?>

<?php echo smarty_function_ia_print_js(array('files'=>'js/intelli/intelli.tree,js/intelli/intelli.deeplinks, js/intelli/intelli.fields, js/intelli/intelli.plans, js/intelli/intelli.textcounter, js/utils/underscore-min'),$_smarty_tpl);?>


<?php echo smarty_function_ia_hooker(array('name'=>'suggestListingBeforeFooter'),$_smarty_tpl);?>
<?php }} ?>