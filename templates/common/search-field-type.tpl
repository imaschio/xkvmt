{assign type $variable.type}

{if in_array($type, array('checkbox', 'combo', 'radio')) && 'checkbox' == $variable.show_as}
	<h5>{lang key="field_{$variable.name}"}</h5>
	<div class="field-group clearfix">
		{ia_html_checkboxes name=$variable.name options=$variable.values grouping=5}
	</div>
{elseif 'radio' == $variable.show_as}
	<h5>{lang key="field_{$variable.name}"}</h5>
	<div class="field-group clearfix">
		{ia_html_radios name=$variable.name options=$variable.values grouping=5}
	</div>
{elseif 'combo' == $variable.show_as}
	{assign name "field_{$variable.name}"}
	<h5>{lang key=$name}</h5>
	<div class="field-group clearfix">
		<select name="{$variable.name}" id="{$variable.name}_domid">
			<option value="_doesnt_selected_">{assign any_meta "{$name}_any_meta"}
				{if isset($lang.$any_meta)}
					{lang key=$any_meta}
				{else}
					{lang key='_select_'}
				{/if}
			</option>
			{html_options options=$variable.values}
		</select>
	</div>
{elseif in_array($type, array('storage', 'image'))}
	<h5>{lang key='contains'} "{lang key="field_{$variable.name}"}"</h5>
	<div class="field-group narrow clearfix">
		<label class="radio">
			<input class="storage" type="radio" id="hasFile{$variable.name}" name="{$variable.name}[has]" value="y"> {lang key='yes'}
		</label>

		<label class="radio">
			<input class="storage" type="radio" id="doesntHaveFile{$variable.name}" name="{$variable.name}[has]" value="n"> {lang key='no'}
		</label>
	</div>
{elseif 'number' == $type}
	{assign var="name" value="field_"|cat:$variable.name}
	<h5>{lang key=$name}</h5>
	<div class="field-group clearfix">
		{if $variable.ranges}
			<label for="{$variable.name}_from_domid">{lang key='from'}
				<select name="_from[{$variable.name}]" id="{$variable.name}_from_domid">
					<option value="_doesnt_selected_">{assign var="any_meta" value=$name|cat:"_any_meta"}
					{if $lang.$any_meta}
						{lang key=$any_meta}
					{else}
						{lang key='_select_'}
					{/if}
					</option>
					{html_options options=$variable.ranges}
				</select>
			</label>
			<label for="{$variable.name}_to_domid"><span style="font-size:11px;"> {lang key='to'}</span>&nbsp;</label>
			<select name="_to[{$variable.name}]" id="{$variable.name}_to_domid">
				<option value="_doesnt_selected_">
					{assign var="any_meta" value="{$name}_any_meta"}
					{if $lang.$any_meta}
						{lang key=$any_meta}
					{else}
						{lang key='_select_'}
					{/if}
				</option>

				{html_options options=$variable.ranges}
			</select>
		{else}
			<div class="input-prepend">
				<span class="add-on">{lang key='from'}</span>
				<input class="numeric span1-5" type="text" id="{$variable.name}_{$name}_from_domid" name="_from[{$variable.name}]" value="">
			</div>
			<div class="input-prepend">
				<span class="add-on">{lang key='to'}</span>
				<input class="numeric span1-5" type="text" id="{$variable.name}_{$name}_to_domid" name="_to[{$variable.name}]" value="">
			</div>
		{/if}
	</div>
{/if}