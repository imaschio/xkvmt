intelli.queue = function()
{
	var vUrl = 'controller.php?plugin=mailer&file=manage-mailer';

	return {
		oGrid: null,
		vUrl: vUrl,
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
			{name: 'subject', mapping: 'subject'},
			{name: 'email', mapping: 'email'},
			{name: 'date', mapping: 'date'},
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
			header: intelli.admin.lang.subject,
			dataIndex: 'subject',
			sortable: true,
			width: 250
		},{
			header: intelli.admin.lang.email,
			dataIndex: 'email',
			sortable: true,
			width: 250
		},{
			header: intelli.admin.lang.date,
			dataIndex: 'date',
			sortable: true,
			width: 110
		},{
			header: "",
			width: 40,
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

		this.dataStore.setDefaultSort('subject');
	},
	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
            minHeight: 100
		});

		this.title = intelli.admin.lang.mail_queue;
		this.renderTo = 'box_queue';

		this.setupBaseParams();
		this.setupPagingPanel();
		this.setupGrid();

		this.setRenderers();
		this.setEvents();

		this.grid.autoExpandColumn = 2;

		this.loadData();
	},
	setupPagingPanel: function()
	{
		/*
		 * Bottom toolbar
		 */
		this.bottomToolbar = new Ext.PagingToolbar(
		{
			store: this.dataStore,
			pageSize: 10,
			displayInfo: true,
			plugins: new Ext.ux.ProgressBarPager(),
			items: [
				'-',
				intelli.admin.lang.items_per_page + ':',
				{
					xtype: 'bettercombo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					width: 80,
					store: intelli.queue.pagingStore,
					value: '10',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'pgnPnl'
				},
				'-',
				{
					text: intelli.admin.lang.remove,
					id: 'removeBtn',
					iconCls: 'remove-grid-ico',
					disabled: true,
					handler: function()
					{
						var rows = intelli.queue.oGrid.grid.getSelectionModel().getSelections();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].json.id;
						}

						Ext.Msg.show(
						{
							title: intelli.admin.lang.confirm,
							msg: intelli.admin.lang.are_you_sure_to_delete_selected_subscribe,
							buttons: Ext.Msg.YESNO,
							icon: Ext.Msg.QUESTION,
							fn: function(btn)
							{
								if('yes' == btn)
								{
									Ext.Ajax.request(
									{
										url: intelli.queue.vUrl,
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

											intelli.queue.oGrid.grid.getStore().reload();

											Ext.getCmp('goBtn').disable();
											Ext.getCmp('removeBtn').disable();
										}
									});
								}
							}
						});
					}
				}
			]
		});
	},
	setupBaseParams: function()
	{
		var params = new Object();
		var status = intelli.urlVal('status');
		var search = intelli.urlVal('quick_search');

		params.action = 'get';

		if(null != status)
		{
			params.status = status;
		}

		if(null != search)
		{
			if(intelli.is_int(search))
			{
				params.id = search;
			}
			else if(intelli.is_email(search))
			{
				params.email = search;
			}
			else
			{
				params.realname = search;
			}
		}

		this.dataStore.baseParams = params;
	},
	setRenderers: function()
	{
		/* add remove link */
		this.columnModel.setRenderer(4, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.remove +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico.png" />';
		});
	},
	setEvents: function()
	{
		/*
		 * Events
		 */


		/* Edit and remove click */
		intelli.queue.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_selected_subscribe,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.queue.vUrl,
								method: 'POST',
								params:
								{
									action: 'remove',
									'ids[]': record.id
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

									Ext.getCmp('goBtn').disable();
									Ext.getCmp('removeBtn').disable();

									intelli.queue.oGrid.grid.getStore().reload();
								}
							});
						}
					}
				});
			}
		});

		/* Enable disable functionality buttons */
		intelli.queue.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('removeBtn').enable();
		});

		intelli.queue.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('goBtn').disable();
				Ext.getCmp('removeBtn').disable();
			}
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.queue.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.queue.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.queue.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if(Ext.get('box_queue'))
	{
		intelli.queue.oGrid = new intelli.exGrid({url: intelli.queue.vUrl});

		/* Initialization grid */
		intelli.queue.oGrid.init();
	}
	if(Ext.get('tree'))
	{
		var tree = new Ext.tree.TreePanel({
			el: 'tree',
			animate: true, 
			autoScroll: true,
			width: 'auto',
			height: 'auto',
			border: true,
			loader: new Ext.tree.TreeLoader(
			{
				dataUrl: 'get-categories.php',
				requestMethod: 'GET'
			}),
			containerScroll: true,
			listeners: {'checkchange': function(node, checked){
				var action;
				
                if(checked){
                    action = "add_to_textarea";
                }else{
                    action = "rem_fr_textarea";
                }
				$.get("controller.php?plugin=mailer&file=manage-mailer", {id: node.id, action: 'get_recip', textarea_action: action, recipients: $("#individual").val()},
				function(data)
				{
					$("#individual").val(data);
				});
            }
        }
		});
		new Ext.tree.TreeSorter(tree, {folderSort: true});
		 
		// set the root node
		var root = new Ext.tree.AsyncTreeNode({
			text: 'ROOT', 
			id: '0'
		});
		tree.setRootNode(root);
			
		tree.render();
	}
});

$("textarea.ckeditor_textarea").each(function()
{
	if(!CKEDITOR.instances[$(this).attr("id")])
	{
		intelli.ckeditor($(this).attr("id"), {toolbar: 'User'});
	}
});