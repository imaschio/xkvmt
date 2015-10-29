$(function()
{
	var map_info = $(".js-map-info");
	var markers = [];
	var geocoder = new google.maps.Geocoder();

	if (map_info.length > 0)
	{
		map_info.each(function(i)
		{
			var _this = $(this);
			var fields = ['id', 'address', 'city', 'state', 'zip', 'country', 'title', 'description', 'url', 'lat', 'lng', 'zoom'];
			for (var k = 0; k < fields.length; k++)
			{
				window[fields[k]] = _this.data(fields[k]);
			}

			// fill map markers array
			var infobubble = {
				backgroundClassName: intelli.config.googlemap_bgclassname,
				content: generateInfoBox(),
				shadowStyle: 0,
				padding: 8,
				minWidth: intelli.config.googlemap_minwidth,
				maxWidth: intelli.config.googlemap_maxwidth,
				backgroundColor: intelli.config.googlemap_bgcolor,
				borderColor: intelli.config.googlemap_bordercolor,
				borderRadius: intelli.config.googlemap_borderradius,
				borderWidth: intelli.config.googlemap_borderwidth,
				arrowSize: 10,
				disableAutoPan: false,
				arrowPosition: '50%',
				arrowStyle: 0
			};

			var listing = {
				id: 'gmarker' + id,
				html: {
					content: generateInfoBox()
				},
				zoom: zoom,
				infobubble: infobubble
			};
			if (_this.data('lat') || _this.data('lng'))
			{
				listing['latitude'] = lat;
				listing['longitude'] = lng;
			}
			else
			{
				listing['address'] = address + ', ' + city + ', ' + state + ', ' + zip + ', ' + country;
			}
			markers.push(listing);

			// process go to map functionality
			var html = '<a title="' + intelli.lang.googlemap_find_on_map +'" class="js-tooltip js-find-map" href="' + window.location + '#google_map" data-id="' + id + '">';
			html += '<i class="icon-map-marker icon-green"></i> ' + intelli.lang.googlemap_find_on_map + '</a>';

			$('.js-map-marker-' + id).html(html);
		});

		// set default location
		if (!markers &&  '1' == intelli.config.googlemap_show_without_data && '' != intelli.config.googlemap_latitude && 'undefined' != typeof intelli.config.googlemap_latitude && '' != intelli.config.googlemap_longtitude && 'undefined' != typeof intelli.config.googlemap_longtitude)
		{
			markers.push({
				id: id,
				address: address + ', ' + city + ' ' + state + ' ' + zip + ', ' + country
			});
		}

		if (markers)
		{
			var infobubble = [];
			for (var i = 0; i < markers.length; i++) {
				infobubble[markers[i].id] = markers[i]['infobubble'];
			};

			// print map
			$('#google_map').goMap({
				markers: markers,
				infobubble: infobubble,
				hideByClick: true,
				maptype: 'ROADMAP',
				zoom: intelli.config.googlemap_default_zoom * 1,
			});

			if (markers.length > 1)
			{
				$.goMap.fitBounds('visible');
			}

			$('.js-find-map').click(function()
			{
				var marker_id = 'gmarker' + $(this).data('id');
				var active_marker = $($.goMap.mapId).data(marker_id);
				google.maps.event.trigger(active_marker, 'click');

				$.goMap.setMap({zoom: 16});
			});
		}
	}

	if (0 == markers.length)
	{
		var block_id = $("#google_map").parent("div").attr("id").replace('content_', '');
		$("#block_" + block_id).remove();
	}
});

// generates map info window content
function generateInfoBox()
{
	var out = '<a href="' + url + '" class="gmap-title">' + title + '</a><div class="gmap-description">' + description + '</div>';

	if ('undefined' != typeof address)
	{
		out += '<div class="gmap-address">' + address + ', ' + city + ', ' + state + ', ' + zip + '</div>';
	}

	return out;
}