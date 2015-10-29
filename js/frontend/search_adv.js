$(function()
{
	$("div.checkboxGroup").each(function ()
	{
		if($("div input", this).size() > 5)
		{
			// show buttons only if there are many checkboxes (more than 5)
			var html = '<div style="clear:both; margin-top:5px;"><br />';
			html += '<input type="button" class="button SelectAll" value="all" />';
			html += '<input type="button" class="button SelectNone" value="none" />';
			html += '<input type="button" class="button SelectInvert" value="invert" /></div>';

			$(this).append(html);
		}
	});

	// selecters
	$("div.checkboxGroup div input.SelectAll").click(function()
	{
		$(this).parent().parent().find("input").each(function()
		{
			$(this).attr('checked', 'checked');
		});
	});

	$("div.checkboxGroup div input.SelectNone").click(function()
	{
		$(this).parent().parent().find("input").each(function()
		{
			$(this).removeAttr('checked');
		});
	});

	$("div.checkboxGroup div input.SelectInvert").click(function()
	{
		$(this).parent().parent().find("input").each(function()
		{
			if($(this).attr("checked") == window.undefined)
			{
				$(this).attr('checked', 'checked');
			}
			else
			{
				$(this).removeAttr('checked');
			}
		});
	});

	$('fieldset.collapsible > legend').each(function()
	{
		var fieldset = $(this.parentNode);
		var text = this.innerHTML;

		x = $('<div class="fieldset-wrapper"></div>').append(fieldset.children(':not(legend)'));

		$(this).empty().append($('<a href="#">'+ text +'</a>').click(function()
		{
			var fieldset = $(this).parents('fieldset:first')[0];
			intelli.common.toggleFieldset(fieldset);

			return false;

		})).after(x);
	});

	$('#advsearch :input').each(function()
	{
		var name = $(this).attr("name");

		if(name)
		{
			if(!$(this).is(".storage"))
			{
				if(-1 < name.indexOf("["))
				{
					name = name.substr(0, name.indexOf("["));
				}

				data = POSTDATA[name];

				if(name != '_settings')
				{
					var type = $(this).attr("type");
					if(type != "checkbox" && type != "radio")
					{
						if(typeof data == 'object')
						{
							for(var i in data)
							{
								data = data[i];
								delete POSTDATA[name][i];
								break;
							}
						}
						$(this).val(data);
					}
					else if(data != null && data.length)
					{
						v = $(this).val();

						// if checkboxes
						if(typeof(data) == 'object' && type == 'checkbox')
						{
							for(var i in data)
							{
								if(v == data[i])
								{
									$(this).attr("checked", "checked");
									break;
								}
							}
						}
						else
						{
							if(v == data)
							{
								$(this).attr("checked", "checked");
							}
						}
					}
				}
				else
				{
					if(data)
					{
						data = data.sort;

						if($(this).val() == data)
						{
							$(this).attr("checked", "checked");
						}
					}
				}
			}
			else
			{
				if(-1 < name.indexOf("["))
				{
					name = name.substr(0, name.indexOf("["));
				}

				data = POSTDATA[name].has;

				if(data == 'y' && $(this).val() == 'y')
				{
					$(this).attr("checked", "checked");
				}
				else if(data == 'n' && $(this).val() == 'n')
				{
					$(this).attr("checked", "checked");
				}
			}
		}
	});

	$("input.numeric").numeric();
});