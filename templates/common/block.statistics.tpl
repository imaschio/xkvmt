<table style="width: 100%">
	<tbody>
		{if isset($num_listings)}
			<tr>
				<td>{lang key='total_num_listings'}</td>
				<td class="text-right">{$num_listings|string_format:'%d'}</td>
			</tr>
		{/if}

		{if isset($num_categories)}
			<tr>
				<td>{lang key='total_num_categories'}</td>
				<td class="text-right">{$num_categories|string_format:'%d'}</td>
			</tr>
		{/if}

		{ia_hooker name='statisticsBlock'}
	</tbody>
</table>