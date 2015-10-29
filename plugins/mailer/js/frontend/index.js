$("#unsubscribe").click( function() {
	if ($("#subscribe").attr("value") == intelli.lang.subscribe)
	{
		$(".realname").css("display","none");
		$("#subscribe").attr("value",intelli.lang.unsubscribe);
		$("#unsubscribe").empty();
		$("#sub").val("0");
		$("#unsubscribe").append(intelli.lang.subscribe);
	}
	else
	{
		$(".realname").css("display","block");
		$("#subscribe").attr("value",intelli.lang.subscribe);
		$("#unsubscribe").empty();
		$("#sub").val("1");
		$("#unsubscribe").append(intelli.lang.unsubscribe);
	}
	return false;
});

// Newsletter block
$("#realname").focus( function() {
	$(".captcha-block").css("display", "block");
});

$("#realname").blur( function() {
	$(".captcha-block").css("display", "none");
});

$("#email").focus( function() {
	$(".captcha-block").css("display", "block");
});

$("#email").blur( function() {
	$(".captcha-block").css("display", "none");
});

$("#securityCode").focus( function() {
	$(".captcha-block").css("display", "block");
});