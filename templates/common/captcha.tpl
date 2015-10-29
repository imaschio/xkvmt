{if $config.captcha && $config.captcha_name && empty($esynAccountInfo)}
	<div class="captcha-simple" id="captcha">
		<div class="fieldset">
			<div class="content">
				{include_captcha name=$config.captcha_name}
			</div>
		</div>
	</div>
{/if}