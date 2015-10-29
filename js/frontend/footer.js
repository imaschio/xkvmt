$(function()
{
	intelli.common.init();

	// put target _blank for the external links
	if (intelli.config.external_blank_page == 1)
	{
		$('a[href*="http://"]:not([href*="' + intelli.config.esyn_url + '"])').attr("target", "_blank");
	}

	// put nofollow for the external links
	if (intelli.config.external_no_follow == 1)
	{
		$('a[href*="http://"]:not([href*="' + intelli.config.esyn_url + '"])').attr('rel', 'nofollow');
	}

	// process bootstrap tooltips
	$('.js-tooltip').tooltip();

	// process search autocomplete
	$("#search_input").typeahead(
	{
		source: function (query, process)
		{
			listings = {};

			return $.ajax(
			{
				url: intelli.config.esyn_url + 'ajax.php',
				type: 'get',
				dataType: 'json',
				displayField: 'title',
				data:  { action: 'autocomplete', q: query },
				success: function (data)
				{
					var display = [];

					if (typeof data != 'undefined')
					{
						$.each(data, function (i, listing)
						{
							listings[listing.title] = listing;
							display.push(listing.title);
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
				window.location.href = listings[item].url;
			}
		}
	});

	// process counts for items
	$('.js-count').click(function()
	{
		var _this = $(this);
		var id = _this.data('id');
		var item = _this.data('item');

		if ('' != item && '' != id)
		{
			$.ajax(
			{
				type: 'POST',
				async: false,
				cache: false,
				url: 'ajax.php',
				data: 'action=count&item=' + item + '&id=' + id
			});
		}

		return true;
	});

	// process favorites
	$('.js-favorites').click(function()
	{
		var _this = $(this);

		return intelli.common.actionFavorites(_this.data('id'), _this.data('account'), _this.data('action'));
	});

	// process move listing
	$('.js-move').click(function()
	{
		var _this = $(this);

		return intelli.common.moveListing(_this.data('id'), _this.data('category'));
	});

	// process report broken listing
	if (intelli.config.broken_listings_report)
	{
		$('.js-report').click(function()
		{
			return intelli.common.reportBrokenListing($(this).data('id'));
		});
	}

	// redirect to listing URL
	$('.js-visit').click(function()
	{
		var _this = $(this);
		var id = _this.data('id');
		var url = _this.data('url');

		// count clicks
		if ('' != id)
		{
			$.ajax(
			{
				type: 'POST',
				async: false,
				cache: false,
				url: 'ajax.php',
				data: 'action=count&item=listings&id=' + id
			});
		}

		(1 == intelli.config.external_blank_page) ? window.open(url, '_blank') : window.location = url;

		return false;
	});

	// delete listing
	if (intelli.config.allow_listings_deletion)
	{
		$('.js-delete').click(function(e)
		{
			e.preventDefault;

			return intelli.common.deleteListing($(this).data('id'));
		});
	}

	// switch listing display type
	$('.js-switch-display-type').click(function()
	{
		var _this = $(this);
		if (!_this.hasClass('disabled'))
		{
			('tile' == $(this).data('type')) ? intelli.createCookie('listing_display_type', 'tile') : intelli.eraseCookie('listing_display_type');

			// reload the page
			window.location.replace(document.URL);
		}
	});

	// dropdown menu
	$('.dropdown').hover(function()
	{
		clearTimeout($.data(this, 'timer'));
		$(this).children('.dropdown-menu').show();
	}, function()
	{
		var el = $(this);
		$.data(this, 'timer', setTimeout(function() {
			el.children('.dropdown-menu').hide();
		}, 200));

	}).click(function(e)
	{
		e.preventDefault;

		var href = $(this).children('a').attr('href');
		if('#' != href)
		{
			window.location = href;
		}
	});

	// minmax init
	minMax();
	
	detectFilename();
});


// Get filename on change for all input[type=file]
function detectFilename() 
{
	$('.upload-wrap input[type="file"]').on('change', function() 
	{
		var filename = $(this).val();
		var lastIndex = filename.lastIndexOf("\\");
		if (lastIndex >= 0) 
		{
			filename = filename.substring(lastIndex + 1);
		}
		$(this).prev().find('.uneditable-input').text(filename);
	});
}

