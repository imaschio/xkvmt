intelli.Options = function()
{
	var vUrl = 'controller.php?file=listing-visual-options';

	return {
		vUrl: vUrl
	};
}();
var TextSize;

Ext.onReady(function()
{
	Ext.namespace("Ext.ux.menu", "Ext.ux.form");
	Ext.QuickTips.init();

	$("#add_star, #add_badge").click(function(event)
	{
		event.preventDefault();

		$('#upload_type').val($(this).attr('id'));

		uploadWindow.show();
	});

	var uploadFormBadge = new Ext.FormPanel(
	{
		fileUpload: true,
		border: false,
		width: 500,
		frame: true,
		autoHeight: true,
		bodyStyle: 'padding: 10px 10px 0 10px;',
		labelWidth: 50,
		defaults: {
			anchor: '95%',
			allowBlank: false,
			msgTarget: 'side'
		},
		items: [
		{
			xtype: 'fileuploadfield',
			id: 'form-file-badge',
			emptyText: intelli.admin.lang.select_icon,
			fieldLabel: intelli.admin.lang.icon,
			allowBlank: false,
			name: 'icon'
		}],
		buttons: [
		{
			text: intelli.admin.lang.upload,
			handler: function()
			{
				if (uploadFormBadge.getForm().isValid())
				{
					var uploadType = $('#upload_type').val();
					uploadFormBadge.getForm().submit(
					{
						url: intelli.Options.vUrl + '&action=upload_icon&icon=' + uploadType,
						waitMsg: intelli.admin.lang.uploading_icon + '...',
						success: function(form, o)
						{
							var icon = (o.result.error) ? Ext.MessageBox.ERROR  : Ext.MessageBox.INFO;

							Ext.Msg.show(
							{
								title: intelli.admin.lang.uploading_icon,
								msg: o.result.msg,
								buttons: Ext.Msg.OK,
								icon: icon
							});

							form.reset();

							$("#preview-" + uploadType).attr("src", intelli.config.esyn_url + o.result.src);
							$('input[name="' + uploadType + '"]').val(o.result.src);
						}
					});
				}
			}
		},{
			text: intelli.admin.lang.reset,
			handler: function()
			{
				uploadFormBadge.getForm().reset();
			}
		}]
	});

	var uploadWindow = new Ext.Window(
	{
		title: intelli.admin.lang.uploading_icon,
		width: 515,
		modal: true,
		resizable: false,
		autoScroll: true,
		closeAction : 'hide',
		items: uploadFormBadge,
		buttons: [
		{
			text: intelli.admin.lang.close,
			handler: function()
			{
				uploadWindow.hide();
			}
		}]
	});

	// highlight link
	var ColorPicker = new Ext.ux.form.ColorPickerField({
		hideHtmlCode:true,
		opacity:false,
		editMode:'all',
		name: 'highlight',
		value: $("input[name=highlight]").val(),
		//ref: 'color',
		id: 'fieldColor'
	});
	ColorPicker.render('highlight');

	// highlight link color
	var ColorLink = new Ext.ux.form.ColorPickerField({
		hideHtmlCode:true,
		opacity:false,
		editMode:'all',
		name: 'color_link',
		value: $("input[name=color_link]").val(),
		//ref: 'color',
		id: 'ColorLink'
	});
	ColorLink.render('color');

	TextSize = new Ext.form.SpinnerField(
	{
		fieldLabel: intelli.admin.lang.link_size,
		name: 'link_big',
		id: 'link_size_out',
		minValue: 10,
		maxValue: 100,
		value: $("#link_big").css("font-size"),
		width: 45
	});

	TextSize.on('spin', function ()
	{
		var size = new Number($('#link_size_out').val());
		var enlarge = 25+size;
		$("#link_big").css("font-size",size+'px');
		$("#link_big").parent().parent().css("height", enlarge+'px');
	});
	TextSize.render('link_size');

	$("#link_big").click(function(){return false;});
});