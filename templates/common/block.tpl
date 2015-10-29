<div class="box {$block.classname}{if isset($collapsible) && $collapsible == '1'} collapsible{/if}{if isset($collapsed) && $collapsed == '1'} collapsed{/if}" {if isset($block.id)}id="block_{$block.id}" data-id="{$block.id}"{/if}>
	<h4 class="box-caption-{$style}" {if isset($id)}id="caption_{$id}"{elseif $block.id}id="caption_{$block.id}"{/if}>
		{if !empty($block.rss)}<a href="{$block.rss}" >{print_img fl='xml.gif' full=true}</a>{/if}
		{$caption} {ia_hooker name='blockHeader'}
	</h4>

	<div class="box-content box-content-{$style}{if isset($collapsible) && '1' == $collapsible} collapsible-content{/if}" {if isset($id)}id="content_{$id}"{elseif $block.id}id="content_{$block.id}"{/if}>
		{$_block_content_}
	</div>
</div>