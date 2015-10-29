$(function()
{
	var treeCat = new intelli.tree(
	{
		id: 'tree',
		type: 'radio',
		state: '',
		hideRoot: true,
		expandableLocked: true,
		menuType: intelli.config.categories_tree_type,
		callback: function()
		{
			var idCategory = $(this).val();
			var titleCategory = $(this).attr('title');

			/* hiding any notification boxes */
			intelli.display($(".error"), 'hide');

			$('#category_id').val(idCategory);
			$('#category_title').val(titleCategory);

			$('#categoryTitle span').text(titleCategory);
		},
		dropDownCallback: function(id, title)
		{
			$('#category_id').val(id);
			$('#category_title').val(title);
			$('#categoryTitle span').text(title);
		}
	});

	treeCat.init();
});