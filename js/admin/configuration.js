intelli.configuration = function()
{
	var vUrl = 'controller.php?file=configuration';

	return {
		vUrl: vUrl
	};
}();

Ext.onReady(function()
{
	if($("input[name='param[mcross_functionality]']").val() == 0)
	{
		$("#mcross_number_links").parent().parent().css('display','none');
		$("#box-conf-mcross_only_sponsored").parent().parent().css('display','none');
	}
	else
	{
		$("#mcross_number_links").parent().parent().css('display','');
		$("#box-conf-mcross_only_sponsored").parent().parent().css('display','');
	}
	
	$("input[name='param[mcross_functionality]']").change(function()
	{
		if($(this).val() == 0)
		{
			$("#mcross_number_links").parent().parent().css('display','none');
			$("#box-conf-mcross_only_sponsored").parent().parent().css('display','none');
		}
		else
		{
			$("#mcross_number_links").parent().parent().css('display','');
			$("#box-conf-mcross_only_sponsored").parent().parent().css('display','');
		}
	});

	$("#show_htaccess").click(function()
	{
		var display = 'block' == $("#htaccess").css("display") ? 'hide' : 'show';

		$("#htaccess")[display](function() {
			if($('#htaccess').is(':visible')) {
				var clip = new ZeroClipboard($('.button.copy'), {
					moviePath: intelli.config.esyn_url + 'js/utils/zeroclipboard/ZeroClipboard.swf',
					hoverClass: 'hover',
					activeClass: 'active'
				});

				clip.on('dataRequested', function (client, args) {
					var nodeText = '';

					$.each($('textarea', '#htaccess_code'), function(i, node) {
						nodeText += $(node).val() + '\n\n';
					});

					client.setText(nodeText);
				});
			}
		});

		return false;
	});

	$("#download").click(function()
	{
		window.location = window.location.href + '&download_htaccess';

		return false;
	});

	function showImagePanel(conf)
	{
		/*
		* Vewing Site Logo image
		*/
		var uploadForm = new Ext.FormPanel({
		   fileUpload: true,
		   border: false,
		   width: 500,
		   frame: true,
		   autoHeight: true,
		   bodyStyle: 'padding: 10px 10px 0 10px;',
		   labelWidth: 60,
		   defaults:
		   {
			   anchor: '95%',
			   allowBlank: false,
			   msgTarget: 'side'
		   },
		   items: [
		   {
			   xtype: 'fileuploadfield',
			   id: 'form-file',
			   emptyText: intelli.admin.lang.select_image,
			   fieldLabel: intelli.admin.lang.image,
			   allowBlank: false,
			   name: conf
		   }],
		   buttons: [
		   {
			   text: intelli.admin.lang.remove,
			   disable: '' != intelli.config[conf] ? false : true,
			   handler: function()
			   {
				   Ext.Ajax.request(
				   {
					   url: intelli.configuration.vUrl,
					   method: 'GET',
					   params:
					   {
						   action: 'remove_image',
						   conf: conf
					   },
					   failure: function()
					   {
						   Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
					   },
					   success: function(data)
					   {
						   Ext.get(viewImage.getEl().query('img')).remove();

						   $('#conf_' + conf + '').siblings('a').remove();
						   
						   upload_window.hide().show();
					   }
				   });
			   }
		   },{
			   text: intelli.admin.lang.upload,
			   handler: function()
			   {
				   if(uploadForm.getForm().isValid())
				   {
					   uploadForm.getForm().submit(
					   {
						   url: intelli.configuration.vUrl + '&action=upload&conf=' + conf,
						   waitMsg: intelli.admin.lang.uploading_image,
						   success: function(form, o)
						   {
							   var icon = (o.result.error) ? Ext.MessageBox.ERROR  : Ext.MessageBox.INFO;
   
							   Ext.Msg.show(
							   {
								   title: intelli.admin.lang.uploading_image,
								   msg: o.result.msg,
								   buttons: Ext.Msg.OK,
								   icon: icon
							   });
   
							   form.reset();
   
							   viewImage.getEl().update('<img src="' + intelli.config.esyn_url + 'uploads/' + o.result.file_name + '" alt="" />');
						   }
					   });
				   }
			   }
		   },{
			   text: intelli.admin.lang.reset,
			   handler: function()
			   {
				   uploadForm.getForm().reset();
			   }
		   }]
		});
		
		var viewImage = new Ext.Panel(
		{
			border: false,
			bodyStyle: "text-align: center",
			autoLoad:
			{
				url: intelli.configuration.vUrl + '&action=get_image&conf=' + conf,
				scripts: false
			}
		});
	
		var upload_window = new Ext.Window(
		{
			title: intelli.admin.lang.uploading_image,
			border: false,
			width: 515,
			modal: true,
			resizable: false,
			autoScroll: true,
			closeAction : 'hide',
			items: [viewImage, uploadForm],
			buttons: [
			{
				text: intelli.admin.lang.close,
				handler: function()
				{
					upload_window.hide();
				}
			}]
		});
		
		upload_window.show();
	}

	$("a.view_image").click(function()
	{
		var conf = $(this).siblings("input[type='file']").attr("name");
		
		showImagePanel(conf);

		return false;
	});

	$("a.remove_image").click(function()
	{
		var conf = $(this).siblings("input[type='file']").attr("name");
	
		Ext.Ajax.request(
		{
			url: intelli.configuration.vUrl,
			method: 'GET',
			params:
			{
				action: 'remove_image',
				conf: conf
			},
			failure: function()
			{
				Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
			},
			success: function(data)
			{
				$('#conf_' + conf).siblings('a').remove();

				Ext.MessageBox.alert(intelli.admin.lang.image_removed);
			}
		});

		return false;
	});

	$("textarea.cked").each(function()
	{
		intelli.ckeditor($(this).attr("id"), {toolbar: 'User', height: '200px'});
	});

	$.get(intelli.configuration.vUrl, {action: 'permission'}, function(data)
	{
		var data = eval('(' + data + ')');
		
		if (!data)
		{
			new Ext.ToolTip(
			{
				target: 'rebuild',
				html: intelli.admin.lang.notif_htaccess_permission
			});

			$('#htaccess a.rebuild').addClass("disabled");
		}
	});

	$('a.rebuild').click(function()
	{
		Ext.Ajax.request(
		{
			url: intelli.configuration.vUrl,
			method: 'GET',
			params:
			{
				action: 'rebuild'
			},
			failure: function()
			{
				Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
			},
			success: function(data)
			{
				Ext.MessageBox.alert(intelli.admin.lang.notification, intelli.admin.lang.notif_htaccess_rebuilt);
			}
		});

		return false;
	});
	
	$('#htaccess a.button').click(function()
	{
		if ($(this).hasClass('rebuild'))
		{
			if(!$(this).hasClass("disabled"))
			{
				Ext.Ajax.request(
				{
					url: intelli.configuration.vUrl,
					method: 'GET',
					params:
					{
						action: 'rebuild'
					},
					failure: function()
					{
						Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
					},
					success: function(data)
					{
						Ext.MessageBox.alert(intelli.admin.lang.notification, intelli.admin.lang.notif_htaccess_rebuilt);
					}
				});
			}
		}

		if ($(this).hasClass('close'))
		{
			if('block' == $("#htaccess").css("display"))
			{
				$("#htaccess").hide();
			}
		}

		if ($(this).hasClass('save'))
		{
			$('#htaccess_form').submit();
		}

		return false;
	});
});
