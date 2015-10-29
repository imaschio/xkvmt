intelli.transactions = function()
{
	var vUrl = 'controller.php?file=transactions';

	return {
		oGrid: null,
		vUrl: vUrl,
		vWindowAdd: null,
		vFormAdd: null,
		conditionsStoreFilter: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['or', intelli.admin.lang['or']],
				['and', intelli.admin.lang['and']]
			]
		}),
		statusStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['pending', 'pending'],
				['passed', 'passed'],
				['failed', 'failed'],
				['refunded', 'refunded']
			]
		}),
		itemTypesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['account', intelli.admin.lang.account],
				['listing', intelli.admin.lang.listing]
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
			{name: 'username', mapping: 'username'},
			{name: 'plan', mapping: 'plan'},
			{name: 'item', mapping: 'item'},
			{name: 'email', mapping: 'email'},
			{name: 'order', mapping: 'order'},
			{name: 'total', mapping: 'total'},
			{name: 'date', mapping: 'date'},
			{name: 'status', mapping: 'status'},
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
		var ds = new Ext.data.Store(
		{
			proxy: new Ext.data.HttpProxy({url: intelli.transactions.vUrl + '&action=listing', method: 'GET'}),
			reader: new Ext.data.JsonReader(
			{
				root: 'data',
				totalProperty: 'total'
			}, [
				{name: 'id', mapping: 'id'},
				{name: 'title', mapping: 'title'},
				{name: 'description', mapping: 'description'}
			])
		});

		var resultTpl = new Ext.XTemplate(
			'<tpl for="."><div class="search-item">',
				'<h4><br />{title}</h4>',
				'{description}',
			'</div></tpl>'
		);

		var search = new Ext.form.ComboBox(
		{
			store: ds,
			displayField: 'title',
			fieldLabel: intelli.admin.lang.listing,
			valueField: 'id',
			allowBlank: false,
			typeAhead: false,
			loadingText: intelli.admin.lang.searching,
			emptyText: intelli.admin.lang.type_listing_title,
			hiddenName: 'listing',
			pageSize: 10,
			hideTrigger: true,
			tpl: resultTpl,
			itemSelector: 'div.search-item'
		});

		this.columnModel = new Ext.grid.ColumnModel([
		this.checkColumn,
		{
			header: intelli.admin.lang.username,
			dataIndex: 'username', 
			sortable: true, 
			width: 150
		},{
			header: intelli.admin.lang.plan, 
			dataIndex: 'plan',
			sortable: true, 
			width: 150
		},{
			header: intelli.admin.lang.item,
			dataIndex: 'item', 
			sortable: true, 
			width: 100
		},{
			header: intelli.admin.lang.email, 
			dataIndex: 'email', 
			sortable: true, 
			width: 150,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.order_number,
			dataIndex: 'order', 
			sortable: true, 
			width: 150
		},{
			header: intelli.admin.lang.total, 
			dataIndex: 'total', 
			sortable: true, 
			width: 80,
			editor: new Ext.form.NumberField({
				allowBlank: false,
				allowDecimals: true,
				allowNegative: false
			})
		},{

			header: intelli.admin.lang.status,
			dataIndex: 'status',
			sortable: true,
			width: 80,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				forceSelection: true,
				lazyRender: true,
				store: intelli.transactions.statusStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			})
		},{
			header: intelli.admin.lang.date, 
			dataIndex: 'date',
			sortable: true, 
			width: 130,
			renderer: intelli.admin.gridRenderDate
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

		this.dataStore.setDefaultSort('date', 'DESC');
	},
	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
            minHeight: 100
		});

		this.title = intelli.admin.lang.manage_transactions;
		this.renderTo = 'box_transactions';

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
		 * Top toolbar
		 */
		this.topToolbar = new Ext.Toolbar(
		{
			items:[
				intelli.admin.lang.email + ':',
				{
					xtype: 'textfield',
					id: 'emailFilter',
					emptyText: 'Email'
				},
				' ',
				intelli.admin.lang.condition + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					width: 60,
					store: intelli.transactions.conditionsStoreFilter,
					value: 'OR',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'conditionsCombo'
				},
				' ',
				intelli.admin.lang.order_number + ':',
				{
					xtype: 'textfield',
					id: 'orderFilter',
					emptyText: 'Order'
				},
				{
					text: intelli.admin.lang.search,
					iconCls: 'search-grid-ico',
					id: 'fltBtn',
					handler: function()
					{
						var email = Ext.getCmp('emailFilter').getValue();
						var order = Ext.getCmp('orderFilter').getValue();

						if('' != email || '' != order)
						{
							intelli.transactions.oGrid.dataStore.baseParams = 
							{
								action: 'get',
								email: email,
								order: order
							};

							intelli.transactions.oGrid.dataStore.reload();
						}
					}
				},
				'-',
				{
					text: intelli.admin.lang.reset,
					id: 'resetBtn',
					handler: function()
					{
						Ext.getCmp('emailFilter').reset();
						Ext.getCmp('orderFilter').reset();

						intelli.transactions.oGrid.dataStore.baseParams = 
						{
							action: 'get',
							email: '',
							order: ''
						};

						intelli.transactions.oGrid.dataStore.reload();
					}
				}
			]
		});

		/*
		 * Bottom toolbar 
		 */
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
					forceSelection: true,
					lazyRender: true,
					width: 80,
					store: intelli.transactions.pagingStore,
					value: '20',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'pgnPnl'
				}
			]
		});
	},
	setupBaseParams: function()
	{
		this.dataStore.baseParams = 
		{
			action: 'get'
		};
	},
	setRenderers: function()
	{
		/* change background color for status field */
		this.columnModel.setRenderer(7, function(value, metadata)
		{
			metadata.css = value;

			return value;
		});

		/* add remove link */
		this.columnModel.setRenderer(9, function(value, metadata)
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
		intelli.transactions.oGrid.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.transactions.vUrl,
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
		intelli.transactions.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_transaction,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.transactions.vUrl,
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

									intelli.transactions.oGrid.grid.getStore().reload();
								}
							});
						}
					}
				});
			}
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.transactions.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.transactions.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.transactions.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if(Ext.get('box_transactions'))
	{
		intelli.transactions.oGrid = new intelli.exGrid({url: intelli.transactions.vUrl});

		/* Initialization grid */
		intelli.transactions.oGrid.init();
	}

	$('#add').click(function()
	{
		if (!intelli.transactions.vFormAdd)
		{
			var fields = ['listing', 'plan', 'account', 'email', 'order', 'total', 'date', 'time'];

			var ds = new Ext.data.Store(
			{
				proxy: new Ext.data.HttpProxy({url: intelli.transactions.vUrl, method: 'GET'}),
				reader: new Ext.data.JsonReader(
				{
					root: 'data',
					totalProperty: 'total'
				}, [
					{name: 'id', mapping: 'id'},
					{name: 'title', mapping: 'title'},
					{name: 'description', mapping: 'description'}
				])
			});

			var resultTpl = new Ext.XTemplate(
				'<tpl for="."><div class="search-item">',
					'<h4><br />{title}</h4>',
					'{description}',
				'</div></tpl>'
			);

			var search = new Ext.form.ComboBox(
			{
				store: ds,
				displayField: 'title',
				fieldLabel: intelli.admin.lang.item_element,
				valueField: 'id',
				allowBlank: false,
				typeAhead: false,
				loadingText: intelli.admin.lang.searching,
				emptyText: intelli.admin.lang.type_listing_account_title,
				name: 'item_element',
				hiddenName: 'listing',
				width: 400,
				pageSize: 10,
				hideTrigger: true,
				tpl: resultTpl,
				itemSelector: 'div.search-item',
				disabled: true,
				listeners:
				{
					'beforequery': function(queryEvent)
					{
						var item_value = intelli.transactions.vFormAdd.getForm().findField('item').getValue();

						if ('' == item_value)
						{
							intelli.admin.notifBox({msg: intelli.admin.lang.empty_transaction_item, type: 'error', autohide: true});
							queryEvent.cancel = true;
						}
					}
				}
			});

			var plan = new Ext.form.ComboBox(
			{
				fieldLabel: intelli.admin.lang.plan,
				typeAhead: true,
				triggerAction: 'all',
				allowBlank: false,
				width: 400,
				forceSelection: true,
				hiddenName: 'plan',
				lazyRender: true,
				disabled: true,
				store: new Ext.data.JsonStore({
					url: intelli.transactions.vUrl + '&action=getplans',
					root: 'data',
					fields: ['value', 'display']
				}),
				displayField: 'display',
				valueField: 'value'
			});

			intelli.transactions.vFormAdd = new Ext.FormPanel(
			{
				labelWidth: 100,
				frame: true,
				title: intelli.admin.lang.add_transaction,
				bodyStyle: 'padding:5px 5px 0',
				renderTo: 'box_transactions_add',
				buttonAlign: 'left',
				items: [
				{
					fieldLabel: intelli.admin.lang.item,
					name: 'item',
					hiddenName: 'item',
					width: 400,
					xtype: 'bettercombo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					store: new Ext.data.SimpleStore(
					{
						fields: ['value', 'display'],
						data : [
							['account', 'Account'],
							['listing', 'Listing']
						]
					}),
					listeners:
					{
						'change': function(field, new_value, old_value)
						{
							ds.setBaseParam('action', new_value);

							plan.store.setBaseParam('type', new_value);
							plan.clearValue();
							plan.store.reload();

							jQuery.each(fields, function(i, v)
							{
								intelli.transactions.vFormAdd.getForm().findField(v).enable();
							});

							Ext.getCmp('submit').enable();
						}
					}
				},
				search, plan,
				{
					store: new Ext.data.Store(
					{
						proxy: new Ext.data.HttpProxy({url: intelli.transactions.vUrl + '&action=account', method: 'GET'}),
						reader: new Ext.data.JsonReader(
						{
							root: 'data',
							totalProperty: 'total'
						}, [
							{name: 'id', mapping: 'id'},
							{name: 'title', mapping: 'title'},
							{name: 'description', mapping: 'description'}
						])
					}),
					xtype: 'combo',
					valueField: 'id',
					displayField: 'title',
					fieldLabel: intelli.admin.lang.account,
					allowBlank: true,
					typeAhead: false,
					loadingText: intelli.admin.lang.searching,
					emptyText: intelli.admin.lang.type_account_username,
					name: 'account',
					hiddenName: 'account',
					width: 400,
					pageSize: 10,
					hideTrigger: true,
					tpl: resultTpl,
					itemSelector: 'div.search-item',
					disabled: true
				},{
					fieldLabel: intelli.admin.lang.email,
					name: 'email',
					vtype: 'email',
					allowBlank: false,
					width: 400,
					xtype: 'textfield',
					disabled: true
				},{
					fieldLabel: intelli.admin.lang.order_number,
					name: 'order',
					width: 400,
					autoHeight: '18px',
					allowBlank: false,
					xtype: 'textfield',
					disabled: true
				},{
					fieldLabel: intelli.admin.lang.total,
					name: 'total',
					width: 400,
					allowBlank: false,
					xtype: 'textfield',
					disabled: true
				},
				new Ext.form.DateField(
				{
					fieldLabel: intelli.admin.lang.date,
					name: 'date',
					editable: false,
					format: 'Y-m-d',
					width: 400,
					disabled: true
				}),
				new Ext.form.TimeField(
				{
					fieldLabel: intelli.admin.lang.time,
					name: 'time',
					editable: false,
					increment: 30,
					width: 400,
					disabled: true
				})],				
				buttons: [
				{
					text: intelli.admin.lang.save,
					id: 'submit',
					disabled: true,
					handler: function()
					{
						var form = intelli.transactions.vFormAdd.getForm();

						if(form.isValid())
						{
							form.submit(
							{
								url: intelli.transactions.vUrl + '&action=add',
								success: function(form, data)
								{
									var type = data.result.error ? 'error' : 'notif';

									intelli.admin.notifBox({msg: data.result.data.msg, type: type, autohide: true});
									intelli.transactions.oGrid.dataStore.reload();

									Ext.Msg.show(
									{
										title: intelli.admin.lang.confirm,
										msg: intelli.admin.lang.add_new_transaction,
										buttons: Ext.Msg.YESNO,
										icon: Ext.Msg.QUESTION,
										fn: function(btn)
										{
											if('no' == btn)
											{
												intelli.transactions.vFormAdd.hide();
											}

											form.reset();
										}
									});
								}
							});
						}
					}
				},{
					text: intelli.admin.lang.cancel,
					handler: function()
					{
						intelli.transactions.vFormAdd.hide();
					}
				}]
			});
		}

		intelli.transactions.vFormAdd.show();

		return false;
	});
});

