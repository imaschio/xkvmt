intelli.blocks = function()
{
	var vUrl = 'controller.php?file=blocks';
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
			{name: 'plugin', mapping: 'plugin'},
			{name: 'position', mapping: 'position'},
			{name: 'type', mapping: 'type'},
			{name: 'lang', mapping: 'lang'},
			{name: 'status', mapping: 'status'},
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
			header: intelli.admin.lang.plugin, 
			dataIndex: 'plugin', 
			sortable: true, 
			width: 150
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
				store: intelli.blocks.positionsStore,
				displayField: 'value',
				valueField: 'display',
				mode: 'local'
			})
		},{
			header: intelli.admin.lang.type, 
			dataIndex: 'type',
			width: 85,
			sortable: true
		},{
			header: intelli.admin.lang.language, 
			dataIndex: 'lang',
			width: 85
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
				store: intelli.blocks.statusesStore,
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

		this.dataStore.setDefaultSort('title');
	},
	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
			minHeight: 100
		});

		this.title = intelli.admin.lang.blocks;
		this.renderTo = 'box_blocks';

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
				intelli.admin.lang.title + ':',
				{
					xtype: 'textfield',
					id: 'searchTitle',
					emptyText: 'Title'
				},
				' ',
				intelli.admin.lang.plugin + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: new Ext.data.Store(
					{
						proxy: new Ext.data.HttpProxy({url: intelli.blocks.vUrl + '&action=getplugins', method: 'GET'}),
						reader: new Ext.data.JsonReader(
						{
							root: 'data',
							totalProperty: 'total'
						}, [
							{name: 'name'},
							{name: 'title'}
						])
					}),
					value: 'all',
					displayField: 'title',
					valueField: 'name',
					id: 'pluginFilter'
				},
				intelli.admin.lang.type + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: intelli.blocks.typesStore,
					value: 'all',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'typeFilter'
				},{
					text: intelli.admin.lang.search,
					iconCls: 'search-grid-ico',
					id: 'fltBtn',
					handler: function()
					{
						var title = Ext.getCmp('searchTitle').getValue();
						var plugin = Ext.getCmp('pluginFilter').getValue();
						var type = Ext.getCmp('typeFilter').getValue();

						if('all' != status || '' != title)
						{
							intelli.blocks.oGrid.dataStore.baseParams = 
							{
								action: 'get',
								title: title,
								plg: plugin,
								type: type
							};

							intelli.blocks.oGrid.dataStore.reload();
						}
					}
				},
				'-',
				{
					text: intelli.admin.lang.reset,
					id: 'resetBtn',
					handler: function()
					{
						Ext.getCmp('searchTitle').reset();
						Ext.getCmp('pluginFilter').setValue('all');
						Ext.getCmp('typeFilter').reset();

						intelli.blocks.oGrid.dataStore.baseParams = 
						{
							action: 'get',
							title: '',
							plg: '',
							type: ''
						};

						intelli.blocks.oGrid.dataStore.reload();
					}
				}
			]
		});

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
					store: intelli.blocks.pagingStore,
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
					store: intelli.blocks.statusesStore,
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
						var rows = intelli.blocks.oGrid.grid.getSelectionModel().getSelections();
						var status = Ext.getCmp('statusCmb').getValue();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].data.id;
						}

						Ext.Ajax.request(
						{
							url: intelli.blocks.vUrl,
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

								intelli.blocks.oGrid.grid.getStore().reload();
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
						var rows = intelli.blocks.oGrid.grid.getSelectionModel().getSelections();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].data.id;
						}

						Ext.Msg.show(
						{
							title: intelli.admin.lang.confirm,
							msg: (ids.length > 1) ? intelli.admin.lang.are_you_sure_to_delete_selected_blocks : intelli.admin.lang.are_you_sure_to_delete_this_block,
							buttons: Ext.Msg.YESNO,
							icon: Ext.Msg.QUESTION,
							fn: function(btn)
							{
								if('yes' == btn)
								{
									Ext.Ajax.request(
									{
										url: intelli.blocks.vUrl,
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

											intelli.blocks.oGrid.grid.getStore().reload();

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
		this.columnModel.setRenderer(6, function(value, metadata)
		{
			metadata.css = value;

			return value;
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

		// Edit fields
		intelli.blocks.oGrid.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.blocks.vUrl,
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

					intelli.blocks.oGrid.grid.getStore().reload();
				}
			});
		});

		intelli.blocks.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				intelli.blocks.oGrid.saveGridState();

				window.location = 'controller.php?file=blocks&do=edit&id='+ record.json.id;
			}

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_block,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.blocks.vUrl,
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

		intelli.blocks.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('removeBtn').enable();
		});

		intelli.blocks.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if (0 == sm.getCount())
			{
				Ext.getCmp('statusCmb').disable();
				Ext.getCmp('goBtn').disable();
				Ext.getCmp('removeBtn').disable();
			}
		});

		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.blocks.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.blocks.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.blocks.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if (Ext.get("box_blocks"))
	{
		intelli.blocks.oGrid = new intelli.exGrid({url: intelli.blocks.vUrl});

		// Initialization grid
		intelli.blocks.oGrid.init();
	}

	if ($("#multi_language").prop("checked"))
	{
		$("select[name='lang']").prop("disabled", "disabled");
	}
	$("#multi_language").click(function()
	{
		var disabled = $(this).prop("checked") ? 'disabled' : '';

		$("select[name='lang']").prop("disabled", disabled);
	});

	 // Hide the pages section if block is sticky and show in otherwise
	(1 == $("input[name='sticky']").val()) ? $("#acos").hide() : $("#acos").show();;
	$("input[name='sticky']").change(function()
	{
		var display = (1 == $(this).val()) ? 'none' : 'block';

		$("#acos").css("display", display);
	});

	// external blocks change
	var external = $('input[name="external"]');
	var external_switcher = $('#external_switcher');
	if (1 == external.val())
	{
		$("#non_external_content").hide();
	}
	else
	{
		$("#non_external_content").show();
	}
	external.change(function()
	{
		var display = (1 == $(this).val()) ? 'none' : 'block';
		$("#non_external_content").css("display", display);
	});

	var all_acos_count = $("#acos input[name^='visible_on_pages']").length;
	var checked_acos_count = $("#acos input[name^='visible_on_pages']:checked").length;

	if (checked_acos_count > 0 && all_acos_count == checked_acos_count)
	{
		$("#select_all").prop("checked", "checked");
	}

	$("#acos input[name^='visible_on_pages']").each(function()
	{
		$(this).click(function()
		{
			var checked = (all_acos_count == $("#acos input[name^='visible_on_pages']:checked").length) ? 'checked' : '';

			$("#select_all").prop("checked", checked);
		});
	});

	if($("#select_all").prop("checked"))
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
			//var checked = $(this).attr("checked") ? 'checked' : '';
			var checked = $(this).prop("checked") ? 'checked' : '';
			var group_class = $(this).attr("class");

			$("input." + group_class).each(function()
			{
				//$(this).attr('checked', checked);
				$(this).prop('checked', checked);
			});
		});
	});

	if (1 == $("input[name='show_header']").val())
	{
		$("input[name='collapsible']").parents('tr:first').show();

		if (1 == $("input[name='collapsible']").val())
		{
			$("input[name='collapsed']").parents('tr:first').show();
		}
	}
	else
	{
		$("input[name='collapsible']").parents('tr:first').hide();
		$("input[name='collapsed']").parents('tr:first').hide();
	}

	$("input[name='show_header']").change(function()
	{
		if ($(this).val() == 1)
		{
			$("input[name='collapsible']").parents('tr:first').show();

			if (1 == $("input[name='collapsible']").val())
			{
				$("input[name='collapsed']").parents('tr:first').show();
			}
		}
		else
		{
			$("input[name='collapsible']").parents('tr:first').hide();
			$("input[name='collapsed']").parents('tr:first').hide();
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

	var editArea = new Array();
	if (1 == $("input[name='multi_language']").val())
	{
		$("#languages").css('display', 'none');
		$("#blocks_contents").css('display', 'block');

		if ('html' == $("#block_type").val())
		{
			if (!CKEDITOR.instances.multi_contents)
			{
				intelli.ckeditor('multi_contents', {toolbar: 'User', height: '400px'});
			}
		}
		else if ('plain' != $("#block_type").val())
		{
			if (CKEDITOR.instances.multi_contents)
			{
				CKEDITOR.instances.multi_contents.destroy();
			}

			var syntax = ('smarty' == $("#block_type").val()) ? 'html' : 'php';
			if ('php' == syntax)
			{
				$('#php_tags_tooltip').show();
			}

			editArea.push('multi_contents');

			editAreaLoader.init(
			{
				id : 'multi_contents'
				,syntax: syntax
				,start_highlight: true
				,allow_resize: 'no'
				,min_height: 400
				,replace_tab_by_spaces: false
				,toolbar: 'search, go_to_line, |, undo, redo'
			});
		}
	}
	else
	{
		$("#languages").css('display', '');
		$("#blocks_contents").css('display', 'none');
	}

	$("input[name='multi_language']").change(function()
	{
		if (1 == $(this).val())
		{
			$("#languages").css('display', 'none');

			$("#blocks_contents").css('display', 'block');

			$("input.block_languages").each(function()
			{
				$(this).prop("checked", "");
				$("#select_all_languages").prop("checked", "");

				initContentBox({lang: $(this).val(), checked: ''});
			});

			if('html' == $("#block_type").val() && !CKEDITOR.instances.multi_contents)
			{
				intelli.ckeditor('multi_contents', {toolbar: 'User', height: '400px'});
			}
		}
		else
		{
			$("#languages").css('display', '');

			$("#blocks_contents").css('display', 'none');

			if('html' == $("#block_type").val() && CKEDITOR.instances.multi_contents)
			{
				CKEDITOR.instances.multi_contents.destroy();
			}

			$("input.block_languages").each(function()
			{
				$(this).prop("checked", 'checked');
				initContentBox({lang: $(this).val(), checked: true});
			});
		}
	});

	$("input.block_languages").each(function()
	{
		$(this).change(function()
		{
			initContentBox({lang: $(this).val(), checked: $(this).prop("checked")})
		});
	});

	$("input.block_languages:checked").each(function()
	{
		initContentBox({lang: $(this).val(), checked: $(this).prop("checked")})
	});

	$("#select_all_languages").click(function()
	{
		var checked = $(this).prop("checked") ? "checked" : "";

		$("input.block_languages").each(function()
		{
			//$(this).attr("checked", checked);
			$(this).prop("checked", checked);

			initContentBox({lang: $(this).val(), checked: checked});
		});
	});

	if ($("input.block_languages:checked").length == $("input.block_languages").length)
	{
		//$("#select_all_languages").attr("checked", "checked");
		$("#select_all_languages").prop("checked", "checked");
	}

	if (false)
	{
		if ('html' == $("#block_type").val())
		{
			$("textarea.cked:visible").each(function()
			{
				intelli.ckeditor($(this).attr("id"), {toolbar: 'User', height: '400px'});
			});

			external_switcher.hide();
		}
		else
		{
			var syntax = ('smarty' == $(this).val()) ? 'html' : 'php';

			if ('php' == syntax)
			{
				external_switcher.show();
				$('#php_tags_tooltip').show();
			}
			else if('smarty' == syntax)
			{
				external_switcher.show();
			}
			else
			{
				external_switcher.hide();
			}

			$("textarea.cked:visible").each(function()
			{
				editArea.push($(this).attr('id'));

				editAreaLoader.init(
				{
					id : $(this).attr('id')
					,syntax: syntax
					,start_highlight: true
					,allow_resize: 'no'
					,min_height: 400
					,replace_tab_by_spaces: true
					,toolbar: 'save, search, go_to_line, |, undo, redo, |, help'
					,save_callback: "saveHook"
				});
			});
		}
	}

	$("#type_tip_" + $("#block_type").val()).show();

	$("#block_type").change(function()
	{
		$.each(CKEDITOR.instances, function(i, o)
		{
			o.destroy();
		});

		$.each(editArea, function(i, o)
		{
			editAreaLoader.delete_instance(o);
		});

		editArea = new Array();

		$("div.option_tip").hide();
		$("#type_tip_" + $(this).val()).show();

		if (intelli.inArray($(this).val(), ['html', 'php', 'smarty']))
		{
			if ('html' == $(this).val())
			{
				$("textarea.cked:visible").each(function()
				{
					intelli.ckeditor($(this).attr("id"), {toolbar: 'User', height: '400px'});
				});

				external_switcher.hide();
				$('#non_external_content').show();
			}
			else
			{
				var syntax = ('smarty' == $(this).val()) ? 'html' : 'php';

				external_switcher.show();
				if (external.val() == 1)
				{
					$('#non_external_content').hide();
				}
				else
				{
					$('#non_external_content').show();
				}

				$('#php_tags_tooltip').show();

				$("textarea.cked:visible").each(function()
				{
					editArea.push($(this).attr('id'));

					editAreaLoader.init(
					{
						id : $(this).attr('id')
						,syntax: syntax
						,start_highlight: true
						,allow_resize: 'no'
						,min_height: 400
						,replace_tab_by_spaces: true
						,toolbar: 'search, go_to_line, |, undo, redo'
						//,toolbar: 'save, search, go_to_line, |, undo, redo, |, help'
						//,save_callback: "saveHook"
					});
				});
			}
		}
		else
		{
			$('#non_external_content').show();
			external_switcher.hide();
		}
	});

	function initContentBox(o)
	{
		var name = 'contents_' + o.lang;
		var display = o.checked ? 'block' : 'none';
		var block_type = $("#block_type").val();

		if (CKEDITOR.instances[name])
		{
			CKEDITOR.instances[name].destroy();
		}

		$.each(editArea, function(i, o)
		{
			if (name == o)
			{
				editAreaLoader.delete_instance(o);
				editArea.splice(i, 1);
			}
		});

		if (o.checked)
		{
			if(intelli.inArray(block_type, ['html', 'php', 'smarty']))
			{
				if ('html' == block_type)
				{
					if (!CKEDITOR.instances[name])
					{
						intelli.ckeditor(name, {toolbar: 'User', height: '400px'});
					}
				}
				else
				{
					var syntax = ('smarty' == block_type) ? 'html' : 'php';

					editArea.push(name);

					editAreaLoader.init(
					{
						id : name
						,syntax: syntax
						,start_highlight: true
						,allow_resize: 'no'
						,min_height: 400
						,replace_tab_by_spaces: true
						,toolbar: 'search, go_to_line, |, undo, redo'
						//,toolbar: 'save, search, go_to_line, |, undo, redo, |, help'
						//,save_callback: "saveHook"
					});
				}
			}
		}

		$("#blocks_contents_" + o.lang).css("display", display);
	}

	$("#add_categories").click(function()
		{
			var category_id = '';
			var crossed = $("#cat_crossed").val().split(',');

			intelli.blocks.crossedTree = new Ext.tree.TreePanel(
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
			new Ext.tree.TreeSorter(intelli.blocks.crossedTree, {folderSort: false});
			 
			// set the root node
			var root = new Ext.tree.AsyncTreeNode({
				text: 'ROOT', 
				id: '0'
			});

			intelli.blocks.crossedTree.setRootNode(root);
				
			root.expand();

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

			intelli.blocks.crossedTree.on('checkchange', function(node, checked)
			{
				if(checked && category_id == node.id)
				{
					intelli.admin.alert({title: 'Error', msg: intelli.admin.lang.cross_warning, type: 'error'});
					
					node.ui.checkbox.checked = false;
					node.attributes.checked = false;
				}
			});

			intelli.blocks.crossedTree.on("append", onAppend);

			intelli.blocks.crossedWin = new Ext.Window(
			{
				title: intelli.admin.lang.tree,
				width : 400,
				height : 450,
				autoScroll: true,
				modal: true,
				closeAction : 'hide',
				items: [intelli.blocks.crossedTree],
				buttons: [
				{
					text : intelli.admin.lang.ok,
					handler: function()
					{
						var categories = intelli.blocks.crossedTree.getChecked();
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
						
						intelli.blocks.crossedWin.hide();
					}
				},{
					text : intelli.admin.lang.cancel,
					handler : function()
					{
						intelli.blocks.crossedWin.hide();
					}
				}]
			});

			intelli.blocks.crossedWin.show();

			return false;
		});
});
