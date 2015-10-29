var item_name = $("input[name='item_name']").val();

if (1 == intelli.config['comments_' + item_name + '_html'])
{
	$("textarea.ckeditor_textarea").each(function()
	{
		if(!CKEDITOR.instances[$(this).attr("id")])
		{
			intelli.ckeditor($(this).attr("id"), {toolbar: 'Basic', counter: 'comment_counter', min_length: intelli.config['comments_' + item_name + '_min_chars'], max_length: intelli.config['comments_' + item_name + '_max_chars']});
		}

		$("#add_comment").click(function() {
			CKEDITOR.instances.comment_form.updateElement();
		});
	});
}

$(function()
{
	if(intelli.config.comments_rating == 1)
	{
		var comment_rating = new commentRating({
			el: 'comment-rating',
			max: intelli.config.comments_rating_max,
			text: intelli.lang.comment_rate_this,
			cls: 'comment_'
		});

		comment_rating.init();
	}

	var comment_textcounter = new intelli.textcounter({
		textarea_el: 'comment_form'
		,counter_el: 'comment_counter'
		,max: intelli.config['comments_' + item_name + '_max_chars']
		,min: intelli.config['comments_' + item_name + '_min_chars']
	});

	comment_textcounter.init();

	$("#comment").validate(
	{
		rules:
		{
			author: "required"
			,email:
			{
				required: true
				,email: true
			}
			,security_code: "required"
			,comment:
			{
				required: true
				,minlength: intelli.config['comments_' + item_name + '_min_chars']
				,maxlength: intelli.config['comments_' + item_name + '_max_chars']
			}
		}
		,submitHandler: function(form)
		{
			var el = $("#add_comment");
			//var form = $(this);

			el.attr("disabled", "disabled");
			el.val("Loading...");
			el.css("background", "url('templates/common/img/ajax-loader.gif') left top no-repeat");
			el.css("padding-left", "15px");

			var author = $("#comment input[name='author']").val();
			var email = $("#comment input[name='email']").val();
			var comment_rating = $("#comment input[name='comment_rating']").val();
			var body =$("#comment_form").val();
			var item_id = $("input[name='item_id']").val();
			var security_code = $("input[name='security_code']").val();

			$.post("controller.php?plugin=comments", {action: 'add', author: author, email: email, comment_rating: comment_rating, body: body, security_code:security_code, item_id: item_id, item_name:item_name}, function(out)
			{
				var data = eval('(' + out + ')');
				var type = data.error ? 'error' : 'notification';
				
				el.attr("disabled", "");
				el.val("Leave comment");
				el.css("background", "");
				el.css("padding-left", "");

				if(!data.error)
				{
					if(1 == intelli.config['comments_' + item_name + '_approval'])
					{
						var html = new Array();

						html = ['<hr /><div class="posted">', intelli.lang.comment_author, '&nbsp;',
						'<strong>', data.comment.author, '</strong>&nbsp;/&nbsp;', data.comment.date, '</div>',
						'<div class="comment">', data.comment.body, '</div>'].join('');

						$("#comments_container").append(html);
					}

					$("#comment input[name='author'][type='text']").val('');
					$("#comment input[name='email'][type='text']").val('');
					$("#comment input[name='url'][type='text']").val('');
					$("#comment input[name='security_code'][type='text']").val('');
					$("#captcha_image_1").click();
					$("#comment_form").val('');

					comment_textcounter.init();
				}

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