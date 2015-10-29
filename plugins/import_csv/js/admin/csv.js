var import_csv = function()
{
}

function actionEl(el, action)
{
	el.disabled = action;
}
function selectingAll(check)
{
	var resultForm = document.getElementById('result');

	for(var i = 0; i < resultForm.elements.length; i++)
	{
		if(resultForm.elements[i].type == 'checkbox')
		{
			resultForm.elements[i].checked = check;
		}
	}	
}
function checkAll()
{
	selectingAll(true);
}

function uncheckAll()
{
	selectingAll(false);
}

var import_csv = new import_csv();

function showTree()
{
	if (!import_csv.categoriesTree)
	{
		import_csv.categoriesTree = new Ext.tree.TreePanel( {
			animate : true,
			autoScroll : true,
			width : 'auto',
			height : 'auto',
			border : false,
			plain : true,
			loader : new Ext.tree.TreeLoader(
			{
				dataUrl : 'get-categories.php',
				baseParams : {
					single : 1
				},
				requestMethod : 'GET'
			}),
			containerScroll : true
		});

		// add a tree sorter in folder mode
		new Ext.tree.TreeSorter(import_csv.categoriesTree, {
			folderSort : true
		});

		// set the root node
		var root = new Ext.tree.AsyncTreeNode( {
			text : 'ROOT',
			id : '0'
		});

		import_csv.categoriesTree.setRootNode(root);

		root.expand();
	}

	if (!import_csv.categoriesWin)
	{
		import_csv.categoriesWin = new Ext.Window({
			title : 'Tree',
			width : 400,
			height : 450,
			autoScroll : true,
			modal : true,
			closeAction : 'hide',
			items : [ import_csv.categoriesTree ],
			buttons : [
			{
				text : 'Ok',
				handler : function()
				{
					var category = import_csv.categoriesTree
							.getSelectionModel()
							.getSelectedNode();
					$("#category_title > a")
							.html(
									'<b>' + category.attributes.text + '<\/b>');

					$("#category_id").val(category.id);
					import_csv.categoriesWin.hide();
				}
			}, {
				text : 'Cancel',
				handler : function()
				{
					import_csv.categoriesWin.hide();
				}
			} ]
		});
	}

	import_csv.categoriesWin.show();

	return false;
}