{if isset($listing.youtubevideo) && !empty($listing.youtubevideo)}
	{ia_block caption=$lang.field_youtubevideo collapsible='1' id='youtube'}

	<div class="text-center">
		{foreach from=$listing.youtubevideo item='video'}
			<object width="425" height="355">
				<param name="movie" value="http://www.youtube.com/v/{$video}&rel=1"></param>
				<param name="wmode" value="transparent"></param>
				<embed src="http://www.youtube.com/v/{$video}&rel=1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="355"></embed>
			</object>
		{/foreach}
	</div>

	{/ia_block}
{/if}