$(function()
{
	var filter_cat = $('#filter-cat').val();

	var filterState = JSON.parse(intelli.readCookie('filterState' + filter_cat));
	if (typeof filterState == 'undefined' || filterState == null)
	{
		filterState = {};
	}

	$('.js-search-filters input').each(function()
	{
		var f_name = $(this).attr('name');
		if ($(this).is(':checked'))
		{
			filterState[f_name] = 'checked';
		}
		else
		{
			filterState[f_name] = 'unchecked';
		}
	});

	$('.js-search-filters input').click(function()
	{
		var f_name = $(this).attr('name');
		if ($(this).is(':checked'))
		{
			filterState[f_name] = 'checked';
		}
		else
		{
			filterState[f_name] = 'unchecked';
		}

		intelli.createCookie('filterState_' + filter_cat, JSON.stringify(filterState));

		window.location.reload();
	});

	$('.filter__group__content').each(function()
	{
		$('> label', this).slice(maxFilterNum).wrapAll('<div class="filter__group__content__more"></div>');
	});

	$('.filter__group__all').on('click', function(e)
	{
		e.preventDefault();

		var $this = $(this),
			$hiddenGroup = $this.closest('.filter__group__content').find('.filter__group__content__more');

		if (!$this.hasClass('opened'))
		{
			$hiddenGroup.slideDown('fast');
			$this.addClass('opened').text(intelli.lang.show_less);
		}
		else
		{
			$hiddenGroup.slideUp('fast');
			$this.removeClass('opened').text(intelli.lang.show_more);
		}
	});

	$('.filter__group__reset').on('click', function(e)
	{
		e.preventDefault();

		var $this = $(this),
			$groupItems = $this.closest('.filter__group__title').next().find('input'),
			field = $this.data('field');

		$groupItems.prop('checked', false);

		$.ajax(
		{
			type: 'POST',
			async: false,
			cache: false,
			url: 'ajax.php',
			data: 'action=reset_filter&name=' + field
		});
	});
});