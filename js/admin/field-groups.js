intelli.fieldGroups = function()
{
	var vUrl = 'controller.php?file=field-groups';

	return {
		oGrid: null,
		vUrl: vUrl,
		statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['active', 'active'],['approval', 'approval']]
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
			{name: 'name'},
			{name: 'title'},
			{name: 'order'},
			{name: 'collapsible'},
			{name: 'collapsed'},
			{name: 'edit'},
			{name: 'remove'}
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
			header: intelli.admin.lang.name,
			dataIndex: 'name',
			sortable: false,
			width: 200,
			hidden: true
		},{
			header: intelli.admin.lang.title,
			dataIndex: 'title', 
			sortable: false,
			width: 200
		},{
			header: intelli.admin.lang.order,
			dataIndex: 'order', 
			width: 60,
			sortable: true,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.collapsible,
			width: 60,
			dataIndex: 'collapsible',
			menuDisabled: true,
			align: 'center'
		},{
			header: intelli.admin.lang.collapsed,
			width: 60,
			dataIndex: 'collapsed',
			menuDisabled: true,
			align: 'center'
		},{
			header: "",
			width: 60,
			dataIndex: 'edit',
			hideable: false,
			menuDisabled: true,
			align: 'center'
		}, {
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

		this.dataStore.setDefaultSort('order');
	},
	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
			minHeight: 100
		});

		this.title = intelli.admin.lang.manage_field_groups;
		this.renderTo = 'box_sections';

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
		this.bottomToolbar = new Ext.PagingToolbar(
		{
			store: this.dataStore,
			pageSize: 20,
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
					store: intelli.fieldGroups.pagingStore,
					value: '20',
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
						var rows = intelli.fieldGroups.oGrid.grid.getSelectionModel().getSelections();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].json.id;
						}

						Ext.Msg.show(
						{
							title: intelli.admin.lang.confirm,
							msg: rows.length > 1 ? intelli.admin.lang.are_you_sure_to_delete_selected_field_groups : intelli.admin.lang.are_you_sure_to_delete_selected_field_group,
							buttons: Ext.Msg.YESNO,
							icon: Ext.Msg.QUESTION,
							fn: function(btn)
							{
								if('yes' == btn)
								{
									Ext.Ajax.request(
									{
										url: intelli.fieldGroups.vUrl,
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

											intelli.fieldGroups.oGrid.grid.getStore().reload();

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
		this.dataStore.baseParams = {action: 'get'};
	},
	setRenderers: function()
	{
		/* add edit link */
		this.columnModel.setRenderer(6, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
		});

		/* add remove link */
		this.columnModel.setRenderer(7, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.remove +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico.png" />';
		});
	},
	setEvents: function()
	{
		/* Edit fields */
		intelli.fieldGroups.oGrid.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.fieldGroups.vUrl,
				method: 'POST',
				params:
				{
					action: 'update',
					'ids[]': editEvent.record.id,
					field: editEvent.field,
					value: editEvent.value
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
		intelli.fieldGroups.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				intelli.fieldGroups.oGrid.saveGridState();

				window.location = 'controller.php?file=field-groups&do=edit&id='+ record.json.id;
			}

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_field_group,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.fieldGroups.vUrl,
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
									var response = Ext.decode(data.responseText);
									var type = response.error ? 'error' : 'notif';

									intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});

									Ext.getCmp('removeBtn').disable();

									grid.getStore().reload();
								}
							});
						}
					}
				});
			}
		});

		/* Enable disable functionality buttons */
		intelli.fieldGroups.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('removeBtn').enable();
		});

		intelli.fieldGroups.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('removeBtn').disable();
			}
		});

		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.fieldGroups.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.fieldGroups.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.fieldGroups.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if (Ext.get('box_sections'))
	{
		intelli.fieldGroups.oGrid = new intelli.exGrid({url: intelli.fieldGroups.vUrl});
		intelli.fieldGroups.oGrid.init();
	}

	$("input[name='collapsible']").change(function()
	{
		if (1 == $(this).val())
		{
			$("input[name='collapsed']").parents('tr:first').show();
		}
		else
		{
			$("input[name='collapsed']").val(0).parents('tr:first').hide();
		}
	});

	if (1 == $("input[name='collapsible']").val())
	{
		$("input[name='collapsed']").parents('tr:first').show();
	}
	else
	{
		$("input[name='collapsed']").val(0).parents('tr:first').hide();
	}

	var check_all = true;
	$("input[name='fields[]']").each(function()
	{
		if (!$(this).prop("checked"))
		{
			check_all = false;
		}
	});

	$("#check_all_fields").prop("checked", check_all);

	$("#check_all_fields").click(function()
	{
		var checked = $(this).prop("checked");

		$("input[name='fields[]']").each(function()
		{
			$(this).prop("checked", checked);
		});
	});
});