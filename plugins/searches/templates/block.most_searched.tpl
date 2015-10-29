{if !empty($most_search)}
<div class="search-log">
	{foreach from=$most_search item=most name=most_search}
		<div class="item">
			<p class="count">
				<a href="{$smarty.const.IA_URL}search.php?what={$most.search_word}&type=1"><b>{$most.search_word|truncate:20:'...'}</b></a>
				<span> - {$lang.searched} {if $most.search_count == '1'}{$lang.once}{else}{$most.search_count} {$lang.times}{/if}</span>
			</p>
			<div class="results">
			{foreach from=$most.search_result|unserialize item=result key=key}
				<p>{$lang.$key}: <b>{$result}</b></p>
			{/foreach}
			</div>
		</div>
	{/foreach}
</div>
{/if}