<div class="page-description">{lang key='thankyou_head'}</div>
<h2>{$email}</h2>
<div>{lang key='thankyou_tail'}</div>

{if $config.accounts_autoapproval}
	<div>
		<input type="button" value=" {lang key='next'} " onclick="document.location.href = 'login.php';" class="btn btn-primary">
	</div>
{/if}