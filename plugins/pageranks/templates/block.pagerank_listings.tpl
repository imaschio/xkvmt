{if isset($pageranks)}
	<table class="table table-striped table-condensed table-hover">
		{foreach from=$pageranks item=pagerank}
			<tr>
				<td width="80%">
					<a href="{$smarty.const.IA_URL}pagerank{$pagerank.pagerank}-listings.html"><i class="icon-signal icon-gray"></i> {lang key='pagerank'} {$pagerank.pagerank}</a>
				</td>
				<td style="text-align: right;">{$pagerank.val}</td>
			</tr>
		{/foreach}
	</table>
{/if}