$(function()
{
	if ($('.b-plan input[name="plan"]').length > 0)
	{
		if ($('.b-plan input[name="plan"]:checked').length == 0)
		{
			$('.b-plan input[name="plan"]:first').attr('checked', 'checked');
		}
	}

	if ($('#gateways input[name="payment_gateway"]').length > 0)
	{
		if ($('#gateways input[name="payment_gateway"]:checked').length == 0)
		{
			$('#gateways input[name="payment_gateway"]:first').attr('checked', 'checked');
		}

		$('#gateways').show();
	}

	$("#auto_generate").click(function()
	{
		if ($(this).attr("checked"))
		{
			intelli.display('passwords', 'hide');

			$("#pass1").attr("disabled", "disabled");
			$("#pass2").attr("disabled", "disabled");
		}
		else
		{
			intelli.display('passwords', 'show');

			$("#pass1").removeAttr("disabled");
			$("#pass2").removeAttr("disabled");
		}
	});

	if (!$("#auto_generate").attr("checked"))
	{
		intelli.display('passwords', 'show');
	}
});