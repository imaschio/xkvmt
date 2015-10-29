$("textarea.ckeditor_textarea").each(function()
{
	if(!CKEDITOR.instances[$(this).attr("id")])
	{
		intelli.ckeditor($(this).attr("id"), {toolbar: 'Basic', counter: 'gb_counter', min_length: intelli.config['gb_min_chars'], max_length: intelli.config['gb_max_chars']});
	}

	$("#add_message").click(function() {
		CKEDITOR.instances.guestbook_form.updateElement();
	});
});

$(function()
{
	var gb_textcounter = new intelli.textcounter({
		textarea_el: 'guestbook_form'
		,counter_el: 'gb_counter'
		,max: intelli.config['gb_max_chars']
		,min: intelli.config['gb_min_chars']
	});

	gb_textcounter.init();

	$("#guestbook").validate(
	{
		rules:
		{
			g_author: "required"
			,g_email:
			{
				required: true
				,email: true
			}
			,security_code: "required"
			,g_message:
			{
				required: true
				,minlength: intelli.config['gb_min_chars']
				,maxlength: intelli.config['gb_max_chars']
			}
		}
		,submitHandler: function(form)
		{
			var el = $("#add");
			//var form = $(this);

			el.attr("disabled", "disabled");
			el.val("Loading...");
			el.css("background", "url('templates/common/img/ajax-loader.gif') left top no-repeat");
			el.css("padding-left", "15px");

			var author = $("input[name='g_author']").val(),
				email = $("input[name='g_email']").val(),
				body = $("#guestbook_form").val(),
				url = $("input[name='g_aurl']").val(),
				reload_captcha;

			switch (intelli.config.captcha_name)
			{
				case 'recaptcha':
					var recaptcha_response_field = $("#recaptcha_response_field").val();
					var recaptcha_challenge_field = $("#recaptcha_challenge_field").val();
					reload_captcha = function (){
						Recaptcha.reload();
					};
					break;

				default:
					var security_code = $("#securityCode").val();
					reload_captcha = function (){
						$("#securityCode").val('');
						$("#captcha_image_1").click();
					};

			}

			$.post("controller.php?plugin=guestbook",
			{
				action: 'add',
				author: author,
				email: email,
				url: url,
				body: body,
				security_code: security_code,
				recaptcha_response_field: recaptcha_response_field,
				recaptcha_challenge_field: recaptcha_challenge_field
			},
			function(data)
			{
				var data = eval('(' + data + ')');
				var type = data.error ? 'error' : 'notification';
				
				el.attr("disabled", "");
				el.val("Leave comment");
				el.css("background", "");
				el.css("padding-left", "");

				if(!data.error)
				{
					if(data.gb)
					{
						var html = '';

						$.each(data.gb,function(i,el){
							html += ['<hr /><div class="posted">', intelli.lang.message_author, '&nbsp;',
									'<strong>', el.author_name, '</strong> (',el.author_url,')&nbsp;/&nbsp;', el.date, '</div>',
									'<div class="comment">', el.body, '</div><hr>'].join('');
						});
						
						$("#listingComment_container").html(html);
					}

					$("input[name='g_author'][type='text']").val('');
					$("input[name='g_email'][type='text']").val('');
					$("input[name='g_aurl'][type='text']").val('');
					$("#guestbook_form").val('');

					gb_textcounter.init();
				}

				reload_captcha();

				intelli.notifBox(
				{
					id: 'error',
					type: type + ' alert alert-info',
					msg: data.msg
				});
			});
		}
	});
});