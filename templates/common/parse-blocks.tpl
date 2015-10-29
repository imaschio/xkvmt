{if isset($pos) && !empty($pos)}
	{foreach $pos as $block}

		{capture name='contents'}
			<!--__b_c_{$block.id}-->
			{ia_parse_block block=$block content=$block.contents}
			<!--__e_c_{$block.id}-->
		{/capture}

		{if $smarty.capture.contents || $manageMode}
			<!--__b_{$block.id}-->
			{if !in_array($block.position, array('inventory', 'mainmenu', 'copyright'))}
				{if $block.show_header}
					{ia_block caption=$block.title style='movable' collapsible=$block.collapsible collapsed=$block.collapsed block=$block}
						{$smarty.capture.contents}
					{/ia_block}
				{else}
					<div class="box no-header {$block.classname}" id="block_{$block.id}" >
						<div class="box-content" id="content_{$block.id}">
							{$smarty.capture.contents}
						</div>
					</div>
				{/if}
			{else}
				{$smarty.capture.contents}
			{/if}
			<!--__e_{$block.id}-->
		{/if}
	{/foreach}
{/if}