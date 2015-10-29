$(function()
{
	var plan = $('input[name="plan"]:checked');
	var cost = $('#planCost_' + plan.val()).val();
	var display = (cost > 0) ? 'show' : 'hide';

	$('#gateways')[display]();

	$('input[name="plan"]').click(function()
	{
		var cost = $('#planCost_' + $(this).val()).val();

		var display = (cost > 0) ? 'show' : 'hide';

		$('#gateways')[display]();
	});
});