{ia_hooker name='tplFrontRegisterBeforeRegister'}

<form method="post" action="{$smarty.const.IA_URL}register.php" class="ia-form">
	<label for="username">{lang key='your_username'}</label>
	<input type="text" class="span3" name="username" id="username" value="{if isset($account.username)}{$account.username|escape:'html'}{elseif isset($smarty.post.username)}{$smarty.post.username|escape:'html'}{/if}">

	<label for="email">{lang key='your_email'}</label>
	<input type="email" class="span3" name="email" id="email" value="{if isset($account.email)}{$account.email|escape:'html'}{elseif isset($smarty.post.email)}{$smarty.post.email|escape:'html'}{/if}">

	<label for="auto_generate" class="checkbox">
		<input type="checkbox" id="auto_generate" name="auto_generate" value="1" {if isset($smarty.post.auto_generate) && $smarty.post.auto_generate == '1'}checked="checked"{elseif !isset($account) && !$smarty.post}checked="checked"{/if}> {lang key='auto_generate_password'}
	</label>

	<div id="passwords" style="display: none;">
		<label for="password">{lang key='your_password'}</label>
		<input type="password" name="password" class="span2" id="pass1" value="{if isset($account.password)}{$account.password|escape:'html'}{elseif isset($smarty.post.password)}{$smarty.post.password|escape:'html'}{/if}">
		<label for="password2">{lang key='your_password_confirm'}</label>
		<input type="password" name="password2" class="span2" id="pass2" value="{if isset($account.password2)}{$account.password2|escape:'html'}{elseif isset($smarty.post.password2)}{$smarty.post.password2|escape:'html'}{/if}">
	</div>

	{if $config.sponsored_accounts && $plans}
		<div style="margin-top: 20px;">
			<div class="fieldset">
				<h4 class="title">{lang key='plans'}</h4>
				<div class="content">
					{foreach $plans as $plan}
						<div class="b-plan">
							<label for="p{$plan.id}" class="radio b-plan__title">
								<input type="radio" name="plan" value="{$plan.id}" id="p{$plan.id}" {if isset($smarty.post.plan) && $smarty.post.plan == $plan.id} checked="checked"{/if}>
								<b>{$plan.title} &mdash; {$config.currency_symbol}{$plan.cost}</b>
							</label>

							<div class="b-plan__description">{$plan.description}</div>
						</div>
					{/foreach}
				</div>
			</div>

			<div id="gateways" class="fieldset" style="display: none;">
				<h4 class="title">{lang key='payment_gateway'}</h4>
				<div class="content collapsible-content">
					{ia_hooker name='paymentButtons'}
				</div>
			</div>
		</div>
	{/if}

	{include file='captcha.tpl'}

	<div class="actions">
		<input type="submit" name="register" value="{lang key='register'}" class="btn btn-primary" />
	</div>
</form>

{ia_print_js files='js/frontend/register'}

{ia_hooker name='registerBeforeFooter'}