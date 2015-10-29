<a target="_blank" class="js-count" id="l_{$listing.id}" data-id="{$listing.id}" data-item="listings" href="{(isset($lightbox)) ? $listing.url : {print_listing_url listing=$listing}}">
	<img {if isset($tile)}style="margin: -90px 0 0 -90px;"{else}class="media-object"{/if} src="{$smarty.const.IA_URL}tmp/webthumbnails/{$listing.domain}.{$config.webthumbnail_format}" alt="">
</a>