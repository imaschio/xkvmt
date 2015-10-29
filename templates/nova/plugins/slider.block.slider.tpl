{if $slides}
	<div class="flexslider flexslider-nova">
		<ul class="slides">
			{foreach from=$slides key=slide_index_key item=slide}
				<li class="{$slide.classname}">
					{print_img ups=true full=true fl=$slide.image alt=$slide.title}
					<div class="container">
						<div class="caption">
							<h2>{$slide.title}</h2>
							<p>{$slide.description|strip_tags|truncate:300:'...'}</p>
						</div>
					</div>
				</li>
			{/foreach}
		</ul>
	</div>

	{ia_add_js}
		$('.flexslider-nova').flexslider(
		{
			pauseOnHover: true,
			slideshowSpeed: {$config.slideshow_speed},
			animationSpeed: {$config.animation_speed},
			animation: '{$config.slider_animation}',
			direction: '{$config.slider_direction}'
		});
	{/ia_add_js}
{/if}