{if isset($box_faq) && !empty($box_faq)}
	{foreach from=$box_faq item=faq}
		<p><a href="{$smarty.const.IA_URL}mod/faq/#{$faq.id}">{$faq.question}</a></p>
	{/foreach}
{/if}