intelli.plugins = function()
{
	var vUrl = 'controller.php?file=plugins';

	return {
		oGridAvailable: null,
		oGridInstalled: null,
		vUrl: vUrl,
		vWinReadme: new Array(),
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

// define Installed Plugins grid
intelli.exGModelInstalled = Ext.extend(intelli.gmodel,
{
	constructor: function(config)
	{
		intelli.exGModelInstalled.superclass.constructor.apply(this, arguments);
	},

	setupReader: function()
	{
		this.record = Ext.data.Record.create([
			{name: 'title', mapping: 'title'},
			{name: 'description', mapping: 'description'},
			{name: 'version', mapping: 'version'},
			{name: 'status', mapping: 'status'},
			{name: 'upgrade', mapping: 'upgrade'},
			{name: 'config', mapping: 'config'},
			{name: 'manage', mapping: 'manage'},
			{name: 'uninstall', mapping: 'uninstall'}
		]);

		this.reader = new Ext.data.JsonReader(
			{
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
				header: "",
				dataIndex: 'readme_installed',
				hideable: false,
				menuDisabled: true,
				width: 40,
				align: 'center',
				renderer: function(value, metadata)
				{
					return '<img class="grid_action" title="'+ intelli.admin.lang.readme +'" alt="'+ intelli.admin.lang.readme +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/readme-grid-ico.png" />';
				}
			}, {
				header: intelli.admin.lang.title,
				dataIndex: 'title',
				sortable: true,
				width: 180
			}, {
				header: intelli.admin.lang.version,
				dataIndex: 'version',
				width: 50
			}, {
				header: intelli.admin.lang.description,
				dataIndex: 'description',
				id: 'description',
				width: 250
			}, {
				header: intelli.admin.lang.status,
				dataIndex: 'status',
				sortable: true,
				width: 100,
				editor: new Ext.form.ComboBox(
					{
						typeAhead: true,
						triggerAction: 'all',
						editable: false,
						lazyRender: true,
						store: intelli.plugins.statusesStore,
						displayField: 'value',
						valueField: 'display',
						mode: 'local'
					}
				),
				renderer: function(value, metadata)
				{
					metadata.css = value;

					return value;
				}
			}, {
				header: "",
				dataIndex: 'upgrade',
				hideable: false,
				menuDisabled: true,
				width: 40,
				align: 'center',
				renderer: function(value, metadata)
				{
					if ('' != value)
					{
						var label = intelli.admin.lang.upgrade_plugin.replace('{plugin}', value);

						return '<img class="grid_action" title="'+ label +'" alt="'+ label +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/upgrade-grid-ico.png" />';
					}
				}
			}, {
				header: "",
				dataIndex: 'config',
				hideable: false,
				menuDisabled: true,
				width: 40,
				align: 'center',
				renderer: function(value, metadata)
				{
					if ('' != value)
					{
						// zero index - group of config
						// first index - name of config
						var config = value.split('|');
						var label = intelli.admin.lang.go_to_config.replace('{plugin}', config[1]);

						return '<a href="controller.php?file=configuration&group=' + config[0] +'#' + config[1] +'"><img class="grid_action" title="'+ label +'" alt="'+ label +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/config-grid-ico.png" /></a>';
					}
				}
			}, {
				header: "",
				dataIndex: 'manage',
				hideable: false,
				menuDisabled: true,
				width: 40,
				align: 'center',
				renderer: function(value, metadata)
				{
					if (value)
					{
						var label = intelli.admin.lang.go_to_manage.replace('{plugin}', value);

						return '<a href="controller.php?plugin=' + value +'"><img class="grid_action" title="'+ label +'" alt="'+ label +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/manage-grid-ico.png" /></a>';
					}
				}
			}, {
				header: "",
				width: 40,
				dataIndex: 'uninstall',
				hideable: false,
				menuDisabled: true,
				align: 'center',
				renderer: function(value, metadata)
				{
					return '<img class="grid_action" title="'+ intelli.admin.lang.uninstall +'" alt="'+ intelli.admin.lang.uninstall +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/uninstall-grid-ico.png" />';
				}
			}]);

		return this.columnModel;
	}
});

intelli.exGridInstalled = Ext.extend(intelli.grid,
{
	model: null,

	constructor: function(config)
	{
		intelli.exGridInstalled.superclass.constructor.apply(this, arguments);

		this.model = new intelli.exGModelInstalled({url: config.url});

		this.dataStore = this.model.setupDataStore();
		this.columnModel = this.model.setupColumnModel();
		this.selectionModel = this.model.setupSelectionModel();

		this.dataStore.setDefaultSort('title', 'ASC');
	},

	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
			minHeight: 500
		});

		this.setupBaseParams();
		this.setupPagingPanel();
		this.setupGrid();

		this.setEvents();

		this.grid.autoExpandColumn = 'description';

		this.grid.setTitle(intelli.admin.lang.installed_plugins);

		this.loadData();
	},

	setupPagingPanel: function()
	{
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
					editable: false,
					lazyRender: true,
					width: 80,
					store: intelli.plugins.pagingStore,
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
					editable: false,
					lazyRender: true,
					store: intelli.plugins.statusesStore,
					value: 'active',
					displayField: 'value',
					valueField: 'display',
					mode: 'local',
					disabled: true,
					id: 'statusCmb'
				},
				{
					text: intelli.admin.lang['do'],
					disabled: true,
					iconCls: 'go-grid-ico',
					id: 'goBtn'
				}
			]
		});
	},

	setupBaseParams: function()
	{
		this.dataStore.baseParams = {action: 'installed'};
	},

	setEvents: function()
	{
		/* Install button event */
		intelli.plugins.oGridAvailable.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);
			var type = Ext.getCmp('typeFilter').getValue();

			if ('install' == fieldName)
			{
				Ext.Ajax.request(
				{
					url: intelli.plugins.vUrl,
					method: 'POST',
					params:
					{
						prevent_csrf: $("#box_plugins input[name='prevent_csrf']").val(),
						action: 'install',
						type: type,
						plugin: record.json.file
					},

					failure: function()
					{
						Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
					},

					success: function(data)
					{
						intelli.plugins.oGridInstalled.dataStore.reload();
						intelli.plugins.oGridAvailable.dataStore.reload();

						var response = Ext.decode(data.responseText);
						var type = response.error ? 'error' : 'notif';

						intelli.admin.notifBox({msg: response.msg, type: type, autohide: false});

						intelli.admin.refreshAdminMenu();
					}
				});
			}

			if ('readme' == fieldName)
			{
				Ext.Ajax.request(
				{
					url: intelli.plugins.vUrl,
					method: 'POST',
					params:
					{
						action: 'getdoctabs',
						plugin: record.json.file,
						type: type
					},

					failure: function()
					{
						Ext.MessageBox.alert(intelli.admin.lang.error_while_doc_tabs);
					},

					success: function(data)
					{
						var data = Ext.decode(data.responseText);
						var doc_tabs = data.doc_tabs;
						var plugin_info = data.plugin_info;

						if (null != doc_tabs)
						{
							var plugin_tabs = new Ext.TabPanel(
							{
								region: 'center',
								bodyStyle: 'padding: 5px;',
								activeTab: 0,
								defaults:
								{
									autoScroll: true
								},
								items: doc_tabs
							});

							var plugin_info = new Ext.Panel(
							{
								region: 'east',
								split: true,
								minWidth: 200,
								collapsible: true,
								html: plugin_info,
								bodyStyle: 'padding: 5px;'
							});

							var win = new Ext.Window(
							{
								title: intelli.admin.lang.plugin_documentation,
								closable: true,
								width: 800,
								height: 550,
								border: false,
								plain: true,
								layout: 'border',
								items: [plugin_tabs, plugin_info]
							});

							win.show();
						}
						else
						{
							Ext.Msg.show(
							{
								title: intelli.admin.lang.error,
								msg: intelli.admin.lang.doc_plugin_not_available,
								buttons: Ext.Msg.OK,
								icon: Ext.Msg.ERROR
							});
						}
					}
				});
			}
		});

		/* Uninstall / readme button event */
		intelli.plugins.oGridInstalled.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if ('upgrade' == fieldName && '' != data)
			{
				Ext.Ajax.request(
				{
					url: intelli.plugins.vUrl,
					method: 'POST',
					params:
					{
						prevent_csrf: $("#box_plugins input[name='prevent_csrf']").val(),
						action: 'install',
						plugin: data
					},

					failure: function()
					{
						Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
					},

					success: function(data)
					{
						intelli.plugins.oGridInstalled.dataStore.reload();
						intelli.plugins.oGridAvailable.dataStore.reload();

						var response = Ext.decode(data.responseText);
						var type = response.error ? 'error' : 'notif';

						intelli.admin.notifBox({msg: response.msg, type: type, autohide: false});

						intelli.admin.refreshAdminMenu();
					}
				});
			}

			if ('readme_installed' == fieldName)
			{
				Ext.Ajax.request(
				{
					url: intelli.plugins.vUrl,
					method: 'POST',
					params:
					{
						action: 'getdoctabs',
						plugin: record.json.name
					},

					failure: function()
					{
						Ext.MessageBox.alert(intelli.admin.lang.error_while_doc_tabs);
					},

					success: function(data)
					{
						var data = Ext.decode(data.responseText);
						var doc_tabs = data.doc_tabs;
						var plugin_info = data.plugin_info;

						if (null != doc_tabs)
						{
							var plugin_tabs = new Ext.TabPanel({
								region: 'center',
								bodyStyle: 'padding: 5px;',
								activeTab: 0,
								defaults: {
									autoScroll: true
								},
								items: doc_tabs
							});

							var plugin_info = new Ext.Panel({
								region: 'east',
								split: true,
								minWidth: 200,
								collapsible: true,
								html: plugin_info,
								bodyStyle: 'padding: 5px;'
							});

							var win = new Ext.Window({
								title: intelli.admin.lang.plugin_documentation,
								closable: true,
								width: 800,
								height: 550,
								border: false,
								plain: true,
								layout: 'border',
								items: [plugin_tabs, plugin_info]
							});

							win.show();
						}
						else
						{
							Ext.Msg.show(
							{
								title: intelli.admin.lang.error,
								msg: intelli.admin.lang.doc_plugin_not_available,
								buttons: Ext.Msg.OK,
								icon: Ext.Msg.ERROR
							});
						}
					}
				});
			}

			if ('uninstall' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_uninstall_selected_plugin,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if ('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.plugins.vUrl,
								method: 'POST',
								params:
								{
									prevent_csrf: $("#box_plugins input[name='prevent_csrf']").val(),
									action: 'uninstall',
									'plugins[]': record.json.name
								},

								failure: function()
								{
									Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
								},

								success: function(data)
								{
									intelli.plugins.oGridInstalled.dataStore.reload();
									intelli.plugins.oGridAvailable.dataStore.reload();

									var response = Ext.decode(data.responseText);
									var type = response.error ? 'error' : 'notif';

									intelli.admin.notifBox({msg: response.msg, type: type, autohide: false});

									intelli.admin.refreshAdminMenu();
								}
							});
						}
					}
				});
			}
		});

		/* Edit fields */
		intelli.plugins.oGridInstalled.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.plugins.vUrl,
				method: 'POST',
				params:
				{
					action: 'update',
					plugin: editEvent.record.json.name,
					field: editEvent.field,
					value: editEvent.value
				},

				failure: function()
				{
					Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
				},

				success: function()
				{
					intelli.plugins.oGridInstalled.dataStore.reload();
				}
			});
		});

		/* Enable disable functionality buttons */
		intelli.plugins.oGridInstalled.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
		});

		intelli.plugins.oGridInstalled.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if (0 == sm.getCount())
			{
				Ext.getCmp('statusCmb').disable();
				Ext.getCmp('goBtn').disable();
			}
		});

		/* Go button action */
		Ext.getCmp('goBtn').on('click', function()
		{
			var rows = intelli.plugins.oGridInstalled.grid.getSelectionModel().getSelections();
			var status = Ext.getCmp('statusCmb').getValue();
			var plugins = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				plugins[i] = rows[i].json.name;
			}

			Ext.Ajax.request(
			{
				url: intelli.plugins.vUrl,
				method: 'POST',
				params:
				{
					action: 'update',
					'plugin[]': plugins,
					field: 'status',
					value: status
				},

				failure: function()
				{
					Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
				},

				success: function(data)
				{
					intelli.plugins.oGridInstalled.grid.getStore().reload();

					var response = Ext.decode(data.responseText);
					var type = response.error ? 'error' : 'notif';

					intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});
				}
			});
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.plugins.oGridInstalled.grid.getStore().lastOptions.params.limit = new_value;
			intelli.plugins.oGridInstalled.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.plugins.oGridInstalled.grid.getStore().reload();
		});
	}
});

// define Available Plugins grid
intelli.exGModelAvailable = Ext.extend(intelli.gmodel,
{
	constructor: function(config)
	{
		intelli.exGModelAvailable.superclass.constructor.apply(this, arguments);
	},

	setupReader: function()
	{
		this.record = Ext.data.Record.create([
			{name: 'title', mapping: 'title'},
			{name: 'version', mapping: 'version'},
			{name: 'description', mapping: 'description'},
			{name: 'author', mapping: 'author'},
			{name: 'date', mapping: 'date'},
			{name: 'install', mapping: 'install'}
		]);

		this.reader = new Ext.data.JsonReader(
			{
				root: 'data', totalProperty: 'total', id: 'id'
			},
			this.record
		);

		return this.reader;
	},

	setupColumnModel: function()
	{
		this.columnModel = new Ext.grid.ColumnModel([
			{
				header: "",
				width: 40,
				dataIndex: 'install',
				hideable: false,
				menuDisabled: true,
				align: 'center',
				renderer: function(value, metadata)
				{
					return '<img class="grid_action" title="'+ intelli.admin.lang.install +'" alt="'+ intelli.admin.lang.install +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/install-grid-ico.png" />';
				}
			}, {
				header: "",
				dataIndex: 'readme',
				hideable: false,
				menuDisabled: true,
				width: 40,
				align: 'center',
				renderer: function(value, metadata)
				{
					return '<img class="grid_action" title="'+ intelli.admin.lang.readme +'" alt="'+ intelli.admin.lang.readme +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/readme-grid-ico.png" />';
				}
			}, {
				header: intelli.admin.lang.title,
				dataIndex: 'title',
				sortable: true,
				width: 200
			}, {
				header: intelli.admin.lang.version,
				dataIndex: 'version',
				width: 50
			}, {
				header: intelli.admin.lang.description,
				id: 'available_description',
				dataIndex: 'description',
				width: 200
			}, {
				header: intelli.admin.lang.author,
				dataIndex: 'author',
				width: 120
			}, {
				header: intelli.admin.lang.date,
				dataIndex: 'date',
				width: 100
			}]);

		return this.columnModel;
	}
});

intelli.exGridAvailable = Ext.extend(intelli.grid,
{
	model: null,

	constructor: function(config)
	{
		intelli.exGridAvailable.superclass.constructor.apply(this, arguments);

		this.model = new intelli.exGModelAvailable({url: config.url});

		this.dataStore = this.model.setupDataStore();
		this.columnModel = this.model.setupColumnModel();
		this.selectionModel = this.model.setupSelectionModel();

		this.dataStore.setDefaultSort('title', 'ASC');
	},

	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
			minHeight: 500
		});

		this.setupBaseParams();
		this.setupPagingPanel();
		this.setupGrid();

		this.grid.autoExpandColumn = 'available_description';

		this.grid.setTitle(intelli.admin.lang.available_plugins);

		this.loadData();
	},

	setupPagingPanel: function()
	{
		this.topToolbar = new Ext.Toolbar(
		{
			items: [
				_t('type') + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					store: new Ext.data.SimpleStore(
					{
						fields: ['value', 'display'],
						data : [['local', 'Local'], ['remote', 'Remote']]
					}),
					value: 'local',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'typeFilter'
				},
				'-',
				{
					xtype: 'button',
					text: 'Search',
					iconCls: 'search-grid-ico',
					id: 'search2',
					handler: function()
					{
						var type = Ext.getCmp('typeFilter').getValue();

						if ('' != type)
						{
							if ('remote' == type)
							{
								intelli.plugins.oGridAvailable.grid.getColumnModel().setHidden(5, true);
							}
							else
							{
								intelli.plugins.oGridAvailable.grid.getColumnModel().setHidden(5, false);
							}

							intelli.plugins.oGridAvailable.dataStore.baseParams =
							{
								action: 'available',
								type: type
							};

							intelli.plugins.oGridAvailable.dataStore.reload();
						}
					}
				},
				'-',
				{
					text: _t('reset'),
					handler: function()
					{
						Ext.getCmp('typeFilter').setValue('local');

						intelli.plugins.oGridAvailable.dataStore.baseParams = {
							action: 'available',
							type: 'local'
						};

						intelli.plugins.oGridAvailable.dataStore.reload();
					}
				}
			]
		});
	},

	setupBaseParams: function()
	{
		this.dataStore.baseParams = {action: 'available', type: 'local'};
	}
});

Ext.onReady(function()
{
	intelli.plugins.oGridAvailable = new intelli.exGridAvailable({url: intelli.plugins.vUrl});
	intelli.plugins.oGridAvailable.init();

	intelli.plugins.oGridInstalled = new intelli.exGridInstalled({url: intelli.plugins.vUrl});
	intelli.plugins.oGridInstalled.init();

	this.plugins = new Ext.ux.PanelResizer({
		minHeight: 500
	});

	var tabPanels = new Ext.TabPanel(
	{
		border: true,
		activeTab: 0,
		renderTo: 'box_plugins',
		autoWidth: true,
		autoHeight: true,
		items: [intelli.plugins.oGridInstalled.grid, intelli.plugins.oGridAvailable.grid]
	});
});