// Create collabsible states and minmax toggler
// --------------------------------------------

function minMax()
{

	var boxesState = JSON.parse(intelli.readCookie('boxesState'));
	if (typeof boxesState == 'undefined' || boxesState == null)
	{
		boxesState = {};
	}

	
	$('div.collapsible').each(function()
	{
		if ($(this).attr("id") != "block_")
		{
			var objId = $(this).attr("id");

			if (!$('.minmax', this).length > 0) {
				$('h4', this).append('<div class="minmax"></div>');
			}

			if (boxesState[objId] == 'visible')
			{
				$(this).removeClass('collapsed');
			}
			else if (boxesState[objId] == 'hidden')
			{
				$(this).addClass('collapsed');	
			}
		}
	});

	$('div.minmax').click(function()
	{
		var obj   = $(this).closest('.collapsible');
		var objId = obj.attr('id');

		if (obj.hasClass('collapsed'))
		{
			$('.collapsible-content', obj).slideDown('fast', function()
			{
				obj.removeClass('collapsed');
			});

			boxesState[objId] = 'visible';
		}
		else
		{
			$('.collapsible-content', obj).slideUp('fast', function()
			{
				obj.addClass('collapsed');
			});

			boxesState[objId] = 'hidden';
		}

		intelli.createCookie('boxesState', JSON.stringify(boxesState));
	});
}