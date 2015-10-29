<div id="google_map" style="width: {$config.googlemap_map_width}; height: {$config.googlemap_map_height}; margin: 0 auto; max-width: none;"></div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
{include_file js='plugins/googlemap/js/jquery/plugins/jquery.gomap.min, plugins/googlemap/js/frontend/infobubble.min, plugins/googlemap/js/frontend/google_map'}

<style type="text/css" media="screen">
.gmap-marker {
	color: #333; font-size: 12px; line-height: 15px; padding: 5px; height:auto!important; width:auto!important;
}
a.gmap-title {
	font-size: 13px; font-weight: bold; text-decoration:underline;
}
div.gmap-description {
	margin: 5px 0;
}
div.gmap-address {
	font-style: italic;
}
</style>