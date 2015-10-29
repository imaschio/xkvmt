$(function() {
	
	base_url = intelli.config.esyn_url;

	FB.init(
	{ 
		appId: intelli.config.fb_app_id, 
		status: true, 
		cookie: true, 
		xfbml : true,
		oauth: true,
		channelUrl : base_url.replace('http:', '') + 'plugins/facebook/channel.html'
	});

	$('.fb-login').click(function(e) {
		e.preventDefault();
		FB.login(function(response) {
			vUrl = base_url + 'controller.php?plugin=facebook&file=connect';
			var options = new Object();
			options.status = response.status;
			
			if(options.status == 'connected')
			{
				FB.api('/me', function(fb_user) {
					options.fb_user = fb_user;

					$.post(vUrl, options, function(data) {
						if(data.status = 'connected')
						{
							window.location = base_url;
						}
					});
				});
			}
		}, {scope: 'email'});
	});

	if($('#ia-exit').attr('data-value') == '1')
	{
		FB.logout();
	}
});