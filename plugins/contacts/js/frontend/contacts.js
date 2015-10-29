$(function()
{
	// process contact member
	$('.js-contact_member').click(function()
	{
		var _this = $(this);
		var id = _this.data('id');
		var account_id = _this.data('listing-account');

		if ('' != id)
		{
			var url = intelli.config.esyn_url + 'mod/contacts/?contact=';

			if (account_id != 0)
			{
				url += 'member&id=' + account_id + '&listing&lid=' + id;
			}
			else
			{
				url += 'listing&id=' + id;
			}

			window.location = url;
		}

		return false;
	});
});