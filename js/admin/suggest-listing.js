intelli.suggestListing = function()
{
	var vUrl = 'controller.php?file=suggest-listing';

	return {
		vUrl: vUrl,
		categoriesTree: null,
		crossedTree: null,
		categoriesWin: null,
		crossedWin: null,
		pagingStore: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [['10', '10'],['20', '20'],['30', '30'],['40', '40'],['50', '50']]
		})
	};
}();

Ext.onReady(function()
{
	$("#f_meta_description").charCount({limit: 0, allowed: 160, counterText: _t('recommended_length')});
	$("#f_meta_keywords").charCount({allowed: 80, counterText: _t('recommended_length')});

	if (Ext.get('date'))
	{
		new Ext.form.DateField(
		{
			allowBlank: false,
			format: 'Y-m-d',
			applyTo: 'date',
			value: Ext.get('date').getValue()
		});
	}

	if (Ext.get('time'))
	{
		new Ext.form.TimeField(
		{
			format: 'h:i:s',
			applyTo: 'time',
			increment: 30,
			value: Ext.get('time').getValue()
		});
	}

	if (Ext.get('expire'))
	{
		new Ext.form.DateField(
		{
			allowBlank: false,
			format: 'Y-m-d',
			applyTo: 'expire',
			value: Ext.get('expire').getValue()
		});
	}

	$('#get_current_pagerank').click(function()
	{
		set_pagerank();

		return false;
	});

	$('#f_url').blur(function()
	{
		set_pagerank();

		return false;
	});

	if (intelli.config.autometafetch)
	{
		$('#fetch_meta').click(function()
		{
			metaFetch();

			return false;
		});
	}

	function metaFetch()
	{
		var url = $('#f_url').val();

		$.get(intelli.config.esyn_url + 'ajax.php', { action: 'fetchmetas', url: url }).done(function(data)
		{
			if (CKEDITOR.instances['description'])
			{
				CKEDITOR.instances['description'].insertText(data);
			}
			else
			{
				$('#f_description').append(' ' + data);
			}
		});
	}

	$("input[name='title'], input[name='path']").each(function()
	{
		$(this).blur(function()
		{
			fillUrlBox();
		});
	});

	function fillUrlBox()
	{
		var title = ('' == $("input[name='title_alias']").val()) ? $("input[name='title']").val() : $("input[name='title_alias']").val();

		if ('' != title && $("#category_id").length > 0)
		{
			$.get(intelli.suggestListing.vUrl, {action: 'getlistingurl', category_id: $("#category_id").val(), title: title}, function(data)
			{
				var data = eval('(' + data + ')');

				if('' != data.data)
				{
					$("#listing_url").text(data.data);
					$("#listing_url_box").fadeIn();
				}
				else
				{
					$("#listing_url_box").hide();
				}
			});
		}
		else
		{
			$("#listing_url_box").hide();
		}
	}

	function set_pagerank()
	{
		var url = $('#f_url').val().replace(/^(https?):\/\//i, '');
		url = url.split('/');
		url = url[0];

		$.get(intelli.config.esyn_url + 'ajax.php', { action: 'getpagerank', url: url } ).done(function(data)
		{
			$('#pagerank option[value='+ data +']').prop("selected", true);
		});
	}

	$('#delete').click(function()
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
					$('input[name="do"]').val('delete');

					$('form[name="suggest_listing"]').submit();
				}
			}
		});
		
		return false;
	});

	$("#change_category").click(function()
	{
		intelli.suggestListing.categoriesTree = new Ext.tree.TreePanel({
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
			})
		});
	
		// add a tree sorter in folder mode
		new Ext.tree.TreeSorter(intelli.suggestListing.categoriesTree, {folderSort: false});
		 
		// set the root node
		var root = new Ext.tree.AsyncTreeNode({
			text: 'ROOT', 
			id: '0'
		});
		
		intelli.suggestListing.categoriesTree.setRootNode(root);
			
		root.expand();

		intelli.suggestListing.categoriesTree.on('render', function()
		{
			var path = Ext.get('category_parents').getValue();

			this.expandPath(path);
		});

		var id = Ext.get('category_id').getValue();

		function onAppend(t, p, n)
		{
			if(id == n.id)
			{
				function onParentExpanded()
				{
					t.getSelectionModel().select(n, null, true);
				};

				p.on("expand", onParentExpanded, null, {single: true});
			}
		};
		
		intelli.suggestListing.categoriesTree.on("append", onAppend);

		intelli.suggestListing.categoriesWin = new Ext.Window(
		{
			title: intelli.admin.lang.tree,
			width : 400,
			height : 450,
			modal: true,
			autoScroll: true,
			closeAction : 'hide',
			items: [intelli.suggestListing.categoriesTree],
			buttons: [
			{
				text : intelli.admin.lang.ok,
				handler: function()
				{
					var category = intelli.suggestListing.categoriesTree.getSelectionModel().getSelectedNode();
					var category_url = intelli.config.esyn_url + 'controller.php?file=browse&id=' + category.id;

					$("#parent_category_title_container a").text(category.attributes.text).attr("href", category_url);
					$("#category_id").val(category.id);

					intelli.suggestListing.categoriesWin.hide();
				}
			},{
				text : intelli.admin.lang.cancel,
				handler : function()
				{
					intelli.suggestListing.categoriesWin.hide();
				}
			}]
		});

		intelli.suggestListing.categoriesWin.show();

		return false;
	});

	$("#add_crossed").click(function()
	{
		var category_id = $("#category_id").val();
		var crossed = $("#multi_crossed").val().split('|');

		intelli.suggestListing.crossedTree = new Ext.tree.TreePanel(
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
		new Ext.tree.TreeSorter(intelli.suggestListing.crossedTree, {folderSort: false});
		 
		// set the root node
		var root = new Ext.tree.AsyncTreeNode({
			text: 'ROOT', 
			id: '0'
		});

		intelli.suggestListing.crossedTree.setRootNode(root);
			
		root.expand();

		intelli.suggestListing.crossedTree.on('render', function()
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
		});

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

		intelli.suggestListing.crossedTree.on('checkchange', function(node, checked)
		{
			if(checked && category_id == node.id)
			{
				intelli.admin.alert({title: 'Error', msg: intelli.admin.lang.cross_warning, type: 'error'});
				
				node.ui.checkbox.checked = false;
				node.attributes.checked = false;
			}
		});

		intelli.suggestListing.crossedTree.on("append", onAppend);

		intelli.suggestListing.crossedWin = new Ext.Window(
		{
			title: intelli.admin.lang.tree,
			width : 400,
			height : 450,
			autoScroll: true,
			modal: true,
			closeAction : 'hide',
			items: [intelli.suggestListing.crossedTree],
			buttons: [
			{
				text : intelli.admin.lang.ok,
				handler: function()
				{
					var categories = intelli.suggestListing.crossedTree.getChecked();
					var ids = new Array();
					var category_url = intelli.config.esyn_url + 'controller.php?file=browse&id=';
					var html = '';
					
					if(categories.length > 0)
					{
						html += '<p class="field">' + intelli.admin.lang.crossed_to + ': <br />';

						for(var i = 0; i < categories.length; i++)
						{
							ids[i] = categories[i].id;
							html += '<a href="' + category_url + categories[i].id +'"><b>' + categories[i].text + '</b></a><br />';
						}

						html += '</p>';
					}

					$("#multi_crossed").val(ids.join('|'));
					$("#crossed").html(html);
					
					intelli.suggestListing.crossedWin.hide();
				}
			},{
				text : intelli.admin.lang.cancel,
				handler : function()
				{
					intelli.suggestListing.crossedWin.hide();
				}
			}]
		});

		intelli.suggestListing.crossedWin.show();

		return false;
	});

	$("input[name='assign_account']").each(function()
	{
		if(1 == $(this).val() && $(this).attr("checked"))
		{
			$("#new_account").css('display', 'block');
		}

		if(2 == $(this).val() && $(this).attr("checked"))
		{
			$("#exist_account").css('display', 'block');
		}

		$(this).click(function()
		{
			if(1 == $(this).val())
			{
				$("#new_account").css('display', 'block');
				$("#exist_account").css('display', 'none');
			}

			if(2 == $(this).val())
			{
				$("#new_account").css('display', 'none');
				$("#exist_account").css('display', 'block');
			}

			if(3 == $(this).val())
			{
				$("#new_account").css('display', 'none');
				$("#exist_account").css('display', 'none');
			}

			if(0 == $(this).val())
			{
				$("#new_account").css('display', 'none');
				$("#exist_account").css('display', 'none');
			}
		});
	});

	$('input[name="assign_plan"]').click(function()
	{
		if ('-1' == $(this).val())
		{
			$('#expire_table').hide();
		}
		else
		{
			$('#expire_table').show();
		}

		return true;
	});

	$("a.clear").each(function()
	{
		$(this).click(function()
		{
			var obj = $(this);
			var params = obj.attr('href').split('/');

			var field_name = params[0];
			var listing_id = params[1];
			var image_name = params[2];

			$.get(intelli.suggestListing.vUrl, {field: field_name, id: listing_id, image: image_name, action: 'clear'}, function(data)
			{
				var response = Ext.decode(data);
				var type = response.error ? 'error' : 'notif';
						
				intelli.admin.notifFloatBox({msg: response.msg, type: type, autohide: true});
				
				$("#file_manage").remove();
				
				if(obj.parents("div.image_box").length > 0)
				{
					obj.parents("div.image_box").parent().remove();
				}
			});

			return false;
		});
	});

	var account_ds = new Ext.data.Store(
	{
		proxy: new Ext.data.HttpProxy({url: intelli.suggestListing.vUrl + '&action=getaccounts', method: 'GET'}),
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

	var account_id = '';
	var account_username = '';
	var account_info = $("#accounts_list").text();

	if('' != account_info)
	{
		account_id = account_info.split('|')[0];
		account_username = account_info.split('|')[1];
	}

	$("#accounts_list").html('');

	var account_search = new Ext.form.ComboBox(
	{
		store: account_ds,
		displayField: 'username',
		valueField: 'id',
		allowBlank: false,
		triggeAction: 'all',
		minChars: 1,
		typeAhead: false,
		loadingText: intelli.admin.lang.searching,
		renderTo: 'accounts_list',
		emptyText: intelli.admin.lang.type_account_username,
		hiddenName: 'account',
		value: account_id,
		valueNotFoundText: account_username,
		pageSize: 10,
		width: 200,
		listWidth: 200,
		hideTrigger: true,
		tpl: resultTpl,
		itemSelector: 'div.search-item'
	});

	$("textarea.ckeditor_textarea").each(function()
	{
		if (!CKEDITOR.instances[$(this).attr("id")])
		{
			intelli.ckeditor($(this).attr("id"), {toolbar: 'User', height: '400px'});
		}
	});

	/**
	 * Deep links
	 */
	
	/** get checked plan if it exists **/
	var checked_plan = $("input[name='assign_plan']:checked");

	if(checked_plan.length > 0)
	{
		var id_plan = checked_plan.attr("id").replace("plan_", "");

		$("#deep_links_" + id_plan).css("display", "block");

		$("#visual_options").css("display", "block");

		var option_ids = $('#option_ids_' + id_plan).val();

		$.getJSON(intelli.config.esyn_url + 'ajax.php', {action: 'admin-get-visual-options', option_ids: option_ids}, function(options)
		{
			var template = $("#optionsList").html();
			$("#visual_options").html(_.template(template, {options:options}));
		});
	}

	if (intelli.config.expire_period && '0' != intelli.config.expire_period && checked_plan.length != 1)
	{
		var defaultDate = new Date();
		var period = parseInt(intelli.config.expire_period);

		defaultDate.setDate(defaultDate.getDate() + period);

		var day		= ('0' + (defaultDate.getDate())).slice(-2);
		var month	= ('0' + (defaultDate.getMonth()+1)).slice(-2);
		var year	= defaultDate.getFullYear();

		var expire_date = year + '-' + month + '-' + day;

		$('#expire').val(expire_date);
	}

	$("input[name='assign_plan']").change(function()
	{
		var _this = $(this);

		var currentDate = new Date();
		var period = _this.data('period');
		var expire_notif = _this.data('expire_notif');

		currentDate.setDate(currentDate.getDate() + period);

		var day		= ('0' + (currentDate.getDate())).slice(-2);
		var month	= ('0' + (currentDate.getMonth()+1)).slice(-2);
		var year	= currentDate.getFullYear();

		var expire_date = year + '-' + month + '-' + day;

		$('#expire').val(expire_date);
		$('#expire_notif').val(expire_notif);
	});

	$("input[name='assign_plan']").each(function()
	{
		$(this).click(function()
		{
			var id_plan = $(this).attr("id").replace("plan_", "");

			$("div.deep_links").each(function()
			{
				$(this).css("display", "none");
			});

			$("#deep_links_" + id_plan).css("display", "block");

			if ($('#plan_reset').attr('checked'))
			{
				$("#visual_options").css("display", "none");
			}
			else
			{
				$("#visual_options").css("display", "block");

				var option_ids = $('#option_ids_' + id_plan).val();

				$.getJSON(intelli.config.esyn_url + 'ajax.php', {action: 'admin-get-visual-options', option_ids: option_ids}, function(options)
				{
					var template = $("#optionsList").html();
					$("#visual_options").html(_.template(template, {options:options}));
				});
			}
		});
	});

	/** event for remove deep link button **/
	$("input.remove_deep").each(function()
	{
		$(this).click(function()
		{
			var item = $(this);
			var id_plan = item.attr("id").replace("deep_", "");

			Ext.Msg.show(
			{
				title: intelli.admin.lang.confirm,
				msg: intelli.admin.lang.are_you_sure_to_delete_this_deep_link,
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				fn: function(btn)
				{
					if('yes' == btn)
					{
						Ext.Ajax.request(
						{
							url: intelli.suggestListing.vUrl,
							method: 'GET',
							params:
							{
								action: 'remove_deep',
								'ids[]': id_plan
							},
							failure: function()
							{
								Ext.MessageBox.alert(intelli.admin.lang.error_saving_changes);
							},
							success: function(data)
							{
								item.parent("div.deep_link_box").remove();
							}
						});
					}
				}
			});

			return false;
		});
	});

	// pictures field
	$("div.gallery").find("input[type='button']").each(function()
	{
		$(this).click(function()
		{
			var action = $(this).attr('class').split(" ");

			if('add_img' == action[0])
			{
				addImgItem($(this));
			}
			else
			{
				removeImgItem($(this));
			}
		});
	});

	function addImgItem(btn)
	{
		var clone = btn.parent().clone(true);
		var name = btn.siblings("input[type='file']").attr("name").replace('[]', '');
		var num = $("#" + name + "_num_img").val();

		if(num > 1)
		{
			$('input:file', clone).val('');
			btn.parent().after(clone);
			$("#" + name + "_num_img").val(num - 1);
		}
		else
		{
			alert(intelli.admin.lang.no_more_files);
		}
	}

	function removeImgItem(btn)
	{
		var name = btn.siblings("input[type='file']").attr("name").replace('[]', '');
		var num = $("#" + name + "_num_img").val();

		if (btn.parent().prev().attr('class') == 'gallery' || btn.parent().next().attr('class') == 'gallery')
		{
			btn.parent().remove();
			$("#" + name + "_num_img").val(num * 1 + 1);
		}
	}

	$("a.lightbox").lightBox();
});