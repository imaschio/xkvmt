$(function()
{
	var field = $("input[name='youtubevideo']");
	field.attr("name", "youtubevideo[]");

	$("#fields").on('updated', function()
	{
		var field = $("input[name='youtubevideo']");
		field.attr("name", "youtubevideo[]");
		var field_parent = field.parent();
	});
	
	if (intelli.config.youtube_number_video > 1)
	{
		if ($("#fields").length > 0)
		{
			$("#fields").on('updated', function()
			{
				var field = $("input[name='youtubevideo']");
				field.attr("name", "youtubevideo[]");
				var field_parent = field.parent();
				
				var html = '&nbsp;';
		
				html += '<div class="youtubevideo_box">';
				html += '<input type="text" class="text" name="youtubevideo[]" size="35" />';
				html += '<input type="button" value="+" class="add_video" />';
				html += '<input type="button" value="-" class="remove_video" />';
				html += '</div>';
		
				field.remove();
				field_parent.append(html);

				$("input.add_video").click(function()
				{
					var count = field_parent.children("div.youtubevideo_box").length;

					if(count < intelli.config.youtube_number_video)
					{
						var clone = field_parent.children("div.youtubevideo_box:first").clone(true);
				
						clone.find("input[name='youtubevideo[]']").val('');

						field_parent.append(clone);
					}
					else
					{
						alert(intelli.lang.youtube_no_more_video);
					}
				});
		
				$("input.remove_video").click(function()
				{
					var count = field_parent.children("div.youtubevideo_box").length;
			
					if(count > 1)
					{
						$(this).parent("div.youtubevideo_box").remove();
					}
				});
			});
		}

		var field_parent = field.parent();
		
		var html = '&nbsp;';
		
		html += '<div class="youtubevideo_box">';
		html += '<input type="text" class="text" name="youtubevideo[]" size="35" />';
		html += '<input type="button" value="+" class="add_video" />';
		html += '<input type="button" value="-" class="remove_video" />';
		html += '</div>';
		
		field.remove();
		field_parent.append(html);
		
		$("input.add_video").click(function()
		{
			var count = field_parent.children("div.youtubevideo_box").length;

			if(count < intelli.config.youtube_number_video)
			{
				var clone = field_parent.children("div.youtubevideo_box:first").clone(true);
				
				clone.find("input[name='youtubevideo[]']").val('');
	
				field_parent.append(clone);
			}
			else
			{
				alert(intelli.lang.youtube_no_more_video);
			}
		});
		
		$("input.remove_video").click(function()
		{
			var count = field_parent.children("div.youtubevideo_box").length;
			
			if(count > 1)
			{
				$(this).parent("div.youtubevideo_box").remove();
			}
		});
	}
	else
	{
		// convert youtube name to array
		$("#fields").on('updated', function()
		{
			var field = $("input[name='youtubevideo']");
			field.attr("name", "youtubevideo[]");
		});
	}
});
