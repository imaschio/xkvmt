{if isset($listing.$instead_thumbnail) && $listing.$instead_thumbnail}
	{if isset($tile)}
		<a class="pull-left image" href="{print_listing_url listing=$listing}">
			{print_img ups=true fl="small_{$listing.$instead_thumbnail}" full=true style="margin: -{$instead_thumbnail_info.thumb_height/2}px 0 0 -{$instead_thumbnail_info.thumb_width/2}px" title=$listing.title}
		</a>
	{else}
		<a class="pull-left" href="{(isset($lightbox)) ? {print_img ups=true fl=$listing.$instead_thumbnail} : {print_listing_url listing=$listing}}" {if isset($lightbox)}rel="ia_lightbox-{$instead_thumbnail}"{/if}>
			{print_img ups=true fl="small_{$listing.$instead_thumbnail}" full=true title=$listing.title class='media-object'}
		</a>
	{/if}
{else}
	<div class="pull-left image">
		{if $config.thumbshot && $config.thumbshots_name}
			{include file="plugins/{$config.thumbshots_name}/templates/thumbshot.tpl"}
		{/if}
	</div>
{/if}