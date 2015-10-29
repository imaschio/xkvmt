intelli.importer = function()
{
	var vUrl = 'controller.php?file=importer';

	return {
		vUrl: vUrl
	};
}();

Ext.onReady(function()
{
	var fields = ['importer', 'host', 'database', 'username', 'password', 'prefix'];

	$('#resetdb').click(function()
	{
		fields.map(function(item)
		{
			var _this = $('#' + item);
			_this.val('');
			_this.prop('disabled', false);
		});

		$("#tables_panel").hide();
	});

	$('#placehold').click(function()
	{
		fields.map(function(item)
		{
			var _this = $('#' + item);
			_this.val(_this.attr('placeholder'));
		});
	});

	$('#connect').click(function()
	{
		var params = [];
		var value;
		var error = false;

		fields.map(function(item)
		{
			value = $('#' + item).val();
			if ('undefined' != typeof value && value != '')
			{
				params[item] = value;
			}
			else
			{
				error = true;
			}
		});

		if (error)
		{
			intelli.admin.notifBox({
				msg: 'Incorrect params passed.',
				type: 'error',
				autohide: true
			})
		}
		else
		{
			$.post(intelli.importer.vUrl,
			{
				action: 'check',
				prevent_csrf: $('input[name="prevent_csrf"]').val(),
				importer: params['importer'],
				host: params['host'],
				database: params['database'],
				username: params['username'],
				password: params['password'],
				prefix: params['prefix']
			}, function(data)
			{
				var data = eval('(' + data + ')');

				if (!data.error)
				{
					// disable form elements
					var inputs = $('#import_form').find('input[type="text"],input[type="password"],select');
					inputs.each(function()
					{
						$(this).prop('disabled', true);
						$(this).addClass('disabled');
					});

					var items_array = data.items;
					if (items_array)
					{
						var html = '<div class="fields-pages">';
						items_array.map(function(item)
						{
							html += '<label for="' + item.name + '"><input type="radio" id="' + item.name + '" name="item" value="' + item.name + '">' + item.name + ' - ' + item.total + '</label>';
						});
						html += '</div>';

						$('#migrate_items').html(html);
					}
					$("#tables_panel").fadeIn();
				}
				else
				{
					intelli.admin.notifBox({
						msg: data.msg,
						type: 'error',
						autohide: true
					});

					$("#tables_panel").hide();
				}
			});
		}
	});

	$('#start').click(function()
	{
		var form = $('#import_form');
		var inputs = form.find('input,select');
		var names = ['importer', 'host', 'database', 'username', 'password', 'prefix'];
		var values = { action: 'import', prevent_csrf: $('input[name="prevent_csrf"]').val(), item: $('input[name="item"]:checked').val(), start: 0 };

		inputs.each(function()
		{
			if (intelli.inArray($(this).attr('name'), names))
			{
				values[$(this).attr('name')] = $(this).val();
			}
		});

		Ext.Msg.show(
		{
			title: intelli.admin.lang.confirm,
			msg: 'Your current ' + $('input[name="item"]:checked').val() + ' related tables will be truncated. Are you sure?',
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn)
			{
				if ('yes' == btn)
				{
					$('#progress_bars').css("width", "0");
					$('#percents').empty().html("0");

					process_import(values);

					intelli.admin.notifBox({msg: 'Import completed.', type: 'notif', autohide: true });
				}
			}
		});

		return false;
	});

	function process_import(values)
	{
		$.ajax( { type: "POST", url: intelli.importer.vUrl, data: values } ).done(function(data)
		{
			var data = eval('(' + data + ')');
			var type = data.error ? 'error' : 'notif';

			if (data.error)
			{
				intelli.admin.notifBox({msg: data.msg, type: type, autohide: true});
			}
			else
			{
				if (data['nums'] > 0)
				{
					values['start'] = parseInt(data['nums']);

					$('#progress_bars').css("width", data['percents'] + "%");
					$('#percents').empty().html(data['percents'] + "%");

					process_import(values);
				}
				else
				{
					$('#progress_bars').css("width", "100%");
					$('#percents').empty().html("100%");
				}
			}
		});
	}
});