intelli.slider = function()
{
	var vUrl = 'controller.php?plugin=slider';

	return {
		oGrid: null,
		vUrl: vUrl,
		statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['all', intelli.admin.lang._status_],
				['active', intelli.admin.lang.active],
				['inactive', intelli.admin.lang.inactive]
			]
		}),
		statusesStoreFilter: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['all', intelli.admin.lang._status_],
				['active', intelli.admin.lang.active],
				['inactive', intelli.admin.lang.inactive]
			]
		}),
		pagingStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['10', '10'],['20', '20'],['30', '30'],['40', '40'],['50', '50']]
		})
	};
}();

intelli.exGModel = Ext.extend(intelli.gmodel,
{
	constructor: function(config)
	{
		intelli.exGModel.superclass.constructor.apply(this, arguments);
	},
	setupReader: function()
	{
		this.record = Ext.data.Record.create([
			{name: 'title', mapping: 'title'},
			{name: 'parents', mapping: 'parents'},
			{name: 'order', mapping: 'order'},
			{name: 'status', mapping: 'status'},
			{name: 'edit', mapping: 'edit'},
			{name: 'remove', mapping: 'remove'}
		]);

		this.reader = new Ext.data.JsonReader({
			root: 'data',
			totalProperty: 'total',
			id: 'id'
			}, this.record
		);

		return this.reader;
	},
	setupColumnModel: function()
	{
		this.columnModel = new Ext.grid.ColumnModel([
		this.checkColumn,
		{
			header: intelli.admin.lang.title, 
			dataIndex: 'title', 
			sortable: true, 
			width: 250,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.category, 
			dataIndex: 'parents',
			sortable: true,
			width: 300,
			renderer: function(value, p, record)
			{
				return String.format('<b><a href="controller.php?file=browse&id={0}">{1}</a></b>', record.json.category_id, value);
			}
		},{
			header: intelli.admin.lang.order, 
			dataIndex: 'order',
			sortable: true,
			width: 60,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.status, 
			dataIndex: 'status',
			sortable: true,
			width: 100,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				editable: false,
				lazyRender: true,
				store: intelli.slider.statusesStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			})
		},{
			header: "",
			width: 60,
			dataIndex: 'edit',
			hideable: false,
			menuDisabled: true,
			align: 'center'
		},{
			header: "",
			width: 60,
			dataIndex: 'remove',
			hideable: false,
			menuDisabled: true,
			align: 'center'
		}]);

		return this.columnModel;
	}
});

intelli.exGrid = Ext.extend(intelli.grid,
{
	model: null,
	constructor: function(config)
	{
		intelli.exGrid.superclass.constructor.apply(this, arguments);

		this.model = new intelli.exGModel({url: config.url});

		this.dataStore = this.model.setupDataStore();
		this.columnModel = this.model.setupColumnModel();
		this.selectionModel = this.model.setupSelectionModel();

		this.dataStore.setDefaultSort('title', 'ASC');
	},
	init: function()
	{
		this.plugins = [new Ext.ux.PanelResizer({
			minHeight: 100
		})];

		this.title = intelli.admin.lang.manage_slide;
		this.renderTo = 'box_slider';

		this.setupBaseParams();
		this.setupPagingPanel();
		this.setupGrid();

		this.setRenderers();
		this.setEvents();

		this.grid.autoExpandColumn = 1;

		this.loadData();
	},
	setupPagingPanel: function()
	{
		var pagingPanel = new Ext.form.ComboBox(
		{
			typeAhead: true,
			allowDomMove: false,
			triggerAction: 'all',
			editable: false,
			lazyRender: true,
			width: 80,
			store: intelli.slider.pagingStore,
			value: '10',
			displayField: 'display',
			valueField: 'value',
			mode: 'local',
			id: 'pgnPnl'
		});

		this.topToolbar = new Ext.Toolbar(
		{
			items:[
				intelli.admin.lang.keywords + ':',
				{
					xtype: 'textfield',
					id: 'keywordsFilter',
					emptyText: intelli.admin.lang.keywords
				},
				' ',
				intelli.admin.lang.status + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					store: intelli.slider.statusesStore,
					value: 'All',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'stsFilter'
				},
				' ',
				{
					text: intelli.admin.lang.search,
					iconCls: 'search-grid-ico',
					id: 'fltBtn',
					handler: function()
					{
						var status = Ext.getCmp('stsFilter').getValue();
						var word = Ext.getCmp('keywordsFilter').getValue();

						if('' != status || '' != type || '' != state)
						{
							intelli.slider.oGrid.dataStore.baseParams = 
							{
								action: 'get',
								status: status,
								q: word
							};

							intelli.slider.oGrid.dataStore.reload();
						}
					}
				},
				'-',
				{
					text: intelli.admin.lang.reset,
					id: 'resetBtn',
					handler: function()
					{
						Ext.getCmp('stsFilter').setValue('All');

						intelli.slider.oGrid.dataStore.baseParams = 
						{
							action: 'get',
							status: ''
						};

						intelli.slider.oGrid.dataStore.reload();
					}
				}
			]
		});

		this.bottomToolbar = new Ext.PagingToolbar(
		{
			store: this.dataStore,
			pageSize: 10,
			displayInfo: true,
			plugins: new Ext.ux.ProgressBarPager(),
			items: [
				'-',
				intelli.admin.lang.items_per_page,
				{
					xtype: 'bettercombo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					width: 80,
					store: intelli.slider.pagingStore,
					value: '10',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'pgnPnl'
				},
				'-',
				intelli.admin.lang.status,
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					store: intelli.slider.statusesStore,
					value: 'active',
					displayField: 'value',
					valueField: 'display',
					mode: 'local',
					disabled: true,
					id: 'statusCmb'
				},{
					text: 'Do!',
					disabled: true,
					iconCls: 'go-grid-ico',
					id: 'goBtn'
				},
				'-',
				{
					text: intelli.admin.lang.remove,
					id: 'removeBtn',
					iconCls: 'remove-grid-ico',
					disabled: true
				}
			]
		});
	},
	setupBaseParams: function()
	{
		var q = intelli.urlVal('q');
		this.dataStore.baseParams = {action: 'get'};

		if (q) this.dataStore.baseParams.q = q;
	},
	setRenderers: function()
	{
		/* change background color for status field */
		this.columnModel.setRenderer(4, function(value, metadata)
		{
			metadata.css = value;

			return value;
		});

		/* add edit link */
		this.columnModel.setRenderer(5, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
		});

		/* add remove link */
		this.columnModel.setRenderer(6, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.remove +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico.png" />';
		});
	},
	setEvents: function()
	{
		/* 
		 * Events
		 */

		/* Edit fields */
		intelli.slider.oGrid.grid.on('afteredit', function(editEvent)
		{
			var value = ('date_added' == editEvent.field) ? editEvent.value.format("Y-m-d") : editEvent.value;

			Ext.Ajax.request(
			{
				waitMsg: 'Saving changes...',
				url: intelli.slider.vUrl,
				method: 'POST',
				params:
				{
					action: 'update',
					'ids[]': editEvent.record.id,
					field: editEvent.field,
					value: value
				},
				failure: function()
				{
					Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
				},
				success: function(data)
				{
					editEvent.grid.getStore().reload();

					var response = Ext.decode(data.responseText);
					var type = response.error ? 'error' : 'notif';

					intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});
				}
			});
		});

		/* Edit and remove click */
		intelli.slider.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: 'Confirm',
					msg: intelli.admin.lang.are_you_sure_to_delete_this_slide,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								waitMsg: intelli.admin.lang.saving_changes,
								url: intelli.slider.vUrl,
								method: 'POST',
								params:
								{
									action: 'remove',
									'ids[]': record.json.id
								},
								failure: function()
								{
									Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
								},
								success: function(data)
								{
									grid.getStore().reload();

									var response = Ext.decode(data.responseText);
									var type = response.error ? 'error' : 'notif';

									intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});
								}
							});
						}
					}
				});
			}
			
			if('edit' == fieldName)
			{
				intelli.slider.oGrid.saveGridState();

				window.location = 'controller.php?plugin=slider&do=edit&id='+ record.json.id;
			}
		});

		/* Enable disable functionality buttons */
		intelli.slider.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('removeBtn').enable();
		});

		intelli.slider.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('statusCmb').disable();
				Ext.getCmp('goBtn').disable();
				Ext.getCmp('removeBtn').disable();
			}
		});

		/* Go button action */
		Ext.getCmp('goBtn').on('click', function()
		{
			var rows = intelli.slider.oGrid.grid.getSelectionModel().getSelections();
			var status = Ext.getCmp('statusCmb').getValue();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Ajax.request(
			{
				waitMsg: 'Saving changes...',
				url: intelli.slider.vUrl,
				method: 'POST',
				params:
				{
					action: 'update',
					'ids[]': ids,
					field: 'status',
					value: status
				},
				failure: function()
				{
					Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
				},
				success: function(data)
				{
					intelli.slider.oGrid.grid.getStore().reload();

					var response = Ext.decode(data.responseText);
					var type = response.error ? 'error' : 'notif';

					intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});
				}
			});
		});

		/* remove button action */
		Ext.getCmp('removeBtn').on('click', function()
		{
			var rows = intelli.slider.oGrid.grid.getSelectionModel().getSelections();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Ajax.request(
			{
				waitMsg: 'Saving changes...',
				url: intelli.slider.vUrl,
				method: 'POST',
				params:
				{
					action: 'remove',
					'ids[]': ids
				},
				failure: function()
				{
					Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
				},
				success: function(data)
				{
					var response = Ext.decode(data.responseText);
					var type = response.error ? 'error' : 'notif';

					intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});

					intelli.slider.oGrid.grid.getStore().reload();
				}
			});
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.slider.oGrid.grid.getStore().baseParams.limit = new_value;
			intelli.slider.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.slider.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if(Ext.get('box_slider'))
	{
		intelli.slider.oGrid = new intelli.exGrid({url: intelli.slider.vUrl});

		/* Initialization grid */
		intelli.slider.oGrid.init();
	}

	$("#change_category").click(function()
	{
		if(!intelli.slider.categoriesTree)
		{
			intelli.slider.categoriesTree = new Ext.tree.TreePanel({
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
			new Ext.tree.TreeSorter(intelli.slider.categoriesTree, {folderSort: true});

			// set the root node
			var root = new Ext.tree.AsyncTreeNode({
				text: 'ROOT', 
				id: '0'
			});
			intelli.slider.categoriesTree.setRootNode(root);

			root.expand();
		}

		if(!intelli.slider.categoriesWin)
		{
			intelli.slider.categoriesWin = new Ext.Window(
			{
				title: 'Tree',
				width : 400,
				height : 450,
				modal: true,
				autoScroll : true,
				closeAction : 'hide',
				items: [intelli.slider.categoriesTree],
				buttons: [
				{
					text : 'Ok',
					handler: function()
					{
						var category = intelli.slider.categoriesTree.getSelectionModel().getSelectedNode();
						var category_url = 'controller.php?file=browse&id=' + category.id;

						$("#parent_category_title_container a").text(category.attributes.text).attr("href", category_url);
						$("#category_id").val(category.id);

						intelli.slider.categoriesWin.hide();
					}
				},{
					text : 'Cancel',
					handler : function()
					{
						intelli.slider.categoriesWin.hide();
					}
				}]
			});
		}

		intelli.slider.categoriesWin.show();

		return false;
	});

	$("textarea.ckeditor_textarea").each(function()
	{
		if(!CKEDITOR.instances[$(this).attr("id")])
		{
			intelli.ckeditor($(this).attr("id"), {toolbar: 'Simple'});
		}
	});

	if(intelli.urlVal('q'))
	{
		Ext.getCmp('keywordsFilter').setValue(intelli.urlVal('q'));
	}
});