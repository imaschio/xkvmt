$(function()
{
	// activate first tab
	if ($("#fieldTabs").length > 0)
	{
		$('#fieldTabs a:first').tab('show');
	}

	$('fieldset.collapsible > legend').each(function()
	{
		var fieldset = $(this.parentNode);
		var text = this.innerHTML;

		x = $('<div class="fieldset-wrapper"></div>').append(fieldset.children(':not(legend)'));

		$(this).empty().append($('<a href="#">'+ text +'</a>').click(function()
		{
			var fieldset = $(this).parents('fieldset:first')[0];
			intelli.common.toggleFieldset(fieldset);

			return false;

		})).after(x);
	});
});