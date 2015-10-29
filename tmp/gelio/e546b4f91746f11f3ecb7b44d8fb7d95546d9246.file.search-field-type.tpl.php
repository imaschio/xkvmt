<?php /* Smarty version Smarty-3.1.13, created on 2015-03-18 09:20:11
         compiled from "/home/wwwsyaqd/public_html/templates/common/search-field-type.tpl" */ ?>
<?php /*%%SmartyHeaderCode:63976670155097b8b5f2c50-09507463%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e546b4f91746f11f3ecb7b44d8fb7d95546d9246' => 
    array (
      0 => '/home/wwwsyaqd/public_html/templates/common/search-field-type.tpl',
      1 => 1425025902,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '63976670155097b8b5f2c50-09507463',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'variable' => 0,
    'type' => 0,
    'name' => 0,
    'any_meta' => 0,
    'lang' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55097b8b6da515_94382548',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55097b8b6da515_94382548')) {function content_55097b8b6da515_94382548($_smarty_tpl) {?><?php if (!is_callable('smarty_function_lang')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.lang.php';
if (!is_callable('smarty_function_ia_html_checkboxes')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_html_checkboxes.php';
if (!is_callable('smarty_function_ia_html_radios')) include '/home/wwwsyaqd/public_html/includes/smarty/custom/function.ia_html_radios.php';
if (!is_callable('smarty_function_html_options')) include '/home/wwwsyaqd/public_html/includes/smarty/plugins/function.html_options.php';
?><?php if (isset($_smarty_tpl->tpl_vars['type'])) {$_smarty_tpl->tpl_vars['type'] = clone $_smarty_tpl->tpl_vars['type'];
$_smarty_tpl->tpl_vars['type']->value = $_smarty_tpl->tpl_vars['variable']->value['type']; $_smarty_tpl->tpl_vars['type']->nocache = null; $_smarty_tpl->tpl_vars['type']->scope = 0;
} else $_smarty_tpl->tpl_vars['type'] = new Smarty_variable($_smarty_tpl->tpl_vars['variable']->value['type'], null, 0);?>

<?php if (in_array($_smarty_tpl->tpl_vars['type']->value,array('checkbox','combo','radio'))&&'checkbox'==$_smarty_tpl->tpl_vars['variable']->value['show_as']){?>
	<h5><?php echo smarty_function_lang(array('key'=>"field_".((string)$_smarty_tpl->tpl_vars['variable']->value['name'])),$_smarty_tpl);?>
</h5>
	<div class="field-group clearfix">
		<?php echo smarty_function_ia_html_checkboxes(array('name'=>$_smarty_tpl->tpl_vars['variable']->value['name'],'options'=>$_smarty_tpl->tpl_vars['variable']->value['values'],'grouping'=>5),$_smarty_tpl);?>

	</div>
<?php }elseif('radio'==$_smarty_tpl->tpl_vars['variable']->value['show_as']){?>
	<h5><?php echo smarty_function_lang(array('key'=>"field_".((string)$_smarty_tpl->tpl_vars['variable']->value['name'])),$_smarty_tpl);?>
</h5>
	<div class="field-group clearfix">
		<?php echo smarty_function_ia_html_radios(array('name'=>$_smarty_tpl->tpl_vars['variable']->value['name'],'options'=>$_smarty_tpl->tpl_vars['variable']->value['values'],'grouping'=>5),$_smarty_tpl);?>

	</div>
<?php }elseif('combo'==$_smarty_tpl->tpl_vars['variable']->value['show_as']){?>
	<?php if (isset($_smarty_tpl->tpl_vars['name'])) {$_smarty_tpl->tpl_vars['name'] = clone $_smarty_tpl->tpl_vars['name'];
$_smarty_tpl->tpl_vars['name']->value = "field_".((string)$_smarty_tpl->tpl_vars['variable']->value['name']); $_smarty_tpl->tpl_vars['name']->nocache = null; $_smarty_tpl->tpl_vars['name']->scope = 0;
} else $_smarty_tpl->tpl_vars['name'] = new Smarty_variable("field_".((string)$_smarty_tpl->tpl_vars['variable']->value['name']), null, 0);?>
	<h5><?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['name']->value),$_smarty_tpl);?>
</h5>
	<div class="field-group clearfix">
		<select name="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
_domid">
			<option value="_doesnt_selected_"><?php if (isset($_smarty_tpl->tpl_vars['any_meta'])) {$_smarty_tpl->tpl_vars['any_meta'] = clone $_smarty_tpl->tpl_vars['any_meta'];
$_smarty_tpl->tpl_vars['any_meta']->value = ((string)$_smarty_tpl->tpl_vars['name']->value)."_any_meta"; $_smarty_tpl->tpl_vars['any_meta']->nocache = null; $_smarty_tpl->tpl_vars['any_meta']->scope = 0;
} else $_smarty_tpl->tpl_vars['any_meta'] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['name']->value)."_any_meta", null, 0);?>
				<?php if (isset($_smarty_tpl->tpl_vars['lang']->value[$_smarty_tpl->tpl_vars['any_meta']->value])){?>
					<?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['any_meta']->value),$_smarty_tpl);?>

				<?php }else{ ?>
					<?php echo smarty_function_lang(array('key'=>'_select_'),$_smarty_tpl);?>

				<?php }?>
			</option>
			<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['variable']->value['values']),$_smarty_tpl);?>

		</select>
	</div>
<?php }elseif(in_array($_smarty_tpl->tpl_vars['type']->value,array('storage','image'))){?>
	<h5><?php echo smarty_function_lang(array('key'=>'contains'),$_smarty_tpl);?>
 "<?php echo smarty_function_lang(array('key'=>"field_".((string)$_smarty_tpl->tpl_vars['variable']->value['name'])),$_smarty_tpl);?>
"</h5>
	<div class="field-group narrow clearfix">
		<label class="radio">
			<input class="storage" type="radio" id="hasFile<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
[has]" value="y"> <?php echo smarty_function_lang(array('key'=>'yes'),$_smarty_tpl);?>

		</label>

		<label class="radio">
			<input class="storage" type="radio" id="doesntHaveFile<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
" name="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
[has]" value="n"> <?php echo smarty_function_lang(array('key'=>'no'),$_smarty_tpl);?>

		</label>
	</div>
<?php }elseif('number'==$_smarty_tpl->tpl_vars['type']->value){?>
	<?php if (isset($_smarty_tpl->tpl_vars["name"])) {$_smarty_tpl->tpl_vars["name"] = clone $_smarty_tpl->tpl_vars["name"];
$_smarty_tpl->tpl_vars["name"]->value = ("field_").($_smarty_tpl->tpl_vars['variable']->value['name']); $_smarty_tpl->tpl_vars["name"]->nocache = null; $_smarty_tpl->tpl_vars["name"]->scope = 0;
} else $_smarty_tpl->tpl_vars["name"] = new Smarty_variable(("field_").($_smarty_tpl->tpl_vars['variable']->value['name']), null, 0);?>
	<h5><?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['name']->value),$_smarty_tpl);?>
</h5>
	<div class="field-group clearfix">
		<?php if ($_smarty_tpl->tpl_vars['variable']->value['ranges']){?>
			<label for="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
_from_domid"><?php echo smarty_function_lang(array('key'=>'from'),$_smarty_tpl);?>

				<select name="_from[<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
]" id="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
_from_domid">
					<option value="_doesnt_selected_"><?php if (isset($_smarty_tpl->tpl_vars["any_meta"])) {$_smarty_tpl->tpl_vars["any_meta"] = clone $_smarty_tpl->tpl_vars["any_meta"];
$_smarty_tpl->tpl_vars["any_meta"]->value = ($_smarty_tpl->tpl_vars['name']->value).("_any_meta"); $_smarty_tpl->tpl_vars["any_meta"]->nocache = null; $_smarty_tpl->tpl_vars["any_meta"]->scope = 0;
} else $_smarty_tpl->tpl_vars["any_meta"] = new Smarty_variable(($_smarty_tpl->tpl_vars['name']->value).("_any_meta"), null, 0);?>
					<?php if ($_smarty_tpl->tpl_vars['lang']->value[$_smarty_tpl->tpl_vars['any_meta']->value]){?>
						<?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['any_meta']->value),$_smarty_tpl);?>

					<?php }else{ ?>
						<?php echo smarty_function_lang(array('key'=>'_select_'),$_smarty_tpl);?>

					<?php }?>
					</option>
					<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['variable']->value['ranges']),$_smarty_tpl);?>

				</select>
			</label>
			<label for="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
_to_domid"><span style="font-size:11px;"> <?php echo smarty_function_lang(array('key'=>'to'),$_smarty_tpl);?>
</span>&nbsp;</label>
			<select name="_to[<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
]" id="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
_to_domid">
				<option value="_doesnt_selected_">
					<?php if (isset($_smarty_tpl->tpl_vars["any_meta"])) {$_smarty_tpl->tpl_vars["any_meta"] = clone $_smarty_tpl->tpl_vars["any_meta"];
$_smarty_tpl->tpl_vars["any_meta"]->value = ((string)$_smarty_tpl->tpl_vars['name']->value)."_any_meta"; $_smarty_tpl->tpl_vars["any_meta"]->nocache = null; $_smarty_tpl->tpl_vars["any_meta"]->scope = 0;
} else $_smarty_tpl->tpl_vars["any_meta"] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['name']->value)."_any_meta", null, 0);?>
					<?php if ($_smarty_tpl->tpl_vars['lang']->value[$_smarty_tpl->tpl_vars['any_meta']->value]){?>
						<?php echo smarty_function_lang(array('key'=>$_smarty_tpl->tpl_vars['any_meta']->value),$_smarty_tpl);?>

					<?php }else{ ?>
						<?php echo smarty_function_lang(array('key'=>'_select_'),$_smarty_tpl);?>

					<?php }?>
				</option>

				<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['variable']->value['ranges']),$_smarty_tpl);?>

			</select>
		<?php }else{ ?>
			<div class="input-prepend">
				<span class="add-on"><?php echo smarty_function_lang(array('key'=>'from'),$_smarty_tpl);?>
</span>
				<input class="numeric span1-5" type="text" id="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
_from_domid" name="_from[<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
]" value="">
			</div>
			<div class="input-prepend">
				<span class="add-on"><?php echo smarty_function_lang(array('key'=>'to'),$_smarty_tpl);?>
</span>
				<input class="numeric span1-5" type="text" id="<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
_<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
_to_domid" name="_to[<?php echo $_smarty_tpl->tpl_vars['variable']->value['name'];?>
]" value="">
			</div>
		<?php }?>
	</div>
<?php }?><?php }} ?>