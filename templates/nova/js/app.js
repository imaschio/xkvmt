jQuery(document).ready(function($) {

	// inventory menu dropdown
	$('.nav-login .dropdown-menu input, .nav-login .dropdown-menu label, .nav-login .dropdown-menu a').click(function(e)
	{
		e.stopPropagation();
	});
	$('.nav-login .dropdown-menu .close').click(function()
	{
		$(this).closest('.dropdown-menu').hide();
		return false;
	});

	// Sponsored box slider
	if($('.slider-box').length > 0)
	{
		$('.slider-box').flexslider({
			animation: 'slide',
			controlNav: false
		});
	}

	// toolbar for listings
	if($('.ia-item .toolbar').length > 0) {
		$('.ia-item .toolbar .toolbar-toggle').click(function()
		{
			$(this).next().toggle();
			$('i', this).toggleClass('icon-gray');

			return false;
		});
	}

	// ia-item tile hovering effect
	if($('.ia-item.tile').length > 0) {
		$('.ia-item.tile .tile-body').hover(
			function() {
				var itemLink = $('a', this).attr('href');

				$('.description:not(.regular)', this).stop().animate({
					top: '0'
				}, 'fast');

				$(this).click(function(){
					window.location = itemLink;
				});
			},
			function() {
				var itemLink = $('a', this).attr('href');

				$('.description:not(.regular)', this).stop().animate({
					top: '140px'
				}, 'fast');
			}
		);
	}

	// back to top button
	var scroll_timer,
    	displayed = false,
    	$message = $('#backToTop'),
    	$window = $(window),
    	top = $(document.body).children(0).position().top;

    $window.scroll(function () {
        window.clearTimeout(scroll_timer);
        scroll_timer = window.setTimeout(function () {
            if($window.scrollTop() <= top)
            {
                displayed = false;
                $message.fadeOut(500);
            }
            else if(displayed == false)
            {
                displayed = true;
                $message.stop(true, true).show().click(function () { $message.fadeOut(500); });
            }
        }, 100);
    });

    $('a', $message).click(function () {
    	$('html, body').animate({scrollTop:0}, 'fast');
    	return false;
    });
});