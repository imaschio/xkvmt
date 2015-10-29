<div class="media ia-item clearfix">
	<div class="avatar pull-left">
		{if isset($account.avatar) && !empty($account.avatar)}
			{print_img ups=true fl=$account.avatar full=true title=$account.username class='avatar'}
		{else}
			{print_img fl='no-avatar.png' full=true title=$account.username class='avatar'}
		{/if}
	</div>
	<div class="media-body">
		<p>{lang key='username'}: {$account.username}</p>
		<p>{lang key='date_registration'}: {$account.date_reg|date_format:$config.date_format}</p>
	</div>
</div>
<hr>
{if isset($listings) && !empty($listings)}
	{include file='ia-listings.tpl' listings=$listings}
{else}
	<div class="alert alert-info">{lang key='no_account_listings'}</div>
{/if}