Ext.onReady(function()
{
	$('#google-map .ajax').on('click', function(){
		$('.limit').val('5').attr("readonly", true);
	});
});