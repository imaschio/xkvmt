$(function()
{
	var map = null;
	
	var latlng = new google.maps.LatLng(intelli.config.googlemap_latitude * 1, intelli.config.googlemap_longtitude * 1);
	
	intelli.lang = ('undefined' != typeof intelli.admin) ? intelli.admin.lang : intelli.lang;
	
    var myOptions =
	{
		zoom: intelli.config.googlemap_default_zoom * 1,
		center: latlng,
		disableDoubleClickZoom: true,
		mapTypeId: google.maps.MapTypeId.ROADMAP
    };
	
	var bounds = new google.maps.LatLngBounds();
	var geocoder = new google.maps.Geocoder();
	var infowindows = new Array();
	
	var user_marker = null;

	if ($("#fields").length > 0)
    {
		$("#fields").on('updated', function()
        {
            add_field();
        });
    }

	add_field();

	function add_field()
	{
		if ('undefined' != typeof intelli.admin)
		{
			$("input[name='" + intelli.config.googlemap_append_field + "']").parent().append('&nbsp;<a href="' + intelli.config.esyn_url + 'controller.php?plugin=googlemap&file=map" id="select_coordinates">' + intelli.lang.googlemap_select_coordinates + '</a>');
		}
		else
		{
			$("#f_" + intelli.config.googlemap_append_field).parent().append('&nbsp;<a href="' + intelli.config.esyn_url + 'controller.php?plugin=googlemap&file=map" id="select_coordinates">' + intelli.lang.googlemap_select_coordinates + '</a>');
			$("#field_wrapper_longitude, #field_wrapper_latitude, #field_wrapper_zoom").hide();
		}

		$("#select_coordinates").colorbox(
		{
			onComplete: createMap
		});
	}

	function createMap()
	{
		map = new google.maps.Map(document.getElementById("google_map"), myOptions);
		
		if (null != user_marker)
		{
			user_marker.setMap(null);
		}

		var address = $("input[name='address']").val();
		var city = $("input[name='city']").val();
		var state = $("input[name='state']").val();
		var zip = $("input[name='zip']").val();
		var country = $("input[name='country']").val();

		var lat = Number($("input[name='latitude']").val());
		var lng = Number($("input[name='longitude']").val());
	
		var zoom = Number($("input[name='zoom']").val());

		var full_address = address + ' ' + city + ', ' + state + ' ' + zip + ', ' + country;

		if(!isNaN(lat) && !isNaN(lng) && lat > 0 && lng > 0)
		{
			var point =	new google.maps.LatLng(lat, lng);

			user_marker = new google.maps.Marker({
				position: point, 
				map: map,
				draggable: true
			});
			
			user_marker.setMap(map);
			
			map.panTo(point);

			if(!isNaN(zoom) && zoom > 1)
			{
				map.setZoom(zoom);
			}
			
			google.maps.event.addListener(user_marker, "dragend", dragMarker);
		}
		else if('' != full_address.split(' ').join('').replace(/\,/g, ''))
		{
			geocoder.geocode({'address': full_address}, function(results, status)
			{
				if(status == google.maps.GeocoderStatus.OK)
				{
					var point = results[0].geometry.location;
					
					user_marker = new google.maps.Marker({
						map: map, 
						position: point,
						draggable: true
					});

					user_marker.setMap(map);

					map.panTo(point);

					if(!isNaN(zoom) && zoom > 1)
					{
						map.setZoom(zoom);
					}

					google.maps.event.addListener(user_marker, "dragend", dragMarker);

					$("input[name='latitude']").val(point.lat());
					$("input[name='longitude']").val(point.lng());
					$("input[name='zoom']").val(map.getZoom());
				}
				else
				{
					alert(intelli.lang.googlemap_address_not_found.replace('{address}', full_address));
				}
			});
		}
		else
		{
			map.setCenter(latlng);

			alert(intelli.lang.googlemap_address_empty);
		}

		google.maps.event.addListener(map, 'dblclick', doubleClick);
		google.maps.event.addListener(map, 'zoom_changed', zooming);
	}
	
	function zooming()
	{
		var zoom = map.getZoom();

		$("input[name='zoom']").val(zoom);
	}

	function doubleClick(e)
	{
		var lat = e.latLng.lat();
		var lng = e.latLng.lng();
		var zoom = map.getZoom();

		$("input[name='latitude']").val(lat);
		$("input[name='longitude']").val(lng);
		$("input[name='zoom']").val(zoom);
		
		var point =	new google.maps.LatLng(lat * 1, lng * 1);

		if(null != user_marker)
		{
			user_marker.setMap(null);
		}

		user_marker = new google.maps.Marker({
			position: point,
			map: map,
			draggable: true
		});
			
		user_marker.setMap(map);

		google.maps.event.addListener(user_marker, "dragend", dragMarker);
	}
	
	function dragMarker()
	{
		var mpoint = user_marker.getPosition();

		$("input[name='latitude']").val(mpoint.lat());
		$("input[name='longitude']").val(mpoint.lng());
	}
});