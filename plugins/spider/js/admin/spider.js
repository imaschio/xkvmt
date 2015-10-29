$(function() {
	$('#engine').change(function(){
		type = $(this).val();

		switch(type) 
		{
			case "google":
				$(".number_of_results").hide();
			break;

			/*
			case "yahoo":
				$("input[name='keywordsin']").removeAttr("disabled");
			break;
			
			case "bing":
				$(".number_of_results").show();
			break;
			*/
		}
	}).change();

	$("#search_next").click(function(){
		var start = $("#search_start");
		start.val(parseInt(start.val()) + 1);
		$("#search_form").submit();
	});

	$("#search_prev").click(function(){
		var start = $("#search_start");
		start.val(parseInt(start.val()) - 1);
		$("#search_form").submit();
	});

	$("#continue").click(function() {
		$("#search_start").val('0');
	});

	$("#check_all").click(function() {
		$("#result input.common").attr("checked", "checked");
		return false;
	});

	$("#uncheck_all").click(function() {
		$("#result input.common:checked").removeAttr("checked");
		return false;
	});
});