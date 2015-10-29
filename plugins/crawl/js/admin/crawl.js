intelli.crawl = function()
{
	var vUrl = 'controller.php?plugin=crawl';

	return {
		oGrid: null,
		vUrl: vUrl,
		vTree: null,
		statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['active', 'active'],['inactive', 'inactive']]
		}),
		pagingStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['10', '10'],['20', '20'],['30', '30'],['40', '40'],['50', '50']]
		})
	};
}();

Ext.onReady(function()
{
	$("#category_tree").click(function()
	{
		intelli.crawl.vTree = new Ext.tree.TreePanel({
			animate: true, 
			autoScroll: true,
			width: 'auto',
			height: 'auto',
			border: false,
			plain: true,
			loader: new Ext.tree.TreeLoader(
			{
				dataUrl: 'get-categories.php',
				baseParams: {single: 1},
				requestMethod: 'GET'
			}),
			containerScroll: true
		});
	
		// add a tree sorter in folder mode
		new Ext.tree.TreeSorter(intelli.crawl.vTree, {folderSort: false});
		 
		// set the root node
		var root = new Ext.tree.AsyncTreeNode({
			text: 'ROOT', 
			id: '0'
		});
		intelli.crawl.vTree.setRootNode(root);
			
		root.expand();

		intelli.crawl.vWindow = new Ext.Window(
		{
			title: intelli.admin.lang.tree,
			width : 400,
			height : 450,
			modal: true,
			autoScroll: true,
			closeAction : 'hide',
			items: [intelli.crawl.vTree],
			buttons: [
			{
				text : intelli.admin.lang.ok,
				handler: function()
				{
					var category = intelli.crawl.vTree.getSelectionModel().getSelectedNode();

					$("#category_id").val(category.id);
					$("#category_tree b").text(category.text);
					
					intelli.crawl.vWindow.hide();
				}
			},{
				text : intelli.admin.lang.cancel,
				handler : function()
				{
					intelli.crawl.vWindow.hide();
				}
			}]
		});

		intelli.crawl.vWindow.show();

		return false;
	});
	
	$("#force_encode").click(function()
	{
		$("#force_encode_div").toggle();
	});
});