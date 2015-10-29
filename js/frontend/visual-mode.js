$(function ()
{
	$('.js-groupWrapper').sortable(
	{
		appendTo: document.body,
		containment: "document",
		connectWith: '.js-groupWrapper',
		forceHelperSize: true,
		items: "> div.box",
		opacity: 0.9,
		placeholder: "sortable-placeholder",
		scrollSensitivity: 5,
		tolerance: "pointer",
		change: function(event, ui)
		{
			$(this).sortable( "option", "axis", "y" );
		},
		out: function(event, ui)
		{
			$(this).sortable("option", "axis", "");
		},
		update: function (event, ui)
		{
			var _this = $(this);
			var position = _this.data('position');
			var blocks = _this.sortable( "serialize", { key: "blocks[]" } );

			$.get("ajax.php?action=blocks&position=" + position + '&' + blocks, function(data)
			{
				_this.animate({ borderColor: '#FFFFFF', borderWidth: "3px", backgroundColor: '#FFFF99'}, 1000, function()
				{
					_this.animate({backgroundColor: 'lightgreen'}, 1000);
				});
			});
		}
	}).each(function()
	{
		var _this = $(this);
		_this.prepend('<div class="manage-block-name">&quot;<b>' + _this.data('position') + '</b>&quot; blocks' + '</div>');
	});
});