$(function()
{
	$("table").find("input[name=email]").parents('td').append(' <input type="button" class="common" id="contact_with_user" value="'+intelli.admin.lang.contact_account+'">');
	
	$("#contact_with_user").click(function()
	{
		location.href = "controller.php?plugin=contacts&do=contact&to=" + $("table").find("input[name=email]").val();
	});
});
