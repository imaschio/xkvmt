$(function()
{
	fillDuration();

	if($("#recurring").attr("checked"))
	{
		$("#duration").prop("disabled", "");
		$("#units_duration").prop("disabled", "");
	}

	$("#units_duration").change(function()
	{
		fillDuration();
	});
	
	$("#recurring").click(function()
	{
		var disabled = $(this).attr("checked") ? '' : 'disabled';

		$("#duration").prop("disabled", disabled);
		$("#units_duration").prop("disabled", disabled);
	});
});

function fillDuration()
{
	var options = {D: 90, W: 52, M: 24, Y: 5};
	var unit_duration = $("#units_duration").val();
	var default_duration = $("#def_duration").val();
	var html = '';

	$("#duration").empty();

	for(var i = 1; i <= options[unit_duration]; i++)
	{
		html += '<option value="'+ i +'">'+ i +'</option>';
	}

	$("#duration").html(html);
	
	if(default_duration > 0)
	{
		$("#duration").val(default_duration);
	}
}