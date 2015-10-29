$(function()
{
	var pause = 1;
	var start = 0;
	
	$(function()
	{
		$("#start_yandex").bind('click', function()
		{
			if (1 == pause)
			{
				start = $('#start_num_yandex').val();
				pause = 0;
				main();
				$('#start_yandex').val('Pause');
			}
			else
			{
				pause = 1;
				$('#start_yandex').val('Start');
			}
		});
	});

	function main()
	{
		var url = 'controller.php?plugin=yandexrank';

		if (0 != pause) return false;
		
		Ext.Ajax.request(
		{
			waitMsg: intelli.admin.lang.saving_changes,
			url: url,
			method: 'POST',
			params:
			{
				action: 'recountyandex',
				start: start
			},
			failure: function()
			{
				Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
			},
			success: function(data)
			{
				outdata = eval('(' + data['responseText'] + ')');

				if (outdata['num'] > 0)
				{
					start = parseInt(outdata['num']);

					$('#start_num_yandex').val(start);
					$('#progress_bar_yandex').css("width",outdata['percent']+"%");
					$('#percent_yandex').empty().html(outdata['percent']+"%");
					main();
				}
				else
				{
					pause = 1;
					$('#progress_bar_yandex').css("width","100%");
					$('#percent_yandex').empty().html("100%");
					$('#start_yandex').val('Start');
				}
			}
		});
	}
});