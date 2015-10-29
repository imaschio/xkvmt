Ext.onReady(function()
{
	$("div.selecting a").each(function()
	{
		$(this).click(function()
		{
			var selected = ('select' == $(this).attr("class")) ? 'selected' : '';

			$('#tbl option').attr('selected', selected);

			return false;
		});
	});

	$("#save_file").click(function()
	{
		var display = $(this).attr("checked") ? 'block' : 'none';

		$("#save_to").css("display", display);
	});

	$("#exportAction").click(function()
	{
		if($("#sql_structure").attr("checked") || $("#sql_data").attr("checked"))
		{
			$("#export").attr("value", "1");
			$("#dump").submit();

			return true;
		}
		else
		{
			intelli.admin.alert(
			{
				title: intelli.admin.lang.error,
				type: 'error',
				msg: intelli.admin.lang.export_not_checked
			});
		}

		return false;
	});

	$("#importAction").click(function()
	{
		if($("#sql_file").attr("value"))
		{
			$("#run_update").attr("value", "1");
			$("#update").submit();

			return true;
		}
		else
		{
			intelli.admin.alert(
			{
				title: intelli.admin.lang.error,
				type: 'error',
				msg: intelli.admin.lang.choose_import_file
			});
		}

		return false;
	});

	$("#addTableButton").click(function()
	{
		addData('table');
	});

	$("#table").dblclick(function()
	{
		addData('table');
	});

	var tables = [];

	$("#table").click(function()
	{
		var table = $(this).attr("value");

		if(table)
		{
			if (!tables[table])
			{
				$.ajax(
				{
					type: "GET",
					url: "controller.php?file=database",
					data: "action=fields&table=" + table,
					success: function(data)
					{
						var items = eval('(' + data + ')');
						var fields = $("#field")[0];

						tables[table] = items;

						fields.options.length = 0;

						for (var i = 0; i < items.length; i++)
						{
							fields.options[fields.options.length] = new Option(items[i], items[i]);
						}

						fields.options[0].selected = true;

						// Show dropdown and the button
						$("#field").fadeIn();
						$("#addFieldButton").fadeIn();
					}
				});
			}
			else
			{
				var items = tables[table];
				var fields = $("#field")[0];

				fields.options.length = 0;

				for (var i = 0; i < items.length; i++)
				{
					fields.options[fields.options.length] = new Option(items[i], items[i]);
				}

				fields.options[0].selected = true;

				// Show dropdown and the button
				$("#field").fadeIn();
				$("#addFieldButton").fadeIn();
			}
		}
	});

	$("#addFieldButton").click(function()
	{
		addData('field');
	});

	$("#field").dblclick(function()
	{
		addData('field');
	});

	$("#clearButton").click(function()
	{
		Ext.Msg.confirm('Question', intelli.admin.lang.clear_confirm, function(btn, text)
		{
			if (btn == 'yes')
			{
				$("#query").attr("value", "SELECT * FROM ");
				$("#field").fadeOut();
				$("#addFieldButton").fadeOut();
			}
		});

		return true;
	});

	if($("#backup_message").length > 0)
	{
		$("#server").attr("disabled", "disabled");
	}

	function addData(item)
	{
		var value = $("#" + item).attr("value");

		if (value)
		{
			$("#query").attr("value", $("#query").attr("value") + "`" + value + "` ");
		}
		else
		{
			intelli.admin.alert(
			{
				title: 'Error',
				type: 'error',
				msg: 'Please choose any ' + item + '.'
			});
		}
	}

	/*
	 * Reseting tables
	 */
	$("#all_options").click(function()
	{
		var checked = $(this).attr("checked") ? 'checked' : '';

		$("input[name='options[]']").each(function()
		{
			$(this).attr("checked", checked);
		});
	});

	if($('#sql_table').length > 0)
	{
		// create the grid
		var grid = new Ext.ux.grid.TableGrid("sql_table", {
			stripeRows: true // stripe alternate rows
		});

		grid.render();

		$('#sql_table').show();
	}

	// handle ajax calls
	$('.ajax').click(function()
	{
		var _this 			= $(this);
		var container 		= _this.closest('li');
		var numTotal 		= _this.data('num');
		var progressBlock	= $('.consistency-progress');
		var annotation 		= container.find('.consistency-item-annotation');

		// clear progress info
		progressBlock.find('.current-info').text('0');
		progressBlock.find('.bar').removeAttr('style');
		progressBlock.find('.limit').val('100');

		// append and set new values
		progressBlock.appendTo(container.find('.consistency-item-actions'));
		// $(annotation).before(progressBlock);
		container.addClass('active').siblings().removeClass('active');

		return false;
	});

	var ajax_call = '';
	$('.run').live('click', function()
	{
		$('#ajax-loader').remove();
		$('.stop').removeClass('hide');
		$(this).hide();

		var _this = $(this);
		var type = _this.parents('li').find('a').data('type');
		var prelaunch = _this.parents('li').find('a').data('prelaunch');
		var total = _this.parents('li').find('a').data('num');
		var start = 0;
		var limit = +($('.limit').val());
		var prgInfo = _this.parents('li').find('.current-info');
		var prgBar = _this.parents('li').find('.bar');
		var prgWdth = 0;
		var level = 1;
		var max_level = 1;

		if ($('#all_listings').attr('checked'))
		{
			var all_listings = '1';
		}
		else
		{
			var all_listings = '0';
		}

		if(prelaunch)
		{
			$.get("controller.php?file=database&page=consistency&prelaunch=1", 
			{
				call: 'ajax',
				type: type,
			},
			function(data)
			{
				var data = jQuery.parseJSON(data);
				if (data.level)
				{
					max_level = data.level;
				}
			});
		}

		function progress(start)
		{
			$.get("controller.php?file=database&page=consistency",
			{
				call: 'ajax',
				type: type,
				start: start,
				limit: limit,
				level: level,
				all_listings: all_listings
			},
			function(data)
			{
				var data = jQuery.parseJSON(data);

				if (data.level)
				{
					level++;
					start = 0;
				}
				else
				{
					start = limit+start;
				}

				if (data.num_updated)
				{
					prgWdth = data.num_updated > 0 ? prgWdth+data.num_updated : prgWdth;
				}
				else
				{
					prgWdth = prgWdth+limit;
				}

				if(start < total && level <= max_level)
				{
					ajax_call = setTimeout(function() {	
						progress(start);
						prgInfo.text(Math.round(prgWdth * 100 / total) + '%');
						prgBar.addClass('active').animate({
							width: prgWdth * 200 / total
						}, 'fast');
					}, 500 );
				}
				else
				{
					prgBar.animate({
						width: '210px'
					}, 'fast', function () {
						prgInfo.text('100%');
						prgBar.removeClass('active');
					});

					start = 0;
					$('.run').show();
					$('.stop').addClass('hide');
				}

			});
		}

		ajax_call = setTimeout( function() { progress(start); }, 1000 );

		return false;
	});

	$('.stop').live('click', function(e)
	{
		clearTimeout(ajax_call);
		$('.run').show();
		$(this).addClass('hide');
		start = 0;

		return false;
	});
});