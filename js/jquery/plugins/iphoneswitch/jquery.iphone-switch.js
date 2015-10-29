/************************************************ 
*  jQuery iphoneSwitch plugin                   *
*                                               *
*  Author: Daniel LaBare                        *
*  Date:   2/4/2008                             *
************************************************/

jQuery.fn.iphoneSwitch = function(start_state, switched_on_callback, switched_off_callback, options)
{
	var state = start_state == 'on' ? start_state : 'off';
	
	// define default settings
	var settings =
	{
		mouse_over: 'pointer',
		mouse_out:  'default',
		switch_on_container_path: '../js/jquery/plugins/iphoneswitch/images/iphone_switch_container_on.png',
		switch_off_container_path: '../js/jquery/plugins/iphoneswitch/images/iphone_switch_container_off.png',
		switch_path: '../js/jquery/plugins/iphoneswitch/images/iphone_switch.png',
		switch_height: 25,
		switch_width: 94
	};

	if (options)
	{
		jQuery.extend(settings, options);
	}

	// create the switch
	return this.each(function()
	{
		var container;
		var image;
		var html;
		
		html = '<div class="iphone_switch_container" style="height:'+settings.switch_height+'px; width:'+settings.switch_width+'px; overflow: hidden">';
		html += '<img class="iphone_switch" style="height:'+settings.switch_height+'px; width:'+settings.switch_width+'px; background-image:url('+settings.switch_path+'); background-repeat:none; background-position:'+(state == 'on' ? 0 : -53)+'px" src="'+(state == 'on' ? settings.switch_on_container_path : settings.switch_off_container_path)+'" /></div>';

		jQuery(this).html(html);
		
		jQuery(this).mouseover(function()
		{
			jQuery(this).css("cursor", settings.mouse_over);
		});

		// click handling
		jQuery(this).click(function()
		{
			if (state == 'on')
			{
				jQuery(this).find('.iphone_switch').animate({backgroundPosition: "-53px"}, "slow", function()
				{
					jQuery(this).attr('src', settings.switch_off_container_path);
					switched_off_callback();
				});
				state = 'off';
			}
			else
			{
				jQuery(this).find('.iphone_switch').animate({backgroundPosition: 0}, "slow", function()
				{
					switched_on_callback();
				});
				jQuery(this).find('.iphone_switch').attr('src', settings.switch_on_container_path);
				state = 'on';
			}
		});

		// custom events
		jQuery(this).bind('setOff', function()
		{
			jQuery(this).find('.iphone_switch').animate({backgroundPosition: "-53px"}, "slow", function()
			{
				jQuery(this).attr('src', settings.switch_off_container_path);
			});

			state = 'off';
		});

		jQuery(this).bind('setOn', function()
		{
			jQuery(this).find('.iphone_switch').animate({backgroundPosition: 0}, "slow");
			jQuery(this).find('.iphone_switch').attr('src', settings.switch_on_container_path);

			state = 'on';
		});
	});
};
