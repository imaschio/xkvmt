$(function()
{
	$.ajaxSetup({async: false});

	$("#cat_navigation").typeahead(
	{
		source: function (query, process)
		{
			categories = {};

			return $.ajax(
			{
				url: intelli.config.esyn_url + 'ajax.php',
				type: 'get',
				dataType: 'json',
				displayField: 'title',
				data:  { action: 'catsfilter', q: query },
				success: function (data)
				{
					var display = [];

					if (typeof data != 'undefined')
					{
						$.each(data, function (i, category)
						{
							categories[category.title] = category;
							display.push(category.title);
						});

						process(display);
					}

					return false;
				}
			});
		},

		updater: function (item)
		{
			if (item)
			{
				window.location.href = categories[item].path;
			}
		}
	});

	$.ajaxSetup({async: true});

});