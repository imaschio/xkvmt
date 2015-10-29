intelli.plugins.admin_stats = function()
{
	return {
		vUrl: 'controller.php?plugin=admin_stats'
	};
}();

/*
 * Listings By Day of week panel
 */
intelli.plugins.admin_stats.lbdChart = new Ext.chart.ColumnChart(
{
	store: new Ext.data.JsonStore(
	{
		fields: ['days', 'submissions'],
		url: intelli.plugins.admin_stats.vUrl + '&action=getlbd'
	}),
	yField: 'submissions',
	xField: 'days',
	extraStyle:
	{
		xAxis:
		{
			labelRotation: -90
		}
	}
});

intelli.plugins.admin_stats.lbdChart.store.load();

intelli.plugins.admin_stats.lbdPanel = Ext.extend(Ext.Panel,
{
	title: intelli.admin.lang.submissions_by_day,
	autoWidth: true,
	height: 200,
	plugins: Ext.ux.PortletPlugin,
	initComponent: function()
	{
		this.items = intelli.plugins.admin_stats.lbdChart;

		intelli.plugins.admin_stats.lbdPanel.superclass.initComponent.call(this);
	}
});

Ext.reg('blbd', intelli.plugins.admin_stats.lbdPanel);

/*
 * Listings By Month panel
 */
intelli.plugins.admin_stats.lbmChart = new Ext.chart.ColumnChart(
{
	store: new Ext.data.JsonStore(
	{
		fields: ['month', 'submissions'],
		url: intelli.plugins.admin_stats.vUrl + '&action=getlbm'
	}),
	yField: 'submissions',
	xField: 'month',
	extraStyle:
	{
		xAxis:
		{
			labelRotation: -90
		}
	}
});

intelli.plugins.admin_stats.lbmChart.store.load();

intelli.plugins.admin_stats.lbmPanel = Ext.extend(Ext.Panel,
{
	title: intelli.admin.lang.submissions_by_month,
	autoWidth: true,
	height: 200,
	plugins: Ext.ux.PortletPlugin,
	initComponent: function()
	{
		this.items = intelli.plugins.admin_stats.lbmChart;

		intelli.plugins.admin_stats.lbmPanel.superclass.initComponent.call(this);
	}
});

Ext.reg('blbm', intelli.plugins.admin_stats.lbmPanel);

Ext.onReady(function()
{
	Ext.getCmp('ia_portal').addPortlet('blbd', 'left');
	Ext.getCmp('ia_portal').addPortlet('blbm', 'right');
});
