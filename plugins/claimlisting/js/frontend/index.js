$(function()
{
	$('#check-code-owner').click(function()
	{
		var _this = $(this);
		var string = $('#code').val();
		var id = _this.data('listingid');

		$.post(intelli.config.esyn_url + 'controller.php?plugin=claimlisting', {listing_string: string, id_listing: id, action: 'check'},function(checked)
		{
			if(checked == 1)
			{
				window.location = intelli.config.esyn_url + 'mod/claimlisting/?listing=' + id;
			}
			else
			{
				$('#notification > div.alert').html(intelli.lang.error_claim_code);
				$('#notification').show();
			}
		});
	});

	// process counts for items
	$('.js-claim').click(function()
	{
		var _this = $(this);
		var id = _this.data('id');

		if ('' != id)
		{
			var url = intelli.config.esyn_url;
			url += (logged == 1) ? 'mod/claimlisting/' + id : 'login.php';

			window.location = url;
		}

		return false;
	});
});