intelli.listingFields = function()
{
	var vUrl = 'controller.php?file=listing-fields';

	return {
		oGrid: null,
		vUrl: vUrl,
		statusesStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['active', 'active'],['inactive', 'inactive']]
		}),
		typeStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['', 'All'],
				['text', 'Text'],
				['textarea', 'Textarea'],
				['number', 'Number'],
				['combo', 'Dropdown List'],
				['radio', 'Radio Set'],
				['checkbox', 'Checkboxes Set'],
				['storage', 'File Storage'],
				['image', 'Image'],
				['pictures', 'Gallery']
			]
		}),
		pagingStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['10', '10'],['20', '20'],['30', '30'],['40', '40'],['50', '50']]
		})
	};
}();

var group_ds = new Ext.data.Store(
{
	proxy: new Ext.data.HttpProxy({url: intelli.listingFields.vUrl + '&action=getgroups', method: 'GET'}),
	reader: new Ext.data.JsonReader(
	{
		root: 'data',
		totalProperty: 'total'
	}, [
		{name: 'name'},
		{name: 'title'}
	])
});

var group_combo = new Ext.form.ComboBox(
{
	store: group_ds,
	displayField: 'title',
	valueField: 'name',
	triggerAction: 'all',
	forceSelection: true,
	allowBlank: true,
	loadingText: intelli.admin.lang.searching,
	hiddenName: 'group'
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
			{name: 'name', mapping: 'name'},
			{name: 'title', mapping: 'title'},
			{name: 'group', mapping: 'group_title'},
			{name: 'type', mapping: 'type'},
			{name: 'length', mapping: 'length'},
			{name: 'pages', mapping: 'pages'},
			{name: 'order', mapping: 'order'},
			{name: 'order_v', mapping: 'order_v'},
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
			header: intelli.admin.lang.name, 
			dataIndex: 'name',
			sortable: true,
			hidden: true,
			width: 200
		},{
			header: intelli.admin.lang.title, 
			dataIndex: 'title', 
			width: 200
		},{
			header: intelli.admin.lang.group,
			dataIndex: 'group',
			width: 100,
			editor: group_combo
		},{
			header: intelli.admin.lang.field_type, 
			dataIndex: 'type',
			width: 70
		},{
			header: intelli.admin.lang.field_length, 
			dataIndex: 'length',
			hidden: true,
			width: 70
		},{
			header: intelli.admin.lang.pages, 
			dataIndex: 'pages',
			width: 120
		},{
			header: intelli.admin.lang.order, 
			dataIndex: 'order',
			sortable: true,
			width: 70,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: intelli.admin.lang.order_view,
			dataIndex: 'order_v',
			sortable: true,
			width: 70,
			editor: new Ext.form.TextField({
				allowBlank: false
			})
		},{
			header: "", 
			dataIndex: 'edit',
			width: 40,
			hideable: false,
			menuDisabled: true,
			align: 'center'
		},{
			header: "",
			dataIndex: 'remove',
			width: 40,
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

		this.title = intelli.admin.lang.listing_fields;
		this.renderTo = 'box_fields';

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
				intelli.admin.lang.keywords + ':',
				{
					xtype: 'textfield',
					id: 'keywordsFilter',
					emptyText: intelli.admin.lang.keywords
				},
				' ',
				intelli.admin.lang.type + ':',
				{
					xtype: 'combo',
					typeAhead: true,
					triggerAction: 'all',
					forceSelection: true,
					lazyRender: true,
					store: intelli.listingFields.typeStore,
					value: '',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					id: 'typeFilter'
				},
				{
					text: intelli.admin.lang.filter,
					iconCls: 'search-grid-ico',
					id: 'fltBtn',
					handler: function()
					{
						var keywords = Ext.getCmp('keywordsFilter').getValue();
						var type = Ext.getCmp('typeFilter').getValue();

						intelli.listingFields.oGrid.dataStore.baseParams =
						{
							action: 'get',
							what: keywords,
							type: type
						};

						intelli.listingFields.oGrid.dataStore.reload();						
					}
				},
				'-',
				{
					text: intelli.admin.lang.reset,
					id: 'resetBtn',
					handler: function()
					{
						Ext.getCmp('keywordsFilter').reset();
						Ext.getCmp('typeFilter').setValue('');

						intelli.listingFields.oGrid.dataStore.baseParams =
						{
							action: 'get',
							what: '',
							type: ''
						};

						intelli.listingFields.oGrid.dataStore.reload();
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
					store: intelli.listingFields.pagingStore,
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
		/* add edit link */
		this.columnModel.setRenderer(9, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
		});

		/* add remove link */
		this.columnModel.setRenderer(10, function(value, metadata)
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
		/* 
		 * Events
		 */

		/* Edit fields */
		intelli.listingFields.oGrid.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.listingFields.vUrl,
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
		intelli.listingFields.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				intelli.listingFields.oGrid.saveGridState();

				window.location = 'controller.php?file=listing-fields&do=edit&id='+ record.json.id;
			}

			if('remove' == fieldName)
			{
				// don't allow to remove current admin
				if(0 == data)
				{
					return false;
				}

				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_listing_field,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.listingFields.vUrl,
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
		intelli.listingFields.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('removeBtn').enable();
		});

		intelli.listingFields.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('removeBtn').disable();
			}
		});

		/* remove button action */
		Ext.getCmp('removeBtn').on('click', function()
		{
			var rows = intelli.listingFields.oGrid.grid.getSelectionModel().getSelections();
			var ids = new Array();

			for(var i = 0; i < rows.length; i++)
			{
				ids[i] = rows[i].json.id;
			}

			Ext.Msg.show(
			{
				title: intelli.admin.lang.confirm,
				msg: intelli.admin.lang.are_you_sure_to_delete_this_listing_field,
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				fn: function(btn)
				{
					if('yes' == btn)
					{
						Ext.Ajax.request(
						{
							url: intelli.listingFields.vUrl,
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

								intelli.listingFields.oGrid.grid.getStore().reload();

								Ext.getCmp('removeBtn').disable();
							}
						});
					}
				}
			});
		});

		/* Paging panel event */
		Ext.getCmp('pgnPnl').on('change', function(field, new_value, old_value)
		{
			intelli.listingFields.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.listingFields.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.listingFields.oGrid.grid.getStore().reload();
		});
	}
});

Ext.onReady(function()
{
	if(Ext.get('box_fields'))
	{
		intelli.listingFields.oGrid = new intelli.exGrid({url: intelli.listingFields.vUrl});

		/* Initialization grid */
		intelli.listingFields.oGrid.init();
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
			border: false,
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
			checked: ('' == categories || intelli.inArray('0', categories)) ? true : false
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
		
		// render the tree
		tree.render();
		
		if(categories.length > 1)
		{
			root.expand();
		}
	}

	/*
	 * Searchable event handler
	 */
	$("input[name='searchable']").change(function()
	{
		var action = ('1' == $(this).val()) ? 'block' : 'none';
		var type = $("#type").val();

		if('block' == action)
		{
			//$("#fulltext_search").removeAttr("disabled");
			$("#fulltext_search").prop("disabled", '');
			//$("#showAs").removeAttr("disabled");
			$("#showAs").prop("disabled", '');
			$("#search_section").fadeIn("slow");

			if(intelli.inArray(type, ['text', 'textarea']))
			{
				$("#fulltext_search_zone").show();
			}

			if('number' == type)
			{
				$('#number').show();
			}
		}
		else
		{
			//$("#fulltext_search").attr("disabled", "disabled");
			$("#fulltext_search").prop("disabled", "disabled");
			//$("#showAs").attr("disabled", "disabled");
			$("#showAs").prop("disabled", "disabled");
			$("#search_section").fadeOut("slow");

			$("#fulltext_search_zone").hide();

			if('number' == type)
			{
				$('#number').hide();
			}
		}
	});
	
	function thumbInit(enable)
	{
		if (enable)
		{
			$.each(fields, function(k, v)
			{
				state[v] = $('[name="' + v + '"]').val();
			});

			$("input[name='file_prefix']").val("thumb_");
			$("input[name='file_prefix']").prop("disabled", "disabled");
			$("input[name='image_title_length']").prop("disabled", "disabled");
			$("input[name='image_width']").val(120);
			$("input[name='image_height']").val(90);
			$("input[name='thumb_width']").val(0);
			$("input[name='thumb_width']").prop("disabled", "disabled");
			$("input[name='thumb_height']").val(0);
			$("input[name='thumb_height']").prop("disabled", "disabled");
			$("select[name='resize_mode']").val('crop');
			$("select[name='resize_mode']").prop("disabled", "disabled");
		}
		else
		{
			$("input[name='file_prefix']").prop("disabled", "");
			$("input[name='image_title_length']").prop("disabled", "");
			$("input[name='image_width']").prop("disabled", "");
			$("input[name='image_height']").prop("disabled", "");
			$("input[name='thumb_width']").prop("disabled", "");
			$("input[name='thumb_height']").prop("disabled", "");
			$("select[name='resize_mode']").prop("disabled", "");

			$.each(fields, function(k, v)
			{
				$('[name="' + v + '"]').val(state[v]);
			});
		}
	}
	
	var fields = ['file_prefix', 'image_width', 'image_height', 'thumb_width', 'thumb_height', 'resize_mode', 'pic_type'];
	var state = new Array();

	//initialization for instead_thumbnail
	if ($("input[name='instead_thumbnail']:checked").val() == 1)
	{
		thumbInit(true);
	}
	
	//instead_thumbnail handler
	$("#instead_thumbnail_yes, #instead_thumbnail_no").click(function()
	{
		var checked = (1 == $(this).val()) ? true : false;

		thumbInit(checked);
	});

	/*
	 * Field types event handler
	 */
	if('' != $("#type").val())
	{
		var type = $("#type").val();

		type = ('combo' == type || 'radio' == type || 'checkbox' == type) ? 'multiple' : type;

		$('#' + type).css('display', 'block');
		
		if(type == 'checkbox')
		{
			$("#check_all_option").show();
		}

		if(intelli.inArray(type, ['text', 'textarea']))
		{
			$('#fulltext_search_zone').show();
		}

		//if('multiple' == type && $("#searchable1").attr("checked"))
		if('multiple' == type && $("#searchable1").prop("checked"))
		{
			//$("#showAs").attr("disabled", "");
			$("#showAs").prop("disabled", "");
			
			$("input[name*='any_meta']").each(function()
			{
				//$(this).attr("disabled", "");
				$(this).prop("disabled", "");
			});
		}
	}

	$("#type").change(function()
	{
		var type = $(this).val();

		$('#multiple').css('display', 'none');
		$('#text').css('display', 'none');
		$('#textarea').css('display', 'none');
		$('#storage').css('display', 'none');
		$('#image').css('display', 'none');
		$('#number').css('display', 'none');
		$('#pictures').css('display', 'none');
		$("#check_all_option").hide();
	
		if(type != '' && intelli.inArray(type, ['textarea', 'text', 'number', 'storage', 'image', 'pictures']))
		{
			$('#' + type).css('display', 'block');

			if(1 == $('input[name="searchable"]').val() && intelli.inArray(type, ['text', 'textarea']))
			{
				$("#fulltext_search_zone").show();
			}
			else
			{
				$("#fulltext_search_zone").hide();
			}
		}
		else if('combo' == type || 'radio' == type || 'checkbox' == type)
		{
			$('#multiple').css('display','block');
			$("#fulltext_search_zone").hide();

			if (type == 'checkbox')
			{
				$("#check_all_option").show();
			}
		}

		return true;
	});

	$("#type").change();

	$("a[class*='actions']").each(function()
	{
		$(this).click(function()
		{
			var action = $(this).attr("class").split("_")[1];

			var type = $("#type").val();
			var val = $(this).siblings("input[name='lang_values[" + intelli.config.lang + "][]']").val();
			var defaultVal = $('#multiple_default').val();
			var allDefault = defaultVal.split('|');

			if('removeItem' == action)
			{
				var td = $(this).parent().parent();
				var div = td.children("div");
				
				$(this).parent().remove();

				addArrows(td, div);
			}

			if('setDefault' == action)
			{
				if('' != val)
				{
					if('checkbox' == type)
					{
						if('' != defaultVal)
						{
							var valOut = new Array();

							if(!intelli.inArray(val, allDefault))
							{
								allDefault[allDefault.length++] = val;
							}

							$('#multiple_default').val(allDefault.join("|"));
						}
						else
						{
							$('#multiple_default').val(val);
						}
					}
					else
					{
						$('#multiple_default').val(val);
					}
				}
			}
			
			if('removeDefault' == action)
			{
				if('' != defaultVal)
				{
					if(allDefault.length > 1)
					{
						var valOut = new Array();			

						for(i = 0; i < allDefault.length; i++)
						{
							if(allDefault[i] != val)
							{
								valOut[valOut.length] = allDefault[i];
							}
						}
						
						$('#multiple_default').val(valOut.join("|"));						
					}
					else if(defaultVal == val)
					{
						$('#multiple_default').val("");
					}
				}
			}

			if('removeItem' == action)
			{
				$(this).parent().remove();

				if('' != defaultVal)
				{
					if(allDefault.length > 1)
					{
						var valOut = new Array();			

						for(i = 0; i < allDefault.length; i++)
						{
							if(allDefault[i] != val)
							{
								valOut[valOut.length] = allDefault[i];
							}
						}
						
						$('#multiple_default').val(valOut.join("|"));						
					}
					else if(defaultVal == val)
					{
						$('#multiple_default').val("");
					}
				}
			}

			return false;
		});
	});
	
	$("#add_two_items").click(function()
	{
		var clone = $('#value_two_items').clone(true);

		clone.css("display", "block");

		clone.find("input").each(function()
		{
			$(this).val('');
		});

		clone.insertBefore(this);
		
		return false;
	});

	addArrows();

	$("#add_item").click(function()
	{
		var clone = $('#value_item').clone(true);

		clone.css("display", "block");

		clone.find("input").each(function()
		{
			$(this).val('');
		});

		clone.insertBefore(this);

		addArrows();

		return false;
	});

	// select box for resize mode
	$("select[name='resize_mode']").change(function()
	{
		$("span.option_tip").each(function()
		{
			$(this).css("display", "none");
		});

		$("#resize_mode_tip_" + $(this).val()).css("display", "block");
	});

	function addArrows()
	{
		var td = $('td.td_items');
		var div = td.children('div');

		if (div.length > 1)
		{
			div.each(function(i, k)
			{
				if (0 != i)
				{
					$(this).find('a.arrow_up').css('visibility', 'visible');
				}

				if (i != div.length-1)
				{
					$(this).find('a.arrow_down').css('visibility', 'visible');
				}
			});

			td.children('div:first').find('a.arrow_up').css('visibility', 'hidden');
			td.children('div:last').find('a.arrow_down').css('visibility', 'hidden');
		}
	}

	$('a.arrow_up').click(function()
	{
		moveElement('prev', $(this).parent());

		return false;
	});

	$('a.arrow_down').click(function()
	{
		moveElement('next', $(this).parent());

		return false;
	});

	function moveElement(dir, el)
	{
		var a = ('prev' == dir) ? 'before' : 'after';
		var move_el = el[dir]();
		var clone_el = el.clone(true);
		
		el.remove();

		move_el[a](clone_el);

		addArrows();
	}

	var resize_mode_tip_id = $("select[name='resize_mode'] option:selected").val();

	$("#resize_mode_tip_" + resize_mode_tip_id).css("display", "block");
	
	// select box for resize mode
	$("select[name='pic_resize_mode']").change(function()
	{
		$("span.option_tip").each(function()
		{
			$(this).css("display", "none");
		});

		$("#pic_resize_mode_tip_" + $(this).val()).css("display", "block");
	});

	var pic_resize_mode_tip_id = $("select[name='pic_resize_mode'] option:selected").val();

	$("#pic_resize_mode_tip_" + resize_mode_tip_id).css("display", "block");

	$("input.numeric").numeric();
});
