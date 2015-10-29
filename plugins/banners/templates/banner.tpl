<td style="text-align: center;">
	{if $banner}
		<div style="text-align: center;padding: 0 0 3px 0;">
			{if $banner.type == "local"}
				<a id="bnr{$banner.id}" href="{$banner.url}" target="{$banner.target}" {if $banner.no_follow}rel="nofollow"{/if}>
					{print_img fl=$banner.image full="true" alt=$banner.alt ups="true" title=$banner.title style="width:"|cat:$banner.width|cat:"px;height:"|cat:$banner.height|cat:"px"}
				</a>
    		{elseif $banner.type == "flash"}
			<div id="bnr{$banner.id}">
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" {if $banner.width != 0 and $banner.height != 0}width="{$banner.width}" height="{$banner.height}{/if}">
    				<param name="movie" value="{print_img fl=$banner.image ups='true'}">
    				<param name="quality" value="high">
    				<embed src="{print_img fl=$banner.image ups='true'}" quality="high" pluginspage=" http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" {if $banner.width != 0 && $banner.height != 0}width="{$banner.width}" height="{$banner.height}"{/if}></embed>
				</object>
			</div>
			{elseif $banner.type == "remote"}
				<a id="bnr{$banner.id}" href="{$banner.url}" target="{$banner.target}" {if $banner.no_follow}rel="nofollow"{/if}><img src="{$banner.image}" alt="{$banner.alt}" title="{$banner.title}" {if $banner.width != 0 && $banner.height != 0}style="width:{$banner.width};height:{$banner.height}px"{/if} /></a>
			{elseif $banner.type == "html"}
				<div id="bnr{$banner.id}">{$banner.content}</div>
			{elseif $banner.type == "text"}
				<a id="bnr{$banner.id}" href="{$banner.url}" target="{$banner.target}" {if $banner.no_follow}rel="nofollow"{/if}>{$banner.planetext_content|escape:"html"}</a>
			{/if}
		</div>
	{/if}
	{if $banner_pos}
    	<div style="text-align: center;">
			{if ($banner_pos == "top") || ($banner_pos == "center") || ($banner_pos == "verybottom")}
				{if ($banner)}
					<div style="text-align: center;">
						<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">{lang key='click_to_add_banner'}</a>
					</div>
				{else}
					<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">
						<img src="{$smarty.const.IA_URL}plugins/banners/templates/img/468x60.gif" alt="{$lang.$banner_pos} banner" border="0" />
					</a>
				{/if}
			{elseif ($banner_pos == "left") || ($banner_pos == "right")}
				{if ($banner)}
					<div style="text-align: center;">
						<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">
						{lang key='click_to_add_banner'}</a>
					</div>
				{else}
					<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">
						<img src="{$smarty.const.IA_URL}plugins/banners/templates/img/468x60_vertical.gif" alt="{$lang.$banner_pos} banner" border="0" />
					</a>
				{/if}
			{elseif ($banner_pos == "user1") || ($banner_pos == "user2")}
				{if ($banner)}
					<div style="text-align: center;">
						<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">
						{lang key='click_to_add_banner'}</a>
					</div>
				{else}
					<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">
						<img style="font-size: 11px;" src="{$smarty.const.IA_URL}plugins/banners/templates/img/120x60.gif" alt="{$lang.$banner_pos} banner" border="0" />
					</a>
				{/if}
			{elseif ($banner_pos == "footer1") || ($banner_pos == "footer2") || ($banner_pos == "footer3") || ($banner_pos == "footer4")}
				{if ($banner)}
					<div style="text-align: center;">
						<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">
						{lang key='click_to_add_banner'}</a>
					</div>
				{else}
					<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">
						<img style="font-size: 11px;" src="{$smarty.const.IA_URL}plugins/banners/templates/img/120x60.gif" alt="{$lang.$banner_pos} banner" border="0" />
					</a>
				{/if}
			{elseif ($banner_pos == "bottom")}
				{if ($banner)}
					<div style="text-align: center;">
						<a style="font-size: 11px;" href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">{lang key='click_to_add_banner'}</a>
					</div>
				{else}
					<a href="{$smarty.const.IA_URL}mod/banners/index.html?banner_pos={$banner_pos}">
						<img src="{$smarty.const.IA_URL}plugins/banners/templates/img/234x60.gif" alt="{$lang.$banner_pos} banner" border="0" />
					</a>
				{/if}
			{/if}
    	</div>
    {/if}
</td>