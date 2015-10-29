{if count($faq_cats) > 0}
	{foreach from=$faq_cats item=faq_cat}
		<div class="faq-section">
			{if $faq_cat.id > 0}<h4>{$faq_cat.title} <span>&mdash; {count($faq_cat.faqs)}</span></h4>{/if}

			<div class="faq-block">
				{foreach from=$faq_cat.faqs item=faq}
					<p><a class="js-toggle-answer" href="#">{$faq.question}</a></p>
					<div class="faq-answer" id="faq{$faq.id}">{$faq.answer}</div>
				{/foreach}
			</div>
		</div>
	{/foreach}

	{ia_print_css files='plugins/faq/templates/css/style'}
	{ia_add_js}
		jQuery(document).ready(function($) 
		{
			$('.js-toggle-answer').on('click', function()
			{
				$(this).parent().next().fadeToggle();
				return false;
			});
		});
	{/ia_add_js}
{else}
	<div class="alert alert-info">{lang key='no_faq'}</div>
{/if}