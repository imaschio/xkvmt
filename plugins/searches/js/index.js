intelli.searches = function()
{
	var vUrl = 'controller.php?plugin=searches';

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

var expander = new Ext.grid.RowExpander({
	tpl : new Ext.Template(
		'{answer}'
	)
});

intelli.exGModel = Ext.extend(intelli.gmodel,
{
	constructor: function(config)
	{
		intelli.exGModel.superclass.constructor.apply(this, arguments);
	},
	setupReader: function()
	{
		this.record = Ext.data.Record.create([
			{name: 'search_word', mapping: 'search_word'},
			{name: 'search_count', mapping: 'search_count'},
			{name: 'search_result', mapping: 'search_result'},
			{name: 'last_search', mapping: 'last_search'},
		//	{name: 'edit', mapping: 'edit'},
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
			header: intelli.admin.lang.search_word, 
			dataIndex: 'search_word',
			sortable: true,
			width: 250,
			renderer: function(value, p, record)
			{
                return String.format('<b><a href="' + intelli.config.esyn_url + 'search.php?what={0}" target="_blank">{0}</a></b>',  value);
			}
		},{
			header: intelli.admin.lang.search_count, 
			dataIndex: 'search_count',
			sortable: true,
			width: 80,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.search_result, 
			dataIndex: 'search_result', 
			sortable: true, 
			width: 150
		},{
			header: intelli.admin.lang.date, 
			dataIndex: 'last_search',
			width: 140
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
	},
	init: function()
	{
		this.plugins = [new Ext.ux.PanelResizer({
            minHeight: 100
		}), expander];

		this.title = intelli.admin.lang.searches;
		this.renderTo = 'box_searches';

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
					store: intelli.searches.pagingStore,
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
					disabled: true
				}
			]
		});
	},
	setupBaseParams: function()
	{
		this.dataStore.baseParams = {action: 'get'};
	},
	setRenderers: function()
	{

		/* add remove link */
		this.columnModel.setRenderer(5, function(value, metadata)
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
		intelli.searches.oGrid.grid.on('afteredit', function(editEvent)
		{
			var value =  editEvent.value;

			Ext.Ajax.request(
			{
				waitMsg: intelli.admin.lang.saving_changes,
				url: intelli.searches.vUrl,
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
		intelli.searches.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				intelli.searches.oGrid.saveGridState();

				window.location = 'controller.php?plugin=searches&do=edit&id='+ record.json.id;
			}

			if('remove' == fieldName)
			{
				Ext.Ajax.request(
				{
					waitMsg: intelli.admin.lang.saving_changes,
					url: intelli.searches.vUrl,
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
		});

		/* Enable disable functionality buttons */
		intelli.searches.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('removeBtn').enable();
		});

		intelli.searches.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('removeBtn').disable();
			}
		});

		/* remove button action */
		Ext.getCmp('removeBtn').on('click', function()
		{
			var rows = intelli.searches.oGrid.grid.getSelectionModel().getSelections();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Ajax.request(
			{
				waitMsg: intelli.admin.lang.saving_changes,
				url: intelli.searches.vUrl,
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

					intelli.searches.oGrid.grid.getStore().reload();

					//Ext.getCmp('statusCmb').disable();
					//Ext.getCmp('goBtn').disable();
					Ext.getCmp('removeBtn').disable();
				}
			});
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.searches.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.searches.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.searches.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if(Ext.get('box_searches'))
	{
		intelli.searches.oGrid = new intelli.exGrid({url: intelli.searches.vUrl});

		/* Initialization grid */
		intelli.searches.oGrid.init();
	}
	
	if(Ext.get('date'))
	{
		new Ext.form.DateField(
		{
			allowBlank: false,
			format: 'Y-m-d',
			applyTo: 'date'
		});
	}
});