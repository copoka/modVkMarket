modVkMarket.grid.Goods = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'modvkmarket-grid-goods';
	}
	Ext.applyIf(config, {
		url: modVkMarket.config.connector_url,
		fields: this.getFields(config),
		columns: this.getColumns(config),
		tbar: this.getTopBar(config),
		sm: new Ext.grid.CheckboxSelectionModel(),
		baseParams: {
			action: 'mgr/market/getlist'
		},
		listeners: {
			rowDblClick: function (grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateGood(grid, e, row);
			}
		},
		viewConfig: {
			forceFit: true,
			enableRowBody: true,
			autoFill: true,
			showPreview: true,
			scrollOffset: 0,
		},
		paging: true,
		remoteSort: true,
		autoHeight: true,
	});
	modVkMarket.grid.Goods.superclass.constructor.call(this, config);

	// Clear selection on grid refresh
	this.store.on('load', function () {
		if (this._getSelectedIds().length) {
			this.getSelectionModel().clearSelections();
		}
	}, this);
};
Ext.extend(modVkMarket.grid.Goods, MODx.grid.Grid, {
	windows: {},

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = modVkMarket.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuGood(menu);
	},

	clearMarket: function (act, btn, e) {
        var thisGrid = this;
        Ext.MessageBox.confirm(
            _('modvkmarket_market_clear'),
            _('modvkmarket_market_clear_confirm'),
            function(config){
                var w = new modVkMarket.window.Console({
                    'register'  : 'mgr'
                    ,baseParams:{
                        action: 'mgr/market/clear'
                    }
                    ,scope: thisGrid
                }).show();

				w.log({message: _('modvkmarket_market_clear_start'), level: 3});

                w.on('complete', function(){
                    this.scope.refresh();
                });
            }
        );
		return true;
	},

    syncMarket: function (act, btn, e) {
        var thisGrid = this;
        Ext.MessageBox.confirm(
            _('modvkmarket_market_sync'),
            _('modvkmarket_market_sync_confirm'),
            function(config){
                var w = new modVkMarket.window.Console({
                    'register'  : 'mgr'
                    ,baseParams:{
                        action: 'mgr/market/sync'
                    }
                    ,scope: thisGrid
                }).show();

				w.log({message: _('modvkmarket_market_sync_start'), level: 3});

                w.on('complete', function(){
                    this.scope.refresh();
                });
            }
        );
        return true;
    },

	getFields: function (config) {
		var fields =  ['id', 'title', 'thumb_photo', 'description', 'price', 'views_count', 'likes', 'actions', 'url', 'group_id'];
		return fields;
	},

	getColumns: function (config) {
		var columns = [];
		var add = {
			id: {
				width: 50,
				sortable: false
			},
			thumb_photo: {
				sortable: false,
				width: 100,
				renderer: function(value){
					return '<img src="' + value + '" width="100" />';
				}
			},
			title: {
				sortable: false,
				width: 150,
				renderer: function(value, metaData, record){
					return '<a href="' + record.get('url') + '" target="_blank">'+value+'</a>';
				}
			},
			description: {
				sortable: false
			},
            price: {
                sortable: false,
                renderer: function(value){
                    return value.text;
                }
            },
			likes: {
				width: 25,
				sortable: false,
                renderer: function(value){
                    return value.count;
                }
			},
			views_count : {
				width: 25,
				sortable: false
			},
			/*
			actions: {
				width: 25,
				sortable: false,
				renderer: modVkMarket.utils.renderActions,
				id: 'actions'
			}*/
			group_id : {
				width: 80,
				sortable: false
			},
		};

		for (var field in add) {
			if (add[field]) {
				Ext.applyIf(add[field], {
					header: _('modvkmarket_header_' + field),
					tooltip: _('modvkmarket_tooltip_' + field),
					dataIndex: field
				});
				columns.push(add[field]);
			}
		}
		return columns;
	},

	getTopBar: function (config) {
		return [ '->', {
			text: '<i class="icon icon-refresh"></i>&nbsp;' + _('modvkmarket_action_sync'),
			handler: this.syncMarket,
			scope: this
		}, {
            text: '<i class="icon icon-remove"></i>&nbsp;' + _('modvkmarket_action_clear'),
            handler: this.clearMarket,
            scope: this
        }];
	},

	onClick: function (e) {
		var elem = e.getTarget();
		if (elem.nodeName == 'BUTTON') {
			var row = this.getSelectionModel().getSelected();
			if (typeof(row) != 'undefined') {
				var action = elem.getAttribute('action');
				if (action == 'showMenu') {
					var ri = this.getStore().find('id', row.id);
					return this._showMenu(this, ri, e);
				}
				else if (typeof this[action] === 'function') {
					this.menu.record = row.data;
					return this[action](this, e);
				}
			}
		}
		return this.processEvent('click', e);
	},

	_getSelectedIds: function () {
		var ids = [];
		var selected = this.getSelectionModel().getSelections();

		for (var i in selected) {
			if (!selected.hasOwnProperty(i)) {
				continue;
			}
			ids.push(selected[i]['id']);
		}

		return ids;
	},

	_doSearch: function (tf, nv, ov) {
		this.getStore().baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	},

	_clearSearch: function (btn, e) {
		this.getStore().baseParams.query = '';
		Ext.getCmp(this.config.id + '-search-field').setValue('');
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
});
Ext.reg('modvkmarket-grid-goods', modVkMarket.grid.Goods);
