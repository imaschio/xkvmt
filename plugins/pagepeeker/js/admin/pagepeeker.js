$(function()
{
	$('.js-update-thumbnail').on('click', function(e)
	{
		e.preventDefault();

		var vUrl = 'controller.php?file=suggest-listing';
		Ext.Ajax.request(
		{
			url: intelli.suggestListing.vUrl,
			method: 'GET',
			params:
			{
				action: 'update_thumb',
				'domain': $(this).attr('href')
			},
			failure: function()
			{
				Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
			},
			success: function(data)
			{
				var result = Ext.decode(data.responseText);
				var token = Math.floor(Math.random() * 6);
				$('.thumbnail_box img').removeAttr('src').attr('src', '../uploads/thumbnails/' + result.img + '?upd=' + token );

				intelli.admin.notifFloatBox({msg: intelli.admin.lang.thumbnail_updated, type: 'notif'});
			}
		});

		return false;
	});
});
