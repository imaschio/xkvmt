var dmozex = function()
{
}

dmozex.prototype =
{
	init : function()
	{
		var dmoz_sess = $("#dmoz_session").val();
		if (dmoz_sess == '')
			this.getMain();
		else
			this.getBrowser(dmoz_sess);
	},
	getMain : function()
	{
		$.get('controller.php?plugin=dmozex', {
			action : 'getMain'
		}, function(data)
		{
			var response = data.split('||');
	
			$('#bBrowser > div > div.box-content').html(response[0]);

			$('#bBrowser').fadeIn('fast');			
			if ('null' != response[1])
			{
				$('#bCategories > div > div.box-content').html(
						response[1]);
				$('#bCategories').fadeIn('fast');
			}
		});
	},
	getBrowser : function(category)
	{
		var notifBox = $('div.msg');
		if ('block' == notifBox.css('display'))
		{
			notifBox.fadeOut('slow');
		}
		if ('/' == category)
		{
			this.getMain();

			return;
		}
		$.get('controller.php?plugin=dmozex', {
					action : 'getBrowser',
					category : category
				}, function(data)

				{
					var response = data.split('||');
					$('#bBrowser > div > div.box-content').empty();
					// breadcrumb
						if ('null' != response[0])
						{
							$('#bBrowser > div > div.box-content').append(
									response[0]);
						}
						// categories browse
						if ('null' != response[1])
						{
							$('#bBrowser > div > div.box-content').append(
									response[1]);
						}
						if ('null' != response[0] || 'null' != response[1])
						{
							$('#bBrowser').fadeIn('fast');
						}
						// categories
						if ('null' != response[2])
						{
							$('#bCategories > div > div.box-content').html(
									response[2]);
							$('#bCategories').fadeIn('fast');
						}
						else
						{
							if ('none' != $('#bCategories').css('display'))
							{
								$('#bCategories').fadeOut('fast');
							}
						}
						// listings
						if ('null' != response[3])
						{
							$('#bListings > div > div.box-content').html(
									response[3]);
							$('#bListings').fadeIn('fast');
						}
						else
						{
							if ('none' != $('#bListings').css('display'))
							{
								$('#bListings').fadeOut('fast');
							}
						}
					});
	},
	selectingAll : function(idForm, check)
	{
		var resultForm = document.getElementById(idForm);

		for ( var i = 0; i < resultForm.elements.length; i++)
		{
			if (resultForm.elements[i].type == 'checkbox'
					&& resultForm.elements[i].name != 'one_import')
			{
				resultForm.elements[i].checked = check;
			}
		}
	}
}

var dmozex = new dmozex();

$(document).ready(function()
{
	dmozex.init();
});

function goBrowser(category)
{
	dmozex.getBrowser(category);
}

function checkAll(idForm)
{
	dmozex.selectingAll(idForm, true);
}

function uncheckAll(idForm)
{
	dmozex.selectingAll(idForm, false);
}

function showTree()
{
	if (!dmozex.categoriesTree)
	{
		dmozex.categoriesTree = new Ext.tree.TreePanel( {
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
		new Ext.tree.TreeSorter(dmozex.categoriesTree, {
			folderSort : true
		});

		// set the root node
		var root = new Ext.tree.AsyncTreeNode( {
			text : 'ROOT',
			id : '0'
		});

		dmozex.categoriesTree.setRootNode(root);

		root.expand();
	}

	if (!dmozex.categoriesWin)
	{
		dmozex.categoriesWin = new Ext.Window({
					title : 'Tree',
					width : 400,
					height : 450,
					autoScroll : true,
					modal : true,
					closeAction : 'hide',
					items : [ dmozex.categoriesTree ],
					buttons : [
							{
								text : 'Ok',
								handler : function()
								{
									var category = dmozex.categoriesTree
											.getSelectionModel()
											.getSelectedNode();
									$("#category_title > a")
											.html(
													'<b>' + category.attributes.text + '<\/b>');

									$("#category_id").val(category.id);
									dmozex.categoriesWin.hide();
								}
							}, {
								text : 'Cancel',
								handler : function()
								{
									dmozex.categoriesWin.hide();
								}
							} ]

				});

	}

	dmozex.categoriesWin.show();

	return false;

}

function showTree1()

{

	if (!dmozex.categoriesTree2)

	{

		dmozex.categoriesTree2 = new Ext.tree.TreePanel( {

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

		new Ext.tree.TreeSorter(dmozex.categoriesTree2, {
			folderSort : true
		});

		// set the root node

		var root = new Ext.tree.AsyncTreeNode( {

			text : intelli.admin.lang.root,

			id : '0'

		});

		dmozex.categoriesTree2.setRootNode(root);

		root.expand();

	}

	if (!dmozex.categoriesWin2)

	{

		dmozex.categoriesWin2 = new Ext.Window(

				{

					title : intelli.admin.lang.tree,

					width : 400,

					height : 450,

					autoScroll : true,

					modal : true,

					closeAction : 'hide',

					items : [ dmozex.categoriesTree2 ],

					buttons : [

							{

								text : intelli.admin.lang.ok,

								handler : function()

								{

									var category = dmozex.categoriesTree2
											.getSelectionModel()
											.getSelectedNode();

									$("#category_title1 > a")
											.html(
													'<b>' + category.attributes.text + '<\/b>');

									$("#parent_id").val(category.id);

									dmozex.categoriesWin2.hide();

								}

							}, {

								text : intelli.admin.lang.cancel,

								handler : function()

								{

									dmozex.categoriesWin2.hide();

								}

							} ]

				});

	}

	dmozex.categoriesWin2.show();

	return false;

}