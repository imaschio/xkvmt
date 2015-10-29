{if isset($msg) && !empty($msg)}
	<div id="notification">
		<div class="alert alert-block alert-{if $error}error{elseif $alert}warning{else}success{/if}">
			<ul class="unstyled">
			{foreach $msg as $message}
				<li>{$message}</li>
			{/foreach}
			</ul>
		</div>
	</div>
{/if}