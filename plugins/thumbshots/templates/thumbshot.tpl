<a target="_blank" class="js-count" id="l_{$listing.id}" data-id="{$listing.id}" data-item="listings" href="{(isset($lightbox)) ? $listing.url : {print_listing_url listing=$listing}}">
	<img {if isset($tile)}style="margin: -60px 0 0 -60px;"{/if} class="media-object" src="http://images.thumbshots.com/image.aspx?cid={$config.thumbshotsorg_api_key}&url={$listing.url}" alt="">
</a>