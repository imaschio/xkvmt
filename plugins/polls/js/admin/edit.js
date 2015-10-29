Ext.onReady(function()
{
	if(Ext.get('tree'))
	{
		var checkedCategories = Ext.get("categories").getValue();

		var tree = new Ext.tree.TreePanel({
			el: 'tree',
			animate: true,
			autoScroll: true,
			width: 'auto',
			height: 'auto',
			border: true,
			loader: new Ext.tree.TreeLoader(
			{
				dataUrl: 'get-categories.php?checked=' + checkedCategories,
				requestMethod: 'GET'
			}),
			containerScroll: true
		});

		// add a tree sorter in folder mode
		new Ext.tree.TreeSorter(tree, {folderSort: true});

		// set the root node
		var root = new Ext.tree.AsyncTreeNode({
			text: 'ROOT',
			id: '0',
			checked: intelli.inArray(0, checkedCategories.split('|')) ? true : false
		});
		tree.setRootNode(root);

		root.expand();

		// render the tree
		tree.render();

		//root.expand();
		root.on('checkchange', function(node, checked)
		{
			var checkedNodes = tree.getChecked();
			var tempCats = new Array();

			if(checkedNodes)
			{
				for(var i = 0; i < checkedNodes.length; i++)
				{
					tempCats[i] = checkedNodes[i].id;
				}

				Ext.get("categories").dom.value = tempCats.join('|');
			}
		});
		tree.on('checkchange', function(node, checked)
		{
			var checkedNodes = tree.getChecked();
			var tempCats = new Array();

			if(checkedNodes)
			{
				for(var i = 0; i < checkedNodes.length; i++)
				{
					tempCats[i] = checkedNodes[i].id;
				}

				Ext.get("categories").dom.value = tempCats.join('|');
			}
		});
	}

	if(Ext.get('expires'))
	{
		new Ext.form.DateField(
		{
			allowBlank: false,
			format: 'Y-m-d H:i:s',
			applyTo: 'expires'
		});
	}

	$('#add_item').click(function()
	{
		var template = $("#value_item");
		var clone = template.clone(true);
		clone.css('display', 'block').removeAttr("id");

		$("#before").before(clone);

		return false;
	});

	$("#remove_node").click(function()
	{
		$(this).parent().remove();

		return false;
	});
});