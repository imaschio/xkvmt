$(function(){
	/* Setting up the tree categories */
	$("textarea.ckeditor_textarea").each(function()
	{
		if(!CKEDITOR.instances[$(this).attr("id")])
		{
			intelli.ckeditor($(this).attr("id"), {toolbar: 'Basic'});
		}
	});

	var treeCat = new intelli.tree({
		id: 'tree',
		type: 'checkbox',
		hideRoot: false,
		menuType: 'tree',
		callback: function()
		{
			var catId = $(this).val().replace('_crs', '');

			/* hiding any notification boxes */
			intelli.display('notification', 'hide');

			$('#categoryTitle > strong').text($(this).attr('title'));
			$('#category_id').val(catId);
		},
		dropDownCallback: function(id, title)
		{
			/* hiding any notification boxes */
			intelli.display('notification', 'hide');

				$('#categoryTitle > strong').text(title);
				$('#category_id').val(id);
		}
	});

	/* Initialization tree categories */
	treeCat.init();

	intelli.display('#tree', 'show');

	function changeType()
	{
		var type = $('#typeSelecter option:selected').val();

		$('#imageTitle, #imageParams, #imageUrl, #bannerurl, #htmlsettings, #mediasize, #bannerThumbnail, #uploadcontainer, #textcontainer, #planetextcontainer, #imageFit').hide('slow');

		switch(type)
		{
			case "html":
				$('#htmlsettings, #textcontainer').show('slow');
			break;

			case "text":
				$('#planetextcontainer, #bannerurl').show('slow');
			break;

			case "local":
				$('#bannerThumbnail, #mediasize, #imageTitle, #uploadcontainer, #bannerurl').show('slow');
				if ($('#setown').attr("checked"))
				{
					$('#imageParams').show('slow');
				}
				if ($('#imageKeepRatio').attr("checked"))
				{
					$('#imageFit').show('slow');
				}
			break;

			case "remote":
				$('#imageUrl, #mediasize, #imageTitle, #bannerurl').show('slow');
				if ($('#setown').attr("checked"))	
				{
					$('#imageParams').show('slow');
				}
				if ($('#imageKeepRatio').attr("checked"))
				{
					$('#imageFit').show('slow');
				}
			break;

			case "flash":
				$('#uploadcontainer, #bannerThumbnail, #mediasize').show('slow');
			break;
		}
	}
	$("#typeSelecter").change(function(){changeType()});
	changeType();

	function getTarget(clear)
	{
		var type = $("#banner_target option:selected").val();

		if('other' == type)
		{
			if (clear) $('#settarget input').val('');
			$('#settarget').show();
		}
		else
		{
			$('#settarget').hide();
			$('#settarget input').val(type);
		}
	}
	$("#banner_target").change(function(){getTarget(true)});

	getTarget(false);

	function imageFit()
	{
		if ($('#file').val().substr(-3) != 'swf')
		{
			$('#imageFit').slideToggle();
		}else{
			$('#imageFit').hide("slow");
		}
	}
	$("#imageKeepRatio").click(function(){imageFit()});
});