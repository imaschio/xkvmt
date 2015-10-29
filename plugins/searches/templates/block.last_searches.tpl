{if !empty($last_search)}
<div class="search-log">
	{foreach from=$last_search item=last name=last_search}
		<div class="item">
			<p class="count">
				<a href="{$smarty.const.IA_URL}search.php?what={$last.search_word}&type=1"><b>{$last.search_word|truncate:20:'...'}</b></a>
				<span> - {$lang.searched} {if $last.search_count == '1'}{$lang.once}{else}{$last.search_count} {$lang.times}{/if}</span>
			</p>
			<div class="results">
			{foreach from=$last.search_result|unserialize item=result key=key}
				<p>{$lang.$key}: <b>{$result}</b></p>
			{/foreach}
			</div>
		</div>
	{/foreach}
</div>
{/if}