intelli.pages = function()
{
	var vUrl = 'controller.php?file=pages';

	return {
		oGrid: null,
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
			{name: 'plugin', mapping: 'plugin'},
			{name: 'last_updated', mapping: 'last_updated'},
			{name: 'status', mapping: 'status'},
			//{name: 'order', mapping: 'order'},
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
			header: intelli.admin.lang.unique_key, 
			dataIndex: 'name', 
			sortable: true,
			hidden: true,
			width: 350
		},{
			header: intelli.admin.lang.title, 
			dataIndex: 'title', 
			sortable: true,
			width: 300
		},{
			header: intelli.admin.lang.plugin, 
			dataIndex: 'plugin', 
			sortable: true,
			width: 100
		},{
			header: intelli.admin.lang.status, 
			dataIndex: 'status',
			width: 100,
			sortable: true,
			editor: new Ext.form.ComboBox({
				typeAhead: true,
				triggerAction: 'all',
				forceSelection: true,
				lazyRender: true,
				store: intelli.pages.statusesStore,
				displayField: 'display',
				valueField: 'value',
				mode: 'local'
			})
		},{
			header: intelli.admin.lang.last_updated, 
			dataIndex: 'last_updated',
			sortable: true,
			width: 130,
			renderer: intelli.admin.gridRenderDate
		}/*,{
			header: intelli.admin.lang.order,
			dataIndex: 'order',
			sortable: true,
			width: 50,
			editor: new Ext.form.NumberField({
				allowBlank: false,
				allowDecimals: true,
				allowNegative: false
			})
		}*/,{
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

		this.dataStore.setDefaultSort('name');
	},
	init: function()
	{
		this.plugins = new Ext.ux.PanelResizer({
            minHeight: 100
		});

		this.title = intelli.admin.lang.pages;
		this.renderTo = 'box_pages';

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
				intelli.admin.lang.key + ':',
				{
					xtype: 'textfield',
					id: 'searchKey',
					emptyText: intelli.admin.lang.key
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
						proxy: new Ext.data.HttpProxy({url: intelli.pages.vUrl + '&action=getplugins', method: 'GET'}),
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
				},{
					text: intelli.admin.lang.search,
					iconCls: 'search-grid-ico',
					id: 'fltBtn',
					handler: function()
					{
						var key = Ext.getCmp('searchKey').getValue();
						var plugin = Ext.getCmp('pluginFilter').getValue();

						if ('all' != plugin || '' != key)
						{
							intelli.pages.oGrid.dataStore.baseParams = 
							{
								action: 'get',
								key: key,
								plg: plugin
							};

							intelli.pages.oGrid.dataStore.reload();
						}
					}
				},
				'-',
				{
					text: intelli.admin.lang.reset,
					id: 'resetBtn',
					handler: function()
					{
						Ext.getCmp('searchKey').reset();
						Ext.getCmp('pluginFilter').setValue('all');

						intelli.pages.oGrid.dataStore.baseParams = 
						{
							action: 'get',
							key: '',
							plg: ''
						};

						intelli.pages.oGrid.dataStore.reload();
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
					forceSelection: true,
					lazyRender: true,
					width: 80,
					store: intelli.pages.pagingStore,
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
					store: intelli.pages.statusesStore,
					value: 'active',
					displayField: 'display',
					valueField: 'value',
					mode: 'local',
					disabled: true,
					id: 'statusCmb'
				},{
					text: intelli.admin.lang['do'],
					id: 'goBtn',
					iconCls: 'go-grid-ico',
					disabled: true,
					handler: function()
					{
						var rows = intelli.pages.oGrid.grid.getSelectionModel().getSelections();
						var status = Ext.getCmp('statusCmb').getValue();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].json.id;
						}

						Ext.Ajax.request(
						{
							url: intelli.pages.vUrl,
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

								intelli.pages.oGrid.grid.getStore().reload();
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
						var rows = intelli.pages.oGrid.grid.getSelectionModel().getSelections();
						var ids = new Array();

						for(var i = 0; i < rows.length; i++)
						{
							ids[i] = rows[i].json.id;
						}

						Ext.Msg.show(
						{
							title: intelli.admin.lang.confirm,
							msg: (ids.length > 1) ? intelli.admin.lang.are_you_sure_to_delete_selected_pages : intelli.admin.lang.are_you_sure_to_delete_this_page,
							buttons: Ext.Msg.YESNO,
							icon: Ext.Msg.QUESTION,
							fn: function(btn)
							{
								if('yes' == btn)
								{
									Ext.Ajax.request(
									{
										url: intelli.pages.vUrl,
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

											intelli.pages.oGrid.grid.getStore().reload();

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
		this.columnModel.setRenderer(4, function(value, metadata)
		{
			metadata.css = value;

			return value;
		});

		/* add edit link */
		this.columnModel.setRenderer(6, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.edit +'" title="'+ intelli.admin.lang.edit +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/edit-grid-ico.png" />';
		});

		/* add remove link */
		this.columnModel.setRenderer(7, function(value, metadata)
		{
			return '<img class="grid_action" alt="'+ intelli.admin.lang.remove +'" title="'+ intelli.admin.lang.remove +'" src="templates/'+ intelli.config.admin_tmpl +'/img/icons/remove-grid-ico.png" />';
		});
	},
	setEvents: function()
	{
		/*
		 * Events
		 */

		/* activate actions button */
		intelli.pages.oGrid.grid.getSelectionModel().on('rowselect', function()
		{
			Ext.getCmp('statusCmb').enable();
			Ext.getCmp('goBtn').enable();
			Ext.getCmp('removeBtn').enable();
		});

		/* deactivate actions button */
		intelli.pages.oGrid.grid.getSelectionModel().on('rowdeselect', function(sm)
		{
			if(0 == sm.getCount())
			{
				Ext.getCmp('statusCmb').disable();
				Ext.getCmp('goBtn').disable();
				Ext.getCmp('removeBtn').disable();
			}
		});

		/* Edit fields */
		intelli.pages.oGrid.grid.on('afteredit', function(editEvent)
		{
			Ext.Ajax.request(
			{
				url: intelli.pages.vUrl,
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

		/* Remove button event */
		intelli.pages.oGrid.grid.on('cellclick', function(grid, rowIndex, columnIndex)
		{
			var record = grid.getStore().getAt(rowIndex);
			var fieldName = grid.getColumnModel().getDataIndex(columnIndex);
			var data = record.get(fieldName);

			if('edit' == fieldName)
			{
				intelli.pages.oGrid.saveGridState();

				window.location = 'controller.php?file=pages&do=edit&id='+ record.json.id;
			}

			if('remove' == fieldName)
			{
				Ext.Msg.show(
				{
					title: intelli.admin.lang.confirm,
					msg: intelli.admin.lang.are_you_sure_to_delete_this_page,
					buttons: Ext.Msg.YESNO,
					icon: Ext.Msg.QUESTION,
					fn: function(btn)
					{
						if('yes' == btn)
						{
							Ext.Ajax.request(
							{
								url: intelli.pages.vUrl,
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
									Ext.getCmp('removeBtn').disable();

									intelli.pages.oGrid.grid.getStore().reload();
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
			intelli.pages.oGrid.grid.getStore().lastOptions.params.limit = new_value;
			intelli.pages.oGrid.grid.bottomToolbar.pageSize = parseInt(new_value);

			intelli.pages.oGrid.grid.getStore().reload();
		});
	}
});


Ext.onReady(function()
{
	if(Ext.get('box_pages'))
	{
		intelli.pages.oGrid = new intelli.exGrid({url: intelli.pages.vUrl});

		/* Initialization grid */
		intelli.pages.oGrid.init();
	}

	if(1 == $('input[name="external_url"]').val())
	{
		$('#url_field').css("display", "block");
		$('#ckeditor').css("display", "none");
		$('#page_options').css("display", "none");
	}
	else if(0 == $('input[name="external_url"]').val())
	{
		$('#url_field').css("display", "none");
		$('#ckeditor').css("display", "block");
		$('#page_options').css("display", "block");

		$("#ckeditor textarea.ckeditor_textarea").each(function()
		{
			if(!CKEDITOR.instances[$(this).attr("id")])
			{
				intelli.ckeditor($(this).attr("id"), {toolbar: 'User'});
			}
		});
	}

	$('input[name="external_url"]').change(function()
	{
		var val = $(this).val();

		if (1 == val)
		{
			$('#url_field').css("display", "block");
			$('#ckeditor').css("display", "none");
			$('#page_options').css("display", "none");
		}
		else
		{
			fillUrlBox();

			$("#ckeditor textarea.ckeditor_textarea").each(function()
			{
				if(!CKEDITOR.instances[$(this).attr("id")])
				{
					intelli.ckeditor($(this).attr("id"), {toolbar: 'User'});
				}
			});

			$('#url_field').css("display", "none");
			$('#ckeditor').css("display", "block");
			$('#page_options').css("display", "block");
		}
	});
	
	/**
	 * The preview functionality. To display the preview page in the new page add the target attribute
	 */
	$("input[name='preview']").click(function()
	{
		$("#page_form").attr("target", "_blank");
	});

	/**
	 * Remove the target attribute if we save the page
	 */
	$("input[name='save']").click(function()
	{
		$("#page_form").removeAttr("target");
	});

	$("input[name='name'], input[name='custom_url'], #unique_url").each(function()
	{
		$(this).blur(function()
		{
			fillUrlBox();
		});
	});

	var items = [];
	
	if ($('#languages_content').length > 0)
	{
		var pre_lang = $('.pre_lang');

		pre_lang.each(function()
		{
			items.push(
			{
				contentEl: this.id, 
				title: $(this).attr('title'),
				listeners:
				{
					activate: function(tab)
					{
						if(!CKEDITOR.instances['contents['+tab.title+']'])
						{
							intelli.ckeditor('contents['+tab.title+']', {toolbar: 'User' } );
						}
					}
				}
			});
		});
		
		var tabs = new Ext.TabPanel(
		{
			renderTo: 'languages_content',
			activeTab: 0,
			shim: false,
			defaults:
			{
				autoHeight: true
			},
			items: items
		});

		for(var i = pre_lang.length-1; i >= 0; i--)
		{
			tabs.setActiveTab(i);
		}
	}

	function fillUrlBox()
	{
		var params = new Array();
		
		var page_unique_key = $("input[name='name']").val();
		var external_url = !!(1 == $('input[name="inunique_url"]').val());
		var unique_url = $("#unique_url").val();
		var custom_seo_url = $("input[name='custom_url']").val();

		params = {page_unique_key: page_unique_key, external_url: external_url, unique_url: unique_url, custom_seo_url: custom_seo_url, action: 'getpageurl'};

		if (external_url && ('' != unique_url))
		{
			sendQuery(params);
		}
		else if(!external_url && '' != page_unique_key)
		{
			sendQuery(params);
		}
		else
		{
			if ('block' == $("#page_url_box").css('display'))
			{
				$("#page_url_box").fadeOut();
			}
		}
	}

	function sendQuery(params)
	{
		$.get(intelli.pages.vUrl, params, function(data)
		{
			var data = eval('(' + data + ')');

			if('' != data.data)
			{
				$("#page_url").text(data.data);
				$("#page_url_box").fadeIn();
			}
			else
			{
				$("#page_url_box").fadeOut();
			}
		});
	}
});