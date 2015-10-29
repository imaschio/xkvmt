$(function(){
	if($("#answer").length > 0)
	{
		CKEDITOR.replace('answer', {toolbar: 'User'});
		
		var setLang = function()
		{
			var cat_opt = $("#faq_category option:selected");
			var cat_id = cat_opt.val();
			var faq_lang = $("#faq_lang");
			if (cat_id == '-1')
			{
				faq_lang.removeAttr("disabled");
			}else{
				var lang = cat_opt.attr("class");
				faq_lang.val(lang);
				$("#lang2").val(lang);
				faq_lang.attr("disabled","disabled");
			}
		};
		$("#faq_category").change(function()
		{
			setLang();
		});
		setLang();
	}

	if($("#description").length > 0)
	{
		CKEDITOR.replace('description', {toolbar: 'User'});
	}
	
	
});