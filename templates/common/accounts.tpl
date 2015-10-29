{if $search_alphas}
	<div class="text-center">
		<div class="alphabetic-search btn-group">
			{foreach from=$search_alphas item=onealpha}
				{if $onealpha == $alpha}
					<a class="btn btn-mini disabled">{$onealpha}</a>
				{else}
					<a href="{$smarty.const.IA_URL}accounts/{$onealpha}/" class="btn btn-mini">{$onealpha}</a>
				{/if}
			{/foreach}
		</div>
	</div>
{/if}

{if $accounts}
	{navigation aTotal=$total_accounts aTemplate=$url aItemsPerPage=$config.num_get_accounts aNumPageItems=5 aTruncateParam=0 notiles=true}

	<ul class="unstyled">
		{foreach $accounts as $account}
			<li>
				<div class="media ia-item bordered">
					<a href="{print_account_url account=$account}" class="media-object pull-left">
						{if isset($account.avatar) && !empty($account.avatar)}
							{print_img ups=true fl=$account.avatar full=true title=$account.username class='avatar'}
						{else}
							{print_img fl='no-avatar.png' full=true class='avatar'}
						{/if}
					</a>
					<div class="media-body">
						<p>{lang key='username'}: <a href="{print_account_url account=$account}">{$account.username}</a></p>
						<p>{lang key='date_registration'}: {$account.date_reg|date_format:$config.date_format}</p>
					</div>
				</div>
			</li>
		{/foreach}
	</ul>

	{navigation aTotal=$total_accounts aTemplate=$url aItemsPerPage=$config.num_get_accounts aNumPageItems=5 aTruncateParam=0 notiles=true}
{else}
	<div class="alert alert-info">{lang key='no_accounts'}</div>
{/if}