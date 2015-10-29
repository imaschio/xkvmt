intelli.listings = function()
{
	var vUrl = 'controller.php?file=listings';

	return {
		oGrid: null,
		vUrl: vUrl,
		vWindow: null,
		vTree: null,
		statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['active', intelli.admin.lang.active],
				['approval', intelli.admin.lang.approval],
				['banned', intelli.admin.lang.banned],
				['suspended', intelli.admin.lang.suspended],
				['deleted', intelli.admin.lang.deleted]
			]
		}),
		statusesStoreFilter: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['all', intelli.admin.lang._status_],
				['active', intelli.admin.lang.active],
				['approval', intelli.admin.lang.approval],
				['banned', intelli.admin.lang.banned],
				['suspended', intelli.admin.lang.suspended],
				['deleted', intelli.admin.lang.deleted]
			]
		}),
		pagingStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['10', '10'],['20', '20'],['30', '30'],['40', '40'],['50', '50']]
		}),
		statesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['all', intelli.admin.lang._check_status_],
				['reported_as_broken', intelli.admin.lang.reported_as_broken],
				['destvalid', intelli.admin.lang.destination_valid],
				['destbroken', intelli.admin.lang.destination_broken],
				['recipbroken', intelli.admin.lang.reciprocal_broken],
				['recipvalid', intelli.admin.lang.reciprocal_valid]
			]
		}),
		typesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['all', intelli.admin.lang._type_],
				['featured', intelli.admin.lang.featured],
				['sponsored', intelli.admin.lang.sponsored],
				['partner', intelli.admin.lang.partner],
				['regular', intelli.admin.lang.regular]
			]
		}),
		actionsStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['', intelli.admin.lang._action_],
				['check_broken', intelli.admin.lang.check_broken],
				['unbroken', intelli.admin.lang.unbroken],
				['update_pagerank', intelli.admin.lang.update_pagerank],
				['recip_recheck', intelli.admin.lang.recip_recheck],
				['copy', intelli.admin.lang.copy],
				['cross', intelli.admin.lang.cross],
				['move', intelli.admin.lang.move],
				['send_email', intelli.admin.lang.send_email]
			]
		})
	};
}();

var expander = new Ext.grid.RowExpander({
	tpl : new Ext.Template(
		'<p>{listing_details}</p>'
	)
});

var account_ds = new Ext.data.Store(
{
	proxy: new Ext.data.HttpProxy({url: intelli.listings.vUrl + '&action=getaccounts', method: 'GET'}),
	reader: new Ext.data.JsonReader(
	{
		root: 'data',
		totalProperty: 'total'
	}, [
		{name: 'id', mapping: 'id'},
		{name: 'username', mapping: 'username'}
	])
});

var resultTpl = new Ext.XTemplate(
	'<tpl for="."><div class="search-item" style="padding: 3px;">',
		'<h4>{username}</h4>',
	'</div></tpl>'
);

var account_search = new Ext.form.ComboBox(
{
	store: account_ds,
	displayField: 'username',
	valueField: 'id',
	allowBlank: true,
	minChars: 1,
	typeAhead: false,
	loadingText: intelli.admin.lang.searching,
	hiddenName: 'listing',
	pageSize: 10,
	hideTrigger: true,
	tpl: resultTpl,
	itemSelector: 'div.search-item'
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
			{name: 'title', mapping: 'title'},
			{name: 'account_id', mapping: 'username'},
			{name: 'category', mapping: 'category'},
			//MOD: Display payment ID
			//{name: 'payment_id', mapping: 'payment_id'},
			//{name: 'payment_status', mapping: 'payment_status'},
			{name: 'parents', mapping: 'parents'},
			{name: 'status', mapping: 'status'},
			{name: 'date', mapping: 'date'},
			{name: 'description'},
			{name: 'listing_details'},
			{name: 'url', dataIndex: 'url'},
			{name: 'domain', dataIndex: 'domain'},
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
		expander,
		{
			header: intelli.admin.lang.title,
			dataIndex: 'title',
			sortable: true,
			renderer: function(value, p, record)
			{
				return String.format('<b><a href="{0}" target="_blank">{1}</a></b>', record.json.url, value);
			},
			width: 230,
			editor: new Ext.form.TextField({ allowBlank: false })
		},{
			header: intelli.admin.lang.url,
			dataIndex: 'url',
			sortable: false,
			renderer: function(value, p, record)
			{
				return String.format('<b><a href="{0}" target="_blank">{1}</a></b>', record.json.url, value);
			},
			width: 250,
			editor: new Ext.form.TextField({ allowBlank: false })
		},{
			header: intelli.admin.lang.account,
			dataIndex: 'account_id',
			sortable: true,
			width: 100,
			renderer: function(value, p, record)
			{
				if (value)
				{
					return String.format('<b><a href="controller.php?file=accounts&do=edit&id={0}">{1}</a></b>', record.json.account_id, value);
				}
			},
			editor: account_search
		},{
			header: intelli.admin.lang.category, 
			dataIndex: 'parents',
			sortable: true,
			width: 250,
			renderer: function(value, p, record)
			{
				return String.format('<b><a href="controller.php?file=browse&id={0}">{1}</a></b>', record.json.category_id, value);
			}
		//MOD: Display payment ID
		/*},{
			header: 'Payment ID', 
			dataIndex: 'payment_id',
			sortable: true,
			width: 80
		},{
			header: 'Payment Status', 
			dataIndex: 'payment_status',
			sortable: true,
			width: 90*/
		}, {
			header: intelli.admin.lang.date,
			dataIndex: 'date',
			width: 130,
			sortable: true,
			editor: new Ext.ux.form.DateTimeField(
			{
				format: 'Y-m-d H:i:s',
				allowBlank: false
			})
		}, {
			header: intelli.admin.lang.status,
			dataIndex: 'status',
			sortable: true,
			width: 100,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				forceSelection: true,
				lazyRender: true,
				store: intelli.listings.statusesStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			}),
			renderer: function(value, metadata)
			{
				metadata.css = value;

				return value;
			}
		},{
			header: "",
			width: 40,
			dataIndex: 'edit',
			hideable: false,
			menuDisabled: true,
			align: 'center',
			renderer: function(value, metadata)
			{
				return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
			}
		},{
			header: "",
			width: 40,
			dataIndex: 'remove',
			hideable: false,
			menuDisabled: true,
			align: 'center',
			renderer: function(value, metadata)
			{
				return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.remove +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico.png" />';
			}
		},{
			header: intelli.config.duplicate_type,
			dataIndex: 'domain',
			hidden: true
		}]);

		return this.columnModel;
	},
	mySetupDataStore: function()
	{
		this.proxy = this.setupProxy();
		this.reader = this.setupReader();

		this.dataStore = new Ext.data.GroupingStore({
			remoteSort: true,
			proxy: this.proxy,
			reader: this.reader,
			groupField: intelli.config.duplicate_type
		});

		return this.dataStore;
	}
});

intelli.exGrid = Ext.extend(intelli.grid,
{
	model: null,
	constructor: function(config)
	{
		intelli.exGrid.superclass.constructor.apply(this, arguments);

		this.model = new intelli.exGModel({url: config.url});

		if ('duplicate' == config.mode)
		{
			this.dataStore = this.model.mySetupDataStore();
		}
		else
		{
			this.dataStore = this.model.setupDataStore();
		}

		this.columnModel = this.model.setupColumnModel();
		this.selectionModel = this.model.setupSelectionModel();

		this.dataStore.setDefaultSort('date', 'DESC');

		if ('duplicate' == config.mode)
		{
			this.view = new Ext.grid.GroupingView({
				forceFit: true,
				startCollapsed: true,
				groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
			});
		}
	},

	init: function()
	{
		this.plugins = [new Ext.ux.PanelResizer({
            minHeight: 300
		}), expander];

		this.title = intelli.admin.lang.listings;
		this.renderTo = 'box_listings';

		this.setupBaseParams();
		this.setupPagingPanel();
		this.setupGrid();

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
			autoScroll: true,

			items:[
				intelli.admin.lang.keywords + ':',
				{
					xtype: 'textfield',
					id: 'keywordsFilter',
					emptyText: intelli.admin.lang.keywords
				},
				' ',
				intelli.admin.lang.account + ':',
				{
					xtype: 'combo',
					store: account_ds,
					valueField: 'id',
					displayField: 'username',
					allowBlank: true,
					minChars: 1,
					typeAhead: false,
					loadingText: intelli.admin.lang.searching,
					pageSize: 10,
					hideTrigger: true,
					tpl: resultTpl,
					itemSelector: 'div.search-item',
					id: 'accountFilter'
				},
				intelli.admin.lang.status + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: intelli.listings.statusesStoreFilter,
					value: 'all',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'stsFilter'
				},
				' ',
				intelli.admin.lang.state + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: intelli.listings.statesStore,
					value: 'all',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'stFilter'
				},
				{
					text: intelli.admin.lang.filter,
					iconCls: 'search-grid-ico',
					id: 'fltBtn',
					handler: function()
					{
						var keywords = Ext.getCmp('keywordsFilter').getValue();
						var account = Ext.getCmp('accountFilter').getValue();
						var status = Ext.getCmp('stsFilter').getValue();
						var state = Ext.getCmp('stFilter').getValue();

						if('' != keywords || '' != account || '' != status || '' != state)
						{
							intelli.listings.oGrid.dataStore.baseParams = 
							{
								action: 'get',
								what: keywords,
								account: account,
								status: status,
								state: state
							};

							intelli.listings.oGrid.dataStore.reload();
						}
					}
				},
				'-',
				{
					text: intelli.admin.lang.reset,
					id: 'resetBtn',
					handler: function()
					{
						Ext.getCmp('keywordsFilter').reset();
						Ext.getCmp('accountFilter').reset();
						Ext.getCmp('stsFilter').setValue('all');
						Ext.getCmp('stFilter').setValue('all');

						intelli.listings.oGrid.dataStore.baseParams = 
						{
							action: 'get',
							what: '',
							account: '',
							status: '',
							state: ''
						};

						intelli.listings.oGrid.dataStore.reload();
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
			autoScroll: true,
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
					store: intelli.listings.pagingStore,
					value: '20',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'pgnPnl'
				},
				'-',
				intelli.admin.lang.status + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: intelli.listings.statusesStore,
					width: 80,
					value: 'active',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					disabled: true,
					id: 'statusCmb'
				},
				{
					text: intelli.admin.lang['do'],
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
				},
				'-',
				intelli.admin.lang.action + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: intelli.listings.actionsStore,
					width: 120,
					value: '',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					disabled: true,
					id: 'actCmb'
				},
				{
					text: intelli.admin.lang['do'],
					disabled: true,
					iconCls: 'go-grid-ico',
					id: 'actBtn'
				}
			]
		});
	},
	setupBaseParams: function()
	{
		this.dataStore.baseParams = 
		{
			action: 'get',
			what: (intelli.urlVal('quick_search')) ? intelli.urlVal('quick_search').replace('+',' ') : '',
			status: (intelli.urlVal('status')) ? intelli.urlVal('status') : '',
			type: (intelli.urlVal('type')) ? intelli.urlVal('type') : '',
			state: (intelli.urlVal('state')) ? intelli.urlVal('state') : '',
			mode: (intelli.urlVal('mode')) ? intelli.urlVal('mode') : ''
		};
	},

	setEvents: function()
	{
		/* Edit fields */
		intelli.listings.oGrid.grid.on('afteredit', function(editEvent)
		{
			var value = 'date' == editEvent.field ? editEvent.value.format("Y-m-d H:i:s") : editEvent.value;
			value = ('account_id' == editEvent.field && '' == value) ? '0' : value;

			if('' != value)
			{
				Ext.Ajax.request(
				{
					url: intelli.listings.vUrl,
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
			}
		});

		/* Edit and remove click */
		intelli.listings.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				var status = intelli.urlVal('status');
				var url = 'controller.php?file=suggest-listing&do=edit&id='+ record.json.id;

				url += status ? '&status=' + status : '';

				intelli.listings.oGrid.saveGridState();

				window.location = url;
			}

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_listing,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if ('yes' == btn)
						{
							var reasonWindow = new Ext.Window(
							{
								title: intelli.admin.lang.remove_reason,
								width : 550,
								height : 220,
								contentEl : 'remove_reason',
								modal: true,
								bodyStyle: 
								{
									padding: '5px'
								},
								closeAction : 'hide',
								listeners:
								{
									'afterrender': function(cmp)
									{
										$("#remove_reason").css("display", "block");
									}
								},
								buttons: [
								{
									text : intelli.admin.lang.ok,
									handler: function()
									{
										var reason_text = $("#remove_reason_text").val();

										$("#remove_reason_text").val('');

										Ext.Ajax.request(
										{
											url: intelli.listings.vUrl,
											method: 'POST',
											params:
											{
												action: 'remove',
												'ids[]': record.json.id,
												reason: reason_text
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
												Ext.getCmp('actBtn').disable();
												Ext.getCmp('actCmb').disable();
												Ext.getCmp('removeBtn').disable();

												grid.getStore().reload();
											}
										});

										reasonWindow.hide();
									}
								}]
							});

							reasonWindow.show();
						}
					}
				});
			}
		});

		/* Enable disable functionality buttons */
		intelli.listings.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('actBtn').enable();
			Ext.getCmp('actCmb').enable();
			Ext.getCmp('removeBtn').enable();
		});

		intelli.listings.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('statusCmb').disable();
				Ext.getCmp('goBtn').disable();
				Ext.getCmp('actBtn').disable();
				Ext.getCmp('actCmb').disable();
				Ext.getCmp('removeBtn').disable();
			}
		});

		/* Go button action */
		Ext.getCmp('goBtn').on('click', function()
		{
			var rows = intelli.listings.oGrid.grid.getSelectionModel().getSelections();
			var status = Ext.getCmp('statusCmb').getValue();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Ajax.request(
			{
				url: intelli.listings.vUrl,
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
					intelli.listings.oGrid.grid.getStore().reload();

					var response = Ext.decode(data.responseText);
					var type = response.error ? 'error' : 'notif';
						
					intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});
				}
			});
		});

		/* remove button action */
		Ext.getCmp('removeBtn').on('click', function()
		{
			var rows = intelli.listings.oGrid.grid.getSelectionModel().getSelections();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Msg.show(
			{
				title: intelli.admin.lang.confirm,
				msg: (ids.length > 1) ? intelli.admin.lang.are_you_sure_to_delete_selected_listings : intelli.admin.lang.are_you_sure_to_delete_this_listing,
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				fn: function(btn)
				{
					if('yes' == btn)
					{
						var reasonWindow = new Ext.Window(
						{
							title: intelli.admin.lang.remove_reason,
							width : 550,
							height : 220,
							contentEl : 'remove_reason',
							modal: true,
							bodyStyle: 
							{
								padding: '5px'
							},
							closeAction : 'hide',
							listeners:
							{
								'afterrender': function(cmp)
								{
									$("#remove_reason").css("display", "block");
								}
							},
							buttons: [
							{
								text : intelli.admin.lang.ok,
								handler: function()
								{
									var reason_text = $("#remove_reason_text").val();

									$("#remove_reason_text").val('');

									Ext.Ajax.request(
									{
										url: intelli.listings.vUrl,
										method: 'POST',
										params:
										{
											action: 'remove',
											'ids[]': ids,
											reason: reason_text
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

											intelli.listings.oGrid.grid.getStore().reload();

											Ext.getCmp('statusCmb').disable();
											Ext.getCmp('goBtn').disable();
											Ext.getCmp('actBtn').disable();
											Ext.getCmp('actCmb').disable();
											Ext.getCmp('removeBtn').disable();
										}
									});

									reasonWindow.hide();
								}
							}]
						});

						reasonWindow.show();
					}
				}
			});
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.listings.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.listings.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.listings.oGrid.grid.getStore().reload();
		});

		/* Do action event */
		Ext.getCmp('actBtn').on('click', function()
		{
			var action = Ext.getCmp('actCmb').getValue();
			var rows = intelli.listings.oGrid.grid.getSelectionModel().getSelections();
			var ids = new Array();
			var listings = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
				listings[i] = rows[i].json.title;
			}

			if('send_email' == action)
			{
				var emailWindow = new Ext.Window(
				{
					title: intelli.admin.lang.email,
					contentEl : 'email_templates',
					modal: true,
					autoHeight: true,
					bodyStyle: 
					{
						padding: '5px'
					},
					closeAction : 'hide',
					buttons: [
					{
						text : intelli.admin.lang.send,
						handler: function()
						{
							var subject = $("#subject").val();
							var body = CKEDITOR.instances.body.getData();
							var custom_email = $('#custom_email').val();

							if (!subject && !body)
							{
								Ext.MessageBox.alert(intelli.admin.lang.fill_subject_body);
							}
							else
							{
								Ext.Ajax.request(
								{
									url: intelli.listings.vUrl,
									method: 'POST',
									params:
									{
										action 	: 	'send_email',
										'ids[]' : 	ids,
										subject : 	subject,
										body 	: 	body
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

										intelli.listings.oGrid.grid.getStore().reload();

										Ext.getCmp('statusCmb').disable();
										Ext.getCmp('goBtn').disable();
										Ext.getCmp('actBtn').disable();
										Ext.getCmp('actCmb').disable();
										Ext.getCmp('removeBtn').disable();
									}
								});

								emailWindow.hide();
							}
						}
					},{
						text : intelli.admin.lang.cancel,
						handler: function()
						{
							emailWindow.hide();
						}
					}]
				});
			
				emailWindow.show();
				return false;
			}

			if (intelli.inArray(action, ['copy', 'cross', 'move']))
			{
				intelli.listings.vTree = new Ext.tree.TreePanel({
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
				new Ext.tree.TreeSorter(intelli.listings.vTree, {folderSort: false});
				 
				// set the root node
				var root = new Ext.tree.AsyncTreeNode({
					text: 'ROOT', 
					id: '0'
				});
				intelli.listings.vTree.setRootNode(root);
					
				root.expand();

				intelli.listings.vWindow = new Ext.Window(
				{
					title: intelli.admin.lang.tree,
					width : 400,
					height : 450,
					modal: true,
					autoScroll: true,
					closeAction : 'hide',
					items: [intelli.listings.vTree],
					buttons: [
					{
						text : intelli.admin.lang.ok,
						handler: function()
						{
							var category = intelli.listings.vTree.getSelectionModel().getSelectedNode();

							var msg = intelli.admin.lang.action_confirm;

							msg = msg.replace("{action}", action);
							msg = msg.replace("{listings}", listings.join(', '));
							msg = msg.replace("{category}", category.text);

							Ext.Msg.show(
							{
								title: intelli.admin.lang.confirm,
								msg: msg,
								buttons: Ext.Msg.YESNO,
								icon: Ext.Msg.QUESTION,
								fn: function(btn)
								{
									if('yes' == btn)
									{
										Ext.Ajax.request(
										{
											url: intelli.listings.vUrl,
											method: 'POST',
											params:
											{
												action: action,
												'ids[]': ids,
												category: category.id
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

												intelli.listings.oGrid.grid.getStore().reload();
												intelli.listings.vWindow.hide();
											}
										});
									}
								}
							});
						}
					},{
						text : intelli.admin.lang.cancel,
						handler : function()
						{
							intelli.listings.vWindow.hide();
						}
					}]
				});

				intelli.listings.vWindow.show();
			}
			else
			{
				Ext.Ajax.request(
				{
					url: intelli.listings.vUrl,
					method: 'POST',
					params:
					{
						action: action,
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
					}
				});
			}
		});
	}
});

Ext.onReady(function()
{
	var mode = 'listings';

	if ('duplicate' == intelli.urlVal('mode'))
	{
		mode = 'duplicate';
	}

	if(Ext.get('box_listings'))
	{
		intelli.listings.oGrid = new intelli.exGrid({url: intelli.listings.vUrl, mode: mode});

		/* Initialization grid */
		intelli.listings.oGrid.init();

		if(intelli.urlVal('status'))
		{
			Ext.getCmp('stsFilter').setValue(intelli.urlVal('status'));
		}
		
		if(intelli.urlVal('state'))
		{
			Ext.getCmp('stFilter').setValue(intelli.urlVal('state'));
		}

		if(intelli.urlVal('quick_search'))
		{
			Ext.getCmp('keywordsFilter').setValue(intelli.urlVal('quick_search').replace('+',' '));
		}
	}
});

$.fn.extend(
{
	insertAtCaret: function(myValue)
	{
		var obj;

		if( typeof this[0].name !='undefined' ) obj = this[0];
        else obj = this;

		if ($.browser.msie)
		{
			obj.focus();
			sel = document.selection.createRange();
			sel.text = myValue;
			obj.focus();
		}
		else if ($.browser.mozilla || $.browser.webkit)
		{
			var startPos = obj.selectionStart;
			var endPos = obj.selectionEnd;
			var scrollTop = obj.scrollTop;
			obj.value = obj.value.substring(0, startPos)+myValue+obj.value.substring(endPos,obj.value.length);
			obj.focus();
			obj.selectionStart = startPos + myValue.length;
			obj.selectionEnd = startPos + myValue.length;
			obj.scrollTop = scrollTop;
		}
		else
		{
			obj.value += myValue;
			obj.focus();
		}
	}
});

$(function()
{
	var tags_win = new Ext.Window(
	{
		title: intelli.admin.lang.email_templates_tags,
		width : 'auto',
		height : 'auto',
		modal: false,
		autoScroll: true,
		closeAction : 'hide',
		contentEl: 'template_tags',
		buttons: [
		{
			text : intelli.admin.lang.close,
			handler : function()
			{
				tags_win.hide();
			}
		}]
	});

	$('#tags').click(function()
	{
		tags_win.show();

		return false;
	});

	$('input[name="template"]').change(function()
	{
		var template = $('#tpl').val().replace(/tpl_/, '').replace(/_subject/, '');
		var val = $(this).val();

		if('' != template)
		{
			$.get('controller.php', {file: 'email-templates', tmpl: template, val: val, action: 'setconfig'}, function(data)
			{

			});
		}

		return false;
	});

	$('#tpl, #lang').bind('change', function()
	{
		var id = $('#tpl').val();
		var code = $('#lang').val();
		var tpl = $('#tpl option:selected');

		if(CKEDITOR.instances.body)
		{
			CKEDITOR.instances.body.destroy();
		}

		if (!id)
		{
			$('#subject').val('');
			
			if ('html' == intelli.config.mimetype)
			{
				intelli.ckeditor('body', {toolbar: 'User'});
				CKEDITOR.instances.body.setData('');
			}
			else
			{
				$('#body').val('');
			}
			return false;
		}

		$.get('controller.php', {file: 'email-templates', id: id, code: code, action: 'get-tpl'}, function(data)
		{
			$('#subject').val(data['subject']);
			
			if ('html' == intelli.config.mimetype)
			{
				intelli.ckeditor('body', {toolbar: 'User'});
				CKEDITOR.instances.body.setData(data['body']);
			}
			else
			{
				$('#body').val(data['body']);
			}

			var trigger = (1 == data.config) ? 'setOn' : 'setOff';

			$('#box-template').trigger(trigger);

		}, 'json');
	});

	$('#tpl_form').ajaxForm({
		success: showResponse,
		beforeSerialize: showRequest
	});

	$('a.email_tags').click(function()
	{
		if('html' == intelli.config.mimetype)
		{
			CKEDITOR.instances.body.insertHtml($(this).text());
		}
		else
		{
			$('#body').insertAtCaret($(this).text());
		}

		return false;
	});

});

function showRequest()
{
	if('' == $('#tpl').val())
	{
		return false;
	}

	if ("object" == typeof CKEDITOR.instances.body)
	{
		CKEDITOR.instances.body.updateElement();
	}

	$('#tpl_form').ajaxLoader();
}

function showResponse(data)
{
	$('#tpl_form').ajaxLoaderRemove();
	if (data == 'ok')
	{
		intelli.admin.notifBox({msg: intelli.admin.lang.changes_saved, type: 'notif', autohide: true});
	}
}