$(function()
{
	$('.js-update-thumbnail').on('click', function(e)
	{
		e.preventDefault();

		var id = $(this).data('listing-id');
		var vUrl = 'view-listing.php?id=' + id;

		$.get(vUrl, {
				action: 'update_thumb',
				'domain': $(this).attr('href')
			}, function(data) {
				var result = jQuery.parseJSON(data);
				var token = Math.floor(Math.random() * 6);
				$('#listing-'+id+' .media-object').removeAttr('src').attr('src', 'uploads/thumbnails/' + result.img + '?upd=' + token );
				alert(intelli.lang.thumbnail_updated);
			}
		);
		return false;
	});
});
