$(function()
{
	var listing_input = $("#listing_id");

	$("a.js-count").each(function()
	{
		var id_link = $(this).data('id');
		var title = $(this).attr('title');

		if (title != 'View Listing')
		{
			var real_url_input = $("#real_url_" + id_link);
			var new_window = intelli.config.new_window;

			if(real_url_input.length > 0)
			{
				var real_url = real_url_input.val();

				$(this).click(function()
				{
					if(new_window == 1)
					{
						window.open(real_url, 'popup');
					}
					else
					{
						window.location = real_url;
					}

					return false;
				});
			}
		}
	});

	if(listing_input.length > 0)
	{
		var id_link = listing_input.val();
		var listing_obj = $("#l" + id_link);

		var real_url_input = $("#real_url_" + id_link);

		if(real_url_input.length > 0)
		{
			var real_url = real_url_input.val();
			var new_window = intelli.config.new_window;
			listing_obj.click(function()
			{
				if(new_window == 1)
				{
					window.open(real_url, 'popup');
				}
				else
				{
					window.location = real_url;
				}
				
				return false;
			});
		}
	}

	var action = $("a.js-visit");
	if (action.length)
	{
		var listing_id = $("#listing_id").val();
		var real_listing_url = $("#real_url_" + listing_id).val();

		action.data('url', real_listing_url);
	}
});