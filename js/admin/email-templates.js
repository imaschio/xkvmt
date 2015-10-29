$.fn.extend(
{
	insertAtCaret: function(myValue)
	{
		var obj;

		if( typeof this[0].name !='undefined' ) obj = this[0];
        else obj = this;

		if ($.browser.msie)
		{
			obj.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
			obj.focus();
		}
		else if ($.browser.mozilla || $.browser.webkit)
		{
			var startPos = obj.selectionStart;
			var endPos = obj.selectionEnd;
			var scrollTop = obj.scrollTop;
			obj.value = obj.value.substring(0, startPos)+myValue+obj.value.substring(endPos,obj.value.length);
			obj.focus();
			obj.selectionStart = startPos + myValue.length;
			obj.selectionEnd = startPos + myValue.length;
			obj.scrollTop = scrollTop;
		}
		else
		{
			obj.value += myValue;
			obj.focus();
		}
	}
});

$(function()
{
	var tags_win = new Ext.Window(
	{
		title: intelli.admin.lang.email_templates_tags,
		width : 'auto',
		height : 'auto',
		modal: false,
		autoScroll: true,
		closeAction : 'hide',
		contentEl: 'template_tags',
		buttons: [
		{
			text : intelli.admin.lang.close,
			handler : function()
			{
				tags_win.hide();
			}
		}]
	});

	var name_win = new Ext.Window(
	{
		title: intelli.admin.lang.email_template_name,
		width : 'auto',
		height : 'auto',
		modal: true,
		autoScroll: true,
		closeAction : 'hide',
		bodyStyle: 
		{
			padding: '15px'
		},
		contentEl: 'template_name',
		buttons: [
		{
			text : intelli.admin.lang.save,
			handler: function()
			{
				var tpl_name = $("#tpl_name").val();
				if (!tpl_name)
				{
					Ext.MessageBox.alert(intelli.admin.lang.error, intelli.admin.lang.error_fill_email_tpl_name);
				}
				else
				{
					$.get('controller.php', {file: 'email-templates', name: tpl_name, action: 'check-name'}, function(data)
					{
						if(data)
						{
							var html = '<input type="hidden" name="new_tpl_name" value="' + tpl_name + '">';
							$('#tpl_form').append(html);
							$('#tpl_form').submit();
							var t = setTimeout(function()
							{
								intelli.admin.notifBox({msg: intelli.admin.lang.changes_saved, type: 'notif', autohide: true});
								location.reload();
							}, 1000);
						}
						else
						{
							Ext.MessageBox.alert(intelli.admin.lang.error, intelli.admin.lang.error_tpl_name_exists);
						}
					}, 'json');

					name_win.hide();
				}
			}
		},
		{
			text : intelli.admin.lang.cancel,
			handler : function()
			{
				name_win.hide();
			}
		}]
	});

	$('#tags').click(function()
	{
		tags_win.show();

		return false;
	});

	$('#save_as_new').click(function()
	{
		if($('#tpl').val())
		{
			$('#tpl_name').val('');
			name_win.show();
		}

		return false;
	});

	$('input[name="template"]').change(function()
	{
		var template = $('#tpl').val().replace(/tpl_/, '').replace(/_subject/, '');
		var val = $(this).val();

		if('' != template)
		{
			$.get('controller.php', {file: 'email-templates', tmpl: template, val: val, action: 'setconfig'}, function(data)
			{

			});
		}

		return false;
	});

	$('#tpl, #lang').bind('change', function()
	{
		var id = $('#tpl').val();
		var code = $('#lang').val();

		if (!id)
		{
			$('#subject').val('');
			
			if ('html' == intelli.config.mimetype)
			{
				CKEDITOR.instances.body.setData('');
			}
			else
			{
				$('#body').val('');
			}

			$('#switcher').hide();

			return false;
		}

		$('#switcher').show();
		
		$.get('controller.php', {file: 'email-templates', id: id, code: code, action: 'get-tpl'}, function(data)
		{
			$('#subject').val(data['subject']);
			
			if ('html' == intelli.config.mimetype)
			{
				CKEDITOR.instances.body.setData(data['body']);
			}
			else
			{
				$('#body').val(data['body']);
			}

			var trigger = (1 == data.config) ? 'setOn' : 'setOff';

			$('#box-template').trigger(trigger);

		}, 'json');
	});

	$('#tpl_form').ajaxForm({
		success: showResponse,
		beforeSerialize: showRequest
	});

	if ('html' == intelli.config.mimetype)
	{
		intelli.ckeditor('body', {toolbar: 'User'});
	}

	$('a.email_tags').click(function()
	{
		if('html' == intelli.config.mimetype)
		{
			CKEDITOR.instances.body.insertHtml($(this).text());
		}
		else
		{
			$('#body').insertAtCaret($(this).text());
		}

		return false;
	});

});

function showRequest()
{
	if('' == $('#tpl').val())
	{
		return false;
	}

	if ("object" == typeof CKEDITOR.instances.body)
	{
		CKEDITOR.instances.body.updateElement();
	}

	$('#tpl_form').ajaxLoader();
}

function showResponse(data)
{
	$('#tpl_form').ajaxLoaderRemove();
    if (data == 'ok')
    {
        intelli.admin.notifBox({msg: intelli.admin.lang.changes_saved, type: 'notif', autohide: true});
    }
}

