{if isset($field_groups) && $field_groups && isset($showFilters)}
	{assign maxFilterNum 5}

	<form action="" method="post" id="advsearch" class="ia-form filter">
		{foreach $field_groups as $key => $group}
			{foreach $group as $field}
				<div class="filter__group">
					{if in_array($field.type, array('checkbox', 'combo', 'radio')) && 'checkbox' == $field.show_as}
						<h5 class="filter__group__title">{lang key="field_{$field.name}"} <span class="text-right"><a class="filter__group__reset" href="#" data-field="{$field.name}">x</a></span></h5>
						<div class="filter__group__content">
							{ia_html_checkboxes name=$field.name options=$field.values checked=$field.checked grouping=5}
							<div class="filter__group__actions text-right">
								{if count($field.values) > $maxFilterNum}
									<a class="filter__group__all" href="#" style="float: none;">{lang key='show_more'}</a>
								{/if}
							</div>
						</div>
					{elseif in_array($field.type, array('storage', 'image'))}
						<h5 class="filter__group__title">{lang key='contains'} "{lang key="field_{$field.name}"}" <span class="text-right"><a class="filter__group__reset" href="#" data-field="{$field.name}">x</a></span></h5>
						<div class="filter__group__content">
							<label class="radio inline">
								<input class="storage" type="radio" id="hasFile{$field.name}" name="{$field.name}[has]" value="y" {if $fileFields[$field.name] == 'y'}checked{/if}> {lang key='yes'}
							</label>
							<label class="radio inline">
								<input class="storage" type="radio" id="doesntHaveFile{$field.name}" name="{$field.name}[has]" value="n" {if $fileFields[$field.name] == 'n'}checked{/if}> {lang key='no'}
							</label>
						</div>
					{/if}
				</div>
			{/foreach}
		{/foreach}

		<div class="filter__actions">
			<button type="reset" class="btn btn-small pull-right">{lang key='reset_filters'}</button>
			<button type="submit" class="btn btn-primary btn-small pull-left">{lang key='apply_filters'}</button>
		</div>
	</form>

	{ia_add_js}
var maxFilterNum = {$maxFilterNum};
	{/ia_add_js}

	{ia_print_js files='js/frontend/search_filters'}
{/if}