intelli.banners = function()
{
	var vUrl = 'controller.php?plugin=banners';
	var blockPositions = new Array();
	
	$.each(intelli.config.esyndicat_block_positions.split(','), function(i, v)
	{
		blockPositions.push([v, v]);
	});

	return {
		oGrid: null,
		vUrl: vUrl,
		positionsStore: new Ext.data.SimpleStore(
        {
            fields: ['value', 'display'],
            data : blockPositions
        }),
        statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
			    ['active', intelli.admin.lang.active],
			    ['inactive', intelli.admin.lang.inactive]
			]
		}),
		pagingStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
			    ['10', '10'],
			    ['20', '20'],
			    ['30', '30'],
			    ['40', '40'],
			    ['50', '50']
			]
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
			{name: 'position', mapping: 'position'},
			{name: 'type', mapping: 'type'},
			{name: 'status', mapping: 'status'},
			{name: 'showed', mapping: 'showed'},
			{name: 'clicked', mapping: 'clicked'},
			{name: 'block', mapping: 'block_id'},
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
			width: 250,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.position, 
			dataIndex: 'position', 
			sortable: true,
			width: 85,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				//allowDomMove: false,
				triggerAction: 'all',
				editable: false,
				lazyRender: true,
				store: intelli.banners.positionsStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			})
		},{
			header: intelli.admin.lang.type, 
			dataIndex: 'type',
			width: 130,
			sortable: true
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
				store: intelli.banners.statusesStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			})
		},{
			header: intelli.admin.lang.impressions, 
			dataIndex: 'showed', 
			sortable: true, 
			width: 80
		},{
			header: intelli.admin.lang.clicks, 
			dataIndex: 'clicked', 
			sortable: true, 
			width: 80
		},{
			header: "",
			width: 40,
			dataIndex: 'block',
			hideable: false,
			menuDisabled: true,
			align: 'center'
		},{
			header: "",
			width: 60,
			dataIndex: 'edit',
			hideable: false,
			menuDisabled: true,
			align: 'center'
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
		this.plugins = new Ext.ux.PanelResizer({
            minHeight: 100
		});

		this.title = intelli.admin.lang.manage_banners;
		this.renderTo = 'box_banners';

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
			store: intelli.banners.pagingStore,
			value: '10',
			displayField: 'display',
			valueField: 'value',
			mode: 'local',
			id: 'pgnPnl'
		});

		var removeButton = new Ext.Toolbar.Button({
			text: intelli.admin.lang.remove,
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
			store: intelli.banners.statusesStore,
			value: 'active',
			displayField: 'value',
			valueField: 'display',
			mode: 'local',
			disabled: true,
			id: 'statusCmb'
		});

		var goButton = new Ext.Toolbar.Button({
			text: intelli.admin.lang['do'],
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
				'Items per page:',
				{
					xtype: 'bettercombo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					width: 80,
					store: intelli.banners.pagingStore,
					value: '10',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'pgnPnl'
				},
				'-',
				'Status:',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					editable: false,
					lazyRender: true,
					store: intelli.banners.statusesStore,
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
					id: 'goBtn'
				},
				'-',
				{
					text: intelli.admin.lang.remove,
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
		this.columnModel.setRenderer(4, function(value, metadata)
		{
			metadata.css = value;

			return value;
		});

		/* add the link to block configuration page */
		this.columnModel.setRenderer(7, function(value, metadata)
		{
			if('' != value)
			{
				return '<a href="controller.php?file=blocks&do=edit&id=' + value + '"><img class="grid_action" title="'+ intelli.admin.lang.edit +'" alt="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/config-grid-ico.png" /></a>';
			}
		});
		
		/* add edit link */
		this.columnModel.setRenderer(8, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
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
		intelli.banners.oGrid.grid.on('afteredit', function(editEvent)
		{
			var value = ('date' == editEvent.field) ? editEvent.value.format("Y-m-d") : editEvent.value;

			Ext.Ajax.request(
			{
				url: intelli.banners.vUrl,
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
					
					intelli.banners.oGrid.grid.getStore().reload();
				}
			});
		});

		/* Edit and remove click */
		intelli.banners.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

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
								url: intelli.banners.vUrl,
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
									
									intelli.banners.oGrid.grid.getStore().reload();
								}
							});
						}
					}
				});
			}
		});

		/* Enable disable functionality buttons */
		intelli.banners.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('removeBtn').enable();
		});

		intelli.banners.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
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
			var rows = intelli.banners.oGrid.grid.getSelectionModel().getSelections();
			var status = Ext.getCmp('statusCmb').getValue();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Ajax.request(
			{
				url: intelli.banners.vUrl,
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
					intelli.banners.oGrid.grid.getStore().reload();

					var response = Ext.decode(data.responseText);
					var type = response.error ? 'error' : 'notif';
						
					intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});
				}
			});
		});

		/* Edit and remove click */
		intelli.banners.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				intelli.banners.oGrid.saveGridState();

				window.location = 'controller.php?plugin=banners&file=index&do=edit&id='+ record.json.id;
			}

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_selected_banners,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.banners.vUrl,
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

									Ext.getCmp('statusCmb').disable();
									Ext.getCmp('goBtn').disable();
									//Ext.getCmp('actBtn').disable();
									//Ext.getCmp('actCmb').disable();
									Ext.getCmp('removeBtn').disable();

									intelli.banners.oGrid.grid.getStore().reload();
								}
							});
						}
					}
				});
			}
		});
		
		/* remove button action */
		Ext.getCmp('removeBtn').on('click', function()
		{
			var rows = intelli.banners.oGrid.grid.getSelectionModel().getSelections();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Msg.show(
			{
				title: intelli.admin.lang.confirm,
				msg: (ids.length > 1) ? intelli.admin.lang.are_you_sure_to_delete_selected_banners : intelli.admin.lang.are_you_sure_to_delete_this_account,
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				fn: function(btn)
				{
					if('yes' == btn)
					{
						Ext.Ajax.request(
						{
							url: intelli.banners.vUrl,
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

								intelli.banners.oGrid.grid.getStore().reload();
							}
						});
					}
				}
			});
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.banners.oGrid.grid.getStore().baseParams.limit = new_value;
			intelli.banners.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.banners.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if(Ext.get('box_banners'))
	{
		intelli.banners.oGrid = new intelli.exGrid({url: intelli.banners.vUrl});

		/* Initialization grid */
		intelli.banners.oGrid.init();
	}

	if(Ext.get('tree'))
	{
		var checkedCategories = Ext.get("categories").getValue();

		var tree = new Ext.tree.TreePanel({
			el: 'tree',
			animate: true, 
			autoScroll: true,
			width: 'auto',
			height: 'auto',
			border: true,
			loader: new Ext.tree.TreeLoader(
			{
				dataUrl: 'get-categories.php?checked=' + checkedCategories,
				requestMethod: 'GET'
			}),
			containerScroll: true
		});
         
		// add a tree sorter in folder mode
		new Ext.tree.TreeSorter(tree, {folderSort: true});

		// set the root node
		var root = new Ext.tree.AsyncTreeNode({
			text: 'ROOT', 
			id: '0',
			checked: intelli.inArray(0, checkedCategories.split('|')) ? true : false
		});
		tree.setRootNode(root);
            
		// render the tree
		tree.render();

		//root.expand();
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

	changeType();
	
});

function changeType()
{
	type = $('#typeSelecter option:selected').val();

	$('#imageTitle, #imageParams, #imageUrl, #bannerurl, #htmlsettings, #mediasize, #bannerThumbnail, #uploadcontainer, #textcontainer, #planetextcontainer, #imageFit').hide();

	switch(type) 
	{
		case "html":
			if(!CKEDITOR.instances.content)
			{
				CKEDITOR.replace('content');
			}
			$('#htmlsettings, #textcontainer').show();
		break;
		
		case "text":
			$('#planetextcontainer, #bannerurl').show();
		break;
		
		case "local":
			$('#bannerThumbnail, #mediasize, #imageTitle, #uploadcontainer, #bannerurl').show();
			if($('#setown').attr("checked"))	{	$('#imageParams').show();	}
			if($('#imageKeepRatio').attr("checked"))	{	$('#imageFit').show();	}
		break;
		
		case "remote":
			$('#imageUrl, #mediasize, #imageTitle, #bannerurl').show();
			if($('#setown').attr("checked"))	{	$('#imageParams').show();	}
			if($('#imageKeepRatio').attr("checked"))	{	$('#imageFit').show();	}
		break;

		case "flash":
			$('#uploadcontainer, #bannerThumbnail, #mediasize').show();
		break;
	}
}

function getTarget(sObj)
{
	type = sObj.options[sObj.selectedIndex].value;

	if('other' == type) 
	{
		$('#settarget input').val('');	
		$('#settarget').show();	
	}
	else
	{
		$('#settarget').hide();	
		$('#settarget input').val(type);
	}
}

function imageFit()
{
	if ($('#file').val().substr(-3) != 'swf')
	{
		$('#imageFit').slideToggle();
	}else{
		$('#imageFit').hide("slow");
	}
}

