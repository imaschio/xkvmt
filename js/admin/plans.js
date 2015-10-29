intelli.plans = function()
{
	var vUrl = 'controller.php?file=plans';

	return {
		oGrid: null,
		vUrl: vUrl,
		statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['active', 'active'],['inactive', 'inactive']]
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
		itemsStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data :
				[['all', intelli.admin.lang.all],
				['listing', intelli.admin.lang.listings],
				['account', intelli.admin.lang.accounts]]
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
			{name: 'cost', mapping: 'cost'},
			{name: 'period', mapping: 'period'},
			{name: 'lang', mapping: 'lang'},
			{name: 'item', mapping: 'item'},
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
			width: 250
		},{
			header: intelli.admin.lang.cost,
			dataIndex: 'cost', 
			width: 120,
			renderer: function(value, p, record)
			{
				if (record.json.recurring == '1')
				{
					return String.format('{0}{1} ' + intelli.admin.lang.each + ' {2} ' + record.json.unit, intelli.config.currency_symbol, value, record.json.duration);
				}
				else
				{
					return String.format('{0}{1}', intelli.config.currency_symbol, value);
				}
			},
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.days, 
			dataIndex: 'period', 
			width: 70,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.language, 
			dataIndex: 'lang',
			hidden: true,
			width: 100
		},{
			header: intelli.admin.lang.item,
			dataIndex: 'item',
			width: 100,
			sortable: true
		},{
			header: intelli.admin.lang.order, 
			dataIndex: 'order',
			width: 100,
			editor: new Ext.form.TextField({
				allowBlank: false
			}),
			sortable: true
		},{
			header: intelli.admin.lang.status, 
			dataIndex: 'status',
			width: 100,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				forceSelection: true,
				lazyRender: true,
				store: intelli.plans.statusesStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			}),
			renderer: function(value, metadata)
			{
				metadata.css = value;

				return value;
			},
			sortable: true
		},{
			header: "", 
			dataIndex: 'edit',
			width: 40,
			hideable: false,
			menuDisabled: true,
			align: 'center',
			renderer: function(value, metadata)
			{
				return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
			}
		},{
			header: "",
			dataIndex: 'remove',
			width: 40,
			hideable: false,
			menuDisabled: true,
			align: 'center',
			renderer: function(value, metadata)
			{
				return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.remove +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico.png" />';
			}
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

		this.title = intelli.admin.lang.plans;
		this.renderTo = 'box_plans';

		this.setupBaseParams();
		this.setupPagingPanel();
		this.setupGrid();

		this.setEvents();

		this.grid.autoExpandColumn = 1;

		this.loadData();
	},
	setupPagingPanel: function()
	{
		this.topToolbar = new Ext.Toolbar(
		{
			items:[
				intelli.admin.lang.item + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: intelli.plans.itemsStore,
					value: 'all',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'itemFilter'
				},
				' ',
				intelli.admin.lang.status + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: intelli.plans.statusesStoreFilter,
					value: 'all',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'stsFilter'
				},
				{
					text: intelli.admin.lang.filter,
					iconCls: 'search-grid-ico',
					id: 'fltBtn',
					handler: function()
					{
						var status = Ext.getCmp('stsFilter').getValue();
						var item = Ext.getCmp('itemFilter').getValue();

						if('' != status || '' != item)
						{
							intelli.plans.oGrid.dataStore.baseParams =
							{
								action: 'get',
								status: status,
								item: item
							};

							intelli.plans.oGrid.dataStore.reload();
						}
					}
				},
				'-',
				{
					text: intelli.admin.lang.reset,
					id: 'resetBtn',
					handler: function()
					{
						Ext.getCmp('stsFilter').setValue('all');
						Ext.getCmp('itemFilter').setValue('all');

						intelli.plans.oGrid.dataStore.baseParams =
						{
							action: 'get',
							status: '',
							item: ''
						};

						intelli.plans.oGrid.dataStore.reload();
					}
				}
			]
		});

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
					store: intelli.plans.pagingStore,
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
						var rows = intelli.plans.oGrid.grid.getSelectionModel().getSelections();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].json.id;
						}

						Ext.Msg.show(
						{
							title: intelli.admin.lang.confirm,
							msg: (ids.length > 1) ? intelli.admin.lang.are_you_sure_to_delete_selected_plans : intelli.admin.lang.are_you_sure_to_delete_this_plan,
							buttons: Ext.Msg.YESNO,
							icon: Ext.Msg.QUESTION,
							fn: function(btn)
							{
								if ('yes' == btn)
								{
									Ext.Ajax.request(
									{
										url: intelli.plans.vUrl,
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

											intelli.plans.oGrid.grid.getStore().reload();

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
		var item = intelli.urlVal('item');

		item = ('' != item && intelli.inArray(item, ['account', 'listing'])) ? item : '';
		var status = (intelli.urlVal('status')) ? intelli.urlVal('status') : '';

		this.dataStore.baseParams = {action: 'get', item: item, status: status};
	},

	setEvents: function()
	{
		// Edit fields
		intelli.plans.oGrid.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.plans.vUrl,
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

		/* Remove click */
		intelli.plans.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				intelli.plans.oGrid.saveGridState();

				window.location = 'controller.php?file=plans&do=edit&id='+ record.json.id;
			}

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_plan,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.plans.vUrl,
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
		intelli.plans.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('removeBtn').enable();
		});

		intelli.plans.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('removeBtn').disable();
			}
		});

		/* remove button action */
		Ext.getCmp('removeBtn').on('click', function()
		{
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.plans.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.plans.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.plans.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if (Ext.get('box_plans'))
	{
		intelli.plans.oGrid = new intelli.exGrid({url: intelli.plans.vUrl});

		/* Initialization grid */
		intelli.plans.oGrid.init();

		if(intelli.urlVal('status'))
		{
			Ext.getCmp('stsFilter').setValue(intelli.urlVal('status'));
		}

		if(intelli.urlVal('item'))
		{
			Ext.getCmp('itemFilter').setValue(intelli.urlVal('item'));
		}
	}

	if(Ext.get('tree'))
	{
		var categories = Ext.get("categories").getValue();

		categories = ('' != categories) ? categories.split('|') : new Array();

		var tree = new Ext.tree.TreePanel({
			el: 'tree',
			animate: true, 
			autoScroll: true,
			width: 'auto',
			height: 'auto',
			border: true,
			stateful: true,
			getState: function()
			{
				var state = new Object();
				var expanded = [];
				var checked = [];

				for(var id in this.nodeHash)
				{
					var node = this.nodeHash[id];

					if(node.expanded)
					{
						expanded.push(node.getPath());

						if(!node.isRoot)
						{
							expanded.remove(node.parentNode.getPath());
						}
					}

					if(node.attributes.checked)
					{
						checked.push(node.attributes.id);
					}
				}

				state = {
					"expanded": expanded,
					"checked": checked
				};

				return state;
			},
			applyState : function(state, config)
			{
				if(state.expanded && state.expanded.length > 0)
				{
					for(var i = 0; i < state.expanded.length; i++)
					{
						var path = state.expanded[i];

						if(path)
						{
							this.addListener('render', function()
							{
								this.expandPath(path);
							});
						}
					}
				}

				if(state.checked && state.checked.length > 0)
				{
					this.addListener('append', function(tree, parent, node)
					{
						if(intelli.inArray(node.attributes.id, state.checked))
						{
							node.attributes.checked = true;
						}
					});
				}
			},
			stateEvents: ['expandnode', 'checkchange', 'collapsenode'],
			loader: new Ext.tree.TreeLoader(
			{
				dataUrl: 'get-categories.php',
				requestMethod: 'GET'
			}),
			containerScroll: true
		});

		// add a tree sorter in folder mode
		new Ext.tree.TreeSorter(tree, {folderSort: false});

		// set the root node
		var root = new Ext.tree.AsyncTreeNode({
			text: 'ROOT', 
			id: '0',
			checked: (intelli.inArray('0', categories)) ? true : false
		});

		tree.setRootNode(root);

		tree.on('render', function()
		{
			var parents = new Array();
			var path = Ext.get('categories_parents').getValue();

			if('' != path)
			{
				parents = path.split('|');

				for(var i = 0; i < parents.length; i++)
				{
					this.expandPath(parents[i]);
				}
			}
		});

		function onAppend(t, p, n)
		{
			if(intelli.inArray(n.id, categories))
			{
				function onParentExpanded()
				{
					n.ui.toggleCheck(true);
				};

				p.on("expand", onParentExpanded, null, {single: true});
			}
		};

		tree.on("append", onAppend);

		// render the tree
		tree.render();

		if(categories.length > 1)
		{
			root.expand();
		}

		root.on('checkchange', function(node, checked)
		{
			var checkedNodes = tree.getChecked();
			var tempCats = new Array();

			if(checkedNodes)
			{
				for(var i = 0; i < checkedNodes.length; i++)
				{
					tempCats[i] = checkedNodes[i].id;
				}

				Ext.get("categories").dom.value = tempCats.join('|');
			}
		});

		tree.on('checkchange', function(node, checked)
		{
			var checkedNodes = tree.getChecked();
			var tempCats = new Array();

			if(checkedNodes)
			{
				for(var i = 0; i < checkedNodes.length; i++)
				{
					tempCats[i] = checkedNodes[i].id;
				}

				Ext.get("categories").dom.value = tempCats.join('|');
			}
		});
	}

	var check_all = true;
	var item = $('select[name="item"]').val();

	var display = ('listing' == item) ? 'show' : 'hide';

	$('.listing_options')[display]();
	$('#listing_fields')[display]();
	$('#visual_options')[display]();

	$('#' + item + '_pages').show();

	if ('account' == item)
	{
		$('#addit_account_options').show();
	}

	$('select[name="item"]').change(function()
	{
		var display = ('listing' == $(this).val()) ? 'show' : 'hide';

		if ('account' == $(this).val())
		{
			$('#addit_account_options').show();
		}
		else
		{
			$('#addit_account_options').hide();
		}

		$('div.plan_pages').hide();
		$('#' + $(this).val() + '_pages').show();

		$('.listing_options')[display]();
		$('#listing_fields')[display]();
		$('#visual_options')[display]();
	});

	$("input[name='fields[]']").each(function()
	{
		if(!$(this).attr("checked"))
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

	$("#check_all_options").prop("checked", check_all);

	$("#check_all_options").click(function()
	{
		var checked = $(this).prop("checked");

		$("input[name='visual_options[]']").each(function()
		{
			$(this).prop("checked", checked);
		});
	});

	$("input[name='num_allowed_listings_type']").each(function()
	{
		if(1 == $(this).val() && $(this).attr("checked"))
		{
			$("#nal").css("display", 'block');
		}

		$(this).click(function()
		{
			var display = (1 == $(this).val()) ? 'block' : 'none';

			$("#nal").css("display", display);

			if('block' == display)
			{
				$("input[name='num_allowed_listings']").val('');
			}
		});
	});
});