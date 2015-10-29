intelli.comments = function()
{
	var vUrl = 'controller.php?plugin=comments';

	return {
		oGrid: null,
		vUrl: vUrl,
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

intelli.exGModel = Ext.extend(intelli.gmodel,
{
	constructor: function(config)
	{
		intelli.exGModel.superclass.constructor.apply(this, arguments);
	},
	setupReader: function()
	{
		this.record = Ext.data.Record.create([
			{name: 'body', mapping: 'body'},
			{name: 'item_title', mapping: 'item_title'},
			{name: 'author', mapping: 'author'},
            {name: 'rating', mapping: 'rating'},
			{name: 'status', mapping: 'status'},
			{name: 'date', mapping: 'date'},
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
			header: intelli.admin.lang.body, 
			dataIndex: 'body', 
			sortable: true, 
			width: 250,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.item,
			dataIndex: 'item_title',
			sortable: true,
			renderer: function(value, p, record)
			{
                var string = '';

                if (record.json.item == 'listings')
                {
                    string = String.format('<b>' + intelli.admin.lang.listing + ': <a href="controller.php?file=suggest-listing&do=edit&id={0}" target="_blank">{1}</a></b>', record.json.item_id, value);
                }
                else if (record.json.item == 'articles')
                {
                    string = String.format('<b>' + intelli.admin.lang.article + ': <a href="controller.php?plugin=premium_articles&do=edit&id={0}" target="_blank">{1}</a></b>', record.json.item_id, value);
                }
                else if (record.json.item == 'news')
                {
                    string = String.format('<b>' + intelli.admin.lang.news + ': <a href="controller.php?plugin=news&do=edit&id={0}" target="_blank">{1}</a></b>', record.json.item_id, value);
                }

				return string;
			},
			width: 250
		},{
			header: intelli.admin.lang.author, 
			dataIndex: 'author',
			sortable: true,
			renderer: function(value, p, record)
			{
                var string = '';

                if (record.json.account_id > 0)
                {
                    string = String.format('<b><a href="controller.php?file=accounts&do=edit&id={0}" target="_blank">{1}</a></b>', record.json.account_id, value);
                }
                else
                {
                    string = value;
                }

				return string;
			},
			width: 140
		},{
            header: intelli.admin.lang.rating,
			dataIndex: 'rating',
			sortable: true,
			width: 40
		},{
			header: intelli.admin.lang.status, 
			dataIndex: 'status',
			width: 50,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				editable: false,
				lazyRender: true,
				store: intelli.comments.statusesStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			})
		},{
			header: intelli.admin.lang.date, 
			dataIndex: 'date',
            sortable: true,
			width: 110
		},{
			header: "",
			width: 30,
			dataIndex: 'edit',
			hideable: false,
			menuDisabled: true,
			align: 'center'
		},{
			header: "",
			width: 30,
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
	},
	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
            minHeight: 100
		});

		this.title = intelli.admin.lang.manage_comments;
		this.renderTo = 'box_comments';

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
			store: intelli.comments.pagingStore,
			value: '10',
			displayField: 'display',
			valueField: 'value',
			mode: 'local',
			id: 'pgnPnl'
		});

		var removeButton = new Ext.Toolbar.Button({
			text: intelli.admin.lang['remove'],
			id: 'removeBtn',
			iconCls: 'remove-grid-ico',
			disabled: true
		});

		var changeStatus = new Ext.form.ComboBox({
			typeAhead: true,
			allowDomMove: false,
			triggerAction: 'all',
			editable: false,
			lazyRender: true,
			store: intelli.comments.statusesStore,
			value: 'active',
			displayField: 'value',
			valueField: 'display',
			mode: 'local',
			disabled: true,
			id: 'statusCmb'
		});

		var goButton = new Ext.Toolbar.Button({
			text: intelli.admin.lang['go'],
			disabled: true,
			iconCls: 'go-grid-ico',
			id: 'goBtn'
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
					store: intelli.comments.pagingStore,
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
					store: intelli.comments.statusesStore,
					value: 'active',
					displayField: 'value',
					valueField: 'display',
					mode: 'local',
					disabled: true,
					id: 'statusCmb'
				},{
					text: intelli.admin.lang['go'],
					disabled: true,
					iconCls: 'go-grid-ico',
					id: 'goBtn'
				},
				'-',
				{
					text: intelli.admin.lang['remove'],
					id: 'removeBtn',
					iconCls: 'remove-grid-ico',
					disabled: true
				},
				'-'
			]
		});
	},
	setupBaseParams: function()
	{
		this.dataStore.baseParams = {action: 'get'};
	},
	setRenderers: function()
	{
		/* change background color for status field */
		this.columnModel.setRenderer(5, function(value, metadata)
		{
			metadata.css = value;

			return value;
		});

		/* add edit link */
		this.columnModel.setRenderer(7, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
		});

		/* add remove link */
		this.columnModel.setRenderer(8, function(value, metadata)
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
		intelli.comments.oGrid.grid.on('afteredit', function(editEvent)
		{
			var value = ('date' == editEvent.field) ? editEvent.value.format("Y-m-d") : editEvent.value;

			Ext.Ajax.request(
			{
				waitMsg: intelli.admin.lang.saving_changes,
				url: intelli.comments.vUrl,
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
		intelli.comments.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				intelli.comments.oGrid.saveGridState();

				window.location = 'controller.php?plugin=comments&do=edit&id='+ record.json.id;
			}

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_comment,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								waitMsg: intelli.admin.lang.saving_changes,
								url: intelli.comments.vUrl,
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
		});

		/* Enable disable functionality buttons */
		intelli.comments.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('removeBtn').enable();
		});

		intelli.comments.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
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
			var rows = intelli.comments.oGrid.grid.getSelectionModel().getSelections();
			var status = Ext.getCmp('statusCmb').getValue();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Ajax.request(
			{
				waitMsg: intelli.admin.lang.saving_changes,
				url: intelli.comments.vUrl,
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
					intelli.comments.oGrid.grid.getStore().reload();

					var response = Ext.decode(data.responseText);
					var type = response.error ? 'error' : 'notif';
						
					intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});
				}
			});
		});

		/* remove button action */
		Ext.getCmp('removeBtn').on('click', function()
		{
			var rows = intelli.comments.oGrid.grid.getSelectionModel().getSelections();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Msg.show(
			{
				title: intelli.admin.lang.confirm,
				msg: (ids.length > 1) ? intelli.admin.lang.are_you_sure_to_delete_selected_comments : intelli.admin.lang.are_you_sure_to_delete_this_account,
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				fn: function(btn)
				{
					if('yes' == btn)
					{
						Ext.Ajax.request(
						{
							waitMsg: intelli.admin.lang.saving_changes,
							url: intelli.comments.vUrl,
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

								intelli.comments.oGrid.grid.getStore().reload();
							}
						});
					}
				}
			});
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.comments.oGrid.grid.getStore().baseParams.limit = new_value;
			intelli.comments.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.comments.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if(Ext.get('box_comments'))
	{
		intelli.comments.oGrid = new intelli.exGrid({url: intelli.comments.vUrl});

		/* Initialization grid */
		intelli.comments.oGrid.init();
	}


    if($("#commentbody").length > 0 && !CKEDITOR.instances["commentbody"])
    {
        intelli.ckeditor("commentbody", {toolbar: 'User'});
    }

});