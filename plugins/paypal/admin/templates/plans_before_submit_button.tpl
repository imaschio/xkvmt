<tr>
	<td class="caption" colspan="2"><strong>{$esynI18N.paypal_options}</strong></td>
</tr>

<tr>
	<td width="200"><strong>{$esynI18N.recurring}:</strong></td>
	<td>
		<input type="checkbox" name="recurring" value="1" id="recurring" {if isset($plan.recurring) && $plan.recurring eq '1'}checked="checked"{elseif isset($smarty.post.recurring) && $smarty.post.recurring eq '1'}checked="checked"{/if} />
	</td>
</tr>

<tr>
	<td><strong>{$esynI18N.units_duration}:</strong></td>
	<td>
		<select name="units_duration" id="units_duration" disabled="disabled">
			{if isset($units_duration) && !empty($units_duration)}
				{foreach from=$units_duration key=key item=option}
					{if isset($plan.units_duration) && $plan.units_duration eq $key|upper}
						<option value="{$key|upper}" selected="selected">{$option}</option>
					{else}
						<option value="{$key|upper}">{$option}</option>
					{/if}
				{/foreach}
			{/if}
		</select>
	</td>
</tr>

<tr>
	<td><strong>{$esynI18N.duration}:</strong></td>
	<td>
		<select name="duration" id="duration" disabled="disabled"></select>
		<input type="hidden" name="default_duration" id="def_duration" value="{if isset($plan.duration)}{$plan.duration}{elseif isset($smarty.post.duration)}{$smarty.post.duration}{else}0{/if}" />
	</td>
</tr>