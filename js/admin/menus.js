var Menus, Pages;

var buildTrees = function()
{
	 Menus = new Ext.tree.TreePanel({
		renderTo: 'div_menus',
		title: _t('menus'),
		id: 'menus',
		height: 200,
		width: 300,
		autoScroll: true,
		enableDD: true,
		root: menus,
		contextMenu: new Ext.menu.Menu({
			items: [/*{
				// CREATE NEW MENU
				id: 'create-menu',
				iconCls: 'silk-add',
				text: _t('add_menu'),
				handler: add_menu
			},*/{
				id: 'edit-menu',
				iconCls: 'silk-edit',
				text: _t('edit_menu'),
				handler: edit_menu
			}, {
				id: 'delete-node',
				text: _t('delete'),
				handler: function(){
					var delNode = Menus.selModel.selNode;
					delNode.parentNode && delNode.parentNode.removeChild(delNode);
				},
				iconCls: 'silk-delete'
			}]
		}),
		listeners: {
			'beforenodedrop' : function(e)
			{
				if(!e.dropNode.parentNode.parentNode && e.dropNode.parentNode.attributes.id == 'pages') return false;
				if (e.dropNode.parentNode.parentNode && e.dropNode.parentNode.parentNode.attributes.id == 'pages')
				{
					var add = true;
					jQuery.each(Menus.root.childNodes, function(index, item){
						if(item.id == e.dropNode.id) add = false;
					});
					if(!add)return false;
					e.dropNode = new Ext.tree.TreeNode({
						id: e.dropNode.id,
						leaf: false,
						text: e.dropNode.text
					});
					e.target.expand();
					if (!e.target.leaf) {
						e.target.appendChild(e.dropNode);
					}
					else {
						e.target.parentNode.appendChild(e.dropNode);
					}
				}
			},
			'nodedrop' : function(e){
				e.target.leaf = false;
				e.target.expand(); 
			},
			'checkchange': function(node, checked){
				if(checked){
					node.getUI().addClass('complete');
				}else{
					node.getUI().removeClass('complete');
				}
			},
			contextmenu: function(node, e) {
				node.select();
				var c = node.getOwnerTree().contextMenu;
				c.contextNode = node;
				c.showAt(e.getXY());
			}

		}
	});
	Menus.getRootNode().expand();

	// build pages tree
	Pages = new Ext.tree.TreePanel({
		renderTo: 'box_pages',
		title: _t('pages'),
		height: 200,
		width: 300,
		autoScroll: true,
		enableDD: true,
		listeners: {
			'beforenodedrop' : function(){
				return false;
			}
		},
		root: {
			text: _t('items'),
			draggable: false,
			id: 'items_root',
		},
		loader : new Ext.tree.TreeLoader(
		{
			dataUrl: 'get-menus.php',
			baseParams: {single: 1},
			requestMethod: 'GET'
		})
	});

	Pages.getRootNode().expand();
};

var edit_menu = function(new_menu)
{
	if (Ext.getCmp('menus').selModel.selNode)
	{
		var selNode = Ext.getCmp('menus').selModel.selNode;
		var reg_exp = / \(no link\)/;
		var text = selNode.text.replace(/ \(custom\)/, '').replace(reg_exp, '');
		var win;
		var form;
		var menu_id = $('#name').val();

		$.ajax(
		{
			dataType: 'json',
			async: false,
			url: 'controller.php?file=menus',
			data: {
				action: 'get_phrases',
				id: selNode.id,
				current: text,
				menu: menu_id,
				new: (new_menu == 'add')
			},
			success:function(json)
			{
				if (!json.langs || json.length == 0)
				{
					return false;
				}

				var node = (new_menu == 'add' ? 'node_' + Math.floor(Math.random(1000) * 100000) : selNode.id);

				win = new Ext.Window(
				{
					layout: 'fit',
					width: 350,
					autoHeight: true,
					plain: true,
					items: [new Ext.FormPanel(
					{
						id: 'form-panel',
						labelWidth: 75,
						url: 'controller.php?file=menus&action=save_phrases&menu=' + menu_id + '&node=' + node,
						frame:true,
						bodyStyle:'padding: 5px 5px 0',
						width: 350,
						autoHeight: true,
						defaults: {width: 230},
						defaultType: 'textfield',
						items: json.langs,
						buttons: [
						{
							text: _t('save'),
							handler: function()
							{
								changed = true;
								Ext.getCmp('form-panel').getForm().submit({waitMsg : 'Saving...',success:function(){
									var form_text = Ext.getCmp('form-panel').getForm().getValues()[intelli.config.lang];
									if (form_text == '')
									{
										return false;
									}
									if (new_menu == 'add')
									{
										var target = selNode;
										var newMenu = new Ext.tree.TreeNode({
											id: node,
											text: form_text + ' (no link)',
											leaf: false,
											cls: 'folder',
											children: []
										});

										if (selNode.leaf)
										{
											target = selNode.parentNode;
										}

										target.appendChild(newMenu);
										target.expand();
									}
									else
									{
										selNode.setText(form_text + (selNode.text.match(reg_exp) ? ' (no link)' : ' (custom)'));
									}
									win.close();
								}});
							}
						},{
							text: _t('cancel'),
							handler: function()
							{
								win.close();
							}
						}]
					})]
				}).show();
			}
		});
	}
};

intelli.menus = function()
{
	var vUrl = 'controller.php?file=menus';
	var blockPositions = new Array();

	$.each(intelli.config.esyndicat_block_positions.split(','), function(i, v)
	{
		blockPositions.push([v, v]);
	});

	return {
		positionsStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : blockPositions
		}),
		typesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['plain', 'plain'],['smarty', 'smarty'],['php', 'php'],['html', 'html']]
		}),
		statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['active', 'active'],['inactive', 'inactive']]
		}),
		pagingStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['10', '10'],['20', '20'],['30', '30'],['40', '40'],['50', '50']]
		}),
		vUrl: vUrl,
		oGrid: null	
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
			{name: 'id', mapping: 'id'},
			{name: 'title', mapping: 'title'},
			{name: 'status', mapping: 'status'},
			{name: 'position', mapping: 'position'},
			{name: 'order', mapping: 'order'},
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
			width: 300,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.status,
			dataIndex: 'status',
			sortable: true,
			width: 85,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				editable: false,
				lazyRender: true,
				store: intelli.menus.statusesStore,
				displayField: 'value',
				valueField: 'display',
				mode: 'local'
			})
		},{
			header: intelli.admin.lang.position, 
			dataIndex: 'position', 
			sortable: true,
			width: 85,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				editable: false,
				lazyRender: true,
				store: intelli.menus.positionsStore,
				displayField: 'value',
				valueField: 'display',
				mode: 'local'
			})
		},{
			header: intelli.admin.lang.order,
			dataIndex: 'order',
			sortable: true,
			width: 85,
			editor: new Ext.form.NumberField({
				allowBlank: false,
				allowDecimals: false,
				allowNegative: false
			})
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

		this.title = intelli.admin.lang.menus;
		this.renderTo = 'box_menus';

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
					store: intelli.menus.pagingStore,
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
					store: intelli.menus.statusesStore,
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
						var rows = intelli.menus.oGrid.grid.getSelectionModel().getSelections();
						var status = Ext.getCmp('statusCmb').getValue();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].data.id;
						}

						Ext.Ajax.request(
						{
							url: intelli.menus.vUrl,
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
								var response = Ext.decode(data.responseText);
								var type = response.error ? 'error' : 'notif';

								intelli.admin.notifBox({msg: response.msg, type: type, autohide: true});

								intelli.menus.oGrid.grid.getStore().reload();
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
						var rows = intelli.menus.oGrid.grid.getSelectionModel().getSelections();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].data.id;
						}

						Ext.Msg.show(
						{
							title: intelli.admin.lang.confirm,
							msg: (ids.length > 1) ? intelli.admin.lang.are_you_sure_to_delete_selected_menus : intelli.admin.lang.are_you_sure_to_delete_this_menu,
							buttons: Ext.Msg.YESNO,
							icon: Ext.Msg.QUESTION,
							fn: function(btn)
							{
								if('yes' == btn)
								{
									Ext.Ajax.request(
									{
										url: intelli.menus.vUrl,
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

											intelli.menus.oGrid.grid.getStore().reload();

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
		this.dataStore.baseParams = {action: 'get'};
	},
	setRenderers: function()
	{
		/* change background color for status field */
		this.columnModel.setRenderer(2, function(value, metadata)
		{
			metadata.css = value;

			return value;
		});

		/* add edit link */
		this.columnModel.setRenderer(5, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
		});

		/* add remove link */
		this.columnModel.setRenderer(6, function(value, metadata)
		{
			if (1 == value)
			{
				return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.remove +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico.png" />';
			}
			else
			{
				return '<img src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico-grey.png" />';
			}
		});
	},
	setEvents: function()
	{
		intelli.menus.oGrid.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.menus.vUrl,
				method: 'POST',
				params:
				{
					action: 'update',
					ids: editEvent.record.id,
					field: editEvent.field,
					value: editEvent.value
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

					intelli.menus.oGrid.grid.getStore().reload();
				}
			});
		});

		intelli.menus.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if ('edit' == fieldName)
			{
				intelli.menus.oGrid.saveGridState();

				window.location = 'controller.php?file=menus&do=edit&id='+ record.json.id;
			}

			if ('remove' == fieldName)
			{
				if(0 == data)
				{
					return false;
				}

				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_menu,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.menus.vUrl,
								method: 'POST',
								params:
								{
									action: 'remove',
									ids: record.id
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

									grid.getStore().reload();
								}
							});
						}
					}
				});
			}
		});

		intelli.menus.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('removeBtn').enable();
		});

		intelli.menus.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('statusCmb').disable();
				Ext.getCmp('goBtn').disable();
				Ext.getCmp('removeBtn').disable();
			}
		});

		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.menus.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.menus.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.menus.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if (Ext.get("box_menus"))
	{
		intelli.menus.oGrid = new intelli.exGrid({url: intelli.menus.vUrl});

		// Initialization grid
		intelli.menus.oGrid.init();
	}
	else
	{
		buildTrees();

		$('#title').keyup(function()
		{
			Menus.root.setText($(this).val());
		}).blur(function()
		{
			if ('' == $(this).val())
			{
				Menus.root.setText('New Menu');
			}
		});

		$('#delete_menu').click(function()
		{
			var delNode = Menus.selModel.selNode;

			if(delNode)
			{
				delNode.parentNode && delNode.parentNode.removeChild(delNode);
			}
		});

		$('#add_menu').click(function()
		{
			var target = (Menus.selModel.selNode ? Menus.selModel.selNode : Menus.root);

			if(Pages.selModel.selNode) 
			{
				var selNode = Pages.selModel.selNode;
				var add = true;
				jQuery.each(Menus.root.childNodes, function(index, item){
					if(item.id == selNode.id) add = false;
				});
				if(selNode.leaf && add)
				{
					var dropNode = new Ext.tree.TreeNode({
						id: selNode.id,
						leaf: true,
						text: selNode.text
					});
					if (!target.leaf) {
						target.appendChild(dropNode);					
					}
					else {
						target.parentNode.appendChild(dropNode);
					}
					Menus.root.expand();
				}
			}
		});

		if (1 == $("input[name='show_header']").val())
		{
			$("input[name='collapsible']").parents('tr:first').show();
		}
		else
		{
			$("input[name='collapsible']").val(0).parents('tr:first').hide();
		}

		$("input[name='show_header']").change(function()
		{
			if ($(this).val() == 1)
			{
				$("input[name='collapsible']").parents('tr:first').show();
			}
			else
			{
				$("input[name='collapsible']").val(0).parents('tr:first').hide();
			}
		});

		if (1 == $("input[name='sticky']").val())
		{
			$("#acos").css("display", 'none');
		}
		else
		{
			$("#acos").css("display", 'block');
		}

		$("input[name='sticky']").change(function()
		{
			var display = (1 == $(this).val()) ? 'none' : 'block';

			$("#acos").css("display", display);
		});

		var all_acos_count = $("#acos input[name^='visible_on_pages']").length;
		var checked_acos_count = $("#acos input[name^='visible_on_pages']:checked").length;

		if (checked_acos_count > 0 && all_acos_count == checked_acos_count)
		{
			//$("#select_all").attr("checked", "checked");
			$("#select_all").prop("checked", "checked");
		}

		$("#acos input[name^='visible_on_pages']").each(function()
		{
			$(this).click(function()
			{
				var checked = (all_acos_count == $("#acos input[name^='visible_on_pages']:checked").length) ? 'checked' : '';

				//$("#select_all").attr("checked", checked);
				$("#select_all").prop("checked", checked);
			});
		});

		if ($("#select_all").prop("checked"))
		{
			$("#acos input[type='checkbox']").each(function()
			{
				$(this).prop("checked", "checked")
			});
		}

		$("#select_all").click(function()
		{
			var checked = $(this).prop("checked") ? 'checked' : '';

			$("#acos input[type='checkbox']").each(function()
			{
				$(this).prop("checked", checked)
			});
		});

		$("#acos input[name^='select_all_']").each(function()
		{
			$(this).click(function()
			{
				var checked = $(this).prop("checked") ? 'checked' : '';
				var group_class = $(this).attr("class");

				$("input." + group_class).each(function()
				{
					$(this).prop('checked', checked);
				});
			});
		});

		$('#save').click(function()
		{
			$('#menu').val(new Ext.tree.JsonTreeSerializer(Menus).toString());

			$('#menu_form').submit();

			return false;
		});


		$("#add_categories").click(function()
		{
			var category_id = '';
			var crossed = $("#cat_crossed").val().split(',');

			intelli.menus.crossedTree = new Ext.tree.TreePanel(
			{
				animate: true, 
				autoScroll: true,
				width: 'auto',
				height: 'auto',
				border: false,
				plain: true,
				loader: new Ext.tree.TreeLoader(
				{
					dataUrl: 'get-categories.php?disabled[]=' + category_id,
					baseParams: {single: 0},
					requestMethod: 'GET'
				}),
				containerScroll: true
			});
		
			// add a tree sorter in folder mode
			new Ext.tree.TreeSorter(intelli.menus.crossedTree, {folderSort: false});
			 
			// set the root node
			var root = new Ext.tree.AsyncTreeNode({
				text: 'ROOT', 
				id: '0'
			});

			intelli.menus.crossedTree.setRootNode(root);
				
			root.expand();

			/*intelli.menus.crossedTree.on('render', function()
			{
				var path = Ext.get('crossed_expand_path').getValue();

				if('' != path)
				{
					path = path.split(',');

					for(var i = 0; i < path.length; i++)
					{
						this.expandPath(path[i]);
					}
				}
			});*/

			function onAppend(t, p, n)
			{
				if(intelli.inArray(n.id, crossed))
				{
					function onParentExpanded()
					{
						n.ui.toggleCheck(true);
					};

					p.on("expand", onParentExpanded, null, {single: true});
				}
			};

			intelli.menus.crossedTree.on('checkchange', function(node, checked)
			{
				if(checked && category_id == node.id)
				{
					intelli.admin.alert({title: 'Error', msg: intelli.admin.lang.cross_warning, type: 'error'});
					
					node.ui.checkbox.checked = false;
					node.attributes.checked = false;
				}
			});

			intelli.menus.crossedTree.on("append", onAppend);

			intelli.menus.crossedWin = new Ext.Window(
			{
				title: intelli.admin.lang.tree,
				width : 400,
				height : 450,
				autoScroll: true,
				modal: true,
				closeAction : 'hide',
				items: [intelli.menus.crossedTree],
				buttons: [
				{
					text : intelli.admin.lang.ok,
					handler: function()
					{
						var categories = intelli.menus.crossedTree.getChecked();
						var ids = new Array();
						var category_url = intelli.config.esyn_url + 'controller.php?file=browse&id=';
						var html = '';
						
						if(categories.length > 0)
						{
							for(var i = 0; i < categories.length; i++)
							{
								ids[i] = categories[i].id;
							}
						}

						$("#cat_crossed").val(ids.join(','));
						$("#crossed").html(html);
						
						intelli.menus.crossedWin.hide();
					}
				},{
					text : intelli.admin.lang.cancel,
					handler : function()
					{
						intelli.menus.crossedWin.hide();
					}
				}]
			});

			intelli.menus.crossedWin.show();

			return false;
		});
	}
});