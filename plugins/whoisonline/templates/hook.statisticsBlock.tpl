<table cellpadding="2" cellspacing="2" width="100%">
<thead>
	<tr>
		<th colspan="2">{lang key='whoisonline'}</th>
	</tr>
</thead>
<tbody>
	<tr>
		<td>{lang key='active_visitors'}:</td>
		<td class="text-right">{$whois_statistics.total_users|default:0}</td>
	</tr>
	<tr>
		<td>{lang key='accounts'}:</td>
		<td class="text-right">{$whois_statistics.accounts|default:0}</td>
	</tr>
	<tr>
		<td>{lang key='visits'}:</td>
		<td class="text-right">{$whois_statistics.num_visits_today|default:0}</td>
	</tr>
	<tr>
		<td>{lang key='total_visits'}:</td>
		<td class="text-right">{$whois_statistics.num_total_visits|default:0}</td>
	</tr>
	{if $whois_statistics.usernames}
		<tr>
			<td rowspan="2">
				{lang key='users'}:
				{foreach from=$whois_statistics.usernames item=user}
					<a href="{print_account_url account=$user}">{$user.username}</a>{if !$user@last},{/if}
				{/foreach}
			</td>
		</tr>
	{/if}
	{if $whois_statistics.botnames}
		<tr>
			<td rowspan="2">
				{lang key='active_bots'}: {$whois_statistics.botnames}
			</td>
		</tr>
	{/if}
</tbody>
</table>