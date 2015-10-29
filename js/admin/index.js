intelli.index = function()
{
	var vUrl = 'controller.php?file=index';

	return {
		vUrl: vUrl,
		/*
		 * Listings statuses chart
		 */
		statusesStoreFilter: new Ext.data.SimpleStore(
		{
			fields: ['value', 'display'],
			data : [
				['all', intelli.admin.lang._status_],
				['active', intelli.admin.lang.active],
				['approval', intelli.admin.lang.approval],
				['unconfirmed', intelli.admin.lang.unconfirmed]
			]
		})
	};
}();

Ext.state.Manager.setProvider(new Ext.ux.state.HttpProvider(
{
	url: intelli.index.vUrl,
	readBaseParams: 
	{
		action: 'readState'
	},
	saveBaseParams:
	{
		action: 'saveState'
	},
	autoRead: false
}));

Ext.state.Manager.getProvider().initState(Ext.appState);

if (Ext.get('box_changelog'))
{
	intelli.index.changelogPanel = Ext.extend(Ext.Panel,
	{
		title: 'Changelog',
		contentEl: 'box_changelog',
		plugins: Ext.ux.PortletPlugin,
		listeners:
		{
			'afterrender': function(cmp)
			{
				cmp.el.setStyle("display", "block");
			}
		}
	});

	Ext.reg('bchangelog', intelli.index.changelogPanel);
}

if (Ext.get('box_twitter'))
{
	intelli.index.twtPanel = Ext.extend(Ext.Panel,
	{
		title: intelli.admin.lang.twitter_news,
		contentEl: 'box_twitter',
		plugins: Ext.ux.PortletPlugin,
		listeners:
		{
			'afterrender': function(cmp)
			{
				Ext.get(cmp.contentEl).setStyle("display", "block");
			}
		}
	});

	Ext.reg('btwitter', intelli.index.twtPanel);
}

intelli.index.listingChart = new Ext.chart.PieChart(
{
	store: new Ext.data.JsonStore(
	{
		fields: ['statuses', 'total'],
		url: intelli.index.vUrl + '&action=getlistingschart'
	}),
	height: 200,
	dataField: 'total',
	categoryField: 'statuses',
	extraStyle:
	{
		legend:
		{
			display: 'bottom',
			padding: 5,
			font:
			{
				family: 'Tahoma',
				size: 13
			}
		}
	}
});
intelli.index.listingChart.store.load();

Ext.ux.bChart = Ext.extend(Ext.Panel,
{
	title: intelli.admin.lang.listings_chart,
	plugins: Ext.ux.PortletPlugin,
	initComponent: function()
	{
		this.items = intelli.index.listingChart;

		Ext.ux.bChart.superclass.initComponent.apply(this, arguments);
	}
});
Ext.reg('bchart', Ext.ux.bChart);

intelli.index.statisticsPanel = Ext.extend(Ext.Panel,
{
	title: intelli.admin.lang.statistics,
	contentEl: 'box_statistics',
	listeners:
	{
		'afterrender': function(cmp)
		{
			Ext.get(cmp.contentEl).setStyle("display", "block");
		}
	},
	plugins: Ext.ux.PortletPlugin
});
Ext.reg('statspanel', intelli.index.statisticsPanel);

if (Ext.get('box_fdb'))
{
	intelli.index.fdbPanel = Ext.extend(Ext.Panel,
	{
		title: intelli.admin.lang.submit_feedback,
		contentEl: 'box_fdb',
		listeners:
		{
			'afterrender': function(cmp)
			{
				Ext.get(cmp.contentEl).setStyle("display", "block");
			}
		},
		plugins: Ext.ux.PortletPlugin
	});
	Ext.reg('feedback', intelli.index.fdbPanel);
}

Ext.ux.iaPortal = Ext.extend(Ext.ux.Portal,
{
	border: false,
	id: 'ia_portal',
	stateful: true,
	height: 'auto',
	width: 'auto',
	portlets: new Array(),
	initComponent: function()
	{
		this.items = [
		{
			columnWidth: .50,
			defaults:
			{
				resizeable: false,
				closeable: false,
				style: 'margin: 5px 0 10px 0'
			},
			style: 'padding: 0 10px 0 0'
		},{
			columnWidth:.50,
			defaults:
			{
				resizeable: false,
				closeable: false,
				style: 'margin: 5px 0 10px 0'
			}
		}],
        
		Ext.ux.iaPortal.superclass.initComponent.call(this);
    },

	getState: function()
	{
		var state = [];
		
		for (var i = 0; i < this.items.length; i++)
		{
			var col = this.items.items[i];

			state[i] = [];
			
			for (var j = 0; j < col.items.length; j++)
			{
				var p = col.items.items[j];

				state[i][j] = Ext.applyIf({xtype: p.getXType(), id: p.id}, p.initialConfig);
			}
		}

		return state;
	},

	applyState: function(state, config)
	{
		this.stateful = false;
		
		for (var i = 0; i < state.length; i++)
		{
			var col = this.items.items[i];

			if (col)
			{
				while (col.items && col.items.length > 0)
				{
					col.remove(col.items.items[0]);
				}
				
				for (var j = 0; j < state[i].length; j++)
				{
					if (typeof state[i][j] != 'undefined' && Ext.ComponentMgr.isRegistered(state[i][j].xtype))
					{
						col.add(state[i][j]);
					}
				}
			}
		}
		
		this.stateful = true;
	},

	addPortlet: function(xtype, pos)
	{
		if (!this.existPortlet(xtype))
		{
			if (typeof pos == 'undefined')
			{
				pos = 'left';
			}
			else
			{
				pos = intelli.inArray(pos, ['left', 'right']) ? pos : 'left';
			}

			if (pos == 'left')
			{
				pos = 0;
			}

			if (pos == 'right')
			{
				pos = 1;
			}

			intelli.index.portal.items.items[pos].add({xtype: xtype});
		}
	},

	existPortlet: function(xtype)
	{
		if (typeof xtype == 'undefined')
		{
			return;
		}

		return intelli.inArray(xtype, this.getAllPortlets());
	},

	getAllPortlets: function(cache)
	{
		if (typeof cache == 'undefined')
		{
			cache = true;
		}

		if (this.portlets.length > 0 && cache)
		{
			return this.portlets;
		}

		if (this.portlets.length == 0 || !cache)
		{
			for (var i = 0; i < this.items.length; i++)
			{
				var col = this.items.items[i];

				for (var j = 0; j < col.items.length; j++)
				{
					var p = col.items.items[j];

					this.portlets.push(p.getXType());
				}
			}
		}

		return this.portlets;
	},

	listeners:
	{
		'validatedrop': function(overEvent)
		{
			if (overEvent.columnIndex == 0 && overEvent.position == 0)
			{
				overEvent.status = false;
			}

			return overEvent.status;
		}
	}
});
Ext.reg('iaPortal', Ext.ux.iaPortal);

Ext.onReady(function()
{
	intelli.index.portal = new Ext.ux.iaPortal();

	intelli.index.portal.render('box_panels_content');

	if (Ext.ComponentMgr.isRegistered('btwitter'))
	{
		intelli.index.portal.addPortlet('btwitter', 'left');
	}

	intelli.index.portal.addPortlet('bchart', 'left');

	intelli.index.portal.addPortlet('statspanel', 'right');
	if (Ext.ComponentMgr.isRegistered('bchangelog'))
	{
		intelli.index.portal.addPortlet('bchangelog', 'right');

		$('#box_changelog').show();

		$('#changelog_item').change(function()
		{
			var val = $(this).val();
			$('.changelog_item').hide();
			$('#changelog_'+val).show();
		}).change();
	}

	if (Ext.ComponentMgr.isRegistered('feedback'))
	{
		intelli.index.portal.addPortlet('feedback', 'left');
	}

	// feedback submit form
	$("#submitButton").click(function()
	{
		var subject = $("#subject").val();
		var body = $("#body").val();
		
		if (body != '')
		{
			Ext.Msg.confirm(intelli.admin.lang.confirm, intelli.admin.lang.send_confirm, function(btn, text)
			{
				if (btn == 'yes')
				{
					Ext.Ajax.request(
					{
						url: intelli.index.vUrl,
						params: { action: 'submitrequest', subject: subject, body: body },
						success: function(data)
						{
							var response = Ext.decode(data.responseText);
							intelli.admin.notifBox({msg: response.msg, type: 'notif', boxid: 'feedbackbox', autohide: true});
						}
					});
				}
			});
		}
		else
		{
			intelli.admin.notifBox(
			{
				msg: intelli.admin.lang.body_incorrect,
				type: 'error',
				boxid: 'feedbackbox',
				autohide: true
			});
		}
	});
	
	// feedback clear form
	$("#resetButton").click(function()
	{
		Ext.Msg.confirm(intelli.admin.lang.confirm, intelli.admin.lang.clear_confirm, function(btn, text)
		{
			if (btn == 'yes')
			{
				$("#body").attr("value", "");
			}
		});
		
		return true;
	});

	intelli.index.portal.doLayout();
});