{if $slides}
	<div class="flexslider flexslider-esyn">
		<ul class="slides">
			{foreach from=$slides item=slide}
				<li class="{$slide.classname}">
					{print_img ups=true full=true fl=$slide.image title=$slide.title}
					<div class="caption">
						<h3>{$slide.title}</h3>
						{$slide.description|truncate:250:'...'}
					</div>
				</li>
			{/foreach}
		</ul>
	</div>

	{ia_add_media files='css:js/jquery/plugins/flexslider/flexslider, css:plugins/slider/templates/css/esyn-slider'}

	{ia_add_js}
		$('.flexslider-esyn').flexslider({
			pauseOnHover: true,
			slideshowSpeed: {$config.slideshow_speed},
			animationSpeed: {$config.animation_speed},
			animation: '{$config.slider_animation}',
			direction: '{$config.slider_direction}'
		});
	{/ia_add_js}
{/if}