<form action="{$smarty.const.IA_URL}upgrade.php?id={$smarty.get.id}&salt={$smarty.get.salt}" method="post" id="upgrade_form" enctype="multipart/form-data">
	{if isset($plans) && !empty($plans)}
		<fieldset class="collapsible">
			<legend><strong>{lang key='plans'}</strong></legend>
			{foreach $plans as $plan}
				<p class="field">
					<input type="radio" name="plan" value="{$plan.id}" id="p{$plan.id}" {if $listing.plan_id == $plan.id}checked="checked"{/if} />
					<input type="hidden" id="planCost_{$plan.id}" value="{$plan.cost}" />
					<label for="p{$plan.id}"><strong>{$plan.title} - {$config.currency_symbol} {$plan.cost}</strong></label><br />
					<span class="plan-description">{$plan.description}</span>
				</p>
			{/foreach}
		</fieldset>

		<div id="gateways" style="display: none;">
			<fieldset class="collapsible">
				<legend><strong>{lang key='payment_gateway'}</strong></legend>
				{ia_hooker name='paymentButtons'}
			</fieldset>
		</div>

		<input type="hidden" id="listing_id" value="{$listing.id}" />
	{/if}
	<input type="submit" name="upgrade" value="{lang key='upgrade_listing'}" class="btn btn-primary" />
</form>

{ia_print_js files='js/intelli/intelli.plans, js/frontend/upgrade'}