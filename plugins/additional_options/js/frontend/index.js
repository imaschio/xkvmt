$(function()
{
	if (logged != '1' || $('.js-remove').data('listing-account') != account_id)
	{
		$('.js-remove').parent().remove();
	}

	// delete listing
	$('.js-remove').click(function()
	{
		var _this = $(this);
		var id = _this.data('id');
		var acc_id = _this.data('listing-account');

		if (logged == 1 && account_id == acc_id)
		{
			if (confirm(intelli.lang.do_you_want_delete_listing))
			{
				var url = intelli.config.esyn_url;
				url += 'controller.php?plugin=additional_options&file=remove-listing&id=' + id + '&account_id=' + account_id;

				window.location = url;

				return false;
			}
			else
			{
				return false;
			}
		}
		else
		{
			alert(intelli.lang.you_cannot_delete_listing);
			return false;
		}
	});
});