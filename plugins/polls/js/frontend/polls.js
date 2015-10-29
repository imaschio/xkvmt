$(".poll_options_list input.poll_option").click(function()
{
	var option_id;
	option_id = $(this).attr('id');
	option_id = option_id.split('_');
	// extract option id
	poll_id   = option_id[2];
	option_id = option_id[3]; 
	
	$("#poll_"+poll_id).fadeOut();

	gl = this;
	
	$.post("controller.php?plugin=polls", {id: option_id, poll_id: poll_id, action: 'vote'}, function(data) 
	{
		var data = eval('(' + data + ')');
		var type = data.error ? 'error' : 'notification';
		
		if(!data.error)
		{
			$("#poll_"+poll_id).html(data.data).fadeIn();
		}
	})
});