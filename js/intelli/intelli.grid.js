// selection model
sm = new Ext.grid.CheckboxSelectionModel();

intelli.grid = function(config)
{
	this.grid;
	this.dataStore;
	this.columnModel;
	this.selectionModel;
	this.topToolbar;
	this.bottomToolbar;

	this.plugins = config.plugins || '';

	this.start = config.start || 0;
	this.limit = config.limit || 20;

	this.title = config.title || '';
	this.renderTo = config.renderTo || '';

	this.view = config.view || null;

	this.pgnPnlId = config.pgnPnlId || 'pgnPnl';

	this.setupGrid = function()
	{
		this.grid = new Ext.grid.EditorGridPanel(
		{
			store: this.dataStore,
			colModel: this.columnModel,
			sm: sm,
			height: 530,
			autoWidth: true,
			title: this.title,
			renderTo: this.renderTo,
			frame: true,
			loadMask: true,
			columnLines: true,
			stripeRows: true,
			stateful: true,
			plugins: this.plugins,
			trackMouseOver: true,
			bbar: this.bottomToolbar,
			tbar: this.topToolbar,
			view: this.view
		});
	};

	this.getGrid = function()
	{
		return this.grid;
	};

	this.loadData = function()
	{
		var grid = this.getGrid();
		var name = 'startStore_' + grid.id;
		var state = Ext.state.Manager.getProvider();
		var stateStart = state.get(name, 0);

		var bbar = grid.getBottomToolbar();

		if ('undefined' != typeof bbar)
		{
			var pageStore = 'pageSizeStore_' + grid.id;
			var pageRestore = state.get(pageStore, 0);

			if (pageRestore)
			{
				bbar.pageSize = pageRestore;
				Ext.getCmp(this.pgnPnlId).setValue(pageRestore);

				state.clear(pageStore);
			}
		}
		
		var start = stateStart ? stateStart : this.start;
		var limit = pageRestore ? pageRestore : this.limit;

		state.clear(name);

		this.dataStore.load({params:{start: start, limit: limit}});
	};

	this.saveGridState = function()
	{
		var grid = this.getGrid();
		var state = Ext.state.Manager.getProvider();
		var name = 'startStore_' + grid.id;
		var bbar = grid.getBottomToolbar();

		if ('undefined' != typeof bbar)
		{
			var pageStore = 'pageSizeStore_' + grid.id;

			state.set(pageStore, bbar.pageSize);
		}
		
		state.set(name, grid.store.lastOptions.params.start);
	}
};

intelli.gmodel = function(conf)
{
	var url = conf.url || 'data.php';

	this.checkColumn = sm;
	this.record = null;
	this.reader = null;
	this.proxy = null;
	this.columnModel = null;
	this.dataStore = null;

	this.setupProxy = function()
	{
		return new Ext.data.HttpProxy({url: url, method: 'GET'});
	};

	this.setupDataStore = function()
	{
		this.proxy = this.setupProxy();
		this.reader = this.setupReader();

		this.dataStore = new Ext.data.Store(
		{
			remoteSort: true,
			proxy: this.proxy,
			reader: this.reader
		});

		return this.dataStore;
	};

	this.setupSelectionModel = function()
	{
		var selectionModel = new Ext.grid.CheckboxSelectionModel();

		return selectionModel;
	};
};

// Chrome Width Fix
if (!Ext.isDefined(Ext.webKitVersion))
{
	Ext.webKitVersion = Ext.isWebKit ? parseFloat(/AppleWebKit\/([\d.]+)/.exec(navigator.userAgent)[1], 10) : NaN;
}

/*
 * Box-sizing was changed beginning with Chrome v19. For background information, see:
 * http://code.google.com/p/chromium/issues/detail?id=124816
 * https://bugs.webkit.org/show_bug.cgi?id=78412
 * https://bugs.webkit.org/show_bug.cgi?id=87536
 * http://www.sencha.com/forum/showthread.php?198124-Grids-are-rendered-differently-in-upcoming-versions-of-Google-Chrome&p=824367
 *
 * */
if (Ext.isWebKit && Ext.webKitVersion >= 535.2) { // probably not the exact version, but the issues started appearing in chromium 19
	Ext.override(Ext.grid.ColumnModel, {
		getTotalWidth: function (includeHidden) {
			if (!this.totalWidth) {
				var boxsizeadj = 2;
				this.totalWidth = 0;
				for (var i = 0, len = this.config.length; i < len; i++) {
					if (includeHidden || !this.isHidden(i)) {
						this.totalWidth += (this.getColumnWidth(i) + boxsizeadj);
					}
				}
			}
			return this.totalWidth;
		}
	});

	Ext.onReady(function()
	{
		Ext.get(document.body).addClass('ext-chrome-fixes');
		Ext.util.CSS.createStyleSheet('@media screen and (-webkit-min-device-pixel-ratio:0) {.x-grid3-cell{box-sizing: border-box !important;}}', 'chrome-fixes-box-sizing');
	});
}