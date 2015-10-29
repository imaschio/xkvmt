intelli.faq_categories = function()
{
	var vUrl = 'controller.php?plugin=faq&file=categories';

	return {
		oGridCat: null,
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

var save_id = '';

intelli.exGModelCat = Ext.extend(intelli.gmodel,
{
	constructor: function(config)
	{
		intelli.exGModelCat.superclass.constructor.apply(this, arguments);
	},
	setupReader: function()
	{
		this.record = Ext.data.Record.create([
			{name: 'title', mapping: 'title'},
			{name: 'description', mapping: 'description'},
			{name: 'lang', mapping: 'lang'},
			{name: 'status', mapping: 'status'},
			{name: 'show_faq'},
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
			width: 190,
			menuDisabled: true,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.description, 
			dataIndex: 'description', 
			sortable: true, 
			width: 560,
			menuDisabled: true,
			editor: new Ext.form.TextArea({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.language, 
			dataIndex: 'lang', 
			sortable: true, 
			width: 70,
			menuDisabled: true
		},{
			header: intelli.admin.lang.status, 
			dataIndex: 'status',
			width: 100,
			sortable: true,
			menuDisabled: true,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				editable: false,
				lazyRender: true,
				store: intelli.faq_categories.statusesStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			})
		},{
			header: "",
			width: 40,
			dataIndex: 'show_faq',
			hideable: false,
			menuDisabled: true,
			align: 'center'
		},{
			header: "",
			width: 40,
			dataIndex: 'edit',
			hideable: false,
			menuDisabled: true,
			align: 'center'
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

intelli.exGridCat = Ext.extend(intelli.grid,
{
	model: null,
	constructor: function(config)
	{
		intelli.exGridCat.superclass.constructor.apply(this, arguments);

		this.model = new intelli.exGModelCat({url: config.url});
			
		this.dataStore = this.model.setupDataStore();
		this.columnModel = this.model.setupColumnModel();
		this.selectionModel = this.model.setupSelectionModel();
	},
	init: function()
	{
		this.plugins = [new Ext.ux.PanelResizer({
            minHeight: 100
		})];
		
		this.title = intelli.admin.lang.faq_categories;
		this.renderTo = 'box_faq_categories';

		this.setupBaseParams();
		this.setupPagingPanel();
		this.setupGrid();

		this.setRenderers();
		this.setEvents();

		//this.grid.autoExpandColumn = 1;

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
					store: intelli.faq_categories.pagingStore,
					value: '10',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'pgnPnlCat'
				},
				'-',
				intelli.admin.lang.status,
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					store: intelli.faq_categories.statusesStore,
					value: 'active',
					displayField: 'value',
					valueField: 'display',
					mode: 'local',
					disabled: true,
					id: 'statusCmbCat'
				},{
					text: intelli.admin.lang['do'],
					disabled: true,
					iconCls: 'go-grid-ico',
					id: 'goBtnCat'
				},
				'-',
				{
					text: intelli.admin.lang.remove,
					id: 'removeBtnCat',
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
		/* change background color for status field */
		this.columnModel.setRenderer(4, function(value, metadata)
		{
			metadata.css = value;

			return value;
		});
		
		/* add manage faqs link */
		this.columnModel.setRenderer(5, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.click_to_manage_faqs +'" title="'+ intelli.admin.lang.click_to_manage_faqs +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/question.gif" />';
		});

		/* add edit link */
		this.columnModel.setRenderer(6, function(value, metadata)
		{
			if ('-1' != value)
			{
				return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
			}
		});

		/* add remove link */
		this.columnModel.setRenderer(7, function(value, metadata)
		{
			if ('-1' != value)
			{
				return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.remove +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico.png" />';
			}	
		});
	},
	setEvents: function()
	{
		/* 
		 * Events
		 */
		intelli.faq_categories.oGridCat.grid.getSelectionModel().on('beforerowselect', function(grid,rowIndex,k,record)
		{
			if (record.id < 0) return false;
		});
		
		intelli.faq_categories.oGridCat.grid.on('beforeedit', function(e)
		{
			if (e.record.id < 0)
			{
				return false;
			}
		});
		/* Edit fields */
		intelli.faq_categories.oGridCat.grid.on('afteredit', function(editEvent)
		{
			var value =  editEvent.value;

			Ext.Ajax.request(
			{
				waitMsg: intelli.admin.lang.saving_changes,
				url: intelli.faq_categories.vUrl,
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
		intelli.faq_categories.oGridCat.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('show_faq' == fieldName)
			{
				id = record.id;
				if(save_id != id)
				{
					intelli.faq.oGrid.grid.getStore().load({params: {action: 'get',id: id, limit:'10', start:'0'}});
					save_id = id;
				}
			}
			
			if (record.id < 0)
			{
				return false;
			}
			
			if('edit' == fieldName)
			{
				intelli.faq_categories.oGridCat.saveGridState();
				if (record.json.id>0)
				{
					window.location = intelli.faq_categories.vUrl +'&do=edit&id='+ record.json.id;
				}
			}

			if('remove' == fieldName)
			{
				if (record.json.id>0)
				{
					Ext.Msg.show(
					{
						title: intelli.admin.lang.confirm,
						msg: intelli.admin.lang.are_you_sure_to_delete_selected_faq_category,
						buttons: Ext.Msg.YESNO,
						icon: Ext.Msg.QUESTION,
						fn: function(btn)
						{
							if('yes' == btn)
							{
								Ext.Ajax.request(
								{
									url: intelli.faq_categories.vUrl,
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
										
										intelli.faq_categories.oGridCat.grid.getStore().reload();
										intelli.faq.oGrid.grid.getStore().reload();
									}
								});
							}
						}
					});
				}
			}
		});

		/* Enable disable functionality buttons */
		intelli.faq_categories.oGridCat.grid.getSelectionModel().on('rowselect', function(grid,rowIndex,record)
		{
			if (record.id>0)
			{
				Ext.getCmp('statusCmbCat').enable();
				Ext.getCmp('goBtnCat').enable();
				Ext.getCmp('removeBtnCat').enable();
			}
		});

		intelli.faq_categories.oGridCat.grid.getSelectionModel().on('rowdeselect', function(sm,rowIndex,record)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('statusCmbCat').disable();
				Ext.getCmp('goBtnCat').disable();
				Ext.getCmp('removeBtnCat').disable();
			}
		});

		/* Go button action */
		Ext.getCmp('goBtnCat').on('click', function()
		{
			var rows = intelli.faq_categories.oGridCat.grid.getSelectionModel().getSelections();
			var status = Ext.getCmp('statusCmbCat').getValue();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Ajax.request(
			{
				waitMsg: intelli.admin.lang.saving_changes,
				url: intelli.faq_categories.vUrl,
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
					intelli.faq_categories.oGridCat.grid.getStore().reload();
					intelli.faq.oGrid.grid.getStore().reload();

					var response = Ext.decode(data.responseText);
					var type = response.error ? 'error' : 'notif';
						
					intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});

					Ext.getCmp('statusCmbCat').disable();
					Ext.getCmp('goBtnCat').disable();
					Ext.getCmp('removeBtnCat').disable();
				}
			});
		});

		/* remove button action */
		Ext.getCmp('removeBtnCat').on('click', function()
		{
			var rows = intelli.faq_categories.oGridCat.grid.getSelectionModel().getSelections();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Msg.show(
			{
				title: intelli.admin.lang.confirm,
				msg: (ids.length > 1) ? intelli.admin.lang.are_you_sure_to_delete_selected_faq_categories : intelli.admin.lang.are_you_sure_to_delete_this_faq_category,
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				fn: function(btn)
				{
					if('yes' == btn)
					{
						Ext.Ajax.request(
						{
							waitMsg: intelli.admin.lang.saving_changes,
							url: intelli.faq_categories.vUrl,
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

								intelli.faq_categories.oGridCat.grid.getStore().reload();
								intelli.faq.oGrid.grid.getStore().reload();
							}
						});
					}
				}
			});
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnlCat').on('change', function(field, new_value, old_value)
		{
			intelli.faq_categories.oGridCat.grid.getStore().lastOptions.params.limit = new_value;
			intelli.faq_categories.oGridCat.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.faq_categories.oGridCat.grid.getStore().reload();
			intelli.faq.oGrid.grid.getStore().reload();
		});
	}
});