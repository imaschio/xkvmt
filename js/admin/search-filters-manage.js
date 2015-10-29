var Filter, Fields;

var buildTrees = function()
{
	 Filter = new Ext.tree.TreePanel({
		renderTo: 'div_filter',
		title: _t('filter'),
		id: 'filter',
		height: 200,
		width: 300,
		autoScroll: true,
		enableDD: true,
		root: filter,
		contextMenu: new Ext.menu.Menu({
			items: [{
				id: 'edit-menu',
				iconCls: 'silk-edit',
				text: _t('edit_filter'),
			}, {
				id: 'delete-node',
				text: _t('delete'),
				handler: function(){
					var delNode = Filter.selModel.selNode;
					delNode.parentNode && delNode.parentNode.removeChild(delNode);
				},
				iconCls: 'silk-delete'
			}]
		}),
		listeners: {
			'beforenodedrop' : function(e)
			{
				if(!e.dropNode.parentNode.parentNode && e.dropNode.parentNode.attributes.id == 'fields') return false;
				if (e.dropNode.parentNode.parentNode && e.dropNode.parentNode.parentNode.attributes.id == 'fields')
				{
					var add = true;
					jQuery.each(Filter.root.childNodes, function(index, item){
						if(item.id == e.dropNode.id) add = false;
					});
					if(!add)return false;
					e.dropNode = new Ext.tree.TreeNode({
						id: e.dropNode.id,
						leaf: false,
						text: e.dropNode.text
					});
					e.target.expand();
					if (!e.target.leaf) {
						e.target.appendChild(e.dropNode);
					}
					else {
						e.target.parentNode.appendChild(e.dropNode);
					}
				}
			},
			'nodedrop' : function(e){
				e.target.leaf = false;
				e.target.expand(); 
			},
			'checkchange': function(node, checked){
				if(checked){
					node.getUI().addClass('complete');
				}else{
					node.getUI().removeClass('complete');
				}
			},
			contextmenu: function(node, e) {
				node.select();
				var c = node.getOwnerTree().contextMenu;
				c.contextNode = node;
				c.showAt(e.getXY());
			}

		}
	});
	Filter.getRootNode().expand();

	// build Fields tree
	Fields = new Ext.tree.TreePanel({
		renderTo: 'div_fields',
		title: _t('listing_fields'),
		height: 200,
		width: 300,
		autoScroll: true,
		enableDD: true,
		listeners: {
			'beforenodedrop' : function(){
				return false;
			}
		},
		root: fields
	});

	Fields.getRootNode().expand();
};

intelli.searchFilters = function()
{

}

Ext.onReady(function()
{
	buildTrees();

	$('#save').click(function()
	{
		$('#filter_content').val(new Ext.tree.JsonTreeSerializer(Filter).toString());
		$(this).closest('form').submit();

		return false;

	});

	$('#title').keyup(function()
	{
		Filter.root.setText($(this).val());
	}).blur(function()
	{
		if ('' == $(this).val())
		{
			Filter.root.setText('New Menu');
		}
	});

	$('#delete_field').click(function()
	{
		var delNode = Filter.selModel.selNode;

		if(delNode)
		{
			delNode.parentNode && delNode.parentNode.removeChild(delNode);
		}
	});

	$('#add_field').click(function()
	{
		var target = (Filter.selModel.selNode ? Filter.selModel.selNode : Filter.root);

		if(Fields.selModel.selNode) 
		{
			var selNode = Fields.selModel.selNode;
			var add = true;
			jQuery.each(Filter.root.childNodes, function(index, item){
				if(item.id == selNode.id) add = false;
			});
			if(selNode.leaf && add)
			{
				var dropNode = new Ext.tree.TreeNode({
					id: selNode.id,
					leaf: true,
					text: selNode.text
				});
				if (!target.leaf) {
					target.appendChild(dropNode);					
				}
				else {
					target.parentNode.appendChild(dropNode);
				}
				Filter.root.expand();
			}
		}
	});

	$("#change_category").click(function()
	{
		if(!intelli.searchFilters.categoriesTree)
		{
			intelli.searchFilters.categoriesTree = new Ext.tree.TreePanel({
				animate: true, 
				width: 'auto',
				height: 'auto',
				border: false,
				plain: true,
				loader: new Ext.tree.TreeLoader(
				{
					dataUrl: 'get-categories.php',
					baseParams: {single: 1},
					requestMethod: 'GET'
				})
			});
		
			// add a tree sorter in folder mode
			new Ext.tree.TreeSorter(intelli.searchFilters.categoriesTree, {folderSort: false});
			 
			// set the root node
			var root = new Ext.tree.AsyncTreeNode({
				text: 'ROOT', 
				id: '0'
			});
			intelli.searchFilters.categoriesTree.setRootNode(root);
				
			root.expand();
		}

		if(!intelli.searchFilters.categoriesWin)
		{
			intelli.searchFilters.categoriesWin = new Ext.Window(
			{
				title: intelli.admin.lang.tree,
				width : 400,
				height : 450,
				autoScroll: true,
				modal: true,
				closeAction : 'hide',
				items: [intelli.searchFilters.categoriesTree],
				buttons: [
				{
					text : intelli.admin.lang.ok,
					handler: function()
					{
						var category = intelli.searchFilters.categoriesTree.getSelectionModel().getSelectedNode();
						var category_url = intelli.config.esyn_url + 'controller.php?file=browse&id=' + category.id;

						$("#category_title_container a[href!='#']").text(category.attributes.text).attr("href", category_url);
						$("#category_id").val(category.id);

						intelli.searchFilters.categoriesWin.hide();
					}
				},{
					text : intelli.admin.lang.cancel,
					handler : function()
					{
						intelli.searchFilters.categoriesWin.hide();
					}
				}]
			});
		}

		intelli.searchFilters.categoriesWin.show();

		return false;
	});
});
