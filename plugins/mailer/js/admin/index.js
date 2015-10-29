intelli.mailer = function()
{
	var vUrl = 'controller.php?plugin=mailer';

	return {
		oGrid: null,
		vUrl: vUrl,
		statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['active', 'active'],['approval', 'approval'],['unconfirmed', 'unconfirmed']]
		}),
		statusesStoreFilter: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['all', intelli.admin.lang._status_],
				['active', 'active'],
				['approval', 'approval'],
				['unconfirmed', 'unconfirmed']
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
			{name: 'realname', mapping: 'realname', type: 'string'},
			{name: 'email', mapping: 'email'},
			{name: 'status', mapping: 'status'},
			{name: 'date', mapping: 'date_reg'},
			{name: 'sendemail', mapping: 'sendemail'},
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
			header: intelli.admin.lang.realname,
			dataIndex: 'realname',
			sortable: true,
			width: 250,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.email,
			dataIndex: 'email',
			sortable: true,
			width: 250,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.status,
			dataIndex: 'status',
			width: 100,
			sortable: true,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				editable: false,
				lazyRender: true,
				store: intelli.mailer.statusesStore,
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
			width: 40,
			dataIndex: 'sendemail',
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

		this.dataStore.setDefaultSort('realname');
	},
	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
            minHeight: 100
		});

		this.title = intelli.admin.lang.manage_subscribers;
		this.renderTo = 'box_subscribe';

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
		/*
		 * Top toolbar
		 */
		this.topToolbar = new Ext.Toolbar(
		{
			items:[
			intelli.admin.lang.id + ':',
			{
				xtype: 'numberfield',
				allowDecimals: false,
				allowNegative: false,
				name: 'searchId',
				id: 'searchId',
				emptyText: 'Enter ID',
				style: 'text-align: left'
			},
			intelli.admin.lang.realname + ':',
			{
				xtype: 'textfield',
				name: 'searchRealname',
				id: 'searchRealname',
				emptyText: 'Enter realname'
			},
			intelli.admin.lang.email + ':',
			{
				xtype: 'textfield',
				name: 'searchEmail',
				id: 'searchEmail',
				emptyText: 'Enter email'
			},
			intelli.admin.lang.status + ':',
			{
				xtype: 'combo',
				typeAhead: true,
				triggerAction: 'all',
				editable: false,
				lazyRender: true,
				store: intelli.mailer.statusesStoreFilter,
				value: 'all',
				displayField: 'display',
				valueField: 'value',
				mode: 'local',
				id: 'stsFilter'
			},{
				text: intelli.admin.lang.search,
				iconCls: 'search-grid-ico',
				id: 'fltBtn',
				handler: function()
				{
					var id = Ext.getCmp('searchId').getValue();
					var realname = Ext.getCmp('searchRealname').getValue();
					var email = Ext.getCmp('searchEmail').getValue();
					var status = Ext.getCmp('stsFilter').getValue();

					if('' != id || '' != realname || '' != email || '' != status)
					{
						intelli.mailer.oGrid.dataStore.baseParams =
						{
							action: 'get',
							realname: realname,
							email: email,
							status: status,
							id: id
						};

						intelli.mailer.oGrid.dataStore.reload();
					}
				}
			},
			'-',
			{
				text: intelli.admin.lang.reset,
				id: 'resetBtn',
				handler: function()
				{
					Ext.getCmp('searchId').reset();
					Ext.getCmp('searchRealname').reset();
					Ext.getCmp('searchEmail').reset();
					Ext.getCmp('stsFilter').setValue('all');

					intelli.mailer.oGrid.dataStore.baseParams =
					{
						action: 'get',
						realname: '',
						email: '',
						status: ''
					};

					intelli.mailer.oGrid.dataStore.reload();
				}
			}]
		});

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
					store: intelli.mailer.pagingStore,
					value: '10',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'pgnPnl'
				},
				intelli.admin.lang.status + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					store: intelli.mailer.statusesStore,
					value: 'active',
					displayField: 'value',
					valueField: 'display',
					mode: 'local',
					disabled: true,
					id: 'statusCmb'
				},{
					text: intelli.admin.lang['do'],
					disabled: true,
					iconCls: 'go-grid-ico',
					id: 'goBtn',
					handler: function()
					{
						var rows = intelli.mailer.oGrid.grid.getSelectionModel().getSelections();
						var status = Ext.getCmp('statusCmb').getValue();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].json.id;
						}

						Ext.Ajax.request(
						{
							url: intelli.mailer.vUrl,
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
								intelli.mailer.oGrid.grid.getStore().reload();

								var response = Ext.decode(data.responseText);
								var type = response.error ? 'error' : 'notif';

								intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});
							}
						});
					}
				},
				'-',
				{
					text: intelli.admin.lang.remove,
					id: 'removeBtn',
					iconCls: 'remove-grid-ico',
					disabled: true,
					handler: function()
					{
						var rows = intelli.mailer.oGrid.grid.getSelectionModel().getSelections();
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
										url: intelli.mailer.vUrl,
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

											intelli.mailer.oGrid.grid.getStore().reload();

											Ext.getCmp('statusCmb').disable();
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
		/* change background color for status field */
		this.columnModel.setRenderer(3, function(value, metadata)
		{
			metadata.css = value;

			return value;
		});

		/* add sendemail link */
		this.columnModel.setRenderer(5, function(value, metadata)
		{
			if (value > 0)
			{
				return '<img class="grid_action" alt="' + intelli.admin.lang.resend_confirmation_email + '" title="' + intelli.admin.lang.resend_confirmation_email + '" src="templates/' + intelli.config.admin_tmpl + '/img/icons/email-grid-ico.png" />';
			}
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
		intelli.mailer.oGrid.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.mailer.vUrl,
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
		intelli.mailer.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
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
								url: intelli.mailer.vUrl,
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

									Ext.getCmp('statusCmb').disable();
									Ext.getCmp('goBtn').disable();
									Ext.getCmp('removeBtn').disable();

									intelli.mailer.oGrid.grid.getStore().reload();
								}
							});
						}
					}
				});
			}
			if ('sendemail' == fieldName)
			{
				if (data != '0')
				{
					Ext.Msg.show(
					{
						title: intelli.admin.lang.confirm,
						msg: intelli.admin.lang.are_you_sure_to_resend_confirmation,
						buttons: Ext.Msg.YESNO,
						icon: Ext.Msg.QUESTION,
						fn: function(btn)
						{
							if ('yes' == btn)
							{
								Ext.Ajax.request(
								{
									url: intelli.mailer.vUrl,
									method: 'POST',
									params: {
										action: 'sendemail',
										'ids[]': record.id
									},
									failure: function(){
										Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
									},
									success: function(data){
										var response = Ext.decode(data.responseText);
										var type = response.error ? 'error' : 'notif';

										intelli.admin.notifBox({
											msg: response.msg,
											type: type,
											autohide: true
										});

										Ext.getCmp('statusCmb').disable();
										Ext.getCmp('goBtn').disable();
										Ext.getCmp('removeBtn').disable();

										intelli.mailer.oGrid.grid.getStore().reload();
									}
								});
							}
						}
					});
				}
			}
		});

		/* Enable disable functionality buttons */
		intelli.mailer.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('removeBtn').enable();
		});

		intelli.mailer.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('statusCmb').disable();
				Ext.getCmp('goBtn').disable();
				Ext.getCmp('removeBtn').disable();
			}
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.mailer.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.mailer.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.mailer.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if(Ext.get('box_subscribe'))
	{
		intelli.mailer.oGrid = new intelli.exGrid({url: intelli.mailer.vUrl});

		/* Initialization grid */
		intelli.mailer.oGrid.init();

		if(intelli.urlVal('status'))
		{
			Ext.getCmp('stsFilter').setValue(intelli.urlVal('status'));
		}

		var search = intelli.urlVal('quick_search');
		
		if(null != search)
		{
			if(intelli.is_int(search))
			{
				Ext.getCmp('searchId').setValue(search);
			}
			else if(intelli.is_email(search))
			{
				Ext.getCmp('searchEmail').setValue(search);
			}
			else
			{
				Ext.getCmp('searchRealname').setValue(search);
			}
		}
	}
});



$('input.check').click(function() {
		news_check('queue',$(this).attr("checked"));
});
