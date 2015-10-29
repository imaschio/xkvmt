{include file="header.tpl" css="js/ext/plugins/panelresizer/css/PanelResizer"}

{if isset($smarty.get.do) && ($smarty.get.do eq 'add' || $smarty.get.do eq 'edit')}
	{include file="box-header.tpl" title=$gTitle}
	<form action="controller.php?plugin=coupons&amp;do={$smarty.get.do}{if $smarty.get.do eq 'edit'}&amp;id={$smarty.get.id}{/if}" method="post">
	{preventCsrf}
		<table class="striped" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="t1"><strong>{$esynI18N.coupon_code}:</strong></td>
			<td><input type="text" name="coupon_code" class="common" value="{$coupon.coupon_code}" /></td>
		</tr>
		<tr>
			<td><strong>{$esynI18N.description}:</strong></td>
			<td>
				<textarea name="description" class="common" cols="67" rows="8">{if $smarty.get.do eq 'edit'}{$coupon.description}{elseif isset($smarty.post.description)}{$smarty.post.description}{/if}</textarea>
			</td>
		</tr>
		<tr>
			<td><strong>{$esynI18N.discount_percent}:</strong></td>
			<td>
				<input type="text" name="discount" class="common" value="{if $smarty.get.do eq 'edit'}{$coupon.discount}{elseif isset($smarty.post.discount)}{$smarty.post.discount}{/if}" />
			</td>
		</tr>
		<tr>
			<td><strong>{$esynI18N.time_used}:</strong></td>
			<td>
				<input type="text" name="time_used" class="common" value="{if $smarty.get.do eq 'edit'}{$coupon.time_used}{elseif isset($smarty.post.time_used)}{$smarty.post.time_used}{/if}" />
			</td>
		</tr>
		<tr>
			<td><strong>{$esynI18N.coupon}&nbsp;{$esynI18N.status}:</strong></td>
			<td>
				<select name="status" class="common">
					<option value="approval" {if $smarty.get.do eq 'edit' && $coupon.status eq 'approval'}selected="selected"{elseif isset($smarty.post.status) && $smarty.post.status eq 'approval' || !isset($smarty.post.status) && $smarty.get.do eq 'add'}selected="selected"{/if}>{$esynI18N.approval}</option>
					<option value="active" {if $smarty.get.do eq 'edit' && $coupon.status eq 'active' || isset($smarty.post.status) && $smarty.post.status eq 'approval'}selected="selected"{/if}>{$esynI18N.active}</option>
				</select>
			</td>
		</tr>
		<tr class="all">
			<input type="hidden" name="do" value="{if isset($smarty.get.do)}{$smarty.get.do}{/if}" />
			<input type="hidden" name="id" value="{if isset($coupon.id)}{$coupon.id}{/if}" />
			<td><input type="submit" name="save" value="{if $smarty.get.do eq 'edit'}{$esynI18N.save_changes}{else}{$esynI18N.add}{/if}" class="common" /></td>
		</tr>
		</table>
	</form>
	{include file="box-footer.tpl"}
{else}
	<div id="box_coupons" style="margin-top: 15px;"></div>
{/if}

{include_file js="js/ckeditor/ckeditor, js/intelli/intelli.grid,  js/ext/plugins/bettercombobox/betterComboBox, js/ext/plugins/panelresizer/PanelResizer, js/ext/plugins/progressbarpager/ProgressBarPager, plugins/coupons/js/admin/coupons"}

{include file='footer.tpl'}