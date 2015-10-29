{if !empty($field_groups)}
	{ia_hooker name='viewListingBeforeFieldsDisplay'}

	<!-- Grouped fields -->
	<ul class="nav nav-tabs" id="fieldTabs">
		{foreach from=$field_groups key=group_name item=fields}
			{if 'non_group' != $group_name}
				<li class="tab_{$group_name}"><a href="#tab-pane_{$group_name}" data-toggle="tab">{lang key="field_group_title_{$group_name}"}</a></li>
			{/if}
		{/foreach}
	</ul>

	<div class="tab-content" id="fieldTabsContent">
		{foreach from=$field_groups key=group_name item=fields}
			{if 'non_group' != $group_name}
				<div class="tab-pane" id="tab-pane_{$group_name}">
			{else}
				{capture name='nogroup'}
			{/if}

			<div class="ia-wrap">
				{if !empty($fields) && !is_array($fields)}
					{$fields}
				{else}
					{foreach $fields as $field}
						<div class="ia-field clearfix plain">
							{assign var='key' value=$field.name}
							{assign var='field_name' value="field_{$field.name}"}

							{if $listing.$key || '0' == $listing.$key}
								<div class="title">{lang key=$field_name}</div>

								<div class="content">
									{if in_array($field.type, array('text', 'textarea', 'number'))}
										{$listing.$key}
									{elseif in_array($field.type, array('combo', 'radio'))}
										{lang key="field_{$field.name}_{$listing.$key}"}
									{elseif 'checkbox' == $field.type}
										{foreach from=','|explode:$listing.$key item=field_val}
											{lang key="field_{$field.name}_{$field_val}"}{if !$field_val@last},&nbsp;{/if}
										{/foreach}
									{elseif 'storage' == $field.type}
										<a href="{$smarty.const.IA_URL}uploads/{$listing.$key}">{lang key='download'}</a>
									{elseif 'image' == $field.type}
										{assign var='image_name' value="small_{$listing.$key}"}
										{assign var='image_path' value=$smarty.const.IA_UPLOADS|cat:$image_name}
										{assign var='image_title' value=$listing["{$key}_title"]}

										{if $image_path|file_exists}
											<a href="{$smarty.const.IA_URL}uploads/{$listing.$key}" target="_blank" rel="ia_lightbox[{$key}]">{print_img ups=true full=true fl=$image_name title=$image_title}</a>
										{else}
											<a href="{$smarty.const.IA_URL}uploads/{$listing.$key}" target="_blank" rel="ia_lightbox[{$key}]">{print_img ups=true full=true fl=$listing.$key title=$image_title}</a>
										{/if}
									{elseif 'pictures' == $field.type}
										{assign var='images' value=","|explode:$listing.$key}
										{assign var='image_title_key' value="{$key}_titles"}
										{assign var='image_titles' value=","|explode:$listing.$image_title_key}

										{if 'gallery' == $field.pic_type}
											{ia_add_media files='css:js/jquery/plugins/flexslider/flexslider'}

											<div class="ia-gallery__wrapper">
												<div class="flexslider ia-gallery">
													<ul class="slides">
														{foreach from=$images key=indx item=image}
															{assign var='image_name' value="small_$image"}
															{assign var='image_path' value=$smarty.const.IA_UPLOADS|cat:$image_name}
															{if isset($image_titles.$indx)}
																{assign var='image_title' value=$image_titles.$indx}
															{else}
																{assign var='image_title' value=''}
															{/if}
												
															<li>
																{if $image_path|file_exists}
																	<a href="{$smarty.const.IA_URL}uploads/{$image}" rel="ia_lightbox[{$key}]" title="{$image_title}">{print_img ups=true full=true fl=$image title=$image_title class='ia-gallery__image'}</a>
																{else}
																	<a href="{$smarty.const.IA_URL}uploads/{$image}" rel="ia_lightbox[{$key}]" title="{$image_title}">{print_img ups=true full=true fl=$image title=$image_title class='ia-gallery__image'}</a>
																{/if}
															</li>
														{/foreach}
													</ul>
												</div>
												
												<div class="flexslider ia-gallery-carousel">
													<ul class="slides">
														{foreach from=$images key=indx item=image}
															{assign var='image_name' value="small_$image"}
															{assign var='image_path' value=$smarty.const.IA_UPLOADS|cat:$image_name}
												
															<li>
																{if $image_path|file_exists}
																	<a href="#">{print_img ups=true full=true fl=$image_name}</a>
																{else}
																	<a href="#">{print_img ups=true full=true fl=$image}</a>
																{/if}
															</li>
														{/foreach}
													</ul>
												</div>
											</div>

											{ia_add_js}
// The slider being synced must be initialized first
$('.ia-gallery-carousel').flexslider(
{
	animation: "slide",
	controlNav: false,
	animationLoop: false,
	slideshow: false,
	itemWidth: 80,
	itemMargin: 5,
	asNavFor: '.ia-gallery',
	prevText: '',
	nextText: ''
});

$('.ia-gallery').flexslider(
{
	animation: "slide",
	controlNav: false,
	directionNav: false,
	animationLoop: false,
	slideshow: false,
	sync: ".ia-gallery-carousel"
});
											{/ia_add_js}

										{else}
											<ul class="thumbnails ia-gallery--simple">
												{foreach from=$images key=indx item=image}
													{assign var='image_name' value="small_$image"}
													{assign var='image_path' value=$smarty.const.IA_UPLOADS|cat:$image_name}

													{if isset($image_titles.$indx)}
														{assign var='image_title' value=$image_titles.$indx}
													{else}
														{assign var='image_title' value=''}
													{/if}

													<li class="span2">
														{if $image_path|file_exists}
															<a href="{$smarty.const.IA_URL}uploads/{$image}" rel="ia_lightbox[{$key}]" title="{$image_title}" class="thumbnail">{print_img ups=true full=true fl=$image_name title=$image_title}</a>
														{else}
															<a href="{$smarty.const.IA_URL}uploads/{$image}" rel="ia_lightbox[{$key}]" title="{$image_title}" class="thumbnail">{print_img ups=true full=true fl=$image title=$image_title}</a>
														{/if}
													</li>
												{/foreach}
											</ul>
										{/if}
									{/if}
								</div>
							{/if}
						</div>
					{/foreach}
				{/if}
			</div>

			{if $group_name != 'non_group'}
				</div>
			{else}
				{/capture}
			{/if}
		{/foreach}
	</div>

	<!-- non grouped fields -->
	<div id="fieldNonGroup">{$smarty.capture.nogroup}</div>

	{ia_hooker name='viewListingAfterFieldsDisplay'}
{/if}